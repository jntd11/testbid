{* Include Overlib initialisation *}
{include file='t_overlib.tpl'}

{* Include Validation script *}
{include file='t_javascript_language.tpl'}

{* Include Tabbing Scripts *}
{createtab}

{* set the custom Auction CSS & Template CSS - after tabbing so we replace css in tabbed output*}
{set_css}

<script language="javascript" type="text/javascript" src="{$smarty.const.BIDS_COMPONENT}/fvalidate/fValidate.core.js"></script>
<script language="javascript" type="text/javascript" src="{$smarty.const.BIDS_COMPONENT}/fvalidate/fValidate.datetime.js"></script>
<script language="javascript" type="text/javascript" src="{$smarty.const.BIDS_COMPONENT}/fvalidate/fValidate.numbers.js"></script>
<script language="javascript" type="text/javascript" src="{$smarty.const.BIDS_COMPONENT}/fvalidate/fValidate.basic.js"></script>
<script language="javascript" type="text/javascript" src="{$smarty.const.BIDS_COMPONENT}/fvalidate/fValidate.controls.js"></script>
<script language="javascript" type="text/javascript" src="{$smarty.const.BIDS_COMPONENT}/fvalidate/fValidate.lang-enUS.js"></script>
<script language="javascript" type="text/javascript" src="{$smarty.const.BIDS_COMPONENT}/fvalidate/fValidate.config.js"></script>

<script language="javascript" type="text/javascript" src="{$smarty.const.BIDS_COMPONENT}/js/multifile.js"></script>

<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/date.js"></script>
<script type="text/javascript" src="{$mosConfig_live_site}/components/com_bids/js/auction_edit.js"></script>
<style type="text/css">
{literal}
 .tab-page{ z-index:0 !important;}
{/literal}
</style>

<table>
	<tr>
	 <td>
		<input type="submit" name="save" value="{$smarty.const.but_save}" class="back_button" />
	 </td>
	</tr>
</table>
<div class="auction_edit_header">
	{$smarty.const.bid_offer}: <small>{if $auction->id}{$smarty.const.bid_edit}{else}{$smarty.const.bid_new}{/if}</small>
	{if $auction->title}<small>[{$auction->title}]</small>{/if}
