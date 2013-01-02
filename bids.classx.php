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

define('AUCTION_PICTURES',$mosConfig_live_site.'/images/auctions');
define('AUCTION_PICTURES_PATH',$mosConfig_absolute_path.'/images/auctions/');
define('BIDS_COMPONENT_PATH',$mosConfig_absolute_path.'/components/com_bids');
define('BIDS_COMPONENT',$mosConfig_live_site.'/components/com_bids');

define('AUCTION_TYPE_PUBLIC',1);
define('AUCTION_TYPE_PRIVATE',2);

$cb_fieldmap=array();

class mosBidOffers extends mosDBTable {
	var $id					= null;
	var $userid				= null;
	var $title				= null;
	var $shortdescription	= null;
	var $description		= null;
	var $picture			= null;
	var $link_extern		= null;
	var $initial_price		= null;
	var $currency			= null;
	var $BIN_price			= null;
	var $auction_type		= null;
	var $automatic          = null;
	var $payment			= null;
	var $shipment_info		= null;
	var $shipment_price		= null;
	var $start_date			= null;
	var $end_date			= null;
	var $closed_date		= null;
	var $params 			= null;
	var $published			= null;
	var $close_offer		= null;
	var $close_by_admin		= null;
	var $hits				= null;
	var $modified			= null;
	var $newmessages        = null;
	var $winner_id          = null;
	var $cat				= null;
	var $auction_nr         = null;
	var $nr_items           = null;
	var $nr_items_left      = null;
	var $featured           = null;
	var $reserve_price      = null;
	var $min_increase		= null;
	var $custom_fld1		= null;
	var $custom_fld2		= null;
	var $custom_fld3		= null;
	var $custom_fld4		= null;
	var $custom_fld5		= null;

