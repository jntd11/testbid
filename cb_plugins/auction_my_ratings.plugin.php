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
class getmyratingsTab extends cbTabHandler {

	function getmyratingsTab() {
		$this->cbTabHandler();
	}

	function getDisplayTab($tab,$user,$ui){
		global $database,$mosConfig_absolute_path,$mosConfig_live_site,$my,$Itemid;

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


        $rtab=mosGetParam($_REQUEST,'tab','0');
        if ($rtab==$tab->tabid){
    		$limitstart = mosGetParam($_REQUEST,'limitstart','0');
        }else{
            $limitstart=0;
        }

   		$pageNav = new mosPageNav( $total, $limitstart, ITEMS_PER_PAGE);

   		$query = "select r.*,us.username,a.title from #__bid_rate r
			  left join #__users us on r.voter = us.id
			  left join #__bid_auctions a on r.auction_id = a.id
		      where r.user_rated = '$user->user_id' ";
		$database->setQuery($query,$pageNav->limitstart,$pageNav->limit);
		$myratings = $database->loadObjectList();

   		$query = "select count(*) from #__bid_rate r
			  left join #__users us on r.voter = us.id
			  left join #__bid_auctions a on r.auction_id = a.id
		      where r.user_rated = '$user->user_id' ";
		$database->setQuery($query);
		$total= $database->loadResult();
        $pageNav->total=$total;

		$query = "select sum(rating) from #__bid_rate r
			  left join #__users us on r.voter = us.id
			  left join #__bid_auctions a on r.auction_id = a.id
		      where r.user_rated = '$user->user_id' ";
		$database->setQuery($query);
		$all_ratings= $database->loadResult();

		$query = "select avg(rating) from #__bid_rate r
			  left join #__users us on r.voter = us.id
			  left join #__bid_auctions a on r.auction_id = a.id
		      where r.user_rated = '$user->user_id' and rate_type='auctioneer' ";
		$database->setQuery($query);
		$seller_ratings= intval($database->loadResult());

		$query = "select avg(rating) from #__bid_rate r
			  left join #__users us on r.voter = us.id
			  left join #__bid_auctions a on r.auction_id = a.id
		      where r.user_rated = '$user->user_id' and rate_type!='auctioneer' ";
		$database->setQuery($query);

		$buyer_ratings= intval($database->loadResult());

		$rating=($total>0)?intval($all_ratings/$total):0;


		$return .= "<style type='text/css'>
						#auction_star{height:12px;margin:0px;padding:0px;}

					</style>";
		$return.='<script type="text/javascript" src="'.$mosConfig_live_site.'/components/com_bids/js/ratings.js"></script>';
        $return .="<span class='auction_my_rating'>".bid_overall_rating.": <span id='rating_user' rating='".$rating."'></span>&nbsp;($rating/10)</span><br>";
        $return .="<span class='auction_my_rating'>".bid_rating_auctioneer.": <span id='rating_user' rating='".$seller_ratings."'></span>&nbsp;($seller_ratings/10)</span><br>";
        $return .="<span class='auction_my_rating'>".bid_rating_bidder.": <span id='rating_user' rating='".$buyer_ratings."'></span>&nbsp;($buyer_ratings/10)</span>";
		$return .='<form name="topForm'.$tab->tabid.'" action="index.php" method="post">';
		$return .="<table width='100%'>";
		$return .="<input type='hidden' name='option' value='com_comprofiler' />";
		$return .="<input type='hidden' name='task' value='userProfile' />";
		$return .="<input type='hidden' name='user' value='".$user->user_id."' />";
		$return .="<input type='hidden' name='tab' value='".$tab->tabid."' />";
		$return .="<input type='hidden' name='act' value='' />";


		if($myratings) {
			$return	.= '<tr>';
 			$return .='<td colspan=3><hr></td>';
			$return .= '</tr>';
			$k=0;
			foreach ($myratings as $mr){
			 	$link_view_details = sefRelToAbs("index.php?option=com_bids&task=ViewDetails&id=$mr->voter&Itemid=$Itemid");
			 	$link_view_bids = sefRelToAbs("index.php?option=com_bids&task=viewbids&id=$mr->auction_id&Itemid=$Itemid");
			 	if ($mr->rate_type=='auctioneer'){$utype=bid_buyer;}else{$utype=bid_seller;}

    			 $return .="<tr class='myrating".($k)."'>";
    			 $return .="<td colspan=3>";
    			 $return .="<a href='".$link_view_bids."'>".mosStripslashes($mr->title)."</a>";
    			 $return .="</td>";
    			 $return .= "</tr>";
    			 $return .='<tr class="myrating'.$k.'">';
    			 $return .='<td>';
    			 $return .= "<a href='".$link_view_details."'>$mr->username </a>(".$utype.")";
    			 $return .= "</td>";
    			 $return .="<td width='20%' colspan=2 nowrap>";
		         $return .="<span id='rating_user' rating='".$mr->rating."'></span>";
    			 $return .="</td>";
    			 $return .="</tr>";
    			 $return .="<tr class='myrating".($k)."'>";
    			 $return .="<td colspan='3' style='border-bottom:1px solid black'>";
    			 $return .= $mr->message;
    			 $return .= "</td>";
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

		$return .= "</table>";
		$return .= "</form>";

		$return .= "
				<script>
				var alreadyrunflag=0
				if (document.addEventListener)
					document.addEventListener('DOMContentLoaded', function(){alreadyrunflag=1; FillRatings();}, false)
				else if (document.all && !window.opera){
					document.write('<script type=\"text/javascript\" id=\"contentloadtag\" defer=\"defer\" src=\"javascript:void(0)\"><\/script>')

				var contentloadtag=document.getElementById('contentloadtag')
					contentloadtag.onreadystatechange=function(){
					if (this.readyState=='complete'){
						alreadyrunflag=1
						FillRatings();
					}
					}
				}
				window.onload=function(){
					setTimeout('if (!alreadyrunflag) {FillRatings();}', 0)
				}
				</script>
		";

		return $return;


	}

}


?>