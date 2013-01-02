<?php
/**
* @package AuctionsFactory 
* @version 1.6.0
* @copyright www.thefactory.ro
* @license: commercial
* @modified: 28-05-2009 (dd-mm-YYYY)
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );
global $id,$cb_fieldmap, $mainframe,$lang,$bid_acl;

require_once( $mainframe->getPath( 'class',$option ) );

require_once(BIDS_COMPONENT_PATH.'/options.php');
require_once(BIDS_COMPONENT_PATH.'/bids.functions.php');
require_once(BIDS_COMPONENT_PATH.'/bids.acl.php');
require_once(BIDS_COMPONENT_PATH.'/bids.payment.php');
// load the html drawing class
require_once( $mainframe->getPath( 'front_html',$option ) );

//force load of Joomla Javascript
$mainframe->set( 'joomlaJavascript', 1 );
if (!$task) $task='listauctions';

//Load user Settings
detectIntegration();
loadLanguageFile();

if(JOOMLA_VERSION==5){
	/**  Added in Auction Factory 05.06.09 */
	$user = &JFactory::getUser();
	$acl = & JFactory::getACL();
	$user = & JFactory::getUser();
	$acl->addACL( 'com_media', 'manage', 'users', $user->usertype );
	$acl->addACL( 'com_media', 'popup', 'users', $user->usertype );
	/**  Added in Auction Factory 05.06.09 */
}

$id = intval( mosGetParam( $_REQUEST ,'id', 0 ) ); //bid id

$bid_acl=new mosBidACL();
if (!$bid_acl->acl_check($task)) return; //various ACL checks

/*@var $payment mosBidsPayment*/
$payment=&mosBidsPayment::getInstance();

if ($task=='purchase' || $task=='payment'  ){
	$payment->process();
	return ;
}
if ($payment->checktask($task,$_REQUEST)){
	//if the payment plugin
	// handles the task then no further handling needed
	return ;
}

$_SESSION['lastpicture'] = isset($_SESSION['lastpicture'])?$_SESSION['lastpicture']:'listauctionspicture';
switch( $task ) {
	//========================== BIDS ========================================
	case 'debug':
		 //echo "current" .$currdatetest = date("m/d/y H:i:s");
		 //echo "auctionDatetoIso ";print_r(auctionDatetoIso($currdatetest));
		 //phpinfo();
		 saveBid($option,"263","savebid", 0,  0, 0);
		 exit;
		 break;
	case 'viewbids':
		AuctionDetails( $option,$id );//
		break;
	case 'mybidspicture':
		$_SESSION['lastpicture'] = "mybidspicture";
	case 'mybids':
		listMyBids($option);//
		break;
	case 'mywonbidspicture':
		$_SESSION['lastpicture'] = "mywonbidspicture";
	case 'mywonbids':
		listMyWonBids($option);//
		break;
	case 'bin':
	case 'sendbid':
		print_r($_REQUEST);
		$proxy		= mosGetParam($_REQUEST,'proxy',0);
		$id			= mosGetParam($_REQUEST,'id',0);
		$amount		= mosGetParam($_REQUEST,'amount'.$id,0);
		/*
		* Added to know where the bid comes from
		* det means Details page
		*/
		if(mosGetParam($_REQUEST,'page',"") == "det"){
			$amount		= mosGetParam($_REQUEST,'amount',0);
		}
		if($proxy && $id) {
			$redirect_link = mosRedirect($mosConfig_live_site."/index.php?option=$option&task=editproxyticket&p=$id&v=$amount");
		}else{
			$redirect_link = saveBid($option,$id,$task);
		}
		echo $proxy;
		echo "<br>TODO---->".$redirect_link;
		mosRedirect($redirect_link);
		break;
	case 'testproxy':
		if($_REQUEST['test'] > 0){
			$queryTest = "update `jos_bid_auctions` set proxypluscron = 0, proxycron = 0";
			$database->setQuery($queryTest);
			$database->query();

			$queryTest = "TRUNCATE TABLE `jos_bids` ";
			$database->setQuery($queryTest);
			$database->query();

			$queryTest = "update `jos_proxyplus_bids` SET outbid =0";
			$database->setQuery($queryTest);
			$database->query();
			if($_REQUEST['test'] == 2) {
				$queryTest = "TRUNCATE `jos_proxyplus_bids`";
				$database->setQuery($queryTest);
				$database->query();

				$queryTest = "TRUNCATE `jos_proxyplus_tickets`";
				$database->setQuery($queryTest);
				$database->query();
			}

			$queryTest = "TRUNCATE `jos_bid_proxy`";
			$database->setQuery($queryTest);
			$database->query();

			echo "emp";
			exit;
		}
		break;
	case 'autobid':
		$test = 0;
		$pass = mosGetParam($_REQUEST,'pass','');
		$cron= mosGetParam($_REQUEST,'cron',0);
		$test= mosGetParam($_REQUEST,'test',0);
		if($pass !== AUCTIONS_CRON_PASSWORD && !$cron){
			die ("AUTH ERROR");
		}
		$profile = new mosComProfiler($database);
		$manaInfo = $profile->getManagerInfo();
		//Added for start date
		if($manaInfo->cb_buyerschoice == "Buyer's Choice") {
			$start_date = $manaInfo->cb_startdate;
			$end_date  = $manaInfo->cb_enddate;
		}
		/**************************************************************************
		*JAI TODO  to remove true 
		*/
		if(strtotime($start_date) <= time() || $test) {
			/* commented for testing */
			$proxyPlusBids = array();
			/*
			* ProxyPlus auto bid
			*/
			$proxyPlusBids = array();
			$queryMain = "SELECT DISTINCT id, initial_price FROM  #__bid_auctions where close_offer=0 and published=1 and proxypluscron = 0 and id in (SELECT distinct auction_id FROM #__proxyplus_tickets ppt JOIN #__proxyplus_bids ppb ON (ppt.id = ppb.ticket_id))";
			$database->setQuery($queryMain);
			$rowsMain = $database->loadObjectList();
			$auction = new mosBidOffers($database);
			echo "<pre>";
			//print_r($rowsMain);
			foreach($rowsMain as $rowsMainId) { 
				/*
				 * To avoid changing all places 
				 * Used as it like savebid
				 */
				$id_offer = $rowsMainId->id;

				$price = $rowsMainId->initial_price;
				$queryPrice = "SELECT max(bid_price) FROM #__bids WHERE id_offer = $rowsMainId->id";
				$database->setQuery($queryPrice);
				$priceBids = $database->loadResult();
				$amount = ($price < $priceBids)?$priceBids:$price;

				$auction = new mosBidOffers($database);
				if(!$auction->load($rowsMainId->id)){
					echo bid_does_not_exist;
					return;
				}
					echo "<br>JAI - id=".$rowsMainId->id."AMOUNT = ".$amount;
					//JaiStartH
					$query   = "SELECT lot_desired, ppt.id as id, ppt.userid FROM #__proxyplus_tickets ppt JOIN #__proxyplus_bids ppb ON (ppt.id = ppb.ticket_id) WHERE ppb.auction_id = $rowsMainId->id ORDER BY ppt.userid asc";
					$database->setQuery($query);
					$rowsProxyPlusTickets = $database->loadObjectList();
					$proxyPlusBids = array();
					if(count($rowsProxyPlusTickets) > 0) {
						$countpriorityBids=0;
						foreach($rowsProxyPlusTickets as $rowProxyPlusTickets) {
							echo "<br>".$query   = "SELECT * FROM #__proxyplus_bids AS b WHERE ticket_id = ".$rowProxyPlusTickets->id ." AND outbid = 0 ORDER BY priority, datemodified ASC LIMIT ".$rowProxyPlusTickets->lot_desired;
							$database->setQuery( $query);
							$rowsProxyPlusbids = $database->loadObjectList();
							foreach($rowsProxyPlusbids as $rowProxyPlusbids) {
								if($rowProxyPlusbids->auction_id == $rowsMainId->id) {
									$proxyPlusBids[strtotime($rowProxyPlusbids->datemodified)] = array(
														"id"=>$rowProxyPlusbids->id,
														"ticket_id"=>$rowProxyPlusbids->ticket_id,
														"lot_desired"=>$rowProxyPlusTickets->lot_desired,
														"auction_id"=>$rowProxyPlusbids->auction_id,
														"my_bid"=>$rowProxyPlusbids->my_bid,
														"priority"=>$rowProxyPlusbids->priority,
														"datemodified"=>$rowProxyPlusbids->datemodified,
														"outbid"=>$rowProxyPlusbids->outbid,
														"userid"=>$rowProxyPlusTickets->userid
													  );
									$countpriorityBids++;
								}
							}
						}
					}
					ksort($proxyPlusBids);
					print_r($proxyPlusBids);
					/*
					*  Amount:				 actuall highest amount
					*  userid:				 Original userid
					*  currentuserid:		 Highest bidder
					*  currentBid			 Highest bidder bid, but minimum value he can bid to stay highest bidder
					*  currentHighestEqual   If set to 1, already equal amount is there from some user who 
					*/
					$currentHighestEqual = 0;
					$bidCount = 0; 
				if(isset($proxyPlusBids) && count($proxyPlusBids) > 0) {
					do {
							$currentHighestEqual = 0;
							$loopcount1 = 0;
							foreach($proxyPlusBids as $keyBids=>$rowPlaceBids){
									echo "<br> currentuserid = ".$currentuserid;
									echo "<br> amount = ".$amount;
									echo "<br> rowPlaceBids=";
									print_r($rowPlaceBids);
									$loopcount1++;

									//Same bidder
									if($currentuserid == $rowPlaceBids['userid']) {
										$proxyplusstatus++;
										if(count($proxyPlusBids) <= 1) {
											unset($proxyPlusBids[$keyBids]);
										}
										continue;
									}					
								if($amount == $rowPlaceBids['my_bid']){
									echo "<br> I - $amount == $rowPlaceBids[my_bid]";
									echo "currentuserid $currentuserid, currentBid $currentBid, amount $amount, bidType $bidType";
									$currentBid	   = $amount;
									echo "<br> currentHighestEqual".$currentHighestEqual;
									if(!$currentHighestEqual){
										$currentuserid = $rowPlaceBids['userid'];
									}else {
										$arrOutbid[] = $rowPlaceBids['userid'];
										/*
										*  Commented on 1/28/2010
										*  To avoid the outbid status & causing issues
										*  TODO check the functionality
										*/
											$query1= "UPDATE #__proxyplus_bids SET outbid = 1 WHERE auction_id = $id_offer AND id = ".$rowPlaceBids['id'];
											$database->setQuery($query1);
											$database->query();
										/*
										*/
									}
									$currentHighestEqual = 1;
									$bidType       = "pp";

									unset($proxyPlusBids[$keyBids]);
									$querylots = "SELECT * FROM `#__proxyplus_bids` ppb where ticket_id = ".$rowPlaceBids['ticket_id']." and outbid = 0 ORDER BY priority limit ".($rowPlaceBids['lot_desired']-1).", 1";
									$database->setQuery($querylots);
									$resultslots = $database->loadObjectList();
									$resultslots[0]->userid  = $rowPlaceBids['userid'];
									$proxyplusTOBID[]        = $resultslots[0];
									$proxyplusstatus         = 0;
								}elseif(($amount + findBidIncrement($amount)) <= $rowPlaceBids['my_bid']){
										echo "<br> II - (($amount + findBidIncrement($amount)) <= $rowPlaceBids[my_bid])";
										echo "currentuserid $currentuserid, currentBid $currentBid, amount $amount, bidType $bidType";
										$currentuserid       = $rowPlaceBids['userid'];
										if($currentBid) {
											$currentBid	         = $amount + findBidIncrement($amount);
										}else {
											$currentBid	         = $amount;
										}
										$amount		         = $rowPlaceBids['my_bid'];
										$bidType             = "pp";
										$currentHighestEqual = 1;
								}elseif($amount < $rowPlaceBids['my_bid'] && $amount + findBidIncrement($amount) > $rowPlaceBids['my_bid']){
										echo "<br> III - ($amount < $rowPlaceBids[my_bid] && $amount + findBidIncrement($amount) > $rowPlaceBids[my_bid])";
										echo "currentuserid $currentuserid, currentBid $currentBid, amount $amount, bidType $bidType";
										$currentuserid       = $rowPlaceBids['userid'];
										if($currentBid) {
											$currentBid	         = $amount + findBidIncrement($amount);
										}else {
											$currentBid	         = $amount;
										}
										//added 1/27/2010 
										//Need to check
										$currentBid	         = $rowPlaceBids['my_bid'];
										$amount		         = $rowPlaceBids['my_bid'];
										$bidType			 = "pp";
										$currentHighestEqual = 1;

								}elseif($amount > $rowPlaceBids['my_bid']) {
									echo "<br> IV - ($amount > $rowPlaceBids[my_bid])";

									$query1= "UPDATE #__proxyplus_bids SET outbid = 1 WHERE auction_id = $id_offer AND id = ".$rowPlaceBids['id'];
									$database->setQuery($query1);
									$database->query();
									$arrOutbid[] = $rowPlaceBids['userid'];
									unset($proxyPlusBids[$keyBids]);
									echo "currentuserid $currentuserid, currentBid $currentBid, amount $amount, bidType $bidType";
									if($rowPlaceBids['my_bid'] > $currentBid) {
										$currentBid	   = $rowPlaceBids['my_bid'] + findBidIncrement($rowPlaceBids['my_bid']);
										if($currentBid > $amount) {
											$currentBid = $amount;
										}
									}

									$querylots = "SELECT * FROM `#__proxyplus_bids` ppb where ticket_id = ".$rowPlaceBids['ticket_id']." and outbid = 0 ORDER BY priority limit ".($rowPlaceBids['lot_desired']-1).", 1";
									$database->setQuery($querylots);
									$resultslots = $database->loadObjectList();
									$resultslots[0]->userid = $rowPlaceBids['userid'];
									$proxyplusTOBID[] = $resultslots[0];
									$proxyplusstatus = 0;
									echo "currentuserid $currentuserid, currentBid $currentBid, amount $amount, bidType $bidType";
									continue;
						}
					}
					echo "<br> proxyPlusBids=";
					print_r($proxyPlusBids);

				}while(count($proxyPlusBids) > 0);
				echo "<b>LAST ---currentuserid $currentuserid, currentBid $currentBid, amount $amount, bidType $bidType</b>";
				//JaiEndH
				/*
				 * Bidding Process START
				 */
				$bestBid=null;
				$bestBid = $auction->GetBestBid($id_offer);

				$queryBidUser = "select * from #__bids where  cancel=0 and userid='".$currentuserid."' and id_offer='".$id_offer."' ";
				$database->setQuery($queryBidUser);
				$head_message=new mosBids($database);//new head message

				if(!$database->loadObject($head_message)) {
					$head_message->userid        = $currentuserid;
					$head_message->id_offer      = $id_offer;
					$head_message->bidtype       = $bidType;
					$head_message->bid_price     = $currentBid;
					$head_message->initial_bid   = $currentBid;
					$head_message->modified      = date("Y-m-d H:i:s",time());
					$head_message->accept		 = 0;
					$head_message->cancel		 = 0;
					$head_message->store();

					$owner_user		=	new mosUser($database);
					$owner_user->load($auction->userid);

					if($amount > 0){
						$auction->SendMails($watches,'new_bid_watchlist');
						$auction->SendMails(array($owner_user),'new_bid');

						$userMail	=	new mosUser($database);
						$userMail->load($currentuserid);
						$auction->SendMails(array($userMail),'bid_new_mybid');
						
					}else{
						$auction->SendMails(array($owner_user),'new_message');
					}

					/*
					* Added for sending outbid mails to all
					* Need to check TODO
					*/
					if(count($arrOutbid) > 0){
						foreach($arrOutbid as $keyauctionid=>$outbidUser) {
							$userMail	=	new mosUser($database);
							$userMail->load($outbidUser);
							$auction->SendMails(array($userMail),'bid_outbid');

						}
					}
					/*
					* Old One - Outbid mail only to lastbest bid
					* Commented TODO
					
					if($currentuserid != $bestBid->userid) { 
						$userMail	=	new mosUser($database);
						$userMail->load($bestBid->userid);
						$auction->SendMails(array($userMail),'bid_outbid');
					}
					*/
				}else {
					if($head_message->cancel){
						echo bid_err_was_canceled;
						return;
					}
					if($currentBid >= $head_message->bid_price){
						$head_message->bidtype       = $bidType;
						$head_message->bid_price     = $currentBid;
						$head_message->modified      = date("Y-m-d H:i:s",time());
						$head_message->store();
						
						$owner_user	=	new mosUser($database);
						$owner_user->load($auction->userid);
						if($amount	>	0) {
							$auction->SendMails($watches,'new_bid_watchlist');
							$auction->SendMails(array($owner_user),'new_bid');

							$userMail	=	new mosUser($database);
							$userMail->load($currentuserid);
							$auction->SendMails(array($userMail),'bid_new_mybid');

						}else{
							$auction->SendMails(array($owner_user),'new_message');
						}
					/*
					* Added for sending outbid mails to all
					* Need to check TODO
					*/
					foreach($arrOutbid as $keyauctionid=>$outbidUser) {
						$userMail	=	new mosUser($database);
						$userMail->load($outbidUser);
						$auction->SendMails(array($userMail),'bid_outbid');

					}
					/*
					* Old One - Outbid mail only to lastbest bid
					* Commented TODO
						if($currentuserid != $bestBid->userid) { 
							$userMail	=	new mosUser($database);
							$userMail->load($bestBid->userid);
							$auction->SendMails(array($userMail),'bid_outbid');
						}
					*/
					}else{
						//must be bigger
						echo "TODO - SERVER Validation";
						echo bid_err_must_be_greater_mybid;
						//mosRedirect($redirect_link,bid_err_must_be_greater_mybid);
						//return;
					}
				}
				$arrOutbid = array();
				/*
				 * Bidding Process END
				 */

			}else{
				echo "NO BIDDING -- currentuserid $currentuserid, currentBid $currentBid, amount $amount, bidType $bidType";
			}
				$queryProxy = "update #__bid_auctions set proxypluscron=1 where id= $rowsMainId->id";
				$database->setQuery($queryProxy);
				$database->query();
		  } //Loop for each Auction
		}
		exit;
		break;
	case 'report_auction':
		reportAuction($option,$id); //
		break;
	case 'terms_and_conditions':
		terms_and_conditions();//
		break;
	case 'rate':
		userRate($id,$option);
		break;

		//=========================== Auctions ==========================================
	case 'listauctions':
		listAuctions( $option );//
		break;
	case 'listauctionspicture':
		$_SESSION['lastpicture'] = "listauctionspicture";
		listAuctions( $option );//
		break;

	case 'myauctionspicture':
		$_SESSION['lastpicture'] = "myauctionspicture";
		listMyAuctions( $option );//
		break;
	case 'myauctions':
		listMyAuctions( $option);//
		break;
	case 'editauction':
	case 'republish':
		editAuction( $id, $option,$task ); //
		break;
	case 'saveauction':
		saveAuction( $id, $option ); //
		break;
	case 'saveproxyplus':
		saveProxyPlus( $id, $option ); //
		break;
	case 'savenextbid':
		saveNextBid( $id, $option ); //
		break;
	case 'newauction':
		editAuction( 0, $option ); //id=0 --> new Auction
		break;
	case 'cancelauction':
		CancelAuction($id,$option);//
		break;
	case 'accept':
		acceptBid($option);
		break;
	case 'watchlist':
		addWatch($id,$option);
		break;
	case 'mywatchlistpicture':
		$_SESSION['lastpicture'] = "mywatchlistpicture";
	case 'mywatchlist':
		listMyWatchlist($option);
		break;
	case 'delwatch':
		delWatch($id,$option);
		break;
		case'savemessage':
		saveMessage($option,$id);
		break;
	case 'publish':
		publishAuction($option,$id);
		break;

	default:
	case 'listcats':
		listcategories($option);
		break;
	case 'rss':
		AuctionsRss($option);
		break;

		//============================= USER DETAILS ===============================

	case 'UserDetails':
		if(CB_DETECT) mosRedirect($mosConfig_live_site."/index.php?option=com_comprofiler&Itemid=$Itemid");
		else userEdit( $option );
		break;
	case 'ViewDetails':
		if ($my->id==$id) { if(CB_DETECT) mosRedirect($mosConfig_live_site."/index.php?option=com_comprofiler&Itemid=$Itemid&task=userDetails");
		else userEdit( $option ); }
		else { if(CB_DETECT) mosRedirect($mosConfig_live_site."/index.php?option=com_comprofiler&task=userProfile&user=$id");
		else viewUser( $option, $id ); }
		break;

	case 'saveUserDetails':
		userSave( $option );//
		break;

	case 'canceluser':
		mosRedirect( $mosConfig_live_site."/index.php?option=$option&Itemid=$Itemid" );
		break;

		case'myratings':
		viewUserRatings($option,$id);
		break;

		//=========================== SEARCH =========================================

		case'search':
		search($option);
		break;
		case'showSearchResults':
	case 'tags':
		showSearchResults($option);
		break;

		//============================================================================

	case 'bulkimport':
		showimportform($option);
		break;
	case 'importcsv':
		import($option);
		break;
	case 'decodevin':
		decodeVin($option);
		break;

	/* JaiStartH */
	case 'editproxyticket':
	case 'listproxyticket':
		listProxyTicket($option);
		break;
	/* JaiStartI */
	case 'listnextbid':
		listNextBid($option);
		break;
	/* JaiENDI */

	case 'delproxyticket':
		delProxyTicket($option);
		break;

	/* JaiStartG */
	case 'listbigboard':
		listBigBoard($option);
	?>
	<script type="text/javascript">
		checkCount('<?php echo bid_opt_refresh_minutes; ?>', '<?php echo bid_newbid_bgcolor; ?>', '<?php echo bid_opt_bgcolor_minutes; ?>');
		a = setInterval("checkCount('<?php echo bid_opt_refresh_minutes; ?>','<?php echo bid_newbid_bgcolor; ?>', '<?php echo bid_opt_bgcolor_minutes; ?>');",<?php echo bid_opt_refresh_minutes  * 1000 * 60; ?>);
	</script>
	<?php
		break;
	case 'listmybigboard':
		listBigBoard($option,"1");
	?>
	<script type="text/javascript">
		checkCount('<?php echo bid_opt_refresh_minutes; ?>', '<?php echo bid_newbid_bgcolor; ?>', '<?php echo bid_opt_bgcolor_minutes; ?>');
		a = setInterval("checkCount('<?php echo bid_opt_refresh_minutes; ?>','<?php echo bid_newbid_bgcolor; ?>, <?php echo bid_opt_bgcolor_minutes; ?>');",<?php echo bid_opt_refresh_minutes  * 1000 * 60; ?>);
	</script>
	<?php
		break;
	case 'listmywbigboard':
		listBigBoard($option,"2");
	?>
	<script type="text/javascript">
		checkCount('<?php echo bid_opt_refresh_minutes; ?>', '<?php echo bid_newbid_bgcolor; ?>', '<?php echo bid_opt_bgcolor_minutes; ?>');
		a = setInterval("checkCount('<?php echo bid_opt_refresh_minutes; ?>','<?php echo bid_newbid_bgcolor; ?>, <?php echo bid_opt_bgcolor_minutes; ?>');",<?php echo bid_opt_refresh_minutes  * 1000 * 60; ?>);
	</script>
	<?php
		break;
	case 'checkbb':
		echo checkBB($option);
		exit;
		break;
  /* JaiEndG */

    default:

		break;

}

