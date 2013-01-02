
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
			<form action="index.php" method="post" name="auctionForm" onsubmit="return FormValidate2({$current_row->id});" >
			<input type="hidden" name="islogged" id="islogged" value="{$logged}">
		
		  <input type="hidden" name="option" value="com_bids">
		  <input type="hidden" name="pageId" value="{$current_row->pageId}">
		  <input type="hidden" name="task" value="sendbid">
		  <input type="hidden" name="id" value="{$current_row->id}">
		  <input type="hidden" name="leadbid" id="leadbid{$current_row->id}" value="{$current_row->mine}">
		  <input type="hidden" name="initial_price{$current_row->id}" id="initial_price{$current_row->id}" value="{$current_row->initial_price}">
		  <input type="hidden" name="bin_price{$current_row->id}" id="bin_price{$current_row->id}" value="{$current_row->BIN_price}">
		  <input type="hidden" name="mylastbid{$current_row->id}" id="mylastbid{$current_row->id}" value="{$current_row->bid_price}">
		  <input type="hidden" name="min_increase{$current_row->id}"  id="min_increase{$current_row->id}" value="{$current_row->min_increase}">
		  <input type="hidden" name="start_date{$current_row->id}" id="start_date{$current_row->id}" value="{$current_row->start_date|date_format:"%m/%d/%Y %H:%M"}">
		  <input type="hidden" name="end_date{$current_row->id}" id="end_date{$current_row->id}" value="{$current_row->end_date|date_format:"%m/%d/%Y %H:%M"}">
		  <input type="hidden" name="maxbid{$current_row->id}" id="maxbid{$current_row->id}" value="{$current_row->bid_price}">
         	  <input type="hidden" name="Itemid" value="{$Itemid}">
		  <input type="hidden" name="auction_id" id="auction_id{$current_row->id}" value="{$current_row->id}">
{*  <tr id="auction_row{$class}" style="background:url({$mosConfig_live_site}/components/com_bids/images/auction_bg{$class}.gif) repeat-x;" class="{$class_featured}">*}
	
    <tr id="auction_row{$class}" >
{*  Removed (commented) Duplicate coulmn - Jai 091709*}
{*  JaiStartB *}		
  {*    <td id="auction_thumb{$class}"  valign="top"> *}
{* JaiEndB *}
					{*  LDHEndA *}
		<td id="auction_thumb{$class}_old_1"  valign="top" width="20%">
			<a class="linkpicture" href="{if $task=='mybids'}{$current_row->links.bid_list}{else}{$current_row->links.auctiondetails}{/if}">{$current_row->gallery}</a>
			{if $current_row->link_extern != ""}
			  
				<input onclick="window.open('{$current_row->link_extern}','targetWindow', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=720,height=480')" type="button" class="button art-button" id="refresh" value="{$smarty.const.external_link}">
			{/if}
			<br>
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
							<a class="linkpicture" href="{if $task=='mybids'}{$current_row->links.bid_list}{else}{$current_row->links.auctiondetails}{/if}"><span id="auctitlelabel{$current_row->id}">{$smarty.const.auction_title}</span></a>
							<a class="linkpicture" href="{if $task=='mybids'}{$current_row->links.bid_list}{else}{$current_row->links.auctiondetails}{/if}"><span id="auctitle{$current_row->id}">{$current_row->title}</span></a>
							{if $current_row->auction_type eq $smarty.const.AUCTION_TYPE_PRIVATE}
									<span id="auction_private">{$smarty.const.bid_private}</span>
							{/if}
						</div>
					</td>
					<td width="20%">
						<a class="linkpicture" href="{if $task=='mybids'}{$current_row->links.bid_list}{else}{$current_row->links.auctiondetails}{/if}"><span id="labelshortdesc{$current_row->id}">{$smarty.const.label_short_desc}</span></a>
						<a class="linkpicture" href="{if $task=='mybids'}{$current_row->links.bid_list}{else}{$current_row->links.auctiondetails}{/if}"><span id="shortdesc{$current_row->id}">{$current_row->shortdescription}</span></a>
					</td>
					<td width="45%">
					{* LDHEndE *}
						<a class="linkpicture" href="{if $task=='mybids'}{$current_row->links.bid_list}{else}{$current_row->links.auctiondetails}{/if}">{$smarty.const.label_custom_field1}</a>
						<a class="linkpicture" href="{if $task=='mybids'}{$current_row->links.bid_list}{else}{$current_row->links.auctiondetails}{/if}">{$current_row->custom_fld1}</a>
					</td>
					<td width="35%">
					<div id="auction_date">
						<span>{$smarty.const.bid_bid_price}</span>&nbsp;
						<span id="auction_price_bold">
							{if $current_row->bid_price != 0}
								{$current_row->currency_name}&nbsp;{$current_row->bid_price|number_format:0}
							{/if}
						</span>
					</div>
					<div id="auction_date">
						<span>{$smarty.const.current_bidder}</span>&nbsp;
						<span id="auction_price_bold">
							{$current_row->current_bidder}
						</span>
					</div>

					</td>
				</tr>
				<tr>
	{* JAISTARTJ *}
	{if $optionRowspan == 2}
					<td colspan="3" rowspan="4" valign="top" width="30%">
						<a class="linkpicture" href="{if $task=='mybids'}{$current_row->links.bid_list}{else}{$current_row->links.auctiondetails}{/if}">{$current_row->description}</a>
					</td>
	{else}

					<td>
						<a class="linkpicture" href="{if $task=='mybids'}{$current_row->links.bid_list}{else}{$current_row->links.auctiondetails}{/if}">{$smarty.const.label_custom_field2}</a>
						<a class="linkpicture" href="{if $task=='mybids'}{$current_row->links.bid_list}{else}{$current_row->links.auctiondetails}{/if}">{$current_row->custom_fld2}</a>
					</td>
					<td>
						<a class="linkpicture" href="{if $task=='mybids'}{$current_row->links.bid_list}{else}{$current_row->links.auctiondetails}{/if}">{$smarty.const.label_custom_field3}</a>
						<a class="linkpicture" href="{if $task=='mybids'}{$current_row->links.bid_list}{else}{$current_row->links.auctiondetails}{/if}">{$current_row->custom_fld3}</a>
					</td>
					<td>
						<a class="linkpicture" href="{if $task=='mybids'}{$current_row->links.bid_list}{else}{$current_row->links.auctiondetails}{/if}">{$smarty.const.label_custom_field4}</a>
						<a class="linkpicture" href="{if $task=='mybids'}{$current_row->links.bid_list}{else}{$current_row->links.auctiondetails}{/if}">{$current_row->custom_fld4}</a>
					</td>
	{/if}
	{* JAIENDJ *}

					<td rowspan="4" valign="top">
					{if $smarty.const.next_bid_allow == 1}
					
						<span>{$smarty.const.next_bid}</span>&nbsp;
						<span id="auction_price_bold">
							{if $current_row->bid_next != "SOLD" && $current_row->bid_next != "ENDED" && $current_row->bid_next != "PENDING"}
								{$current_row->currency_name}&nbsp;{$current_row->bid_next|number_format:0}
							{else}
								{$current_row->bid_next}
							{/if}
						</span>
					</div>
					
					{/if}

					{if $current_row->add_to_watchlist}
        					&nbsp;&nbsp;&nbsp;&nbsp;<span id='add_to_watchlist'><a href='{$current_row->links.add_to_watchlist}'>
        						<img src="{$mosConfig_live_site}/components/com_bids/images/f_watchlist_1.jpg" title="{$smarty.const.bid_add_to_watchlist}" alt="{$smarty.const.bid_add_to_watchlist}"/>
        					</a></span>
        				   {elseif $current_row->del_from_watchlist}
        					<span id='add_to_watchlist'><a href='{$current_row->links.del_from_watchlist}'>
        						<img src="{$mosConfig_live_site}/components/com_bids/images/f_watchlist_0.jpg" title="{$smarty.const.bid_remove_from_watchlist}" alt="{$smarty.const.bid_remove_from_watchlist}"/>
        					</a></span>
        			{/if}
						<input type="hidden" name="outbid{$current_row->id}" id="outbid{$current_row->id}" value="{$current_row->outbid}">
						{if $current_row->proxyplus_price != 0}
							<div id="" class="spacing-top">
  							 <input type="hidden" name="maxproxyplus{$current_row->id}" id="maxproxyplus{$current_row->id}" 
							 value="{if $current_row->proxyplus_price != ''}{$current_row->proxyplus_price}{else}0{/if}">
							 
							{$smarty.const.proxyplusLabel} {$current_row->currency_name}&nbsp;{$current_row->proxyplus_price|number_format:0}
							{if $current_row->outbid == 1} OUTBID {/if}
							</div>
						{else}
							 <input type="hidden" name="maxproxyplus{$current_row->id}" id="maxproxyplus{$current_row->id}" value="0">
						{/if}
					{if  !$current_row->expired && $smarty.const.bid_opt_allow_proxy == 1 && $current_row->is_my_auction != 1}
					   <div class="spacing-top">
							<input type="hidden" name="prxo{$current_row->id}" id="prxo{$current_row->id}" value="0">
							<input {$disable_bids} type="checkbox" class="inputbox" name="proxy" id="proxy" value="1" onclick="ProxyClick1(this,{$current_row->id});">&nbsp;{$smarty.const.bid_proxy}
							{infobullet text=$smarty.const.bid_help_proxy_bidding}
					   </div>
					{/if}
					{if $smarty.const.bid_allow == 1}
					   <div id="auction_date" class="spacing-top">
							{if !$current_row->expired && $current_row->is_my_auction != 1}
							<span id="bid{$current_row->id}" >
							{if $auction->highest_bid gt 0}
								{$smarty.const.bid_my_bids}
							{else}
								{$smarty.const.bid_my_bids}
							{/if}
							</span>
						{$current_row->currency_name}&nbsp;<input name="amount{$current_row->id}" autocomplete="off" id="amount{$current_row->id}" class="inputbox" onkeypress="return onlyNumbers(event);" type="text" value="" size="20" on alt="bid" {$disable_bids}>&nbsp;
						{/if}
					  </div>
					{/if}
					{if  !$current_row->expired && $current_row->is_my_auction != 1 && $smarty.const.bid_allow == 1}
						<div class="spacing-top">
						<input type="submit" name="send" value="{$smarty.const.but_send_bid}"  class="button art-button" {$disable_bids}/>
						</div>
					{/if}
					{if  $current_row->expired}
    						<div id="auction_info_bottom" class="spacing-top">
    						   <font class='expired'>{$smarty.const.bid_expired}</font>
    							</div>
    				{elseif $smarty.const.bid_opt_enable_countdown}
    							<div id="auction_info_bottom" class="spacing-top">
							{if $current_row->countdowntype == 1}
    							 {$smarty.const.bid_starts_in}:
							{else}
								{$smarty.const.bid_expires_in}:
							{/if}
							&nbsp;<span id="time{$current_row->rownr}">{$current_row->countdown}</span>

    						</div>
    				{/if}

					</td>
					
				</tr>
					<tr>
	{* JAISTARTJ *}
	{if $optionRowspan == 1}
					<td colspan="3" rowspan="3" valign="top" style="padding-right: 5px;">
						<a class="linkpicture" href="{if $task=='mybids'}{$current_row->links.bid_list}{else}{$current_row->links.auctiondetails}{/if}">{$current_row->description}</a>
					</td>
	{elseif $optionRowspan != 2}

					<td>
							{*  ADDED for Optional Fields - Jai *}
							{*  JaiStartA *}
							{if $smarty.const.bid_opt_allow_auctioneer == 1}
								{$smarty.const.bid_bid_seller}:&nbsp;<a href="{$current_row->links.otherauctions}" alt="{$smarty.const.bid_more_offers_user}">{$current_row->username}</a><br/>
							{/if}
					</td>
					<td>
						<a class="linkpicture" href="{if $task=='mybids'}{$current_row->links.bid_list}{else}{$current_row->links.auctiondetails}{/if}">{$smarty.const.label_custom_field5}</a>
						<a class="linkpicture" href="{if $task=='mybids'}{$current_row->links.bid_list}{else}{$current_row->links.auctiondetails}{/if}">{$current_row->custom_fld5}</a>
					</td>
					<td>
						&nbsp;
					</td>
	{/if}
	{* JAIENDJ *}
				</tr>		
				<tr>
	{* JAISTARTJ *}
	{if $optionRowspan != 1 && $optionRowspan != 2}
					<td colspan="3" rowspan="2" style="padding-right: 5px;">
						<a class="linkpicture" href="{if $task=='mybids'}{$current_row->links.bid_list}{else}{$current_row->links.auctiondetails}{/if}">{$current_row->description}</a>
					</td>
	{/if}
	{* JAIENDJ *}
					
				</tr>		
				</table>
    			</td>
			</tr>

			</table>
		</td>
	</tr>
	</form>