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
class getmyauctionsTab extends cbTabHandler {

	function getmyauctionsTab() {
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
		$query = "SELECT u.username,b.*, m.name as payment_name,c.name as currency_name
			FROM #__bid_auctions b
			left join #__bid_payment m on b.payment=m.id
			left join #__bid_currency c on b.currency=c.id
			left join #__users u on b.userid=u.id

			where userid = '$user->user_id' order by id desc";

		$database->setQuery($query);
		$nrauctions = $database->loadObjectList();


		$total = count($nrauctions);

        $rtab=mosGetParam($_REQUEST,'tab','0');
        if ($rtab==$tab->tabid){
    		$limitstart = mosGetParam($_REQUEST,'limitstart','0');
        }else{
            $limitstart=0;
        }

   		$pageNav = new mosPageNav( $total, $limitstart, ITEMS_PER_PAGE);
		$database->setQuery($query,$pageNav->limitstart, $pageNav->limit);
		$myauctions = $database->loadObjectList();


		$return .= "\t\t<div>\n";
		$return .="<table width='100%'>";
		$return .='<form name="topForm'.$tab->tabid.'" action="index.php" method="post">';
		$return .="<input type='hidden' name='option' value='com_comprofiler' />";
		$return .="<input type='hidden' name='task' value='userProfile' />";
		$return .="<input type='hidden' name='user' value='".$user->user_id."' />";
		$return .="<input type='hidden' name='tab' value='".$tab->tabid."' />";
		$return .="<input type='hidden' name='act' value='' />";


		if($myauctions) {


			$return	.= '<tr>';
			$return .= '<th class="list_ratings_header" width="15%">'.bid_bid_status.'</th>';
			$return .= '<th class="list_ratings_header" width="*%">'.bid_bid_title.'</th>';
			$return .= '<th class="list_ratings_header" width="15%">'.bid_start_date.'</th>';
			$return .= '<th class="list_ratings_header" width="15%">'.bid_end_date.'</th>';
			$return .= '<th class="list_ratings_header" width="10%">'.bid_initial_price.'</th>';

			if($my->id==$user->user_id){
				$return .= '<th class="list_ratings_header" width="10%">'.bid_last_bid.'</th>';
			}

			$return .= '</tr>';
			$k=0;
			foreach ($myauctions as $ma){
			    /*@var $ma mosBidOffers*/
    			if($my->id == $user->user_id){
    				$query = "SELECT bid_price from #__bids where id_offer='$ma->id' order by id desc limit 1";
    				$database->setQuery($query);
    				$last_bid = $database->loadResult();
    			}else{
    			    if (!$ma->published || $ma->close_by_admin) continue; //hide unpublished or Banned auctions
    			}
    	       	$link_view_details = sefRelToAbs("index.php?option=com_comprofiler&task=userprofile&user=$ma->userid&Itemid=$Itemid");
    			$link_view_auction = sefRelToAbs("index.php?option=com_bids&task=viewbids&id=$ma->id&Itemid=$Itemid");

    			$status="";
    			if ($ma->close_offer){
    			    $status=bid_closed;

    			}elseif(!$ma->published){
    			    $status=bid_unpublished;
    			}elseif(strtotime($ma->end_date) <= strtotime(date("Y-m-d",time()))){
                    $status=bid_expired;
                }else{
                    $status=bid_published;
                }

    			 $return .='<tr class="mywatch'.$k.'">';
    			 $return .='<td>';
    			 $return .= $status;
    			 $return .='</td>';

    			 $return .='<td>';
    			 $return .= '<a href="'.$link_view_auction.'">'.mosStripslashes($ma->title).'</a>';
    			 $return .='</td>';

    			 $return .='<td>';
    			 $return .= date(bid_opt_date_format,strtotime($ma->start_date));
    			 $return .='</td>';
    			 $return .='<td>';
    			 $return .= date(bid_opt_date_format,strtotime($ma->end_date));
    			 $return .='</td>';
    			 $return .='<td>';
    			 $return .= $ma->initial_price." ".$ma->currency_name;
    			 $return .='</td>';
    			 if($my->id==$user->user_id){
    			     if ($last_bid) $last_bid.=' '.$ma->currency_name;
    			  	 $return .= '<td>'.$last_bid.'</td>';
    			 }
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


		return $return;
	}
}
?>