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
{* include file='t_header_filter.tpl' *}

<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/ratings.js"></script>
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/ui/jquery.js"></script>
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/ui/interface.js"></script>
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/ui/jquery.simplemodal.js"></script>
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/ui/jqueryForm.js"></script>
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/yui/yahoo-dom-event/yahoo-dom-event.js"></script>
{literal}
<script type="text/javascript">
   function backOrg(ids) {
	$("#row"+ids).attr("style","");
	var pid = $("#pid"+ids).val();
	$("#colprice"+ids).html('<a class="nolink" href="index.php?option=com_bids&amp;task=viewbids&amp;id='+ids+'&orgtask=listbigboard&p='+pid+'">'+new1['bid_price']+'</a>');
	$("#coluser"+ids).html('<a class="nolink" href="index.php?option=com_bids&amp;task=viewbids&amp;id='+ids+'&orgtask=listbigboard&p='+pid+'">'+new1['userid']+'</a>');
	$("#colnext"+ids).html('<a class="nolink" href="index.php?option=com_bids&amp;task=viewbids&amp;id='+ids+'&orgtask=listbigboard&p='+pid+'">'+new1['bid_next']+'</a>');
   }
   function checkCount(min,colorbg,colorbgmin) {
			$.post("index.php", {
					option: "com_bids",
					task: "checkbb"
			}, function(data) {
				var data1;
				var olddata = data;
				data = eval("(" + data + ")");
				if(data != 0) {
					for (data1 in data) {
						var new1 = data[data1];
						var pid = $("#pid"+new1['id_offer']).val();
						$("#colprice"+new1['id_offer']).html('<a class="nolink" href="index.php?option=com_bids&amp;task=viewbids&amp;id='+new1['id_offer']+'&orgtask=listbigboard&p='+pid+'">'+new1['bid_price']+'</a>');
						$("#coluser"+new1['id_offer']).html('<a class="nolink" href="index.php?option=com_bids&amp;task=viewbids&amp;id='+new1['id_offer']+'&orgtask=listbigboard&p='+pid+'">'+new1['userid']+'</a>');
						$("#colnext"+new1['id_offer']).html('<a class="nolink" href="index.php?option=com_bids&amp;task=viewbids&amp;id='+new1['id_offer']+'&orgtask=listbigboard&p='+pid+'">'+new1['bid_next']+'</a>');
						var currentColor = $("#row"+new1["id_offer"]).attr("style");
						if(currentColor == undefined || currentColor.match("background-color:") == null){
							$("#row"+new1['id_offer']).attr("style","background-color: "+colorbg+";").fadeIn("1000");
							var str1 = 'backOrg('+new1["id_offer"]+');'
							setTimeout(str1, (colorbgmin * 1000 * 60));
						}
					}
				}
			}); 
	return true;
 }
 function refreshMe() {
	window.location.reload();
 }

</script>
{/literal}
<table width="100%" cellpadding="0" cellspacing="0" border="0" valign="top" style="padding-top: -10px; ">
<tr><td  style="text-align: left; color: green;" width="20%"><b>Click on Lot# to Bid</b></td>
<td align="center"  width="60%"><span style="text-align: center; font-size: 20px; color: blue;" ><b>
{if $task == "listbigboard"}
	THE BIG BOARD
{elseif $task == "listmybigboard"}
	MY For Sale BIG BOARD
{else }
	MY BIG BOARD
{/if}
</b></span></td>
<td align="right"  width="20%"><input type="button" value="Refresh (F5)" id="refresh" class="button art-button"  onClick="refreshMe();"></td></tr>
</table>
<table align="center" cellpadding="2" cellspacing="2" width="100%" id="auction_list_container" border="0" style="border: 1px solid #FFFFFF">
    <tr id="auction_row{$class}" >	
     {section name=loop1 start=0 loop=3 step=1}
	  <td >
		<table width="100%" style="border: 1px solid #FFFFFF;">
		<tr >
			<td class="col1" width="20%">&nbsp;&nbsp;Lot #</td>
			<td class="col" width="21%">{$smarty.const.label_short_desc|string_format:"%10.10s"}</td>
			<td  width="20%" colspan="2">Current Bid</td>
			<td  width="20%" align="center">Current Bidder #</td>
			<td  width="20%" colspan="2">Next Bid</td>
		</tr>
		</table>
	  </td>
     {/section}
  </tr>
   <tr>
     <td align="left" valign="top">
		{assign var=countHead value=0}
		{assign var=headeriteration value=1}
		{assign var=headeriterationorg value=1}
		{assign var=headerinit value="0"}
		<table width="100%" cellpadding="1" cellspacing="0" border="0" valign="top" >
		{section name=auctionsloop loop=$auction_rows}
			{assign var=iteration1 value=$smarty.section.auctionsloop.iteration-1}
			{if $smarty.section.auctionsloop.iteration/2 is odd}
				{assign var=class value="1"}
			{else}
				{assign var=class value="2"}
			{/if}
			{if $headeriteration == 1 && $headerinit == 0}
				<tr><td colspan="7" align="center">{$headers.$headeriteration}</td></tr>
				{assign var=headerinit value=1}
			{/if}
			{if $countHead == $headersnum.$headeriteration}
				{assign var=headeriteration value="`$headeriteration+1`"}
				{if $headersoption.$headeriteration == 1 && $headeriteration != 1}
					</table></td>
					<td align="left" valign="top">
					<table width="100%" cellpadding="1" cellspacing="0" border="0" valign="top" >
				{/if}
				
				<tr><td colspan="7" align="center">{$headers.$headeriteration}</td></tr>
				{assign var=countHead value=1}
			{else}
				{assign var=countHead value="`$countHead+1`"}
			{/if}
			{assign var=current_row value=`$auction_rows[auctionsloop]`}
			{include file='t_listbigboard_cell.tpl'}
			
 		{/section}
		</table>
	 </td>
   </tr>
</table>
{* include file='t_listfooter.tpl' *}
</form>
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/auctions.js"></script>
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/startcounter.js"></script>
