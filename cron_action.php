<?php

/* cron script */
define( '_VALID_MOS', 1 );
define( '_JEXEC', 1 );
//Change the password in the next line
define('AUCTIONS_CRON_PASSWORD','wmb0824');

if(file_exists("../../libraries/joomla/version.php"))
{
    define('JOOMLA_VERSION',5);
}else{
    define('JOOMLA_VERSION',1);
}

if(JOOMLA_VERSION=="1"){
	include_once( '../../globals.php' );
	require_once( '../../configuration.php' );
	require_once( '../../includes/joomla.php' );
}
else{
	define( 'DS', DIRECTORY_SEPARATOR );
	define('JPATH_BASE', str_replace("components".DS."com_bids","",dirname(__FILE__)));
	require_once( '../../includes/defines.php' );
	require_once ( '../../includes/framework.php' );
	$mosConfig_live_site = JPATH_SITE;
	$mainframe =& JFactory::getApplication('site');

	$mainframe->initialise();

	JPluginHelper::importPlugin('system');

	JDEBUG ? $_PROFILER->mark('afterInitialise') : null;
	$mainframe->triggerEvent('onAfterInitialise');

	global $mosConfig_live_site;
	$mosConfig_live_site = str_replace("/components/com_bids","", $mosConfig_live_site);

}
require_once( 'bids.class.php' );
require_once( 'options.php' );
require_once( 'bids.payment.php' );

session_name( md5( $mosConfig_live_site ) );
session_start();
@set_time_limit(0);
@ignore_user_abort(true);

if(php_sapi_name()=='cli')
{
    $argv=$_SERVER['argv'];
    $argc=$_SERVER['argc'];
    if ($argc > 0)
    {
      for ($i=1;$i < $argc;$i++)
      {
        parse_str($argv[$i],$tmp);
        $_REQUEST = array_merge($_REQUEST, $tmp);
      }
    }
}

$pass = mosGetParam($_REQUEST,'pass','');
$daily= mosGetParam($_REQUEST,'daily','');
$opendate = mosGetParam($_REQUEST,'opendate',0);
if($pass !== AUCTIONS_CRON_PASSWORD){
    die ("AUTH ERROR");
}
if($opendate == 9){
	echo $query = "select cp.id as id from #__comprofiler cp JOIN #__users u ON cp.user_id = u.id WHERE gid = 21";
	$database->setQuery($query);
	$profIds = $database->loadResult();
	
	$query = "UPDATE #__comprofiler SET close_offer = 0 WHERE id = ".$profIds;
	$database->setQuery($query);
	$database->query();
	
	$query = "UPDATE #__bid_auctions SET close_offer = 0";
	$database->setQuery($query);
	$database->query();
	print_r($database);
	exit;

}
if($opendate) {
	echo $query = "select cp.id as id from #__comprofiler cp JOIN #__users u ON cp.user_id = u.id WHERE gid = 21";
	$database->setQuery($query);
	$profIds = $database->loadResult();

	//Added for making the auction status ended
	echo $query = "UPDATE #__comprofiler SET close_offer = 0 WHERE id = ".$profIds;
	$database->setQuery($query);
	$database->query();
	exit;
}

