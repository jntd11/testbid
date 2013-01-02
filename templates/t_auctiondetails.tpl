{* Include Overlib initialisation *}
{include file='t_overlib.tpl'}
{* Include Validation script *}
{include file='t_javascript_language.tpl'}
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/ratings.js"></script>
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/ui/jquery.js"></script>
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/ui/interface.js"></script>
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/ui/jquery.simplemodal.js"></script>
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/ui/jqueryForm.js"></script>
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/yui/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript">
   jQuery.noConflict();
 </script>
{* Include Tabbing Scripts *}
{createtab}
{* set the custom Auction CSS & Template CSS - after tabbing so we replace css in tabbed output*}
{set_css}

<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/ratings.js"></script>
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/auctions.js"></script>
<style type="text/css">
{literal}
 .tab-page{ z-index:0 !important;}
{/literal}
</style>

<table width="100%"  class="" >
<input type="hidden" name="servertime" id="servertime" value="{$smarty.now|date_format:'%m/%d/%Y %k:%M'}">
<tr>
<td >
{if $auction->links.auctiondetails_prev == ""}
<input type="button" class="button art-button" value="Previous" disabled="disabled">
{else}
<a href="{$auction->links.auctiondetails_prev}" onClick="window.location.href='{$auction->links.auctiondetails_prev}';"  style="text-decoration: none;"><input type="button" class="button art-button" value="Previous"></a>
{/if}
{if $auction->links.auctiondetails_next == ""}
<input type="button" class="button art-button" value="Next" disabled="disabled">
{else}
<a href="{$auction->links.auctiondetails_next}" onClick="window.location.href='{$auction->links.auctiondetails_next}';" style="text-decoration: none;"><input type="button" class="button art-button" value="Next"></a>
{/if}


</td>
</tr>

