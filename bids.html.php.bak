<?php
/**
 * @package AuctionsFactory
 * @version 1.6.0
 * @copyright www.thefactory.ro
 * @license: commercial
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

//================================= BIDS ============================
require_once( $mainframe->getPath( 'class',$option ) );
if(defined("bid_opt_gallery")){
	require_once( BIDS_COMPONENT_PATH."/gallery/gl_".bid_opt_gallery.".php");
}
else
	require_once( BIDS_COMPONENT_PATH."/gallery/gl_lytebox.php");


class HTML_Auction_helper{
	
	function reportAuction($id,$title,$Itemid){
		
		global $mosConfig_live_site;
		?>
    	<script language="javascript" type="text/javascript" src="<?php echo BIDS_COMPONENT."/fvalidate/fValidate.core.js" ?>"></script>
		<link href="<?php echo $mosConfig_live_site;?>/components/com_bids/css/<?php echo bid_css;?>" rel="stylesheet" type="text/css" />
		<form action="index.php" name="auctionForm" method="POST">
		<input type="hidden" name="option" value="com_bids" />
		<input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
		<input type="hidden" name="id" value="<?php echo $id;?>" />
		<input type="hidden" name="task" value="report_auction" />
		 <table>
			<tr><td><div class="bidmessage" ><?php echo bid_report_offer_msg; ?></div></td></tr>
			<tr><td><span class="bidtitle"><?php echo $title;?></span></td></tr>
			<tr><td><textarea name="message" rows="10" cols="50"></textarea></td></tr>
			<tr>
				<td>
					<a href='javascript:history.go(-1);'><input type="button" class="back_button" value="<?php echo but_back;?>" /></a>
					<input type="submit" name="send" value="<?php echo(bids_send_message); ?>" class="back_button" />
				</td>
			</tr>
		 </table>
		</form>
		<?php
	}
	function searchAuction(&$lists){
		/*@var $smarty Smarty*/
		if(JOOMLA_VERSION=="1")	mosCommonHTML::loadCalendar();
		else{
			?>
			<script type="text/javascript" src="<?php echo JURI::root(true);?>/includes/js/calendar/calendar.js"></script>
			<link rel="stylesheet" href="<?php echo JURI::root(true);?>/media/system/css/calendar-jos.css" type="text/css"  title="green"  media="all" />
			<script type="text/javascript" src="<?php echo JURI::root(true);?>/includes/js/calendar/lang/calendar-en-GB.js"></script>
			<?php
		}
		$smarty=HTML_Auction::LoadSmarty();
		$smarty->assign("lists",$lists);
	    $smarty->display("t_search.tpl");
	}

	function bulkimport($Itemid,$option,$errors){
		global $database,$mosConfig_live_site,$mosConfig_absolute_path;
		 mosCommonHTML::loadOverlib();
		?>
		<link href="<?php echo $mosConfig_live_site;?>/components/com_bids/css/<?php echo bid_css;?>" rel="stylesheet" type="text/css">
		<script language="javascript" type="text/javascript">
			function validateForm(){
				var csv = document.getElementById('csv');
				if(!csv.value){
					alert('<?php echo bid_err_csv_no_file;?>');
					return false;
				}
			}
		</script>
		  <form action="index.php" method="post" name="auctionForm" onsubmit="return validateForm();" enctype="multipart/form-data">
		  <input type="hidden" name="option" value="com_bids">
		  <input type="hidden" name="task" value="importcsv">
		  <input type="hidden" name="Itemid" value="<?php echo $Itemid;?>">
		  <table width="100%" class="contentpaneopen">
			<tr><th colspan="2"><?php echo bid_auctions_bulk_import; ?></th></tr>
			<tr>
				<td><?php echo bid_csv_file; echo mosToolTip(bid_help_uploadcsv); ?></td>
				<td><input type="file" name="csv" id="csv"></td>
			</tr>
			<tr>
				<td><?php echo bid_csv_img_arch; echo mosToolTip(bid_help_uploadimg); ?></td>
				<td><input type="file" name="arch" id="arch"></td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" name="send" value="<?php echo but_save;?>" class="back_button" /></td>
			</tr>
		  </table>
		  </form>
		<div align="left">
		<?php
		if(count($errors)>0){
			for ($i=1;$i<=count($errors);$i++){
				if($errors[$i]){ echo bid_line." ".$errors[$i].'<br>'; }
			}
		}
		?>
		</div>
		<?php
	}

	function listCategories($option,$rows,$Itemid){
		/*@var $smarty Smarty*/
        $smarty=HTML_Auction::LoadSmarty();
		?>
		<link href="<?php echo $mosConfig_live_site;?>/components/com_bids/css/<?php echo bid_css;?>" rel="stylesheet" type="text/css">
		<?php if (file_exists(BIDS_COMPONENT_PATH."/templates/bid_template.css")) {?>
    		<link href="<?php echo $mosConfig_live_site;?>/components/com_bids/templates/bid_template.css" rel="stylesheet" type="text/css">
		<?php }
		$smarty->assign('categories',$rows);
        $smarty->display("t_categories.tpl");
	}
}

class HTML_Auction{
    function LoadSmarty(){
        global $Itemid,$task,$option,$mosConfig_live_site,$my;
        // set path to Smarty directory *nix style
        if (!defined('SMARTY_DIR')) define('SMARTY_DIR', BIDS_COMPONENT_PATH.'/smarty/libs/');
        // include the smarty class Note 'S' is upper case
        require_once(SMARTY_DIR . 'Smarty.class.php');
        require_once(BIDS_COMPONENT_PATH . '/tabbedview.php');
        require_once(BIDS_COMPONENT_PATH . '/bids.smarty.plugins.php');
        require_once(BIDS_COMPONENT_PATH . '/bids.smarty.php');

        $act=mosGetParam($_REQUEST,'act','');
		$smarty = new AuctionSmarty();
		$smarty->template_dir=BIDS_COMPONENT_PATH.'/templates/';
		$smarty->compile_dir=BIDS_COMPONENT_PATH.'/templates/cache/';
		//$smarty->debugging= true;

		$smarty->register_function('set_css','smarty_set_css');
		$smarty->register_function('infobullet','smarty_infobullet');
		$smarty->register_function('printdate','smarty_printdate');
		$smarty->register_function('createtab','smarty_createtab');
		$smarty->register_function('startpane','smarty_startpane');
		$smarty->register_function('endpane','smarty_endpane');
		$smarty->register_function('starttab','smarty_starttab');
		$smarty->register_function('endtab','smarty_endtab');
		$smarty->register_modifier('stripslashes', 'stripslashes');

		$smarty->assign('mosConfig_live_site',$mosConfig_live_site);
		$smarty->assign('Itemid',$Itemid);
		$smarty->assign('task',$task);
		$smarty->assign('act',$act);
		$smarty->assign('option',$option);
		$smarty->assign('is_logged_in',($my->id)?"1":"0");
		$smarty->assign('userid',$my->id);

		$arr_dateformat=array(
			'Y-m-d'=>'%Y-%m-%d',
			'Y-d-m'=>'%Y-%d-%m',
			'm/d/Y'=>'%m/%d/%Y',
			'd/m/Y'=>'%d/%m/%Y',
			'D, F d Y'=>'%Y-%m-%d'
		);
		$smarty->assign('opt_date_format',$arr_dateformat[bid_opt_date_format]);

		return $smarty;
    }

