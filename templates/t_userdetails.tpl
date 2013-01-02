{* Include Overlib initialisation *}
{include file='t_overlib.tpl'}

{* set the custom Auction CSS & Template CSS - after tabbing so we replace css in tabbed output*}
{set_css}

 <div class="componentheading">
	 {$smarty.const.bid_user_details}: {$user->username}
 </div>
 <table width="100%" border="0" cellpadding="0" cellspacing="0" class="userdetailstable">
  <tr>
   <td width=85>{$smarty.const.bid_name}</td>
   <td>{$user->name}</td>
  </tr>
  <tr>
   <td width=85>{$smarty.const.bid_surname}</td>
   <td>{$user->surname}</td>
  </tr>
  <tr>
   <td width=85>{$smarty.const.bid_address}</td>
   <td>{$user->address}</td>
  </tr>
  <tr>
   <td width=85>{$smarty.const.bid_city}</td>
   <td>{$user->city}</td>
  </tr>
  <tr>
   <td width=85>{$smarty.const.bid_country}</td>
   <td>{$user->country}</td>
  </tr>
  <tr>
   <td width=85>{$smarty.const.bid_phone}</td>
   <td>{$user->phone}</td>
  </tr>
  {if $smarty.const.bid_opt_allowpaypal}
	  <tr>
       <td width=85>{$smarty.const.bid_user_paypalemail}</td>
       <td>{$user->paypalemail}</td>
	  </tr>
 {/if}
	  <tr><td height="10px" colspan="2"></td></tr>
	  <tr><td colspan="2"><h3>{$smarty.const.bid_last_10_ratings}</h3></td></tr>
	  <tr>
    	  <td colspan="2">
            {include file='t_myratings.tpl'}
          </td>
	  </tr>
</table>
<div>&nbsp;</div>
<div>
    <a href="{$mosConfig_live_site}/index.php?option={$option}&Itemid={$Itemid}&task=myratings&id={$user->userid}">{$smarty.const.bid_all_user_ratings}</a>
</div>
<div>
  <a href='{$mosConfig_live_site}/index.php?option=com_bids&task=listauctions&userid={$user->userid}&Itemid={$Itemid}'>{$smarty.const.bid_more_auctions}</a>
</div>