//================================= BIDS =================================
function listAuctions( $option ) {

	global $database, $my, $mosConfig_absolute_path;
	global $mosConfig_live_site,$task;
	global $Itemid;
	
	$where = "";
	
    $order_fields = mosBidOffers::getFieldOrderArray();
	$limit 		  = intval( mosGetParam( $_REQUEST, 'limit',  bid_opt_nr_items_per_page) );
	$limitstart   = intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );

	global $filter_bidtype, $filter_category, $filter_order, $filter_order_asc, $filter_userid;
	setFilters($limitstart);
	$arrPicture = array("listauctionspicture","myauctionspicture","mybidspicture","mywatchlistpicture","mywonbidspicture");
	if(in_array($task,$arrPicture)) {
		$filter_bidtype = 4;
	}

	if (!isset($order_fields[$filter_order])) $filter_order='start_date';


	switch($filter_bidtype) {
		default:
		case 0 :
			$where .=" where close_offer=0 and start_date<=now() and published=1";
			if($filter_category && $filter_category!=0){
				if(!bid_opt_inner_categories)
					
					$where .=" and a.cat=$filter_category ";
					
				else{
					
					$cCategory = new mosBidCategories($database);
					$cids = $cCategory->getCategoryChildren($filter_category);
					array_push($cids,$filter_category);
					$cidFilter = implode(",", $cids);
					if(strlen($cidFilter)>0)
						$cidFilter = "(".$cidFilter.")";
					
					$where .=" and a.cat IN $cidFilter ";
				}
			}
			break;
		case 1 :
			$where .=" where close_offer=1 and close_by_admin=0";
			if($filter_category && $filter_category!=0){
				
				if(!bid_opt_inner_categories)
					$where .=" and a.cat=$filter_category ";
				else{
					
					$cCategory = new mosBidCategories($database);
					$cids = $cCategory->getCategoryChildren($filter_category);
					array_push($cids,$filter_category);
					$cidFilter = implode(",", $cids);
					if(strlen($cidFilter)>0)
						$cidFilter = "(".$cidFilter.")";
					
					$where .=" and a.cat IN $cidFilter ";
				}
			}
			//avoid showing closed by admin
			break;
		case 3 :
			$where .=" left join #__bid_watchlist ww on a.id=ww.auctionid where ww.userid='$my->id'";
			if($filter_category && $filter_category!=0){
				if(!bid_opt_inner_categories)
					$where .=" and a.cat=$filter_category ";
				else
					$where .=" and a.cat=$filter_category or cats.parent = $filter_category ";
			}
			break;
		case 4 :
			$where .=" where published=1";
			break;

	}

	if ($filter_userid){
		$where.=" and a.userid='$filter_userid'";
	}
	//    $where.=" and userid='$my->id'";
	if ($filter_order_asc == 1) {
		$ord = "desc";
	}else{
		$ord = "asc";
	}



	$q = "select count(*) from #__bid_auctions a
	LEFT JOIN #__bid_categories as cats ON a.cat=cats.id 
	$where ";
	$database->setQuery($q);
	$total = $database->loadResult();

	require_once( $GLOBALS['mosConfig_absolute_path'] . '/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, $limit  );

	$query =  "SELECT
				a.*, b.name,b.username,m.name as payment_name, c.name as currency_name ,cats.catname, cats.id as cid
	           FROM #__bid_auctions as a
			   LEFT JOIN #__users as b ON b.id=a.userid
				LEFT JOIN #__bid_payment as m ON m.id=a.payment
				LEFT JOIN #__bid_currency as c ON c.id=a.currency
				LEFT JOIN #__bid_categories as cats ON a.cat=cats.id
	";
	$query	.=" $where ";
	
	if($filter_bidtype == 4) {
		$query  .="	ORDER by a.title + 1, a.title ASC";
	}elseif($filter_order == "newest_first"){
		$query .= " ORDER BY featured='gold' desc, start_date desc ";
	}else{
		$query  .="	ORDER by  featured='gold' desc, a.".$filter_order." $ord ";
	}
	
	$database->setQuery( $query,$limitstart, $limit );
	//echo $database->_sql;exit;
	$rows = $database->loadObjectList();
	$profile = new mosComProfiler($database);
	$manaInfo = $profile->getManagerInfo();
		//Added for finding next bid 
		//JaiStartI
		foreach ($rows as $keyTemp=>$rowTemp) {
			//Added for start date
			if($manaInfo->cb_buyerschoice == "Buyer's Choice") {
				$rows[$keyTemp]->start_date = $manaInfo->cb_startdate;
				$rows[$keyTemp]->end_date  = $manaInfo->cb_enddate;
			}
			$queryBids = "SELECT * FROM #__bids AS d JOIN
			(
			  SELECT id, id_offer, max(bid_price) AS bid_price
			  FROM #__bids  GROUP BY id_offer) AS d1  ON (d.id_offer = d1.id_offer AND d.bid_price = d1.bid_price) WHERE d.id_offer = ".$rowTemp->id;
			 $database->setQuery($queryBids);
			 if($rowsbid=$database->loadObjectList()) {

				$rows[$keyTemp]->bid_price = $rowsbid[0]->bid_price;
				$rows[$keyTemp]->bid_next = $rowsbid[0]->bid_price + findBidIncrement($rowsbid[0]->bid_price);
				$rows[$keyTemp]->current_bidder = $rowsbid[0]->userid;
				if($rowsbid[0]->bid_price == 0) {
					$rows[$keyTemp]->current_bidder = "";
					$rows[$keyTemp]->bid_price = "";
					$rows[$keyTemp]->bid_next = $rows[$keyTemp]->initial_price;
				}
			 }else{
				 $rows[$keyTemp]->bid_price = "";
				 $rows[$keyTemp]->bid_next = $rows[$keyTemp]->initial_price;
			 }
			 //Added for Finding proxy bids
			$queryProxyBids = "SELECT auction_id, max(max_proxy_price) as proxy_price FROM #__bid_proxy AS bp WHERE auction_id = ".$rowTemp->id." AND bp.user_id = $my->id";
			
			 $database->setQuery($queryProxyBids);
			 
			 if($rowsProxyBids = $database->loadObjectList()) {
				$rows[$keyTemp]->proxy_price = $rowsProxyBids[0]->proxy_price;
			 }else {
				 $rows[$keyTemp]->proxy_price = 0;
			 }
			 //Added for Finding proxy bids
			$queryProxyPlusBids = "SELECT auction_id, max(my_bid) as proxyplus_price, outbid FROM #__proxyplus_bids AS bp  JOIN #__proxyplus_tickets bpt ON (bp.ticket_id = bpt.id) WHERE auction_id = ".$rowTemp->id." AND bpt.userid = $my->id";
			
			 $database->setQuery($queryProxyPlusBids);
			 
			 if($rowsProxyPlusBids = $database->loadObjectList()) {
				$rows[$keyTemp]->proxyplus_price = $rowsProxyPlusBids[0]->proxyplus_price;
				$rows[$keyTemp]->outbid			 = $rowsProxyPlusBids[0]->outbid;
			 }else {
				 $rows[$keyTemp]->proxyplus_price = 0;
			 }
		}
		//End StartI
	$lists = array();

	$orders[] = mosHTML::makeOption('start_date',bid_sort_newest);
	$orders[] = mosHTML::makeOption('initial_price+0',bid_sort_initialprice);
	$orders[] = mosHTML::makeOption('BIN_price+0',bid_sort_binprice);
	$orders[] = mosHTML::makeOption('end_date',bid_sort_end_date);
	$orders[] = mosHTML::makeOption('name',bid_sort_username);
	$lists['orders'] = mosHTML::selectList($orders,'filter_order',
	'class="inputbox" onchange="document.auctionForm.submit();"','value', 'text',$filter_order);

	$bid_type[] = mosHTML::makeOption( 0, bid_filter_available);
	$bid_type[] = mosHTML::makeOption( 1, bid_filter_archive);
	$bid_type[] = mosHTML::makeOption( 3, bid_filter_watchlist);


	$lists['filter_bidtype'] =  mosHTML::selectList($bid_type,'filter_bidtype',
	'class="inputbox" onchange="document.auctionForm.submit();"','value', 'text',$filter_bidtype);

	$ord_desc[] = mosHTML::makeOption( 1, bid_order_desc);
	$ord_desc[] = mosHTML::makeOption( 2, bid_order_asc);
	$lists['filter_order_asc'] =  mosHTML::selectList($ord_desc,'filter_order_asc',
	'class="inputbox" onchange="document.auctionForm.submit();"','value', 'text',$filter_order_asc);

	$lists['filter_userid']=$filter_userid;

	$cats = makeCatTree();
	if (count($cats)>0){
		$cats=array_merge(array(mosHTML::makeOption( 0, bid_all_categories)),$cats);
		$lists['filter_cats'] = mosHTML::selectList($cats,'cat','class="inputbox" style="width:190px;" onchange="document.auctionForm.submit();"','value', 'text',$filter_category);
	}
	else
	$lists['filter_cats'] ='&nbsp;';

	if ($filter_userid)	$sfilters['users'] = $filter_userid;
	$sfilters['bid_type'] = $filter_bidtype;
	$sfilters['cat'] = $filter_category;
	HTML_Auction::listAuctions( $rows, $lists, $pageNav,$sfilters);
}


function listMyBids($option){
	global $database,  $my,  $task;
	
	$where = "";

	$filter_bidtype 	= intval(mosGetParam($_REQUEST,'filter_bidtype',0));

	$limit 		= intval( mosGetParam( $_REQUEST, 'limit',  bid_opt_nr_items_per_page) );
	$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );
	$where.=" where a.userid='$my->id'";
	if($task == "mybidspicture") {
		$filter_bidtype = '2';
	}
	switch ($filter_bidtype){
		case '0':
		$where .= " and b.close_offer=0 and b.published=1 ";
		break;
		case '1':
		$where .=" and b.close_offer=1 and b.published=1 ";
		break;
		case '2':
		$where .=" and b.published=1 ";
		break;
	}


	//    $where.=" and userid='$my->id'";

	$query="select count(*) from #__bids a
	left join #__bid_auctions b on a.id_offer=b.id
        $where ";
	$database->setQuery($query);
	$total=$database->loadResult();
	if ( $total <= $limit ) $limitstart = 0;
	require_once( $GLOBALS['mosConfig_absolute_path'] . '/includes/pageNavigation.php' );
	$pageNav = new mosPageNav($total, $limitstart, $limit  );

	$query="select a.id as parent_message,a.bid_price,a.modified as bid_date,
        b.newmessages as bid_newmessages,
        a.accept as accept, a.cancel as cancel,
         b.*,c.name as currency_name, u.name as name, u.username
        from #__bids a
        left join #__bid_auctions as b on a.id_offer=b.id ";

	$query.="left join #__bid_currency c on b.currency=c.id
        left join #__users u on u.id=b.userid
        ";

	
	if($filter_bidtype == 2) {
		$query  .="$where 	ORDER by  b.title + 1, b.title ASC";
	}else {
		$query .= "$where order by featured='gold' desc";
	}


	$database->setQuery($query,$limitstart, $limit);
	$rows=$database->loadObjectList();
	$profile = new mosComProfiler($database);
	$manaInfo = $profile->getManagerInfo();
		//Added for finding next bid 
		//JaiStartI
		foreach ($rows as $keyTemp=>$rowTemp) {
			//Added for start date
			if($manaInfo->cb_buyerschoice == "Buyer's Choice") {
				$rows[$keyTemp]->start_date = $manaInfo->cb_startdate;
				$rows[$keyTemp]->end_date  = $manaInfo->cb_enddate;
			}
	
			$queryBids = "SELECT * FROM #__bids AS d JOIN
			(
			  SELECT id, id_offer, max(bid_price) AS bid_price
			  FROM #__bids  GROUP BY id_offer) AS d1  ON (d.id_offer = d1.id_offer AND d.bid_price = d1.bid_price) WHERE d.id_offer = ".$rowTemp->id;
			 $database->setQuery($queryBids);
			 
			 if($rowsbid=$database->loadObjectList()) {
				$rows[$keyTemp]->bid_price = $rowsbid[0]->bid_price;
				$rows[$keyTemp]->current_bidder = $rowsbid[0]->userid;
				$rows[$keyTemp]->bid_next = $rowsbid[0]->bid_price + findBidIncrement($rowsbid[0]->bid_price);
				if($rowsbid[0]->bid_price == 0) {
					$rows[$keyTemp]->current_bidder = "";
					$rows[$keyTemp]->bid_price = "";
					$rows[$keyTemp]->bid_next = $rows[$keyTemp]->initial_price;
				}
			 }else{
				 $rows[$keyTemp]->bid_price = "";
				 $rows[$keyTemp]->bid_next = $rows[$keyTemp]->initial_price;
			 }
			 //Added for Finding proxy bids
			$queryProxyBids = "SELECT auction_id, max(max_proxy_price) as proxy_price FROM #__bid_proxy AS bp WHERE auction_id = ".$rowTemp->id." AND bp.user_id = $my->id";
			
			 $database->setQuery($queryProxyBids);
			 
			 if($rowsProxyBids = $database->loadObjectList()) {
				$rows[$keyTemp]->proxy_price = $rowsProxyBids[0]->proxy_price;
			 }else {
				 $rows[$keyTemp]->proxy_price = 0;
			 }
			 //Added for Finding proxy bids
			$queryProxyPlusBids = "SELECT auction_id, max(my_bid) as proxyplus_price FROM #__proxyplus_bids AS bp  JOIN #__proxyplus_tickets bpt ON (bp.ticket_id = bpt.id) WHERE auction_id = ".$rowTemp->id." AND bpt.userid = $my->id";
			
			 $database->setQuery($queryProxyPlusBids);
			 
			 if($rowsProxyPlusBids = $database->loadObjectList()) {
				$rows[$keyTemp]->proxyplus_price = $rowsProxyPlusBids[0]->proxyplus_price;
			 }else {
				 $rows[$keyTemp]->proxyplus_price = 0;
			 }
		}
		//End StartI


	$bid_type[] = mosHTML::makeOption( 0, bid_filter_available);
	$bid_type[] = mosHTML::makeOption( 1, bid_filter_archive);


	$lists['filter_bidtype'] =  mosHTML::selectList($bid_type,'filter_bidtype',
	'class="inputbox" onchange="document.auctionForm.submit();"','value', 'text',$filter_bidtype);

	$sfilters['bid_type'] = $filter_bidtype;
	HTML_Auction::listAuctions($rows,$lists,$pageNav,$sfilters);

}
function listMyWonBids($option){
	global $database,  $my,  $task;

	$rated 	= intval(mosGetParam($_REQUEST,'filter_rated',0));

	$limit 		= intval( mosGetParam( $_REQUEST, 'limit',  bid_opt_nr_items_per_page) );
	$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );

	//TODO Jai - previous check
	$where.=" where a.userid='$my->id' and accept=1";

	//$where.=" where a.userid='$my->id' ";
	if ($rated){
		//$query = "select count(id) from #__bid_rate where (voter = '$my->id' and auction_id = '$row->id')";
		$where.=" and r.voter is null";
	}
	$query="select count(*) from #__bids a
    	       left join #__bid_auctions as b on a.id_offer=b.id
    	       left join #__bid_rate r on r.voter='$my->id' and r.auction_id=b.id
        $where ";
	$database->setQuery($query);

	$total=$database->loadResult();
	if ( $total <= $limit ) $limitstart = 0;
	require_once( $GLOBALS['mosConfig_absolute_path'] . '/includes/pageNavigation.php' );
	$pageNav = new mosPageNav($total, $limitstart, $limit  );

	$query="SELECT a.id as parent_message,a.bid_price,a.modified as bid_date,
        b.newmessages as bid_newmessages,
        a.accept as accept, a.cancel as cancel,
         b.*,c.name as currency_name, u.name as name, u.username
        from #__bids as a
        left join #__bid_auctions b on a.id_offer=b.id
        left join #__bid_currency c on b.currency=c.id
        left join #__users u on u.id=b.userid
	    left join #__bid_rate r on r.voter='$my->id' and r.auction_id=b.id
        $where";

	echo $query .= " ORDER BY b.title + 0, b.title ASC";

	$database->setQuery($query,$limitstart, $limit);
	$rows=$database->loadObjectList();
	$profile = new mosComProfiler($database);
	$manaInfo = $profile->getManagerInfo();

	foreach($rows as $keyTemp=>$rowTemp) {
			//Added for start date
			if($manaInfo->cb_buyerschoice == "Buyer's Choice") {
				$rows[$keyTemp]->start_date = $manaInfo->cb_startdate;
				$rows[$keyTemp]->end_date  = $manaInfo->cb_enddate;
			}
	}

	$rated_list[] = mosHTML::makeOption( 0, bid_all);
	$rated_list[] = mosHTML::makeOption( 1, bid_unrated);
	$lists['filter_rated'] =  mosHTML::selectList($rated_list,'filter_rated',
	'class="inputbox" onchange="document.auctionForm.submit();"','value', 'text',$rated);
	$sfilters['filter_rated']=$rated;
	HTML_Auction::listAuctions($rows,$lists,$pageNav,$sfilters);

}

function AuctionDetails($option, $id) {

	global $database,  $my,$Itemid;

	$auction = new mosBidOffers($database);
	if (!$auction->load($id)) {
		echo bid_err_does_not_exist;
		return;
	}
	$auction->getManagerSettings();
	//Added for Finding proxy bids11/23/2009
	$queryProxyBids = "SELECT auction_id, max(max_proxy_price) as proxy_price FROM #__bid_proxy AS bp WHERE auction_id = ".$auction->id." AND bp.user_id = $my->id";
	$database->setQuery($queryProxyBids);	 
	 if($rowsProxyBids = $database->loadObjectList()) {
		$auction->proxy_price = $rowsProxyBids[0]->proxy_price;
	 }else {
		 $auction->proxy_price = 0;
	 }

	//Added for Finding proxy bids
	$queryProxyPlusBids = "SELECT auction_id, max(my_bid) as proxyplus_price FROM #__proxyplus_bids AS bp  JOIN #__proxyplus_tickets bpt ON (bp.ticket_id = bpt.id) WHERE auction_id = ".$auction->id." AND bpt.userid = $my->id";
	$database->setQuery($queryProxyPlusBids);
	 if($rowsProxyPlusBids = $database->loadObjectList()) {
		$auction->proxyplus_price = $rowsProxyPlusBids[0]->proxyplus_price;
	 } else {
		 $auction->proxyplus_price = 0;
	 }

	if (!$auction->published && $my->id!=$auction->userid) {
		echo bid_err_does_not_exist;
		return;
	}
	
  if ($my->id!=$auction->userid && (!isset($_COOKIE['washit'])||$_COOKIE['washit']<time()))
  {
    $auction->hit();
    setcookie("washit", time()+4);
  }
	$bid_user=new mosUser($database);
	$bid_user->load($auction->userid);

	$database->setQuery("update #__bid_messages set wasread=1 where userid2='$my->id' and id_offer=$auction->id");
	$database->query();


	$u=new mosBidUsers($database);
	$bid_user_details = $u->getUserDetails($bid_user->id);


	$query = "
	SELECT m.*,u1.username as fromuser, u2.username as touser
	FROM #__bid_messages as m
	LEFT JOIN #__users u1 ON u1.id = m.userid1";
	$query .= " LEFT JOIN #__users u2 ON u2.id = m.userid2
		WHERE m.id_offer=$id and (m.userid1 = $my->id or m.userid2 = $my->id)
	";
	$database->setQuery($query);

	$messages = $database->loadObjectList();

	if ($auction->auction_type==AUCTION_TYPE_PRIVATE && $my->id!=$auction->userid) {
		//Closed Auction
		$where = " where id_offer='$id' and userid='$my->id'";
	}else{
		//Open Auction
		$where = " where id_offer='$id'";
	}
	$query = "select a.*,u.name,u.username from #__bids a
		left join #__users u on a.userid=u.id

		$where ";
	$database->setQuery($query);
	$bids_placed = $database->loadObjectList();

	$database->setQuery("select name from #__bid_currency where id=$auction->currency");
	$currency=$database->loadResult();
	

	$database->setQuery("select name from #__bid_payment where id=$auction->payment");
	$payment=$database->loadResult();
	$arr[] = mosHTML::makeOption(1,'1');
	$arr[] = mosHTML::makeOption(2,'2');
	$arr[] = mosHTML::makeOption(3,'3');
	$arr[] = mosHTML::makeOption(4,'4');
	$arr[] = mosHTML::makeOption(5,'5');
	$arr[] = mosHTML::makeOption(6,'6');
	$arr[] = mosHTML::makeOption(7,'7');
	$arr[] = mosHTML::makeOption(8,'8');
	$arr[] = mosHTML::makeOption(9,'9');
	$arr[] = mosHTML::makeOption(10,'10');

	$list['ratings'] = mosHTML::radioList($arr,'rate',null);
	HTML_Auction::AuctionDetails($auction,$list,$bid_user,$bid_user_details,$bids_placed,$currency,$payment,$messages);
}

