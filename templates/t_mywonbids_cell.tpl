{*
    Used to display one Auction Cell in Auctions list


*}
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

    <tr id="auction_row{$class}" class="{$class_featured}">
		<td id="auction_thumb{$class}"  valign="top">
			  <a href="{$current_row->links.auctiondetails}">{$current_row->gallery}</a>
		</td>
		<td valign="top" id="auction_cell" >
			<table width="100%">
			<tr>
    			<td colspan="2" valign="top">
    				<div id="auction_title">
    					<a href="{$current_row->links.auctiondetails}">{$current_row->title}</a>
    					{if $current_row->auction_type eq $smarty.const.AUCTION_TYPE_PRIVATE}
    							<span id="auction_private">{$smarty.const.bid_private}</span>
    					{/if}
    				</div>
				{*  ADDED for Optional Fields - Jai *}
				{*  JaiStartB *}
				{if $smarty.const.bid_opt_allow_startdate == 1}

    				<div id="auction_date">
    					<span>{$smarty.const.bid_start_date} </span>:&nbsp;{$current_row->start_date_text}
    				</div>
				{/if}
				{*  JaiEndB *}
    			</td>
			</tr>
			<tr>
    			<td id="auction_middle" valign="top" colspan="2">
    				<div id="auction_container">
    					<div id="auction_info">
							{*  ADDED for Optional Fields - Jai *}
							{*  JaiStartB *}
							{if $smarty.const.bid_opt_allow_auctioneer == 1}
	    							{$smarty.const.bid_bid_auctioneer}:&nbsp;<a href="{$current_row->links.otherauctions}" alt="{$smarty.const.bid_more_offers_user}">{$current_row->username}</a>
	    							{if $current_row->verified_auctioneer}<img src="{$mosConfig_live_site}/components/com_bids/images/verified_1.gif"  id='auction_star' height="16" border="0" onmouseover="overlib('{$smarty.const.bid_user_verified}');" onmouseout="nd();"/>{/if}
							{/if}
							{*  JaiEndB *}
							{*  ADDED for Optional Fields - Jai *}
							{*  JaiStartB *}
							{if $smarty.const.bid_opt_allow_rating == 1}
								{$smarty.const.bid_rate_title}:<a href="{$current_row->links.auctioneer_profile}" alt="{$smarty.const._DETAILS_TITLE}"><span id="rating_user" rating="{$current_row->rating_overall}"></span></a>
								{if $current_row->must_rate}
								    &nbsp;<a href="{$current_row->links.rate_auction}" alt="{$smarty.const.bid_rate}">{$smarty.const.bid_rate}</a>
								    {/if}
							{/if}
							{*  JaiEndB *}
    							{if $current_row->winning_bid}
    								<br/><span id="auction_price_bold">{$smarty.const.bid_winning_bid}: {$current_row->currency_name}&nbsp;{$current_row->winning_bid|number_format:0}</span>
    							{/if}
								{if $current_row->nr_bidders}
    								<br/>{$smarty.const.bid_bidders}: {$current_row->nr_bidders}
    							{/if}
    					</div>
    					{if $current_row->paypalemail}
    					<div id="paypal_button">
                            <form name='paypalForm' action="https://www.paypal.com/cgi-bin/webscr" method="post" name="paypal">
                    		<input type="hidden" name="cmd" value="_xclick">
                    		<input type="hidden" name="business" value="{$current_row->paypalemail}">
                    		<input type="hidden" name="item_name" value="{$current_row->title}">
                    		<input type="hidden" name="item_number" value="{$current_row->id}">
                    		<input type="hidden" name="invoice" value="{$current_row->auction_nr}">
                    		<input type="hidden" name="amount" value="{$current_row->total_price|number_format:0}">
                    		<input type="hidden" name="quantity" value="{$current_row->nr_items}">
                    		<input type="hidden" name="return" value="{$current_row->links.auctiondetails}">
                    		<input type="hidden" name="tax" value="0" />
                    		<input type="hidden" name="rm" value="2" />
                    		<input type="hidden" name="no_note" value="1" />
                    		<input type="hidden" name="no_shipping" value="1" />
                    		<input type="hidden" name="currency_code" value="{$current_row->currency_name}">
                    		<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but06.gif" name="submit" alt="{$smarty.const.bid_paypal_buynow}" style="margin-left: 50px;">

                            </form>
    					</div>
    					{/if}
    				 </div>
					<div id="auction_info_bottom">
					<span class='canceled_on'>
					{if $current_row->end_date gt $current_row->closed_date && !$current_row->winning_bid}
						{$smarty.const.bid_canceled_on}
					{else}
						{$smarty.const.bid_closed_on_date}
					{/if}:
					</span>
					{$current_row->closed_date|date_format $smarty.const.bid_opt_date_format}
					</div>
    			</td>
			</tr>
			<tr>
    			<td colspan="2" valign="top">
    				<div id="auction_info_bottom">
    				<span id='new_message'><a href='{$current_row->links.messages}'>
    				{if $current_row->nr_new_messages}
    					<img src="{$mosConfig_live_site}/components/com_bids/images/f_message_1.png" title="{$smarty.const.bid_newmessages}" alt="{$smarty.const.bid_newmessages}" />
    				{else}
    					<img src="{$mosConfig_live_site}/components/com_bids/images/f_message_0.png" title="{$smarty.const.bid_no_new_messages}" alt="{$smarty.const.bid_no_new_messages}" />
    				{/if}
    				</a></span>
    				<span id="auction_number">{$smarty.const.bid_auction_number}: {$current_row->auction_nr}</span>
    				</div>
    			</td>
			</tr>
			{*  ADDED for Optional Fields - Jai *}
			{*  JaiStartB *}
			{if $smarty.const.bid_opt_allow_tag == 1}
				{if $current_row->links.tags}
				<tr>
				<td colspan="2" valign="top">
					{$smarty.const.bid_tags}:&nbsp;{$current_row->links.tags}
				</td>
				</tr>
				{/if}
			{/if}
			{*  ADDED for Optional Fields - Jai *}
			{*  JaiStartB *}
			</table>
		</td>
	</tr>


