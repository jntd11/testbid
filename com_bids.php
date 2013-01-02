<?php
/**
 * sh404SEF support for com_bids component.
 * Author :  The Factory
 * contact : support@thefactory.ro
 *
 * {shSourceVersionTag: Version x - 2007-09-20}
 *
 * This is a sample sh404SEF native plugin file
 *
 */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
if (!class_exists('sefbids_helper')){
        class sefbids_helper {
            function GetTaskMap()
            {
                $task_map=array(
                    "viewbids"=>"auction",
                    "ViewDetails"=>"auctioneer"

                );
                return $task_map;
            }
            function Task2Map($task){
                $map=sefbids_helper::GetTaskMap();

                if (isset($map[$task]))
                    return $map[$task];
                else
                    return $task;
            }
            function Map2Task($url_part){
                $map=array_flip(sefbids_helper::GetTaskMap());
                if (isset($map[$url_part]))
                    return $map[$url_part];
                else
                    return $url_part;
            }

        	function str_clean( $string ) {
        		$aToReplace = array(" ","/","&","ï","¿","½","!","$","%","@","?","#","(",")","+","*",":",";","'","\"");
        		$aReplacements = array("-","-","and","");

        		$str_buff = str_replace($aToReplace,$aReplacements,strtolower($string)	);
        		return $str_buff;
        	}

        	function getCatString(&$id){
        		global $database;

        		$database->setQuery("select catname,parent from #__bid_categories where id=$id");
        		$catDetails = $database->loadObjectList();
        		$cl_catName = sefbids_helper::str_clean($catDetails[0]->catname);
        		if($catDetails[0]->parent != 0){
        			$database->setQuery("select catname from #__bid_categories where id=".$catDetails[0]->parent);
        			$parentDetails = $database->loadObjectList();
        			$cl_parentName = sefbids_helper::str_clean($parentDetails[0]->catname);
        			$returnString = $cl_parentName."/".$cl_catName;
        		}else {
        			$returnString = $cl_catName;
        		}

        		return $returnString;

        	}

        	function getCat(&$cat,&$subcat){
        		global $database;
        		if (!$cat) return false;
        		$database->setQuery("select id from #__bid_categories where hash like '".md5(strtolower(sefbids_helper::str_clean($cat)))."'");
        		$id = $database->loadResult();

        		if ($subcat){
            		$database->setQuery("select id from #__bid_categories where hash like '".md5(strtolower(sefbids_helper::str_clean($subcat)))."' and parent='$id'");
        		    $id = $database->loadResult();

        		}
        		return $id;
        	}

        	function getTitleBid(&$id){
        		global $database;
        		$database->setQuery("select title from #__bid_auctions where id=$id");
        		$result = $database->loadResult();
        		$result = sefbids_helper::str_clean($result);
        		return $result;
        	}

        	function getUsername(&$id){
        		global $database;
        		$database->setQuery("select a.username from #__users a where a.id = $id ");
        		$result = $database->loadResult();
        		$result = sefbids_helper::str_clean($result);
        		return $result;
        	}

            function getAuctionNr($url_array)
            {
                $ret="";
                if (in_array("auction_nr",$url_array)){
                    $i=array_search("auction_nr",$url_array);
                    sefbids_helper::setVar("auction_nr",$url_array[$i+1]);
                    $ret.="&auction_nr=".$url_array[$i+1];
                }
                return $ret;

            }

        }

}




// ------------------  standard plugin initialize function - don't change ---------------------------
global $sh_LANG, $sefConfig,$mosConfig_absolute_path;
$shLangName = '';
$shLangIso = '';
$title = array();
$shItemidString = '';
$dosef = shInitializePlugin( $lang, $shLangName, $shLangIso, $option);
if ($dosef == false) return;
// ------------------  standard plugin initialize function - don't change ---------------------------