	function AuctionDetails( &$auction,&$lists,&$bid_user,&$bid_user_details,&$bids_list,$currency,$payment,$messages,$bid_increment=0 )
	{
		/* @var $smarty Smarty*/
		/* @var $auction mosBidOffers */
		/* @var $bid_user mosUser */
		/* @var $max_price int */
		/* @var $bid_list array */
		/* @var $count_bidders int */

		/* @var $currency int */
		global $database,$my,$Itemid,$cb_fieldmap,$mosConfig_live_site;
		global $mainframe;
		$smarty=HTML_Auction::LoadSmarty();
		$pageId = mosGetParam( $_REQUEST, 'p', "");
		/* Page tagging */
		$tags= new mosBidTags($database);
        $auction->tags=$tags->getAuctionTags($auction->id);
        $mainframe->setPageTitle(mosStripslashes($auction->title));
        $mainframe->addMetaTag('description',strip_tags(mosStripslashes($auction->shortdescription)));
        $mainframe->addMetaTag('abstract',strip_tags(mosStripslashes($auction->description)));
        $mainframe->appendMetaTag('keywords',mosStripslashes(strip_tags(implode($auction->tags,','))));
        // gallery
		?>
		<script>
				setInterval("window.location.reload();",<?php echo (bid_opt_refresh_minutes * 1000 * 60); ?>);
		</script>
		<?php
		if(defined("bid_opt_gallery"))
			$gallery_name = "gl_".bid_opt_gallery;
		else
			$gallery_name = "gl_lytebox";
		$gallery=new $gallery_name($database,AUCTION_PICTURES);$gallery->getGalleryForAuction($auction);
        if (count($gallery->imagelist)>0){$gallery->writeJS();}

        if ($my->id){
			$database->setQuery("select count(*) from #__bid_messages where userid2='$my->id' and id_offer='$auction->id' and wasread!=1");
			$auction->nr_new_messages = $database->loadResult();

			if ($auction->userid==$my->id) $auction->is_my_auction=1;
			else $auction->is_my_auction=0;
			if (!$auction->is_my_auction && !$auction->close_offer) {
					$database->setQuery("SELECT count(*) from #__bid_watchlist WHERE userid='$my->id' AND auctionid='$auction->id' ");
					if($database->loadResult())
						$auction->del_from_watchlist=1;
					else
	 					$auction->add_to_watchlist=1;
			}
			$mybid=new mosBids($database);
        	if (! $mybid->LoadBidForUser($auction->id,$my->id)) $mybid=null;
			if(isset($mybid->bid_price))
        		$auction->mybid = $mybid->bid_price;
        	else
        		$auction->mybid = null;
        }

		$database->setQuery("SELECT sum(rating)/count(*) from #__bid_rate where user_rated='$auction->userid' and rate_type='auctioneer'");
		$auction->rating_auctioneer= $database->loadResult();

		$database->setQuery("SELECT sum(rating)/count(*) from #__bid_rate where user_rated='$auction->userid' and rate_type='bidder'");
		$auction->rating_bidder= $database->loadResult();

		$database->setQuery("SELECT sum(rating)/count(*) from #__bid_rate where user_rated='$auction->userid' ");
		$auction->rating_overall= $database->loadResult();

		if ($auction->is_my_auction || ($auction->auction_type==AUCTION_TYPE_PUBLIC && $auction->GetParam('bid_counts'))){
			$database->setQuery("SELECT count(distinct userid) from #__bids where id_offer=$auction->id");
			$auction->nr_bidders = $database->loadResult();
		}
		if ($auction->is_my_auction || ($auction->auction_type==AUCTION_TYPE_PUBLIC && $auction->GetParam('max_price'))){
			$database->setQuery("SELECT b.bid_price,b.userid from #__bids b where id_offer='$auction->id'  and accept = 1");
			$winbid = $database->loadRow();

			if ($winbid){
			    $auction->winning_bid=$winbid[0];
				$auction->winning_user=$winbid[1];
			    if($winbid[1]==$my->id && $my->id) $auction->i_am_winner=1;
			}
			else{
				$database->setQuery("SELECT max(bid_price) from #__bids where id_offer=$auction->id");
				$max_bid = $database->loadResult();
				if ($max_bid) {
					$auction->highest_bid=$max_bid;
					$database->setQuery("SELECT userid from #__bids where id_offer=$auction->id AND bid_price = $auction->highest_bid");
					$max_bid_user = $database->loadResult();
					if ($max_bid_user) 
						$auction->winning_user = $max_bid_user;
				}
			}

		}elseif ($auction->close_offer){
			$database->setQuery("SELECT b.bid_price from #__bids b where id_offer='$auction->id'  and accept = 1 and userid='$my->id'");
			$winbid = $database->loadRow();
			if ($winbid){
			    $auction->winning_bid=$winbid[0];
    		    $auction->i_am_winner=1;
			}
		}
		if (bid_opt_allowpaypal ){
			/**  ==> Fixed in Auction 1.5.8 **/
    		$paypalemail=null;
		    if (CB_DETECT){
		    	if($cb_fieldmap['paymentparam']){
			        $database->setQuery("select ".$cb_fieldmap['paymentparam']." from #__comprofiler where user_id='$auction->userid' ");
				    $paypalemail=$database->loadResult();
		    	}else
		    		$paypalemail=null;
		    }else{
		        $database->setQuery("select paypalemail from #__bid_users where userid='$auction->userid' ");
		    }
		    $auction->paypalemail=$paypalemail;
			/**  <== Fixed in Auction 1.5.8 **/
		}
		$bidStatus = bid_process;
		$auction->countdowntype = 1;
		if(bid_opt_enable_countdown){
            $expiredate = strtotime($auction->end_date);
            $diff       = $expiredate - time();
            if ($diff>0){
                $s=sprintf("%02d",$diff%60);
                $diff=intval($diff/60);
                $m=sprintf("%02d",$diff%60);
                $diff=intval($diff/60);
                $h=sprintf("%02d",$diff%24);
                $d=intval($diff/24);

                if ($d>0) $auction->countdown="$d ".bid_days.", $h:$m:$s";
                else $auction->countdown="$h:$m:$s";
				$auction->countdowntype = 0;
            }else{
                $auction->countdown=bid_process_complete;
            }
		}
		//JaiStartJ
		$expiredate=strtotime($auction->start_date);
		$diff=$expiredate-time();
		if ($diff>0){
					$s=sprintf("%02d",$diff%60);
					$diff=intval($diff/60);
					$m=sprintf("%02d",$diff%60);
					$diff=intval($diff/60);
					$h=sprintf("%02d",$diff%24);
					$d=intval($diff/24);
					if ($d>0) $auction->countdown="$d ".bid_days.", $h:$m:$s";
					else $auction->countdown="$h:$m:$s";
					$auction->countdowntype = 1;
		}
		/*JaiStartE */
		
		//TODO JAI Old one 
		$database->setQuery("select cb_buyerschoice, cb_startdate, cb_enddate, cb_shipment, cb_payment from #__comprofiler cp JOIN #__users u ON cp.user_id = u.id WHERE gid = 21");
		//$database->setQuery("select cb_buyerschoice, cb_startdate, cb_enddate, cb_shipment, cb_payment from #__comprofiler where user_id=$my->id");
		$managerInfo=$database->loadObjectList();
		$auction->buyersChoiceManager = $managerInfo[0]->cb_buyerschoice;
		if($auction->buyersChoiceManager == "Buyer's Choice") {
			$auction->start_date = $managerInfo[0]->cb_startdate;
			$auction->end_date  = $managerInfo[0]->cb_enddate;
		}
		$auction->startDateManager = $managerInfo[0]->cb_startdate;
		$auction->endDateManager = $managerInfo[0]->cb_enddate;
		$auction->shipment_info = $managerInfo[0]->cb_shipment;
		$auction->payment_name = $managerInfo[0]->cb_payment;
		//TODO Old code
		//$auction->payment_name =$payment;
		/* JaiEndE */

		/* JaiStartC */
		if(bid_opt_enable_hour){
			         $dateformat=bid_opt_date_format." H:i";
		}else $dateformat=bid_opt_date_format;
	    $auction->start_date_text=date($dateformat,strtotime($auction->start_date));
		/* JaiEndC */
		if($auction->end_date && $auction->end_date!='0000-00-00 00:00:00' ) {
			if(bid_opt_enable_hour){
			         $dateformat=bid_opt_date_format." H:i";
			}else $dateformat=bid_opt_date_format;
			$auction->end_date_text=date($dateformat,strtotime($auction->end_date));
		}
        if($auction->auction_type!=AUCTION_TYPE_PRIVATE && bid_opt_allow_proxy == 1 && !$auction->is_my_auction){
			$database->setQuery("select max_proxy_price from #__bid_proxy where auction_id='$auction->id' and user_id = '$my->id' ");
       		$auction->my_proxy_bid = $database->loadResult();
        }
        if ($auction->close_offer && $my->id && ($auction->is_my_auction || $auction->i_am_winner)){
		  $database->setQuery("select count(id) from #__bid_rate where (voter = '$my->id' and auction_id = '$auction->id')");
		  if ($database->loadResult()==0)
		      $auction->must_rate=1;
        }
    	$auction->links=HTML_Auction::_CreateLinksArray($auction,$lists);
    	$auction->thumbnail=$gallery->getThumb(0,0);
    	$auction->gallery=$gallery->getGallery(0,1);
        $auction->currency_name=$currency;
        

        //$auction->catname=mosStripslashes($auction->GetCategory());
        global $mosConfig_absolute_path;
        require_once($mosConfig_absolute_path."/components/com_bids/router.php");
        $auction->catname=sefbids_helper::getCatString($auction->cat);

        $auction->username=$bid_user->username;
        $auction->verified_auctioneer=mosBidACL::_isVerified($auction->userid);
        if(isset($auction->winning_bid) )
        	$auction->total_price = $auction->winning_bid + $auction->shipment_price;

        $auction->description=mosStripslashes($auction->description);
        $auction->shortdescription=mosStripslashes($auction->shortdescription);
		/* JaiStartD */
		if(strtotime($auction->start_date) > time()) {
			$auction->isValidateDate = 1; 
		}else {
			$auction->isValidateDate = 0; 
		}

		if(isset($auction->highest_bid))
			$auction->min_increase = $auction->findBidIncrement($auction->highest_bid);
		else
			$auction->min_increase = $auction->findBidIncrement($auction->initial_price);

		if(strtotime($auction->end_date) <= time() || $auction->close_offer == 1){
			$auction->countdowntype = 0;
			if($auction->close_offer != 1) {
				$bidStatus = bid_process_pending;
			}else{
				$auction->expired=true;
				$bidStatus = bid_process_complete;
			}
		}else{
			$auction->expired=false;
		}
		//JAISTARTO
		// Added for checking if user has same as highest bidder
		 $auction->mine = 0;
		 if($auction->winning_user == $my->id) {
			 $auction->mine = 1;
		 }
		

		/* JaiEndD */
		  /*
		  * JaiStartJ
		  */
			$optionRowspan = 0;
			if(bid_opt_allow_auctioneer != 1 &&  label_custom_field5 == ""){
				$optionRowspan++;
				if(label_custom_field2 == "" && label_custom_field3 == ""  && label_custom_field4 == "" ){
					$optionRowspan++;
				}
			}
		  /*
		  *JaiEndJ
		  */
		  //echo "<pre>";
		  //print_R($auction);
		  //exit;
		if(!lic::checkValidity())
				  $smarty->assign('disable_bids',"disabled");
        $smarty->assign('optionRowspan',$optionRowspan);
		$smarty->assign('bid_increment',$bid_increment);
		$smarty->assign('auction',$auction);
		$smarty->assign('auctioneer',$bid_user);
		$smarty->assign('auctioneer_details',$bid_user_details);
		$smarty->assign('bid_list',$bids_list);
		$smarty->assign('message_list',$messages);
		$smarty->assign('lists',$lists);
/* PLUGINS */
        $database->setQuery("select * from #__bid_pricing where enabled=1 order by ordering");
        $pp=$database->loadObjectList();
        $pricing_plugins=array();
        if($pp)
            foreach ($pp as $p){
                $pricing_plugins[$p->itemname]=$p;
            }

		$smarty->assign('pricing_plugins',$pricing_plugins);


/* PLUGINS */

		$terms=new mosBidMails($database);
		if ($terms->load('terms_and_conditions') )
			if ($terms->content!=='') $smarty->assign('terms_and_conditions','1');

        if (bid_opt_enable_countdown) { ?>
            <script type="text/javascript" src="<?php echo $mosConfig_live_site,"/components/com_bids/js/countdown.js"?>"></script>
        <?php }
        
		if(bid_opt_enable_captcha){
			require_once(BIDS_COMPONENT_PATH.'/checkspam/checkspam.class.php');
			$cs = new checkspam;
			$cs->init_session();
			$cs->exec_checkspam(0); // Text mode verification
			$smarty->assign("cs",$cs);
		}


		$smarty->display('t_auctiondetails.tpl');
        if (bid_opt_enable_countdown) { ?>
            <script type="text/javascript">
                var days='<?php echo  bid_days;?>,';
                var expired='<?php echo $bidStatus;?>';
				var typecount = '<?php echo isset($auction->countdowntype)?$auction->countdowntype:1; ?>';
                var nrcounters=100;
            </script>
        <?php }

	}