    var $_allowed_picture_ext = array('JPEG','JPG','GIF','PNG');
    //Parameters and default values
    var $_parameters=array("picture"=>1,
                           "add_picture"=>1,
                           "auto_accept_bin"=>1,
                           "bid_counts"=>1,
                           "max_price"=>1,
                           "show_reserve"=>0
                           );

/**
* @param database A database connector object
*/
	function mosBidOffers( &$db ) {
		$this->mosDBTable( '#__bid_auctions', 'id', $db );
	}
	function hit(){
		$this->_db->setQuery("update ".$this->_tbl." set hits=hits+1 ".
		"\n where id='$this->id'");
		return $this->_db->query();

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
    function isMyAuction()
    {
        global $my;
        if ($my->id && $my->id==$this->userid) return true;
        else return false;
    }
	function check(){
		global $mosConfig_absolute_path;
		//require_once($mosConfig_absolute_path."/components/com_bids/options.php");
    	$this->_db->setQuery("select count(id) from #__users where id=$this->userid");

	$uid_exists = $this->_db->loadResult();



	if($uid_exists==0){
		$error['userid'] = bid_import_check_userid;
	}
	if($this->auction_type){
		switch ($this->auction_type){
			case 'public':
				$auction_type = 1;
			break;
			case 'private':
				$auction_type = 2;
			break;
			default:
			break;
		}
	}
		if ($this->title==''){
			$error['title'] = bid_import_check_title;
		}

		if ($this->BIN_price>0 && $this->BIN_price<$this->initial_price && $this->initial_price>0){
			$error['BIN'] = bid_import_check_BIN;
		}
    	if(floatval($this->initial_price)<=0){
    		$error['Initialprice'] =bid_err_initial_price;
    	}

		if(!is_numeric($this->currency)){
			if (!$this->currency){
				$error[] = bid_import_check_currency;
			}else {

				$this->_db->setQuery("select id from #__bid_currency where name like '$this->currency%'");

				$currency = $this->_db->loadResult();
				if(!$currency){
					$error['currency'] = bid_import_check_currency;
				}
			}
		}else {

			$this->_db->setQuery("select count(id) from #__bid_currency where id=$this->currency");
			$curexists = $this->_db->loadResult();
			if($curexists==0){
				$error['currency'] = bid_import_check_currency;
			}
		}
		if(!$this->initial_price){
			$error['initial_price'] = bid_import_check_initial_price;
		}else {
			if($this->initial_price <= 0){
				$error['initial_price'] = bid_import_check_initial_price;
			}
		}

		if(!is_numeric($this->auction_type)){
			if(!$this->auction_type){

				$error['auction_type'] = bid_import_check_auction_type;
			}else {
				if(!in_array($auction_type,array(1,2))){

					$error['auction_type'] = bid_import_check_auction_type;
				}
			}
		}else {
			if(!in_array($this->auction_type,array("1","2"))){
				$error['auction_type'] = bid_import_check_auction_type;
			}
		}
		if(strtotime($this->start_date) >= strtotime($this->end_date)){
			$error['end_date'] .= bid_import_check_end_date;
		}
		return $error;
	}
	function acl_check($user){
		if ($this->id==null) return true;//new offer
		if ($user->gid==25) return true;//superadmin
		return ($user->id==$this->userid);

	}
	function delete( $oid=null ) {
	    global $mosConfig_absolute_path;
	    if ($oid){
	        $this->load($oid);
	    }

	    $this->_db->setQuery("select * from #__bid_pictures where id_offer='$this->id'");
	    $images=$this->_db->loadObjectList();
	    if (count($images)){
	        foreach ($images as $image){
	            if (file_exists(AUCTION_PICTURES_PATH.$image->picture)){
        	        @unlink(AUCTION_PICTURES_PATH.$image->picture);
        	        @unlink(AUCTION_PICTURES_PATH."middle_".$image->picture);
        	        @unlink(AUCTION_PICTURES_PATH."resize_".$image->picture);
	            }
	        }
	    }

	    if (file_exists(AUCTION_PICTURES_PATH.$this->picture)){
	        @unlink(AUCTION_PICTURES_PATH.$this->picture);
	        @unlink(AUCTION_PICTURES_PATH."middle_".$this->picture);
	        @unlink(AUCTION_PICTURES_PATH."resize_".$this->picture);

	    }

		$this->_db->setQuery("delete from #__bid_tags where auction_id='$this->id'"); //remove pictures
		$this->_db->query();
		$this->_db->setQuery("delete from #__bid_pictures where id_offer='$this->id'"); //remove pictures
		$this->_db->query();
		$this->_db->setQuery("delete from #__bids where id_offer='$this->id'"); //remove bids
		$this->_db->query();
		$this->_db->setQuery("delete from #__bid_proxy where auction_id='$this->id'"); //remove pictures
		$this->_db->query();
		$this->_db->setQuery("delete from #__bid_report_auctions where id_offer='$this->id'"); //remove pictures
		$this->_db->query();
		$this->_db->setQuery("delete from #__bid_watchlist where auctionid='$this->id'"); //remove watches
		$this->_db->query();
		$this->_db->setQuery("delete from #__bid_messages where id_offer='$this->id'"); //remove watches
		$this->_db->query();
		$this->_db->setQuery("delete from #__bid_auctions where id='$this->id'"); //remove the auction
		$this->_db->query();


	}
	function cron_close_offers(){
			//TO DO!!
		$this->_db->setQuery("update ".$this->_tbl." set closed_date=end_date, close_offer='1' ".
		"\n where end_date<now() and close_offer<>'1' ");

		$q = "select id, closed_date, end_date from #__bid_auctions where end_date<now() and closed_date='0000-00-00'";
		$this->_db->setQuery($q);
		$rs = $this->_db->loadObjectList();
		for($i=0;$i<count($rs);$i++){
			$r = $rs[$i];
			$q = "update #__bid_auctions set closed_date=end_date, close_offer='1' where end_date<now() and id='$r->id' and (closed_date='0000-00-00' or closed_date='')";
			$this->_db->setQuery($q);
			$this->_db->query();

			$sql = "select title from #__bid_auctions where id=$r->id";
			$this->_db->setQuery($sql);
			$title = $this->_db->loadResult();

			$q = "SELECT u.email, bu.name, bu.surname
				  FROM #__users u, #__bid_users bu, #__bid_auctions bo
				  WHERE bo.id = $r->id
				  AND u.id = bo.userid
				  AND bu.userid = u.id";
			$this->_db->setQuery($q);
			$this->_db->loadObject($off);

			$name 		= $off->name;
			$surname	= $off->surname;
			$email		= $off->email;

				//notice mail about the canceling of the offer
			$mess = "Dear $name ".$surname.",<br><br>We announce you that your offer <b>$title</b> will expire in 24 hours.<br>Please visit our site to see details!<br><br>Echipa blabla.";

			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

			$subject = "The offer $title was modified!";
			mail($email, $subject, $mess, $headers);
		}

		return $this->_db->query();
	}

	function SendMails($userlist,$mailtype){

		global $mosConfig_mailfrom,$mosConfig_sitename,$mosConfig_live_site;
		set_time_limit(0);
		ignore_user_abort();
		$mail_body=new mosBidMails($this->_db);
		if (!$mail_body->load($mailtype) ) {
			echo "hre1";
			return;
		}
		if (!$mail_body->enabled) { 
			echo "hre2";
			return;
		}
		if (count($userlist)<=0) { 
			echo "hre3";
			return;
		}

		foreach($userlist as $can){
			//notice mail about the canceling of the offer
			/*@var $can mosUser*/
			$mess =str_replace("%NAME%",$can->name,$mail_body->content);
			$mess =str_replace("%SURNAME%",$can->surname,$mess);
			$mess =str_replace("%AUCTIONTITLE%",mosStripslashes($this->title),$mess);
			$mess =str_replace("%AUCTIONLINK%",$mosConfig_live_site.'/index.php?option=com_bids&task=viewbids&id='.$this->id,$mess);

			$subj =str_replace("%NAME%",$can->name,$mail_body->subject);
			$subj =str_replace("%SURNAME%",$can->surname,$subj);
			$subj =str_replace("%AUCTIONTITLE%",mosStripslashes($this->title),$subj);
			$subj =str_replace("%AUCTIONLINK%",$mosConfig_live_site.'/index.php?option=com_bids&task=viewbids&id='.$this->id,$subj);
			if($can->email) {
				 $this->writelog("\t\n----------------------\t\n--".$can->email."\t\n--".$subj."\t\n--".$mess);
			     mosMail($mosConfig_mailfrom,$mosConfig_sitename,$can->email, $subj, $mess, true);
			}
		}

   }
   function writelog($str){
	   	$fp = fopen("components\\com_bids\\mail.log","a");
		fwrite($fp,$str);
		fclose($fp);
   }

	function sendNewMessage($id,$id_msg,$message,$id_bidder=null){
       global $my;

		$m = new mosBidMessages($this->_db);

		if(!$id_msg){
                $m->id_offer=$this->id;
                $m->parent_message=0;
                $m->message=$message;
                $m->modified=date('Y-m-d H:i:s',time());
                $m->userid1=$my->id;
                if ($id_bidder)
                    $m->userid2=$id_bidder;
                else
                    $m->userid2=$this->userid;
                $usr=new mosUser($this->_db);
                $usr->load($this->userid);

                $this->SendMails(array($usr),"new_message");

        } else {
            $replytom = new mosBidMessages($this->_db);
            $replytom->load($id_msg);
            $m->id_offer = $this->id;
            $m->parent_message = $id_msg;
            $m->userid1 = $my->id;
            $m->userid2 = $replytom->userid1;
            $m->modified = date('Y-m-d H:i:s',time());
            $m->message = $message;
            $replytom->wasread = 1;
            $replytom->store();

            $usr=new mosUser($this->_db);
            $usr->load($replytom->userid1);

            $this->SendMails(array($usr),"new_message");
        }



        $m->store();


    }
    function isAllowedImage($ext)
    {

        return in_array(strtoupper($ext),$this->_allowed_picture_ext);
    }
    function ChooseWinner(){
    	//Chooses the Winner in the current auction

    	if (!$this->id) return; // must be loaded

    	$usr = new mosUser($this->_db);

    	$query = "SELECT b.* FROM #__bids b
        	where b.id_offer = $this->id order by bid_price desc limit 1
    	";

    	$this->_db->setQuery($query);

    	$winner =null;
    	$this->_db->LoadObject($winner);

		if($winner){
        	$query = "SELECT *  from #__bids where id_offer=$this->id and bid_price='$winner->bid_price'";
        	$this->_db->setQuery($query);
        	$isduplicate = $this->_db->loadObjectList();

        	if (count($isduplicate)>1){
	        	$query = "UPDATE #__bid_auctions SET automatic = '0' WHERE id=$this->id";
	        	$this->_db->setQuery($query);
	        	$this->_db->query($query);
    			$usr->load($this->userid);
    			$this->SendMails(array($usr),'bid_choose_winner');//alert owner that 2 or more bids are the same
        	}else{

        	    if ($this->reserve_price>0 && $this->reserve_price>$winner->bid_price){
        	        //reserve price not met!
        			$usr->load($this->userid);
        			$this->SendMails(array($usr),'bid_reserve_not_met');//reserve price not met


        	    }else{
        			$usr->load($winner->userid);
        			$this->winner_id = $winner->userid;
        			$this->store();

					$this->_db->setQuery("update #__bids set accept=1 where id='".$isduplicate[0]->id."'");
					$this->_db->query();

        			$this->SendMails(array($usr),'bid_accepted');

        	    	$usr->load($this->userid);
        	    	$this->SendMails(array($usr),'bid_offer_winner_to_owner');
        	    }
        	}

	    } else {
	    	$usr->load($this->userid);
	    	$this->SendMails(array($usr),'bid_offer_no_winner_to_owner');
		}

    }
    function SetPaymentMethod($payment_method_name)
    {
        $this->_db->setQuery("select id from #__bid_payment where name='$payment_method_name'");
        $this->payment=$this->_db->LoadResult();
        return $this->payment;
    }
    function SetCategory($category_name)
    {
        $this->_db->setQuery("select id from #__bid_categories where catname='".$category_name."'");
        $this->cat=$this->_db->LoadResult();
        return $this->cat;
    }
    function SetCurrency($currency_name)
    {
        $this->_db->setQuery("select id from #__bid_currency where name='".$currency_name."'");
        $this->currency=$this->_db->LoadResult();
        return $this->currency;
    }
    function GetPaymentMethod()
    {
        $this->_db->setQuery("select name from #__bid_payment where id='$this->payment'");
        return $this->_db->LoadResult();
    }
    function GetCategory()
    {
        $this->_db->setQuery("select catname,id from #__bid_categories where id='$this->cat'");
        return $this->_db->LoadResult();
    }
    function GetCurrency()
    {
        $this->_db->setQuery("select name from #__bid_currency where id='$this->currency'");
        return $this->_db->LoadResult();
    }
    function GetWinningBid()
    {
		$query = "SELECT * from #__bids b where id_offer='$this->id'  and accept = 1";
		$this->_db->setQuery($query);
		$b=null;
		$this->_db->LoadObject($b);
		return $b;

    }
    function GetBestBid()
    {
		$query = "SELECT * from #__bids b where id_offer='$this->id'  order by bid_price desc";
		$this->_db->setQuery($query,0,1);
		$b=null;
		$this->_db->LoadObject($b);
		return $b;

    }
	//JaiStartI
    function getManagerSettings()
    {
		$query = "select cp.id as comprofid,  cb_buyerschoice as buyerschoice, cb_startdate as start_date, cb_enddate as end_date, cb_shipment, cb_payment, cb_inactivity as inactivity from #__comprofiler cp JOIN #__users u ON cp.user_id = u.id WHERE gid = 21";
		$this->_db->setQuery($query,0,1);
		$b=null;
		$this->_db->LoadObject($b);
		if($b->buyerschoice == "Buyer's Choice") {
			$this->start_date = $b->start_date;
			$this->end_date = $b->end_date;
		}
		return $b;
    }
	//JaiENDI
    function GetParam($paramname,$def_value=null)
    {
        if (class_exists('JParameter')){
   			$param=new JParameter($this->params);
        }else{
            $param=new mosParameters($this->params);
        }

        if ($def_value===null){
            $def_value=$this->_parameters[$paramname];
        }
        return $param->get($paramname,$def_value);
    }
    function SetParam($paramname,$value)
    {
        if (class_exists('JParameter')){
   			$param=new JParameter($this->params);
        }else{
            $param=new mosParameters($this->params);
        }
        $param->set($paramname,$value);
        $this->params="";
        foreach ($this->_parameters as $key=>$def_val){
            $this->params.="$key=".$param->get($key,$def_val)."\n";
        }
    }
    function SetAllParams($request_array)
    {
        if (!is_array($request_array)) return ;


        foreach ($this->_parameters as $key=>$def_val){
            if (isset($request_array[$key])){
                $this->SetParam($key,$request_array[$key]);
            }else{
                $this->SetParam($key,$def_val);
            }
        }

    }
    function ParamsToArray()
    {
        if (class_exists('JParameter')){
   			$param=new JParameter($this->params);
        }else{
            $param=new mosParameters($this->params);
        }
        foreach ($this->_parameters as $key=>$def_val){
            if ($param->get($key,'')==''){
                $param->set($key,$def_val);
            }
        }

        return $param->toArray();
    }
    function getFieldOrderArray()
    {
        return array(
        	'start_date'	=> bid_sort_start_date,
        	'title'			=> bid_sort_title,
        	'name'			=> bid_sort_username,
        	'end_date'		=> bid_sort_end_date,
        	'initial_price+0'	=> bid_sort_initialprice,
        	'hits'			=> bid_sort_hits,
        	'BIN_price+0' 	=> bid_sort_binprice,
        	'id' 			=> bid_sort_newest
        );

    }
	/* JaiSTartG */
	function getFieldOrderBBArray()
    {
        return array(
        	'title'			=> bid_sort_title,
        	'id' 			=> bid_sort_newest
        );

    }
	// JaiEndG
}

class mosBids extends mosDBTable {
	var $id					= null;
	var $id_offer			= null;
	var $userid				= null;
	var $initial_bid        = null;
	var $bid_price			= null;
	var $payment			= null;
	var $cancel				= null;
	var $accept				= null;
	var $modified			= null;
    var $id_proxy           = null;
/**
* @param database A database connector object
*/
	function mosBids( &$db ) {
		$this->mosDBTable( '#__bids', 'id', $db );
	}