// ------------------  load language file - adjust as needed ----------------------------------------
//$shLangIso = shLoadPluginLanguage( 'com_bids', $shLangIso, '_SEF_SAMPLE_TEXT_STRING');
// ------------------  load language file - adjust as needed ----------------------------------------

// remove common URL from GET vars list, so that they don't show up as query string in the URL
shRemoveFromGETVarsList('option');
shRemoveFromGETVarsList('lang');
if (!empty($Itemid))
  shRemoveFromGETVarsList('Itemid');
if (!empty($limit))
shRemoveFromGETVarsList('limit');
if (isset($limitstart))
  shRemoveFromGETVarsList('limitstart'); // limitstart can be zero


// start by inserting the menu element title (just an idea, this is not required at all)
$task = isset($task) ? @$task : null;
$Itemid = isset($Itemid) ? @$Itemid : null;

$shAuctionName = shGetComponentPrefix($option);
$shAuctionName = empty($shAuctionName) ?
		getMenuTitle($option, $task, $Itemid, null, $shLangName) : $shAuctionName;
$shAuctionName = (empty($shAuctionName) || $shAuctionName == '/') ? 'auctions':$shAuctionName;


require_once($mosConfig_absolute_path.'/components/com_bids/options.php');
if (file_exists($mosConfig_absolute_path.'/components/com_bids/lang/'.bid_opt_language))
    require_once($mosConfig_absolute_path.'/components/com_bids/lang/'.bid_opt_language);
else
    require_once($mosConfig_absolute_path.'/components/com_bids/lang/default.php');

switch ($task) {
	case 'editauction':
	case 'republish' :
	case 'saveauction' :
	case 'newauction' :
	case 'cancelauction' :
	case 'accept' :
	case 'watchlist' :
	case 'delwatch' :
	case 'savemessage' :
	case 'publish' :
	case 'bulkimport':
	case 'importcsv':
	case 'decodevin':
	case 'bin':
	case 'sendbid':
	case 'report_auction':
	case 'terms_and_conditions':
	case 'rate':
	case 'rss':
	case 'saveUserDetails':
	case 'canceluser':
	case 'ViewDetails':
	  $dosef = false;  // these tasks do not require SEF URL
	break;

	case 'viewbids':
        if (isset($id)) $auction_title=sefbids_helper::getTitleBid($id);
	case 'UserDetails':
	case 'mybids':
	case 'mywonbids':
	case 'listauctions':
	case 'myauctions':
	case 'listcats':
	case 'mywatchlist':
	case'myratings':
	case'search':
	case'showSearchResults':
	case 'tags':
	default:
        $title[]=sefbids_helper::Map2Task($task);
        if (isset($userid)) $auction_user=sefbids_helper::getUsername($userid);

        if (isset($auction_title)) $title[]=$auction_title;
        if (isset($auction_user)) {
            $title[]=$userid;
            $title[]=$auction_user;
        	shRemoveFromGETVarsList('userid');                           // also remove task, as it is not needed
        }
        if (isset($tag)) {
            $title[]=$tag;
        	  shRemoveFromGETVarsList('tag');                           // also remove task, as it is not needed
        }
        if (isset($cat)) {
            $c=sefbids_helper::getCatString($cat);
            $title=array_merge($title,explode('/',$c));
          	shRemoveFromGETVarsList('cat');                           // also remove task, as it is not needed

        }
	  shRemoveFromGETVarsList('task');                           // also remove task, as it is not needed
	                                                             // because we can revert the SEF URL without
	                                                             // it
}

// ------------------  standard plugin finalize function - don't change ---------------------------
if ($dosef){
   $string = shFinalizePlugin( $string, $title, $shAppendString, $shItemidString,
      (isset($limit) ? @$limit : null), (isset($limitstart) ? @$limitstart : null),
      (isset($shLangName) ? @$shLangName : null));
}
// ------------------  standard plugin finalize function - don't change ---------------------------


?>
