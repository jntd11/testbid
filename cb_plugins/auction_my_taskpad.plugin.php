<?php
/**
 * @package AuctionsFactory
 * @version 1.5.2
 * @copyright www.thefactory.ro
 * @license: commercial
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

global $mosConfig_absolute_path;

class myTaskPad extends cbTabHandler {

	function getmywatchlistTab() {
		$this->cbTabHandler();
	}

	function getDisplayTab($tab,$user,$ui){
		global $database,$mosConfig_absolute_path,$mosConfig_live_site,$my,$Itemid,$cb_fieldmap;

		if($my->id!=$user->user_id || !$my->id){
			return null;
		}

		if(!file_exists($mosConfig_absolute_path."/components/com_bids/bids.php")){
			  return "<div>You must First install <a href='http://www.thefactory.ro/shop/joomla-components/auction-factory.html'> Auction Factory </a></div>";
		}
		require_once($mosConfig_absolute_path."/components/com_bids/options.php");
		require_once($mosConfig_absolute_path."/components/com_bids/bids.class.php");
		require_once($mosConfig_absolute_path."/components/com_bids/bids.acl.php");

		$database->setQuery("SELECT enabled FROM #__bid_pricing WHERE itemname = 'comission'");
		$database->loadObject($pricing_plugin);


		$database->setQuery("select field,cb_field from #__bid_cbfields");
    	$r=$database->loadAssocList();
    	for($i=0;$i<count($r);$i++){
    	    $cb_fieldmap[$r[$i]['field']]=$r[$i]['cb_field'];
    	}

		$tasklist=array(
		  'newauction'=>'f_newauction.png',
		  'myauctions'=>'f_myauctions.png ',
		  'mybids'=>'f_mybids.png',
		  'mywonbds'=>'f_mywonbids.png',
		  'mywatchlist'=>'f_mywatchlist.png',
		  'listcats'=>'f_listcats.png',
		  'listauctions'=>'f_listauctions.png',
		  'search'=>'f_search.png'
		);
        $bid_acl=new mosBidACL();
        if (bid_opt_enable_acl){
    		$isSeller=$bid_acl->isSeller();
    		$isBidder=$bid_acl->isBidder();
        }else{
    		$isSeller=true;
    		$isBidder=true;
        }
		$isVerified=$bid_acl->isVerified();

        if (file_exists(BIDS_COMPONENT_PATH.'/lang/'.bid_opt_language))
            require_once(BIDS_COMPONENT_PATH.'/lang/'.bid_opt_language);
        else
            require_once(BIDS_COMPONENT_PATH.'/lang/default.php');

		$params = $this->params;
		$query = "SELECT *
			FROM #__bid_pricing p
			left join #__bid_credits b on p.itemname=b.credittype
			where   b.userid='$my->id' and enabled=1";

		$database->setQuery($query);
		$credits = $database->loadObjectList();

		$query = "SELECT * FROM #__bid_balance where auctioneer='$my->id'";
		$database->setQuery($query);
		$comissions =null;
		$comissions = $database->loadObjectList();

		$return .= "\t\t<div>\n";
   		$return .="<table width='100%'>";
		$return .= "\t\t<tr><td colspan=4><div>\n";
		if ($isSeller) {
    		$return .= "\t\t<img style='margin-right:50px;' src='".BIDS_COMPONENT."/images/f_can_sell1.gif' border=0>\n";
		}else {
    		$return .= "\t\t<img style='margin-right:50px;' src='".BIDS_COMPONENT."/images/f_can_sell2.gif' border=0>\n";
		}
		if ($isBidder) {
    		$return .= "\t\t<img style='margin-right:60px;' src='".BIDS_COMPONENT."/images/f_can_buy1.gif' border=0>\n";
		}else {
    		$return .= "\t\t<img style='margin-right:60px;' src='".BIDS_COMPONENT."/images/f_can_buy2.gif' border=0>\n";
		}
		if ($isVerified) {
    		$return .= "\t\t<img style='margin-right:50px;' src='".BIDS_COMPONENT."/images/verified_1.gif' border=0>\n";
		}else {
    		$return .= "\t\t<img style='margin-right:50px;' src='".BIDS_COMPONENT."/images/verified_0.gif' border=0>\n";
		}
		$return .= "\t\t</div></td></tr>\n";
		$return .= "\t\t<tr><td colspan=4>\n";
   		$return .= "\t\t<div style='width:95px;float:left;'>".bid_acl_group_seller.":".($isSeller?bid_yes:bid_no)."</div>\n";
   		$return .= "\t\t<div style='width:95px;float:left;'>".bid_acl_group_bidder.":".($isBidder?bid_yes:bid_no)."</div>\n";
   		$return .= "\t\t<div style='width:95px;float:left;'>".bid_user_verified.":".($isVerified?bid_yes:bid_no)."</div>\n";
		$return .= "\t\t</div></td></tr>\n";
		$return .= "\t\t<tr><td colspan=4>\n";

		$keys=array_keys($tasklist);

   		$return .= "<table width='100%'><tr>";
   		for ($i=0;$i<count($keys)/2;$i++){
   		    $f_task=sefRelToAbs("index.php?option=com_bids&task=".$keys[$i]);
   		    $return .= "<td width='100'><a href='$f_task'><img src='".BIDS_COMPONENT."/images/".$tasklist[$keys[$i]]."' border=0></a></td>";
   		}
   		$return .= "</tr><tr>";
   		for ($i=count($keys)/2;$i<count($keys);$i++){
   		    $f_task=sefRelToAbs("index.php?option=com_bids&task=".$keys[$i]);
   		    $return .= "<td width='100'><a href='$f_task'><img src='".BIDS_COMPONENT."/images/".$tasklist[$keys[$i]]."' border=0></a></td>";
   		}
		$return .= "\t\t</tr></table></td></tr>\n";

		if(count($credits)>0) {
			$return	.= '<tr>';
			$return .= '<th class="list_ratings_header">'.bid_payment_item.'</th>';
			$return .= '<th class="list_ratings_header">'.bid_payment_credits.'</th>';
			$return .= '<th class="list_ratings_header">'.bid_payment_item_price.'</th>';
			$return .= '<th class="list_ratings_header">&nbsp;</th>';
			$return .= '</tr>';
			$k=0;
			foreach ($credits as $credit){
                $link_purchase_item = sefRelToAbs("index.php?option=com_bids&task=$credit->task_pay");
                if (defined('bid_payment_'.$credit->itemname))
                    $itemname=constant('bid_payment_'.$credit->itemname);
                else $itemname=$credit->itemname;

    			 $return .='<tr class="mywatch'.$k.'">';
    			 $return .='<td>';
    			 $return .= $itemname;
    			 $return .='</td>';
    			 $return .='<td>';
    			 $return .= number_format($credit->amount,0);
    			 $return .='</td>';
    			 $return .='<td>';
    			 $return .= number_format($credit->price,2)." ".$credit->currency;
    			 $return .='</td>';
    			 $return .='<td>';
    			 $return .= '<a href="'.$link_purchase_item.'">'.bid_payment_purchase.'</a>';
    			 $return .='</td>';
    			 $return .= "</tr>";
    			 $k=1-$k;

			 }
		}
		if($comissions && $pricing_plugin->enabled) {
			$return	.= '<tr>';
			$return .= '<th class="list_ratings_header" colspan=4><hr></th>';
			$return .= '</tr>';
			$return	.= '<tr>';
			$return .= '<th class="list_ratings_header" colspan=4>'.bid_comissions_amount.': ';
			foreach($comissions as $c)
				if($c->amount>0)
					$return .= $c->amount." ".$c->currency_name." ";
			$return .= '&nbsp <a href="'.$mosConfig_live_site.'/index.php?option=com_bids&task=pay_comission">'.bid_pay_comission.'</a></th>';
			$return .= '</tr>';
			$return	.= '<tr>';
			$return .= '<th class="list_ratings_header" colspan=4>'.bid_last_paymentdate.': '.$comissions->last_pay.'</th>';
			$return .= '</tr>';
		}
		$return .= "</table>";
		$return .= "</div>";

		//$return ="";
		return $return;
	}
}
?>