<div id="popupContact">
		<a id="popupContactClose">x</a>
		<div id="contactArea">
		<INPUT TYPE="button" id="popupcontinue" class="button art-button" VALUE="Continue bid confirm" onClick="" /> <br />
		<INPUT class="button art-button"  id="popupsavelater" TYPE="button" VALUE="Save choices for later submission" onClick="" /><br />
		<INPUT class="button art-button"  id="popupcancel" TYPE="button" VALUE="Cancel and exit" onClick="" /><br />
		</div>
	</div>
	<div id="backgroundPopup"></div>
{* JaiStartH *}
{if $task != editproxyticket}
<tr id="" >	
	  <td  colspan="2">
		
		<table cellpadding="2" cellspacing="2" width="80%" align="center">
		<tr>
			<td colspan="2" style="font-size: 14px; font-weight: bold;">{$smarty.const.ticket} {$userticket[$myId]} {if $myId == 0}  <input name="ticketid" id="ticketid" type="hidden" value="1"> {else}  <input name="ticketid" id="ticketid" type="hidden" value="{$myId}">{/if} <input name="run" id="run" type="hidden" value="0"></td>
			<td colspan="3" align="left" style="font-size: 24px; color: red; font-weight: bold;">{$smarty.const.lot_desired} 
			<input class="proxy" type="text" name="lotdesired" id="lotdesired" size="5" value="{$lots_desired[$myId]}" readonly="readonly"/>
			</td>
		</tr>
		<tr>
			<td colspan="5" style="padding: 3px;"></td>
		</tr>
		<tr class="proxyheader">
			<td width="" class="styleborder">Priority</td>
			<td width="" class="styleborder">Lot #</td>
			<td width="" class="styleborder">My Max Bid</td>
			<td width="" class="styleborder">{$smarty.const.label_short_desc}</td>
			<td width="" class="styleborder">Next Bid</td>
		</tr>
		<tr>
			<td colspan="5" style="padding: 1px;"></td>
		</tr>
		{foreach from=$item1 key=myId1 item=item2}
			<tr id="row1" class="auction_row1" >
				<td width="" id="prioritycol1" class="styleborder">{$item2->priority}</td>
				<td width="" id="lotcol1" class="styleborder">{$item2->title}</td>
				<td width="" id="mybidcol1" class="styleborder">{$item2->my_bid}</td>
				<td width="" id="desccol1" class="styleborder">{$item2->shortdesc}</td>
				<td width="" id="nextbidcol1" class="styleborder">{$countdownmess}{if $countdown != -1}{$item2->bid_next}{else}{$item2->countdownmess}{/if}</td>
			</tr>
		{/foreach}
		<tr>
			<td colspan="5" >&nbsp;</td>
		</tr>
		<tr id="rowbutton"><td colspan="5" align="right">
		{if $start_date > $smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}
			<input type="button" value="Delete Entire ticket" id="delticket" class="button art-button"  onClick="delTicket({$myId})">
			<a href='{$mosConfig_live_site}/index.php?option=com_bids&task=editproxyticket&ticketid={$myId}' style="text-decoration: none;"><input type="button" value="Edit Ticket" class="button art-button"  id="editticket" onClick="window.location='{$mosConfig_live_site}/index.php?option=com_bids&task=editproxyticket&ticketid={$myId}';"></a> 
		{else}
			<a href='{$mosConfig_live_site}/index.php?option=com_bids&task=editproxyticket&ticketid={$myId}&run=1' style="text-decoration: none;"><input type="button" value="Edit Ticket" class="button art-button"  id="editticket" onClick="window.location='{$mosConfig_live_site}/index.php?option=com_bids&task=editproxyticket&ticketid={$myId}&run=1';"></a> 
		{/if}
		
		</td></tr>
		</table>
	  </td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
{elseif $ticketid > 0 || $sessionproxy > 0}
<tr id="" >	
	  <td  colspan="2">
		
		<table cellpadding="2" cellspacing="2" width="80%" align="center">
		<tr>
			<td colspan="2" style="font-size: 14px; font-weight: bold;">{$smarty.const.ticket} {$userticket[$myId]} 
			{if $sessionproxy > 0}
				<input name="ticketid" id="ticketid" type="hidden" value="{$session.proxy.1.6}"> 
			{else}
				<input name="ticketid" id="ticketid" type="hidden" value="{$ticketid}"> 
			{/if}
			
			<input type="hidden" value="edit" id="proxyplustype" name="proxyplustype">
			<input name="run" id="run" type="hidden" value="{$isRun}">
			</td>
			<td colspan="3" align="left" style="font-size: 24px; color: red; font-weight: bold;">{$smarty.const.lot_desired}
			{if $sessionproxy > 0}
				<input class="proxy"  type="text" name="lotdesired" id="lotdesired" size="5" value="{$session.proxy.1.5}"/>
				<input class="proxy"  type="hidden" name="oldlotdesired" id="oldlotdesired" size="5" value="{$session.proxy.1.5}"/>
			{else}
				<input class="proxy"  type="text" name="lotdesired" id="lotdesired" size="5" value="{$lots_desired[$myId]}"/>
				<input class="proxy"  type="hidden" name="oldlotdesired" id="oldlotdesired" size="5" value="{$lots_desired[$myId]}"/>
			{/if}
			</td>
		</tr>
		<tr>
			<td colspan="5" style="padding: 1px;"></td>
		</tr>
		<tr class="proxyheader">
			<td width="" class="styleborder">Priority</td>
			<td width="" class="styleborder">Lot #</td>
			<td width="" class="styleborder">My Max Bid</td>
			<td width="" class="styleborder">{$smarty.const.label_short_desc}</td>
			<td width="" class="styleborder">Next Bid</td>
		</tr>
		<tr>
			<td colspan="5" style="padding: 2px;"></td>
		</tr>
		
		{if $sessionproxy > 0}
		  {foreach from=$session.proxy key=myId item=item1}
			<tr id="row{$myId}" class="auction_row1" >
				<td width="20%" id="prioritycol{$myId}" class="styleborder">
					<input class="proxy" name="id{$myId}" id="id{$myId}" type="hidden" value="{$myId}">{$myId}
				</td>
				<td width="20%" id="lotcol{$myId}" class="styleborder">
					<input class="proxy"  type="text" name="lotno{$myId}" id="lotno{$myId}" value="{$item1.2}" onBlur="showNextRow({$myId});" onMouseDown="showNextRow({$myId});"  >
					<input type="hidden" name="oldlotno{$item1.0}" id="oldlotno{$item1.0}" value="{$item1.1}" >
					<input type="hidden" name="auction_id{$myId}" id="auction_id{$myId}" value="{$item1.7}">
				</td>
				<td width="20%" id="mybidcol{$myId}" class="styleborder">
				    <input class="proxy" type="text" name="mybid{$myId}" id="mybid{$myId}" value="{$item1.1}"  onkeypress="return onlyNumbers(event);">
				</td>
				<td width="20%" id="desccol{$myId}" class="styleborder">{$item1.3}</td>
				<td width="20%" id="nextbidcol{$myId}" class="styleborder">{$item1.4}</td>
			</tr>
		  {/foreach}
		  {literal}
				<script>
					editticket2();
				</script>
		  {/literal}
			  <tr id="row{$item1.0+6}" style="display: none;" class="auction_row1"></tr>
		{else}
		{foreach from=$item1 key=myId1 item=item2}
			<tr id="row{$item2->priority}" class="auction_row1" >
				<td width="20%" id="prioritycol{$item2->priority}" class="styleborder">
				    <input class="proxy" name="id{$item2->priority}" id="id{$item2->priority}" type="hidden" value="{$item2->priority}">{$item2->priority}
				</td>
				<td width="20%" id="lotcol{$item2->priority}" class="styleborder">
				{if $isRun == 1}
				    <input class="proxy"  type="text" name="lotno{$item2->priority}" id="lotno{$item2->priority}" readonly="readonly" disabled="disabled" value="{$item2->title}" onBlur="showNextRow({$item2->priority});" onMouseDown="showNextRow({$item2->priority});" >
				{else}
				    <input class="proxy"  type="text" name="lotno{$item2->priority}" id="lotno{$item2->priority}" value="{$item2->title}" onBlur="showNextRow({$item2->priority});" onMouseDown="showNextRow({$item2->priority});" >
				{/if}
					<input type="hidden" name="oldlotno{$item2->priority}" id="oldlotno{$item2->priority}" value="{$item2->title}" ><input type="hidden" name="auction_id{$item2->priority}" id="auction_id{$item2->priority}" value="{$item2->auction_id}">
				</td>
				<td width="20%" id="mybidcol{$item2->priority}" class="styleborder">
				    <input class="proxy" type="text" name="mybid{$item2->priority}" onkeypress="return onlyNumbers(event);" id="mybid{$item2->priority}" value="{$item2->my_bid}" onBlur="checkbid({$item2->priority});" onMouseDown="checkbid({$item2->priority});" >
				    <input type="hidden" name="oldmybid{$item2->priority}" id="oldmybid{$item2->priority}" value="{$item2->my_bid}">
					<input type="hidden" name="is_outbid{$item2->priority}" id="is_outbid{$item2->priority}" value="{$item2->outbid}">
				</td>
				<td width="20%" id="desccol{$item2->priority}" class="styleborder">{$item2->shortdesc}</td>
				<td width="20%" id="nextbidcol{$item2->priority}" class="styleborder">{$item2->bid_next}</td>
			</tr>
		{/foreach}
		{literal}
				<script>
					editticket2();
				</script>
		{/literal}
		<tr id="row{$item2->priority+6}" style="display: none;" class="auction_row1"></tr>
		{/if}
		
		<tr>
			<td colspan="5" >&nbsp;</td>
		</tr>
		<tr id="rowbutton"><td colspan="5" align="right">
		{if $sessionproxy == 0 && $start_date > $smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}
			<input type="button" value="Delete Entire ticket" id="delticket" class="button art-button"  onClick="delTicket({$ticketid})"> 
		{else}
			<input type="button" value="Edit Ticket" id="editticket" class="button art-button"  onClick="editticket1();" style="display: none;">
		{/if}
		<input type="button" value="Continue to Confirm" class="button art-button"  id="confirmticket" onClick="confirmProxyPlus(1);"> <input type="submit" value="SEND BIDS" class="button art-button"  id="sendbid" name="sendbid" style="display:none;" disabled="true">
		{if $sessionproxy > 0}
			
			<input type="hidden" name="type" id="type" value="1">
		{else}
			<input type="hidden" name="type" id="type" value="2">
		{/if}
		</td></tr>
		</table>
	  </td>