	function LoadBidForUser($auctionid,$userid){
	    $tmp = false;
	    $this->_db->setQuery("SELECT * FROM ".$this->_tbl." WHERE cancel=0 and id_offer='$auctionid' and userid='$userid' ");
	    $this->_db->LoadObject($tmp);
	    return $tmp;
	}



    function getAuction()
    {
        $a=new mosBidOffers($this->_db);
        $a->load($this->id_offer);
        return $a;
    }
}


class mosBidMessages extends mosDBTable {
	var $id					= null;
	var $id_offer			= null;
	var $userid1			= null;
	var $userid2			= null;
	var $parent_message		= null;
	var $message			= null;
	var $modified			= null;
	var $wasread            = null;

/**
* @param database A database connector object
*/
	function mosBidMessages( &$db ) {
		$this->mosDBTable( '#__bid_messages', 'id', $db );
	}
}

class mosBidReportOffer extends mosDBTable {
	var $id 				= null;
	var $id_offer			= null;
	var $userid				= null;
	var $message			= null;
	var $processing			= null;
	var $solved				= null;
	var $modified			= null;

	function mosBidReportOffer( &$db ) {
		$this->mosDBTable( '#__bid_report_auctions', 'id', $db );
	}
}

class mosBidMails extends mosDBTable {
	var $id 				= null;
	var $mail_type			= null;
	var $content			= null;
	var $subject			= null;
	var $enabled            = null;

