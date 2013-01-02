{* Include Validation script *}
{include file='t_javascript_language.tpl'}
{if $current_row->rownr is odd}
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
{*  <tr id="auction_row{$class}" style="background:url({$mosConfig_live_site}/components/com_bids/images/auction_bg{$class}.gif) repeat-x;" class="{$class_featured}">*}
	<form action="index.php" method="post" name="auctionForm" onsubmit="return FormValidate2({$current_row->id});">
		  <input type="hidden" name="option" value="com_bids">
		  <input type="hidden" name="pageId" value="{$current_row->pageId}">
			<input type="hidden" name="leadbid" id="leadbid{$current_row->id}" value="{$current_row->mine}">
			<input type="hidden" name="task" value="sendbid">
		  <input type="hidden" name="id" value="{$current_row->id}">
		  <input type="hidden" name="initial_price" id="initial_price{$current_row->id}" value="{$current_row->initial_price}">
		  <input type="hidden" name="bin_price" id="bin_price{$current_row->id}" value="{$current_row->BIN_price}">
		  <input type="hidden" name="mylastbid" id="mylastbid{$current_row->id}" value="{$current_row->bid_price}">
		  <input type="hidden" name="min_increase"  id="min_increase{$current_row->id}" value="{$current_row->min_increase}">
		  <input type="hidden" name="start_date" id="start_date{$current_row->id}" value="{$current_row->start_date|date_format:"%m/%d/%Y %H:%M"}">
		  <input type="hidden" name="maxbid" id="maxbid{$current_row->id}" value="{$current_row->bid_price}">
         	  <input type="hidden" name="Itemid" value="{$Itemid}">
		  <input type="hidden" name="auction_id" id="auction_id{$current_row->id}" value="{$current_row->id}">
    <tr id="auction_row{$class}">		
{*  Removed (commented) Duplicate coulmn - Jai 091709*}
{*  JaiStartB *}		
  {*    <td id="auction_thumb{$class}"  valign="top"> *}
{* JaiEndB *}
					{*  LDHEndA *}
		<td id="auction_thumb{$class}"  valign="top" width="20%">
			<a href="{if $task=='mybids'}{$current_row->links.bid_list}{else}{$current_row->links.auctiondetails}{/if}">{$current_row->gallery}</a>
		</td>
		<td valign="top" id="auction_cell" width="80%">
			<table width="100%">
			<tr>
    			<td colspan="2" valign="top">
				<table width="100%">
				<tr>
					{* LDHStartE *}
					<td width="10%">
						<div id="auction_title">
							{$smarty.const.auction_title} 
							<a href="{if $task=='mybids'}{$current_row->links.bid_list}{else}{$current_row->links.auctiondetails}{/if}">{$current_row->title}</a>
							{if $current_row->auction_type eq $smarty.const.AUCTION_TYPE_PRIVATE}
									<span id="auction_private">{$smarty.const.bid_private}</span>
							{/if}
						</div>
					</td>
					<td width="20%">
						{$smarty.const.label_short_desc} 
						{$current_row->shortdescription}
					</td>
					<td width="30%">
					{* LDHEndE *}
						{$smarty.const.label_custom_field1}
						{$current_row->custom_fld1}
					</td>
					<td width="5%">&nbsp;</td>
					<td width="25%">
					<div id="auction_date">
						<span>{$smarty.const.bid_bid_price}</span>&nbsp;
						<span id="auction_price_bold">
							{if $current_row->bid_price != 0}
								{$current_row->currency_name}&nbsp;{$current_row->bid_price|number_format:0}
							{/if}
						</span>
					</div>
					</td>
					<td width="10%">
					<div id="auction_date">
						<span>{$smarty.const.current_bidder}</span>&nbsp;
						<span id="auction_price_bold">
							{$current_row->current_bidder}
						</span>
					</div>
					</td>
				</tr>
				<tr>
					<td>
						{$smarty.const.label_custom_field2}
						{$current_row->custom_fld2}
					</td>
					<td>
						{$smarty.const.label_custom_field3}
						{$current_row->custom_fld3}
					</td>
					<td>
						{$smarty.const.label_custom_field4}
						{$current_row->custom_fld4}
					</td>
					<td width="5%">&nbsp;</td>
					<td>
						<span>{$smarty.const.next_bid}</span>&nbsp;
						<span id="auction_price_bold">
							{$current_row->currency_name}&nbsp;{$current_row->bid_next|number_format:0}
						</span>
					</div>
					</td>
					<td>
					<div id="auction_info_bottom">
        				{if $current_row->add_to_watchlist}
        					<span id='add_to_watchlist'><a href='{$current_row->links.add_to_watchlist}'>
        						<img src="{$mosConfig_live_site}/components/com_bids/images/f_watchlist_1.jpg" title="{$smarty.const.bid_add_to_watchlist}" alt="{$smarty.const.bid_add_to_watchlist}"/>
        					</a></span>
        				   {elseif $current_row->del_from_watchlist}
        					<span id='add_to_watchlist'><a href='{$current_row->links.del_from_watchlist}'>
        						<img src="{$mosConfig_live_site}/components/com_bids/images/f_watchlist_0.jpg" title="{$smarty.const.bid_remove_from_watchlist}" alt="{$smarty.const.bid_remove_from_watchlist}"/>
        					</a></span>
        				{/if}
					</div>
					</td>
				</tr>
				<tr>
					<td>
							{*  ADDED for Optional Fields - Jai *}
							{*  JaiStartA *}
							{if $smarty.const.bid_opt_allow_auctioneer == 1}
								{$smarty.const.bid_bid_seller}:&nbsp;<a href="{$current_row->links.otherauctions}" alt="{$smarty.const.bid_more_offers_user}">{$current_row->username}</a><br/>
							{/if}
					</td>
					<td>
						{$smarty.const.label_custom_field5}
						{$current_row->custom_fld5}
					</td>
					<td>
						&nbsp;
					</td>
					<td width="5%">&nbsp;</td>
					<td width="25%">
						{if  !$current_row->expired && $smarty.const.bid_opt_allow_proxy == 1 && $current_row->is_my_auction != 1}
						<input type="hidden" name="prxo" id="prxo{$current_row->id}" value="0">
						<input {$disable_bids} type="checkbox" class="inputbox" name="proxy" id="proxy" value="1" onclick="ProxyClick1(this,{$current_row->id});">&nbsp;{$smarty.const.bid_proxy}
						{infobullet text=$smarty.const.bid_help_proxy_bidding}
						{/if}

					</td>
					<td>
						{if $current_row->proxy_price != 0}
							{$smarty.const.proxyLabel} {$current_row->currency_name}&nbsp;{$current_row->proxy_price|number_format:0}
						{/if}
						{if $current_row->proxyplus_price != 0}
							{$smarty.const.proxyplusLabel} {$current_row->currency_name}&nbsp;{$current_row->proxyplus_price|number_format:0}
						{/if}
						
					</td>
				</tr>		
				<tr>
					<td colspan="3" rowspan="2">
						{$current_row->description}
					</td>
					<td width="5%">&nbsp;</td>
					<td>
						  {if  !$current_row->expired && $current_row->is_my_auction != 1}
							<span id="bid{$current_row->id}" >
							{if $auction->highest_bid gt 0}
								{$smarty.const.bid_my_bids}
							{else}
								{$smarty.const.bid_my_bids}
							{/if}
							</span>
						{$current_row->currency_name}&nbsp;<input name="amount{$current_row->id}" id="amount{$current_row->id}" class="inputbox" type="text" value="" size="20" alt="bid" {$disable_bids}>&nbsp;
						{/if}
					</td>
					<td>
						&nbsp;
					</td>
				</tr>		
				<tr>
					<td width="5%">&nbsp;</td>
					<td>
						 {if  $current_row->expired}
    							<div id="auction_info_bottom">
    						   <font class='expired'>{$smarty.const.bid_expired}</font>
    							</div>
    						{elseif $smarty.const.bid_opt_enable_countdown}
    							<div id="auction_info_bottom">
							{if $current_row->countdowntype == 1}
    								{$smarty.const.bid_starts_in}:
							{else}
								{$smarty.const.bid_expires_in}:
							{/if}
							&nbsp;<span id="time{$current_row->rownr}">{$current_row->countdown}</span>

    							</div>
    						{/if}

					</td>
					<td>
						 {if  !$current_row->expired && $current_row->is_my_auction != 1}
						<input type="submit" name="send" value="{$smarty.const.but_send_bid}"  class="back_button" {$disable_bids} />
						{/if}
					</td>
				</tr>		
				</table>
    			</td>
			</tr>

			</table>
		</td>
	</tr>
	</form>