</tr>
{else}
<tr id="" >	
	  <td  colspan="2">
		
		<table cellpadding="2" cellspacing="2" width="80%" align="center">
		<tr>
			<td colspan="2" style="font-size: 14px; font-weight: bold;">{$smarty.const.ticket} {if $count == 0} 1 <input name="ticketid" id="ticketid" type="hidden" value="1"> {else} {$count+1} <input name="ticketid" id="ticketid" type="hidden" value="{$count+1}">{/if} 
			<input type="hidden" value="new" id="proxyplustype" name="proxyplustype">
			<input name="run" id="run" type="hidden" value="0">
			</td>
			<td colspan="3" align="left" style="font-size: 24px; color: red; font-weight: bold;">{$smarty.const.lot_desired} <input class="proxy" type="text" name="lotdesired" id="lotdesired" size="5" {if $fromProxy > 0} value="1" {/if} />
			</td>
		</tr>
		<tr>
			<td colspan="5" style="padding: 1px;"></td>
		</tr>
		<tr class="proxyheader">
			<td width="" class="styleborder">Priority</td>
			<td width="" class="styleborder">Lot #</td>
			<td width="" class="styleborder">My Max Bid</td>
			<td width="" class="styleborder">{$smarty.const.label_short_desc}</td>
			<td width="" class="styleborder">Next Bid</td>
		</tr>
		<tr>
			<td colspan="5" style="padding: 2px;"></td>
		</tr>
		{if $fromProxy > 0}
		<tr id="row1" class="auction_row1" >
			<td width="20%" id="prioritycol1" class="styleborder">1 <input type="hidden" name="id1" id="id1" value="1"></td>
			<td width="20%" id="lotcol1" class="styleborder"><input class="proxy" type="text" name="lotno1" id="lotno1" value="{$rowProxy->title}" onBlur="showNextRow('1');" onMouseDown="showNextRow('1');"><input type="hidden" name="auction_id1" id="auction_id1" value="{$fromProxy}"></td>
			<td width="20%" id="mybidcol1" class="styleborder"><input class="proxy" type="text" name="mybid1" id="mybid1" value="{$amountProxy}" onBlur="checkbid(1);" onMouseDown="checkbid(1);" onkeypress="return onlyNumbers(event);"></td>
			<td width="20%" id="desccol1" class="styleborder">{$rowProxy->shortdescription}</td>
			<td width="20%" id="nextbidcol1" class="styleborder">{$rowProxy->bid_next}</td>
		</tr>
		<tr id="row2" class="auction_row2" >
			<td width="20%" id="prioritycol2" class="styleborder">2 <input type="hidden" name="id2" id="id2" value="2"></td>
			<td width="20%" id="lotcol2" class="styleborder"><input class="proxy" type="text" name="lotno2" id="lotno2" onBlur="showNextRow('2');" onMouseDown="showNextRow('2');" ><input type="hidden" name="auction_id2" id="auction_id2" value=""></td>
			<td width="20%" id="mybidcol2" class="styleborder"><input class="proxy" type="text" name="mybid2" id="mybid2" onkeypress="return onlyNumbers(event);" onBlur="checkbid(2);" onMouseDown="checkbid(2);"></td>
			<td width="20%" id="desccol2" class="styleborder"></td>
			<td width="20%" id="nextbidcol2" class="styleborder"></td>
		</tr>
		<tr id="row3" class="auction_row1" >
			<td width="" id="prioritycol3" class="styleborder">3<input type="hidden" name="id3" id="id3" value="3"></td>
			<td width="" id="lotcol3" class="styleborder"><input class="proxy" type="text" name="lotno3" id="lotno3" onBlur="showNextRow('3');" onMouseDown="showNextRow('3');"><input type="hidden" name="auction_id3" id="auction_id3" value=""></td>
			<td width="" id="mybidcol3" class="styleborder"><input class="proxy" type="text" name="mybid3" onkeypress="return onlyNumbers(event);" id="mybid3" onBlur="checkbid(3);" onMouseDown="checkbid(3);"></td>
			<td width="" id="desccol3" class="styleborder"></td>
			<td width="" id="nextbidcol3" class="styleborder"></td>
		</tr>
		<tr id="row4" class="auction_row2" >
			<td width="" id="prioritycol4" class="styleborder">4 <input type="hidden" name="id4" id="id4" value="4"></td>
			<td width="" id="lotcol4" class="styleborder"><input class="proxy" type="text" name="lotno4" id="lotno4" onBlur="showNextRow('4');" onMouseDown="showNextRow('4');"><input type="hidden" name="auction_id4" id="auction_id4" value=""></td>
			<td width="" id="mybidcol4" class="styleborder"><input class="proxy" type="text" name="mybid4" id="mybid4" onkeypress="return onlyNumbers(event);" onBlur="checkbid(4);" onMouseDown="checkbid(4);"></td>
			<td width="" id="desccol4" class="styleborder"></td>
			<td width="" id="nextbidcol4" class="styleborder"></td>
		</tr>
		<tr id="row5" class="auction_row1" >
			<td width="" id="prioritycol5" class="styleborder">5 <input type="hidden" name="id5" id="id5" value="5"></td>
			<td width="" id="lotcol5" class="styleborder"><input class="proxy" type="text" name="lotno5" id="lotno5" onBlur="showNextRow('5');" onMouseDown="showNextRow('5');"><input type="hidden" name="auction_id5" id="auction_id5" value=""></td>
			<td width="" id="mybidcol5" class="styleborder"><input class="proxy" type="text" name="mybid5" id="mybid5" onkeypress="return onlyNumbers(event);" onBlur="checkbid(5);" onMouseDown="checkbid(5);"></td>
			<td width="" id="desccol5" class="styleborder"></td>
			<td width="" id="nextbidcol5" class="styleborder"></td>
		</tr>
		{else}
		<tr id="row1" class="auction_row1" >
			<td width="20%" id="prioritycol1" class="styleborder">1 <input type="hidden" name="id1" id="id1" value="1"></td>
			<td width="20%" id="lotcol1" class="styleborder"><input class="proxy" type="text" name="lotno1" id="lotno1" onBlur="showNextRow('1');" onMouseDown="showNextRow('1');"><input type="hidden" name="auction_id1" id="auction_id1" value=""></td>
			<td width="20%" id="mybidcol1" class="styleborder"><input class="proxy" type="text" name="mybid1" id="mybid1" onkeypress="return onlyNumbers(event);" onBlur="checkbid(1);" onMouseDown="checkbid(1);"></td>
			<td width="20%" id="desccol1" class="styleborder"></td>
			<td width="20%" id="nextbidcol1" class="styleborder"></td>
		</tr>
		<tr id="row2" class="auction_row2" >
			<td width="" id="prioritycol2" class="styleborder">2 <input type="hidden" name="id2" id="id2" value="2"></td>
			<td width="" id="lotcol2" class="styleborder"><input class="proxy" type="text" name="lotno2" id="lotno2" onBlur="showNextRow('2');" onMouseDown="showNextRow('2');"><input type="hidden" name="auction_id2" id="auction_id2" value=""></td>
			<td width="" id="mybidcol2" class="styleborder"><input class="proxy" type="text" name="mybid2" id="mybid2" onkeypress="return onlyNumbers(event);" onBlur="checkbid(2);" onMouseDown="checkbid(2);"></td>
			<td width="" id="desccol2" class="styleborder"></td>
			<td width="" id="nextbidcol2" class="styleborder"></td>
		</tr>
		<tr id="row3" class="auction_row1" >
			<td width="" id="prioritycol3" class="styleborder">3<input type="hidden" name="id3" id="id3" value="3"></td>
			<td width="" id="lotcol3" class="styleborder"><input class="proxy" type="text" name="lotno3" id="lotno3" onBlur="showNextRow('3');" onMouseDown="showNextRow('3');"><input type="hidden" name="auction_id3" id="auction_id3" value=""></td>
			<td width="" id="mybidcol3" class="styleborder"><input class="proxy" type="text" name="mybid3" id="mybid3" onkeypress="return onlyNumbers(event);" onBlur="checkbid(3);" onMouseDown="checkbid(3);"></td>
			<td width="" id="desccol3" class="styleborder"></td>
			<td width="" id="nextbidcol3" class="styleborder"></td>
		</tr>
		<tr id="row4" class="auction_row2" >
			<td width="" id="prioritycol4" class="styleborder">4 <input type="hidden" name="id4" id="id4" value="4"></td>
			<td width="" id="lotcol4" class="styleborder"><input class="proxy" type="text" name="lotno4" id="lotno4" onBlur="showNextRow('4');" onMouseDown="showNextRow('4');"><input type="hidden" name="auction_id4" id="auction_id4" value=""></td>
			<td width="" id="mybidcol4" class="styleborder"><input class="proxy" type="text" name="mybid4" id="mybid4" onkeypress="return onlyNumbers(event);" onBlur="checkbid(4);" onMouseDown="checkbid(4);"></td>
			<td width="" id="desccol4" class="styleborder"></td>
			<td width="" id="nextbidcol4" class="styleborder"></td>
		</tr>
		<tr id="row5" class="auction_row1" >
			<td width="" id="prioritycol5" class="styleborder">5 <input type="hidden" name="id5" id="id5" value="5"></td>
			<td width="" id="lotcol5" class="styleborder"><input class="proxy" type="text" name="lotno5" id="lotno5" onBlur="showNextRow('5');" onMouseDown="showNextRow('5');"><input type="hidden" name="auction_id5" id="auction_id5" value=""></td>
			<td width="" id="mybidcol5" class="styleborder"><input class="proxy" type="text" name="mybid5" id="mybid5" onkeypress="return onlyNumbers(event);" onBlur="checkbid(5);" onMouseDown="checkbid(5);"></td>
			<td width="" id="desccol5" class="styleborder"></td>
			<td width="" id="nextbidcol5" class="styleborder"></td>
		</tr>
		{/if}
		<tr id="row6" style="display: none;" class="auction_row1"></tr>
		<tr id="alertmess" style="display: none"><td colspan="5" align="right" style="color: red; font-size: 12px;">Warning: This ticket CANNOT be changed when bidding has begun.</td></tr>
		<tr id="rowbutton"><td colspan="5" align="right">
		<input type="button" value="Edit Ticket" id="editticket" class="button art-button"  onClick="editticket1();" style="display: none;">
		<input type="button" value="Continue to Confirm" class="button art-button"  id="confirmticket" onClick="confirmProxyPlus(1);">
		<input type="submit" value="SEND BIDS" class="button art-button"  id="sendbid" name="sendbid" onClick="confirmProxyPlus(2);" style="display: none;" disabled="true">
		<input type="hidden" name="type" id="type" value="1"></td></tr>
		</table>
	  </td>
</tr>

{/if}
