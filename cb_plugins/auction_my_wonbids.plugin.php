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
class getmywonbidsTab extends cbTabHandler {

	function getmywonbidsTab() {
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




		$params = $this->params;

    	$where.=" where a.userid='$my->id' and accept=1";


        $query="select a.id as parent_message,a.bid_price,a.modified as bid_date,
            b.newmessages as bid_newmessages,
            a.accept as accept, a.cancel as cancel,
             b.*,c.name as currency_name, u.name as name, u.username,
             r.rating
            from #__bids a
            left join #__bid_auctions b on a.id_offer=b.id
            left join #__bid_currency c on b.currency=c.id
            left join #__users u on u.id=b.userid
    	    left join #__bid_rate r on r.voter='$my->id' and r.auction_id=b.id
            $where  order by id desc";

		$database->setQuery($query);
		$nrbids = $database->loadObjectList();


		$total = count($nrbids);

        $rtab=mosGetParam($_REQUEST,'tab','0');
        if ($rtab==$tab->tabid){
    		$limitstart = mosGetParam($_REQUEST,'limitstart','0');
        }else{
            $limitstart=0;
        }

   		$pageNav = new mosPageNav( $total, $limitstart, ITEMS_PER_PAGE);
		$database->setQuery($query,$pageNav->limitstart, $pageNav->limit);
		$mybids = $database->loadObjectList();

		$return .= "\t\t<div>\n";
		$return .="<table width='100%'>";
		$return .='<form name="topForm'.$tab->tabid.'" action="index.php" method="post">';
		$return .="<input type='hidden' name='option' value='com_comprofiler' />";
		$return .="<input type='hidden' name='task' value='userProfile' />";
		$return .="<input type='hidden' name='user' value='".$user->user_id."' />";
		$return .="<input type='hidden' name='tab' value='".$tab->tabid."' />";
		$return .="<input type='hidden' name='act' value='' />";


		if($mybids) {
			$return	.= '<tr>';
			$return .= '<th class="list_ratings_header">'.bid_bid_title.'</th>';
			$return .= '<th class="list_ratings_header">'.bid_bid_auctioneer.'</th>';
			$return .= '<th class="list_ratings_header">'.bid_end_date.'</th>';
			$return .= '<th class="list_ratings_header">'.bid_bid_price.'</th>';
			$return .= '<th class="list_ratings_header">'.bid_rate.'</th>';
			$return .= '</tr>';
			$k=0;
			foreach ($mybids as $mb){
            $link_view_details = sefRelToAbs("index.php?option=com_bids&task=ViewDetails&id=$mb->userid&Itemid=$Itemid");
			$link_view_auction = sefRelToAbs("index.php?option=com_bids&task=viewbids&id=$mb->id&Itemid=$Itemid");
			//var_dump($myratings);exit;
			 $return .='<tr class="mywatch'.$k.'">';
			 $return .='<td>';
			 $return .= '<a href="'.$link_view_auction.'">'.mosStripslashes($mb->title).'</a>';
			 $return .='</td>';
			 $return .='<td>';
			 $return .= '<a href="'.$link_view_details.'">'.$mb->username.'</a>';
			 $return .='</td>';
			 $return .='<td>';
			 $return .= date(bid_opt_date_format,strtotime($mb->closed_date));
			 $return .='</td>';
			 $return .='<td>';
			 $return .= $mb->bid_price." ".$mb->currency_name;
			 $return .='</td>';
			 $return .='<td>';
			 if ($mb->rating){
    			 $return .= $mb->rating;

			 }else{
    			 $return .= '<a href="'.$link_view_auction.'#bid_list">'.bid_rate.'</a>';

			 }
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