<tr bgcolor="#efefef">
<td>

   {include file='t_auctiondetails_plugins.tpl'}
   <table style="background: #efefef;font-weight: bold;background: #f0f0f0;padding: 4px;" width="100%" cellspacing="3" cellpadding="3" border="0">
   <tr>
   <td colspan="4">
	{$auction->gallery}
   </td>
   </tr>
   <tr>
		<td colspan="4">
	<table>
		{if $auction->is_my_auction}
		<tr>
			 <td align="left" colspan="3">
			    {if $auction->link_extern != ""}
				<input onclick="window.open('{$auction->link_extern}','targetWindow', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=720,height=480')" type="button" class="button art-button" id="refresh" value="{$smarty.const.external_link}">
				{/if}
				{if $auction->close_offer == 1}
					<input type="button" onclick="window.location = '{$auction->links.republish}';" class="back_button" value="{$smarty.const.bid_img_repub_offer}">
					{if $auction->is_my_auction && !$auction->winner_id}
    					<input type="button" value="{$smarty.const.bid_img_cancel_offer}"  onclick="if(confirm('{$smarty.const.bid_confirm_cancel_auction}')) window.open('{$auction->links.cancel}','_parent');" class="back_button" />
					{/if}

				{else}
					<input type="button" onclick="window.location = '{$auction->links.edit}';" class="back_button" value="{$smarty.const.bid_img_edit_offer}">
					<input type="button" value="{$smarty.const.bid_img_cancel_offer}"  onclick="if(confirm('{$smarty.const.bid_confirm_cancel_auction}')) window.open('{$auction->links.cancel}','_parent');" class="back_button" />
				{/if}
			 </td>
		</tr>
		{elseif $auction->link_extern != ""}
		   <tr>
			 <td colspan="3"><input onclick="window.open('{$auction->link_extern}','targetWindow', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=720,height=480')" type="button" class="button art-button" id="refresh" value="{$smarty.const.external_link}"></td>
		   </tr>
		{/if}
			

			{if $auction->is_my_auction && $auction->close_offer==1 && !$auction->winner_id && !empty($bid_list)}
    		   <tr width="100%">
    				 <td>
                    <img style="margin:0px;height:20px" src="{$smarty.const.BIDS_COMPONENT}/images/choose_winner.png" border="0" alt="{$smarty.const.bid_choose_a_winner}" title="{$smarty.const.bid_choose_a_winner}"/>
    				 <font color="Red">{$smarty.const.bid_choose_a_winner}</font></td>
    			</tr>
			{/if}
 </table>
			{if $auction->paypalemail && $auction->i_am_winner}
			<div id="paypal_button">
				{if $use_sandbox=="1"}
                <form name='paypalForm' action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" name="paypal">
                {else}
                <form name='paypalForm' action="https://www.paypal.com/cgi-bin/webscr" method="post" name="paypal">
                {/if}
        		<input type="hidden" name="cmd" value="_xclick">
        		<input type="hidden" name="business" value="{$auction->paypalemail}">
        		<input type="hidden" name="item_name" value="{$auction->title}">
        		<input type="hidden" name="item_number" value="{$auction->id}">
        		<input type="hidden" name="invoice" value="{$auction->auction_nr}">
        		<input type="hidden" name="amount" value="{$auction->total_price|number_format:0}">
        		<input type="hidden" name="quantity" value="{$auction->nr_items}">
        		<input type="hidden" name="return" value="{$auction->links.bids}">
        		<input type="hidden" name="tax" value="0" />
        		<input type="hidden" name="rm" value="2" />
        		<input type="hidden" name="no_note" value="1" />
        		<input type="hidden" name="no_shipping" value="1" />
        		<input type="hidden" name="currency_code" value="{$auction->currency_name}">
        		<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but06.gif" name="submit" alt="{$smarty.const.bid_paypal_buynow}" style="margin-left: 50px;">

                </form>
			</div>
			{/if}
		</td>
	</tr>
	<tr >
	 {* column 1 *}
	  <td width="75%" valign="top" align="left">
		<table>
		<tr>
					{* JaiStartD *}
				 <td class="editbidtitle" width="25%"><span id="auctitlelabel">{$smarty.const.auction_title}</span> <span id="auctitle"><b>{$auction->title}</span></b>
				 {if $auction->published == 0}
					<br/><b>{$smarty.const.bid_unpublished}</b>
				 {/if}
				</td>
				 <td width="25%"><span id="labelshortdesc">{$smarty.const.label_short_desc}</span> <span id="shortdesc">{$auction->shortdescription}</span></td>
				  <td width="25%">{$smarty.const.label_custom_field1} {$auction->custom_fld1}</td>
			{* JaiEndD *}
		</tr>
		<tr>
			{* JaiStartD *} 
			{if $smarty.const.bid_opt_allow_category == 1}
				<td align="left" >{$smarty.const.bid_category}:
				{if $auction->catname}<a href="{$auction->links.filter_cat}">{$auction->catname}</a>
				{else}&nbsp;-&nbsp;{/if}</td>
			{/if}
			{* JaiEndD *}

	 {* column 2 *}
	{* JAISTARTJ *}
	{if $optionRowspan != 2}
				 <td >{$smarty.const.label_custom_field2} {$auction->custom_fld2}</td>
				 <td >{$smarty.const.label_custom_field3} {$auction->custom_fld3}</td> 
				 <td >{$smarty.const.label_custom_field4} {$auction->custom_fld4}</td>
	{/if}
	     </tr>
	{* JAISTARTJ *}
	{if $optionRowspan == 0}
			<tr>
				 <td >	  
				{if $smarty.const.bid_opt_allow_auctioneer == 1}
				{$smarty.const.bid_user}:
				    <a href="{$auction->links.auctioneer_profile}">{$auctioneer->username}</a>
				{/if}
				    {if $auction->verified_auctioneer}<img src="{$mosConfig_live_site}/components/com_bids/images/verified_1.gif"  id='auction_star' height="16" border="0" onmouseover="overlib('{$smarty.const.bid_user_verified}');" onmouseout="nd();"/>{/if}
				</td>
				 <td >{$smarty.const.label_custom_field5} {$auction->custom_fld5}</td>
				 <td>&nbsp;</td>
			 </tr>
	{/if}
			<tr>
				  <td valign="top" colspan="3">{$auction->description}</td>
			</tr>

			{if $auction->BIN_price gt 0}
			<tr>
			 <td >{$smarty.const.bid_binprice}</td>
			 <td >{$auction->currency_name}&nbsp;{$auction->BIN_price|number_format:0}&nbsp;</td>
			</tr>
			{/if}

			{if $smarty.const.bid_opt_allow_rating == 1}
        			<tr>
        				<td valign="top">{$smarty.const.bid_rate_title}:
        				<a href="{$auction->links.auctioneer_profile}" alt="{$smarty.const._DETAILS_TITLE}">
        				<span id="rating_user" rating="{$auction->rating_overall}"></span></a>
        				&nbsp;<a href="{$auction->links.otherauctions}">{$smarty.const.bid_auctions_by_user}</a>
        				</td>
        				<td valign="top">{$smarty.const.bid_registration_date}:{printdate date=$auctioneer->registerDate use_hour=0}</td>
        			</tr>
			{/if}
			</tr>
			{if $smarty.const.bid_opt_allow_payment == 1}
			<tr>
			 <td valign="top" colspan="2">{$smarty.const.bid_payment}:&nbsp;<span>{$auction->payment_name}</span>
			 {if $auction->paypalemail}
			     <img src="https://www.paypal.com/en_US/i/btn/x-click-but05.gif" border="0">
			 {/if}
			 </td>
			</tr>
			{/if}
			{if $smarty.const.bid_opt_allow_shipment == 1}
			<tr>
			 <td valign ="top" colspan="2">{$smarty.const.bid_shipment}:&nbsp;<span>
			 {if $auction->shipment_info}{$auction->shipment_info}{else}-{/if}</span></td>
			</tr>
			{/if}
			{if $smarty.const.bid_opt_allow_shipmentprice == 1}
			<tr>
			 <td valign ="top" colspan="2">{$smarty.const.bid_shipment_price}:&nbsp;<span>
			 {if $auction->shipment_price}{$auction->currency_name}&nbsp;{$auction->shipment_price|number_format:0}{else}-{/if}</span></td>
			</tr>
			{/if}

		   </table>
	  </td>
	  {* JaiStartD *}
	  {* Dummy Column *}
          	<td width="2%" valign="top">&nbsp;</td> 
	  {* 3rd Column for New method*}
		<td width="30%" valign="top">
		  <table width="100%" valign="top">
			{if $auction->is_my_auction!=1 && !$auction->close_offer}
			<tr>
			<td colspan="2">
				<table width="100%">
				<tr>
				<td>
					{if $auction->add_to_watchlist}
						<span id='add_to_watchlist'>
						<a href='{$auction->links.add_to_watchlist}'>
							<img src="{$mosConfig_live_site}/components/com_bids/images/f_watchlist_1.jpg" title="{$smarty.const.bid_add_to_watchlist}" alt="{$smarty.const.bid_add_to_watchlist}"/>
						</a>
						<a href='{$auction->links.add_to_watchlist}'>
							Add to Watchlist
						</a>&nbsp;
						</span>
					   {elseif $auction->del_from_watchlist}
						<span id='add_to_watchlist'><a href='{$auction->links.del_from_watchlist}'>
							<img src="{$mosConfig_live_site}/components/com_bids/images/f_watchlist_0.jpg" title="{$smarty.const.bid_remove_from_watchlist}" alt="{$smarty.const.bid_remove_from_watchlist}"/>
						</a>
					   	<a href='{$auction->links.del_from_watchlist}'>
							Remove from Watchlist
						</a>&nbsp;
						</span>
					{/if}
				  </td>
				  </tr>
				</table>
			</td>
			</tr>
			{/if}
		 <tr>
		 <td colspan="2">	   
			{if $auction->is_my_auction!=1 && $auction->close_offer!=1 && !$auction->expired}
			   {* ensure it is not my auction and the auction still runs*}
			   {* Added on 8/21/2010 to allow bidding for proxy bids *}
			   {if !$is_logged_in && false}
				{assign var="disable_bids" value="disabled"}
				<h2><strong>{$smarty.const.bid_login_to_bid}</strong></h2>
			   {/if}
		</td>
		</tr>
		<tr>
		  <form action="index.php" method="post" name="auctionForm" onsubmit="return FormValidate(this);">
		  {if !$is_logged_in}
			<input type="hidden" name="islogged" id="islogged" value="0">
		  {else}
			<input type="hidden" name="islogged" id="islogged" value="1">
		  {/if}
		  <input type="hidden" name="option" value="com_bids">
		  <input type="hidden" name="page" value="det">
		  <input type="hidden" name="task" value="sendbid">
		  <input type="hidden" name="id" value="{$auction->id}">
		  <input type="hidden" name="initial_price" value="{$auction->initial_price}">
		  <input type="hidden" name="bin_price" value="{$auction->BIN_price}">
		  <input type="hidden" name="mylastbid" value="{$auction->mybid->bid_price}">
		  <input type="hidden" name="min_increase" value="{$auction->min_increase}">
		  <input type="hidden" name="Itemid" value="{$Itemid}">
		  <input type="hidden" name="maxbid" value="{$auction->highest_bid}">
		  <input type="hidden" name="prxo" id="prxo" value="0">
		  <input type="hidden" name="leadbid" id="leadbid" value="{$auction->mine}">
		   <td colspan=2 id="proxy_price" style="display:none;">
					<input type="text" class="inputbox" name="max_proxy_price" value="" {$disable_bids}>&nbsp;{$smarty.const.bid_maxpp_text}
			</td>
		 </tr>
		{if $auction->highest_bid gt 0}
		<tr>
		 <td width="60%" align="left">{$smarty.const.bid_bid_price}:&nbsp; {$auction->currency_name}&nbsp;{$auction->highest_bid|number_format:0}</td>
		 <td width="40%" align="left">{$smarty.const.current_bidder} &nbsp; {$auction->winning_user}</td>
		</tr>
		{/if}
        {if $smarty.const.next_bid_allow == 1}
		{if $auction->highest_bid gt 0}
		<tr>
			<td colspan="2">{$smarty.const.next_bid}&nbsp; {$auction->currency_name}&nbsp;{$auction->highest_bid+$auction->min_increase|number_format:0}</td>
		</tr>
		{else}
		<tr>
			<td colspan="2">{$smarty.const.next_bid}&nbsp; {$auction->currency_name}&nbsp;{$auction->initial_price|number_format:0}</td>
		</tr>
		{/if}
		{/if}
		{if $auction->proxy_price > 0}
		<tr>
		<td colspan="2">{$smarty.const.proxyLabel} {$auction->currency_name}&nbsp;{$auction->proxy_price|number_format:0}
											{if $auction->proxy_price < $auction->highest_bid} OUTBID {/if}
	
		</td>
		</tr>
		{/if}
		<input type="hidden" name="outbid" id="outbid" value="{if $auction->proxyplus_price < $auction->highest_bid}1{/if}"> 
		<input type="hidden" name="maxproxyplus" id="maxproxyplus" value="{if $auction->proxyplus_price != ''}{$auction->proxyplus_price}{else}0{/if}">
		{if $auction->proxyplus_price > 0}
		<tr>
		<td colspan="2">
		
  		
		{$smarty.const.proxyplusLabel} {$auction->currency_name}&nbsp;{$auction->proxyplus_price|number_format:0}
													{if $auction->proxyplus_price < $auction->highest_bid} OUTBID {/if}
		</td>
		</tr>
		{/if}
		 {if $auction->auction_type!=AUCTION_TYPE_PRIVATE && $smarty.const.bid_opt_allow_proxy == 1}
		 <tr>
		 <td  colspan="2">
			<input {$disable_bids} type="checkbox" class="inputbox" name="proxy" id="proxy" value="1" onclick="ProxyClick(this);">&nbsp;{$smarty.const.bid_proxy}
			{infobullet text=$smarty.const.bid_help_proxy_bidding}
		 </td>
		 </tr>
		 {/if}
		 <tr>
		 <td colspan="2">
		   {if $smarty.const.bid_allow == 1}
			<span id="bid" >
			{if $auction->highest_bid gt 0}
				{$smarty.const.bid_my_bids}
			{else}
				{$smarty.const.bid_my_bids}
			{/if}
			</span>
			{if !$auction->expired}
				{$auction->currency_name}&nbsp;<input id="amount" name="amount" class="inputbox" autocomplete="off" type="text" value="" onkeypress="return onlyNumbers(event);"   size="20" alt="bid" {$disable_bids}>&nbsp;
			{/if}
		   {/if}
		  </td>
		 </tr>

		  <tr><td colspan="2">
		  {if !$auction->expired && $smarty.const.bid_allow == 1}
			<input type="submit" name="send" value="{$smarty.const.but_send_bid}" class="button art-button" {$disable_bids} />
		  {/if}
		  </td>
		 </tr>
		 {if $terms_and_conditions && 0}
			<tr>
			 <td colspan="2"><input type="checkbox" class="inputbox" name="agreement" value="1"  {$disable_bids} />
			  <a href="javascript: void(0);" onclick="window.open('{$auction->links.terms}','messwindow','location=1,status=1,scrollbars=1,width=500,height=500')" id="auction_category">
			  </a>
			 </td>
			</tr>
		  {/if}
		 </form>
	   {/if}
		</td>
		 </tr>
			{* JaiStartD *}
		
			{if $smarty.const.bid_opt_allow_startdate == 1 || $auction->isValidateDate == 1}
			<tr style="display:none">
				<td>{$smarty.const.bid_start_date}</td>
				<td id="startdate">{$auction->start_date_text}</td>
			</tr>
			{/if}
			{* JaiEndD *}

			<tr  style="display:none">
			 <td >{$smarty.const.bid_expired_date}</td>
			 <td >{$auction->end_date_text}</td>
			</tr>
			<tr>
				<td>				
				{if $auction->countdowntype == 1}
    								{$smarty.const.bid_starts_in}:
							{else}
								{$smarty.const.bid_expires_in}:
				{/if}
				</td>
				<td>
			{if $smarty.const.bid_opt_enable_countdown && !$auction->expired && !$auction->close_offer}
        				<span id="time1">{$auction->countdown}</span>
			{elseif  $auction->expired}
					<span id=""><font class='expired'>{$smarty.const.bid_expired}</font></span>
        	{/if}
        		</td>
			</tr>
			{if $auction->nr_bidders != null}
				{if $smarty.const.bid_opt_show_bidders == 0}
				<tr>
					<td>{$smarty.const.bid_no_bidders}</td>
					<td>{$auction->nr_bidders}</td>
				</tr>
				{elseif $auction->is_my_auction == 1}
					<tr>
						<td>{$smarty.const.bid_no_bidders}</td>
						<td>{$auction->nr_bidders}</td>
					</tr>
				{/if}
			{/if}
		</table>
	   </td>
	  {* JaiEndD *}
	</tr>
   </table>