// -------------- EXPIRED AUCTIONS  ----------- //
	//$query = "SELECT a.* from #__bid_auctions a where date_diff(NOW()- '2 days') > a.end_date and a.close_offer != 1 and a.published = 1 and a.close_by_admin!=1";
	$query = "SELECT a.* from #__bid_auctions a
			  where NOW() > a.end_date and a.close_offer != 1 and a.published = 1 and a.close_by_admin!=1";
	$database->setQuery($query);
	$rows = $database->loadObjectList();
	if(count($rows)>0){
		$row = new mosBidOffers( $database );
		foreach ($rows as $r){
    		// Close expired auctions
    		$row->load( $r->id );
    		$row->close_offer=1;
    		$row->closed_date=date("Y-m-d H:i:s",time());
    		if ($row->store()){
       			//Notify users;
        		$query = "select distinct a.userid from #__bids a where a.id_offer=$row->id";
        		$database->setQuery($query);
        		$ids = $database->loadResultArray();
                $id_string=implode(',',$ids);
                //get all bidders
                if (count($ids)>0){
        			$query = "select u.* from #__users u
        					  where u.id in ( $id_string )";
        			$database->setQuery($query);
        			$mails = $database->loadObjectList();
        			$row->SendMails($mails,'bid_closed'); //Notyfy Bidders abour closed Auction

        			//delete bidders from watchlist, to avoid double notification
        			$query = "delete from #__bid_watchlist a where a.auctionid='$r->id' and userid in ($id_string)";
        			$database->setQuery($query);
        			$database->query();
					$query = "select cp.id as id from #__comprofiler cp JOIN #__users u ON cp.user_id = u.id WHERE gid = 21";
					$database->setQuery($query);
	        		$profIds = $database->loadResult();

					//Added for making the auction status ended
        			echo $query = "UPDATE #__comprofiler SET close_offer = 1 WHERE id = ".$profIds;
        			$database->setQuery($query);
        			$database->query();
                }

        		//Notify  Watchlist, Clean Watchlist
        		$query = "select a.userid from #__bid_watchlist a where a.auctionid=$r->id";
        		$database->setQuery($query);
        		$ids = $database->loadResultArray();
                $id_string=implode(',',$ids);

                if (count($ids)>0){
        			$query = "select u.* from #__users u
        					  where u.id in ($id_string)";
        			$database->setQuery($query);
        			$mails = $database->loadObjectList();
        			$row->SendMails($mails,'bid_watchlist_closed'); //Notify Watchlist
                }
        	    $query = "delete from #__bid_watchlist a where a.auctionid=$r->id";
        		$database->setQuery($query);
        		$database->query();
        		//Choose a winner
        		if($row->automatic){
    				$row->ChooseWinner();//automatic choose winner
    			}else {
    				$usr=new mosUser($database);
    				$usr->load($row->userid);
    				$row->SendMails(array($usr),'bid_choose_winner');// Notify owner to choose winner
    			}
    		}
    		
	   }
	}
// END EXPIRED AUCTION

// BEGIN DAILY TASKS --> notifications , watchlist
    if ($daily){
        //Notify upcoming expirations
    	$query = "SELECT a.* from #__bid_auctions a
    		 	  where now()>=DATE_ADD(end_date,INTERVAL -1 DAY) and a.close_offer != 1 and published = 1 and a.close_by_admin!=1";
    	$database->setQuery($query);
    	$rows = $database->loadObjectList();

    	$auction = new mosBidOffers($database);
    	$usr=new mosUser($database);

    	if (count($rows))
        	foreach ($rows as $row){
                	$auction->load($row->id);
        			$usr->load($row->userid);
        			$auction->SendMails(array($usr),'bid_your_will_expire'); // Notify Owner that his auction will soon expire

        			$query = "SELECT u.* from #__users u
        					left join #__bid_watchlist w on u.id = w.userid
        					where w.auctionid = $row->id";

        			$database->setQuery($query);
        			$watchlist_mails = $database->loadObjectList();
        			$auction->SendMails($watchlist_mails,'bid_watchlist_will_expire'); //Notify Users in watchlist that an auction will expire
        	}

    	//Close all auctions without a parent user (deleted users?)
    	$query = "update #__bid_auctions a left join #__users b on a.userid=b.id set close_by_admin = 1,closed_date=now() where b.id is null";
    	$database->setQuery($query);
    	$database->query();
        //delete Very old auctions (past Archived time)
    	$interval =  intval(bid_opt_archive);
    	if ($interval>0){
        	$query = "SELECT id
        				FROM #__bid_auctions
        				WHERE NOW( ) > DATE_ADD( closed_date, INTERVAL $interval
        				MONTH )
        				AND (close_offer =1
        				or  close_by_admin=1)";
            $database->setQuery($query);
        	$idx = $database->loadResultArray(); //select auctions that have to be purged

            $row = new mosBidOffers( $database );
            if (count($idx))
        		foreach ($idx as $id){
        		    $row->delete($id);
        		}
    	}
    }
//plugin tasks
    /*@var $payment mosBidsPayment*/
    $payment=&mosBidsPayment::getInstance();

    $payment->processCron($daily);



?>