	function mosBidMails( &$db ) {
		$this->mosDBTable( '#__bid_mails', 'mail_type', $db );
	}

	function store( $updateNulls=false ) {
		$k = $this->_tbl_key;
		if ($this->$k) {
			$ret = $this->_db->updateObject($this->_tbl, $this, $this->_tbl_key, $updateNulls);
		} else {
			$ret = $this->_db->insertObject($this->_tbl, $this, $this->_tbl_key);
		}

		if (!$ret) {
			$this->_error = strtolower(get_class($this))."::store failed <br />" . $this->_db->getErrorMsg();
			return false;
		} else {
			return true;
		}
	}

}

class mosBidUsers extends mosDBTable {
	var $userid				= null;
	var $name				= null;
	var $surname			= null;
	var $address			= null;
	var $city				= null;
	var $country			= null;
	var $phone				= null;
	var $verified			= null;
	var $isBidder			= null;
	var $isSeller			= null;
	var $powerseller		= null;
	var $modified			= null;
    var $paypalemail        = null;
	function mosBidUsers( &$db ) {
		$this->mosDBTable( '#__bid_users', 'userid', $db );
	}
	function getUserDetails($uid=null)
	{
	    global $cb_fieldmap;
	    if ($uid) $this->userid=$uid;
	    if (CB_DETECT){


	        $this->_db->setQuery("select * from #__comprofiler where user_id='$this->userid'");
	        $u_cb=$this->_db->loadAssocList();
            $u_cb=$u_cb[0];
            if ($cb_fieldmap["verified"])
                $this->verified=$u_cb[$cb_fieldmap["verified"]];
            if ($cb_fieldmap["isBidder"])
                $this->isBidder=$u_cb[$cb_fieldmap["isBidder"]];
            if ($cb_fieldmap["isSeller"])
                $this->isSeller=$u_cb[$cb_fieldmap["isSeller"]];
            if ($cb_fieldmap["country"])
                $this->country=$u_cb[$cb_fieldmap["country"]];
            if ($cb_fieldmap["city"])
                $this->city=$u_cb[$cb_fieldmap["city"]];
            if ($cb_fieldmap["powerseller"])
                $this->powerseller=$u_cb[$cb_fieldmap["powerseller"]];
            if ($cb_fieldmap["paymentparam"])
                $this->paymentparam=$u_cb[$cb_fieldmap["paymentparam"]];
            $keys=array_keys($u_cb);
            for($i=0;$i<count($u_cb);$i++)
            {
                $field=$keys[$i];
                $this->$field=$u_cb[$field];
            }

	    }else {
	        $this->load();
	    }
	    return $this;
	}
}

class mosBidRate extends mosDBTable {
	var $id 				= null;
	var $voter				= null;
	var $user_rated 		= null;
	var $modified			= null;
	var $auction_id         = null;
	var $message            = null;
	var $rate_type			= null;