</td>
</tr>
<tr>
<td >
{if $auction->links.auctiondetails_prev == ""}
<input type="button" class="button art-button" value="Previous" disabled="disabled">
{else}
<a href="{$auction->links.auctiondetails_prev}" onClick="window.location.href='{$auction->links.auctiondetails_prev}';"  style="text-decoration: none;"><input type="button" class="button art-button" value="Previous"></a>
{/if}
{if $auction->links.auctiondetails_next == ""}
<input type="button" class="button art-button" value="Next" disabled="disabled">
{else}
<a href="{$auction->links.auctiondetails_next}" onClick="window.location.href='{$auction->links.auctiondetails_next}';" style="text-decoration: none;"><input type="button" class="button art-button" value="Next"></a>
{/if}


</td>
</tr>
{*JaiStartN*}

{* -------------------  TABBING PART ---------------------*}
<tr>
<td colspan="2">
	   {startpane id="content-pane" usecookies=0}
	{* BIN tab *}
	   {if $auction->BIN_price > 0 && $auction->is_my_auction!=1 && $auction->close_offer!=1 && !$auction->expired}
		   {* ensure it is not my auction and the auction still runs*}
		   {starttab paneid="tab3" text=$smarty.const.bid_tab_offer_bin}

		   {if !$is_logged_in}
			{assign var="disable_bids" value="disabled"}
			<h2><strong>{$smarty.const.bid_login_to_bid}</strong></h2>
		   {/if}
		   <table>
		   <tr>
			  <td>
			  <input type="button" class="back_button" onclick="MakeBinBid('{$auction->links.bin}');"
				value="{$smarty.const.bid_buy_it_now}: {$auction->currency_name}&nbsp;{$auction->BIN_price|number_format:0}"
			    name="bin_button" {$disable_bids} />
			  </td>
		   </tr>
		   </table>
		 {endtab}
	   {/if}
	{* existing Bids Tab *}
	{if $is_logged_in}
	  {if $smarty.const.bid_opt_show_list == 0 || $auction->is_my_auction == 1}
	    {starttab paneid="tab4" text=$smarty.const.bid_tab_offer_list}
		<table width="100%">
		<tbody>
		 <tr>
		  <th class="auction_bids_list" width="5%">{$smarty.const.bid_no_short}</th>
		  <th class="auction_bids_list" width="20%">{$smarty.const.bid_date}</th>
		  <th class="auction_bids_list" width="*%">{$smarty.const.bid_username}</th>
		  <th class="auction_bids_list" width="20%">{$smarty.const.bid_bided_amount}</th>
		  {if $auction->is_my_auction && $auction->close_offer !=1 && $auction->automatic != 1}
			  <th class="auction_bids_list" width="10%">&nbsp;</th>
		  {/if}
		  {if $auction->must_rate && $smarty.const.bid_opt_allow_rating == 1}
				<th class="auction_bids_list" width="20%">&nbsp;</th>
		  {/if}
		  {if $auction->my_proxy_bid}
			  <th class="auction_bids_list" width="20%">{$smarty.const.bid_my_proxy}</th>
		  {/if}
		</tr>
		{section name=bids loop=$bid_list}
			{assign var="tr_class" value=cycle('auction_bids_list1','auction_bids_list2')}
			{if $bid_list[bids]->userid==$userid}
				{assign var="tr_class" value=cycle('auction_bids_mybid1','auction_bids_mybid2')}
				<a name='mybid' id='mybid'></a>
			{/if}
			{if $bid_list[bids]->accept}
				{assign var="tr_class" value="auction_winner"}
			{/if}
			<tr class="{$tr_class}">
			  <td>{$smarty.section.bids.rownum}</td>
			  <td>{printdate date=$bid_list[bids]->modified}</td>
			  <td>
			     <a href="index.php?option=com_bids&task=ViewDetails&id={$bid_list[bids]->userid}&Itemid={$Itemid}"  onmouseover="overlib('{$smarty.const.bid_view_user_profile}');" onmouseout="nd();">{$bid_list[bids]->username}</a>
            	{if $auction->is_my_auction && $smarty.const.bid_opt_allow_messages}
                    <a href="javascript:void(0);" id="sendm" onclick="SendMessage(this,0,{$bid_list[bids]->userid},'{$bid_list[bids]->username}');">({$smarty.const.but_send_message})</a>
            	{/if}
			  </td>
			  <td>{$auction->currency_name}&nbsp;{$bid_list[bids]->bid_price|number_format:0}
					{if $auction->reserve_price>0 && $auction->is_my_auction}
					   {if $auction->reserve_price>$bid_list[bids]->bid_price}
					       <img style="margin:0px;" src="{$smarty.const.BIDS_COMPONENT}/images/reserve_price_not_ok.png" border="0" alt="{$smarty.const.bid_reserve_not_met}" title="{$smarty.const.bid_reserve_not_met}"/>
					   {else}
					       <img style="margin:0px;" src="{$smarty.const.BIDS_COMPONENT}/images/reserve_price_ok.png" border="0"  alt="{$smarty.const.bid_reserve_met}" title="{$smarty.const.bid_reserve_met}"/>
					   {/if}
					{/if}

			  </td>
			  {if $auction->is_my_auction && ($auction->close_offer !=1 || !$auction->winner_id) && $auction->automatic != 1}
				 <td><a href="index.php?option=com_bids&task=accept&bid={$bid_list[bids]->id}&Itemid={$Itemid}" onclick="return confirm('{$smarty.const.bid_confirm_accept_bid}');">
					<img src="{$smarty.const.BIDS_COMPONENT}/images/auctionicon16.gif" border="0" />{$smarty.const.bid_accept}</a>
				 </td>
			  {/if}
			  {if $auction->must_rate && $bid_list[bids]->accept && $smarty.const.bid_opt_allow_rating == 1}
				 <td>
					<span><a href="javascript:void(0);" onclick="document.getElementById('rate').style.display = 'block';">Rate</a></span>
					<form action="index.php" method="post" name="auctionForm" onsubmit="return validateForm(this);">
					<input type="hidden" name="option" value="com_bids">
					<input type="hidden" name="task" value="rate">
					<input type="hidden" name="Itemid" value="{$Itemid}">
					<input type="hidden" name="id" value="{$auction->id}">
					<input type="hidden" name="user_rated" value="{if !$auction->is_my_auction}{$auction->userid}{else}{$auction->winner_id}{/if}">
					<input type="hidden" name="auction_id" value="{$auction->id}">
					<table id="rate" style="display:none;">
					<tr>
					<td>
						{$lists.ratings}
						<div style="margin:5px;">{$smarty.const.bid_comment}</div>
						<div style="margin:10px;"><textarea name="comment" cols="40" rows="3" class="inputbox" ></textarea></div>
						<div><input type="submit" value="{$smarty.const.bid_rate}" class="back_button"></div>
					</td>
					</tr>
					</table>
					</form>
				 </td>
			  {/if}
			  {if $auction->my_proxy_bid}
				{if $bid_list[bids]->userid==$userid}
					<td class="auction_my_proxy">{$auction->currency_name}&nbsp;{$auction->my_proxy_bid|number_format:0}</td>
				{else}
					<td>&nbsp;</td>
				{/if}
			  {/if}
			</tr>
		{sectionelse}
  			{if $auction->auction_type == $smarty.const.AUCTION_TYPE_PRIVATE}
    			<h2>{$smarty.const.bid_you_have_no_bids}</h2>
    		{else}
    			<h2>{$smarty.const.bid_no_user_bids}</h2>
    		{/if}
		{/section}
		</tbody>
		</table>
		{endtab}
	  {/if}
	{/if}
	{endpane}
  </td>
</tr>

{if $auction->close_offer}
<tr>
  <td colspan="2">
	<h1>{$smarty.const.bid_auction_closed_on}: {printdate  date=$auction->closed_date}</h1>
	{if $auction->i_am_winner}
		<h1>{$smarty.const.bid_alt_you_are_winner}</h1>
	{/if}
  </td>
 </tr>
{/if}
</table>
{if $smarty.const.bid_opt_enable_countdown}
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/startcounter.js"></script>
{/if}
