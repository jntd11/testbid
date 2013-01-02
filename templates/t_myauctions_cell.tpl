{*
    Used to display one Auction Cell in Auctions list

    You can use following variables to display several informations about an auction


*}
{if $current_row->rownr is odd}
	{assign var=class value="1"}
{else}
	{assign var=class value="2"}
{/if}
    <tr id="auction_row{$class}">
		<td id="auction_thumb{$class}"  valign="top">
			  <a class="linkpicture" href="{if $task=='mybids'}{$current_row->links.bid_list}{else}{$current_row->links.auctiondetails}{/if}">{$current_row->gallery}</a>
		</td>
		<td id="auction_cell">
    		<table width="100%">
    		<tr>
        		<td id="auction_middle" valign="top">
        			<div id="auction_title">
        				<a href="{$current_row->links.auctiondetails}">{$current_row->title}</a>
        			</div>
        			<div id="auction_description">
        				{$current_row->shortdescription}
        			</div>
        		<div id="auction_container">
        			<div id="auction_info">
					{*  ADDED for Optional Fields - Jai *}
					{*  JaiStartB *}
					{if $smarty.const.bid_opt_allow_category == 1}
        					<span id="auction_category">{$smarty.const.bid_category}&nbsp;
        						{if $current_row->catname}
        						    <a href="{$current_row->links.filter_cat}">{$current_row->catname}</a><br/>
        						{else}
        						    &nbsp;-&nbsp;<br/>
        						{/if}
        					</span>
         				{/if}
					{* JaiEndB *}
		   				<span id='new_message'><a href='{$current_row->links.messages}'>
            				{if $current_row->nr_new_messages}
            					<img src="{$mosConfig_live_site}/components/com_bids/images/f_message_1.png" title="{$smarty.const.bid_newmessages}" alt="{$smarty.const.bid_newmessages}" /><br/>
            				{else}
            					<img src="{$mosConfig_live_site}/components/com_bids/images/f_message_0.png" title="{$smarty.const.bid_no_new_messages}" alt="{$smarty.const.bid_no_new_messages}" /><br/>
            				{/if}
            				</a></span>
             				{if $current_row->winning_bid}
             				    {$smarty.const.bid_winning_bid} {$current_row->winning_bid}
             				{elseif $current_row->highest_bid}
             				    {$smarty.const.bid_highest_bid} {$current_row->highest_bid}
             				{/if}
             				{if $current_row->nr_bidders}
             				    <br/>{$smarty.const.bid_bidders}: {$current_row->nr_bidders}
             				{/if}

        			</div>
        		 	<div id="auction_buttons">
        				{if $current_row->add_to_watchlist}
        				        <span id='add_to_watchlist'><a href='{$current_row->links.add_to_watchlist}'>{$smarty.const.bid_add_to_watchlist}</a></span>
        				   {elseif $current_row->del_from_watchlist}
            				    <span id='add_to_watchlist'><a href='{$current_row->links.del_from_watchlist}'>{$smarty.const.bid_remove_from_watchlist}</a></span>
        			    {/if}
        	     	</div>
        	     </div>
        		 <div id="auction_info_bottom">
        				{if $current_row->close_offer}
        				    <span class='canceled_on'>
        				    {if $current_row->end_date gt $current_row->closed_date}
        				        {$smarty.const.bid_canceled_on}
        				    {else}
        				        {$smarty.const.bid_closed_on_date}
        				    {/if}:
        				    </span>
        				    {$current_row->closed_date|date_format $smarty.const.bid_opt_date_format}
                        {elseif  $current_row->expired}
        		           <font class='expired'>{$smarty.const.bid_expired}</font><br>
        		        {/if}
		{*  ADDED for Optional Fields - Jai *}
		{*  JaiStartB *}
		{if $smarty.const.bid_opt_allow_tag == 1}
        		        {if $current_row->links.tags}{$smarty.const.bid_tags}:&nbsp;{$current_row->links.tags}{/if}
		{/if}
		{* JaiEndB *}
        	    </div>
        		</td>
        		<td id="auction_right"  valign="top">
        		 	<div id="auction_date">
        				<span>{$smarty.const.bid_start_bid_text}</span>&nbsp;
        				<span id="auction_price_bold">
        				    {$current_row->currency_name}&nbsp;{$current_row->initial_price|number_format:0}
        				</span>
        			</div>
				{*  ADDED for Optional Fields - Jai *}
				{*  JaiStartB *}
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
						{if $current_row->auction_type eq $smarty.const.AUCTION_TYPE_PRIVATE}
						    <br/>{$smarty.const.bid_private}
						{/if}
					</div>
				{/if}
				{* JaiEndB *}
       				{*  ADDED for Optional Fields - Jai *}
				{*  JaiStartB *}
				{if $smarty.const.bid_opt_allow_startdate == 1}
					<div id="auction_date">
					<span>{$smarty.const.bid_start_date} </span>&nbsp;{$current_row->start_date_text}
					</div>
				{/if}
				{*  JaiEndB *}
        		 	<div id="auction_price">
        		 	{if $current_row->end_date}
        				<span>{$smarty.const.bid_end_date}</span>&nbsp;{$current_row->end_date_text}

        				{if $smarty.const.bid_opt_enable_countdown && !$current_row->expired && !$current_row->close_offer}
        				<br/>{$smarty.const.bid_expires_in}:&nbsp;<span id="time{$current_row->rownr}">{$current_row->countdown}</span>
        				{/if}
        			{/if}
        			</div>
        			<div id="auction_info_bottom">
        			    <span id="auction_number">{$smarty.const.bid_auction_number}: {$current_row->auction_nr}</span>
        			    {if $current_row->expired || $current_row->close_offer}
        			    <input type="button" onclick="window.location = '{$current_row->links.republish}';" class="back_button" value="{$smarty.const.bid_img_repub_offer}">
        			    {/if}
              		</div>

        		</td>
        	</tr>
            </table>
		</td>
	</tr>