	function mosBidRate( &$db ) {
		$this->mosDBTable( '#__bid_rate', 'id', $db );
	}

}

class mosBidCountry extends mosDBTable {
	var $id 				= null;
	var $name				= null;
	var $simbol				= null;
	var $active				= null;

	function mosBidCountry( &$db ) {
		$this->mosDBTable( '#__bid_country', 'id', $db );
	}
}

class mosBidPicture extends mosDBTable {
	var $id 				= null;
	var $id_offer			= null;
	var $userid				= null;
	var $picture			= null;
	var $modified			= null;

	function mosBidPicture ( &$db ) {
		$this->mosDBTable( '#__bid_pictures', 'id', $db );
	}
}

class mosBidWatchlist extends mosDBTable {

	var $id 				= null;
	var $userid            = null;
	var $auctionid         = null;

	function mosBidWatchlist( &$db ){
		$this->mosDBTable( '#__bid_watchlist', 'id', $db);
	}
}

class mosBidProxyBids extends mosDBTable {
	var $id				   = null;
	var $auction_id        = null;
	var $user_id            = null;
	var $max_proxy_price   = null;
	var $active            = null;
	var $latest_bid        = null;

	function mosBidProxyBids(&$db){
		$this->mosDBTable('#__bid_proxy','id',$db);
	}
}
/* JaiStartH */
class mosBidProxyPlusBids extends mosDBTable {
	var $id				   = null;
	var $auction_id        = null;
	var $ticket_id            = null;
	var $my_bid   = null;
	var $priority     = null;
	var $datemodified        = null;

