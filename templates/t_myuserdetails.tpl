{* Include Overlib initialisation *}
{include file='t_overlib.tpl'}

{* set the custom Auction CSS & Template CSS - after tabbing so we replace css in tabbed output*}
{set_css}
{literal}
 <script language="javascript" type="text/javascript">
function formvalidate(  ) {
 	var form = document.auctionForm;
	// do field validation
	if (form.name.value == "") {
		alert( "{/literal}{$smarty.const._REGWARN_NAME}{literal}" );
		return false;
	} else if (form.surname.value == "") {
		alert( "{/literal}{$smarty.const.bid_err_enter_surname}{literal}" );
		return false;
	} else if (form.country.value == 0) {
		alert( "{/literal}{$smarty.const.bid_err_enter_country}{literal}" );
		return false;
	} else if (form.address.value == 0) {
		alert( "{/literal}{$smarty.const.bid_err_enter_address}{literal}" );
		return false;
	} else if (form.city.value == 0) {
		alert( "{/literal}{$smarty.const.bid_err_enter_city}{literal}" );
		return false;
	}
	return true;
 }
 </script>
 {/literal}
 <form action="index.php" method="post" name="auctionForm" onsubmit="return formvalidate()">
 <input type="hidden" name="Itemid" value="{$Itemid}" />
 <input type="hidden" name="option" value="{$option}">
 <input type="hidden" name="task" value="saveUserDetails" />

<div><a href="{$mosConfig_live_site}/index.php?option={$option}&Itemid={$Itemid}&task=myratings">{$smarty.const.bid_my_ratings}</a></div>
 <div class="componentheading">
	 {$smarty.const.bid_edit_user_details}
 </div>
 <input name="save" value="{$smarty.const.but_save}" class="back_button" type="submit">
 <table width="100%" border="0" cellpadding="0" cellspacing="0" class="userdetailstable">
  <tr>
   <td width=85>{$smarty.const.bid_name}</td>
   <td>
	<input class="inputbox" type="text" name="name" value="{$user->name}" size="40" />
   </td>
  </tr>

  <tr>
   <td width=85>{$smarty.const.bid_surname}</td>
   <td>
	<input class="inputbox" type="text" name="surname" value="{$user->surname}" size="40" />
   </td>
  </tr>

  <tr>
   <td width=85>{$smarty.const.bid_address}</td>
   <td>
	<input class="inputbox" type="text" name="address" value="{$user->address}" size="40" />
   </td>
  </tr>
 <tr>
   <td width=85>{$smarty.const.bid_city}</td>
   <td>
	<input class="inputbox" type="text" name="city" value="{$user->city}" size="40" />
   </td>
  </tr>



  <tr>
   <td width=85>{$smarty.const.bid_country}</td>
   <td>
   {$lists.country}
   </td>
  </tr>


  <tr>
   <td width=85>{$smarty.const.bid_phone}</td>
   <td>
	<input class="inputbox" type="text" name="phone" value="{$user->phone}" size="40" />
   </td>
  </tr>

  {if $smarty.const.bid_opt_allowpaypal}
      <tr>
       <td width=85>{$smarty.const.bid_user_paypalemail}</td>
       <td>
    	<input class="inputbox" type="text" name="paypalemail" value="{$user->paypalemail}" size="40" />
       </td>
      </tr>
  {/if}

  <tr><td colspan="2"><hr></td></tr>
  <tr><td colspan="2">{$smarty.const.bid_payment_credits}</td></tr>
  <tr>
   <td colspan="2">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	   <th>{$smarty.const.bid_payment_item}</th>
	   <th>{$smarty.const.bid_payment_credits}</th>
	   <th>{$smarty.const.bid_payment_item_price}</th>
	   <th>&nbsp;</th>
	</tr>
	  {section name=credit loop=$credits}
            {assign var=txt value=$credits[credit]->itemname}
            {assign var=txt value="bid_payment_"|cat:$txt}
    	  <tr class="mywatch{cycle values="0,1"}">
        	  <td>{$smarty.const.$txt }</td>
        	  <td>{if $credits[credit]->amount}{$credits[credit]->amount}{else}0{/if}</td>
        	  <td>{$credits[credit]->price}&nbsp;{$credits[credit]->currency}</td>
        	  <td><a href="index.php?option=com_bids&task={$credits[credit]->task_pay}">{$smarty.const.bid_payment_purchase}</a></td>
    	  </tr>
	  {/section}
	</table>
   </td>
  </tr>
	  {if $lists.pricing_plugin->enabled=="1"}
	  <tr>
	   <td colspan="2"><strong>{$smarty.const.bid_comissions_amount}&nbsp;</strong>
	   {foreach from=$lists.debts item=debt key=k}
	   {if $debt->amount>0}	
	   	{$debt->currency_name}&nbsp;{$debt->amount} 	
	   {/if}
	   {/foreach}	
	   	<a href="{$lists.pay_comission_link}">{$smarty.const.bid_pay_comission}</a>
	   </td>
	  </tr>
	  {/if}
</table>
</form>
<br><h3>{$smarty.const.bid_last_10_ratings}</h3>
{include file='t_myratings.tpl'}

