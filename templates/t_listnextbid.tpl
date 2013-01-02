{* JaiStartI *}
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/ratings.js"></script>
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/ui/jquery.js"></script>
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/ui/interface.js"></script>
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/ui/jquery.simplemodal.js"></script>
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/ui/jqueryForm.js"></script>
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/yui/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/bidinc.js"></script>
<form action="index.php?option=com_bids&Itemid={$Itemid}" method="post" name="auctionForm" onSubmit="return validate();">
<input type="hidden" name="task" id="task" value="savenextbid">
<tr id="" >	
	  <td  colspan="2">
		<table width="100%" cellpadding="1" cellspacing="0" border="0" style="">
		<tr>
			<td colspan="5" style="font-size: 14px; font-weight: bold;">{$smarty.const.nextbidTitle}</td>
		</tr>
		<tr>
			<td >
			$ {if $auction_rows[0]->bid_inc_id != ""} <input type="hidden" name="bid_inc_id1" id="bid_inc_id1" value="{$auction_rows[0]->bid_inc_id}"> {/if}
			<input type="text" name="bid_incre1" id="bid_incre1" value="{if $auction_rows[0]->bid_next != ""}{$auction_rows[0]->bid_next}{else}50{/if}">
			</td>
			<td >{$smarty.const.nextbidcol1}</td>
			<td >$ <span id="lblbid_range_from1">{if $auction_rows[0]->range_from != ""}{$auction_rows[0]->range_from}{else}1{/if}</span> <input type="hidden" name="bid_range_from1" id="bid_range_from1" value="{if $auction_rows[0]->range_from != ""}{$auction_rows[0]->range_from|number_format}{else}1{/if}"></td>
			<td >{$smarty.const.nextbidcol2}</td>
			<td >$ <input type="text" name="bid_range_to1" id="bid_range_to1" value="{if $auction_rows[0]->range_to != ""}{$auction_rows[0]->range_to|number_format}{else}{999999|number_format}{/if}"></td>
		</tr>
		<tr>
			<td >$ 
			{if $auction_rows[1]->bid_inc_id != ""} <input type="hidden" name="bid_inc_id2" id="bid_inc_id2" value="{$auction_rows[1]->bid_inc_id}"> {/if}
			<input type="text" name="bid_incre2" id="bid_incre2" onblur="fillrange(2);" value="{if $auction_rows[1]->bid_next != ""}{$auction_rows[1]->bid_next}{/if}"></td>
			<td >{$smarty.const.nextbidcol1}</td>
			<td >$ <span id="lblbid_range_from2">{if $auction_rows[1]->range_from != ""}{$auction_rows[1]->range_from|number_format}{/if}</span> <input type="hidden" name="bid_range_from2" id="bid_range_from2" value="{if $auction_rows[1]->range_from != ""}{$auction_rows[1]->range_from}{/if}"></td>
			<td >{$smarty.const.nextbidcol2}</td>
			<td >$ <input type="text" name="bid_range_to2" id="bid_range_to2" value="{if $auction_rows[1]->range_to != ""}{$auction_rows[1]->range_to|number_format}{/if}"></td>
		</tr>
		<tr>
			<td >$ 
			{if $auction_rows[2]->bid_inc_id != ""} <input type="hidden" name="bid_inc_id3" id="bid_inc_id3" value="{$auction_rows[2]->bid_inc_id}"> {/if}
			<input type="text" name="bid_incre3" id="bid_incre3" onblur="fillrange(3);"  value="{if $auction_rows[2]->bid_next != ""}{$auction_rows[2]->bid_next}{/if}"></td>
			<td >{$smarty.const.nextbidcol1}</td>
			<td >$ <span id="lblbid_range_from3">{if $auction_rows[2]->range_from != ""}{$auction_rows[2]->range_from|number_format}{/if}</span> <input type="hidden" name="bid_range_from3" id="bid_range_from3" value="{if $auction_rows[2]->range_from != ""}{$auction_rows[2]->range_from}{/if}"></td>
			<td >{$smarty.const.nextbidcol2}</td>
			<td >$ <input type="text" name="bid_range_to3" id="bid_range_to3" value="{if $auction_rows[2]->range_to != ""}{$auction_rows[2]->range_to|number_format}{/if}"></td>
		</tr>
		<tr>
			<td >$ {if $auction_rows[3]->bid_inc_id != ""} <input type="hidden" name="bid_inc_id4" id="bid_inc_id4" value="{$auction_rows[3]->bid_inc_id}"> {/if}
			<input type="text" name="bid_incre4" id="bid_incre4" onblur="fillrange(4);"  value="{if $auction_rows[3]->bid_next != ""}{$auction_rows[3]->bid_next}{/if}"></td>
			<td >{$smarty.const.nextbidcol1}</td>
			<td >$ <span id="lblbid_range_from4">{if $auction_rows[3]->range_from != ""}{$auction_rows[3]->range_from|number_format}{/if}</span> <input type="hidden" name="bid_range_from4" id="bid_range_from4" value="{if $auction_rows[3]->range_from != ""}{$auction_rows[3]->range_from}{/if}"></td>
			<td >{$smarty.const.nextbidcol2}</td>
			<td >$ <input type="text" name="bid_range_to4" id="bid_range_to4" value="{if $auction_rows[3]->range_to != ""}{$auction_rows[3]->range_to|number_format}{/if}"></td>
		</tr>
		<tr>
			<td >$ {if $auction_rows[4]->bid_inc_id != ""} <input type="hidden" name="bid_inc_id5" id="bid_inc_id5" value="{$auction_rows[4]->bid_inc_id}"> {/if}
			<input type="text" name="bid_incre5" id="bid_incre5" onblur="fillrange(5);"  value="{if $auction_rows[4]->bid_next != ""}{$auction_rows[4]->bid_next}{/if}"></td>
			<td >{$smarty.const.nextbidcol1}</td>
			<td >$ <span id="lblbid_range_from5">{if $auction_rows[4]->range_from != ""}{$auction_rows[4]->range_from|number_format}{/if}</span> <input type="hidden" name="bid_range_from5" id="bid_range_from5" value="{if $auction_rows[4]->range_from != ""}{$auction_rows[4]->range_from}{/if}"  value="{if $auction_rows[4]->range_to != ""}{$auction_rows[4]->range_to|number_format}{/if}"></td>
			<td >{$smarty.const.nextbidcol2}</td>
			<td >$ <input type="text" name="bid_range_to5" id="bid_range_to5" value="{if $auction_rows[4]->range_to != ""}{$auction_rows[4]->range_to|number_format}{/if}"></td>
		</tr>
		<tr id="alertmess"><td colspan="5" align="left" style="color: red; font-size: 12px;">Fill in as many lines as needed.</td></tr>
		{if $expired != 1}
		<tr id="rowbutton"><td colspan="5" align="left"><input type="submit" value="Save" class="button art-button"  id="saveinc" name="saveinc" onClick=""><input type="hidden" name="type" id="type" value="1"></td></tr>
		{/if}
		</table>
	  </td>
</tr>