    function _CreateLinksArray(&$auction,&$lists=array())
    {
        /*@var $auction mosBidAuction*/
        global $Itemid,$task;
		$taskOrg = mosGetParam( $_REQUEST, 'orgtask',  "");
		
        $links=array();

        $links['otherauctions']=sefRelToAbs( 'index.php?option=com_bids&amp;task=listauctions&amp;userid='. $auction->userid .'&amp;Itemid='. $Itemid );
		//@todo Change $auction->pageId to $auction->id on 5/20/2012
		if(isset($auction->pageId))
	        $links['auctiondetails'] = sefRelToAbs( 'index.php?option=com_bids&task=viewbids&orgtask='.$task.'&id='. $auction->id .'&Itemid='. $Itemid.'&p='.$auction->pageId );
		else
			$links['auctiondetails'] = sefRelToAbs( 'index.php?option=com_bids&task=viewbids&orgtask='.$task.'&id='. $auction->id .'&Itemid='. $Itemid.'&p=0');
		//JaiStartN
		//JAIMAY
		if(isset($lists['pageId']) && $lists['pageId'] > 0) {
		  $links['auctiondetails_prev'] = sefRelToAbs( 'index.php?option=com_bids&task=viewbids&orgtask='.$taskOrg.'&id='. $lists['previousPage'] .'&Itemid='. $Itemid.'&p='.($lists['pageId']-1));
		}else{
		  $links['auctiondetails_prev'] = sefRelToAbs( '');
		}
		if(isset($lists['nextPage']) && $lists['nextPage'] != "") {
			$links['auctiondetails_next'] = sefRelToAbs( 'index.php?option=com_bids&task=viewbids&orgtask='.$taskOrg.'&id='. $lists['nextPage'] .'&Itemid='. $Itemid.'&p='.($lists['pageId']+1));
		}else{
			$links['auctiondetails_next'] = sefRelToAbs( '');
		}
		//JAIENDN
        $links['bids'] = sefRelToAbs( 'index.php?option=com_bids&task=viewbids&id='. $auction->id .'&Itemid='. $Itemid ).'#bid';
        $links['bid_list'] = sefRelToAbs( 'index.php?option=com_bids&task=viewbids&id='. $auction->id .'&Itemid='. $Itemid ).'#bid_list';
		if(isset($lists['pageId']) && $lists['pageId'] > 0) { 
		  	$links['edit'] = sefRelToAbs( 'index.php?option=com_bids&amp;task=editauction&amp;id='.$auction->id.'&amp;Itemid='. $Itemid.'&p='.$lists['pageId'] );
		}else{
			if(isset($auction->pageId))
				$links['edit'] = sefRelToAbs( 'index.php?option=com_bids&amp;task=editauction&amp;id='.$auction->id.'&amp;Itemid='. $Itemid.'&p='.$auction->pageId );
			else 
				$links['edit'] = sefRelToAbs( 'index.php?option=com_bids&amp;task=editauction&amp;id='.$auction->id.'&amp;Itemid='. $Itemid.'&p=');
		}
	  	$links['cancel'] = sefRelToAbs( 'index.php?option=com_bids&amp;task=cancelauction&amp;id='.$auction->id.'&amp;Itemid='. $Itemid );
	  	$links['publish'] = sefRelToAbs('index.php?option=com_bids&amp;task=publish&amp;Itemid='.$Itemid.'&amp;id='.$auction->id);
		if(isset($auction->cat))
		  	$links['filter_cat'] = sefRelToAbs("index.php?option=com_bids&task=listauctions&cat=".$auction->cat."&Itemid=$Itemid");
		else
			$links['filter_cat'] = sefRelToAbs("index.php?option=com_bids&task=listauctions&cat=&Itemid=$Itemid");
		$links['republish'] = sefRelToAbs('index.php?option=com_bids&amp;task=republish&amp;Itemid='.$Itemid.'&amp;id='.$auction->id);
		$links['messages'] = sefRelToAbs('index.php?option=com_bids&amp;task=viewbids&amp;Itemid='.$Itemid.'&amp;id='.$auction->id).'#messages';
		$links['rate_auction'] = sefRelToAbs('index.php?option=com_bids&amp;task=viewbids&amp;Itemid='.$Itemid.'&amp;id='.$auction->id).'#bid_list';
		$links['add_to_watchlist'] = sefRelToAbs('index.php?option=com_bids&amp;task=watchlist&amp;Itemid='.$Itemid.'&amp;id='.$auction->id);
		$links['del_from_watchlist'] = sefRelToAbs('index.php?option=com_bids&amp;task=delwatch&amp;Itemid='.$Itemid.'&amp;id='.$auction->id);
        $links['pagelinks'] = sefRelToAbs('index.php?option=com_bids&amp;task='.$task.'&amp;Itemid='.$Itemid);
        $links['auctioneer_profile'] = sefRelToAbs( 'index.php?option=com_bids&amp;task=ViewDetails&amp;id='. $auction->userid .'&amp;Itemid='. $Itemid );

        $links['report'] = sefRelToAbs('index.php?option=com_bids&amp;task=report_auction&amp;id='.$auction->id.'&amp;Itemid='. $Itemid);
		$links['bin'] = sefRelToAbs( 'index.php?option=com_bids&amp;task=bin&amp;id='.$auction->id.'&amp;Itemid='. $Itemid );

        $links['new_auction'] = sefRelToAbs( 'index.php?option=com_bids&amp;task=newauction&amp;Itemid='. $Itemid );
		$links['bulkimport'] = sefRelToAbs( 'index.php?option=com_bids&amp;task=bulkimport&amp;Itemid='.$Itemid );
		$links['terms'] = sefRelToAbs( 'index.php?option=com_bids&task=terms_and_conditions' );

		$links['tags'] = '';
		if( isset($auction->tags) && count($auction->tags)>0 )
		for($i=0;$i<count($auction->tags);$i++){
		    if (isset($auction->tags[$i]) && $auction->tags[$i]) {
    		    $href=sefRelToAbs('index.php?option=com_bids&amp;task=tags&amp;Itemid='.$Itemid.'&amp;tag='.$auction->tags[$i]);
    		    $links['tags'] .= "<span id='auction_tag'><a href='$href'>".$auction->tags[$i]."</a>".(($i+1<count($auction->tags))?",":"")."</span>";
		    }
		}

		return $links;
    }

