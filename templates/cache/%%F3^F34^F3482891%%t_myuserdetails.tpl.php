<?php /* Smarty version 2.6.18, created on 2009-08-12 13:43:13
         compiled from t_myuserdetails.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'set_css', 't_myuserdetails.tpl', 5, false),array('function', 'cycle', 't_myuserdetails.tpl', 109, false),array('modifier', 'cat', 't_myuserdetails.tpl', 108, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 't_overlib.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php echo smarty_set_css(array(), $this);?>

<?php echo '
 <script language="javascript" type="text/javascript">
function formvalidate(  ) {
 	var form = document.auctionForm;
	// do field validation
	if (form.name.value == "") {
		alert( "'; ?>
<?php echo @_REGWARN_NAME; ?>
<?php echo '" );
		return false;
	} else if (form.surname.value == "") {
		alert( "'; ?>
<?php echo @bid_err_enter_surname; ?>
<?php echo '" );
		return false;
	} else if (form.country.value == 0) {
		alert( "'; ?>
<?php echo @bid_err_enter_country; ?>
<?php echo '" );
		return false;
	} else if (form.address.value == 0) {
		alert( "'; ?>
<?php echo @bid_err_enter_address; ?>
<?php echo '" );
		return false;
	} else if (form.city.value == 0) {
		alert( "'; ?>
<?php echo @bid_err_enter_city; ?>
<?php echo '" );
		return false;
	}
	return true;
 }
 </script>
 '; ?>

 <form action="index.php" method="post" name="auctionForm" onsubmit="return formvalidate()">
 <input type="hidden" name="Itemid" value="<?php echo $this->_tpl_vars['Itemid']; ?>
" />
 <input type="hidden" name="option" value="<?php echo $this->_tpl_vars['option']; ?>
">
 <input type="hidden" name="task" value="saveUserDetails" />

<div><a href="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/index.php?option=<?php echo $this->_tpl_vars['option']; ?>
&Itemid=<?php echo $this->_tpl_vars['Itemid']; ?>
&task=myratings"><?php echo @bid_my_ratings; ?>
</a></div>
 <div class="componentheading">
	 <?php echo @bid_edit_user_details; ?>

 </div>
 <input name="save" value="<?php echo @but_save; ?>
" class="back_button" type="submit">
 <table width="100%" border="0" cellpadding="0" cellspacing="0" class="userdetailstable">
  <tr>
   <td width=85><?php echo @bid_name; ?>
</td>
   <td>
	<input class="inputbox" type="text" name="name" value="<?php echo $this->_tpl_vars['user']->name; ?>
" size="40" />
   </td>
  </tr>

  <tr>
   <td width=85><?php echo @bid_surname; ?>
</td>
   <td>
	<input class="inputbox" type="text" name="surname" value="<?php echo $this->_tpl_vars['user']->surname; ?>
" size="40" />
   </td>
  </tr>

  <tr>
   <td width=85><?php echo @bid_address; ?>
</td>
   <td>
	<input class="inputbox" type="text" name="address" value="<?php echo $this->_tpl_vars['user']->address; ?>
" size="40" />
   </td>
  </tr>
 <tr>
   <td width=85><?php echo @bid_city; ?>
</td>
   <td>
	<input class="inputbox" type="text" name="city" value="<?php echo $this->_tpl_vars['user']->city; ?>
" size="40" />
   </td>
  </tr>



  <tr>
   <td width=85><?php echo @bid_country; ?>
</td>
   <td>
   <?php echo $this->_tpl_vars['lists']['country']; ?>

   </td>
  </tr>


  <tr>
   <td width=85><?php echo @bid_phone; ?>
</td>
   <td>
	<input class="inputbox" type="text" name="phone" value="<?php echo $this->_tpl_vars['user']->phone; ?>
" size="40" />
   </td>
  </tr>

  <?php if (@bid_opt_allowpaypal): ?>
      <tr>
       <td width=85><?php echo @bid_user_paypalemail; ?>
</td>
       <td>
    	<input class="inputbox" type="text" name="paypalemail" value="<?php echo $this->_tpl_vars['user']->paypalemail; ?>
" size="40" />
       </td>
      </tr>
  <?php endif; ?>

  <tr><td colspan="2"><hr></td></tr>
  <tr><td colspan="2"><?php echo @bid_payment_credits; ?>
</td></tr>
  <tr>
   <td colspan="2">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	   <th><?php echo @bid_payment_item; ?>
</th>
	   <th><?php echo @bid_payment_credits; ?>
</th>
	   <th><?php echo @bid_payment_item_price; ?>
</th>
	   <th>&nbsp;</th>
	</tr>
	  <?php unset($this->_sections['credit']);
$this->_sections['credit']['name'] = 'credit';
$this->_sections['credit']['loop'] = is_array($_loop=$this->_tpl_vars['credits']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['credit']['show'] = true;
$this->_sections['credit']['max'] = $this->_sections['credit']['loop'];
$this->_sections['credit']['step'] = 1;
$this->_sections['credit']['start'] = $this->_sections['credit']['step'] > 0 ? 0 : $this->_sections['credit']['loop']-1;
if ($this->_sections['credit']['show']) {
    $this->_sections['credit']['total'] = $this->_sections['credit']['loop'];
    if ($this->_sections['credit']['total'] == 0)
        $this->_sections['credit']['show'] = false;
} else
    $this->_sections['credit']['total'] = 0;
if ($this->_sections['credit']['show']):

            for ($this->_sections['credit']['index'] = $this->_sections['credit']['start'], $this->_sections['credit']['iteration'] = 1;
                 $this->_sections['credit']['iteration'] <= $this->_sections['credit']['total'];
                 $this->_sections['credit']['index'] += $this->_sections['credit']['step'], $this->_sections['credit']['iteration']++):
$this->_sections['credit']['rownum'] = $this->_sections['credit']['iteration'];
$this->_sections['credit']['index_prev'] = $this->_sections['credit']['index'] - $this->_sections['credit']['step'];
$this->_sections['credit']['index_next'] = $this->_sections['credit']['index'] + $this->_sections['credit']['step'];
$this->_sections['credit']['first']      = ($this->_sections['credit']['iteration'] == 1);
$this->_sections['credit']['last']       = ($this->_sections['credit']['iteration'] == $this->_sections['credit']['total']);
?>
            <?php $this->assign('txt', $this->_tpl_vars['credits'][$this->_sections['credit']['index']]->itemname); ?>
            <?php $this->assign('txt', ((is_array($_tmp='bid_payment_')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['txt']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['txt']))); ?>
    	  <tr class="mywatch<?php echo smarty_function_cycle(array('values' => "0,1"), $this);?>
">
        	  <td><?php echo @constant($this->_tpl_vars['txt']); ?>
</td>
        	  <td><?php if ($this->_tpl_vars['credits'][$this->_sections['credit']['index']]->amount): ?><?php echo $this->_tpl_vars['credits'][$this->_sections['credit']['index']]->amount; ?>
<?php else: ?>0<?php endif; ?></td>
        	  <td><?php echo $this->_tpl_vars['credits'][$this->_sections['credit']['index']]->price; ?>
&nbsp;<?php echo $this->_tpl_vars['credits'][$this->_sections['credit']['index']]->currency; ?>
</td>
        	  <td><a href="index.php?option=com_bids&task=<?php echo $this->_tpl_vars['credits'][$this->_sections['credit']['index']]->task_pay; ?>
"><?php echo @bid_payment_purchase; ?>
</a></td>
    	  </tr>
	  <?php endfor; endif; ?>
	</table>
   </td>
  </tr>
	  <?php if ($this->_tpl_vars['lists']['pricing_plugin']->enabled == '1'): ?>
	  <tr>
	   <td colspan="2"><strong><?php echo @bid_comissions_amount; ?>
&nbsp;</strong>
	   <?php $_from = $this->_tpl_vars['lists']['debts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['debt']):
?>
	   <?php if ($this->_tpl_vars['debt']->amount > 0): ?>	
	   	<?php echo $this->_tpl_vars['debt']->amount; ?>
 <?php echo $this->_tpl_vars['debt']->currency_name; ?>
	
	   <?php endif; ?>
	   <?php endforeach; endif; unset($_from); ?>	
	   	<a href="<?php echo $this->_tpl_vars['lists']['pay_comission_link']; ?>
"><?php echo @bid_pay_comission; ?>
</a>
	   </td>
	  </tr>
	  <?php endif; ?>
</table>
</form>
<br><h3><?php echo @bid_last_10_ratings; ?>
</h3>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 't_myratings.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
