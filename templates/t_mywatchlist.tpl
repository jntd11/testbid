{* Include Overlib initialisation *}
{include file='t_overlib.tpl'}
{set_css}

<form action="index.php" method="get" name="auctionForm">
<input type="hidden" name="option" value="com_bids">
<input type="hidden" name="task" value="{$task}">
<input type="hidden" name="Itemid" value="{$Itemid}">
{* Include filter selectboxes *}
{include file='t_header_filter.tpl'}
<h2>{$smarty.const.bid_my_watchlist}</h2>

<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/ratings.js"></script>

<table width="60%" cellpadding="0" cellspacing="0" border="0" id="auction_list_container" >
<tr>
	<td width="20%">{$lists.filter_order_asc}</td>
	<td width="20%">{$lists.filter_cats}</td>
	<td width="30%"><nobr>{$smarty.const.bid_order_by}:&nbsp;{$lists.orders}</nobr></td>
</tr>
</table>
<table align="center" cellpadding="0" cellspacing="0" width="100%" id="auction_list_container">
    {section name=auctionsloop loop=$auction_rows}
        {assign var=current_row value=`$auction_rows[auctionsloop]`}
        {include file='t_mywatchlist_cell.tpl'}
    {/section}
</table>

{include file='t_listfooter.tpl'}
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/startcounter.js"></script>
</form>
					{*  ADDED for display of *Expires in* to be updated every second of the clock *}
					{*  LDH 091809*}
					{*  LDHStartB *}
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/auctions.js"></script>
					{*  LDHEndB *}