	function mosBidProxyPlusBids(&$db){
		$this->mosDBTable('#__proxyplus_bids','id',$db);
	}
	function store( $updateNulls=false ) {
		$k = $this->_tbl_key;
		if ($this->$k) {
			$ret = $this->_db->updateObject($this->_tbl, $this, $this->_tbl_key, $updateNulls);
		} else {
			$ret = $this->_db->insertObject($this->_tbl, $this, $this->_tbl_key);
		}

		if (!$ret) {
			$this->_error = strtolower(get_class($this))."::store failed <br />" . $this->_db->getErrorMsg();
			return false;
		} else {
			return true;
		}
	}
	function setDelete($ticketid = 0) {
		echo $query = "delete from ".$this->_tbl." WHERE ticket_id = ".$ticketid;
        $this->_db->setQuery($query);
        $this->_db->query();
	}
}
/* JaiStartI */
class mosComProfiler extends mosDBTable {
	var $id				   = null;
	var $user_id        = null;
	var $cb_buyerschoice            = null;
	var $cb_startdate   = null;
	var $cb_enddate     = null;
	var $cb_inactivity     = null;
	var $gid        = null;

	function mosComProfiler(&$db){
		$this->mosDBTable('#__comprofiler','id',$db);
	}
	function store( $updateNulls=false ) {
		$k = $this->_tbl_key;
		if ($this->$k) {
			$ret = $this->_db->updateObject($this->_tbl, $this, $this->_tbl_key, $updateNulls);
		} else {
			$ret = $this->_db->insertObject($this->_tbl, $this, $this->_tbl_key);
		}

		if (!$ret) {
			$this->_error = strtolower(get_class($this))."::store failed <br />" . $this->_db->getErrorMsg();
			return false;
		} else {
			return true;
		}
	}
	function getManagerInfo() {
		$tmp = false;
	    $this->_db->setQuery("SELECT cb_buyerschoice, cb_startdate, cb_enddate FROM ".$this->_tbl." cp JOIN #__users u ON cp.user_id = u.id WHERE gid = 21");
	    $this->_db->LoadObject($tmp);
	    return $tmp;
	}
}
/* JaiStartI */
class mosNextBid extends mosDBTable {
	var $bid_inc_id				   = null;
	var $bid_next        = null;
	var $range_from            = null;
	var $range_to   = null;
	var $date_modifies     = null;

