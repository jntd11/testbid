<?php
/**
 * @package AuctionsFactory
 * @version 1.6.0
 * @copyright www.thefactory.ro
 * @license: commercial
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

class sef_bids {
    function GetTaskMap()
    {
        $task_map=array(
            "viewbids"=>"auction",
            "ViewDetails"=>"auctioneer"

        );
        return $task_map;
    }
    function Task2Map($task){
        $map=sef_bids::GetTaskMap();

        if (isset($map[$task]))
            return $map[$task];
        else
            return $task;
    }
    function Map2Task($url_part){
        $map=array_flip(sef_bids::GetTaskMap());
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
		$cl_catName = sef_bids::str_clean($catDetails[0]->catname);
		if($catDetails[0]->parent != 0){
			$database->setQuery("select catname from #__bid_categories where id=".$catDetails[0]->parent);
			$parentDetails = $database->loadObjectList();
			$cl_parentName = sef_bids::str_clean($parentDetails[0]->catname);
			$returnString = $cl_parentName."/".$cl_catName;
		}else {
			$returnString = $cl_catName;
		}

		return $returnString;

	}

	function getCat(&$cat,&$subcat){
		global $database;
		if (!$cat) return false;
		$database->setQuery("select id from #__bid_categories where hash like '".md5(strtolower(sef_bids::str_clean($cat)))."'");
		$id = $database->loadResult();

		if ($subcat){
    		$database->setQuery("select id from #__bid_categories where hash like '".md5(strtolower(sef_bids::str_clean($subcat)))."' and parent='$id'");
		    $id = $database->loadResult();

		}
		return $id;
	}

	function getTitleBid(&$id){
		global $database;
		$database->setQuery("select title from #__bid_auctions where id=$id");
		$result = $database->loadResult();
		$result = sef_bids::str_clean($result);
		return $result;
	}

	function getUsername(&$id){
		global $database;
		$database->setQuery("select a.username from #__users a where a.id = $id ");
		$result = $database->loadResult();
		$result = sef_bids::str_clean($result);
		$result = $id."/".$result;
		return $result;
	}

	function getVal (&$string, $varname, $default) {

	     $string = str_replace("&amp;","&",$string);    // replace "&amp;" with "&" for explore routine...
	     $vars = explode("&", $string);

	     $temp = array();
	     $i=0;

	     foreach ($vars as $var) {
	        $temp = explode("=", $var);

	        if ($temp[0] == $varname) {  // Found the variable
	         	break;
	        }
	        $i++;
	     }
	     if ($temp[0] != $varname) { // Not found => Set Default values
	        $temp[0] = $varname;
	        $temp[1] = $default;
	     }

     return $temp[1];
	}

	function  setVar($varname,$value) {

	 global $_GET, $_REQUEST,$_POST;       // Mandatory : Need to access to gobal Link (Request) and Posted (GET) Mambo data


     $_POST[$varname] = $value;
     $_GET[$varname] = $value;
     $_REQUEST[$varname] = $value;
     $v = "&" .$varname. "=$value";

     return $v;
  	}

  	function create($url)
  	{
  	    global $mosConfig_absolute_path;
        include($mosConfig_absolute_path.'/components/com_bids/options.php');

  	    $task=sef_bids::getVal($url,'task','listauctions');

  	    $limit=sef_bids::getVal($url,'limit',bid_opt_nr_items_per_page);
  	    $limitstart=sef_bids::getVal($url,'limitstart',0);
  	    $task=sef_bids::getVal($url,'task','listauctions');
  	    $id=sef_bids::getVal($url,'id',0);
  	    $bid=sef_bids::getVal($url,'bid',0);
  	    $cat=sef_bids::getVal($url,'cat','');
  	    $userid=sef_bids::getVal($url,'userid',0);

  	    $auction_nr=sef_bids::getVal($url,'auctionnr',0);

  	    $tags=sef_bids::getVal($url,'tag','');

  	    $sefstring=array();

  	    $sefstring[]=sef_bids::Task2Map($task);

  	    if($id) $sefstring[]=$id;
  	    if($bid) $sefstring[]=$bid;
  	    if($cat) $sefstring[]=sef_bids::getCatString($cat);
  	    if($userid) $sefstring[]=sef_bids::getUsername($userid);
        if ($limitstart){
            $sefstring[]='page/'.$limitstart;
            if ($limit) $sefstring[]='pagesize/'.$limit;
        }
        if ($id) $sefstring[]=sef_bids::getTitleBid($id);
        if ($auction_nr) $sefstring[]='auction_nr/'.$auction_nr;
        if ($tags) $sefstring[]=sef_bids::str_clean($tags);
//Aici de facut si cu restul optiunilor

		$managed=array('option','task','limit','Itemid','limitstart','id','bid','cat','userid','auctionnr','tag');
		$rest_options=array();
		if (strpos($url,'?')) $url=substr($url,strpos($url,'?')+1);
	    $vars = explode("&", $url);
	    if (count($vars))
		     foreach ($vars as $var) {
		        $temp = explode("=", $var);
		        if (in_array($temp[0],$managed)) continue;
		        array_push($rest_options,$temp[0]);
		        array_push($rest_options,$temp[1]);
		}
		if (count($rest_options)) {
			$sefstring[]='options';
			$sefstring=array_merge($sefstring,$rest_options);
		}
		return  implode('/', $sefstring);
  	}
    function getItemid($query)
    {
        global $database;

		$database->setQuery("select id from #__menu where link like 'index.php?$query%'");
		return  $database->loadResult();
    }
    function getPaging($url_array)
    {

        $ret="";
        if (in_array("page",$url_array)){
            $i=array_search("page",$url_array);
            $ret.="&limitstart=".$url_array[$i+1];
            sef_bids::setVar("limitstart",$url_array[$i+1]);
            $i=array_search("pagesize",$url_array);
            if ($i!==FALSE) {
                    $ret.="&limit=".$url_array[$i+1];
                    sef_bids::setVar("limit",$url_array[$i+1]);

            }
        }
        return $ret;
    }
    function getRestOptions($url_array)
    {
        $ret="";
        if (in_array("options",$url_array)){
            $i=array_search("options",$url_array)+1;
            for($j=$i;$j<count($url_array);$j=$j+2){
                sef_bids::setVar($url_array[$j],$url_array[$j+1]);
                $ret.="&".$url_array[$j]."=".$url_array[$j+1];

            }
        }

        return $ret;

    }
    function getAuctionNr($url_array)
    {
        $ret="";
        if (in_array("auction_nr",$url_array)){
            $i=array_search("auction_nr",$url_array);
            sef_bids::setVar("auction_nr",$url_array[$i+1]);
            $ret.="&auction_nr=".$url_array[$i+1];
        }
        return $ret;

    }
	function revert (&$url_array, $pos) {
		//echo $pos;

		global $database;
		$QUERY_STRING="";
		$QUERY_STRING .=substr(sef_bids::setVar("option","com_bids"),1);

        $task=sef_bids::Map2Task($url_array[2]);
        if ($task=="auction_nr") {
                $task="showSearchResults";
                $auction_nr=$url_array[3];
        }

		$QUERY_STRING .= sef_bids::setVar("task",$task);


        $item = 3;
        $Itemid = sef_bids::getItemid($QUERY_STRING);

        if (in_array($task,
            array("viewbids","ViewDetails","cancelauction",
                "editauction","republish","bin","publish",
                "watchlist","delwatch","report_auction"
            ))){
        		$QUERY_STRING .= sef_bids::setVar("id",$url_array[$item]);
        		$item++;

            }
        if ($task=="listauctions" && $url_array[$item] ){
                if (is_numeric($url_array[$item])){
            		$QUERY_STRING .= sef_bids::setVar("userid",$url_array[$item]);
                }else{
                    $cat=sef_bids::getCat($url_array[$item],$url_array[$item+1]);
                    if ($cat) $QUERY_STRING .= sef_bids::setVar("cat",$cat);
                }
        		$item++;
        }
        if ($task=="accept"){
            $QUERY_STRING .= sef_bids::setVar("bid",$url_array[$item]);
        }
        if ($task=="tags"){
            $QUERY_STRING .= sef_bids::setVar("tag",$url_array[$item]);
        }

		$QUERY_STRING.=sef_bids::getPaging($url_array);
		$QUERY_STRING.=sef_bids::getAuctionNr($url_array);
		$QUERY_STRING.=sef_bids::getRestOptions($url_array);
		if ($auction_nr) $QUERY_STRING.=sef_bids::setVar("auction_nr",$auction_nr);
		if ($Itemid) $QUERY_STRING.=sef_bids::setVar("Itemid",$Itemid);

		return $QUERY_STRING;
	}

}

?>