	function listAuctions( &$rows,  &$lists, $pageNav, $sfilters = null) {
		global $Itemid, $mosConfig_live_site,  $my,$database,$task,$cb_fieldmap;

        /*@var $pageNav mosPageNav*/
        if(defined("bid_opt_gallery"))
        	$gallery_name = "gl_".bid_opt_gallery;
        else
        	$gallery_name = "gl_lytebox";
        
       	$gallery=new $gallery_name($database,AUCTION_PICTURES);
		//JAIMAY
		$auction=new mosBidOffers($database);
		$gallery->getGalleryForAuction($auction);
        if (count($gallery->imagelist)>0){$gallery->writeJS();}

        /*@var $smarty Smarty*/
        $smarty=HTML_Auction::LoadSmarty();
		?>
		<link href="<?php echo $mosConfig_live_site;?>/components/com_bids/css/<?php echo bid_css;?>" rel="stylesheet" type="text/css">
		<?php if (file_exists(BIDS_COMPONENT_PATH."/templates/bid_template.css")) {?>
    		<link href="<?php echo $mosConfig_live_site;?>/components/com_bids/templates/bid_template.css" rel="stylesheet" type="text/css">
		<?php } ?>
        <?php if (bid_opt_enable_countdown) { ?>
            <script type="text/javascript" src="<?php echo $mosConfig_live_site,"/components/com_bids/js/countdown.js"?>"></script>
        <?php } ?>
		     <script>
				setInterval("window.location.reload();",<?php echo (bid_opt_refresh_minutes * 1000 * 60); ?>);
			</script>
			  <?php
			  $k = 0;
			  $count = count($rows);
			  $tags= new mosBidTags($database);
			  $auction_rows=array();
			  $profile = new mosComProfiler($database);
			  $manaInfo = $profile->getManagerInfo();

			  /*$query = "SELECT a.id FROM jos_bid_auctions AS a WHERE published = 1 ORDER BY a.title +1, a.title ASC";
			  $database->setQuery( $query);
			  $rowsAll = $database->loadObjectList();
			  foreach($rowsAll as $keyAll=>$rowAll){
				  $pagesAll[] =  $rowAll->id;
			  }*/
			  //JAI Default value if no rows are to display
			  $bidStatus = bid_process_pending;			  
			  
			  
			  for($i=0;$i<$count;$i++){
			  	$row = $rows[$i];
				//$page = array_search($row->id,$pagesAll); 
				//@todo changed to next line as per navigation issue 5/20/2012
				//$row->pageId = $i;
				if(!isset($limitstart)) 
					$limitstart = $pageNav->limitstart;
				else
					$limitstart = $limitstart + 1;
				$row->pageId = $limitstart;
				
				if($manaInfo->cb_buyerschoice == "Buyer's Choice") {
					$row->start_date = $manaInfo->cb_startdate;
					$row->end_date  = $manaInfo->cb_enddate;
				}

                /*@var $row mosBidOffers*/
				if(isset($row->params))
	    			$param=new mosParameters($row->params);
				
                $row->tags=$tags->getAuctionTags($row->id);
			  	$links=HTML_Auction::_CreateLinksArray($row);

			  	$gallery->clearImages();
			  	$gallery->addImage($row->picture);
                $row->rownr=$i+1;
				if ($my->id){
					$database->setQuery("select count(*) from #__bid_messages where userid2='$my->id' and id_offer='$row->id' and wasread!=1");
					$row->nr_new_messages = $database->loadResult();

					if ($row->userid==$my->id) $row->is_my_auction=1;
					else  $row->is_my_auction=0;
					if (!$row->is_my_auction && !$row->close_offer) {
	 					$database->setQuery("SELECT count(*) from #__bid_watchlist WHERE userid='$my->id' AND auctionid='$row->id' ");
	 					if($database->loadResult())
	 					     $row->del_from_watchlist=1;
	 					else
        	 			     $row->add_to_watchlist=1;

					}
					if (!$row->is_my_auction && $row->close_offer) {
            			$query = "SELECT b.bid_price from #__bids b where id_offer='$row->id'  and accept = 1 and userid='$my->id'";
            			$database->setQuery($query);
            			$winbid = $database->loadRow();
            			if ($winbid){
            			    $row->winning_bid=$winbid[0];
            			    $row->i_am_winner=1;
            			}
					}
                    if ($task=='mybids'){
                    	$mybid=new mosBids($database);
                    	$t = $mybid->LoadBidForUser($row->id,$my->id);
                    	if (!$t ) $mybid=null;
                    	$row->mybid = $t->bid_price;
                    }
                    if ($row->close_offer && $my->id && ($row->is_my_auction || $row->i_am_winner)){
            		  $query = "select count(id) from #__bid_rate where (voter = '$my->id' and auction_id = '$row->id')";
            		  $database->setQuery($query);
            		  if ($database->loadResult()==0)
            		  	$row->must_rate=1;
                    }
				}
                if ($task!='myauctions'){
						$database->setQuery("SELECT sum(rating)/count(*) from #__bid_rate where user_rated='$row->userid' and rate_type='auctioneer'");
						$row->rating_auctioneer= $database->loadResult();

						$database->setQuery("SELECT sum(rating)/count(*) from #__bid_rate where user_rated='$row->userid' and rate_type='bidder'");
						$row->rating_bidder= $database->loadResult();

						$database->setQuery("SELECT sum(rating)/count(*) from #__bid_rate where user_rated='$row->userid' ");
						$row->rating_overall= $database->loadResult();
                }
    			if ($task=='myauctions' || (isset($row->auction_type) && $row->auction_type==AUCTION_TYPE_PUBLIC && $param->get('max_price','1'))){

    				$database->setQuery("SELECT count(distinct userid) from #__bids where id_offer=$row->id");
    				$row->nr_bidders = $database->loadResult();

					$database->setQuery("SELECT b.bid_price from #__bids b where id_offer=$row->id  and accept = 1");
					$winbid = $database->loadResult();

					if ($winbid) $row->winning_bid=$winbid;
					else{
						$database->setQuery("SELECT max(bid_price) from #__bids where id_offer=$row->id");
						$max_bid = $database->loadResult();
						if ($max_bid) $row->highest_bid=$max_bid;
					}
    			}
    			if ($task=='mywonbids' && bid_opt_allowpaypal){
    			    if (CB_DETECT){
    			        $database->setQuery("select ".$cb_fieldmap['paymentparam']." from #__comprofiler where user_id='$row->userid' ");
    			    }else{
    			        $database->setQuery("select paypalemail from #__bid_users where userid='$row->userid' ");
    			    }
    			    $paypalemail=$database->loadResult();

    			    $row->paypalemail=$paypalemail;
					$row->total_price = $row->winning_bid + $row->shipment_price;
    			}
    			if (bid_opt_enable_countdown){
                    $expiredate=strtotime($row->end_date);
                    $diff=$expiredate-time();
                    if ($diff>0){
                        $s=sprintf("%02d",$diff%60);
                        $diff=intval($diff/60);
                        $m=sprintf("%02d",$diff%60);
                        $diff=intval($diff/60);
                        $h=sprintf("%02d",$diff%24);
                        $d=intval($diff/24);
                        if ($d>0) $row->countdown="$d ".bid_days.", $h:$m:$s";
                        else $row->countdown="$h:$m:$s";
                    }else{
						if($row->close_offer != 1) {
							$row->countdown = bid_process_pending;
						}else{
							$row->countdown = bid_process_complete;
						}
					}
					$row->countdowntype = 0;
    			}
				//JaiStartJ
				$bidStatus = bid_process;
				$expiredate=strtotime($row->start_date);
				$diff=$expiredate-time();
				$row->countdowntype = 1;
				if($diff>0){
					$s=sprintf("%02d",$diff%60);
					$diff=intval($diff/60);
					$m=sprintf("%02d",$diff%60);
					$diff=intval($diff/60);
					$h=sprintf("%02d",$diff%24);
					$d=intval($diff/24);
					if ($d>0) $row->countdown="$d ".bid_days.", $h:$m:$s";
					else $row->countdown="$h:$m:$s";
					$row->countdowntype = 1;
					if($row->close_offer != 1) {
						$bidStatus = bid_process_pending;
					}else{
						$bidStatus = bid_process;
					}
				}

				//JaiEndJ
				//JaiStartI

    			if(strtotime($row->end_date) <= time() || $row->close_offer == 1){
					
					$row->countdowntype = 0;
					if($row->close_offer != 1) {
						$bidStatus = bid_process_pending;
					}else{
						$row->expired=true;
						$bidStatus = bid_process_complete;
					}
					
				}else{
					$row->expired=false;
				}
			    $row->start_date_text=date(bid_opt_date_format,strtotime($row->start_date));
			    if($row->end_date && $row->end_date!='0000-00-00 00:00:00') {
					if(bid_opt_enable_hour){$dateformat=bid_opt_date_format." H:i";}else $dateformat=bid_opt_date_format;
					$row->end_date_text=date($dateformat,strtotime($row->end_date));
				}
            	$u=new mosBidUsers($database);
            	$u->load($row->userid);
            	$bid_user_details = $u->getUserDetails($row->userid);
				$row->auctioneer=$u;
				$row->auctioneer_details=$bid_user_details;
				$row->verified_auctioneer=mosBidACL::_isVerified($row->userid);
    			$row->links=$links;
				$row->thumbnail=$gallery->getThumb(0,0);
		    	$row->gallery=$gallery->getGallery(2);
                $row->description=mosStripslashes($row->description);
				//JaiStartI
				$objBod = new mosBidOffers($database);
				if($row->bid_price!=0) {
					$row->min_increase = $objBod->findBidIncrement($row->bid_price);
					$row->bid_next = $row->bid_price + $row->min_increase;
					$row->current_bidder = $row->biduserid;
				}else {
					$row->bid_next = $row->initial_price;
					$row->current_bidder = "";
				}
				

				//Added for Finding proxy bids
				//auction_id, max(my_bid) as proxyplus_price, outbid 
				$queryProxyPlusBids = "SELECT auction_id, max(my_bid) as proxyplus_price, outbid FROM #__proxyplus_tickets WHERE id  = (SELECT bp.id FROM #__proxyplus_bids AS bp  JOIN #__proxyplus_tickets bpt ON (bp.ticket_id = bpt.id) WHERE auction_id = ".$row->id." AND bpt.userid = $my->id ORDER  by my_bid DESC LIMIT 1)";

				$queryProxyPlusBids = "SELECT auction_id, my_bid as proxyplus_price, outbid  FROM `jos_proxyplus_bids` WHERE auction_id = ".$row->id." and ticket_id in (select id from jos_proxyplus_tickets WHERE userid = ".$my->id.") order by my_bid DESC limit 1";
				$database->setQuery($queryProxyPlusBids);
				$row->proxyplus_price = 0;

				if($rowsProxyPlusBids = $database->loadObjectList()) {
					$row->proxyplus_price	 = $rowsProxyPlusBids[0]->proxyplus_price;
					$row->outbid			 = $rowsProxyPlusBids[0]->outbid;
				 }
				
				/*
    			 * JaiStartK
				*/
				if($row->close_offer == 1){
					if($row->expired && $row->current_bidder != ""){
						$row->bid_next = "SOLD";
					}elseif($row->expired){
						$row->bid_next = "ENDED";
					}
				}elseif(strtotime($row->end_date) <= time()){
					$row->bid_next = bid_process_pending;
				}
				/*
				* JaiEndK
				*/
				 $row->mine = 0;
				 if($row->current_bidder == $my->id) {
					 $row->mine = 1;
				 }
    			$auction_rows[]=$row;
			  }

			  /*
			  * JaiStartJ
			  */
				$optionRowspan = 0;
				if(bid_opt_allow_auctioneer != 1 &&  label_custom_field5 == ""){
					$optionRowspan++;
					if(label_custom_field2 == "" && label_custom_field3 == ""  && label_custom_field4 == "" ){
						$optionRowspan++;
					}
				}
			  /*
			  *JaiEndJ
			  */
			  if(!lic::checkValidity())
				  $smarty->assign('disable_bids',"disabled");
			  $smarty->assign('logged',$my->id);
              $smarty->assign('optionRowspan',$optionRowspan);
              $smarty->assign('lists',$lists);
              $smarty->assign('auction_rows',$auction_rows);
              $link_page = "index.php?option=com_bids&task=$task&Itemid=$Itemid";
              if(isset($sfilters["users"]) && $sfilters["users"]!=""){
				$userid_urlfilter = "&userid=".$sfilters["users"];
				$link_page = "index.php?option=com_bids&task=$task&Itemid=$Itemid".$userid_urlfilter;
              }
              $smarty->assign('paging_pagelinks',$pageNav->writePagesLinks($link_page));
              $smarty->assign('paging_pagecounter',$pageNav->writePagesCounter());
              $smarty->assign('paging_limitbox',$pageNav->getLimitBox($link_page));
			  $smarty->assign('s',$pageNav->limitstart);
              $smarty->assign('sfilters',$sfilters);
              $smarty->assign('filters',HTML_Auction::makeFilterArry($sfilters));
              $smarty->display("t_$task.tpl");
			  ?>
		
        <?php if (bid_opt_enable_countdown) { ?>
            <script type="text/javascript">
                var days='<?php echo  bid_days;?>,';
                var expired='<?php echo $bidStatus;?>';
				var typecount = '<?php echo isset($row->countdowntype)?$row->countdowntype:1; ?>';
                var nrcounters= '<?php echo (count($auction_rows) < 100)?100:count($auction_rows);?>';
			  </script>
        <?php }
	}
	