	function mosNextBid(&$db){
		$this->mosDBTable('#__bid_increment','bid_inc_id',$db);
	}
	function setDelete($id=0){
        $this->_db->setQuery("delete from ".$this->_tbl);
        $this->_db->query();
	 }
	function store( $updateNulls=false ) {
		$k = $this->_tbl_key;
		if ($this->$k) {
			$ret = $this->_db->updateObject($this->_tbl, $this, $this->_tbl_key, $updateNulls);
		} else {
			$ret = $this->_db->insertObject($this->_tbl, $this, $this->_tbl_key);
		}

		if (!$ret) {
			$this->_error = strtolower(get_class($this))."::store failed <br />" . $this->_db->getErrorMsg();
			return false;
		} else {
			return true;
		}
	}
}
class mosProxyPlusTicket extends mosDBTable {
	var $id				   = null;
	var $auction_id        = null;
	var $userid = null;
	var $datemodified        = null;
	var $ticket_id = null;
	var $lot_desired = null;

	function mosProxyPlusTicket(&$db){
		$this->mosDBTable('#__proxyplus_tickets','id',$db);
	}

	function store( $updateNulls=false ) {
		echo $k = $this->_tbl_key;
		if ($this->$k) {
			$ret = $this->_db->updateObject($this->_tbl, $this, $this->_tbl_key, $updateNulls);
		} else {
			$ret = $this->_db->insertObject($this->_tbl, $this, $this->_tbl_key);
		}

		if (!$ret) {
			$this->_error = strtolower(get_class($this))."::store failed <br />" . $this->_db->getErrorMsg();
			return false;
		} else {
			return true;
		}
	}
}
/*JaiEndH*/

class mosBidCategories extends mosDBTable {
	var $id                = null;
	var $catname           = null;
	var $parent            = null;
	var $hash              = null;
	var $ordering          = null;

	function mosBidCategories(&$db){
		$this->mosDBTable('#__bid_categories','id',$db);

	}
	
	function build_child( $id, $include_parent=false ,$custom_filter = "" ){
		$tree = $this->build_children_tree($id, $include_parent, false, $custom_filter);
		return $tree;
	}

	function build_children_tree($pid,$include_parent=false,$flag_reset=false,$custom_filter){
		global $database;	
	
		static $treeCat = array(),$depth=0;
		if($flag_reset){
			$treeCat = array();
		}
		
	
		if($pid==='' || $pid<0) return;
	
		//adding the parent element to the tree
		if($include_parent && $depth==0){
			if($pid>0){
				$q="SELECT * FROM #__bid_categories WHERE id='".$pid."' {$custom_filter} LIMIT 1";
				$database->setQuery($q);
				$base_parent=$database->loadAssocList('id');
	
				$key = key($base_parent);
	
				$treeCat[$key] = $base_parent[$key];
				$treeCat[$key]['depth'] = 0;
				$treeCat[$key]['prev'] = null;
				$treeCat[$key]['next'] = null;
				$treeCat[$key]['nr_children'] = Bids_has_children($pid);
			}
			//a special case when $pid=0; obviously, there's no category with id=0, so...
			else{
				$treeCat[0]['id'] = 0;
				$treeCat[0]['catname'] = "ROOT";
				$treeCat[0]['parent'] = null;
				$treeCat[0]['ordering'] = null;
				$treeCat[0]['description'] = 'Base level';
				$treeCat[0]['hash'] = null;
				$treeCat[0]['depth'] = 0;
				$treeCat[0]['nr_children'] = Bids_has_children(0);
			}
		}
	
		$q="SELECT * FROM #__bid_categories WHERE parent='".$pid."' {$custom_filter} ORDER BY ordering ASC";
		$database->setQuery($q);
		$children=$database->loadAssocList('id');
		//echo $database->_sql;exit;
	
		//in the next 2 "foreach" structures i'm adding to the "tree" the children of $pid
		$prev_key = null;
		$i=0;
		if(count($children))
		foreach($children as $key=>$value){
	
			$child = &$children[$key];
	
			//here i add to each category the 'prev' and 'next' keys, in which we store the prevoius and next categories' ids
			if( $i==0 ){
				$child['prev']=null;
				if(count($children)==1){
					$child['next']=null;
				}
			}
			elseif( $i>0 ){
				$child['prev'] = $children[$prev_key]['id'];
				$children[$prev_key]['next'] = $child['id'];
				if( $i==(count($children)-1) ){
					$child['next'] = null;
				}
			}
	
			//also i add the 'depth' key;
			$child['depth']=$depth;
	
			//and now the number of children for the child category
			$child['nr_children']=Bids_has_children($child['id']);
	
			$prev_key = $key;
			$i++;
		}
	
		if(count($children))
		foreach($children as $key=>$value){
	
			$child = &$children[$key];
			//keeping the id indexing
			$treeCat[$key]=$child;
	
			if($child['nr_children']){
				$depth++;
					$this->build_children_tree($child['id'],$include_parent,$flag_reset,$custom_filter);
				$depth--;
			}
		}
	
		return $treeCat;
	}
	function string_cat_path($id){
	
		$cat_path = $this->get_cat_path($id);
		global $Itemid;
	
		//if only the root is in path, don't display it
		if(count($cat_path)==1){
			return;
		}
	
		$string_path = '';
		$i=1;
		$nrcats = count($cat_path);
		foreach($cat_path as $cat){
			$link = sefRelToAbs( 'index.php?option=com_bids&cat='.$cat['id'].'&Itemid='.$Itemid);
			$string_path .= '<a href="'.$link.'">'.$cat['catname'].'</a>/';
			$i++;
		}
		$string_path = trim($string_path,"/");
	
		return $string_path;
	}
	
