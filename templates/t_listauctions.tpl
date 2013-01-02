{*
 modified 3.06.2009
 added in line 42:
 <script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/auctions.js"></script>
 *}
{* Include Overlib initialisation *}
{include file='t_overlib.tpl'}
{set_css}

<form action="index.php" method="get" name="auctionForm">
<input type="hidden" name="option" value="com_bids">
<input type="hidden" name="task" value="{$task}">
<input type="hidden" name="Itemid" value="{$Itemid}">

{* Include filter selectboxes *}
{include file='t_header_filter.tpl'}

<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/ratings.js"></script>
{literal}
<script>
function refreshMe(){
	window.location.reload();
 }
</script>
{/literal}

<table width="100%" cellpadding="0" cellspacing="0" border="0" id="auction_list_container" >
<tr>
	<td width="30%">{$lists.filter_bidtype}</td>
	<td width="20%">{$lists.filter_order_asc}</td>
	<td width="20%">{$lists.filter_cats}</td>
	<td width="30%"><nobr>{$smarty.const.bid_order_by}:&nbsp;{$lists.orders}<a href="index.php?option=com_bids&task=rss" target="_blank">
    		<img src="{$mosConfig_live_site}/components/com_bids/images/f_rss.jpg" width="14" border="0">
    		</a></nobr></td>

</tr>
<tr><td align="right" colspan="4"><a href="javascript:void(0);" onClick="refreshMe();">Reload Page</a></td></tr>
</table>

<table align="center" cellpadding="0" cellspacing="0" width="100%" id="auction_list_container">
    {section name=auctionsloop loop=$auction_rows}
        {assign var=current_row value=`$auction_rows[auctionsloop]`}
        {include file='t_listauctions_cell.tpl'}
    {/section}
</table>

{include file='t_listfooter.tpl'}
</form>
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/auctions.js"></script>
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/startcounter.js"></script>
