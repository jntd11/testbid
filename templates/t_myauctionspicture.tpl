{*
 modified 3.06.2009
 added in line 42:
 <script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/auctions.js"></script>
 *}
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/ratings.js"></script>
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/ui/jquery.js"></script>
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/ui/interface.js"></script>
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/ui/jquery.simplemodal.js"></script>
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/ui/jqueryForm.js"></script>
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/yui/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/auctions.js"></script>
<script type="text/javascript">
   jQuery.noConflict();
 </script>
{* Include Overlib initialisation *}
{include file='t_overlib.tpl'}
{set_css}


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
<input type="hidden" name="servertime" id="servertime" value="{$smarty.now|date_format:'%m/%d/%Y %k:%M'}">
<tr><td>{$smarty.const.labelpicture}</td><td align="right"><input type="button" value="Refresh (F5)" id="refresh" class="button art-button"  onClick="refreshMe();"></td></tr>
</table>
<table align="center" cellpadding="0" cellspacing="0" width="100%" id="auction_list_container">
    {section name=auctionsloop loop=$auction_rows}
        {assign var=current_row value=`$auction_rows[auctionsloop]`}
        {include file='t_myauctionspicture_cell.tpl'}
    {/section}
</table>

</form>
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/auctions.js"></script>
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/startcounter.js"></script>
