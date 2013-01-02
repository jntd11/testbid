{if $smarty.section.auctionsloop.iteration mod 2 == 1}
	{assign var=class value="1"}
{else}
	{assign var=class value="2"}
{/if}
{if $current_row->featured && 	$current_row->featured!='none'}
	{assign var=class_featured value="listing-"|cat:$current_row->featured}
{else}
	{assign var=class_featured value=""}
{/if}

{*  ADDED for item display background change - LDH 091509*}
{*  LDHStartA *}
{* JaiStartY *}

		<tr id="row{$current_row->id}" class="auction_bb_row{$class}" style="margin-bottom: 10px;">
			<td class="col1" width="20%"><input type="hidden" id="pid{$current_row->id}" name="pid{$current_row->id}" value="{$current_row->pageId}">
			&nbsp;&nbsp;<a href="index.php?option=com_bids&task=viewbids&orgtask={$task}&id={$current_row->id}&p={$current_row->pageId}" class="nolink">{$current_row->title}</a></td>
			<td class="col5" width="20%"><a href="index.php?option=com_bids&task=viewbids&orgtask={$task}&id={$current_row->id}&p={$current_row->pageId}" class="nolink">{$current_row->shortdescription|string_format:"%10.10s"}</a></td>
			<td class="" width="4%">{if $current_row->currency_name == "USD"} $ {elseif $current_row->currency_name == ""} $ {else} {$current_row->currency_name} {/if}</td>
			<td class="col2" width="16%" id="colprice{$current_row->id}" onBlur="checkbid();"><a href="index.php?option=com_bids&task=viewbids&orgtask={$task}&id={$current_row->id}&p={$current_row->pageId}" class="nolink">{if $current_row->bid_price != 0} {$current_row->bid_price} {/if}</a></td>
			<td class="col3" width="20%" id="coluser{$current_row->id}"><a href="index.php?option=com_bids&task=viewbids&orgtask={$task}&id={$current_row->id}&p={$current_row->pageId}" class="nolink">{if $current_row->bid_user != 0}{$current_row->bid_user}{/if}</a></td>
			<td class="col2" width="4%">{if $current_row->currency_name == "USD"} $ {elseif $current_row->currency_name == ""} $  {else} {$current_row->currency_name} {/if} </td>
			<td class="col4" width="16%" id="colnext{$current_row->id}"><a href="index.php?option=com_bids&task=viewbids&orgtask={$task}&id={$current_row->id}&p={$current_row->pageId}" class="nolink">{$current_row->bid_next}</a></td>
		</tr>
		<tr><td colspan="7"></td></tr>