function saveMessage($option,$id_offer){
	global $database,  $mosConfig_absolute_path,  $Itemid, $my;
	global  $mosConfig_live_site;

	if(!$my->id && bid_opt_enable_captcha){
		require_once(BIDS_COMPONENT_PATH.'/checkspam/checkspam.class.php');
		$cs = new checkspam();
		$cs->init_session();
		// if code not good halt
		if(!$cs->verify($_POST['secretcode'])){
			$redirect_link = 'index.php?option=com_bids&task=viewbids&id='.$id_offer.'&Itemid='.$Itemid."&mosmsg=".bid_message_error_captcha."#messages";
			mosRedirect($redirect_link);
		}
	}
	
	$id_msg = mosGetParam($_POST,"idmsg",'');
	$bidder_id = mosGetParam($_POST,"bidder_id",0);

	$auction = new mosBidOffers($database);


	if  (!$auction->load($id_offer)){
		echo bid_does_not_exist;
		return;
	}

	if  ($auction->close_offer){
		if (!$auction->isMyAuction() && (!$auction->winner_id==$my->id)){
			echo bid_auction_is_closed;
			return;
		}
	}
	if  ($auction->published!=1){
		echo bid_does_not_exist;
		return;
	}


	if (bid_opt_allow_messages){
		$comment=mosGetParam( $_REQUEST, 'message',  '');
		$comment=str_replace("\n",'<br>',$comment);

		$auction->sendNewMessage($id,$id_msg,$comment,$bidder_id);
	}
	$redirect_link = 'index.php?option=com_bids&task=viewbids&id='.$id_offer.'&Itemid='.$Itemid."mosmsg=".bid_message_success."#messages";

	mosRedirect($redirect_link);
}

