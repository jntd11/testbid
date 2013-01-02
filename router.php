<?php

	function bidsBuildRoute(&$query){

		$database = &JFactory::getDBO();

		$segments = array();
    	if(isset($query['task'])){
    		
    		if( isset($query['task']) && $query['task'] =="viewbids") {
				if(!empty($query['id'])){
					$q 	 = "SELECT a.title FROM #__bid_auctions AS a WHERE id='".$query['id']."'"; $database->setQuery($q);
					$rec = $database->loadObject();
					$segments[]="viewbids"; 
					$segments[]=$query['id'];$segments[]=sefbids_helper::str_clean($rec->title).".html";
					unset($query['id']);
					unset($query['task']);
				}
    		}
    		
    		if( isset($query['task']) &&  $query['task'] =="listcats") {
				
				$segments[]="listcats.html";
				unset($query['task']);
			}
    		if( isset($query['task']) &&  $query['task'] =="search") {
				
				$segments[]="search.html";
				unset($query['task']);
			}
    		if( isset($query['task']) &&  $query['task'] =="tags") {
				
				$segments[]="tags";
				unset($query['task']);
				if(!empty($query['tag'])){
					$segments[]=$query['tag'];
					unset($query['tag']);
				}
			}
			
    		if( isset($query['task']) &&  $query['task'] =="listauctions") {
				
				$segments[]="listauctions";
				unset($query['task']);
				
				if(isset($query['cat'])){
					$segments[]=sefbids_helper::getCatString($query['cat']);
					unset($query['cat']);
				}
				if(!empty($query['userid'])){
					$q="SELECT username FROM #__users WHERE id='".$query['userid']."'"; $database->setQuery($q);
					$rec = $database->loadObject();
					if ($rec){
    					$segments[]='user/'.$query['userid'].'/'.sefbids_helper::str_clean($rec->username);
    					unset($query['userid']);
					}
				}
				
    		}
    	}

		return $segments;
	}

	function bidsParseRoute($segments){


		$vars = array();

		switch($segments[0]){
			case 'viewbids':
				$vars['task'] = 'viewbids';
				$v=explode('/',$segments[1]);
				$vars['id']=$v[0];
			break;
			case 'listcats.html':
				$vars['task'] = 'listcats';
				$v=explode('/',$segments[1]);
			break;
			case 'search.html':
				$vars['task'] = 'search';
				$v=explode('/',$segments[1]);
			break;
			case 'tags':
				$vars['task'] = 'tags';
				$vars['tag']=$segments[1];
			break;
			case 'listauctions':
				$vars['task'] = 'listauctions';

				if($segments[1]=="user") {
					$vars['userid']=$segments[2];
				}else{
					$pcat = isset($segments[1])?$segments[1]:null;
					$pscat = isset($segments[2])?$segments[2]:null;
					$pcat = preg_replace('/:/', '-', $pcat, 1);
					$pscat = preg_replace('/:/', '-', $pscat, 1);
					$vars['cat']=sefbids_helper::getCat($pcat, $pscat);
				}
			break;
		}
		return $vars;

	}
	
	
	
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
        		
        		$categ = new mosBidCategories($database);
        		$t = $categ->string_cat_path($id);
        		return $t;
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

        }
}
?>