	function get_cat_path($id){
	
		static $cat_path=array();
	
		global $database;
	
		$q = "select id,catname,parent from #__bid_categories where id='".$id."'";
		$database->setQuery($q);
		$res = $database->loadAssoc();
	
		if($res['id'])
				array_push($cat_path,$res);
	
		if($res['parent']>0){
			$this->get_cat_path($res['parent']);
		}
		else{
	
			$root = array();
			$root['id'] = 0;
			$root['catname'] = bids_root_category;
			$root['parent'] = null;
			array_push($cat_path,$root);
	
			$cat_path=array_reverse($cat_path);
		}
	
		return $cat_path;
	}
	

	function getCategoryChildren($id){
	
		static $_CategoryChildren = array();
		global $database;
	
		$q = "SELECT id FROM #__bid_categories WHERE parent='".$id."'";
		$database->setQuery($q);
		$res = $database->loadAssocList("id");
	
		if(count($res)>0){
				$_CategoryChildren = array_merge($_CategoryChildren, array_keys($res));
				foreach($res as $i => $r){
					$this->getCategoryChildren($r['id']);
				}
			
		}
		//var_dump($_CategoryChildren);
		return $_CategoryChildren;
	}

	
}

class mosBidTags extends mosDBTable {
    var $id=null;
    var $auction_id=null;
    var $tagname=null;
    function mosBidTags(&$db){
        $this->mosDBTable('#__bid_tags','id',$db);
    }
    function setAuctionTags($auction_id,$tags){
        /*@var $tags string*/
        //first delete old tags
        $this->_db->setQuery("delete from ".$this->_tbl." where auction_id='$auction_id'");
        $this->_db->query();

        $tag_arr=explode(',',$tags);

        for ($i=0;$i<min(count($tag_arr),bid_opt_max_nr_tags);$i++){
            $this->id=null;
            $this->auction_id=$auction_id;
            $this->tagname=$tag_arr[$i];
            $this->store();
        }
    }
    function getAuctionTags($auction_id) {
        $this->_db->setQuery("select tagname,id from ".$this->_tbl." where auction_id='$auction_id' order by id");
        return  mosStripslashes($this->_db->loadResultArray());
    }
}
class mosBidPayLog extends mosDBTable {
    var $id=null;
    var $date=null;
    var $amount=null;
    var $currency=null;
    var $refnumber=null;
    var $invoice=null;
    var $ipn_response=null;
    var $ipn_ip=null;
    var $comission_id=null;
    var $status=null;
    var $userid=null;
    var $itemname=null;
    var $payment_method=null;

    function mosBidPayLog(&$db)
    {
        $this->mosDBTable('#__bid_paylog','id',$db);
    }


}


?>