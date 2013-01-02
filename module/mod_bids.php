<?php
/**
 * @package AuctionsFactory
 * @version 1.5.8
 * @copyright www.thefactory.ro
 * @license: commercial
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

global $mosConfig_absolute_path,$mosConfig_live_site, $mod_title;

defined( 'BIDS_COMPONENT_PATH' )  or define('BIDS_COMPONENT_PATH',$mosConfig_absolute_path."/components/com_bids/");

if(!defined('AUCTION_PICTURES')){
	
	define('AUCTION_PICTURES',$mosConfig_live_site.'/images/auctions');
	define('AUCTION_PICTURES_PATH',$mosConfig_absolute_path.'/images/auctions/');
	
}

$module_type=$params->get("type_display",0);
$nr_auctions=$params->get("nr_auctions_displayed",10);
$image_width=$params->get("image_width",30);
$image_height=$params->get("image_height",30);
$display_image=$params->get("display_image",1);



require_once(BIDS_COMPONENT_PATH.'/options.php');
if (file_exists(BIDS_COMPONENT_PATH.'/lang/'.bid_opt_language))
	require_once(BIDS_COMPONENT_PATH.'/lang/'.bid_opt_language);
else
	require_once(BIDS_COMPONENT_PATH.'/lang/default.php');

mosCommonHTML::loadOverlib();

switch ($module_type){
	case '0':
		$query="select a.id,a.title,a.userid,a.auction_type,a.initial_price,a.picture,b.name from #__bid_auctions a
		left join #__bid_currency b on a.currency=b.id
		where a.published = 1 and a.close_offer!=1
		and close_by_admin!=1 and start_date <= NOW()  
		GROUP BY a.id  
		order by start_date desc "; 
	break;
	case '1':
		$query="select distinct a.id,a.title,a.userid,a.auction_type,a.initial_price,a.picture,b.name from #__bid_auctions a
		left join #__bid_currency b on a.currency=b.id
		where a.published = 1 and a.close_offer!=1
		and close_by_admin!=1 and start_date <= NOW()
		GROUP BY a.id 
		order by hits desc ";

	break;
	case '2':
		$query="select a.id,a.title,a.userid,b.bid_price,a.auction_type,a.initial_price,a.picture,c.name from #__bid_auctions a
				left join #__bids b on a.id=b.id_offer
				left join #__bid_currency c on a.currency=c.id
				where a.auction_type!=2 and a.published = 1 and a.close_offer!=1
				and close_by_admin!=1 and start_date <= NOW() 
				GROUP BY a.id 
				order by b.bid_price desc ";
		//echo $query;exit;

	break;
	case '3':
		$query="select a.id,a.title,a.userid,b.bid_price,a.auction_type,a.initial_price,a.picture,c.name from #__bid_auctions a
				left join #__bids b on a.id=b.id_offer
				left join #__bid_currency c on a.currency=c.id
				where a.auction_type!=2 and a.published = 1 and a.close_offer!=1 
				and close_by_admin!=1 and start_date <= NOW() 
				GROUP BY a.id 
				ORDER BY RAND()
				";
		//echo $query;exit;

	break;
	case '4':
		$query="select a.id,a.title,a.userid,b.bid_price,a.auction_type,a.initial_price,a.picture,c.name from #__bid_auctions a
				left join #__bids b on a.id=b.id_offer
				left join #__bid_currency c on a.currency=c.id
				where a.auction_type!=2 and a.published = 1 and a.close_offer!=1 and start_date <= NOW() 
				and close_by_admin!=1 and a.featured <>'none'
				GROUP BY a.id 
				";
		//echo $query;exit;

	break;
	default:
}

$query .= " limit $nr_auctions";

$database->setQuery($query);

$rows = $database->loadObjectList();
//echo $database->_sql;exit;
$i=1;

$js = "
  if ( !document.getElementById('overDiv') ) { 
     document.writeln('<div id=\"overDiv\" style=\"position:absolute; visibility:hidden; z-index:10000;\"></div>'); 
     document.writeln('<scr'+'ipt language=\"Javascript\" src=\"{$mosConfig_live_site}/includes/js/overlib_mini.js\"></scr'+'ipt>'); 
	}
";
 if (JOOMLA_VERSION == 5){
	$document = & JFactory::getDocument();
	$document->addScriptDeclaration($js);
 }
 else
 	echo ('<script type="text/javascript">'.$js.'</script>');
?>

	<div class="module_title" align="center"><?php echo $mod_title;?></div>
	<table width="100%">
		<tr>
		  <?php if ($display_image) { ?>
		      <th>&nbsp;</th>
		  <?php } ?>
			<th><?php echo bid_title;?></th>
			<th><?php echo bid_max_bid;?></th>
		</tr>
		<?php

		for($i=0;$i<count($rows);$i++){

			$database->setQuery("select username from #__users where id=".$rows[$i]->userid);
			$by_user = $database->loadResult();

			$overlib_str = bid_by." ".$by_user."<br>";

			if($rows[$i]->auction_type == 1){
				$overlib_str .= bid_initial_price.": ".$rows[$i]->initial_price." ".$rows[$i]->name;
			}

			if($i%2 == 0){
				$alt_class="table_module_1";
			}else {
				$alt_class="table_module_2";
			}

    		$link_to_auction = sefRelToAbs("index.php?option=com_bids&amp;task=viewbids&amp;id=".$rows[$i]->id);
    		if (file_exists(AUCTION_PICTURES_PATH.$rows[$i]->picture)&& $rows[$i]->picture){
    		  $image=AUCTION_PICTURES."/".$rows[$i]->picture;
    		}else{
    		  $image=AUCTION_PICTURES."/no_image.png";
    		}

    		?>

    		<tr class="<?php echo $alt_class;?>">
		  <?php if ($display_image) { ?>
		      <td nowrap><?php echo $i+1;?>. <img src="<?php echo $image;?>" border="0" width="<?php echo $image_width;?>" height="<?php echo $image_height;?>"></td>
		  <?php } ?>
    		 <td>
    		 	<?php if (!$display_image) echo $i+1,'. ';?><a href="<?php echo $link_to_auction;?>" onmouseover="overlib('<?php echo $overlib_str;?>')" onmouseout="nd();"><?php echo mosStripslashes($rows[$i]->title);?></a>
    		 </td>
    		 <td>
    		 	<?php
    		 	if($rows[$i]->auction_type == 1){
    		 		$database->setQuery("select MAX(bid_price) from #__bids where id_offer='".$rows[$i]->id."'");
    		 		$result = $database->loadResult();
    		 		if(!$result){
    		 			$max_bid=bid_no_bids;
    		 		}else {
    		 			$max_bid = number_format($result,2)." ".$rows[$i]->name;
    		 		}
    		 	}else {
    		 		$max_bid = bid_bid_private;
    		 	}
    		 	echo $max_bid;
    		 	?>
    		 </td>
    		</tr>
    		<?php
		}?>
	</table>
