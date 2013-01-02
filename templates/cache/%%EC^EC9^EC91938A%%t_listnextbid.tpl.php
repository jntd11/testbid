<?php /* Smarty version 2.6.18, created on 2009-11-17 08:18:58
         compiled from t_listnextbid.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'number_format', 't_listnextbid.tpl', 23, false),)), $this); ?>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/js/ratings.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/js/ui/jquery.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/js/ui/interface.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/js/ui/jquery.simplemodal.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/js/ui/jqueryForm.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/js/yui/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/js/bidinc.js"></script>
<form action="index.php?option=com_bids&Itemid=<?php echo $this->_tpl_vars['Itemid']; ?>
" method="post" name="auctionForm" onSubmit="return validate();">
<input type="hidden" name="task" id="task" value="savenextbid">
<tr id="" >	
	  <td  colspan="2">
		<table width="100%" cellpadding="1" cellspacing="0" border="0" style="">
		<tr>
			<td colspan="5" style="font-size: 14px; font-weight: bold;"><?php echo @nextbidTitle; ?>
</td>
		</tr>
		<tr>
			<td >
			$ <?php if ($this->_tpl_vars['auction_rows'][0]->bid_inc_id != ""): ?> <input type="hidden" name="bid_inc_id1" id="bid_inc_id1" value="<?php echo $this->_tpl_vars['auction_rows'][0]->bid_inc_id; ?>
"> <?php endif; ?>
			<input type="text" name="bid_incre1" id="bid_incre1" value="<?php if ($this->_tpl_vars['auction_rows'][0]->bid_next != ""): ?><?php echo $this->_tpl_vars['auction_rows'][0]->bid_next; ?>
<?php else: ?>50<?php endif; ?>">
			</td>
			<td ><?php echo @nextbidcol1; ?>
</td>
			<td >$ <span id="lblbid_range_from1"><?php if ($this->_tpl_vars['auction_rows'][0]->range_from != ""): ?><?php echo $this->_tpl_vars['auction_rows'][0]->range_from; ?>
<?php else: ?>1<?php endif; ?></span> <input type="hidden" name="bid_range_from1" id="bid_range_from1" value="<?php if ($this->_tpl_vars['auction_rows'][0]->range_from != ""): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['auction_rows'][0]->range_from)) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
<?php else: ?>1<?php endif; ?>"></td>
			<td ><?php echo @nextbidcol2; ?>
</td>
			<td >$ <input type="text" name="bid_range_to1" id="bid_range_to1" value="<?php if ($this->_tpl_vars['auction_rows'][0]->range_to != ""): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['auction_rows'][0]->range_to)) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
<?php else: ?><?php echo ((is_array($_tmp=999999)) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
<?php endif; ?>"></td>
		</tr>
		<tr>
			<td >$ 
			<?php if ($this->_tpl_vars['auction_rows'][1]->bid_inc_id != ""): ?> <input type="hidden" name="bid_inc_id2" id="bid_inc_id2" value="<?php echo $this->_tpl_vars['auction_rows'][1]->bid_inc_id; ?>
"> <?php endif; ?>
			<input type="text" name="bid_incre2" id="bid_incre2" onblur="fillrange(2);" value="<?php if ($this->_tpl_vars['auction_rows'][1]->bid_next != ""): ?><?php echo $this->_tpl_vars['auction_rows'][1]->bid_next; ?>
<?php endif; ?>"></td>
			<td ><?php echo @nextbidcol1; ?>
</td>
			<td >$ <span id="lblbid_range_from2"><?php if ($this->_tpl_vars['auction_rows'][1]->range_from != ""): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['auction_rows'][1]->range_from)) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
<?php endif; ?></span> <input type="hidden" name="bid_range_from2" id="bid_range_from2" value="<?php if ($this->_tpl_vars['auction_rows'][1]->range_from != ""): ?><?php echo $this->_tpl_vars['auction_rows'][1]->range_from; ?>
<?php endif; ?>"></td>
			<td ><?php echo @nextbidcol2; ?>
</td>
			<td >$ <input type="text" name="bid_range_to2" id="bid_range_to2" value="<?php if ($this->_tpl_vars['auction_rows'][1]->range_to != ""): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['auction_rows'][1]->range_to)) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
<?php endif; ?>"></td>
		</tr>
		<tr>
			<td >$ 
			<?php if ($this->_tpl_vars['auction_rows'][2]->bid_inc_id != ""): ?> <input type="hidden" name="bid_inc_id3" id="bid_inc_id3" value="<?php echo $this->_tpl_vars['auction_rows'][2]->bid_inc_id; ?>
"> <?php endif; ?>
			<input type="text" name="bid_incre3" id="bid_incre3" onblur="fillrange(3);"  value="<?php if ($this->_tpl_vars['auction_rows'][2]->bid_next != ""): ?><?php echo $this->_tpl_vars['auction_rows'][2]->bid_next; ?>
<?php endif; ?>"></td>
			<td ><?php echo @nextbidcol1; ?>
</td>
			<td >$ <span id="lblbid_range_from3"><?php if ($this->_tpl_vars['auction_rows'][2]->range_from != ""): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['auction_rows'][2]->range_from)) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
<?php endif; ?></span> <input type="hidden" name="bid_range_from3" id="bid_range_from3" value="<?php if ($this->_tpl_vars['auction_rows'][2]->range_from != ""): ?><?php echo $this->_tpl_vars['auction_rows'][2]->range_from; ?>
<?php endif; ?>"></td>
			<td ><?php echo @nextbidcol2; ?>
</td>
			<td >$ <input type="text" name="bid_range_to3" id="bid_range_to3" value="<?php if ($this->_tpl_vars['auction_rows'][2]->range_to != ""): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['auction_rows'][2]->range_to)) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
<?php endif; ?>"></td>
		</tr>
		<tr>
			<td >$ <?php if ($this->_tpl_vars['auction_rows'][3]->bid_inc_id != ""): ?> <input type="hidden" name="bid_inc_id4" id="bid_inc_id4" value="<?php echo $this->_tpl_vars['auction_rows'][3]->bid_inc_id; ?>
"> <?php endif; ?>
			<input type="text" name="bid_incre4" id="bid_incre4" onblur="fillrange(4);"  value="<?php if ($this->_tpl_vars['auction_rows'][3]->bid_next != ""): ?><?php echo $this->_tpl_vars['auction_rows'][3]->bid_next; ?>
<?php endif; ?>"></td>
			<td ><?php echo @nextbidcol1; ?>
</td>
			<td >$ <span id="lblbid_range_from4"><?php if ($this->_tpl_vars['auction_rows'][3]->range_from != ""): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['auction_rows'][3]->range_from)) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
<?php endif; ?></span> <input type="hidden" name="bid_range_from4" id="bid_range_from4" value="<?php if ($this->_tpl_vars['auction_rows'][3]->range_from != ""): ?><?php echo $this->_tpl_vars['auction_rows'][3]->range_from; ?>
<?php endif; ?>"></td>
			<td ><?php echo @nextbidcol2; ?>
</td>
			<td >$ <input type="text" name="bid_range_to4" id="bid_range_to4" value="<?php if ($this->_tpl_vars['auction_rows'][3]->range_to != ""): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['auction_rows'][3]->range_to)) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
<?php endif; ?>"></td>
		</tr>
		<tr>
			<td >$ <?php if ($this->_tpl_vars['auction_rows'][4]->bid_inc_id != ""): ?> <input type="hidden" name="bid_inc_id5" id="bid_inc_id5" value="<?php echo $this->_tpl_vars['auction_rows'][4]->bid_inc_id; ?>
"> <?php endif; ?>
			<input type="text" name="bid_incre5" id="bid_incre5" onblur="fillrange(5);"  value="<?php if ($this->_tpl_vars['auction_rows'][4]->bid_next != ""): ?><?php echo $this->_tpl_vars['auction_rows'][4]->bid_next; ?>
<?php endif; ?>"></td>
			<td ><?php echo @nextbidcol1; ?>
</td>
			<td >$ <span id="lblbid_range_from5"><?php if ($this->_tpl_vars['auction_rows'][4]->range_from != ""): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['auction_rows'][4]->range_from)) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
<?php endif; ?></span> <input type="hidden" name="bid_range_from5" id="bid_range_from5" value="<?php if ($this->_tpl_vars['auction_rows'][4]->range_from != ""): ?><?php echo $this->_tpl_vars['auction_rows'][4]->range_from; ?>
<?php endif; ?>"  value="<?php if ($this->_tpl_vars['auction_rows'][4]->range_to != ""): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['auction_rows'][4]->range_to)) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
<?php endif; ?>"></td>
			<td ><?php echo @nextbidcol2; ?>
</td>
			<td >$ <input type="text" name="bid_range_to5" id="bid_range_to5" value="<?php if ($this->_tpl_vars['auction_rows'][4]->range_to != ""): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['auction_rows'][4]->range_to)) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
<?php endif; ?>"></td>
		</tr>
		<tr id="alertmess"><td colspan="5" align="left" style="color: red; font-size: 12px;">Fill in as many lines as needed.</td></tr>
		<?php if ($this->_tpl_vars['expired'] != 1): ?>
		<tr id="rowbutton"><td colspan="5" align="left"><input type="submit" value="Save" class="button art-button"  id="saveinc" name="saveinc" onClick=""><input type="hidden" name="type" id="type" value="1"></td></tr>
		<?php endif; ?>
		</table>
	  </td>
</tr>