	 /*JaiStartH */
	function listProxyTicket( &$rows, &$lists, $pageNav, $sfilters = null, $lotsdesired,$userticket) {
		global $Itemid, $mosConfig_live_site,  $my,$database,$task,$cb_fieldmap;
       		
     	/*@var $smarty Smarty*/
        $smarty=HTML_Auction::LoadSmarty();
		$ticketid = mosGetParam($_REQUEST,'ticketid','0');
		$isRun = mosGetParam($_REQUEST,'run','0');
		?>
		<link href="<?php echo $mosConfig_live_site;?>/components/com_bids/css/<?php echo bid_css;?>" rel="stylesheet" type="text/css">
		<link href="<?php echo $mosConfig_live_site;?>/components/com_bids/css/popup.css" rel="stylesheet" type="text/css">
		<?php if (file_exists(BIDS_COMPONENT_PATH."/templates/bid_template.css")) {?>
    		<link href="<?php echo $mosConfig_live_site;?>/components/com_bids/templates/bid_template.css" rel="stylesheet" type="text/css">
		<?php } ?>
            <script type="text/javascript" src="<?php echo $mosConfig_live_site,"/components/com_bids/js/countdown.js"?>"></script>
			 <style>
				.auction_bb_row1{
					background-color: <?php echo bid_oldbid_bgcolor1; ?>
					/*min-height: 120px;*/
					width: 100%;
				}
				.auction_bb_row2{
					background-color: <?php echo bid_oldbid_bgcolor2; ?>
					/*min-height: 120px;*/
					width:100%;
				}
			</style>
			  <?php
			  $k = 0;
			  $count = count($rows);
			  if($ticketid) 
				$countBids = count($rows[$ticketid]);
			  else
				$countBids = 0;

			  $tags= new mosBidTags($database);
			  $auction_rows=array();
			  $bidStatus = bid_process;
				$database->setQuery("select cb_buyerschoice, cb_startdate, cb_enddate, close_offer from #__comprofiler cp JOIN #__users u ON cp.user_id = u.id WHERE gid = 21");
				$managerInfo=$database->loadObjectList();
				$auction->buyersChoiceManager = $managerInfo[0]->cb_buyerschoice;
				if($auction->buyersChoiceManager == "Buyer's Choice") {
					$start_date = $managerInfo[0]->cb_startdate;
					$end_date = $managerInfo[0]->cb_enddate;
					$close_offer = $managerInfo[0]->close_offer;
				}
	   
				$expiredate=strtotime($end_date);
				$diff=$expiredate-time();
				if ($diff>0){
					$s=sprintf("%02d",$diff%60);
					$diff=intval($diff/60);
					$m=sprintf("%02d",$diff%60);
					$diff=intval($diff/60);
					$h=sprintf("%02d",$diff%24);
					$d=intval($diff/24);
					
					if ($d>0) $countdown="$d ".bid_days.", $h:$m:$s";
					else $countdown="$h:$m:$s";
				}elseif(strtotime($end_date)<time()){
					if($close_offer != 1) {
						$bidStatus = bid_process_pending;
					}else{
						$bidStatus = bid_process_complete;
					}
					$countdown=-1;
				}
			  				
			  if($count > 0) {
				  foreach($rows as $key=>$row) {
					  //JaiSTARTP-FIX
					 foreach($row as $key1=>$subrow) {
					  if($subrow->close_offer == 1) {
						if($subrow->bid_price > 0 && $countdown == -1)  {
							$row[$key1]->countdownmess= "SOLD";
						}
						elseif($countdown == -1){
							$row[$key1]->countdownmess = "ENDED";
						}
					  }elseif($countdown == -1){
						  $row[$key1]->countdownmess = "PENDING";
					  }
					 }
					
					$rows[$key]=$row;
					//JAIENDP-FIX
				  }
			  }elseif($task != "editproxyticket") {
				  mosRedirect($mosConfig_live_site."/index.php?option=com_bids&task=editproxyticket");
			  }
			  /*JaiStartH*/
			  $terms=new mosBidMails($database);
			  
			   if ($terms->load('bid_proxyplus_message1') )
				    //JaiStartN-Fix
					$smarty->assign('bid_proxyplus_message1',stripslashes($terms->content));
					//JaiENDN-Fix

			   if ($terms->load('bid_proxyplus_message2') )
				   //JaiStartN-Fix
					$smarty->assign('bid_proxyplus_message2',stripslashes($terms->content));
					//JaiENDN-Fix

			  /*JaiEndH*/
              $smarty->assign('lists',$lists);
              $smarty->assign('auction_rows',$rows);
              $link_page = "index.php?option=com_bids&task=$task&Itemid=$Itemid";
              if(isset($sfilters["users"]) && $sfilters["users"]!="") {
				$userid_urlfilter = "&userid=".$sfilters["users"];
				$link_page = "index.php?option=com_bids&task=$task&Itemid=$Itemid".$userid_urlfilter;
              }
			  if(isset($_COOKIE['info'.$my->id])) {
				$infoStatus = $_COOKIE['info'.$my->id];
			  }else {
				$infoStatus = "more";
			  }
			  if(isset($_COOKIE['infomain'.$my->id])) {
				$infoStatusMain = $_COOKIE['infomain'.$my->id];
			  }else {
				$infoStatusMain = "";
			  }
     		  $fromProxy	= mosGetParam($_REQUEST,'p',0);
			  $amountProxy	= mosGetParam($_REQUEST,'v',0);
			  $sessionproxy = 0;
			  if($fromProxy) {
				$query  = "SELECT a.id as auction_id, b.id as id, id_offer,  b.userid,   initial_price  as bid_price, b.bid_price as bid_next , a.shortdescription as shortdescription, a.title as title   FROM #__bids AS b RIGHT JOIN #__bid_auctions AS a ON a.id = b.id_offer WHERE 	a.id = ".$fromProxy." ORDER BY bid_next DESC LIMIT 1";
				$database->setQuery($query);
				$rowMain = $database->loadObjectList();
				$rowProxy = $rowMain[0];
				if($rowProxy->bid_next != "" && $rowProxy->bid_next != "NULL"){
					$objBod = new mosBidOffers($database);
					$rowProxy->bid_next = $rowProxy->bid_next + $objBod->findBidIncrement($rowProxy->bid_next);
			    }else {
					$rowProxy->bid_next = $rowProxy->bid_price;
				}
			  }elseif(!empty($_SESSION['proxy'])){
					$sessionproxy = count($_SESSION['proxy']);
			  }
			  if(!lic::checkValidity())
				  $smarty->assign('disable_bids',"disabled");
			  $smarty->assign('session',$_SESSION);
			  $smarty->assign('sessionproxy',$sessionproxy);
			  $smarty->assign('fromProxy',$fromProxy);
			  $smarty->assign('amountProxy',$amountProxy);
			  
			  $smarty->assign('rowProxy',isset($rowProxy)?$rowProxy:"");
			  $smarty->assign('start_date',$start_date);
			  $smarty->assign("infoStatusMain",$infoStatusMain);
			  $smarty->assign("infoStatus",$infoStatus);
			  $smarty->assign("loggeduser",$my->id);
			  $smarty->assign("ticketid",$ticketid);
			  $smarty->assign('lots_desired',$lotsdesired);
			  $smarty->assign('userticket',$userticket);
			  $smarty->assign('count',$count);
			  $smarty->assign('countdownmess',isset($countdownmess)?$countdownmess:"");
			  $smarty->assign('countBids',$countBids);
			  $smarty->assign('countdown',$countdown);
			  $smarty->assign('columnsBB',array(1,2,3,4));
			  $smarty->assign('totalcount',$count);
              $smarty->assign('sfilters',$sfilters);
              $smarty->assign('sfilters',$sfilters);
              $smarty->assign('isRun',$isRun);
              $smarty->assign('filters',HTML_Auction::makeFilterArry($sfilters));
              $smarty->display("t_listproxyticket.tpl");
			  if(!lic::checkValidity()) {
			  ?>
			  <script>
				  alert("Proxy Choice bid entry not currently allowed.");
				  $("[ID*=mybid]").attr('disabled','disabled');
			  </script>
			   <?php
			  }
				?>
            <script type="text/javascript">
                var days='<?php echo  bid_days;?>,';
                var expired='<?php echo $bidStatus;?>';
				var typecount = '<?php echo isset($auction->countdowntype)?$auction->countdowntype:1; ?>';
                var nrcounters=1;
            </script>
        <?php 
	}
	 /*JaiStartI */
	function listNextBid( &$rows, $expired=0) {
		global $Itemid, $mosConfig_live_site,  $my,$database,$task,$cb_fieldmap;
       		
     	/*@var $smarty Smarty*/
        $smarty=HTML_Auction::LoadSmarty();
		?>
		<link href="<?php echo $mosConfig_live_site;?>/components/com_bids/css/<?php echo bid_css;?>" rel="stylesheet" type="text/css">
		<?php if (file_exists(BIDS_COMPONENT_PATH."/templates/bid_template.css")) {?>
    		<link href="<?php echo $mosConfig_live_site;?>/components/com_bids/templates/bid_template.css" rel="stylesheet" type="text/css">
		<?php } ?>
            <script type="text/javascript" src="<?php echo $mosConfig_live_site,"/components/com_bids/js/countdown.js"?>"></script>
			 <style>
				.auction_bb_row1{
					background-color: <?php echo bid_oldbid_bgcolor1; ?>
					/*min-height: 120px;*/
					width: 100%;
				}
				.auction_bb_row2{
					background-color: <?php echo bid_oldbid_bgcolor2; ?>
					/*min-height: 120px;*/
					width:100%;
				}
			</style>
			  <?php
				$count= count($rows);
			  if($count > 0) {
				  foreach($rows as $key=>$row) {					
					$auction_rows[$key]=$row;
				  }
			  }
			  $smarty->assign('expired',$expired);
              $smarty->assign('auction_rows',$rows);
              $smarty->display("t_listnextbid.tpl");
			  ?>
            <script type="text/javascript">
                var days='<?php echo  bid_days;?>,';
                var expired='<?php echo $bidStatus;?>';
				var typecount = '1';
                var nrcounters=1;
            </script>
        <?php 
	}
	//JaiENDI
	function listBigBoard( &$rows, &$lists, $pageNav, $sfilters = null) {
		global $Itemid, $mosConfig_live_site,  $my,$database,$task,$cb_fieldmap;
        /*@var $pageNav mosPageNav*/
        if(defined("bid_opt_gallery"))
        	$gallery_name = "gl_".bid_opt_gallery;
        else
        	$gallery_name = "gl_lytebox";
        
       	$gallery=new $gallery_name($database,AUCTION_PICTURES);
        /*@var $smarty Smarty*/
        $smarty=HTML_Auction::LoadSmarty();
		?>
		<link href="<?php echo $mosConfig_live_site;?>/components/com_bids/css/<?php echo bid_css;?>" rel="stylesheet" type="text/css">
		<?php if (file_exists(BIDS_COMPONENT_PATH."/templates/bid_template.css")) {?>
    		<link href="<?php echo $mosConfig_live_site;?>/components/com_bids/templates/bid_template.css" rel="stylesheet" type="text/css">
		<?php } ?>
        <?php if (bid_opt_enable_countdown) { ?>
            <script type="text/javascript" src="<?php echo $mosConfig_live_site,"/components/com_bids/js/countdown.js"?>"></script>
        <?php } ?>
			 <style>
				.auction_bb_row1{
					background-color: <?php echo bid_oldbid_bgcolor1; ?>
					/*min-height: 120px;*/
					width: 100%;
				}
				.auction_bb_row2{
					background-color: <?php echo bid_oldbid_bgcolor2; ?>
					/*min-height: 120px;*/
					width:100%;
				}
			</style>

			  <?php
			  $k = 0;
			  $count = count($rows);
			  $tags= new mosBidTags($database);
			  $auction_rows=array();

			  for($i=0;$i<$count;$i++){
			  	$row = $rows[$i];
				$row->pageId = $i;
                /*@var $row mosBidOffers*/

    			$param=new mosParameters($row->params);

                $row->tags=$tags->getAuctionTags($row->id);
			  	$links=HTML_Auction::_CreateLinksArray($row);

			  	$gallery->clearImages();
			  	$gallery->addImage($row->picture);
                $row->rownr=$i+1;
				if ($my->id){
					$database->setQuery("select count(*) from #__bid_messages where userid2='$my->id' and id_offer='$row->id' and wasread!=1");
					$row->nr_new_messages = $database->loadResult();

					if ($row->userid==$my->id) $row->is_my_auction=1;
					else  $row->is_my_auction=0;
					if (!$row->is_my_auction && !$row->close_offer) {
	 					$database->setQuery("SELECT count(*) from #__bid_watchlist WHERE userid='$my->id' AND auctionid='$row->id' ");
	 					if($database->loadResult())
	 					     $row->del_from_watchlist=1;
	 					else
        	 			     $row->add_to_watchlist=1;

					}
					if (!$row->is_my_auction && $row->close_offer) {
            			$query = "SELECT b.bid_price from #__bids b where id_offer='$row->id'  and accept = 1 and userid='$my->id'";
            			$database->setQuery($query);
            			$winbid = $database->loadRow();
            			if ($winbid){
            			    $row->winning_bid=$winbid[0];
            			    $row->i_am_winner=1;
            			}
					}
                    if ($task=='mybids'){
                    	$mybid=new mosBids($database);
                    	$t = $mybid->LoadBidForUser($row->id,$my->id);
                    	if (!$t ) $mybid=null;
                    	$row->mybid = $t->bid_price;
                    }
                    if ($row->close_offer && $my->id && ($row->is_my_auction || $row->i_am_winner)){
            		  $query = "select count(id) from #__bid_rate where (voter = '$my->id' and auction_id = '$row->id')";
            		  $database->setQuery($query);
            		  if ($database->loadResult()==0)
            		  	$row->must_rate=1;
                    }
				}
                if ($task!='myauctions'){
						$database->setQuery("SELECT sum(rating)/count(*) from #__bid_rate where user_rated='$row->userid' and rate_type='auctioneer'");
						$row->rating_auctioneer= $database->loadResult();

						$database->setQuery("SELECT sum(rating)/count(*) from #__bid_rate where user_rated='$row->userid' and rate_type='bidder'");
						$row->rating_bidder= $database->loadResult();

						$database->setQuery("SELECT sum(rating)/count(*) from #__bid_rate where user_rated='$row->userid' ");
						$row->rating_overall= $database->loadResult();
                }
    			if ($task=='myauctions' || ($row->auction_type==AUCTION_TYPE_PUBLIC && $param->get('max_price','1'))){

    				$database->setQuery("SELECT count(distinct userid) from #__bids where id_offer=$row->id");
    				$row->nr_bidders = $database->loadResult();

					$database->setQuery("SELECT b.bid_price from #__bids b where id_offer=$row->id  and accept = 1");
					$winbid = $database->loadResult();

					if ($winbid) $row->winning_bid=$winbid;
					else{
						$database->setQuery("SELECT max(bid_price) from #__bids where id_offer=$row->id");
						$max_bid = $database->loadResult();
						if ($max_bid) $row->highest_bid=$max_bid;
					}
    			}
    			if ($task=='mywonbids' && bid_opt_allowpaypal){
    			    if (CB_DETECT){
    			        $database->setQuery("select ".$cb_fieldmap['paymentparam']." from #__comprofiler where user_id='$row->userid' ");
    			    }else{
    			        $database->setQuery("select paypalemail from #__bid_users where userid='$row->userid' ");
    			    }
    			    $paypalemail=$database->loadResult();

    			    $row->paypalemail=$paypalemail;
					$row->total_price = $row->winning_bid + $row->shipment_price;
    			}

    			if (bid_opt_enable_countdown){
                    $expiredate=strtotime($row->end_date);
                    $diff=$expiredate-time();
                    if ($diff>0){
                        $s=sprintf("%02d",$diff%60);
                        $diff=intval($diff/60);
                        $m=sprintf("%02d",$diff%60);
                        $diff=intval($diff/60);
                        $h=sprintf("%02d",$diff%24);
                        $d=intval($diff/24);
                        if ($d>0) $row->countdown="$d ".bid_days.", $h:$m:$s";
                        else $row->countdown="$h:$m:$s";
                    }else{$row->countdown=bid_process_complete;}
    			}
    			if(strtotime($row->end_date) <= time()){$row->expired=true;}else $row->expired=false;
			    $row->start_date_text=date(bid_opt_date_format,strtotime($row->start_date));
			    if($row->end_date && $row->end_date!='0000-00-00 00:00:00' ) {
					if(bid_opt_enable_hour){$dateformat=bid_opt_date_format." H:i";}else $dateformat=bid_opt_date_format;
					$row->end_date_text=date($dateformat,strtotime($row->end_date));
				}
            	$u=new mosBidUsers($database);
            	$u->load($row->userid);
            	$bid_user_details = $u->getUserDetails($row->userid);
				$row->auctioneer=$u;
				$row->auctioneer_details=$bid_user_details;
				$row->verified_auctioneer=mosBidACL::_isVerified($row->userid);
    			$row->links=$links;
    			$row->thumbnail=$gallery->getThumb();
                $row->description=mosStripslashes($row->description);
				$row->bid_price = number_format($row->bid_price);
				$row->bid_next = number_format($row->bid_next);
				/*
    			 * JaiStartK
				*/
				$expiredate = strtotime($row->end_date);
				$bidStatus = bid_process;
                $diff		= $expiredate - time();
				$auction->countdowntype = 1;
                if ($diff < 0){
					$auction->countdowntype = 0;
					if($row->close_offer == 1){
						if($row->expired && $row->bid_user != 0){
							$row->bid_next = "SOLD";
						}elseif($row->expired){
							$row->bid_next = "ENDED";
						}
					}else{
						$row->bid_next = "PENDING";
					}
					if($row->close_offer != 1) {
						$bidStatus = bid_process_pending;
						?>
						<script>
								setInterval("window.location.reload();",<?php echo (bid_opt_refresh_minutes * 1000 * 60); ?>);
						</script>
						<?php 
					}else{
						$bidStatus = bid_process_complete;
					}
				}else{
				?>
				<script>
						setInterval("window.location.reload();",<?php echo (bid_opt_refresh_minutes * 1000 * 60); ?>);
				</script>
				<?php 
				}
				/*
				* JaiEndK
				*/
				if($row->bid_modified > date("Y-m-d H:i:s",time() - (60 * bid_opt_refresh_minutes))) {
					$row->bid_new_bgcolor = bid_newbid_bgcolor;
				} else {
					$row->bid_new_bgcolor = "";
				}
    			$auction_rows[]=$row;
			  }

			  $headers = array(1=>bb_header1_text,2=>bb_header2_text,3=>bb_header3_text,4=>bb_header4_text,5=>bb_header5_text,6=>bb_header6_text);
			  $headersnum = array(1=>bb_header1_num,2=>bb_header2_num,3=>bb_header3_num,4=>bb_header4_num,5=>bb_header5_num,6=>bb_header6_num);
			  $headersoption = array(1=>bb_header1_option,2=>bb_header2_option,3=>bb_header3_option,4=>bb_header4_option,5=>bb_header5_option,6=>bb_header6_option);
              $smarty->assign('lists',$lists);
              $smarty->assign('auction_rows',$auction_rows);
              $link_page = "index.php?option=com_bids&task=$task&Itemid=$Itemid";
              if(isset($sfilters["users"]) && $sfilters["users"]!="") {
				$userid_urlfilter = "&userid=".$sfilters["users"];
				$link_page = "index.php?option=com_bids&task=$task&Itemid=$Itemid".$userid_urlfilter;
              }
			  $smarty->assign('headers',$headers);
			  $smarty->assign('headersnum',$headersnum);
			  $smarty->assign('headersoption',$headersoption);
			  $smarty->assign('columnsBB',array(1,2,3,4));
			  $smarty->assign('totalcount',$count);
              $smarty->assign('paging_pagelinks',$pageNav->writePagesLinks($link_page));
              $smarty->assign('paging_pagecounter',$pageNav->writePagesCounter());
              $smarty->assign('paging_limitbox',$pageNav->getLimitBox($link_page));
              $smarty->assign('sfilters',$sfilters);
			  $smarty->assign('task',$task);
              $smarty->assign('filters',HTML_Auction::makeFilterArry($sfilters));
              $smarty->display("t_listbigboard.tpl");
			  ?>
        <?php if (bid_opt_enable_countdown) { ?>
            <script type="text/javascript">
                var days='<?php echo  bid_days;?>,';
                var expired='<?php echo $bidStatus;?>';
				var typecount = '<?php echo $auction->countdowntype; ?>';
                var nrcounters= '<?php echo (count($auction_rows) < 100)?100:count($auction_rows); ?>';
            </script>
        <?php }
	}
	function editAuction( &$auction, $option, $lists,$id=null){
	    /*@var $auction mosBidOffers */
		global $mosConfig_live_site,$Itemid,$my,$task,$database,$mainframe;
		
		$mainframe->loadEditor=true;
    	initEditor();
		if(defined("bid_opt_gallery"))
			$gallery_name = "gl_".bid_opt_gallery;
		else
			$gallery_name = "gl_lytebox";
        $gallery=new $gallery_name($database,AUCTION_PICTURES);
        $gallery->getGalleryForAuction($auction);
		if(JOOMLA_VERSION=="1")
			mosCommonHTML::loadCalendar();
		else {
			?>
			<script type="text/javascript" src="<?php echo JURI::root(true);?>/includes/js/calendar/calendar.js"></script>
			<link rel="stylesheet" href="<?php echo JURI::root(true);?>/media/system/css/calendar-jos.css" type="text/css"  title="green"  media="all" />
			<script type="text/javascript" src="<?php echo JURI::root(true);?>/includes/js/calendar/lang/calendar-en-GB.js"></script>
			<?php
		}
		// used to hide "Reset Hits" when hits = 0
		mosMakeHtmlSafe( $auction, ENT_QUOTES );
        $smarty=HTML_Auction::LoadSmarty();

		$gallery=new $gallery_name($database,AUCTION_PICTURES);
		if ($task=='republish'){
		  $old_auction=new mosBidOffers($database);
		  $old_auction->load($id);
		  $gallery->getGalleryForAuction($old_auction);
		}else
		  $gallery->getGalleryForAuction($auction);
        $auction->imagelist=array();
        if (count($gallery->imagelist)>1){
            for($i=0;$i<count($gallery->imagelist);$i++){
                $obj=new stdClass();
                $obj->thumbnail=$gallery->getThumb($i+1,0);
                $obj->id=$gallery->imagelist_ids[$i+1];
                if ($obj->id) $auction->imagelist[]=$obj;
            }
        }
        $auction->is_my_auction=1;
	    $auction->expired=false;
		if($auction->start_date && $auction->start_date!='0000-00-00 00:00:00' ){$auction->start_date_text=date(bid_opt_date_format,strtotime($auction->start_date));}
		if($auction->end_date && $auction->end_date!='0000-00-00 00:00:00' ) {
			if(bid_opt_enable_hour){$dateformat=bid_opt_date_format." H:i";}else $dateformat=bid_opt_date_format;
			$auction->end_date_text=date($dateformat,strtotime($auction->end_date));
		}
		$auction->links=HTML_Auction::_CreateLinksArray($auction);
    	$auction->thumbnail=$gallery->getThumb(0,0);
    	$auction->gallery=$gallery->getGallery();
		/*JaiStartE */
		$database->setQuery("select cb_buyerschoice, cb_startdate, cb_enddate, cb_shipment, cb_payment from #__comprofiler cp JOIN #__users u ON cp.user_id = u.id WHERE gid = 21");
		//TODO Jai
		//$database->setQuery("select cb_buyerschoice, cb_startdate, cb_enddate, cb_shipment, cb_payment from #__comprofiler where user_id=$my->id");
		$managerInfo=$database->loadObjectList();
		$auction->buyersChoiceManager = $managerInfo[0]->cb_buyerschoice;
		if($auction->buyersChoiceManager == "Buyer's Choice") {
			$auction->start_date = $managerInfo[0]->cb_startdate;
			$auction->end_date  = $managerInfo[0]->cb_enddate;
		}
		$auction->startDateManager = $managerInfo[0]->cb_startdate;
		$auction->endDateManager = $managerInfo[0]->cb_enddate;
		$auction->shipment_info = $managerInfo[0]->cb_shipment;
		if($auction->payment){
    		$database->setQuery("select name from #__bid_payment where id=$auction->payment");
    		$auction->payment_name=$database->loadResult();
    	}
		$auction->payment_name = $managerInfo[0]->cb_payment;
		/* JaiEndE */
    	/* ==> Fixed in Auction Factory 1.5.8 */
    	if($auction->currency){
	    	$database->setQuery("select name from #__bid_currency where id='$auction->currency'");
	    	$auction->currency_name=$database->loadResult();
    	}
    	
    	/* <== Fixed in Auction Factory 1.5.8 */
        $auction->username=$my->username;
        $auction->description=mosStripslashes($auction->description);
        $auction->shortdescription=mosStripslashes($auction->shortdescription);
		/* JaiStartD */
		if(strtotime($auction->start_date) > time()) {
			$auction->isValidateDate = 1; 
		}else {
			$auction->isValidateDate = 0; 
		}
		/* JaiEndD */
        if($auction->min_increase=="")
			$auction->min_increase = bid_opt_min_increase;
        $smarty->assign('old_id',$id);
        $smarty->assign('lists',$lists);
        $smarty->assign('auction',$auction);
        $smarty->assign('parameters',$auction->ParamsToArray());
        ?>
        <script type="text/javascript">
        function getEditor(){ <?php getEditorContents( 'description', 'description' ); ?> }
        </script>
		<form action="index.php" method="post" name="auctionForm" enctype="multipart/form-data" onsubmit="getEditor();return validateForm(this);">
		<?php if($task=="republish"){?>
			<input type="hidden" name="oldid" value="<?php echo $id; ?>" />
			<input type="hidden" name="oldppic" value="<?php echo $row->picture;?>" />
		<?php }?>
        <input type="hidden" name="id" value="<?php echo $auction->id; ?>" />
        <input type="hidden" name="option" value="<?php echo $option; ?>" />
        <input type="hidden" name="task" value="saveauction" />
        <input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
		<input type="hidden" name="p" value="<?php echo mosGetParam($_GET,'p',''); ?>">
        <?php
            $smarty->display('t_editauction.tpl');
        ?>
	    </form>
		 <?php
	}

