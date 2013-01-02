{* Include Overlib initialisation *}
{include file='t_overlib.tpl'}

{* Include Validation script *}
{include file='t_javascript_language.tpl'}

{* set the custom Auction CSS & Template CSS - after tabbing so we replace css in tabbed output*}
{set_css}


<h1>{$smarty.const._SEARCH_TITLE}</h1>
<form action="index.php" method="post" name="auctionForm" >
<input type="hidden" name="task" value="showSearchResults">
<input type="hidden" name="option" value="com_bids">
<input type="hidden" name="Itemid" value="{$Itemid}">

 <table width="100%" class="search">
  <tr>
   <td>
    <table>
     <tr>
      <td width="70px">{$smarty.const.search_ipt_title}</td>
      <td><input type="text" name="keyword" class="inputbox" size="20"></td>
      <td><input type="checkbox" class="inputbox" name="indesc" value="1"> &nbsp;{$smarty.const.search_desc_text}</td>
     </tr>
     <tr>
      <td></td>
      <td></td>
      <td><input type="checkbox" class="inputbox" name="inarch" value="1"> &nbsp;{$smarty.const.search_arch}</td>
     </tr>
    </table>
   </td>
  </tr>
  <tr>
   <td>
    <table>
     <tr>
      <td width="70px">{$smarty.const.bid_tags}</td>
      <td><input type="text" name="tag" class="inputbox" size="20"></td>
     </tr>
    </table>
   </td>
  </tr>
  <tr>
   <td>
    <table>
     <tr>
      <td width="70px">{$smarty.const.bid_auction_number}</td>
      <td><input type="text" name="auction_nr" class="inputbox" size="20"></td>
     </tr>
    </table>
   </td>
  </tr>
  <tr>
   <td>
    <table>
     <tr valign="top">
      <td width="70px">{$smarty.const.serch_just_sellers}</td>
      <td>{$lists.users}</td>
     </tr>
    </table>
   </td>
  </tr>
  {if $lists.cats}
  <tr>
   <td>
    <table>
     <tr>
      <td width="70px">{$smarty.const.bid_category}</td>
      <td>
      	{$lists.cats}
      </td>
     </tr>
    </table>
   </td>
  </tr>
  {/if}
  {if $lists.city}
  <tr>
   <td>
    <table>
     <tr>
      <td width="70px">{$smarty.const.bid_city}</td>
      <td>
      	{$lists.city}
      </td>
     </tr>
    </table>
   </td>
  </tr>
  {/if}
  {if $lists.country}
  <tr>
   <td>
    <table>
     <tr>
      <td width="70px">{$smarty.const.bid_country}</td>
      <td>
      	{$lists.country}
      </td>
     </tr>
    </table>
   </td>
  </tr>
  {/if}
  <tr>
   <td>
    <table>
     <tr>
      <td>{$smarty.const.search_after_start_date}</td>
      <td>
      <input class="text_area" name="afterd" id="afterd" type="text" size="12" value="">
      <input name="reset" class="button" onclick="return showCalendar('afterd', 'y-mm-dd');" value="..." type="reset">
      </td>
     </tr>
    </table>
   </td>
  </tr>
   <tr>
   <td>
    <table>
     <tr>
      <td>{$smarty.const.search_before_end_date}</td>
      <td>
      <input class="text_area" name="befored" id="befored" type="text" size="12" value="">
      <input name="reset" class="button" onclick="return showCalendar('befored', 'y-mm-dd');" value="..." type="reset">
      </td>
     </tr>
    </table>
   </td>
  </tr>
  <tr>
   <td><input type="submit" name="{$smarty.const.but_search}" value="{$smarty.const.but_search}" class="back_button"/></td>
  </tr>
 </table>
 </form>