function searchForProxies($id){
	global $database,$my;

	$database->setQuery("select count(*) from #__bids where id_offer=$id");
	$bids = $database->loadResult();


	if($bids > 0){
		/*
		* todo
		* Jai Changed to JOIN (LEFT JOIN previous) 
		* To check proxies
		*/
		$database->setQuery("select count(*) from #__bids a
							left join #__bid_proxy b on a.id_proxy=b.id
							where id_offer=$id and user_id !=".$my->id." and active<>0 ");
		$std_bids = $database->loadResult();
		if($std_bids>0){
			return 3;
		}else {
			return 2;
		}
	}else {
		return 1;
	}
}

function updateProxies($amount,$auctionid){
	global $database,$my,$bidType;
	/* JAI START J */
	$successproxy =0;
	/* JAI END J */

    $successproxy = 0;
	$auction = new mosBidOffers($database);
	$auction->load($auctionid);
	$sql = "select * from #__bid_proxy where (user_id != '{$my->id}') and (auction_id=$auctionid) and active=1";
	$auction->writelog("\t\n----------------------$sql----\t\n");
	$database->setQuery($sql);
	$proxies = $database->loadObjectList();
	for ($i=0;$i<count($proxies);$i++ ){
	    $pr=$proxies[$i];
		if($pr->max_proxy_price < $amount){
			echo "JAI - loop1";

			$database->setQuery("update #__bid_proxy set latest_bid=$pr->max_proxy_price,active=0 where id = $pr->id");
			$database->query();
			
			$query = "update #__bids set bid_price=$pr->max_proxy_price, id_proxy=$pr->id, modified='".date("Y-m-d H:i:s",time())."' where userid=$pr->user_id and id_offer = $auctionid";
			$database->setQuery($query);
			$database->query();

			$query = "INSERT INTO  #__bids_log (bid_price,id_proxy,modified,userid,id_offer,bidtype) VALUES ($pr->max_proxy_price, $pr->id, '".date("Y-m-d H:i:s",time())."', $pr->user_id, $auctionid,'p')";
			$database->setQuery($query);
			$database->query();

			$database->setQuery("select u.* from #__users u where id=$pr->user_id");
			$users = $database->loadObjectList();
			$auction->SendMails($users,'proxy_ended');
			$auction->writelog("\t\n----------------------\t\n-- proxy_ended - JAI - loop1 TEST USERID 4 ".$users['userid']." Amount = $amount");
		}elseif($pr->max_proxy_price == $amount) {
			echo "JAI - loop2";
			
			//TODO OLD changing
		    //$bid=$pr->max_proxy_price - $auction->min_increase;
			$bid = $pr->max_proxy_price - findBidIncrement($pr->max_proxy_price);

			//Added by Jai
			$bid = $amount;

			$database->setQuery("update #__bid_proxy set latest_bid=$bid,active=0 where id = $pr->id");
			$database->query();
			echo $query = "update #__bids set bid_price=$bid, id_proxy=$pr->id, modified='".date("Y-m-d H:i:s",time())."'  where userid=$pr->user_id and id_offer = $auctionid";
			$database->setQuery($query);
			$database->query();

			/*
			* BIDS LOG
			*/
			$query = "INSERT INTO  #__bids_log (bid_price,id_proxy,modified,userid,id_offer,bidtype) VALUES ($bid, $pr->id, '".date("Y-m-d H:i:s",time())."', $pr->user_id, $auctionid,'p')";
			$database->setQuery($query);
			$database->query();

			$database->setQuery("select u.* from #__users u where id=$pr->user_id");
			$users = $database->loadObjectList();
			$auction->SendMails($users,'proxy_ended');
			$auction->writelog("\t\n----------------------\t\n-- proxy_ended - JAI - loop2 TEST USERID 4 ".$users['userid']." Amount = $amount");

			/*
			* JAI TEST
			* Proxies to be updated till one bidder exists
			*/
			if(count($proxies) > 1) {
				updateProxies($bid,$auctionid);
			}
			
		}else{
			echo "JAI - loop3";
			
			//TODO Jai
			//$bid = $amount + $auction->min_increase;
			$bid = $amount + findBidIncrement($amount);

			if ($bid>$pr->max_proxy_price) $bid=$pr->max_proxy_price;

			$database->setQuery("update #__bid_proxy set latest_bid=$bid where id=$pr->id");
			$database->query();
	
			$query = "update #__bids set bid_price=$bid, id_proxy=$pr->id, modified='".date("Y-m-d H:i:s",time())."'  where userid=$pr->user_id and id_offer = $auctionid";
			$database->setQuery($query);
			$database->query();

			$bidType = 2;
			/*
			* BIDS LOG
			*/
			$query = "INSERT INTO  #__bids_log (bid_price,id_proxy,modified,userid,id_offer,bidtype) VALUES ($bid, $pr->id, '".date("Y-m-d H:i:s",time())."', $pr->user_id, $auctionid,'p')";
			$database->setQuery($query);
			$database->query();

			/*
			* JaiSTARTJ
			* To send Proxy accepted mails
			*/
			$database->setQuery("select u.* from #__users u where id=$pr->user_id");
			$users = $database->loadObjectList();
			$auction->SendMails($users,'bid_new_mybid');
			$successproxy = $pr->user_id;
			$auction->writelog("\t\n----------------------\t\n-- proxy_ended - JAI - loop3 TEST USERID 4 ".$users['userid']." Amount = $amount");
			$auction->writelog("\t\n--------$successproxy = successproxy-----\t\n-- proxy_ended - TEST USERID 5 ");
			//JaiENDJ


			/*
			* JAI TEST
			* Proxies to be updated till one bidder exists
			*/
			if(count($proxies) > 1){
				updateProxies($bid,$auctionid);
			}

		}
	}
	return $successproxy;
}
/* 
* Jai Proxy Bidding New function 
* JaiStartI
*/
function autoProxies($amount,$auctionid) {
	global $database,$my, $bidType;

	$auction = new mosBidOffers($database);
	$auction->load($auctionid);
	$sql = "select * from #__bid_proxy where auction_id=$auctionid ORDER BY max_proxy_price, id, user_id ASC";
	$database->setQuery($sql);
	$proxies = $database->loadObjectList();
	$countCurrent = $countProxyBids = count($proxies);
	for ($i=0;$i < $countProxyBids;$i++ ) {
	    $pr=$proxies[$i];
		if($pr->max_proxy_price <= $amount) {
			echo "JAI - loop1";
			
			echo "<br>".$query = "update #__bid_proxy set latest_bid = $pr->max_proxy_price, active=0 where id = $pr->id";
			$database->setQuery($query);
			$database->query();
			if($pr->max_proxy_price == $amount && $bid == $amount) {
				//Not to bid - If same proxy bid & one bid has already placed.
				continue;
			}
			
			echo "<br>".$query = "update #__bids set bid_price=$pr->max_proxy_price, id_proxy=$pr->id, modified='".date("Y-m-d H:i:s",time())."' where userid=$pr->user_id and id_offer = $auctionid";
			$database->setQuery($query);
			$database->query();
			$bid = $amount;

			$database->setQuery("select u.* from #__users u where id=$pr->user_id");
			$users = $database->loadObjectList();
			$auction->SendMails($users,'proxy_ended');
		}elseif($pr->max_proxy_price > $amount) {
			echo "JAI - loop2";
			$bidType = 2;
			/*
			* Added to find Increment amount
			*/
				if($countCurrent > 1) {
					$amount =  $pr->max_proxy_price;
					$bid = $amount;
					echo "<br>".$query = "update #__bid_proxy set latest_bid=$bid,  active=0 where id=$pr->id";
				}else {
					if($i != 0) {
						$amount +=  findBidIncrement($amount);
					}
					$bid = $amount; 
					echo "<br>".$query = "update #__bid_proxy set latest_bid=$bid where id=$pr->id";
				}
			
			$countCurrent--;

			//echo "<br>".$query = "update #__bid_proxy set latest_bid=$bid where id=$pr->id";
			$database->setQuery($query);
			$database->query();
	
			echo "<br>".$query = "update #__bids set bid_price=$bid, id_proxy=$pr->id, modified='".date("Y-m-d H:i:s",time())."'  where userid = $pr->user_id and id_offer = $auctionid";
			$database->setQuery($query);
			$database->query();

					/*
					$bestBid=null;
					$bestBid=$auction->GetBestBid();
					print_r($bestBid);
					//Same bidder
					if($bestBid->userid == $rowPlaceBids['userid']) {
						echo "JAI- <br>BEST BID";
						if(count($proxyPlusBids) <= 1) {
							unset($proxyPlusBids[$rowPlaceBids['userid']]);
						}
						continue;
					}
					*/

			//TODO
			/*
			if($amount > $pr->max_proxy_price) { 
				$amount = $pr->max_proxy_price;
			}
			Checking
			*/
			/*
			* JAI TEST
			* Proxies to be updated till one bidder exists
			if(count($proxies) > 1) {
				updateProxies($bid,$auctionid);
			}
			*/
			
		}
	}
	return $amount;
}
/*
* JaiEndI
*
*/

function Proxy($amount=null,&$auction,$proxy=null) {
	global $database,$my,$mosConfig_live_site,$my;

	$database->setQuery("select * from #__bid_proxy where auction_id = $auction->id and user_id=$my->id ");
	$prx = new mosBidProxyBids($database);
	if($proxy && $auction->auction_type==AUCTION_TYPE_PUBLIC){
		if(!$database->loadObject($prx)){
			$prx->auction_id = $auction->id;
			$prx->user_id = $my->id;
			$prx->max_proxy_price=$amount;
			$prx->active = 1;
			$prx->datemodified = date("Y-m-d H:i:s");
			$prx->store();
		}else {
			$prx->max_proxy_price = $amount;
			$prx->datemodified = date("Y-m-d H:i:s");
			$prx->store($prx->id);
		}
	}else{
		$sqldel = "delete from #__bid_proxy where auction_id=$auction->id and user_id=$my->id";
		$database->setQuery($sqldel);
		$database->query();
	}
	$prx->active=1;
	$proxies = searchForProxies($auction->id);
	
	// wtf that was it: $prx->id (doar daca proxy)
	if( $proxy || $proxies>2 ){
		/*
		  1 -> no bids at all
		  2 -> bids, but no other Proxies
		  3 -> bids and proxies
		*/
		switch($proxies){
			case 1:
				$proxy_price = $auction->initial_price;
				break;
			case 2:
				$database->setQuery("select max(bid_price) from #__bids where id_offer=$auction->id and userid=$my->id");
				$mybid = $database->loadResult();
				$database->setQuery("select max(bid_price) from #__bids where id_offer=$auction->id");
				$max_bid = $database->loadResult();
				if($mybid<$max_bid){
					//$proxy_price = $max_bid + $auction->min_increase;
					$proxy_price = $max_bid + findBidIncrement($max_bid);
				}else {
					$proxy_price = $mybid;
				}
				if($proxy_price>=$amount){
					$proxy_price=$amount;
				}
				break;
			case 3:
				$database->setQuery("select count(*) from #__bid_proxy where auction_id=$auction->id and max_proxy_price>$amount and user_id != $my->id");
				$result = $database->loadResult();
				if($result>0){
				    //other bidders have higher Proxy prices then me
					$proxy_price = $amount;
					$prx->latest_bid=$amount;
					$prx->active=0;
					$prx->store();
					$database->setQuery("select * from #__users where id=$prx->user_id");
					$prxu = $database->loadObjectList();
					$auction->SendMails($prxu,'proxy_ended');
				}else {

					$database->setQuery("select max(max_proxy_price) from #__bid_proxy where auction_id=$auction->id and user_id != ".$my->id);
					$max_proxy = $database->loadResult(); //Other Users proxy
					
					if(($max_proxy+findBidIncrement($max_proxy)) > $amount) {
					    if ($max_proxy==$amount){
						  //old method  Jai
					      //$proxy_price=$amount - findBidIncrement($amount);
						  $proxy_price=$amount;
					      if ($proxy_price<0) $proxy_price=0;
					    }else
						  $proxy_price=$amount;
					}else {
						$proxy_price=$max_proxy+findBidIncrement($max_proxy);
					}
				}
				break;
			default:
		}
		
		
		$database->setQuery("select max(b.bid_price) from #__bids b where id_offer = $auction->id and userid != $my->id");
		$max_bid = $database->loadResult();

		if($auction->auction_type == AUCTION_TYPE_PUBLIC){
			if($max_bid != null && $max_bid + findBidIncrement($max_bid) > $proxy_price && $task!='bin'){
				$min_accepted = $max_bid + findBidIncrement($max_bid);
			}elseif(!$max_bid && $auction->initial_price > $proxy_price){
				$min_accepted = $auction->initial_price + findBidIncrement($auction->initial_price);
			}
			if($min_accepted){
				/*
				* Jai Added to bid for proxy if manual bid ties up with proxy
				*/
				$successproxytest = updateProxies($min_accepted,$auction->id);
				mosRedirect($mosConfig_live_site."/index.php?option=com_bids&task=viewbids&id=$auction->id&Itemid=$Itemid&mosmsg=".bid_err_increase." ".($min_accepted + findBidIncrement($min_accepted)));
				return ;
			}
		}
		
		if( $proxy || $prx->id ){
			
			$prx->latest_bid=$proxy_price;
			$prx->store();
		}

		return array($proxy_price,$prx->id,$prx->max_proxy_price);
	}elseif($amount){

		return array($amount,0,0);
	}

}

function saveBid($option,$id_offer,$task, $amountAuto = 0,  $isProxyPlus=0, $userid=0){
	global $database,  $mosConfig_absolute_path,  $Itemid, $my;
	global $mosConfig_live_site, $bidType;
	echo "<pre>";
	/*
	* Variable for maintaining outbid
	*/
	$aidorg =  $id_offer;
	if($isProxyPlus) {
		$bidType = "pp";
	}

	$mylastbid = mosGetParam($_REQUEST,'mylastbid'.$_REQUEST['auction_id'],0);
	if($userid){
		$mylastbid=0;
	}

	$maxbid = 0;
	$query = "SELECT max(bid_price) from #__bids where id_offer={$id_offer}";
	$database->setQuery($query);
	$maxbid = $database->loadResult();

	$proxyplusTOBID = array();
	$msg = "";
	$redirect_link = sefRelToAbs("index.php?option=com_bids&task=viewbids&id=$id_offer&Itemid=$Itemid");
	
	$auction = new mosBidOffers($database);
	if(!$auction->load($id_offer)){
		echo bid_does_not_exist;
		return;
	}

	/*
	*Jai - added for Setting up start date & end date from buyer's choice
	*/
	$manaSettings = $auction->getManagerSettings();

	/*
	* Auto Bidding on sale start time
	*/
	if($amountAuto) {
		$amount = $amountAuto;
	}
	if(!isset($orgmy)){
		$orgmy = $my;
	}
	if($userid) {
		$my->id = $userid;
	}else {
		$userid = $my->id;
	}
	$currentuserid = $userid;
	/*
	* Jai: Acution Default Errors
	*/
	if ($auction->userid==$my->id){
		echo bid_err_no_self_bidding;
		return;
	}

	if ($auction->close_offer){
		echo bid_auction_is_closed;
		return;
	}
	if ($auction->published !=1){
		echo bid_does_not_exist;
		return;
	}
	//security check :)
	if (!$my->id && $task != "autobid"){
		mosNotAuth();
		return;
	}


	if($task == 'bin'){
		$amount = $auction->BIN_price;
		if ($amount<=0) {
		    echo bid_no_bin;
		    return ;
		}
	}else{
		if(!$amountAuto){
			$amount=floatval( mosGetParam( $_REQUEST, 'amount',  0) );
			if($amount == 0) {
				$amount=floatval( mosGetParam( $_REQUEST, 'amount'.$_REQUEST['auction_id'],  0) );
			}
		}
		$orgamount = $amount;
		if($amount < $auction->initial_price || $amount <= 0){
			mosRedirect($redirect_link,bid_err_price);
			return;
		}
	}
	/*
	* All Variables Backup
	*/
	$orgamount		= $amount;
	if($isProxyPlus && (!isset($currentBid) || $currentBid == 0)){
		$currentBid = $amount;
	}
	if(!isset($orgid_offer) || $orgid_offer == 0){
		$orgid_offer = $id_offer;
	}

	$query = "SELECT u.* from #__users u
			  left join #__bid_watchlist w on u.id=w.userid
			  where not(w.userid is null) and w.auctionid='$id_offer'
			  and (w.userid<>'$my->id')
			  ";
	$database->setQuery($query);
	$watches = $database->loadObjectList();

	$comment=mosGetParam( $_REQUEST, 'message',  '');
	// if the auction it's not private test if bid is right
	// to fix:
	
	if($auction->auction_type != AUCTION_TYPE_PRIVATE && $task != 'bin' && !$isProxyPlus){
		$my_acceptedpr = $mylastbid + findBidIncrement($mylastbid);// my bid must be greater than my last bid + minincrease
		$acceptedpr = $maxbid + findBidIncrement($maxbid);// my bid must be greater than max bid + minincrease
		
		if( $amount < $my_acceptedpr ){
			mosRedirect($redirect_link, bid_err_price_mybid);
			return;
		}
		if($amount < $acceptedpr ){
			mosRedirect($redirect_link, bid_err_price_maxbid);
			return;
		}
	}

	//JaiStartI
	$enddate = strtotime($auction->end_date);
	$diff = $enddate - time();
	if($diff > 0) {
		$minutes = ($diff/60);
		$manaSettings->inactivity;
		if($minutes < $manaSettings->inactivity) {
			if($manaSettings->buyerschoice == "Buyer's Choice") {
				$comProf = new mosComProfiler($database);
				$comProf->_tbl_key='id';
				$comProf->id = $manaSettings->comprofid;
				$comProf->cb_enddate = date("Y-m-d H:i:s", (time()+($manaSettings->inactivity * 60)));
				$comProf->store();
			}else{
				$auction->end_date = date("Y-m-d H:i:s", (time()+($manaSettings->inactivity * 60)));
				$auction->store($id_offer);
			}
		}
	}
	//JaiEndI

		
//JaiStartH
/*
Jai ProxyPlus
*/
$loopcount = $loopcount1 = 0;
	$loopcount = $loopcount1 = 0;
	$proxyplusstatus = 0;
	$proxystatus = 0;
	if(strtotime($auction->start_date) < time()){
		$auction->writelog("<br>----PROXY PLUS II = ".$amount." auction->id = ".$auction->id);
		$query   = "SELECT lot_desired, ppt.id as id, ppt.userid FROM #__proxyplus_tickets ppt JOIN #__proxyplus_bids ppb ON (ppt.id = ppb.ticket_id) ";
		$query   .= " WHERE ppb.auction_id = $id_offer  ";
		/* Jai Commented - 1/10/2010 16:00 To avoid proxyplus condition need to check
		if($isProxyPlus){
			$query   .= " AND ppt.id <> ".$isProxyPlus;
		}
		*/
		echo "<br>".$query   .= " ORDER BY ppt.datemodified, ppt.userid ASC";
		$database->setQuery( $query);
		$rowsProxyPlusTickets = $database->loadObjectList();
		$proxyPlusBids = array();
		if(count($rowsProxyPlusTickets) > 0){
			$countpriorityBids=0;
			foreach($rowsProxyPlusTickets as $rowProxyPlusTickets) {
				echo "<br>".$query   = "SELECT * FROM #__proxyplus_bids AS b WHERE ticket_id = ".$rowProxyPlusTickets->id ." AND outbid = 0 ORDER BY priority, datemodified ASC LIMIT ".$rowProxyPlusTickets->lot_desired;
				$database->setQuery( $query);
				$rowsProxyPlusbids = $database->loadObjectList();
				foreach($rowsProxyPlusbids as $rowProxyPlusbids) {
					if($rowProxyPlusbids->auction_id == $id_offer) {
						$proxyPlusBids[strtotime($rowProxyPlusbids->datemodified)] = array(
														"id"=>$rowProxyPlusbids->id,
														"ticket_id"=>$rowProxyPlusbids->ticket_id,
														"lot_desired"=>$rowProxyPlusTickets->lot_desired,
														"auction_id"=>$rowProxyPlusbids->auction_id,
														"my_bid"=>$rowProxyPlusbids->my_bid,
														"priority"=>$rowProxyPlusbids->priority,
														"datemodified"=>$rowProxyPlusbids->datemodified,
														"outbid"=>$rowProxyPlusbids->outbid,
														"userid"=>$rowProxyPlusTickets->userid
													  );
						$countpriorityBids++;
					}
				}
			}
		}
		ksort($proxyPlusBids);
		print_r($proxyPlusBids);
		$countOrg = count($proxyPlusBids);
		/*
		*  Amount:				 actuall highest amount
		*  userid:				 Original userid
		*  currentuserid:		 Highest bidder
		*  currentBid			 Highest bidder bid, but minimum value he can bid to stay highest bidder
		*  currentHighestEqual   If set to 1, already equal amount is there from some user who 
		*/
		$currentHighestEqual = 0;
		do {
				$loopcount1 = 0;
				foreach($proxyPlusBids as $keyBids=>$rowPlaceBids){
						echo "<br> currentuserid = ".$currentuserid;
						echo "<br> amount = ".$amount;
						print_r($rowPlaceBids);
						$loopcount1++;

						//Same bidder
						if($currentuserid == $rowPlaceBids['userid']) {
							$proxyplusstatus++;
							if(count($proxyPlusBids) <= 1) {
								unset($proxyPlusBids[$keyBids]);
							}
							continue;
						}
						if($amount == $rowPlaceBids['my_bid']){
							$currentBid	   = $amount;
							echo "<br> currentHighestEqual".$currentHighestEqual;
							if(!$currentHighestEqual){
								$currentuserid = $rowPlaceBids['userid'];
							}else{
								/*
								*  Commented on 1/28/2010
								*  To avoid the outbid status & causing issues
								*  TODO check the functionality
								*/
										
								$query1= "UPDATE #__proxyplus_bids SET outbid = 1 WHERE auction_id = $id_offer AND id = ".$rowPlaceBids['id'];
								$database->setQuery($query1);
								$database->query();

								/*
								*/
							}
							$currentHighestEqual = 1;
							$bidType       = "pp";
							unset($proxyPlusBids[$keyBids]);
							
							$querylots = "SELECT * FROM `#__proxyplus_bids` ppb where ticket_id = ".$rowPlaceBids['ticket_id']." and outbid = 0 ORDER BY priority limit ".($rowPlaceBids['lot_desired']-1).", 1";
							$database->setQuery($querylots);
							$resultslots = $database->loadObjectList();
							$resultslots[0]->userid  = $rowPlaceBids['userid'];
							$proxyplusTOBID[]        = $resultslots[0];
							$proxyplusstatus         = 0;
						}elseif(($amount + findBidIncrement($amount)) <= $rowPlaceBids['my_bid']){
								$currentuserid       = $rowPlaceBids['userid'];
								$currentBid	         = $amount + findBidIncrement($amount);
								$amount		         = $rowPlaceBids['my_bid'];
								$bidType             = "pp";
								$currentHighestEqual = 1;
						}elseif($amount < $rowPlaceBids['my_bid'] && $amount + findBidIncrement($amount) > $rowPlaceBids['my_bid']){
								$currentuserid       = $rowPlaceBids['userid'];
								$currentBid	         = $amount + findBidIncrement($amount);
								//added 1/27/2010 
								//Need to check
								$currentBid	         = $rowPlaceBids['my_bid'];
								$amount		         = $rowPlaceBids['my_bid'];
								$bidType			 = "pp";
								$currentHighestEqual = 1;
						}elseif($amount > $rowPlaceBids['my_bid']) {

							$query1= "UPDATE #__proxyplus_bids SET outbid = 1 WHERE auction_id = $id_offer AND id = ".$rowPlaceBids['id'];
							$database->setQuery($query1);
							$database->query();
							unset($proxyPlusBids[$keyBids]);

							if($rowPlaceBids['my_bid'] > $currentBid) {
								$currentBid	   = $rowPlaceBids['my_bid'] + findBidIncrement($rowPlaceBids['my_bid']);
							}

							$querylots = "SELECT * FROM `#__proxyplus_bids` ppb where ticket_id = ".$rowPlaceBids['ticket_id']." and outbid = 0 ORDER BY priority limit ".($rowPlaceBids['lot_desired']-1).", 1";
							$database->setQuery($querylots);
							$resultslots = $database->loadObjectList();
							$resultslots[0]->userid = $rowPlaceBids['userid'];
							$proxyplusTOBID[] = $resultslots[0];
							$proxyplusstatus = 0;
							continue;
						}

				}
		}while(count($proxyPlusBids) > 0);
	}

	echo "currentuserid $currentuserid, currentBid $currentBid, amount $amount, bidType $bidType";
	//JaiEndH

/**
* Manual Bids to handle after prox
*/

	$manualbidactive = 0;
	if(strtotime($auction->start_date) < time() && !$isProxyPlus) {
		if($orgamount > $amount){
			$currentuserid = $userid;
			$currentBid	   = $amount;
			$amount        = $orgamount;
			$bidType       = "m";
		}elseif($orgamount == $amount && $currentuserid == $userid){
			$currentuserid = $userid;
			$currentBid	   = $amount;
			$amount        = $orgamount;
			$bidType       = "m";
		}
	}elseif(!$isProxyPlus){
		mosRedirect($redirect_link,"Bidding Not yet Started");
		return;
	}
	/*
	* Jai ENd Manual
	*/

	/*
	 * Bidding Process START
	 */
	if($countOrg > 0 ||  !$isProxyPlus) {
	$bestBid=null;
	if($auction->auction_type != AUCTION_TYPE_PRIVATE) $bestBid = $auction->GetBestBid($id_offer);
 
	$queryBidUser = "select * from #__bids where  cancel=0 and userid='".$currentuserid."' and id_offer='".$id_offer."' ";
	$database->setQuery($queryBidUser);
	$head_message=new mosBids($database);//new head message

	if(!$database->loadObject($head_message)) {
		$head_message->userid        = $currentuserid;
		$head_message->id_offer      = $id_offer;
		$head_message->bidtype       = $bidType;
		$head_message->bid_price     = $currentBid;
		$head_message->initial_bid   = $currentBid;
		$head_message->modified      = date("Y-m-d H:i:s",time());
		$head_message->accept		 = 0;
		$head_message->cancel		 = 0;
		$head_message->store();

		$owner_user		=	new mosUser($database);
		$owner_user->load($auction->userid);

		if($amount > 0){
			$auction->SendMails($watches,'new_bid_watchlist');
			$auction->SendMails(array($owner_user),'new_bid');

			$userMail	=	new mosUser($database);
			$userMail->load($currentuserid);
			$auction->SendMails(array($userMail),'bid_new_mybid');
			
		}else{
			$auction->SendMails(array($owner_user),'new_message');
		}
		if($currentuserid != $bestBid->userid) { 
			$userMail	=	new mosUser($database);
			$userMail->load($bestBid->userid);
			$auction->SendMails(array($userMail),'bid_outbid');
		}
	}else {
		if($head_message->cancel){
			echo bid_err_was_canceled;
			return;
		}
		if($currentBid >= $head_message->bid_price){
			$head_message->bidtype       = $bidType;
			$head_message->bid_price     = $currentBid;
			$head_message->modified      = date("Y-m-d H:i:s",time());
			$head_message->store();
			
			$owner_user	=	new mosUser($database);
			$owner_user->load($auction->userid);
			if($amount	>	0) {
				$auction->SendMails($watches,'new_bid_watchlist');
				$auction->SendMails(array($owner_user),'new_bid');

				$userMail	=	new mosUser($database);
				$userMail->load($currentuserid);
				$auction->SendMails(array($userMail),'bid_new_mybid');

			}else{
				$auction->SendMails(array($owner_user),'new_message');
			}
			if($currentuserid != $bestBid->userid) { 
				$userMail	=	new mosUser($database);
				$userMail->load($bestBid->userid);
				$auction->SendMails(array($userMail),'bid_outbid');
			}

		}else{
			//must be bigger
			echo "TODO - SERVER Validation";
			echo bid_err_must_be_greater_mybid;
			//mosRedirect($redirect_link,bid_err_must_be_greater_mybid);
			//return;
		}
	  }
	}
	/*
	 * Bidding Process END
	 */
	/*
	* NEED TO CHECK
	* TODO
	*/
	if($amount >= $auction->BIN_price && $auction->BIN_price>0){
		if ($auction->GetParam( 'auto_accept_bin' )=='1' || $auction->automatic){
			//accept and close and send emails
			$head_message->accept=1;
			$head_message->store();

			$auction->winner_id = $my->id;
			$auction->published=1;
			$auction->close_offer=1;
			$auction->closed_date=date('Y-m-d H:i:s',time());
			$auction->store();

			$database->setQuery("delete from #__bid_watchlist where auctionid = $auction->id");
			$database->query();

			$auction->SendMails($watches,'alert_bin_accepted');

			$auction->SendMails(array($owner_user),'new_bid_auto_bin');
			$auction->SendMails(array($my),'bin_accepted');
		} else {
			$auction->SendMails($watches,'alert_new_bid_bin');
			//just notify
			$auction->SendMails(array($owner_user),'new_bid_bin');
			$auction->SendMails(array($my),'bin_wait_approval');
		}
	}
	/*
	* END
	*/

	/*
	* ProxyPlus checking for next priority items 
	* 
	*/
	foreach($proxyplusTOBID as $keyproxyplusTOBID=>$proxyplusTOBIDS){
		$queryCurrent = "SELECT if(max(b.bid_price)!='',max(b.bid_price),a.initial_price) as bid_next FROM jos_bids AS b RIGHT JOIN jos_bid_auctions AS a ON a.id = b.id_offer WHERE a.id = ".$proxyplusTOBIDS->auction_id." ORDER BY bid_price DESC";
		$database->setQuery($queryCurrent);
		$amountProxyPlusNew = $database->loadResult();
		$auction->writelog("<br> INSIDE saveBids1 $option,$proxyplusTOBIDS->auction_id,$task,$amount, 0, $proxyplusTOBIDS->ticket_id, $proxyplusTOBIDS->userid ");
		//OLD No Ticket ids
		saveBid($option,$proxyplusTOBIDS->auction_id,$task,$amountProxyPlusNew, $proxyplusTOBIDS->ticket_id, $proxyplusTOBIDS->userid);
	}

	/*
   	 * To Handle Redirection
	 * 
	 */
	$bestBid2=null;
	$bestBid2=$auction->GetBestBid($id_offer);

	$auction->writelog("<br> $bestBid2->userid != ".$_SESSION['__default']['user']->id);
	print_r($orgmy);
	$bestBid2=null;
	$bestBid2 = $auction->GetBestBid($id_offer);
	if($bidType == "pp" && $bestBid2->userid != $orgmy->id) {
		$redirect_link=$mosConfig_live_site."/index.php?option=com_bids&task=viewbids&id=$aidorg&Itemid=$Itemid&ou=$bidType&mosmsg=".bid_succes_special_new."#bid_list";
	}else {
		$redirect_link=$mosConfig_live_site."/index.php?option=com_bids&task=viewbids&id=$aidorg&Itemid=$Itemid&mosmsg=".bid_succes_special."#bid_list";
	}
	echo $redirect_link;
	return $redirect_link;
}

function reportAuction($option,$id){
	global $database,$my,$mosConfig_live_site,$Itemid;

	$database->setQuery("select title from #__bid_auctions where id=$id");
	$title = $database->loadResult();

	$message = mosGetParam($_POST,'message','');
	if ($message){
		$database->setQuery("insert into #__bid_report_auctions (id_offer,userid,message,modified) values ('$id','$my->id','$message',now() )");
		$database->query();
		mosRedirect($mosConfig_live_site."/index.php?option=com_bids&task=viewbids&id=$id&Itemid=$Itemid",bid_auction_reported);
		exit;
	}

	HTML_Auction_helper::reportAuction($id,$title,$Itemid);
}

function publishAuction($option,$id){
	global $database,$my,$mosConfig_live_site,$Itemid;

	$filter_archive = mosGetParam($_REQUEST,'filter_archive','0');

	$auction = new mosBidOffers($database);
	$auction->load($id);

	if ($my->id!=$auction->userid){
		mosNotAuth();
		return;
	}

	$auction->published = 1;

	$auction->store($id);
	$redirect_link = sefRelToAbs("index.php?option=com_bids&task=myauctions&Itemid=$Itemid&filter_archive=$filter_archive");
	mosRedirect($redirect_link);
}

function terms_and_conditions(){
	global $database;
	$terms=new mosBidMails($database);
	if (!$terms->load('terms_and_conditions') ) return;
	ob_clean();
	echo $terms->content;
	exit;
}

//===================================Auctions ===============================

function listMyAuctions( $option) {
	global  $database, $my;
	global $mosConfig_live_site,$mosConfig_absolute_path,$task;
	global $Itemid;

	if (!$my->id){
		mosNotAuth();
		return;
	}
	$limit 		= intval( mosGetParam( $_REQUEST, 'limit',bid_opt_nr_items_per_page ) );
	$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );

	global $filter_archive, $filter_category;
	setFilters($limitstart);

	$where = " where a.userid='$my->id'";

	if($task == "myauctionspicture") {
		$filter_archive = '3';
	}


	switch($filter_archive){
		case '0':
			$where .= "\n AND a.close_offer='0' and a.published='1'";
			if($filter_category && $filter_category!=0)
			$where .=" and a.cat=$filter_category ";
			break;
		case '1':
			$where .= "\n AND a.close_offer='0' and a.published='0'";
			if($filter_category && $filter_category!=0)
			$where .=" and a.cat=$filter_category ";
			break;
		case '2':
			$where .= "\n AND a.close_offer='1' ";
			if($filter_category && $filter_category!=0)
			$where .=" and a.cat=$filter_category ";
			break;
		case '3':
			//$where .= "\n and a.published='1' ";
			$where .= " ";
			break;
	}

	$q = "select count(*) from #__bid_auctions a $where";

	$database->setQuery($q);
	$total = $database->loadResult();

	if ( $total <= $limit ) $limitstart = 0;

	require_once( $GLOBALS['mosConfig_absolute_path'] . '/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, $limit  );

	$query = "SELECT u.username,a.*, m.name as payment_name,c.name as currency_name,cats.catname, cats.id as cid
			FROM #__bid_auctions as a
			left join #__bid_payment as m on a.payment=m.id
			left join #__bid_currency as c on a.currency=c.id
			left join #__users as u on a.userid=u.id
			left join #__bid_categories as cats on a.cat=cats.id ";
	if($filter_archive == '3') {
		$query .= "
			$where order by a.title + 0, a.title ASC 
			LIMIT $pageNav->limitstart, $pageNav->limit";

	}else {
		$query .= "
			$where order by id desc 
			LIMIT $pageNav->limitstart, $pageNav->limit";
	}
	$database->setQuery( $query );
	$rows = $database->loadObjectList();

	$profile = new mosComProfiler($database);
	$manaInfo = $profile->getManagerInfo();
	

		//Added for finding next bid 
		//JaiStartI
		foreach ($rows as $keyTemp=>$rowTemp) {
			//Added for start date
			if($manaInfo->cb_buyerschoice == "Buyer's Choice") {
				$rows[$keyTemp]->start_date = $manaInfo->cb_startdate;
				$rows[$keyTemp]->end_date  = $manaInfo->cb_enddate;
			}

			$queryBids = "SELECT * FROM #__bids AS d JOIN
			(
			  SELECT id, id_offer, max(bid_price) AS bid_price
			  FROM #__bids  GROUP BY id_offer) AS d1  ON (d.id_offer = d1.id_offer AND d.bid_price = d1.bid_price) WHERE d.id_offer = ".$rowTemp->id;
			 $database->setQuery($queryBids);
			 
			 if($rowsbid=$database->loadObjectList()) {
				$rows[$keyTemp]->bid_price = $rowsbid[0]->bid_price;
				$rows[$keyTemp]->current_bidder = $rowsbid[0]->userid;
				$rows[$keyTemp]->bid_next = $rowsbid[0]->bid_price + findBidIncrement($rowsbid[0]->bid_price);
			 }else{
				 $rows[$keyTemp]->bid_price = "";
				 $rows[$keyTemp]->bid_next = $rows[$keyTemp]->initial_price;
			 }
			 //Added for Finding proxy bids
			$queryProxyBids = "SELECT auction_id, max(max_proxy_price) as proxy_price FROM #__bid_proxy AS bp WHERE auction_id = ".$rowTemp->id." AND bp.user_id = $my->id";
			
			 $database->setQuery($queryProxyBids);
			 
			 if($rowsProxyBids = $database->loadObjectList()) {
				$rows[$keyTemp]->proxy_price = $rowsProxyBids[0]->proxy_price;
			 }else {
				 $rows[$keyTemp]->proxy_price = 0;
			 }
			 //Added for Finding proxy bids
			$queryProxyPlusBids = "SELECT auction_id, max(my_bid) as proxyplus_price FROM #__proxyplus_bids AS bp  JOIN #__proxyplus_tickets bpt ON (bp.ticket_id = bpt.id) WHERE auction_id = ".$rowTemp->id." AND bpt.userid = $my->id";
			
			 $database->setQuery($queryProxyPlusBids);
			 
			 if($rowsProxyPlusBids = $database->loadObjectList()) {
				$rows[$keyTemp]->proxyplus_price = $rowsProxyPlusBids[0]->proxyplus_price;
			 }else {
				 $rows[$keyTemp]->proxyplus_price = 0;
			 }
		}
		//End StartI


	$archive = array();
	$lists = array();

	$archive[] = mosHTML::makeOption( '0', bid_active_offers, 'filter_archive','text');
	$archive[] = mosHTML::makeOption( '1', bid_my_unpublished_offers, 'filter_archive','text');
	$archive[] = mosHTML::makeOption( '2', bid_view_archive, 'filter_archive','text');
	$lists['archive'] = mosHTML::selectList($archive,'filter_archive','class="inputbox" size="1" onchange="document.auctionForm.submit();"','filter_archive','text',$filter_archive);
	$cats = makeCatTree();
	if (count($cats)>0){
		$cats=array_merge(array(mosHTML::makeOption( 0, bid_all_categories)),$cats);
		$lists['filter_cats'] = mosHTML::selectList($cats,'cat','class="inputbox"  style="width:190px;" onchange="document.auctionForm.submit();"','value', 'text',$filter_category);
	}
	else
	$lists['filter_cats'] ='&nbsp;';
	HTML_Auction::listAuctions( $rows,  $lists,$pageNav);
}
function listMyWatchlist( $option) {
	global  $database, $my;
	global $mosConfig_live_site,$mosConfig_absolute_path,$task;
	global $Itemid;

	if (!$my->id){
		mosNotAuth();
		return;
	}

	$limit 		 = intval( mosGetParam( $_REQUEST, 'limit', bid_opt_nr_items_per_page ) );
	$limitstart  = intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );

	$filter_bidtype 	= intval(mosGetParam($_REQUEST,'filter_bidtype',0));
	$filter_order 	= mosGetParam($_REQUEST,'filter_order','newest_first');
	$filter_order_asc 	= intval(mosGetParam($_REQUEST,'filter_order_asc',1));
	$filter_userid 	= intval(mosGetParam($_REQUEST,'userid',0));
	$filter_archive = mosGetParam($_REQUEST,'filter_archive','');
	$filter_category = intval(mosGetParam($_REQUEST,'cat',''));
	$where = " where w.userid='$my->id' ";
	if($task == "mywatchlistpicture") {
		$filter_archive = 4;
	}

	
	switch($filter_archive){
		case '1':
			$where .= "\n AND a.close_offer='1' and a.published='1'";
			if($filter_category && $filter_category!=0)
			$where .=" and a.cat=$filter_category ";
			break;
		case '0':
			$where .= "\n AND a.close_offer='0' and a.published='1'";
			if($filter_category && $filter_category!=0)
			$where .=" and a.cat=$filter_category ";
			break;
		case '3':
			$where .= "\n AND a.close_offer='1'";
			if($filter_category && $filter_category!=0)
			$where .=" and a.cat=$filter_category ";
			break;
		case '4':
			$where .= "\n AND a.published='1' ";
			break;
	}

	$q = "select count(*) from #__bid_watchlist w
	   $where ";
	$database->setQuery($q);
	$total = $database->loadResult();

	if ( $total <= $limit ) $limitstart = 0;

	require_once( $GLOBALS['mosConfig_absolute_path'] . '/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, $limit  );

	$query = "SELECT u.username,a.*, m.name as payment_name,c.name as currency_name,cats.catname
			FROM #__bid_auctions as a
			left join #__bid_payment as m on a.payment=m.id
			left join #__bid_currency as c on a.currency=c.id
			left join #__bid_watchlist as w on a.id=w.auctionid
			left join #__users as u on a.userid = u.id
			left join #__bid_categories as cats on a.cat=cats.id";
	 if($task == "mywatchlistpicture") {
		 $query .=  "$where  and '$my->id'=w.userid order by a.title + 0, a.title ASC 
			LIMIT $pageNav->limitstart, $pageNav->limit";
	 } else {
			$query .=  "$where  and '$my->id'=w.userid order by id desc
			LIMIT $pageNav->limitstart, $pageNav->limit";
	 }
	$database->setQuery( $query );
	$rows = $database->loadObjectList();
	$profile = new mosComProfiler($database);
	$manaInfo = $profile->getManagerInfo();
		
		//Added for finding next bid 
		//JaiStartI
		foreach ($rows as $keyTemp=>$rowTemp) {
			//Added for start date
			if($manaInfo->cb_buyerschoice == "Buyer's Choice") {
				$rows[$keyTemp]->start_date = $manaInfo->cb_startdate;
				$rows[$keyTemp]->end_date  = $manaInfo->cb_enddate;
			}

			$queryBids = "SELECT * FROM #__bids AS d JOIN
			(
			  SELECT id, id_offer, max(bid_price) AS bid_price
			  FROM #__bids  GROUP BY id_offer) AS d1  ON (d.id_offer = d1.id_offer AND d.bid_price = d1.bid_price) WHERE d.id_offer = ".$rowTemp->id;
			 $database->setQuery($queryBids);
			 
			 if($rowsbid=$database->loadObjectList()) {
				$rows[$keyTemp]->bid_price = $rowsbid[0]->bid_price;
				$rows[$keyTemp]->current_bidder = $rowsbid[0]->userid;
				$rows[$keyTemp]->bid_next = $rowsbid[0]->bid_price + findBidIncrement($rowsbid[0]->bid_price);
				if($rowsbid[0]->bid_price == 0) {
					$rows[$keyTemp]->current_bidder = "";
					$rows[$keyTemp]->bid_price = "";
					$rows[$keyTemp]->bid_next = $rows[$keyTemp]->initial_price;
				}
			 }else{
				 $rows[$keyTemp]->bid_price = "";
				 $rows[$keyTemp]->bid_next = $rows[$keyTemp]->initial_price;
			 }
			 //Added for Finding proxy bids
			$queryProxyBids = "SELECT auction_id, max(max_proxy_price) as proxy_price FROM #__bid_proxy AS bp WHERE auction_id = ".$rowTemp->id." AND bp.user_id = $my->id";
			
			 $database->setQuery($queryProxyBids);
			 
			 if($rowsProxyBids = $database->loadObjectList()) {
				$rows[$keyTemp]->proxy_price = $rowsProxyBids[0]->proxy_price;
			 }else {
				 $rows[$keyTemp]->proxy_price = 0;
			 }
			 //Added for Finding proxy bids
			$queryProxyPlusBids = "SELECT auction_id, max(my_bid) as proxyplus_price FROM #__proxyplus_bids AS bp  JOIN #__proxyplus_tickets bpt ON (bp.ticket_id = bpt.id) WHERE auction_id = ".$rowTemp->id." AND bpt.userid = $my->id";
			
			 $database->setQuery($queryProxyPlusBids);
			 
			 if($rowsProxyPlusBids = $database->loadObjectList()) {
				$rows[$keyTemp]->proxyplus_price = $rowsProxyPlusBids[0]->proxyplus_price;
			 }else {
				 $rows[$keyTemp]->proxyplus_price = 0;
			 }
		}
		//End StartI


	$archive = array();
	$lists = array();

	$orders[] = mosHTML::makeOption('start_date',bid_sort_newest);
	$orders[] = mosHTML::makeOption('initial_price',bid_sort_initialprice);
	$orders[] = mosHTML::makeOption('BIN_price',bid_sort_binprice);
	$orders[] = mosHTML::makeOption('end_date',bid_sort_end_date);
	$orders[] = mosHTML::makeOption('name',bid_sort_username);
	$lists['orders'] = mosHTML::selectList($orders,'filter_order',
	'class="inputbox" onchange="document.auctionForm.submit();"','value', 'text',$filter_order);

	$bid_type[] = mosHTML::makeOption( 0, bid_filter_available);
	$bid_type[] = mosHTML::makeOption( 1, bid_filter_archive);


	$lists['filter_archive'] =  mosHTML::selectList($bid_type,'filter_archive',
	'class="inputbox" onchange="document.auctionForm.submit();"','value', 'text',$filter_archive);

	$ord_desc[] = mosHTML::makeOption( 1, bid_order_desc);
	$ord_desc[] = mosHTML::makeOption( 2, bid_order_asc);
	$lists['filter_order_asc'] =  mosHTML::selectList($ord_desc,'filter_order_asc',
	'class="inputbox" onchange="document.auctionForm.submit();"','value', 'text',$filter_order_asc);

	$lists['filter_userid']=$filter_userid;

	$cats = makeCatTree();
	if (count($cats)>0){
		$cats=array_merge(array(mosHTML::makeOption( 0, bid_all_categories)),$cats);
		$lists['filter_cats'] = mosHTML::selectList($cats,'cat','class="inputbox"  style="width:190px;" onchange="document.auctionForm.submit();"','value', 'text',$filter_category);
	}
	else
	$lists['filter_cats'] ='&nbsp;';
	HTML_Auction::listAuctions( $rows,  $lists,$pageNav);
}

function CancelAuction($id,$option){
	global $Itemid,$mosConfig_live_site;
	global $database, $my,$task;

	@ignore_user_abort(true);
	$auction = new mosBidOffers( $database );
	// load the row from the db table
	$auction->load( $id );
	if (!$auction->acl_check($my) ){
		mosNotAuth();
		return;
	}
	$auction->close_offer=1;
	$auction->closed_date=date("Y-m-d H:i:s",time());

	if ($auction->store()){
		//Get Bidders
		$q = "SELECT distinct u.*
    		  FROM #__users u
              left join  #__bids b on b.userid=u.id
    		  WHERE b.id_offer = $id AND b.cancel=0";
		$database->setQuery($q);
		$usermails = $database->loadObjectList();
		$query = "SELECT u.* from #__users u
				left join #__bid_watchlist w on u.id = w.userid
				where w.auctionid = $id";
		$database->setQuery($query);
		$watchlist_mails = $database->loadObjectList();

		$auction->SendMails($watchlist_mails,'bid_watchlist_canceled');
		$auction->SendMails($usermails,'bid_canceled');

		$msg=bid_auction_was_canceled;
	}else{
		$msg=bid_err_auction_was_canceled_failed;
	}
	mosRedirect( "$mosConfig_live_site/index.php?option=$option&task=myauctions&Itemid=$Itemid",$msg );
}

function editAuction($id,$option,$task=''){
	global $mosConfig_absolute_path,$Itemid;
	global $database, $my;

	$row = new mosBidOffers( $database );
	// load the row from the db table
	if ($id) $row->load( $id );
	if (!$row->acl_check($my) ){
		mosNotAuth();
		return;
	}

	if ($task=='republish'){
		$row->id=null; //republish

	}
	if (!$id){
		if(!isset($_SESSION)) session_start();
		if (isset($_SESSION['auction_temp'])){
			$row=unserialize($_SESSION['auction_temp']);
			$row->id=0;
		}
	}

	$lists = array();
	$query = "SELECT id as payment, name"
	. "\n FROM #__bid_payment"
	. "\n ORDER BY id"
	;
	$pays[] = mosHTML::makeOption('0',bid_types_payment,'payment','name');
	$database->setQuery( $query );
	$pays = array_merge($pays, $database->loadObjectList());

	$lists['payment'] = mosHTML::selectList( $pays, 'payment', 'class="inputbox" alt="payment"', 'payment', 'name', intval($row->payment));
	$lists['published'] = mosHTML::yesnoradioList( 'published', 'alt="published"', ($id)?$row->published:1,_CMN_YES, _CMN_NO );

	$opts[] = mosHTML::makeOption('0',bid_auction_without_bin);
	$opts[] = mosHTML::makeOption('1',bid_auction_with_bin);
	$lists['bin']=	mosHTML::selectList( $opts, 'bin_OPTION', 'class="inputbox" onchange="changeBIN();"', 'value','text',  ($row->BIN_price>0?1:0));
	$opts=null;
	$opts[] = mosHTML::makeOption('',bid_pick_type_of_auction);
	$opts[] = mosHTML::makeOption(AUCTION_TYPE_PUBLIC,bid_public_label);
	$opts[] = mosHTML::makeOption(AUCTION_TYPE_PRIVATE,bid_private_label);
	$lists['auctiontype']=	mosHTML::selectList( $opts, 'auction_type', 'class="inputbox" alt="auction_type"',  'value', 'text',$row->auction_type);

	$query = "SELECT id as value, name as text "
	. "\n FROM #__bid_currency"
	. "\n ORDER BY id"
	;
	$opts=null;
	$database->setQuery( $query );
	$opts = $database->loadObjectList();
	$lists['currency']=	mosHTML::selectList( $opts, 'currency', 'class="inputbox" size="1"',  'value', 'text',$row->currency);

	$cats = makeCatTree();
	if (count($cats)>0)
	$lists['cats'] = mosHTML::selectList($cats,'cat','class="inputbox"  style="width:190px;" ','value', 'text',$row->cat);
	else
	$lists['cats']  ='&nbsp;';
	$tags_obj=new mosBidTags($database);
	$tags = null;
	$tags=$tags_obj->getAuctionTags($id);
	if (count($tags)) $row->tags=implode(',',$tags);
	HTML_Auction::editAuction( $row, $option, $lists,$id);
}
function ValidateSaveAuction()
{
	global $database,$my;

	$error_msg="";
	$auction=new mosBidOffers($database);

	$auction->bind($_POST,'_allowed_picture_ext');
	$oldid = mosGetParam($_REQUEST,'oldid',0);
	if ($oldid && !$auction->id) $isRepost=true;else $isRepost=false;

	$end_hour = mosGetParam($_REQUEST,'end_hour','00');
	$end_minutes = mosGetParam($_REQUEST,'end_minutes','00');
	/* JaiStartC */
	$start_hour = mosGetParam($_REQUEST,'start_hour','00');
	$start_minutes = mosGetParam($_REQUEST,'start_minutes','00');
    /* JaiEnd C */
	$auction->start_date=auctionDatetoIso($auction->start_date);
	$auction->end_date=auctionDatetoIso($auction->end_date);
	
	$start_date = $auction->start_date;
	$end_date = $auction->end_date;

	if($start_date && $end_date && !mosGetParam($_REQUEST,'id',0)){
		if(strlen($start_date)<10||strlen($start_date)>10){
			$error_msg.=bid_not_allowed_start_date_format."\n";
		}
		if(strlen($end_date)<10||strlen($end_date)>10){
			$error_msg.=bid_not_allowed_end_date_format."\n";
		}
		/* JaiStartC */
		if(strlen($end_hour) < 0 || strlen($end_hour) > 24){
			$error_msg.=bid_not_allowed_end_date_format."\n";
		}
		if(strlen($start_hour) < 0 || strlen($start_hour) > 24){
			$error_msg.=bid_not_allowed_start_date_format."\n";
		}
		if(strlen($end_minutes) < 0 || strlen($end_minutes) > 60){
			$error_msg.=bid_not_allowed_end_date_format."\n";
		}
		if(strlen($start_minutes) < 0 || strlen($start_minutes) > 60){
			$error_msg.=bid_not_allowed_start_date_format."\n";
		}
		/* JaiEndC */
		$year = date("Y",strtotime($start_date));
		$year_end = date("Y",strtotime($end_date));
		$month = date("m",strtotime($start_date));
		$month_end = date("m",strtotime($end_date));
		$day = date("d",strtotime($start_date));
		$day_end = date("d",strtotime($end_date));
		if($year<1900 && $year>2200) {
			$error_msg.=bid_not_valid_start_date_year."\n";
		}
		if($month<1 && $month>12) {
			$error_msg.=bid_not_valid_start_date_month."\n";
		}
		if($day<1 && $day>31) {
			$error_msg.=bid_not_valid_start_date_day."\n";
		}
		if($year_end<1900 && $year_end>2200) {
			$error_msg.=bid_not_valid_start_date_year."\n";
		}
		if($month_end<1 && $month_end>12) {
			$error_msg.=bid_not_valid_start_date_month."\n";
		}
		if($day_end<1 && $day_end>31) {
			$error_msg.=bid_not_valid_start_date_day."\n";
		}

		$datedif = mktime(0,0,0,$month_end,$day_end,$year_end) - mktime(0,0,0,$month,$day,$year);
		if(bid_opt_availability>0)
		if( floor($datedif/60/60/24)>=bid_opt_availability*31){
			$error_msg.=bid_not_valid_date_interval.": ".bid_opt_availability."\n";
		}
	
	}

	if($auction->id){
		// was  Edit Auction
		$auction->load($auction->id);
		if($my->id!=$auction->userid){
			$error_msg.=bid_err_invalid_offerid."\n";
		}
		$auction->shortdescription=mosGetParam($_POST,'shortdescription','');
		$auction->description=mosGetParam($_POST,'description','',_MOS_ALLOWHTML);
		$auction->link_extern=mosGetParam($_POST,'link_extern','');
		$auction->shipment_info=mosGetParam($_POST,'shipment_info','');
		$auction->published=mosGetParam($_POST,'published','0');
		//if (!$auction->published)
		$query = "select name from #__bid_currency where id = $auction->currency";
		$database->setQuery($query);
		if (!$database->loadResult()){
			//if currencies got modified inbetween
			$query = "select id from #__bid_currency limit 1";
			$database->setQuery($query);
			$auction->currency=$database->loadResult();
		}
	}else{
		$auction->auction_nr=time().rand(100,999);
	}
	$auction->modified=date("Y-m-d H:i:s",time());
	$auction->userid=$my->id;
	$auction->SetAllParams($_POST);
	$profile = new mosComProfiler($database);
	$manaInfo = $profile->getManagerInfo();
	//Added for start date
	if($manaInfo->cb_buyerschoice == "Buyer's Choice") {
		$auction->start_date = $manaInfo->cb_startdate;
		$auction->end_date  = $manaInfo->cb_enddate;
	}
	if(!preg_match("/[0-9]+:[0-9]+/",$auction->start_date)) {
		$auction->start_date = $auction->start_date." " .$start_hour.":".$start_minutes;
		$auction->end_date = $auction->end_date." " .$end_hour.":".$end_minutes;
	}
	if (is_array($errors = $auction->check())) {
		$error_msg.=htmlentities(implode('\n',$errors),ENT_QUOTES);
	}

	if ($auction->close_offer){
		$error_msg.=bid_auction_is_closed."\n";
	}
	//Required Image
	$nrfiles=0;
	if (count($_FILES))
	foreach($_FILES as $k=>$file){
		if (substr($k,0,7)!="picture") continue;
		if( !is_uploaded_file(@$file['tmp_name'])) continue;
		if(!bid_opt_resize_if_larger && filesize($file['tmp_name'])>bid_opt_max_picture_size*1024){
			continue;
		}

		$fname=$file['name'];
		$ext=extract_file_ext($fname);

		if(!$auction->isAllowedImage($ext)){
			continue;
		}
		$nrfiles++;
	}
	if (!$auction->id && !$isRepost && !$nrfiles && bid_opt_require_picture){
		$error_msg.=bid_err_picture_is_required."\n";
	}
	return $error_msg;
}
function saveAuction($id,$option) {
	global $database,$Itemid,$mosConfig_live_site,$my,$mosConfig_absolute_path;

	$errors=ValidateSaveAuction();
	if ($errors) {
		if (!mosGetParam($_REQUEST,'id',0)){
			session_start();
			$auction=new mosBidOffers($database);
			$auction->bind($_POST,'allowed_picture_ext');
			$_SESSION['auction_temp']=serialize($auction);

		}
		$errors=str_replace(array("\n","\r")," ",$errors);
		echo "<script>alert('".htmlentities($errors)."');history.go(-1);</script>";
		exit;
	}
	session_start();
	$_SESSION['auction_temp']=null;
	$auction=new mosBidOffers($database);

	$auction->bind($_POST,'allowed_picture_ext');
	$auction->picture = null;
	$oldid = mosGetParam($_REQUEST,'oldid',0);
	$delete_pictures=mosGetParam( $_POST,'delete_pictures',array());
	$delete_main_picture = mosGetParam( $_POST,'delete_main_picture','');

	$end_hour = mosGetParam($_REQUEST,'end_hour','00');
	$end_minutes = mosGetParam($_REQUEST,'end_minutes','00');
	$auction->start_date = auctionDatetoIso(mosGetParam($_REQUEST,'start_date',$auction->start_date));
	$auction->end_date = auctionDatetoIso(mosGetParam($_REQUEST,'end_date',$auction->end_date));

	if ($oldid && !$auction->id) $isRepost=true;else $isRepost=false;
	$auction->end_date.=" $end_hour:$end_minutes";
	/* JaiStartC */
	$start_hour = mosGetParam($_REQUEST,'start_hour','00'); 
	$start_minutes = mosGetParam($_REQUEST,'start_minutes','00');
	$auction->start_date .= " $start_hour:$start_minutes";
	/* JaiEndC */

	if (!isset($_POST['automatic'])) $auction->automatic=0;

	if($auction->id){
		// was  Edit Auction
		$auction->load($auction->id);
		if ($my->id!=$auction->userid) {
			mosNotAuth();
			exit();
		}
		$auction->shortdescription = mosGetParam($_POST,'shortdescription','');
		$auction->description=mosGetParam($_POST,'description','',_MOS_ALLOWHTML);
		$auction->link_extern=mosGetParam($_POST,'link_extern','');
		$auction->shipment_info=mosGetParam($_POST,'shipment_info','');
		$auction->shipment_price=mosGetParam($_POST,'shipment_price','');
		$auction->custom_fld1=mosGetParam($_POST,'custom_fld1','');
		$auction->custom_fld2=mosGetParam($_POST,'custom_fld2','');
		$auction->custom_fld3=mosGetParam($_POST,'custom_fld3','');
		$auction->custom_fld4=mosGetParam($_POST,'custom_fld4','');
		$auction->custom_fld5=mosGetParam($_POST,'custom_fld5','');
		$auction->published=mosGetParam($_POST,'published','0');
		//if (!$auction->published) 
		$query = "select name from #__bid_currency where id = $auction->currency";
		$database->setQuery($query);
		if (!$database->loadResult()){
			//if currencies got modified inbetween
			$query = "select id from #__bid_currency limit 1";
			$database->setQuery($query);
			$auction->currency=$database->loadResult();
		}

	}else{
		$auction->auction_nr=time().rand(100,999);
	}
	$auction->min_increase = mosGetParam($_POST,'min_increase',0); //
	
	if($auction->min_increase == 0) {
		$auction->min_increase = bid_opt_min_increase;
	}
	
	$auction->modified=date("Y-m-d H:i:s",time());
	$auction->userid=$my->id;
	$auction->SetAllParams($_POST);

	if(isset($_POST['cat'])){
		$auction->cat = mosGetParam($_POST,'cat','');
	}
	/* JaiStartD */
	$auction->start_date = auctionDatetoIso(mosGetParam($_REQUEST,'start_date',$auction->start_date));
	$auction->end_date = auctionDatetoIso(mosGetParam($_REQUEST,'end_date',$auction->end_date));
	$auction->end_date.=" $end_hour:$end_minutes";
	$auction->start_date .= " $start_hour:$start_minutes";
	/* JaiEndD */
	
	$auction->store();

	//Set Auction Tags
	$tags=mosGetParam($_REQUEST,'tags','');
	$tag_obj=new mosBidTags($database);
	$tag_obj->setAuctionTags($auction->id,$tags);

	if($isRepost){

		$database->setQuery("select count(*) from #__bid_auctions where id='$oldid' and userid='$my->id'");
		if (!$database->loadResult()){
			mosNotAuth();
			exit;
		}

		$database->setQuery("select picture from #__bid_auctions where id=$oldid");
		$oldpic = $database->loadResult();

		$new_id = $auction->id;

		if(!empty($oldpic) && !$delete_main_picture){
			if(file_exists(AUCTION_PICTURES_PATH.$oldpic)){

				if (strpos($oldpic,$oldid."_")===0)
				$buf = substr($oldpic,strlen($oldid),strlen($oldpic));
				else
				$buf=$oldpic;

				$new_pic_name =  $new_id."_".$buf;
				copy(AUCTION_PICTURES_PATH.$oldpic,AUCTION_PICTURES_PATH.$new_pic_name);
				copy(AUCTION_PICTURES_PATH."middle_$oldpic",AUCTION_PICTURES_PATH."middle_$new_pic_name");
				copy(AUCTION_PICTURES_PATH."resize_$oldpic",AUCTION_PICTURES_PATH."resize_$new_pic_name");
				$auction->picture = $new_pic_name;
				$auction->store();
			}
		}
		if ($delete_main_picture){
			$auction->picture = '';
			$auction->store();

		}

		$database->setQuery("select * from #__bid_pictures where id_offer=$oldid");
		$pictures = $database->loadObjectList();
		for($i=0;$i<count($pictures);$i++){
			$ext = substr($pictures[$i]->picture,strpos($pictures[$i]->picture,'.')+1,strlen($pictures[$i]->picture));
			if($auction->isAllowedImage($ext) && !in_array($pictures[$i]->id,$delete_pictures)){

				if(file_exists(AUCTION_PICTURES_PATH.$pictures[$i]->picture)){
					$pic=new mosBidPicture($database);

					$pic->id_offer=$auction->id;
					$pic->userid=$auction->userid;
					$pic->modified=date('Y-m-d');
					$pic->store();

					$pic->picture=$auction->id."_img_$pic->id.$ext";
					$pic->store();

					copy(AUCTION_PICTURES_PATH.$pictures[$i]->picture,AUCTION_PICTURES_PATH.$pic->picture);
					copy(AUCTION_PICTURES_PATH."middle_".$pictures[$i]->picture,AUCTION_PICTURES_PATH."middle_".$pic->picture);
					copy(AUCTION_PICTURES_PATH."resize_".$pictures[$i]->picture,AUCTION_PICTURES_PATH."resize_".$pic->picture);
				}
			}
		}
	}

	$msg="";
	$nrfiles=0;
	foreach($_FILES as $k=>$file){
		if (substr($k,0,7)!="picture") continue;
		if( !is_uploaded_file(@$file['tmp_name'])) continue;
		if(!bid_opt_resize_if_larger && filesize($file['tmp_name'])>bid_opt_max_picture_size*1024){
			$msg.=$file['name']."- ".bid_err_imagesize_too_big."<br><br>";
			continue;
		}

		if (!file_exists(AUCTION_PICTURES_PATH)) @mkdir(AUCTION_PICTURES_PATH,0755);
		$fname=$file['name'];
		$ext=extract_file_ext($fname);

		if(!$auction->isAllowedImage($ext)){
			$msg .= bid_err_not_allowed_ext.': '.$file['name'];
			continue;
		}
		if ($k=="picture_main") {
			$file_name=$auction->id."_".$fname.'.'.$ext;
			$auction->picture=$file_name;
			$auction->store();
		}else {
			if ($nrfiles>=bid_opt_maxnr_images) continue;
			$file_name=$fname.'.'.$ext;
			$pic=new mosBidPicture($database);
			$pic->id_offer=$auction->id;
			$pic->userid=$my->id;
			$pic->picture=$file_name;
			$pic->modified=date("Y-m-d H:i:s",time());
			$pic->store();

			$file_name=$auction->id."_img_$pic->id.$ext";
			$pic->picture=$file_name;
			$pic->store();
			$nrfiles++;
		}

		$path= AUCTION_PICTURES_PATH."/$file_name";
		if (bid_opt_resize_if_larger && filesize($file['tmp_name'])>bid_opt_max_picture_size*1024)
		$res=resize_to_filesize($file['tmp_name'], $path,bid_opt_max_picture_size*1024);
		else
		$res=move_uploaded_file($file['tmp_name'], $path);
		if($res) {
			@chmod($path,0755);
			resize_image($file_name,bid_opt_thumb_width,bid_opt_thumb_height,'resize');
			resize_image($file_name,bid_opt_medium_width,bid_opt_medium_height,'middle');
		}else{
			$msg.=$file['name']."- ".bid_err_upload_failed."<br><br>";
		}
	}

	if(!$isRepost){
		if($delete_main_picture){
			$query = "select picture from #__bid_auctions where id=$id";
			$database->setQuery($query);
			$main_pic = $database->loadResult();

			$query = "update #__bid_auctions set picture = '' where id = $id";
			$database->setQuery($query);
			$database->query();

			@unlink(AUCTION_PICTURES_PATH.$main_pic);
			@unlink(AUCTION_PICTURES_PATH."small_".$main_pic);
			@unlink(AUCTION_PICTURES_PATH."middle_".$main_pic);
		}

		foreach($delete_pictures as $dele_id){
			$query = "select picture from #__bid_pictures where id=$dele_id and userid=$my->id";
			$database->setQuery($query);
			$pic = $database->loadResult();

			$query="delete from #__bid_pictures where id='$dele_id' and userid='$my->id'";
			$database->setQuery($query);
			$database->query();

			@unlink(AUCTION_PICTURES_PATH.$pic);
			@unlink(AUCTION_PICTURES_PATH."small_".$pic);
			@unlink(AUCTION_PICTURES_PATH."middle_".$pic);

		}
	}
	if (!$msg) $msg=bid_succes;
	mosRedirect( $mosConfig_live_site."/index.php?option=$option&task=viewbids&id=$auction->id&Itemid=$Itemid",$msg );
}

//JaiStartH 
function saveProxyPlus($id,$option) {
	global $database,$Itemid,$mosConfig_live_site,$my,$mosConfig_absolute_path,$task;

	
	$errors=ValidateSaveAuction();
	//Jai TODO
 	$errors = false;
	 if ($errors) {
		if (!mosGetParam($_REQUEST,'id',0)){
			session_start();
			$auction=new mosBidOffers($database);
			$auction->bind($_POST,'allowed_picture_ext');
			$_SESSION['auction_temp']=serialize($auction);

		}
		$errors=str_replace(array("\n","\r")," ",$errors);
		echo "<script>alert('".htmlentities($errors)."');history.go(-1);</script>";
		exit;
	}
	session_start();
	$_SESSION['auction_temp']=null;
	$auction=new mosBidOffers($database);

	$proxyTicket = new mosProxyPlusTicket($database);
	$type = mosGetParam($_REQUEST,'type',0);
	$proxyTicket->id = NULL;
	if($type == 2) {
		$proxyTicket->_tbl_key='id';
		$proxyTicket->id = mosGetParam($_REQUEST,'ticketid',0);
    }else {
		$countickets = 0;
		$database->setQuery("select count(id) as countickets from #__proxyplus_tickets a where a.userid=$my->id");
		$countickets = $database->loadResult();
		$proxyTicket->ticket_id = $countickets+1;
		$proxyTicket->userid = $my->id;
	}
	$proxyTicket->lot_desired = mosGetParam($_REQUEST,'lotdesired',0);
	$proxyTicket->datemodified = date("Y-m-d H:i:s");
	$proxyTicket->store();
	$currentTicketId = $database->insertid();
	$countLotsMax = mosGetParam($_REQUEST,'maxid',0);
	$countLots = mosGetParam($_REQUEST,'currentid',0);
	$countLots = ($countLotsMax > $countLots)?$countLotsMax:$countLots;
	$bidArr= array();
	$priority = 0;
	if($countLots > 0) {
		$proxyPlusBid = new mosBidProxyPlusBids($database);
		$ticketidexisting = mosGetParam($_REQUEST,'ticketid',0);
		$proxyplustype = mosGetParam($_REQUEST,'proxyplustype',"new");
		if($proxyplustype != "new"){
			$proxyPlusBid->setDelete($ticketidexisting);
		}
		for($i=1; $i <= 20;$i++) {
			$proxyPlusBid->id = NULL;
			$proxyPlusBid->ticket_id = $currentTicketId;
			$proxyPlusBid->auction_id = mosGetParam($_REQUEST,"auction_id$i",0);
			if($proxyPlusBid->auction_id > 0 && !in_array($proxyPlusBid->auction_id,$bidArr)){
				$bidArr[] = $proxyPlusBid->auction_id;
				$priority++;
			}else{
				continue;
			}
			$proxyPlusBid->priority		= $priority;
			$proxyPlusBid->my_bid = mosGetParam($_REQUEST,"mybid$i",0);
			$proxyPlusBid->datemodified = date("Y-m-d H:i:s");
			/*
			* Jai - TODO - commented not to update - delete all records & insert
			* 06-12-2009
			*/
 			if($type == 2) {
				$proxyPlusBid->ticket_id = $ticketidexisting;
			}
		  	if($proxyPlusBid->auction_id != 0) {
				$stored = true;
				$proxyPlusBid->store();
			}
		}
	}

	/*
	*  JaiSTARTI - ProxyPlus Bids if already sale has started
	*  ONly for this ticket
	*/
	$profile = new mosComProfiler($database);
	$manaInfo = $profile->getManagerInfo();
	//Added for start date
	if($manaInfo->cb_buyerschoice == "Buyer's Choice") {
		$start_date = $manaInfo->cb_startdate;
		$end_date  = $manaInfo->cb_enddate;
	}
	
	if($stored && strtotime($start_date) <= time()){
		$proxyPlusBids = array();
		$rowsProxyPlusbids = array();
		$query  = "SELECT lot_desired FROM #__proxyplus_tickets ppt WHERE ppt.id = ".$proxyPlusBid->ticket_id;
		$database->setQuery( $query);
		$lotdesired = $database->loadResult();

		$query  = "SELECT * FROM #__proxyplus_tickets ppt JOIN #__proxyplus_bids b ON (ppt.id = b.ticket_id)  WHERE ppt.id = ".$proxyPlusBid->ticket_id ." AND b.outbid = 0 ORDER BY b.priority LIMIT ".$lotdesired;
		$database->setQuery( $query);
		$rowsProxyPlusbids = $database->loadObjectList();

		if(count($rowsProxyPlusbids)) {
			foreach($rowsProxyPlusbids as $rowProxyPlusbids) {
				$proxyPlusBids[$rowProxyPlusTickets->userid] = array(
													"id"=>$rowProxyPlusbids->id,
													"ticket_id"=>$rowProxyPlusbids->ticket_id,
													"auction_id"=>$rowProxyPlusbids->auction_id,
													"my_bid"=>$rowProxyPlusbids->my_bid,
													"priority"=>$rowProxyPlusbids->priority,
													"datemodified"=>$rowProxyPlusbids->datemodified,
													"outbid"=>$rowProxyPlusbids->outbid,
													"userid"=>$rowProxyPlusTickets->userid
												  );


				$queryPrice  = "SELECT a.id as auction_id, b.id as id, id_offer,  b.userid, bid_price,  IF(b.bid_price > 0,b.bid_price + a.min_increase, initial_price)  as bid_next, a.shortdescription  FROM #__bids AS b RIGHT JOIN #__bid_auctions AS a ON a.id = b.id_offer WHERE a.id = '".$rowProxyPlusbids->auction_id."' ORDER BY bid_price DESC LIMIT 1";
				$database->setQuery($queryPrice);
				$rows = $database->loadObjectList();
				$lists = array();
				if(count($rows) > 0) {
					foreach($rows as $row) {
						$amount = ($row->bid_price > 0)?($row->bid_price + findBidIncrement($row->bid_price)):$row->bid_next;
					}
				}
				//Jai commented previous line for testing, It is used correctly
				echo "<pre>";
				echo "<br>".$option, $rowProxyPlusbids->auction_id, $task, $amount, $rowProxyPlusbids->ticket_id, $rowProxyPlusbids->userid;
				saveBid($option,$rowProxyPlusbids->auction_id,$task,$amount, $rowProxyPlusbids->ticket_id, $rowProxyPlusbids->userid);
				//saveBid($option,$rowProxyPlusbids->auction_id,$task,$amount, 0, 0, $rowProxyPlusTickets->userid);
				//saveBid($option,$rowProxyPlusbids->auction_id,$task,$amount, 0, 0, 0);
			}
		}
	}
	/*
	* JaiENDI
	*/
	if (!$msg) $msg=bid_succes;
	mosRedirect( $mosConfig_live_site."/index.php?option=$option&task=listproxyticket&ticketid=$proxyPlusBid->ticket_id",$msg );
}
//JaiStartI
function saveNextBid($id,$option) {
	global $database,$Itemid,$mosConfig_live_site,$my,$mosConfig_absolute_path,$my;
	if($my->gid == 1) {
		mosRedirect( $mosConfig_live_site."/index.php","Not Autorized for this page");
		exit;
	}
	$bidinc=new mosNextBid($database);
	$bidinc->setDelete();
	for($i = 1; $i <= 5;$i++) {
		$bidinc->bid_inc_id = NULL;
		$bidinc->bid_next = mosGetParam($_REQUEST,'bid_incre'.$i,0);
		$bidinc->bid_next = str_replace(",","",$bidinc->bid_next);
		$bidinc->range_from = mosGetParam($_REQUEST,'bid_range_from'.$i,0);
		$bidinc->range_from = str_replace(",","",$bidinc->range_from);
		$bidinc->range_to = mosGetParam($_REQUEST,'bid_range_to'.$i,0);
		$bidinc->range_to = str_replace(",","",$bidinc->range_to);
		$bidinc->date_modifies = date("Y-m-d H:i:s");

		//TODO Jai commented to test
		//$bidinc->bid_inc_id = mosGetParam($_REQUEST,'bid_inc_id'.$i,0);
		//TODO Jai commented to test

		//Server Validations
		if($bidinc->bid_next <= 0 || $bidinc->bid_next > 9999) {
			continue;
		}
		if($bidinc->range_from <= 0 || $bidinc->range_from > 999999) {
			continue;
		}
		if($bidinc->range_to <= 0 || $bidinc->range_to > 999999) {
			continue;
		}

		if(!$bidinc->bid_inc_id) {
			$bidinc->bid_inc_id = mosGetParam($_REQUEST,'',0);
			$bidinc->store('bid_inc_id');
		}else {
			$bidinc->store();
		}
		$currentId = $database->insertid();
	}
	if (!$msg) $msg=bid_succes;
	mosRedirect( $mosConfig_live_site."/index.php?option=$option&task=listnextbid",$msg );
}
//JaiENDI
function acceptBid($option){
	global $database,$mosConfig_live_site,$my,$Itemid;

	$bid_id = mosGetParam($_REQUEST, 'bid', -1);
	$auction = new mosBidOffers($database);
	$bid = new mosBids($database);

	if (!$bid->load($bid_id)){ echo bid_does_not_exist;return;}
	if ($bid->cancel){ echo bid_does_not_exist; return;}
	if ($bid->bid_price<=0){ echo bid_does_not_exist; return;}
	if (!$auction->load($bid->id_offer)){ echo bid_does_not_exist; return;}
	if (!$auction->acl_check($my)){ mosNotAuth(); return;}
	if ($auction->close_offer && $auction->winner_id){	echo bid_auction_is_closed;	return;}
	if ($auction->published!=1){ echo bid_does_not_exist; return;}
	if ($auction->automatic==1){ echo bid_err_is_automatic;	return;}

	$user1=new mosUser($database);
	if (!$user1->load($bid->userid) ||$user1->block){ echo bid_err_user_does_not_exist;	return;}

	// error_reporting(0);
	@ignore_user_abort(true);

	//Watches
	$database->setQuery("select a.userid from #__bid_watchlist a where a.auctionid=$auction->id");
	$ids = $database->loadResultArray();
	$id_string=implode(',',$ids);

	if (count($ids)>0){
		$query = "select u.* from #__users u
				  where u.id in ($id_string) and u.id<>'$bid->userid'";
		$database->setQuery($query);
		$mails = $database->loadObjectList();
		$auction->SendMails($mails,'bid_watchlist_closed'); //Notify Watchlist
	}
	//End Watches

	$database->setQuery("delete from #__bid_watchlist where auctionid = $auction->id");
	$database->query();

	$auction->published=1;
	$auction->close_offer=1;
	$auction->winner_id = $bid->userid;
	$auction->closed_date=date('Y-m-d H:i:s',time());
	$auction->store();

	$auction->sendNewMessage($auction->id,'',bid_accepted);
	$bid->accept=1;
	$bid->store();

	$auction->SendMails(array($user1),'bid_accepted');

	$query = "select u.* from #__users u
	   left join #__bids b on u.id=b.userid
	   where b.cancel=0 and b.accept=0 and u.block=0 and b.id_offer='$auction->id'
	";
	$database->setQuery($query);
	$loser = $database->loadObjectList();
	$auction->SendMails($loser,'bid_lost');

	$redirect_link = sefRelToAbs('index.php?option=com_bids&task=viewbids&id='.$auction->id.'&Itemid='.$Itemid);
	mosRedirect($redirect_link);
}

function addWatch( $auctionid, $option){
	global $database,$mosConfig_live_site,$my,$Itemid;
	if (!$my->id){ mosNotAuth(); return;}

	$query = "select count(id) from #__bid_watchlist where userid = $my->id and auctionid = '$auctionid'";
	$database->setQuery($query);
	$result = $database->loadResult();
	if($result) return;
	$watchList = new mosBidWatchlist($database);
	$watchList->userid = $my->id;
	$watchList->auctionid = $auctionid;

	$watchList->store(true);
	$refferer = $_SERVER['HTTP_REFERER'];
	if(!$refferer) $refferer=$mosConfig_live_site.'/'."index.php?option=$option";
	mosRedirect($refferer,bid_added_to_watchlist);
	// WITH NO message: mosRedirect($refferer);
}

function delWatch($auctionid,$option){
	global $database,$mosConfig_live_site,$my,$Itemid;
	if (!$my->id) {	mosNotAuth(); return;}

	$database->setQuery("delete from  #__bid_watchlist  where userid=$my->id and auctionid=$auctionid");
	$database->query();
	$refferer = $_SERVER['HTTP_REFERER'];
	if(!$refferer) $refferer=$mosConfig_live_site.'/'."index.php?option=$option";
	mosRedirect($refferer,bid_del_from_watchlist);
	// WITH NO message: mosRedirect($refferer);
}

function search($option){
	global $database,$mosConfig_absolute_path,$my,$Itemid,$cb_fieldmap;

	$query="select distinct u.* from #__users u
			where u.id != '$my->id' and
			u.usertype!='Super Administrator' and u.usertype!='Administrator' and u.block!=1
	";
	$database->setQuery($query);
	$users = $database->loadObjectList();

	$cats = makeCatTree();
	if (count($cats)>0){
		$cats=array_merge(array(mosHTML::makeOption('',bid_all)),$cats);
		$lists['cats'] = mosHTML::selectList($cats,'cat','class="inputbox"  style="width:190px;" ','value', 'text','');
	}
	else
		$lists['cats']  ='';

	$useropts=array();
	for($i=0;$i<count($users);$i++){
		$useropts[]=mosHTML::makeOption($users[$i]->id,$users[$i]->username);
	}

	$lists['users'] = mosHTML::selectList($useropts,'users[]','class="inputbox"  style="width:190px;" size="10" multiple','value', 'text','');

	if (!CB_DETECT){
		$query="select distinct country as value,name as text from #__bid_users ".
		" where country<>'' and country is not null";
		$database->setQuery($query);
		$country[]=mosHTML::makeOption('',bid_all);
		$country = array_merge($country, $database->loadObjectList());
		$lists['country'] = mosHTML::selectList($country,'country','class="inputbox"  style="width:190px;" ','value','text');

		$query="select distinct city as value,city as text from #__bid_users ".
		" where city<>'' and city is not null";
		$database->setQuery($query);
		$city[]=mosHTML::makeOption('',bid_all);
		$city = array_merge($city, $database->loadObjectList());
		$lists['city'] = mosHTML::selectList($city,'city','class="inputbox"  style="width:190px;" ','value','text');

	}
	if (CB_DETECT && $cb_fieldmap['country']) {
		$query="select distinct ".$cb_fieldmap['country']." as value,".$cb_fieldmap['country']." as text from #__comprofiler ".
		" where ".$cb_fieldmap['country']."<>'' and ".$cb_fieldmap['country']." is not null";
		$database->setQuery($query);
		$country[]=mosHTML::makeOption('',bid_all);
		$country = array_merge($country, $database->loadObjectList());
		$lists['country'] = mosHTML::selectList($country,'country','class="inputbox"  style="width:190px;" ','value','text');

	}
	if (CB_DETECT && $cb_fieldmap['city']) {
		$query="select distinct ".$cb_fieldmap['city']." as value,".$cb_fieldmap['city']." as text from #__comprofiler ".
		" where ".$cb_fieldmap['city']."<>'' and ".$cb_fieldmap['city']." is not null";
		$database->setQuery($query);
		$city[]=mosHTML::makeOption('',bid_all);
		$city = array_merge($city, $database->loadObjectList());
		$lists['city'] = mosHTML::selectList($city,'city','class="inputbox"  style="width:190px;" ','value','text');
	}
	HTML_Auction_helper::searchAuction($lists);
}

function showSearchResults($option){
	global  $database, $my,$mosConfig_absolute_path;
	global $mosConfig_live_site,$task,$cb_fieldmap;
	global $Itemid;
    $order_fields=mosBidOffers::getFieldOrderArray();
    
	if(JOOMFISH_DETECT){
		$registry =& JFactory::getConfig();
		$lang = $registry->getValue("config.jflang");
	}


	$row = new mosBidOffers( $database );
	if (!$row->acl_check($my) ){
		mosNotAuth();
		return;
	}
	$limit 		= intval( mosGetParam( $_REQUEST, 'limit',  bid_opt_nr_items_per_page) );
	$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );

	$task = mosGetParam( $_REQUEST, 'task', 'listauctions');

	$inarch 			= mosGetParam( $_REQUEST, 'inarch','');
	$filter_order 		= mosGetParam($_REQUEST,'filter_order','start_date');
	$filter_order_asc 	= intval(mosGetParam($_REQUEST,'filter_order_asc',1));
	$filter_userid 		= intval(mosGetParam($_REQUEST,'userid',0));
	$filter_category 	= mosGetParam($_REQUEST,'cat','');
	$filter_tag 		= mosGetParam($_REQUEST,'tag','');
	$filter_auctionNr 	= mosGetParam($_REQUEST,'auction_nr','');
	$filter_city 		= mosGetParam($_REQUEST,'city','');
	$filter_country 	= mosGetParam($_REQUEST,'country','');
	
	$join="";
	if (!isset($order_fields[$filter_order])) $filter_order='start_date';
	switch($inarch) {
		default:
		case 0 :
			$where=" where a.close_offer=0 ";
			break;
		case 1 :
			$where=" where a.close_offer=1 and a.close_by_admin=0 "; //avoid showing closed by admin
			break;
	}

	if ($filter_userid){
		$where.=" and a.userid='$filter_userid'";
	}
	
	$keyword = mosGetParam( $_REQUEST,'keyword','');

	$u = mosGetParam( $_REQUEST, 'users',null);
	$users=null;
	if ($u && !in_array('-1',$u)){
		$users = implode(',',$u);
	}

	$indesc = mosGetParam( $_REQUEST, 'indesc','');

	$post_stdate = mosGetParam( $_REQUEST, 'afterd','');
	if($post_stdate!='')
		$sdate = date('Y-m-d',strtotime($post_stdate));
	else
		$sdate = "";


	$post_edate = mosGetParam( $_REQUEST, 'befored','');
	if($post_edate!='')
		$bdate = date('Y-m-d',strtotime($post_edate));
	else
		$bdate ="";



	if ($filter_order_asc == 1) {
		$ord = "desc";
	}else{
		$ord = "asc";
	}

	if(!empty($keyword)){
		$keyword = "%".$keyword."%";
		$where .= " and a.title like '$keyword'";
		if(!empty($indesc)){
			$where .= " or a.description like '$keyword'";
			$where .= " or a.shortdescription like '$keyword'";
		}
	}
	if(empty($inarch)){
		$where .= " and a.published = 1 ";
	}
	if(!empty($users)){
		$where .= " and a.userid in ($users)";
	}
	
	if($post_stdate){
		$where .= " and a.start_date>'$sdate' ";
	}
	if($post_edate){
		$where .= " and a.end_date<'$bdate'";
	}
	if(!$post_stdate && !$post_edate)
		$where .=" and a.start_date<=now() ";
	
	if($filter_category && $filter_category!=0){
		if(!bid_opt_inner_categories)
			$where .=" and a.cat=$filter_category ";
		else{
			$cCategory = new mosBidCategories($database);
			$cids = $cCategory->getCategoryChildren($filter_category);
			array_push($cids,$filter_category);
			$cidFilter = implode(",", $cids);
			if(strlen($cidFilter)>0)
				$cidFilter = "(".$cidFilter.")";
			
			$where .=" and a.cat IN $cidFilter ";
		}
	}
	
	if($filter_auctionNr){
		$where .= " and a.auction_nr like '%$filter_auctionNr%' ";
	}
	if (CB_DETECT){
		$join=" left join #__comprofiler cb on a.userid=cb.user_id ";
		if ($filter_city && $cb_fieldmap['city']) {
			$where.=" and cb.".$cb_fieldmap['city']."='$filter_city' ";
		}
		if ($filter_country && $cb_fieldmap['country']) {
			$where.=" and cb.".$cb_fieldmap['country']."='$filter_country' ";
		}
	}else{
		$join=" left join #__bid_users us on a.userid=us.userid ";
		if ($filter_city) {
			$where.=" and us.city='$filter_city' ";
		}
		if ($filter_country) {
			$where.=" and us.country='$filter_country' ";
		}
	}
	


	if ($filter_tag){
		$where.=" and t.tagname LIKE '%$filter_tag%'";
		$q = " select count(*) from #__bid_auctions a
               left join #__bid_tags t on  t.auction_id=a.id
               $join
    	       ";
		$query =  "SELECT a.*, b.name,b.username,m.name as payment_name, c.name as currency_name,cats.catname";

		if(JOOMFISH_DETECT)
		$query .= ",jfl.code as jflang, jfl.name as jflname ";

    	$query .= " FROM #__bid_auctions as a
    			LEFT JOIN #__users b ON b.id=a.userid
    			LEFT JOIN #__bid_payment as m ON m.id=a.payment
    			LEFT JOIN #__bid_currency as c ON c.id=a.currency
    			LEFT JOIN #__bid_categories as cats ON a.cat=cats.id
                LEFT JOIN #__bid_tags as t ON t.auction_id=a.id";

		if(JOOMFISH_DETECT){
			$query .= " LEFT JOIN #__jf_content as jfc ON reference_id = a.id
				LEFT JOIN #__languages as jfl ON jfc.language_id = jfl.id ";
			$q .= " LEFT JOIN #__jf_content as jfc ON reference_id = a.id
				LEFT JOIN #__languages as jfl ON jfc.language_id = jfl.id ";
			
		}
    	$query .= "
    			$join
    			$where ";
		$q .= "
    			$where ";

		if(JOOMFISH_DETECT){
        	$query .= "
    			GROUP BY a.id";
			$q .= "
    			GROUP BY a.id";
		}


    	$query .= "
    			ORDER by featured='gold' desc,".$filter_order." $ord
    			";

	}else{
		$q = "select count(*) from #__bid_auctions a
    	       $join
    	       ";

		$query =  "SELECT a.*,b.name,b.username,m.name as payment_name, c.name as currency_name,cats.catname ";

		if(JOOMFISH_DETECT)
		  $query .= ",jfl.code as jflang, jfl.name as jflname ";

		$query .= "FROM #__bid_auctions as a
    			LEFT JOIN #__users as b on b.id=a.userid
    			LEFT JOIN #__bid_payment as m on m.id=a.payment
    			LEFT JOIN #__bid_currency as c on c.id=a.currency
    			LEFT JOIN #__bid_categories as cats on a.cat=cats.id";

		if(JOOMFISH_DETECT){
			$query .= " LEFT JOIN #__jf_content as jfc ON reference_id = a.id
				LEFT JOIN #__languages as jfl ON jfc.language_id = jfl.id ";
			$q .= " LEFT JOIN #__jf_content as jfc ON reference_id = a.id
				LEFT JOIN #__languages as jfl ON jfc.language_id = jfl.id ";
			
		}
		$query .= "
    			$join
    			$where ";
		$q .= "
    			$where ";

		if(JOOMFISH_DETECT){
        	$query .= "
    			GROUP BY a.id";
			$q .= "
    			GROUP BY a.id";
		}

    	$query .= "
    			ORDER by featured='gold' desc,".$filter_order." $ord
    			";

	}

	$database->setQuery($q);
//	echo $database->_sql;exit;
//	exit;
	$total = $database->loadResult();


	require_once( $GLOBALS['mosConfig_absolute_path'] . '/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, $limit  );

	$database->setQuery($query,$pageNav->limitstart,$pageNav->limit);
	//echo $database->_sql;//exit;
	$rows = $database->loadObjectList();

	$sfilters['keyword'] = trim($keyword,'%');
	$sfilters['users'] = $users;
	$sfilters['indesc'] = $indesc;
	$sfilters['inarch'] = $inarch;
	$sfilters['sdate'] = $sdate;
	$sfilters['bdate'] = $bdate;
	$sfilters['cat'] = $filter_category;
	$sfilters['auction_nr'] = $filter_auctionNr;
	$sfilters['tag'] = $filter_tag;
	$sfilters['city'] = $filter_city;
	$sfilters['country'] = $filter_country;

	$lists = array();

	$orders[] = mosHTML::makeOption('start_date',bid_sort_newest);
	$orders[] = mosHTML::makeOption('initial_price',bid_sort_initialprice);
	$orders[] = mosHTML::makeOption('BIN_price',bid_sort_binprice);
	$orders[] = mosHTML::makeOption('end_date',bid_sort_end_date);
	$orders[] = mosHTML::makeOption('name',bid_sort_username);
	$lists['orders'] = mosHTML::selectList($orders,'filter_order',
	'class="inputbox" onchange="document.auctionForm.submit();"','value', 'text',$filter_order);


	$ord_desc[] = mosHTML::makeOption( 1, bid_order_desc);
	$ord_desc[] = mosHTML::makeOption( 2, bid_order_asc);
	$lists['filter_order_asc'] =  mosHTML::selectList($ord_desc,'filter_order_asc',
	'class="inputbox" onchange="document.auctionForm.submit();"','value', 'text',$filter_order_asc);

	$lists['filter_userid']=$filter_userid;

	HTML_Auction::listAuctions( $rows, $lists, $pageNav,$sfilters);




}
// ====================================== USER DETAILS ==============================

function userEdit( $option) {
	global $database, $my;

	if (!$my->id){mosNotAuth();return;}
	$bid_user = new mosBidUsers($database);
	$bid_user->load($my->id);
	$lists = array(); $countryid = array();
	$database->setQuery("select id as countryid,name from #__bid_country order by name");
	$countryid[] = mosHTML::makeOption( '0',bid_choose_country,'countryid','name');
	$countryid = array_merge( $countryid, $database->loadObjectList() );

	$lists['country'] = mosHTML::selectList( $countryid, 'country', 'class="inputbox" size="1"', 'countryid', 'name', $bid_user->country);

	$sql = "select itemname,amount,price,currency,task_pay from
	   #__bid_pricing p left join #__bid_credits c on p.itemname=c.credittype and c.userid='$my->id'
	   where  p.enabled=1 AND p.itemname <> 'comission'";
	$database->setQuery( $sql );
	$lists["credits"] = $database->loadObjectList() ;
	$sql = "select * from #__bid_rate r
	       left join #__bid_auctions a on r.auction_id=a.id
	       left join #__users u on r.voter=u.id
	       where user_rated='$my->id' order by r.id desc";
	$database->setQuery($sql,0 ,10 );
	$lists["ratings"]=$database->loadObjectList() ;

	$database->setQuery("SELECT enabled FROM #__bid_pricing WHERE itemname = 'comission'");
	$database->loadObject($lists["pricing_plugin"]);

	if($lists["pricing_plugin"]->enabled=="1")
	{
		$database->setQuery("SELECT b.*, c.name AS currency_name FROM #__bid_balance b  LEFT JOIN #__bid_currency c ON b.currency = c.id  WHERE b.auctioneer = '$my->id'");
		$lists["debts"] = $database->loadObjectList();
		$lists["pay_comission_link"] = sefRelToAbs('index.php?option=com_bids&task=pay_comission');
	}

	HTML_UserDetails::editUser($bid_user,$lists);
}

function viewUser( $option,$id) {
	global $database, $my;

	$userdetails = new mosBidUsers($database);
	$userdetails->load($id);

	$usr=new mosUser($database);
	$usr->load($id);
	$userdetails->username=$usr->username;
	$userdetails->userid=$id;

	$userdetails->email=$usr->email;
	$query = "select name from #__bid_country where id=$userdetails->country";
	$database->setQuery($query);
	$userdetails->country = $database->loadResult();

	$sql = "select * from #__bid_rate r
	       left join #__bid_auctions a on r.auction_id=a.id
	       left join #__users u on r.voter=u.id
	       where user_rated='$id' order by r.id desc";
	$database->setQuery( $sql ,0,10);
	$lists["ratings"]=	 $database->loadObjectList() ;


	HTML_UserDetails::viewUser($userdetails,$lists);
	//	HTML_UserDetails::userEdit($userdetails,$option,null,1,$ratingsv);

}
function userSave( $option) {
	global $database, $Itemid,$mosConfig_live_site,$my;
	if (!$my->id) {
		mosNotAuth();
		return;
	}

	$bid_user = new mosBidUsers($database);

	if (!$bid_user->load($my->id)){
		//no record yet
		$bid_user->_tbl_key='id';
		$bid_user->id=null;
	}else{
		$bid_user->_tbl_key='userid';
	}
	$bid_user->bind($_POST);
	$bid_user->userid=$my->id;
	$bid_user->store();
	mosRedirect( $mosConfig_live_site.'/'."index.php?option=$option&task=UserDetails&Itemid=$Itemid", _USER_DETAILS_SAVE );
}

function userRate($id,$option){
	global $database,$my,$Itemid,$mosConfig_live_site;
	$auction_id = $id;
	$note = mosGetParam($_POST,'comment','');
	$rate = mosGetParam($_POST,'rate','0');
	$user_rated = mosGetParam($_POST,'user_rated','');
	$modified_date = date("Y-m-d H:i:s");
	$auction = new mosBidOffers($database);
	$auction->load($auction_id);
	if($auction->winner_id == $user_rated || $auction->userid == $user_rated){
		$database->setQuery("select count(id) from #__bid_rate where auction_id = $id and voter = $my->id");
		$result = $database->loadResult();
		if(!$result){
			$rate_type=($auction->winner_id == $user_rated )?"bidder":"auctioneer";
			$database->setQuery("insert into #__bid_rate (voter,user_rated,rating,modified,message,auction_id,rate_type) values ($my->id,$user_rated,$rate,'$modified_date','$note',$id,'$rate_type')");
			$database->query();
			$database->setQuery("select * from #__users where id = $user_rated");
			$result = $database->loadObjectList();
			$auction->SendMails($result,'bid_rate');
		}
	} else {
		die(bid_err_does_not_exist);
	}
	mosRedirect( $mosConfig_live_site.'/'."index.php?option=$option&task=viewbids&id=$id&Itemid=$Itemid&mosmsg=".bid_rate_succes."#bid_list" );
}


function viewUserRatings($option, $id){
	global $database, $Itemid,$my;
	if (!$my->id && !$id){mosNotAuth();	return;}
	if (!$id) $id=$my->id;
	$query = "select r.*,us.username,a.title from #__bid_rate r
			  left join #__users us on r.voter = us.id
			  left join #__bid_auctions a on r.auction_id = a.id
	    	  where r.user_rated = '$id'

	";
	$database->setQuery($query);
	$myratings = $database->loadObjectList();

	$usr=new mosUser($database);
	$usr->load($id);

	HTML_UserDetails::myRatings($usr,$myratings);
}

function listcategories($option){
	global $database,$my,$Itemid;
	define('CAT_NR_COLS',2);
	
	$cat_filter = null;
	$cat = (int)mosGetParam($_GET,"cat",0);
	$cat_filter = " parent = ".$database->Quote($cat);
	
	//$database->setQuery("select * from #__bid_categories where $cat_filter order by ordering,catname asc");
	$database->setQuery(" SELECT c . * , COUNT( a.id ) as nr_a ".
	" FROM #__bid_categories AS c ".
	" LEFT JOIN #__bid_auctions AS a ON a.cat = c.id  AND a.close_offer=0 AND a.start_date<=now() AND a.published=1 ".
	" WHERE $cat_filter ".
	" GROUP BY c.id  order by c.ordering,catname asc
	");
	$rows = $database->loadObjectList();
	

	for ($i=0;$i<count($rows);$i++){
		$database->setQuery(" SELECT c . * , COUNT( a.id ) as nr_a ".
		" FROM #__bid_categories AS c ".
		" LEFT JOIN #__bid_auctions AS a ON a.cat = c.id  AND a.close_offer=0 AND a.start_date<=now() AND a.published=1   ".
		" WHERE parent = '".$rows[$i]->id."'".
		" GROUP BY c.id  order by c.ordering,catname asc
		");
		//$database->setQuery("select * from #__bid_categories where parent='".$rows[$i]->id."' order by ordering,catname asc");
		$rows[$i]->catname=mosStripslashes($rows[$i]->catname);
		$rows[$i]->subcategories=$database->loadObjectList();
		
		$rows[$i]->link=sefRelToAbs("index.php?option=$option&task=listauctions&cat=".$rows[$i]->id."&Itemid=$Itemid");
		$rows[$i]->view=sefRelToAbs("index.php?option=$option&task=listcats&cat=".$rows[$i]->id."&Itemid=$Itemid");
		$rows[$i]->kids = Bids_has_children($rows[$i]->id);
		for ($j=0;$j<count($rows[$i]->subcategories);$j++){
			$rows[$i]->nr_a += $rows[$i]->subcategories[$j]->nr_a; 
			$rows[$i]->subcategories[$j]->catname = mosStripslashes($rows[$i]->subcategories[$j]->catname);
			$rows[$i]->subcategories[$j]->link=sefRelToAbs("index.php?option=$option&task=listauctions&cat=". $rows[$i]->subcategories[$j]->id."&Itemid=$Itemid");
			$rows[$i]->subcategories[$j]->view=sefRelToAbs("index.php?option=$option&task=listcats&cat=". $rows[$i]->subcategories[$j]->id."&Itemid=$Itemid");
			$rows[$i]->subcategories[$j]->kids = Bids_has_children($rows[$i]->subcategories[$j]->id);
		}
	}
	HTML_Auction_helper::listCategories($option,$rows,$Itemid);
}

function showimportform($option){
	global $Itemid,$mosConfig_live_site,$mosConfig_absolute_path,$my;

	if (!$my->id || bid_opt_allow_import!=1) { mosNotAuth(); return;}
	HTML_Auction_helper::bulkimport($option,$Itemid,null);
}

function import($option){
	global $my;
	if (!$my->id || bid_opt_allow_import!=1) { mosNotAuth(); return;}
	$err=ImportFromCSV($option,0);
	if (count($err)<=0)
	mosRedirect( $mosConfig_live_site.'/'."index.php?option=$option&task=myauctions&Itemid=$Itemid",bid_auctions_imported );
	else{ HTML_Auction_helper::bulkimport($option,$Itemid,$err);}
}

function AuctionsRss($option){
	global $database,$mosConfig_live_site,$mosConfig_absolute_path;

	$feed= bid_opt_RSS_feedtype;
	$cat= strval( mosGetParam( $_GET, 'cat', '' ) );
	$user= strval( mosGetParam( $_GET, 'user', '' ) );

	$limit=(defined("bid_opt_RSS_description"))?intval(bid_opt_RSS_nritems):intval(bid_opt_nr_items_per_page);
	if (!$limit) $limit=10;

	require_once( $mosConfig_absolute_path .'/includes/feedcreator.class.php' );

	$rss 	= new UniversalFeedCreator();
	// load image creator class
	$iso 	= split( '=', _ISO );

	$rss->title 			= bid_opt_RSS_title;
	$rss->description 		= bid_opt_RSS_description;
	$rss->link 				= htmlspecialchars( $mosConfig_live_site );
	$rss->syndicationURL 	= htmlspecialchars( $mosConfig_live_site );
	$rss->cssStyleSheet 	= NULL;
	$rss->encoding 			= $iso[1];

	$where=" where published=1 and close_offer=0 and close_by_admin=0 ";
	
	if ($cat){
		$cCategory = new mosBidCategories($database);
		$cids = $cCategory->getCategoryChildren($cat);
		array_push($cids,$cat);
		$cidFilter = implode(",", $cids);
		if(strlen($cidFilter)>0)
			$cidFilter = "(".$cidFilter.")";
		
		$where .=" and a.cat IN $cidFilter ";
		//$where.=" and ( cat='$cat' or c.parent='$cat' ) ";
	}
	
	if ($user) $where.=" and userid='$user' ";
	$database->setQuery("select a.* from #__bid_auctions a left join #__bid_categories c on c.id=a.cat $where order by id desc",0,$limit);
	$rows=$database->loadObjectList();
	//echo $database->_sql;exit;

	for($i=0;$i<count($rows);$i++){

		$auction=$rows[$i];

		$item_link = 'index.php?option=com_bids&task=viewbids&id='. $auction->id;
		$item_link = sefRelToAbs( $item_link );

		// removes all formating from the intro text for the description text
		$item_description = mosStripslashes($auction->description);
		$item_description = mosHTML::cleanText( $item_description );
		$item_description = html_entity_decode( $item_description );

		// load individual item creator class
		$item = new FeedItem();
		// item info
		$item->title 		= mosStripslashes($auction->title);
		$item->link 		= $item_link;
		$item->description 	= $item_description;
		$item->source 		= $rss->link;
		$item->date         = date( 'r', strtotime($auction->start_date) );
		$database->setQuery("select catname from #__bid_categories where id='$auction->cat' ");
		$item->category     = $database->loadResult();

		$rss->addItem($item);

	}
	$rss->saveFeed($feed);
}

function decodeVin($option)
{
	$vin=mosGetParam($_REQUEST,'vin','');
	if (strlen($vin)!=17){
		echo "ERR - Length must be 17";
		exit;
	}
	if (!($xml_parser = xml_parser_create())){
		echo("ERR - Couldn't create parser.");
		exit;
	}

	$options = array(
	CURLOPT_RETURNTRANSFER => true,     // return web page
	CURLOPT_HEADER         => false,    // don't return headers
	CURLOPT_FOLLOWLOCATION => true,     // follow redirects
	CURLOPT_ENCODING       => "",       // handle all encodings
	CURLOPT_USERAGENT      => "Mozilla/5.0 (Windows; U; Windows NT 5.0; en; rv:1.9) Gecko/2008052906 Firefox/3.0 GoogleToolbarFF 3.1.20080605", // who am i
	CURLOPT_AUTOREFERER    => true,     // set referer on redirect
	CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
	CURLOPT_TIMEOUT        => 120,      // timeout on response
	CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
	);
	$url="http://www.vinquery.com/ws_POQCXTYNO1D/xml_v100_QA7RTS8Y.aspx?accessCode=843aef61-7c16-41ac-a431-034ad3972508&vin=$vin&reportType=2";
	$ch      = curl_init( $url );
	curl_setopt_array( $ch, $options );
	$content = curl_exec( $ch );
	$err     = curl_errno( $ch );
	$errmsg  = curl_error( $ch );
	curl_close( $ch );


	if ($err) {
		echo "ERR - $err ($errmsg) \n";
	} else {
		$xml=simplexml_load_string($content);
		//$xml=simplexml_load_file('D:/www/TestJoomla/components/com_bids/vin.xml');

		$res=xmlGetAttr($xml->VIN->attributes(),"Status");
		if($res!="SUCCESS"){echo "ERR - invalid VIN \n";exit;}
		$details=$xml->VIN->Vehicle;

		echo "Model Year: ".xmlGetAttr($details->attributes(),"Model_Year")."\n";
		echo "Make: ".xmlGetAttr($details->attributes(),"Make")."\n";
		echo "Model: ".xmlGetAttr($details->attributes(),"Model")."\n";
		echo "Trim Level: ".xmlGetAttr($details->attributes(),"Trim_Level")."\n";

		foreach ($details->children() as $item){
			$val=xmlGetAttr($item->attributes(),"Value");
			$unit=xmlGetAttr($item->attributes(),"Unit");
			if ($val=="N/A" || $val=="No data") $unit="";
			echo xmlGetAttr($item->attributes(),"Key").": ";
			echo $val." ";
			echo $unit."\n";
		}

		//        echo $content;
	}
	exit;
}


function setFilters($limitstart){
	global $option, $task;
	if(!isset($_SESSION))
		session_start();

	global $filter_bidtype, $filter_category, $filter_order, $filter_order_asc, $filter_userid,	$filter_archive, $filter_category;

	$filter_bidtype 	= isset($_SESSION[$option][$task]["filter_bidtype"])?$_SESSION[$option][$task]["filter_bidtype"]:0;
	$filter_order 		= isset($_SESSION[$option][$task]["filter_order"])?$_SESSION[$option][$task]["filter_order"]:'id';
	$filter_order_asc 	= isset($_SESSION[$option][$task]["filter_order_asc"])?$_SESSION[$option][$task]["filter_order_asc"]:1;
	$filter_category 	= isset($_SESSION[$option][$task]["filter_category"])?$_SESSION[$option][$task]["filter_category"]:'';
	$filter_archive 	= isset($_SESSION[$option][$task]["filter_archive"])?$_SESSION[$option][$task]["filter_archive"]:0;

	$filter_bidtype 	= intval(mosGetParam($_REQUEST,'filter_bidtype',$filter_bidtype));
	$filter_order	 	= mosGetParam($_REQUEST,'filter_order',$filter_order);
	$filter_order_asc 	= intval(mosGetParam($_REQUEST,'filter_order_asc',$filter_order_asc));
	$filter_category 	= intval(mosGetParam($_REQUEST,'cat',$filter_category));
	$filter_archive  	= mosGetParam($_REQUEST,'filter_archive',$filter_archive);
	$filter_userid 		= intval(mosGetParam($_REQUEST,'userid',0));

	storeFillterInSession("filter_bidtype",$filter_bidtype);
	storeFillterInSession("filter_order",$filter_order);
	storeFillterInSession("filter_order_asc",$filter_order_asc);
	storeFillterInSession("filter_archive",$filter_archive);
	storeFillterInSession("filter_category",$filter_category);
}

function storeFillterInSession($filterName, $filterValue){
	global $task,$option;
	$_SESSION[$option][$task][$filterName]=$filterValue;
}

function loadLanguageFile()
{
	if(JOOMLA_VERSION==5){
		$lang =& JFactory::getLanguage(); 
	}else{
		global $mosConfig_lang;
		$lang->_lang = $mosConfig_lang;
	}
		
    if (JOOMFISH_DETECT){
    	$lng = substr($lang->_lang,0,2);
    }else{
    	$lng = bid_opt_language;
    }
    if (file_exists(BIDS_COMPONENT_PATH.'/lang/'.$lng.".php"))
    	require_once(BIDS_COMPONENT_PATH.'/lang/'.$lng.".php");
    else
    	require_once(BIDS_COMPONENT_PATH.'/lang/default.php');


}
/* JaistartG */
function checkBB($option){
	global $database, $my, $mosConfig_absolute_path;
	global $mosConfig_live_site,$task,$mosConfig_offset;
	global $Itemid;
	
	$limit 		  = intval( mosGetParam( $_REQUEST, 'limit',  1) );
	$limitstart   = intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );
	$type   = intval( mosGetParam( $_REQUEST, 'type', 0 ) );

	//TODO JaiStartH
	if($type == 9) {
		/**
		* For Auto Bidding  of proxy plus bids
		* while sale start time
		* 
		*/
		$proxyPlusBids = array();
		$queryMain = "SELECT DISTINCT id, initial_price FROM  #__bid_auctions where close_offer=0 and published = 1";
		$database->setQuery($queryMain);
		$rowsMain = $database->loadObjectList();
		$auction = new mosBidOffers($database);
		foreach($rowsMain as $rowsMainId) {
			$auction->load($rowsMainId->id);
			$amount = $rowsMainId->initial_price;
		 if(strtotime($auction->start_date) < time()) {
				$query   = "SELECT lot_desired, ppt.id as id, ppt.userid FROM #__proxyplus_tickets ppt JOIN #__proxyplus_bids ppb ON (ppt.id = ppb.ticket_id) WHERE ppb.auction_id = $id_offer ORDER BY ppt.userid asc";
				$database->setQuery( $query);
				$rowsProxyPlusTickets = $database->loadObjectList();
				$proxyPlusBids = array();
				if(count($rowsProxyPlusTickets) > 0) {
					foreach($rowsProxyPlusTickets as $rowProxyPlusTickets) {
						$query   = "SELECT * FROM #__proxyplus_bids AS b WHERE ticket_id = ".$rowProxyPlusTickets->id ." AND outbid = 0 ORDER BY priority LIMIT ".$rowProxyPlusTickets->lot_desired;
						$database->setQuery( $query);
						$rowsProxyPlusbids = $database->loadObjectList();
						foreach($rowsProxyPlusbids as $rowProxyPlusbids) {
							if($rowProxyPlusbids->auction_id == $id_offer) {
								$proxyPlusBids[$rowProxyPlusTickets->userid] = array(
																"id"=>$rowProxyPlusbids->id,
																"ticket_id"=>$rowProxyPlusbids->ticket_id,
																"auction_id"=>$rowProxyPlusbids->auction_id,
																"my_bid"=>$rowProxyPlusbids->my_bid,
																"priority"=>$rowProxyPlusbids->priority,
																"datemodified"=>$rowProxyPlusbids->datemodified,
																"outbid"=>$rowProxyPlusbids->outbid,
																"userid"=>$rowProxyPlusTickets->userid
															  );
							}
						}
					}
				}
				do {
						foreach($proxyPlusBids as $rowPlaceBids){
								//Jai - Coding for bidding
								$bestBid=null;
								if($auction->auction_type != AUCTION_TYPE_PRIVATE ) $bestBid=$auction->GetBestBid();
								//Same bidder
								if($bestBid->userid == $rowPlaceBids['userid']) {
									if(count($proxyPlusBids) <= 1) {
										unset($proxyPlusBids[$rowPlaceBids['userid']]);
									}
									continue;
								}
								$amount += findBidIncrement($amount);
								if($amount > $rowPlaceBids['my_bid']) {
									$outbidded=new mosUser($database);
									$outbidded->load($bestBid->userid);
									$auction->SendMails(array($outbidded),'bid_outbid');
									$query1= "UPDATE #__proxyplus_bids SET outbid = 1 WHERE auction_id = $id_offer AND id = ".$rowPlaceBids['id'];
									$database->setQuery($query1);
									$database->query();
									unset($proxyPlusBids[$rowPlaceBids['userid']]);
									continue;
								}
								$database->setQuery("select * from #__bids where  cancel=0 and userid='".$rowPlaceBids['userid']."' and id_offer='$id_offer' ");
								$head_message=new mosBids($database);//new head message
								if(!$database->loadObject($head_message)) {
									$head_message->userid=$rowPlaceBids['userid'];
									$head_message->id_offer=$id_offer;
									$head_message->id_proxy=$prxid[1];
									$head_message->bid_price=$amount;
									$head_message->initial_bid=$amount;
									$head_message->modified=date("Y-m-d H:i:s",time());
									$head_message->accept=0;
									$head_message->cancel=0;
									$head_message->store();
								} else {
									if ($amount >= $head_message->bid_price){
										$head_message->id_proxy=$prxid[1];
										$head_message->bid_price=$amount;
										$head_message->modified=date("Y-m-d H:i:s",time());
										$head_message->store();
									}
								}
								//Jai End coding for bidding
						}
				}while(count($proxyPlusBids) > 0);
			}
		}
		//MAIN ID
	  }
	//JaiEndH
	//DELETE 
	$profile = new mosComProfiler($database);
	$manaInfo = $profile->getManagerInfo();
	if($manaInfo->cb_buyerschoice == "Buyer's Choice") {
		$start_date = $manaInfo->cb_startdate;
		$end_date = $manaInfo->cb_enddate;
	}
	if(strtotime($start_date) >= time() && $type != 2) {
		return 0;
	}
	if(strtotime($end_date) < time()){
		return 0;
	}
	$query  = "SELECT a.id as auction_id, b.id as id, id_offer,  b.userid, bid_price,  b.bid_price + a.min_increase as bid_next, a.shortdescription FROM #__bids AS b JOIN #__bid_auctions AS a ON a.id = b.id_offer WHERE 	b.modified > '".date("Y-m-d H:i:s",(time() - (60 * bid_opt_bgcolor_minutes)))."' ORDER BY bid_price DESC";
	if($type == 2) {
		$lotid   = mosGetParam( $_REQUEST, 'lot', 0 );
		$ticketid   = mosGetParam( $_REQUEST, 'tid', 0 );

	$query  = "SELECT count(a.id) FROM #__proxyplus_bids AS b JOIN #__bid_auctions AS a ON a.id = b.auction_id JOIN #__proxyplus_tickets pt  ON (pt.id = b.ticket_id) WHERE 	a.title = '".$lotid."' AND pt.userid = ".$my->id ." AND a.userid <> ".$my->id." AND pt.id <> ".$ticketid;
		$database->setQuery( $query);
		$countBids = $database->loadResult();
		if($countBids > 0) { 
			return 1;
		}
		$query  = "SELECT a.id as auction_id, b.id as id, id_offer,  b.userid, bid_price,  IF(b.bid_price > 0,b.bid_price + a.min_increase, initial_price)  as bid_next, a.shortdescription  FROM #__bids AS b RIGHT JOIN #__bid_auctions AS a ON a.id = b.id_offer WHERE 	a.title = '".$lotid."' ORDER BY bid_price DESC";
	}
	$database->setQuery( $query);
	//echo $database->_sql;exit;
	$rows = $database->loadObjectList();
	$lists = array();
	$arrAuctions = array();
	if(count($rows) > 0) {
		foreach($rows as $row) {
			if(!in_array($row->id_offer,$arrAuctions)) { 
				$arrAuctions[] =  $row->id_offer;
				$lists[] = array(
					'id' => $row->id,
					'id_offer'=>$row->id_offer,
					'id_offer' => $row->id_offer,
					'userid' => $row->userid,
					'bid_price' => number_format($row->bid_price),
					'shortdesc' => $row->shortdescription,
					'auction_id' => $row->auction_id,
					'bid_next' => ($row->bid_price > 0)?number_format($row->bid_price + findBidIncrement($row->bid_price)):$row->bid_next
					);
			}
		}
		return json_encode($lists);
	}else {
		return 0;
	}
}

function listBigBoard( $option , $type=0){

	global $database, $my, $mosConfig_absolute_path;
	global $mosConfig_live_site,$task,$mosConfig_offset;
	global $Itemid;

	if($type == 1) {
		$where = " where a.userid='$my->id' and ";
	}else {
		$where = " where ";
	}
	
    $order_fields = mosBidOffers::getFieldOrderBBArray();
	$limit 		  = intval( mosGetParam( $_REQUEST, 'limit',  bid_opt_nr_items_per_page) );
	$limitstart   = intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );

	global $filter_bidtype, $filter_category, $filter_order, $filter_order_asc, $filter_userid;
	setFilters($limitstart);

	if (!isset($order_fields[$filter_order])) $filter_order='start_date';
	

	switch($filter_bidtype) {
		default:
		case 0 :
			$where .= " published=1";
			if($filter_category && $filter_category!=0){
				if(!bid_opt_inner_categories)
					
					$where .=" and a.cat=$filter_category ";
					
				else{
					
					$cCategory = new mosBidCategories($database);
					$cids = $cCategory->getCategoryChildren($filter_category);
					array_push($cids,$filter_category);
					$cidFilter = implode(",", $cids);
					if(strlen($cidFilter)>0)
						$cidFilter = "(".$cidFilter.")";
					
					$where .=" and a.cat IN $cidFilter ";
				}
			}
			break;
		case 1 :
			$where .=" close_offer=1 and close_by_admin=0";
			if($filter_category && $filter_category!=0){
				
				if(!bid_opt_inner_categories)
					$where .=" and a.cat=$filter_category ";
				else{
					
					$cCategory = new mosBidCategories($database);
					$cids = $cCategory->getCategoryChildren($filter_category);
					array_push($cids,$filter_category);
					$cidFilter = implode(",", $cids);
					if(strlen($cidFilter)>0)
						$cidFilter = "(".$cidFilter.")";
					
					$where .=" and a.cat IN $cidFilter ";
				}
			}
			//avoid showing closed by admin
			break;
		case 3 :
			$where .=" left join #__bid_watchlist ww on a.id=ww.auctionid where ww.userid='$my->id'";
			if($filter_category && $filter_category!=0){
				if(!bid_opt_inner_categories)
					$where .=" and a.cat=$filter_category ";
				else
					$where .=" and a.cat=$filter_category or cats.parent = $filter_category ";
			}
			break;
	}

	if ($filter_userid){
		$where.=" and a.userid='$filter_userid'";
	}
	//    $where.=" and userid='$my->id'";
	$filter_order_asc = 0;
	if ($filter_order_asc == 1) {
		$ord = "desc";
	}else{
		$ord = "asc";
	}



	$q = "select count(*) from #__bid_auctions a
	LEFT JOIN #__bid_categories as cats ON a.cat=cats.id 
	$where ";
	$database->setQuery($q);
	$total = $database->loadResult();

	require_once( $GLOBALS['mosConfig_absolute_path'] . '/includes/pageNavigation.php' );
	//Jai - Not needed now - TODO
	 $pageNav = new mosPageNav( $total, $limitstart, $total  );


	$query =  "SELECT a.title as title, a.id as id, a.userid, a.initial_price, a.currency, a.BIN_price, a.auction_type, a.start_date, a.end_date, a.published, a.close_offer, a.cat, a.winner_id, a.min_increase, a.reserve_price, b.username, c.name AS currency_name, cats.catname, cats.id AS cid, d.initial_bid, d.bid_price, d.userid  as bid_user, d.bid_price + a.min_increase as bid_next, d.modified as bid_modified, d.id as bid_id
		FROM #__bid_auctions AS a
		LEFT JOIN #__users AS b ON b.id = a.userid
		LEFT JOIN #__bid_currency AS c ON c.id = a.currency
		JOIN #__bids AS d ON a.id = d.id_offer
		JOIN (
		  SELECT id, id_offer, max(bid_price) AS bid_price
		  FROM #__bids  GROUP BY id_offer) AS d1  ON (d.id_offer = d1.id_offer AND d.bid_price = d1.bid_price)
		LEFT JOIN #__bid_categories AS cats ON a.cat = cats.id 
	";
	if($type == '2') {
		$query .= " JOIN #__bid_watchlist bw ON  (a.id = bw.auctionid) ";
	}

	$query	.=" $where ";
	$query	.=" AND d.bid_price <> 0 ";

	if($type == '2') {
		$query .= " AND bw.userid = '$my->id' ";
	}
	
	$query  .= "UNION ";
	
	$query  .= "SELECT a.title as title, a.id  as id, a.userid, a.initial_price, a.currency, a.BIN_price, a.auction_type, a.start_date, a.end_date, a.published, a.close_offer, a.cat, a.winner_id, a.min_increase, a.reserve_price, b.username, c.name AS currency_name, cats.catname, cats.id AS cid, 0 as initial_bid, 0 as bid_price, 0 as bid_user, a.initial_price as bid_next,  0 as bid_modified, 0 as bid_id
		FROM jos_bid_auctions AS a
		LEFT JOIN #__users AS b ON b.id = a.userid
		LEFT JOIN #__bid_currency AS c ON c.id = a.currency
		LEFT JOIN #__bid_categories AS cats ON a.cat = cats.id
		";
	if($type == '2') {
		$query .= " JOIN #__bid_watchlist bw ON (a.id = bw.auctionid) ";
	}
	$query	.=" $where ";

	if($type == '2') {
		$query .= " AND bw.userid = '$my->id' ";
	}


	$query  .= " AND a.id not in ( select distinct id_offer from #__bids WHERE bid_price <> 0)";
	$query  .= " ORDER by  title + 0, title ASC ";

	$database->setQuery( $query,$limitstart, $limit );
	$rows = $database->loadObjectList();

	$profile = new mosComProfiler($database);
	$manaInfo = $profile->getManagerInfo();

	foreach ($rows as $key=>$rowBB){
		//echo "<br>$rowBB->bid_price". $rows[$key-1]->bid_price .$rowBB->id .$rows[$key-1]->id."title".$rows[$key-1]->title;
		if(($rowBB->bid_price == $rows[$key-1]->bid_price) && ($rowBB->id == $rows[$key-1]->id)){
			$rows[$key]->bid_next = -1;
			array_splice($rows, $key, 1);
		}
		elseif($rowBB->bid_next != $rowBB->initial_price) {
			$rows[$key]->bid_next = $rowBB->bid_price + findBidIncrement($rowBB->bid_price);
		}
		if($manaInfo->cb_buyerschoice == "Buyer's Choice") {
			$rows[$key]->start_date  = $manaInfo->cb_startdate;
			$rows[$key]->end_date	= $manaInfo->cb_enddate;
		}

	}
	$lists = array();

	$orders[] = mosHTML::makeOption('start_date',bid_sort_newest);
	$orders[] = mosHTML::makeOption('initial_price+0',bid_sort_initialprice);
	$orders[] = mosHTML::makeOption('BIN_price+0',bid_sort_binprice);
	$orders[] = mosHTML::makeOption('end_date',bid_sort_end_date);
	$orders[] = mosHTML::makeOption('name',bid_sort_username);
	$lists['orders'] = mosHTML::selectList($orders,'filter_order',
	'class="inputbox" onchange="document.auctionForm.submit();"','value', 'text',$filter_order);

	$bid_type[] = mosHTML::makeOption( 0, bid_filter_available);
	$bid_type[] = mosHTML::makeOption( 1, bid_filter_archive);
	$bid_type[] = mosHTML::makeOption( 3, bid_filter_watchlist);


	$lists['filter_bidtype'] =  mosHTML::selectList($bid_type,'filter_bidtype',
	'class="inputbox" onchange="document.auctionForm.submit();"','value', 'text',$filter_bidtype);

	$ord_desc[] = mosHTML::makeOption( 1, bid_order_desc);
	$ord_desc[] = mosHTML::makeOption( 2, bid_order_asc);
	$lists['filter_order_asc'] =  mosHTML::selectList($ord_desc,'filter_order_asc',
	'class="inputbox" onchange="document.auctionForm.submit();"','value', 'text',$filter_order_asc);

	$lists['filter_userid']=$filter_userid;

	$cats = makeCatTree();
	if (count($cats)>0){
		$cats=array_merge(array(mosHTML::makeOption( 0, bid_all_categories)),$cats);
		$lists['filter_cats'] = mosHTML::selectList($cats,'cat','class="inputbox" style="width:190px;" onchange="document.auctionForm.submit();"','value', 'text',$filter_category);
	}
	else
	$lists['filter_cats'] ='&nbsp;';

	if ($filter_userid)	$sfilters['users'] = $filter_userid;
	$sfilters['bid_type'] = $filter_bidtype;
	$sfilters['cat'] = $filter_category;
	HTML_Auction::listBigBoard( $rows, $lists, $pageNav,$sfilters);
}

function listProxyTicket( $option , $type=0) {
	global $database, $my, $mosConfig_absolute_path;
	global $mosConfig_live_site,$task,$mosConfig_offset;
	global $Itemid, $task;
	global $filter_bidtype, $filter_category, $filter_order, $filter_order_asc, $filter_userid;

	if($task != "editproxyticket") {
	?>
	
	<script type="text/javascript">
		a = setInterval("refreshMe();",<?php echo bid_opt_refresh_minutes  * 1000 * 60; ?>);
	</script>
	<?php
	}
	$where = " where userid = '$my->id'";
	
	$filter_order='id';
	
	$filter_order_asc = 0;
	if ($filter_order_asc == 1) {
		$ord = "desc";
	}else{
		$ord = "asc";
	}

	$query	 = "SELECT pt.* FROM  #__proxyplus_tickets pt";
	$query	.= " $where ";
	$query  .= " ORDER by   1 $ord ";

	$database->setQuery( $query);
	$rowstickets = $database->loadObjectList();
	$profile = new mosComProfiler($database);
	$manaInfo = $profile->getManagerInfo();
	if(count($rowstickets) <= 0 && $task != "editproxyticket") {
		mosRedirect( $mosConfig_live_site."/index.php?option=$option&task=editproxyticket");
	}
	foreach($rowstickets as $rowticket) {
		$query	 = "SELECT ptb.*, ba.title as title, ba.shortdescription as shortdesc, ba.start_date as start_date, ba.end_date as end_date FROM  #__proxyplus_bids ptb JOIN #__bid_auctions ba ON ( ptb.auction_id = ba.id) ";
		$query	.=  " WHERE ticket_id = ".$rowticket->id;
		$query  .= " ORDER by   1 $ord ";
		$database->setQuery( $query);
		$rows[$rowticket->id] = $database->loadObjectList();
		$lots_desired[$rowticket->id] = $rowticket->lot_desired;
		$userticket[$rowticket->id] = $rowticket->ticket_id;
		foreach($rows[$rowticket->id] as $key1 => $rowbids) {
			//Added for start date
			if($manaInfo->cb_buyerschoice == "Buyer's Choice") {
				$start_date = $manaInfo->cb_startdate;
			}

			$query  = "SELECT b.id as id, id_offer,  b.userid, bid_price,  IF(b.bid_price > 0,b.bid_price + a.min_increase, initial_price)  as bid_next, a.shortdescription  FROM #__bids AS b RIGHT JOIN #__bid_auctions AS a ON a.id = b.id_offer WHERE 	a.title = '".$rowbids->title."' ORDER BY bid_price DESC";
			$database->setQuery( $query,0, 1);
			$bids = $database->loadObjectList();
			$rows[$rowticket->id][$key1]->bid_price = $bids[0]->bid_price;
			$rows[$rowticket->id][$key1]->bid_next = ($bids[0]->bid_price > 0)?($bids[0]->bid_price +findBidIncrement($bids[0]->bid_price)):$bids[0]->bid_next;
		}
	}
	$lists = array();

	$orders[] = mosHTML::makeOption('start_date',bid_sort_newest);
	$orders[] = mosHTML::makeOption('initial_price+0',bid_sort_initialprice);
	$orders[] = mosHTML::makeOption('BIN_price+0',bid_sort_binprice);
	$orders[] = mosHTML::makeOption('end_date',bid_sort_end_date);
	$orders[] = mosHTML::makeOption('name',bid_sort_username);
	$lists['orders'] = mosHTML::selectList($orders,'filter_order',
	'class="inputbox" onchange="document.auctionForm.submit();"','value', 'text',$filter_order);

	$bid_type[] = mosHTML::makeOption( 0, bid_filter_available);
	$bid_type[] = mosHTML::makeOption( 1, bid_filter_archive);
	$bid_type[] = mosHTML::makeOption( 3, bid_filter_watchlist);


	$lists['filter_bidtype'] =  mosHTML::selectList($bid_type,'filter_bidtype',
	'class="inputbox" onchange="document.auctionForm.submit();"','value', 'text',$filter_bidtype);

	$ord_desc[] = mosHTML::makeOption( 1, bid_order_desc);
	$ord_desc[] = mosHTML::makeOption( 2, bid_order_asc);
	$lists['filter_order_asc'] =  mosHTML::selectList($ord_desc,'filter_order_asc',
	'class="inputbox" onchange="document.auctionForm.submit();"','value', 'text',$filter_order_asc);

	$lists['filter_userid']=$filter_userid;

	$cats = makeCatTree();
	if (count($cats)>0) {
		$cats=array_merge(array(mosHTML::makeOption( 0, bid_all_categories)),$cats);
		$lists['filter_cats'] = mosHTML::selectList($cats,'cat','class="inputbox" style="width:190px;" onchange="document.auctionForm.submit();"','value', 'text',$filter_category);
	} else {
	  $lists['filter_cats'] ='&nbsp;';
	}

	if ($filter_userid)	$sfilters['users'] = $filter_userid;
	$sfilters['bid_type'] = $filter_bidtype;
	$sfilters['cat'] = $filter_category;
	HTML_Auction::listProxyTicket( $rows, $lists, $pageNav,$sfilters,$lots_desired,$userticket);
}
//JaiStartI

function listNextBid( $option , $type=0) {
	global $database, $my, $mosConfig_absolute_path;
	global $mosConfig_live_site,$task,$mosConfig_offset;
	global $Itemid, $task;
	global $filter_bidtype, $filter_category, $filter_order, $filter_order_asc, $filter_userid;
	if($my->gid == 1) {
		mosRedirect( $mosConfig_live_site."/index.php","Not Autorized for this page");
		exit;
	}
	$query  = "SELECT * FROM #__bid_increment ORDER BY bid_inc_id ASC";
	$database->setQuery($query);
	$rows = $database->loadObjectList();
	$profile = new mosComProfiler($database);
	$manaInfo = $profile->getManagerInfo();
	//Added for finding next bid 
	//JaiStartI
	//Added for start date
	if($manaInfo->cb_buyerschoice == "Buyer's Choice") {
		$start_date = $manaInfo->cb_startdate;
	}
	$expired = (!isset($start_date) || strtotime($start_date) > time())?0:1;
	HTML_Auction::listNextBid( $rows, $expired);
}
//JaiENDI

function delProxyTicket( $option) {

	global $database, $my, $mosConfig_absolute_path;
	global $mosConfig_live_site,$task,$mosConfig_offset;
	global $Itemid;
	global $filter_bidtype, $filter_category, $filter_order, $filter_order_asc, $filter_userid;

	$id = intval( mosGetParam( $_REQUEST ,'id', 0 ) ); //ticket id

	$query = "DELETE  ppt, ppb FROM #__proxyplus_tickets ppt LEFT JOIN #__proxyplus_bids ppb ON (ppt.id = ppb.ticket_id)
					WHERE ppt.userid = '$my->id' AND ppt.id = $id ";
	$database->setQuery( $query);
	$database->query();
	mosRedirect( $mosConfig_live_site."/index.php?option=$option&task=listproxyticket",$msg );
	
}
function findBidIncrement($price = 0) {
	global $database, $my, $mosConfig_absolute_path;
	global $mosConfig_live_site,$task,$mosConfig_offset;
	global $Itemid;
	$bidIncrement = 0;
	$q = "SELECT bid_next  FROM #__bid_increment bi  WHERE $price BETWEEN range_from AND range_to";
	$database->setQuery($q);
	$bidIncrement = $database->loadResult();
	return $bidIncrement;

}
?>