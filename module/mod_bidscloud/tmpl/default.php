<?php // no direct access
defined('_JEXEC') or die('Restricted access');
?>
<style type="text/css">
	.bidsCloudModule{ }
	.fontbidsCloudModule{ }
</style>
<div class="bidsCloudModule">
<?php	
global $Itemid;
if (count($ordered_tag_list)>0)
	foreach($ordered_tag_list as $k => $v)
	{
	if($v){
		$vrate='';
		$k=mosStripslashes($k);
		$size = $min_font+($rank_font * ( ($v - $minimum_count) / $rank_freq) );
		$href=sefRelToAbs('index.php?option=com_bids&amp;task=tags&amp;Itemid='.$Itemid.'&amp;tag='.$k);
		echo "<a href=\"".$href."\" style=\"font-size: ".$size."px;\" class=\"fontbidsCloudModule\" title=\"".$k."\">".$k."</a>";
		{echo "&nbsp;\n";}
	 }
	}  
?>
</div>