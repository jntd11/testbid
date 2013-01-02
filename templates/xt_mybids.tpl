{* Include Overlib initialisation *}
{include file='t_overlib.tpl'}
{set_css}

<form action="index.php" method="get" name="auctionForm">
<input type="hidden" name="option" value="com_bids">
<input type="hidden" name="task" value="{$task}">
<input type="hidden" name="Itemid" value="{$Itemid}">

{* Include filter selectboxes *}
{include file='t_header_filter.tpl'}
<h2>{$smarty.const.bid_my_bids}</h2>

<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/ratings.js"></script>

<table width="60%" cellpadding="0" cellspacing="0" border="0" id="auction_list_container" >
<tr>
	<td width="30%">{$lists.filter_bidtype}</td>
</tr>
</table>

<table align="center" cellpadding="0" cellspacing="0" width="100%" id="auction_list_container">
    {section name=auctionsloop loop=$auction_rows}
        {assign var=current_row value=`$auction_rows[auctionsloop]`}
        {include file='t_mybids_cell.tpl'}
    {/section}
</table>

{include file='t_listfooter.tpl'}
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/startcounter.js"></script>
</form>
