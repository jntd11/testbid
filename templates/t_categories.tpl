{set_css}
<h2>{$smarty.const.bid_cat_head}</h2>
{if $smarty.get.cat>0}
<a href="{$mosConfig_live_site}/index.php?option=com_bids&task=listcats&Itemid={$Itemid}">All</a>
{/if}
<table id="auction_categories" cellspacing="0" cellpadding="0">
{section name=category loop=$categories}
{if $smarty.section.category.rownum is odd}
<tr>
{/if}
	<td width="50%" id="auction_catcell" valign="top">
		<div id="auction_maincat" style="background:url({$mosConfig_live_site}/components/com_bids/images/cat_bg.gif); border:1px solid #FFFFFF; padding-left:2px;">
			<a href="{if $categories[category]->kids>0}{$categories[category]->view}{else}{$categories[category]->link}{/if}" title="{if $categories[category]->kids>0}Subcategories{else}View listings{/if}"><strong>{$categories[category]->catname}</strong></a>
			<a href="index.php?option=com_bids&task=rss&cat={$categories[category]->id}" target="_blank"><img src="{$mosConfig_live_site}/components/com_bids/images/f_rss.jpg" width="10" border="0" alt="rss" /></a>
			<a style="font-size:12px !important;" href="{$categories[category]->link}"><img src="{$mosConfig_live_site}/components/com_bids/images/category.gif" width="10" border="0" alt="view listings" /></a>
		</div>
		<div id="auction_subcat" style="background:#F5F5F5; border:1px solid #FFFFFF; " >
		<span style="font-size:12px;">Subcategories:   {$categories[category]->kids} Auctions: {$categories[category]->nr_a}</span>
		<br />
		{section name=subcategory loop=$categories[category]->subcategories}
			<a href="{if $categories[category]->subcategories[subcategory]->kids>0}{$categories[category]->subcategories[subcategory]->view}{else}{$categories[category]->subcategories[subcategory]->link}{/if}">{$categories[category]->subcategories[subcategory]->catname} </a> ({$categories[category]->subcategories[subcategory]->kids} subcats ) ({$categories[category]->subcategories[subcategory]->nr_a} auctions)
    		<a href="index.php?option=com_bids&task=rss&cat={$categories[category]->subcategories[subcategory]->id}" target="_blank"><img src="{$mosConfig_live_site}/components/com_bids/images/f_rss.jpg" width="10" border="0" alt="rss" /></a> <a style="font-size:9px !important;" href="{$categories[category]->subcategories[subcategory]->link}"><img src="{$mosConfig_live_site}/components/com_bids/images/category.gif" width="10" border="0" alt="view listings" /></a>
			<br />
		{/section}
		</div>
	</td>
{if $smarty.section.category.rownum is not odd}
</tr>
{/if}
{/section}
</table>