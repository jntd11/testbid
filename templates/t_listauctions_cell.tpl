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
    <tr id="auction_row{$class}">		
{*  Removed (commented) Duplicate coulmn - Jai 091709*}
{*  JaiStartB *}		
  {*    <td id="auction_thumb{$class}"  valign="top"> *}
{* JaiEndB *}
					{*  LDHEndA *}
		<td id="auction_thumb{$class}"  valign="top">
			<a href="{if $task=='mybids'}{$current_row->links.bid_list}{else}{$current_row->links.auctiondetails}{/if}">{$current_row->thumbnail}</a>
		</td>
		<td valign="top" id="auction_cell" >
			<table width="100%">
			<tr>
    			<td colspan="2" valign="top">
    				<div id="auction_title">
    					<a href="{if $task=='mybids'}{$current_row->links.bid_list}{else}{$current_row->links.auctiondetails}{/if}">{$current_row->title}</a>
    					{if $current_row->auction_type eq $smarty.const.AUCTION_TYPE_PRIVATE}
    							<span id="auction_private">{$smarty.const.bid_private}</span>
    					{/if}
    				</div>
    				{if $current_row->vin_number}
    				<div>VIN: {$current_row->vin_number}</div>
    				{/if}
    			</td>
			</tr>
			<tr>
    			<td id="auction_middle" valign="top">
					<div id="auction_description">
					{*  ADDED for Optional Fields - Jai *}
					{*  JaiStartA *}
					{if $smarty.const.bid_opt_allow_category == 1}
						<span id="auction_category">{$smarty.const.bid_category}&nbsp;
							{if $current_row->catname}
								<a href="{$current_row->links.filter_cat}">{$current_row->catname}</a><br/>
							{else}
								&nbsp;-&nbsp;<br/>
							{/if}
						</span>
					{/if}
					{*  JaiEndA *}
						{$current_row->shortdescription}
					</div>
    				<div id="auction_container">
    					<div id="auction_info">
    							{if $current_row->mybid}
    								<span id="auction_price_bold">{$smarty.const.bid_mybid}: {$current_row->mybid|number_format:0}</span><br/>
    							{/if}
							{*  ADDED for Optional Fields - Jai *}
							{*  JaiStartA *}
							{if $smarty.const.bid_opt_allow_auctioneer == 1}
									{$smarty.const.bid_bid_auctioneer}:&nbsp;<a href="{$current_row->links.otherauctions}" alt="{$smarty.const.bid_more_offers_user}">{$current_row->username}</a><br/>
							{/if}
							{*  JaiEndA *}
							{if $current_row->verified_auctioneer}<img src="{$mosConfig_live_site}/components/com_bids/images/verified_1.gif"  id='auction_star' height="16" border="0" onmouseover="overlib('{$smarty.const.bid_user_verified}');" onmouseout="nd();"/>{/if}
							{*  ADDED for Optional Fields - Jai *}
							{*  JaiStartA *}
							{if $smarty.const.bid_opt_allow_rating == 1}
								{$smarty.const.bid_rate_title}:<a href="{$current_row->links.auctioneer_profile}" alt="{$smarty.const._DETAILS_TITLE}"><span id="rating_user" rating="{$current_row->rating_overall}" ></span></a><br/>
							{/if}
							{*  JaiEndA *}
    							{if $current_row->winning_bid}
    								{$smarty.const.bid_winning_bid}: {$current_row->currency_name}&nbsp;{$current_row->winning_bid|number_format:0}
    							{elseif $current_row->highest_bid}
    								{$smarty.const.bid_highest_bid}: {$current_row->currency_name}&nbsp;{$current_row->highest_bid|number_format:0}
    								{if $current_row->nr_bidders}
    									&nbsp;({$smarty.const.bid_bidders}: {$current_row->nr_bidders})
    								{/if}
    							{else}
    								{if $current_row->nr_bidders}
    									<br/>{$smarty.const.bid_bidders}: {$current_row->nr_bidders}
    								{/if}
    							{/if}

    					</div>
    				 </div>
    						{if $current_row->close_offer}
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
    						{elseif  $current_row->expired}
    							<div id="auction_info_bottom">
    						   <font class='expired'>{$smarty.const.bid_expired}</font>
    							</div>
    						{elseif $smarty.const.bid_opt_enable_countdown}
    							<div id="auction_info_bottom">
    							{$smarty.const.bid_expires_in}:&nbsp;<span id="time{$current_row->rownr}">{$current_row->countdown}</span>

    							</div>
    						{/if}
    			</td>
    			<td id="auction_right"  valign="top">
    				<div id="auction_date">
    					<span>{$smarty.const.bid_start_bid_text}</span>&nbsp;
    					<span id="auction_price_bold">
    						{$current_row->currency_name}&nbsp;{$current_row->initial_price|number_format}
    					</span>
    				</div>
				{*  ADDED for Optional Fields - Jai *}
				{*  JaiStartA *}
				{if $smarty.const.bid_opt_allow_bin == 1}
					<div id="auction_price">
						<span>{$smarty.const.bid_bin_text}</span>&nbsp;
						<span id="auction_price_bold">
						{if $current_row->BIN_price gt 0}
							{$current_row->currency_name}&nbsp;{$current_row->BIN_price|number_format:0}
						{else}
							{$smarty.const.bid_no_bin}
						{/if}
						</span>
					</div>
				{/if}
				{*  JaiEndA *}
				{*  ADDED for Optional Fields - Jai *}
				{*  JaiStartA *}
				{if $smarty.const.bid_opt_allow_startdate == 1}
					<div id="auction_date">
						<span>{$smarty.const.bid_start_date} </span>:&nbsp;{$current_row->start_date_text}
					</div>
				{/if}
				{*  JaiEndA *}
    				<div id="auction_price">
    				{if $current_row->end_date}
    					<span>{$smarty.const.bid_end_date}</span>:&nbsp;{$current_row->end_date_text}
    				{/if}
    				</div>
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
        				<span id='new_message'><a href='{$current_row->links.messages}'>
        				{if $current_row->nr_new_messages}
        					<img src="{$mosConfig_live_site}/components/com_bids/images/f_message_1.png" title="{$smarty.const.bid_newmessages}" alt="{$smarty.const.bid_newmessages}" />
        				{else}
        					<img src="{$mosConfig_live_site}/components/com_bids/images/f_message_0.png" title="{$smarty.const.bid_no_new_messages}" alt="{$smarty.const.bid_no_new_messages}" />
        				{/if}
        				</a></span>
        				{if $current_row->is_my_auction!=1 && !$current_row->close_offer}
        				<a href='{$current_row->links.bids}'><img src="{$mosConfig_live_site}/components/com_bids/images/f_bid.gif" title="{$smarty.const.bid_tab_offer_bidnew}" alt="{$smarty.const.bid_tab_offer_bidnew}" /></a>
        				{/if}
    				</div>
    			</td>
			</tr>
			<tr>
    			<td colspan="2" valign="top">
    				<div id="auction_info_bottom">
		{*  ADDED for Optional Fields - Jai *}
		{*  JaiStartB *}
		{if $smarty.const.bid_opt_allow_tag == 1}
				{if $current_row->links.tags !=""}
                				{$smarty.const.bid_tags}:&nbsp;{$current_row->links.tags}
            			{/if}
		{/if}
		{* JaiEndB *}
        				<span id="auction_number">{$smarty.const.bid_auction_number}: {$current_row->auction_nr}</span>
    				</div>
    			</td>
			</tr>
			</table>
		</td>
	</tr>