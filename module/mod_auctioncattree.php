<?php
/**
 * @package AuctionsFactory
 * @version 1.5.0
 * @copyright www.thefactory.ro
 * @license: commercial
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );


global $mosConfig_absolute_path;
require_once($mosConfig_absolute_path.'/components/com_bids/bids.functions.php');

echo "<link rel='stylesheet' type='text/css' href='$mosConfig_live_site/modules/auctiontree/tree.css' >";

global $allow_counter , $database; 
$allow_counter	= (int) $params->get('category_counter', 0);

function outputSubCats($pcat,$index,$link_cat){
	global $database,$mosConfig_live_site,$allow_counter;
	$database->setQuery("select * from #__bid_categories where parent='$pcat->id' order by ordering,catname");
	
	$subcats = $database->loadObjectList();
	$imgHeight=9;
	$imgWidth=9;
	$nr_scats = count($subcats);
	$html_subtree .= "<div class='styNodeRegion' style='display:none;' id='".$index."'>";
	
	//echo "<pre>";
	if($nr_scats>0){
	for($i=0;$i<$nr_scats;$i++){
		$counter = "";
		$Children = "";
		$cids = 0;
		if($allow_counter==1){
		    $database->setQuery("SELECT count(*) FROM #__bid_auctions a
		        left join #__bid_categories c on a.cat=c.id  WHERE
		        cat='{$subcats[$i]->id}' or parent='{$subcats[$i]->id}'");
		    $counter_cat = $database->loadResult();
			$counter = "($counter_cat)";
		}
		
		$cids = Bids_has_children($subcats[$i]->id);
		
		if ( $cids > 0 ){
			$v = sefRelToAbs("index.php?option=com_bids&task=listcats&cat=".$subcats[$i]->id);
			$Children .= "<a href='$v' style='font-size:10px !important; padding-left:25px;'>";
			$Children .= " view subcategories (".$cids.") <br />";
			$Children .= "</a>";
		}
		
		$link_scat = sefRelToAbs("index.php?option=com_bids&task=listauctions&cat=".$subcats[$i]->id);
		$html_subtree .= "<div id='styLink' class='slink'>";
		$html_subtree .= "<img  width=".$imgWidth." height=".$imgHeight." src='".$mosConfig_live_site."/images/M_images/indent4.png'/><span onclick='openLink(\"".$link_scat."\",\"_parent\")'>".$subcats[$i]->catname."".$counter."</span>";
		$html_subtree .= "</div>";
		$html_subtree .= $Children;
		
	}
	}elseif($nr_scats==0){
		
		$html_subtree .= "<div id='styLink'>";
		$html_subtree .= "";
		$html_subtree .= "</div>";
		
	}
	$html_subtree .= "</div>";
	return $html_subtree;
	
}

$imgHeight=9;
$imgWidth=9;
$padding_top = 2;
$padding_bottom = 2;

$database->setQuery("select * from #__bid_categories where parent='0' order by ordering,catname");
$parentcats = $database->loadObjectList();

$nr_pcats = count($parentcats);
$html_tree .='<div id="Tree" align="left" class="Tree" style="padding-top:'.$padding_top.'px;padding-bottom:'.$padding_bottom.'px">';
$j=1;
for($i=0;$i<$nr_pcats;$i++){
	
	$counter = "";
	if($allow_counter==1){
	    $database->setQuery("SELECT count(*) FROM #__bid_auctions a
	        left join #__bid_categories c on a.cat=c.id  WHERE
	        cat='{$parentcats[$i]->id}' or parent='{$parentcats[$i]->id}'");
	    $counter_cat = $database->loadResult();
		$counter = "($counter_cat)";
    }
    
	$link_cat = sefRelToAbs("index.php?option=com_bids&task=listauctions&cat=".$parentcats[$i]->id);
	$html_tree .='<div id="styFolder" >';
	$html_tree .='<span class="st_tree" onclick="hideShow(\''.$j.'\',\''.$mosConfig_live_site.'/modules/auctiontree/images/\')"><img id="pic'.$j.'" width="'.$imgWidth.'" height="'.$imgHeight.'" src="'.$mosConfig_live_site.'/modules/auctiontree/images/arrow2.gif"/></span>&nbsp;<span class="st_tree" onclick="openLink(\''.$link_cat.'\',\'_parent\')"> '.$parentcats[$i]->catname.''.$counter.'</span>';
	$html_tree .= outputSubCats($parentcats[$i],$j,$link_cat);
	$html_tree .='</div>';
	$j++;
	
}
$html_tree .='</div>';


echo $html_tree;
echo "<script type='text/javascript' src='$mosConfig_live_site/modules/auctiontree/tree.js'> </script>";
echo "<script type='text/javascript'>load('".$mosConfig_live_site."/modules/auctiontree/images/');</script>";
?>