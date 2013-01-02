<?php
/**
 * @package AuctionsFactory
 * @version 1.5.0
 * @copyright www.thefactory.ro
 * @license: commercial
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

global $allow_counter; 
$allow_counter	= (int) $params->get('category_counter', 0);

global $mosConfig_absolute_path;
require_once($mosConfig_absolute_path.'/components/com_bids/bids.functions.php');

?>
<link rel="stylesheet" href="<?php echo $mosConfig_live_site; ?>/components/com_bids/js/jquery.treeview.css" />
<script type="text/javascript">
if(typeof window.jQuery == 'undefined') {
	document.writeln('<scr'+'ipt src="<?php echo $mosConfig_live_site; ?>/components/com_bids/gallery/js/jquery.js" type="text/javascript"></scr'+'ipt>');
}
</script>
<script src="<?php echo $mosConfig_live_site; ?>/components/com_bids/js/jquery.cookie.js" type="text/javascript"></script>
<script src="<?php echo $mosConfig_live_site; ?>/components/com_bids/js/jquery.treeview.js" type="text/javascript"></script>

<script type="text/javascript">

jQuery(document).ready(function(){
		jQuery("#browser_auction").treeview({
			collapsed: true,
			animated: 250,
			persist: "cookie"
		});
    document.getElementById('browser_auction').style.display='block';
});
if(typeof window.jQuery != 'undefined') {
    jQuery.noConflict();
}

</script>


<?php
global $mosConfig_absolute_path;

$database->setQuery("select * from #__bid_categories where parent='0' order by ordering,catname");
$parentcats = $database->loadObjectList();

echo "<ul id='browser_auction' class='filetree closed' style='display:none'>";
for($i=0;$i<count($parentcats);$i++){

    $database->setQuery("select * from #__bid_categories where parent='".$parentcats[$i]->id."' order by ordering,catname");
    $subcats = $database->loadObjectList();
	$counter = "";
	if($allow_counter==1){
		$database->setQuery("SELECT count(*) FROM #__bid_auctions a
	        left join #__bid_categories c on a.cat=c.id  WHERE
	        cat='{$parentcats[$i]->id}' or parent='{$parentcats[$i]->id}'");
	    $counter_cat = 0;
	    $counter_cat = $database->loadResult();
		$counter = "($counter_cat)";
    }
	$link_cat = sefRelToAbs("index.php?option=com_bids&task=listauctions&cat=".$parentcats[$i]->id);
	echo "<li  style='list-style:none'><span class='folder category_css'><a href='$link_cat'>".$parentcats[$i]->catname."".$counter."</a></span>";
	if (count($subcats)>0) echo "<ul>";
    for($j=0;$j<count($subcats);$j++){
    	$link_cat = sefRelToAbs("index.php?option=com_bids&task=listauctions&cat=".$subcats[$j]->id);
	    $counter_cat = 0;
		$counter = "";
		$Children = "";
		$cids = 0;
		if($allow_counter==1){
			$database->setQuery("SELECT count(*) FROM #__bid_auctions a
	        left join #__bid_categories c on a.cat=c.id  WHERE
	        cat='{$subcats[$j]->id}' or parent='{$subcats[$j]->id}'");
 			$counter_cat = $database->loadResult();
			$counter = "($counter_cat)";
 		}
 		
		$cids = Bids_has_children($subcats[$j]->id);
		
		if ( $cids > 0 ){
			$v = sefRelToAbs("index.php?option=com_bids&task=listcats&cat=".$subcats[$j]->id);
			$Children .= "<br /><a href='$v' style='font-size:9px !important;'>";
			$Children .= " view subcategories (".$cids.") <br />";
			$Children .= "</a>";
		}
    	echo "<li style='list-style:none'><span class='file products_css'><a href='$link_cat'>".$subcats[$j]->catname."".$counter."</a>$Children</span>";
    }
	if (count($subcats)>0) echo "</ul>";

}
echo "</ul>";


?>