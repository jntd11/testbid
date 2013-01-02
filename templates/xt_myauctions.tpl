{* Include Overlib initialisation *}
{include file='t_overlib.tpl'}
{set_css}

<form action="index.php" method="get" name="auctionForm">
<input type="hidden" name="option" value="com_bids">
<input type="hidden" name="task" value="{$task}">
<input type="hidden" name="Itemid" value="{$Itemid}">

{* Include filter selectboxes *}
{include file='t_header_filter.tpl'}
<h2>{$smarty.const.bid_my_auctions}</h2>
<span class='auction_info_text'>{$smarty.const.bid_help_myauctions}</span><br/>
<table width="60%" cellpadding="0" cellspacing="0" border="0" id="auction_list_container" >
<tr>
	<td width="30%" colspan="2"><input type="button" value="{$smarty.const.but_new}" class="back_button" onclick="window.location='{$mosConfig_live_site}/index.php?option=com_bids&task=newauction&Itemid={$Itemid}';">
	{if $smarty.const.bid_opt_allow_import}
	<input type="button" value="{$smarty.const.but_bulk_import}" class="back_button" onclick="window.location='index.php?option=com_bids&task=bulkimport&Itemid={$Itemid}';">
	{/if}
	</td>
	<td width="*%" align="left">&nbsp;</td>
</tr>
<tr>
	<td width="30%" align="left">{$lists.filter_cats}</td>
	<td width="30%" align="left">{$lists.archive}</td>
	<td width="*%" align="left">&nbsp;</td>
</tr>
</table>

<table align="center" cellpadding="0" cellspacing="0" width="100%" id="auction_list_container">
    {section name=auctionsloop loop=$auction_rows}
        {assign var=current_row value=`$auction_rows[auctionsloop]`}
        {include file='t_myauctions_cell.tpl'}
    {/section}
</table>
{include file='t_listfooter.tpl'}
</form>
