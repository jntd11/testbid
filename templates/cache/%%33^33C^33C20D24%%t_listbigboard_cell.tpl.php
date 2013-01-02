<?php /* Smarty version 2.6.18, created on 2011-06-03 11:23:26
         compiled from t_listbigboard_cell.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'cat', 't_listbigboard_cell.tpl', 7, false),array('modifier', 'string_format', 't_listbigboard_cell.tpl', 19, false),)), $this); ?>
<?php if ($this->_sections['auctionsloop']['iteration'] % 2 == 1): ?>
	<?php $this->assign('class', '1'); ?>
<?php else: ?>
	<?php $this->assign('class', '2'); ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['current_row']->featured && $this->_tpl_vars['current_row']->featured != 'none'): ?>
	<?php $this->assign('class_featured', ((is_array($_tmp="listing-")) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['current_row']->featured) : smarty_modifier_cat($_tmp, $this->_tpl_vars['current_row']->featured))); ?>
<?php else: ?>
	<?php $this->assign('class_featured', ""); ?>
<?php endif; ?>


		<tr id="row<?php echo $this->_tpl_vars['current_row']->id; ?>
" class="auction_bb_row<?php echo $this->_tpl_vars['class']; ?>
" style="margin-bottom: 10px;">
			<td class="col1" width="20%"><input type="hidden" id="pid<?php echo $this->_tpl_vars['current_row']->id; ?>
" name="pid<?php echo $this->_tpl_vars['current_row']->id; ?>
" value="<?php echo $this->_tpl_vars['current_row']->pageId; ?>
">
			&nbsp;&nbsp;<a href="index.php?option=com_bids&task=viewbids&orgtask=<?php echo $this->_tpl_vars['task']; ?>
&id=<?php echo $this->_tpl_vars['current_row']->id; ?>
&p=<?php echo $this->_tpl_vars['current_row']->pageId; ?>
" class="nolink"><?php echo $this->_tpl_vars['current_row']->title; ?>
</a></td>
			<td class="col5" width="20%"><a href="index.php?option=com_bids&task=viewbids&orgtask=<?php echo $this->_tpl_vars['task']; ?>
&id=<?php echo $this->_tpl_vars['current_row']->id; ?>
&p=<?php echo $this->_tpl_vars['current_row']->pageId; ?>
" class="nolink"><?php echo ((is_array($_tmp=$this->_tpl_vars['current_row']->shortdescription)) ? $this->_run_mod_handler('string_format', true, $_tmp, "%10.10s") : smarty_modifier_string_format($_tmp, "%10.10s")); ?>
</a></td>
			<td class="" width="4%"><?php if ($this->_tpl_vars['current_row']->currency_name == 'USD'): ?> $ <?php elseif ($this->_tpl_vars['current_row']->currency_name == ""): ?> $ <?php else: ?> <?php echo $this->_tpl_vars['current_row']->currency_name; ?>
 <?php endif; ?></td>
			<td class="col2" width="16%" id="colprice<?php echo $this->_tpl_vars['current_row']->id; ?>
" onBlur="checkbid();"><a href="index.php?option=com_bids&task=viewbids&orgtask=<?php echo $this->_tpl_vars['task']; ?>
&id=<?php echo $this->_tpl_vars['current_row']->id; ?>
&p=<?php echo $this->_tpl_vars['current_row']->pageId; ?>
" class="nolink"><?php if ($this->_tpl_vars['current_row']->bid_price != 0): ?> <?php echo $this->_tpl_vars['current_row']->bid_price; ?>
 <?php endif; ?></a></td>
			<td class="col3" width="20%" id="coluser<?php echo $this->_tpl_vars['current_row']->id; ?>
"><a href="index.php?option=com_bids&task=viewbids&orgtask=<?php echo $this->_tpl_vars['task']; ?>
&id=<?php echo $this->_tpl_vars['current_row']->id; ?>
&p=<?php echo $this->_tpl_vars['current_row']->pageId; ?>
" class="nolink"><?php if ($this->_tpl_vars['current_row']->bid_user != 0): ?><?php echo $this->_tpl_vars['current_row']->bid_user; ?>
<?php endif; ?></a></td>
			<td class="col2" width="4%"><?php if ($this->_tpl_vars['current_row']->currency_name == 'USD'): ?> $ <?php elseif ($this->_tpl_vars['current_row']->currency_name == ""): ?> $  <?php else: ?> <?php echo $this->_tpl_vars['current_row']->currency_name; ?>
 <?php endif; ?> </td>
			<td class="col4" width="16%" id="colnext<?php echo $this->_tpl_vars['current_row']->id; ?>
"><a href="index.php?option=com_bids&task=viewbids&orgtask=<?php echo $this->_tpl_vars['task']; ?>
&id=<?php echo $this->_tpl_vars['current_row']->id; ?>
&p=<?php echo $this->_tpl_vars['current_row']->pageId; ?>
" class="nolink"><?php echo $this->_tpl_vars['current_row']->bid_next; ?>
</a></td>
		</tr>
		<tr><td colspan="7"></td></tr>