</div>
{php}
	$this->assign('requ_img',"<img src='".BIDS_COMPONENT."/images/required_field.png' border=0 style='margin:0px;'/>");
{/php}
<div style="font-size:small">{$smarty.const.bid_required_fields_info|replace:"(*)":$requ_img}</div>
{startpane id="contentpane" usecookies=0}
{*  //TAB1 -    Bid Auction Details *}
{*  JaiStartC *}
{starttab paneid="tab" text=$smarty.const.bid_tab_offer_details hidedetails=1}
{*  JaiEndC *}

  <table width="99%" border="0">
    <tr>
     {*Main Column 1 *}
     {* JaiStartC *}
     {* This file has been Changed a lot for removal of tabs & fields *}
    <td width="60%" valign="top">
        <table width="100%" border="0"  valign="top">       
	 <tr>
	  <td>{$smarty.const.auction_title} <img src="{$smarty.const.BIDS_COMPONENT}/images/required_field.png" border="0" style="margin:0px;" /> </td>
	  <td>
	  {if $task=='editauction'}
		{$auction->title}
	  {else}
		<input class="inputbox" type="text" size="45" name="title" value="{$auction->title}" alt="title">
	  {/if}
	  </td>
	 </tr>
	{*  ADDED for Optional Fields - Jai *}
	{*  JaiStartC *}
	{if $smarty.const.bid_opt_allow_category == 1}
	 <tr>
	  <td>{$smarty.const.bid_category}: </td>
	  <td>{$lists.cats}</td>
	 </tr>
	 {/if}
	{*  JaiEndC *}
	 <tr>
	  <td>{$smarty.const.bid_published}:</td>
	  <td>
		  {if $task=='editauction' && $auction->published && $auction->isValidateDate == 0}
			{$smarty.const.bid_yes}
		  {else}
			{$lists.published}
		  {/if}
		  <input type="hidden" name="published" value="1">
	  </td>
	</tr>
	{*  ADDED for Optional Fields - Jai *}
	{*  JaiStartB *}
	{if $smarty.const.bid_opt_allow_tag == 1}
	 <tr>
		  <td>{$smarty.const.bid_tags}:</td>
		  <td>
			  <input name="tags" class="inputbox" value="{$auction->tags}" size="50" type="text">
			  {infobullet text=$smarty.const.bid_help_tags}
		  </td>
	 </tr>
	 {/if}
	 {* JaiEndB *}
	 {if $smarty.const.label_short_desc != ""}
	 <tr>
		  <td >{$smarty.const.label_short_desc} <img src="{$smarty.const.BIDS_COMPONENT}/images/required_field.png" border="0" style="margin:0px;" /></td>
		  <td><input class="inputbox"  name="shortdescription" type="text" size="50" value="{$auction->shortdescription}"> </td>
	 </tr>
	 {/if}
	 {if $smarty.const.label_custom_field1 != ""}
	 <tr>
		  <td >{$smarty.const.label_custom_field1} </td>
		  <td><input class="inputbox"  name="custom_fld1" type="text" size="50" value="{$auction->custom_fld1}"> </td>
	 </tr>
	 {/if}
	 {if $smarty.const.label_custom_field2 != ""}
	 <tr>
		  <td >{$smarty.const.label_custom_field2} </td>
		  <td><input class="inputbox"  name="custom_fld2" type="text" size="50" value="{$auction->custom_fld2}"> </td>
	 </tr>
	 {/if}
	 {if $smarty.const.label_custom_field3 != ""}
	 <tr>
		  <td >{$smarty.const.label_custom_field3} </td>
		  <td><input class="inputbox"  name="custom_fld3" type="text" size="50" value="{$auction->custom_fld3}"> </td>
	 </tr>
	 {/if}
	 {if $smarty.const.label_custom_field4 != ""}
	 <tr>
		  <td >{$smarty.const.label_custom_field4} </td>
		  <td><input class="inputbox"  name="custom_fld4" type="text" size="50" value="{$auction->custom_fld4}"> </td>
	 </tr>
	 {/if}
	 {if $smarty.const.label_custom_field5 != ""}
	 <tr>
		  <td >{$smarty.const.label_custom_field5} </td>
		  <td><input class="inputbox"  name="custom_fld5" type="text" size="50" value="{$auction->custom_fld5}"> </td>
	 </tr>
	 {/if}
	 <tr>
		  <td colspan="2">
			{$smarty.const.bid_description} <img src="{$smarty.const.BIDS_COMPONENT}/images/required_field.png" border="0" style="margin:0px;" /> 
		  </td>
	</tr>
	<tr>
		  <td colspan="2">
			{php}
				$auct=$this->get_template_vars('auction');
				editorArea( 'description',  $auct->description , 'description', '100%', '400px', '165', '150' ) ;
			{/php}
		  </td>
	 </tr>
	 </table>
      </td>
      {* End Main Coulmn 1 *}
      {* Main Column 2 *}
      <td width="40%" valign="top">
	 <table  width="100%" border="0">
		<tr>
		  <td width="35%">{$smarty.const.bid_attach_photo}</td>
		  <td width="65%"><small style="color: Grey;">{$smarty.const.bid_opt_max_picture_size|string_format:$smarty.const.bid_picture_more_140}</small> </td>
		</tr>
		<tr>
			   <td>
			   {$smarty.const.bid_main_picture}
				{if $smarty.const.bid_opt_require_picture=="1"} 
					<img src="{$smarty.const.BIDS_COMPONENT}/images/required_field.png" border="0" style="margin:0px;" />
				{/if}
			  </td>
			  {if $auction->picture}
			  <td> 	
				{$auction->thumbnail}
				<input type="checkbox" name="delete_main_picture" value="1"> {$smarty.const.bid_delete_picture}
			 </td>
			{else}
			 <td>
				<input type="file" name="picture_main" {if $smarty.const.bid_opt_require_picture}alt="image_file"{/if}>
			 </td>
			{/if}
			  </td>
		</tr>
		<tr>
			<td valign="top">{$smarty.const.bid_other_picture}</td>
			<td align="left">
			{section name=image loop=$auction->imagelist}
			{*  JaiStartC *}
			{$auction->imagelist[image]->thumbnail} &nbsp; <input type="checkbox" name="delete_pictures[]" value="{$auction->imagelist[image]->id}"> {$smarty.const.bid_delete_picture}
			{* JaiEndC *}
			{/section}
				<div id="files">
					<input class="inputbox" {if $auction->imagelist|@count>=$smarty.const.bid_opt_maxnr_images}disabled{/if}  id="my_file_element" type="file" name="pictures_1" >
					<div id="files_list"></div>
					<script>
					var multi_selector = new MultiSelector( document.getElementById('files_list'),{$smarty.const.bid_opt_maxnr_images}-{$auction->imagelist|@count} )
					multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>
				</div>
			 </td>
		</tr>
		<tr>
		  <td>{$smarty.const.bid_link}</td>
		  <td><input class="inputbox" type="text" size="40" name="link_extern" value="{$auction->link_extern}"></td>
		</tr>
		<tr>
			<td>{$smarty.const.bid_start_date} <img src="{$smarty.const.BIDS_COMPONENT}/images/required_field.png" border="0" style="margin:0px;" /></td>
			  <td>
			  {* JaiStartE *}
			  {if ($task=='editauction' && $auction->isValidateDate == 0)  ||  ($auction->buyersChoiceManager == "Buyer's Choice")}
				{printdate date=$auction->start_date use_hour=$smarty.const.bid_opt_enable_hour}
				<input type="hidden" name="start_date" id="start_date" size="15" maxlength="19" value="{$auction->start_date|date_format:"%m/%d/%Y"}" alt="start_date"/>
				<input name="start_hour" size="1" value="{$auction->start_date|date_format:"%H"}" alt="" class="inputbox" type="hidden"> :
				<input name="start_minutes" size="1" value="{$auction->start_date|date_format:"%M"}" alt="" class="inputbox" type="hidden">
			  {else}
				<input class="text_area" type="text" name="start_date" id="start_date" size="15" maxlength="19" value="{$auction->start_date|date_format:$opt_date_format}" alt="start_date"/>
				<input type="reset" class="button" value="..." onClick="return showCalendar('start_date');"><!--'y-mm-dd'-->
				<input name="start_hour" size="1" value="{$auction->start_date|date_format:"%H"}" alt="" class="inputbox"> :
				<input name="start_minutes" size="1" value="{$auction->start_date|date_format:"%M"}" alt="" class="inputbox">

			  {/if}
			  {* JaiEndE *}
			  </td>
		</tr>
		<tr>
			  <td>{$smarty.const.bid_end_date} <img src="{$smarty.const.BIDS_COMPONENT}/images/required_field.png" border="0" style="margin:0px;" /></td>
			  <td>
				  {* JaiStartE *}
				  {if ($task=='editauction' && $auction->isValidateDate == 0) ||  ($auction->buyersChoiceManager == "Buyer's Choice")}
					{printdate date=$auction->end_date use_hour=$smarty.const.bid_opt_enable_hour}
					<input type="hidden" name="end_date" id="end_date" size="15" maxlength="19" value="{$auction->end_date|date_format:"%m/%d/%Y"}" alt="end_date"/>
					<input name="end_hour" size="1" value="{$auction->end_date|date_format:"%H"}" alt="" class="inputbox" type="hidden"> :
					<input name="end_minutes" size="1" value="{$auction->end_date|date_format:"%M"}" alt="" class="inputbox"  type="hidden">
				  {* JaiEndE *}
				  {else}
					<input class="text_area" type="text" name="end_date" id="end_date" size="15" maxlength="19" value="{$auction->end_date|date_format:$opt_date_format}" alt="end_date"/>
					<input type="reset" class="button" value="..." onClick="return showCalendar('end_date');">
					{if $smarty.const.bid_opt_enable_hour}
						<input name="end_hour" size="1" value="{$auction->end_date|date_format:"%H"}" alt="" class="inputbox"> :
						<input name="end_minutes" size="1" value="{$auction->end_date|date_format:"%M"}" alt="" class="inputbox">
					{/if}
				  {/if}
			  </td>
		</tr>
		<tr>
			  <td>{$smarty.const.bid_initial_price} <img src="{$smarty.const.BIDS_COMPONENT}/images/required_field.png" border="0" style="margin:0px;" /></td>
			  <td>{if $task=='editauction'}
				{$auction->currency_name}&nbsp;{$auction->initial_price|number_format:0}
			  {else}
				<input class="inputbox" type="text" size="7" name="initial_price" value="{$auction->initial_price}" alt="initial_price">
				{$lists.currency}
			  {/if}
			  </td>
		</tr>	
		 <tr>
			  <td>
			  	{* JaiStartC *}
				<input class="inputbox" type="hidden" size="7" name="bin_OPTION" id="bin_OPTION" value="0" >
				<input class="inputbox" type="hidden" size="7" name="auction_type" id="auction_type" value="1" >
				{* JaiEndC *}
				<input class="inputbox" type="hidden" name="automatic" value="1" {if $auction->automatic}checked{/if}> 
				{$smarty.const.bid_payment} <img src="{$smarty.const.BIDS_COMPONENT}/images/required_field.png" border="0" style="margin:0px;" />
				</td>
			  <td>
				{* JaiStartE *}
				{$auction->payment_name}
				{* JaiEndE *}
			  </td>
		 </tr>
		 <tr>
			  <td valign="top">{$smarty.const.bid_shipment_price}</td>
			  <td><input name="shipment_price" class="inputbox"  value="{$auction->shipment_price}" /></td>
		 </tr>
		 <tr>
			  <td valign="top">{$smarty.const.bid_shipment}</td>
			  {* JaiStartE *}
			  <td>{$auction->shipment_info}</td>
			  {* JaiEndE *}
		 </tr>
		  <tr>
			<td>{$smarty.const.bid_param_picture_text}: {infobullet text=$smarty.const.bid_param_picture_help}</td>
			<td>
			    <input type="radio" name="picture" value="1" {if $parameters.picture=='1'}checked{/if}>{$smarty.const.bid_show}
			    <input type="radio" name="picture" value="0" {if $parameters.picture!='1'}checked{/if}>{$smarty.const.bid_hide}
			</td>
		  </tr>
		    <tr>
			<td>{$smarty.const.bid_param_add_picture_text}: {infobullet text=$smarty.const.bid_param_add_picture_help}</td>
			<td>
			    <input type="radio" name="add_picture" value="1" {if $parameters.add_picture=='1'}checked{/if}>{$smarty.const.bid_show}
			    <input type="radio" name="add_picture" value="0" {if $parameters.add_picture!='1'}checked{/if}>{$smarty.const.bid_hide}
			</td>
		    </tr>
		    <tr>
			<td align="left">{$smarty.const.bid_param_counts_text}: {infobullet text=$smarty.const.bid_param_counts_help}</td>
			<td>
			{* JaiStartC *}
			   <input type="hidden" name="auto_accept_bin" value="1" {if $parameters.auto_accept_bin=='1'}checked{/if}>
			   <input type="hidden" name="max_price" value="1" {if $parameters.max_price=='1'}checked{/if}>
			{* JaiEndC *}
			    <input type="radio" name="bid_counts" value="1" {if $parameters.bid_counts=='1'}checked{/if}>{$smarty.const.bid_show}
			    <input type="radio" name="bid_counts" value="0" {if $parameters.bid_counts!='1'}checked{/if}>{$smarty.const.bid_hide}
				
			</td>
		    </tr>
	</table>
      </td>
      {* End Main Column 2 *}
      {* JaiEndC *}
      </tr>
   </table>
  <div id="zoom_pic" style="position:absolute;display:none;width:150px;left:300px"><img id="i_zoom_pic" src=""></div>
{endtab}
{endpane}
<br clear="all" />