	function makeFilterArry($filters){
		global $mosConfig_live_site,$database;
        $searchstrings=array();
		if(isset($filters['keyword'])&& $filters['keyword']){
    	   $searchstrings[search_keyword_text]=$filters['keyword'];
		}

   		if(isset($filters['users'])&& $filters['users']){
    	   $database->setQuery("select username from #__users where id in (".$filters['users'].") and block=0");
    	   $results = $database->loadResultArray();
    	   if(is_array($results)){
        	   $usernames=join(',',$results);
        	   $searchstrings[search_usernames_text]=$usernames;
    	   }
    	}
   		if(isset($filters['cat'])&& $filters['cat']){
    	   $database->setQuery("select catname from #__bid_categories where id='".$filters['cat']."'");
    	   $catname = $database->loadResult();
    	   $searchstrings[bid_category]=$catname;
    	}
		if(isset($filters['sdate']) && $filters['sdate']){
			if(date('Y',strtotime($filters['sdate']))>'1970'){
			     $searchstrings[bid_start_date]= date(bid_opt_date_format,strtotime($filters['sdate']));
			}
		}
		if(isset($filters['bdate']) && $filters['bdate']){
			if(date('Y',strtotime($filters['bdate']))>'1970'){
			     $searchstrings[bid_end_date]= date(bid_opt_date_format,strtotime($filters['bdate']));
			}
		}
		if (isset($filters['tag'])&& $filters['tag']){
		  $searchstrings[bid_tags]= $filters['tag'];
		}
		if (isset($filters['auction_nr']) && $filters['auction_nr']){
		  $searchstrings[bid_auction_number]= $filters['auction_nr'];
		}
		if (isset($filters['inarch']) && $filters['inarch']){
		  if($filters['inarch']==1)
		      $searchstrings[bid_filter_archive]= bid_yes;
		  else
		      $searchstrings[bid_filter_watchlist]= bid_yes;
		}
		if (isset($filters['filter_rated'])&&$filters['filter_rated']){
		    $searchstrings[bid_filter_rated]= bid_unrated;
		}
		if (isset($filters['country'])&&$filters['country']){
			if(CB_DETECT)
				$searchstrings[bid_country]= $filters['country'];
  			else{
				$database->setQuery("select name from #__bid_country where id='".$filters['country']."'");
				$searchstrings[bid_country] = $database->loadResult();
  			}
		}
		if (isset($filters['city'])&&$filters['city']){
		    $searchstrings[bid_city]= $filters['city'];
		}
		return $searchstrings;
	}
	function Payment_Thank_You()
	{
	    global $database;
        $smarty=HTML_Auction::LoadSmarty();

        $itemname=mosGetParam($_REQUEST,'itemname','');
        $itemamount=mosGetParam($_REQUEST,'itemamount',1);
        /*@var $payment mosBidsPayment*/
        $payment=mosBidsPayment::getInstance();
        $price_class=$payment->getPaymentItem($itemname);
        $item_description=$price_class->getDescription();

        $smarty->assign('itemname',$itemname);
        $smarty->assign('itemamount',$itemamount);
        $smarty->assign('itemdescription',$item_description);
        $smarty->display('t_payment_return.tpl');
	}
    function Payment_Cancel()
    {
        $smarty=HTML_Auction::LoadSmarty();
        $smarty->display('t_payment_cancel.tpl');
    }
    function showChoosePayment(&$pay_arr)
    {
        for($i=0;$i<count($pay_arr);$i++){
            $pay_arr[$i]->thumbnail=$pay_arr[$i]->getLogo();
        }

        $smarty=HTML_Auction::LoadSmarty();
        $smarty->assign('payment_systems',$pay_arr);
        $smarty->assign('itemname',$_REQUEST['itemname']);
        $smarty->assign('return_url',$_REQUEST['return_url']);

        $smarty->display('t_payment_choose_gateway.tpl');

    }
	
}
// ============================== USER DETAILS ==================================
class HTML_UserDetails {
    function viewUser(&$user,&$lists)
    {
        $smarty=HTML_Auction::LoadSmarty();
		$smarty->assign('user',$user);
		$smarty->assign('lists',$lists);
		$smarty->assign('ratings',$lists['ratings']);
        $smarty->display("t_userdetails.tpl");
    }
    function editUser(&$user,&$lists)
    {
        $smarty=HTML_Auction::LoadSmarty();
		$smarty->assign('user',$user);
		$smarty->assign('lists',$lists);
		$smarty->assign('ratings',$lists['ratings']);
		$smarty->assign('credits',$lists['credits']);
        $smarty->display("t_myuserdetails.tpl");

    }
	function myRatings(&$user,&$myratings){
        $smarty=HTML_Auction::LoadSmarty();
		$smarty->assign('user',$user);
		$smarty->assign('ratings',$myratings);
        $smarty->display("t_myratings.tpl");
	}
}
?>