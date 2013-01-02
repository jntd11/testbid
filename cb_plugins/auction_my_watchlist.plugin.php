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

require_once( $mosConfig_absolute_path. '/includes/pageNavigation.php' );
define(ITEMS_PER_PAGE, '5');
class getmywatchlistTab extends cbTabHandler {

	function getmywatchlistTab() {
		$this->cbTabHandler();
	}

	function getDisplayTab($tab,$user,$ui){
		global $database,$mosConfig_absolute_path,$mosConfig_live_site,$my,$Itemid;

		if($my->id!=$user->user_id){
			return null;
		}

		if(!file_exists($mosConfig_absolute_path."/components/com_bids/bids.php")){
			  return "<div>You must First install <a href='http://www.thefactory.ro/shop/joomla-components/auction-factory.html'> Auction Factory </a></div>";
		}
		require_once($mosConfig_absolute_path."/components/com_bids/options.php");
		require_once($mosConfig_absolute_path."/components/com_bids/bids.class.php");

		$bid_user=new mosBidUsers($database);
		if ($my->id) $bid_user->load($my->id);

        if (file_exists(BIDS_COMPONENT_PATH.'/lang/'.bid_opt_language))
            require_once(BIDS_COMPONENT_PATH.'/lang/'.bid_opt_language);
        else
            require_once(BIDS_COMPONENT_PATH.'/lang/default.php');


		$limitstart = mosGetParam($_REQUEST,'limitstart','0');

		$params = $this->params;

		//$return = "<b>test</b>";
		$query = "SELECT u.username,b.*, m.name as payment_name,c.name as currency_name
			FROM #__bid_auctions b
			left join #__bid_payment m on b.payment=m.id
			left join #__bid_currency c on b.currency=c.id
			left join #__bid_watchlist w on '$user->user_id'=w.userid
			left join #__users u on b.userid = u.id
			where   b.id=w.auctionid order by id desc";

		$database->setQuery($query);
		//echo $database->getQuery();
		$nrwatches = $database->loadObjectList();


		$total = count($nrwatches);

        $rtab=mosGetParam($_REQUEST,'tab','0');
        if ($rtab==$tab->tabid){
    		$limitstart = mosGetParam($_REQUEST,'limitstart','0');
        }else{
            $limitstart=0;
        }

   		$pageNav = new mosPageNav( $total, $limitstart, ITEMS_PER_PAGE);

		$database->setQuery($query,$pageNav->limitstart, $pageNav->limit);
		$mywatches = $database->loadObjectList();

		$return .= "\t\t<div>\n";
		$return .="<table width='100%'>";
		$return .='<form name="topForm'.$tab->tabid.'" action="index.php" method="post">';
		$return .="<input type='hidden' name='option' value='com_comprofiler' />";
		$return .="<input type='hidden' name='task' value='userProfile' />";
		$return .="<input type='hidden' name='user' value='".$user->user_id."' />";
		$return .="<input type='hidden' name='tab' value='".$tab->tabid."' />";
		$return .="<input type='hidden' name='act' value='' />";


		if($mywatches) {
			$return	.= '<tr>';
			$return .= '<th class="list_ratings_header">'.bid_bid_title.'</th>';
			$return .= '<th class="list_ratings_header">'.bid_bid_auctioneer.'</th>';
			$return .= '<th class="list_ratings_header">'.bid_start_date.'</th>';
			$return .= '<th class="list_ratings_header">'.bid_end_date.'</th>';
			$return .= '<th class="list_ratings_header">'.bid_initial_price.'</th>';
			$return .= '</tr>';
			$k=0;
			foreach ($mywatches as $mw){
            $link_view_details = sefRelToAbs("index.php?option=com_bids&task=ViewDetails&id=$mw->userid&Itemid=$Itemid");
			$link_view_auction = sefRelToAbs("index.php?option=com_bids&task=viewbids&id=$mw->id&Itemid=$Itemid");
			//var_dump($myratings);exit;
			 $return .='<tr class="mywatch'.$k.'">';
			 $return .='<td>';
			 $return .= '<a href="'.$link_view_auction.'">'.mosStripslashes($mw->title).'</a>';
			 $return .='</td>';
			 $return .='<td>';
			 $return .= '<a href="'.$link_view_details.'">'.$mw->username.'</a>';
			 $return .='</td>';
			 $return .='<td>';
			 $return .= date(bid_opt_date_format,strtotime($mw->start_date));
			 $return .='</td>';
			 $return .='<td>';
			 $return .= date(bid_opt_date_format,strtotime($mw->end_date));
			 $return .='</td>';
			 $return .='<td>';
			 $return .= $mw->initial_price." ".$mw->currency_name;
			 $return .='</td>';
			 $return .= "</tr>";
			 $k=1-$k;

			 }
		} else {

			$return .=	"".bid_no_items."";

		}
		$pageslinks = "index.php?option=com_comprofiler&task=userProfile&user=$user->user_id&tab=$tab->tabid";

		$return .= "<tr height='20px'>";
		$return .= "<td colspan='3' align='center'>";
		$return .= "</td>";
		$return .= "</tr>";
		$return .= "<tr>";
		$return .= "<td colspan='2' align='center'>";
		$return .= $pageNav->writePagesLinks($pageslinks);
		$return .= "</td>";
		$return .= "</tr>";
		$return .= "</form>";
		$return .= "</table>";
		$return .= "</div>";

		//$return ="";
		return $return;
	}
}
?>