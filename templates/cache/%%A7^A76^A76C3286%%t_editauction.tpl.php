<?php /* Smarty version 2.6.18, created on 2012-05-27 10:26:54
         compiled from t_editauction.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'createtab', 't_editauction.tpl', 8, false),array('function', 'set_css', 't_editauction.tpl', 11, false),array('function', 'startpane', 't_editauction.tpl', 46, false),array('function', 'starttab', 't_editauction.tpl', 49, false),array('function', 'infobullet', 't_editauction.tpl', 96, false),array('function', 'printdate', 't_editauction.tpl', 206, false),array('function', 'endtab', 't_editauction.tpl', 308, false),array('function', 'endpane', 't_editauction.tpl', 309, false),array('modifier', 'replace', 't_editauction.tpl', 45, false),array('modifier', 'string_format', 't_editauction.tpl', 158, false),array('modifier', 'count', 't_editauction.tpl', 188, false),array('modifier', 'date_format', 't_editauction.tpl', 207, false),array('modifier', 'number_format', 't_editauction.tpl', 243, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 't_overlib.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 't_javascript_language.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php echo smarty_createtab(array(), $this);?>


<?php echo smarty_set_css(array(), $this);?>


<script language="javascript" type="text/javascript" src="<?php echo @BIDS_COMPONENT; ?>
/fvalidate/fValidate.core.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo @BIDS_COMPONENT; ?>
/fvalidate/fValidate.datetime.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo @BIDS_COMPONENT; ?>
/fvalidate/fValidate.numbers.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo @BIDS_COMPONENT; ?>
/fvalidate/fValidate.basic.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo @BIDS_COMPONENT; ?>
/fvalidate/fValidate.controls.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo @BIDS_COMPONENT; ?>
/fvalidate/fValidate.lang-enUS.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo @BIDS_COMPONENT; ?>
/fvalidate/fValidate.config.js"></script>

<script language="javascript" type="text/javascript" src="<?php echo @BIDS_COMPONENT; ?>
/js/multifile.js"></script>

<script type="text/javascript" src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/js/date.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/js/auction_edit.js"></script>
<style type="text/css">
<?php echo '
 .tab-page{ z-index:0 !important;}
'; ?>

</style>

<table>
	<tr>
	 <td>
		<input type="submit" name="save" value="<?php echo @but_save; ?>
" class="back_button" />
	 </td>
	</tr>
</table>
<div class="auction_edit_header">
	<?php echo @bid_offer; ?>
: <small><?php if ($this->_tpl_vars['auction']->id): ?><?php echo @bid_edit; ?>
<?php else: ?><?php echo @bid_new; ?>
<?php endif; ?></small>
	<?php if ($this->_tpl_vars['auction']->title): ?><small>[<?php echo $this->_tpl_vars['auction']->title; ?>
]</small><?php endif; ?>
</div>
<?php 
	$this->assign('requ_img',"<img src='".BIDS_COMPONENT."/images/required_field.png' border=0 style='margin:0px;'/>");
 ?>
<div style="font-size:small"><?php echo ((is_array($_tmp=@bid_required_fields_info)) ? $this->_run_mod_handler('replace', true, $_tmp, "(*)", $this->_tpl_vars['requ_img']) : smarty_modifier_replace($_tmp, "(*)", $this->_tpl_vars['requ_img'])); ?>
</div>
<?php echo smarty_startpane(array('id' => 'contentpane','usecookies' => 0), $this);?>

<?php echo smarty_starttab(array('paneid' => 'tab','text' => @bid_tab_offer_details,'hidedetails' => 1), $this);?>


  <table width="99%" border="0">
    <tr>
                   <td width="60%" valign="top">
        <table width="100%" border="0"  valign="top">       
	 <tr>
	  <td><?php echo @auction_title; ?>
 <img src="<?php echo @BIDS_COMPONENT; ?>
/images/required_field.png" border="0" style="margin:0px;" /> </td>
	  <td>
	  <?php if ($this->_tpl_vars['task'] == 'editauction'): ?>
		<?php echo $this->_tpl_vars['auction']->title; ?>

	  <?php else: ?>
		<input class="inputbox" type="text" size="45" name="title" value="<?php echo $this->_tpl_vars['auction']->title; ?>
" alt="title">
	  <?php endif; ?>
	  </td>
	 </tr>
			<?php if (@bid_opt_allow_category == 1): ?>
	 <tr>
	  <td><?php echo @bid_category; ?>
: </td>
	  <td><?php echo $this->_tpl_vars['lists']['cats']; ?>
</td>
	 </tr>
	 <?php endif; ?>
		 <tr>
	  <td><?php echo @bid_published; ?>
:</td>
	  <td>
		  <?php if ($this->_tpl_vars['task'] == 'editauction' && $this->_tpl_vars['auction']->published && $this->_tpl_vars['auction']->isValidateDate == 0): ?>
			<?php echo @bid_yes; ?>

		  <?php else: ?>
			<?php echo $this->_tpl_vars['lists']['published']; ?>

		  <?php endif; ?>
		  <input type="hidden" name="published" value="1">
	  </td>
	</tr>
			<?php if (@bid_opt_allow_tag == 1): ?>
	 <tr>
		  <td><?php echo @bid_tags; ?>
:</td>
		  <td>
			  <input name="tags" class="inputbox" value="<?php echo $this->_tpl_vars['auction']->tags; ?>
" size="50" type="text">
			  <?php echo smarty_infobullet(array('text' => @bid_help_tags), $this);?>

		  </td>
	 </tr>
	 <?php endif; ?>
	 	 <?php if (@label_short_desc != ""): ?>
	 <tr>
		  <td ><?php echo @label_short_desc; ?>
 <img src="<?php echo @BIDS_COMPONENT; ?>
/images/required_field.png" border="0" style="margin:0px;" /></td>
		  <td><input class="inputbox"  name="shortdescription" type="text" size="50" value="<?php echo $this->_tpl_vars['auction']->shortdescription; ?>
"> </td>
	 </tr>
	 <?php endif; ?>
	 <?php if (@label_custom_field1 != ""): ?>
	 <tr>
		  <td ><?php echo @label_custom_field1; ?>
 </td>
		  <td><input class="inputbox"  name="custom_fld1" type="text" size="50" value="<?php echo $this->_tpl_vars['auction']->custom_fld1; ?>
"> </td>
	 </tr>
	 <?php endif; ?>
	 <?php if (@label_custom_field2 != ""): ?>
	 <tr>
		  <td ><?php echo @label_custom_field2; ?>
 </td>
		  <td><input class="inputbox"  name="custom_fld2" type="text" size="50" value="<?php echo $this->_tpl_vars['auction']->custom_fld2; ?>
"> </td>
	 </tr>
	 <?php endif; ?>
	 <?php if (@label_custom_field3 != ""): ?>
	 <tr>
		  <td ><?php echo @label_custom_field3; ?>
 </td>
		  <td><input class="inputbox"  name="custom_fld3" type="text" size="50" value="<?php echo $this->_tpl_vars['auction']->custom_fld3; ?>
"> </td>
	 </tr>
	 <?php endif; ?>
	 <?php if (@label_custom_field4 != ""): ?>
	 <tr>
		  <td ><?php echo @label_custom_field4; ?>
 </td>
		  <td><input class="inputbox"  name="custom_fld4" type="text" size="50" value="<?php echo $this->_tpl_vars['auction']->custom_fld4; ?>
"> </td>
	 </tr>
	 <?php endif; ?>
	 <?php if (@label_custom_field5 != ""): ?>
	 <tr>
		  <td ><?php echo @label_custom_field5; ?>
 </td>
		  <td><input class="inputbox"  name="custom_fld5" type="text" size="50" value="<?php echo $this->_tpl_vars['auction']->custom_fld5; ?>
"> </td>
	 </tr>
	 <?php endif; ?>
	 <tr>
		  <td colspan="2">
			<?php echo @bid_description; ?>
 <img src="<?php echo @BIDS_COMPONENT; ?>
/images/required_field.png" border="0" style="margin:0px;" /> 
		  </td>
	</tr>
	<tr>
		  <td colspan="2">
			<?php 
				$auct=$this->get_template_vars('auction');
				editorArea( 'description',  $auct->description , 'description', '100%', '400px', '165', '150' ) ;
			 ?>
		  </td>
	 </tr>
	 </table>
      </td>
                  <td width="40%" valign="top">
	 <table  width="100%" border="0">
		<tr>
		  <td width="35%"><?php echo @bid_attach_photo; ?>
</td>
		  <td width="65%"><small style="color: Grey;"><?php echo ((is_array($_tmp=@bid_opt_max_picture_size)) ? $this->_run_mod_handler('string_format', true, $_tmp, @bid_picture_more_140) : smarty_modifier_string_format($_tmp, @bid_picture_more_140)); ?>
</small> </td>
		</tr>
		<tr>
			   <td>
			   <?php echo @bid_main_picture; ?>

				<?php if (@bid_opt_require_picture == '1'): ?> 
					<img src="<?php echo @BIDS_COMPONENT; ?>
/images/required_field.png" border="0" style="margin:0px;" />
				<?php endif; ?>
			  </td>
			  <?php if ($this->_tpl_vars['auction']->picture): ?>
			  <td> 	
				<?php echo $this->_tpl_vars['auction']->thumbnail; ?>

				<input type="checkbox" name="delete_main_picture" value="1"> <?php echo @bid_delete_picture; ?>

			 </td>
			<?php else: ?>
			 <td>
				<input type="file" name="picture_main" <?php if (@bid_opt_require_picture): ?>alt="image_file"<?php endif; ?>>
			 </td>
			<?php endif; ?>
			  </td>
		</tr>
		<tr>
			<td valign="top"><?php echo @bid_other_picture; ?>
</td>
			<td align="left">
			<?php unset($this->_sections['image']);
$this->_sections['image']['name'] = 'image';
$this->_sections['image']['loop'] = is_array($_loop=$this->_tpl_vars['auction']->imagelist) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['image']['show'] = true;
$this->_sections['image']['max'] = $this->_sections['image']['loop'];
$this->_sections['image']['step'] = 1;
$this->_sections['image']['start'] = $this->_sections['image']['step'] > 0 ? 0 : $this->_sections['image']['loop']-1;
if ($this->_sections['image']['show']) {
    $this->_sections['image']['total'] = $this->_sections['image']['loop'];
    if ($this->_sections['image']['total'] == 0)
        $this->_sections['image']['show'] = false;
} else
    $this->_sections['image']['total'] = 0;
if ($this->_sections['image']['show']):

            for ($this->_sections['image']['index'] = $this->_sections['image']['start'], $this->_sections['image']['iteration'] = 1;
                 $this->_sections['image']['iteration'] <= $this->_sections['image']['total'];
                 $this->_sections['image']['index'] += $this->_sections['image']['step'], $this->_sections['image']['iteration']++):
$this->_sections['image']['rownum'] = $this->_sections['image']['iteration'];
$this->_sections['image']['index_prev'] = $this->_sections['image']['index'] - $this->_sections['image']['step'];
$this->_sections['image']['index_next'] = $this->_sections['image']['index'] + $this->_sections['image']['step'];
$this->_sections['image']['first']      = ($this->_sections['image']['iteration'] == 1);
$this->_sections['image']['last']       = ($this->_sections['image']['iteration'] == $this->_sections['image']['total']);
?>
						<?php echo $this->_tpl_vars['auction']->imagelist[$this->_sections['image']['index']]->thumbnail; ?>
 &nbsp; <input type="checkbox" name="delete_pictures[]" value="<?php echo $this->_tpl_vars['auction']->imagelist[$this->_sections['image']['index']]->id; ?>
"> <?php echo @bid_delete_picture; ?>

						<?php endfor; endif; ?>
				<div id="files">
					<input class="inputbox" <?php if (count($this->_tpl_vars['auction']->imagelist) >= @bid_opt_maxnr_images): ?>disabled<?php endif; ?>  id="my_file_element" type="file" name="pictures_1" >
					<div id="files_list"></div>
					<script>
					var multi_selector = new MultiSelector( document.getElementById('files_list'),<?php echo @bid_opt_maxnr_images; ?>
-<?php echo count($this->_tpl_vars['auction']->imagelist); ?>
 )
					multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>
				</div>
			 </td>
		</tr>
		<tr>
		  <td><?php echo @bid_link; ?>
</td>
		  <td><input class="inputbox" type="text" size="40" name="link_extern" value="<?php echo $this->_tpl_vars['auction']->link_extern; ?>
"></td>
		</tr>
		<tr>
			<td><?php echo @bid_start_date; ?>
 <img src="<?php echo @BIDS_COMPONENT; ?>
/images/required_field.png" border="0" style="margin:0px;" /></td>
			  <td>
			  			  <?php if (( $this->_tpl_vars['task'] == 'editauction' && $this->_tpl_vars['auction']->isValidateDate == 0 ) || ( $this->_tpl_vars['auction']->buyersChoiceManager == "Buyer's Choice" )): ?>
				<?php echo smarty_printdate(array('date' => $this->_tpl_vars['auction']->start_date,'use_hour' => @bid_opt_enable_hour), $this);?>

				<input type="hidden" name="start_date" id="start_date" size="15" maxlength="19" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['auction']->start_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m/%d/%Y") : smarty_modifier_date_format($_tmp, "%m/%d/%Y")); ?>
" alt="start_date"/>
				<input name="start_hour" size="1" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['auction']->start_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%H") : smarty_modifier_date_format($_tmp, "%H")); ?>
" alt="" class="inputbox" type="hidden"> :
				<input name="start_minutes" size="1" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['auction']->start_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%M") : smarty_modifier_date_format($_tmp, "%M")); ?>
" alt="" class="inputbox" type="hidden">
			  <?php else: ?>
				<input class="text_area" type="text" name="start_date" id="start_date" size="15" maxlength="19" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['auction']->start_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, $this->_tpl_vars['opt_date_format']) : smarty_modifier_date_format($_tmp, $this->_tpl_vars['opt_date_format'])); ?>
" alt="start_date"/>
				<input type="reset" class="button" value="..." onClick="return showCalendar('start_date');"><!--'y-mm-dd'-->
				<input name="start_hour" size="1" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['auction']->start_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%H") : smarty_modifier_date_format($_tmp, "%H")); ?>
" alt="" class="inputbox"> :
				<input name="start_minutes" size="1" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['auction']->start_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%M") : smarty_modifier_date_format($_tmp, "%M")); ?>
" alt="" class="inputbox">

			  <?php endif; ?>
			  			  </td>
		</tr>
		<tr>
			  <td><?php echo @bid_end_date; ?>
 <img src="<?php echo @BIDS_COMPONENT; ?>
/images/required_field.png" border="0" style="margin:0px;" /></td>
			  <td>
				  				  <?php if (( $this->_tpl_vars['task'] == 'editauction' && $this->_tpl_vars['auction']->isValidateDate == 0 ) || ( $this->_tpl_vars['auction']->buyersChoiceManager == "Buyer's Choice" )): ?>
					<?php echo smarty_printdate(array('date' => $this->_tpl_vars['auction']->end_date,'use_hour' => @bid_opt_enable_hour), $this);?>

					<input type="hidden" name="end_date" id="end_date" size="15" maxlength="19" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['auction']->end_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m/%d/%Y") : smarty_modifier_date_format($_tmp, "%m/%d/%Y")); ?>
" alt="end_date"/>
					<input name="end_hour" size="1" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['auction']->end_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%H") : smarty_modifier_date_format($_tmp, "%H")); ?>
" alt="" class="inputbox" type="hidden"> :
					<input name="end_minutes" size="1" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['auction']->end_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%M") : smarty_modifier_date_format($_tmp, "%M")); ?>
" alt="" class="inputbox"  type="hidden">
				  				  <?php else: ?>
					<input class="text_area" type="text" name="end_date" id="end_date" size="15" maxlength="19" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['auction']->end_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, $this->_tpl_vars['opt_date_format']) : smarty_modifier_date_format($_tmp, $this->_tpl_vars['opt_date_format'])); ?>
" alt="end_date"/>
					<input type="reset" class="button" value="..." onClick="return showCalendar('end_date');">
					<?php if (@bid_opt_enable_hour): ?>
						<input name="end_hour" size="1" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['auction']->end_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%H") : smarty_modifier_date_format($_tmp, "%H")); ?>
" alt="" class="inputbox"> :
						<input name="end_minutes" size="1" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['auction']->end_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%M") : smarty_modifier_date_format($_tmp, "%M")); ?>
" alt="" class="inputbox">
					<?php endif; ?>
				  <?php endif; ?>
			  </td>
		</tr>
		<tr>
			  <td><?php echo @bid_initial_price; ?>
 <img src="<?php echo @BIDS_COMPONENT; ?>
/images/required_field.png" border="0" style="margin:0px;" /></td>
			  <td><?php if ($this->_tpl_vars['task'] == 'editauction'): ?>
				<?php echo $this->_tpl_vars['auction']->currency_name; ?>
&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['auction']->initial_price)) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : number_format($_tmp, 0)); ?>

			  <?php else: ?>
				<input class="inputbox" type="text" size="7" name="initial_price" value="<?php echo $this->_tpl_vars['auction']->initial_price; ?>
" alt="initial_price">
				<?php echo $this->_tpl_vars['lists']['currency']; ?>

			  <?php endif; ?>
			  </td>
		</tr>	
		 <tr>
			  <td>
			  					<input class="inputbox" type="hidden" size="7" name="bin_OPTION" id="bin_OPTION" value="0" >
				<input class="inputbox" type="hidden" size="7" name="auction_type" id="auction_type" value="1" >
								<input class="inputbox" type="hidden" name="automatic" value="1" <?php if ($this->_tpl_vars['auction']->automatic): ?>checked<?php endif; ?>> 
				<?php echo @bid_payment; ?>
 <img src="<?php echo @BIDS_COMPONENT; ?>
/images/required_field.png" border="0" style="margin:0px;" />
				</td>
			  <td>
								<?php echo $this->_tpl_vars['auction']->payment_name; ?>

							  </td>
		 </tr>
		 <tr>
			  <td valign="top"><?php echo @bid_shipment_price; ?>
</td>
			  <td><input name="shipment_price" class="inputbox"  value="<?php echo $this->_tpl_vars['auction']->shipment_price; ?>
" /></td>
		 </tr>
		 <tr>
			  <td valign="top"><?php echo @bid_shipment; ?>
</td>
			  			  <td><?php echo $this->_tpl_vars['auction']->shipment_info; ?>
</td>
			  		 </tr>
		  <tr>
			<td><?php echo @bid_param_picture_text; ?>
: <?php echo smarty_infobullet(array('text' => @bid_param_picture_help), $this);?>
</td>
			<td>
			    <input type="radio" name="picture" value="1" <?php if ($this->_tpl_vars['parameters']['picture'] == '1'): ?>checked<?php endif; ?>><?php echo @bid_show; ?>

			    <input type="radio" name="picture" value="0" <?php if ($this->_tpl_vars['parameters']['picture'] != '1'): ?>checked<?php endif; ?>><?php echo @bid_hide; ?>

			</td>
		  </tr>
		    <tr>
			<td><?php echo @bid_param_add_picture_text; ?>
: <?php echo smarty_infobullet(array('text' => @bid_param_add_picture_help), $this);?>
</td>
			<td>
			    <input type="radio" name="add_picture" value="1" <?php if ($this->_tpl_vars['parameters']['add_picture'] == '1'): ?>checked<?php endif; ?>><?php echo @bid_show; ?>

			    <input type="radio" name="add_picture" value="0" <?php if ($this->_tpl_vars['parameters']['add_picture'] != '1'): ?>checked<?php endif; ?>><?php echo @bid_hide; ?>

			</td>
		    </tr>
		    <tr>
			<td align="left"><?php echo @bid_param_counts_text; ?>
: <?php echo smarty_infobullet(array('text' => @bid_param_counts_help), $this);?>
</td>
			<td>
						   <input type="hidden" name="auto_accept_bin" value="1" <?php if ($this->_tpl_vars['parameters']['auto_accept_bin'] == '1'): ?>checked<?php endif; ?>>
			   <input type="hidden" name="max_price" value="1" <?php if ($this->_tpl_vars['parameters']['max_price'] == '1'): ?>checked<?php endif; ?>>
						    <input type="radio" name="bid_counts" value="1" <?php if ($this->_tpl_vars['parameters']['bid_counts'] == '1'): ?>checked<?php endif; ?>><?php echo @bid_show; ?>

			    <input type="radio" name="bid_counts" value="0" <?php if ($this->_tpl_vars['parameters']['bid_counts'] != '1'): ?>checked<?php endif; ?>><?php echo @bid_hide; ?>

				
			</td>
		    </tr>
	</table>
      </td>
                  </tr>
   </table>
  <div id="zoom_pic" style="position:absolute;display:none;width:150px;left:300px"><img id="i_zoom_pic" src=""></div>
<?php echo smarty_endtab(array(), $this);?>

<?php echo smarty_endpane(array(), $this);?>

<br clear="all" />