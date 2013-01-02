<?php /* Smarty version 2.6.18, created on 2009-08-12 13:43:17
         compiled from t_myratings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'set_css', 't_myratings.tpl', 2, false),array('function', 'cycle', 't_myratings.tpl', 12, false),)), $this); ?>
<?php echo smarty_set_css(array(), $this);?>

<?php if ($this->_tpl_vars['task'] == 'myratings'): ?><h2><?php echo @bid_user_ratings; ?>
 : <?php echo $this->_tpl_vars['user']->username; ?>
</h2><?php endif; ?>
<table width="100%">
<?php if (count ( $this->_tpl_vars['ratings'] ) > 0): ?>
	 <tr>
		<th class="list_ratings_header"><?php echo @bid_username; ?>
</th>
		<th class="list_ratings_header"><?php echo @bid_bid_title; ?>
</th>
		<th class="list_ratings_header"><?php echo @bid_bid_rate; ?>
</th>
	 </tr>
    <?php unset($this->_sections['ratingsloop']);
$this->_sections['ratingsloop']['name'] = 'ratingsloop';
$this->_sections['ratingsloop']['loop'] = is_array($_loop=$this->_tpl_vars['ratings']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ratingsloop']['show'] = true;
$this->_sections['ratingsloop']['max'] = $this->_sections['ratingsloop']['loop'];
$this->_sections['ratingsloop']['step'] = 1;
$this->_sections['ratingsloop']['start'] = $this->_sections['ratingsloop']['step'] > 0 ? 0 : $this->_sections['ratingsloop']['loop']-1;
if ($this->_sections['ratingsloop']['show']) {
    $this->_sections['ratingsloop']['total'] = $this->_sections['ratingsloop']['loop'];
    if ($this->_sections['ratingsloop']['total'] == 0)
        $this->_sections['ratingsloop']['show'] = false;
} else
    $this->_sections['ratingsloop']['total'] = 0;
if ($this->_sections['ratingsloop']['show']):

            for ($this->_sections['ratingsloop']['index'] = $this->_sections['ratingsloop']['start'], $this->_sections['ratingsloop']['iteration'] = 1;
                 $this->_sections['ratingsloop']['iteration'] <= $this->_sections['ratingsloop']['total'];
                 $this->_sections['ratingsloop']['index'] += $this->_sections['ratingsloop']['step'], $this->_sections['ratingsloop']['iteration']++):
$this->_sections['ratingsloop']['rownum'] = $this->_sections['ratingsloop']['iteration'];
$this->_sections['ratingsloop']['index_prev'] = $this->_sections['ratingsloop']['index'] - $this->_sections['ratingsloop']['step'];
$this->_sections['ratingsloop']['index_next'] = $this->_sections['ratingsloop']['index'] + $this->_sections['ratingsloop']['step'];
$this->_sections['ratingsloop']['first']      = ($this->_sections['ratingsloop']['iteration'] == 1);
$this->_sections['ratingsloop']['last']       = ($this->_sections['ratingsloop']['iteration'] == $this->_sections['ratingsloop']['total']);
?>
	 	 <tr class="myrating<?php echo smarty_function_cycle(array('values' => '0,1'), $this);?>
">
	 		<td width="15%" >
	 			<a href='<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/index.php?option=com_bids&task=ViewDetails&id=<?php echo $this->_tpl_vars['ratings'][$this->_sections['ratingsloop']['index']]->voter; ?>
&Itemid=<?php echo $this->_tpl_vars['Itemid']; ?>
'><?php echo $this->_tpl_vars['ratings'][$this->_sections['ratingsloop']['index']]->username; ?>
</a>
	 		</td>
	 		<td width="*%">
	 			<a href='<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/index.php?option=com_bids&task=viewbids&id=<?php echo $this->_tpl_vars['ratings'][$this->_sections['ratingsloop']['index']]->auction_id; ?>
&Itemid=<?php echo $this->_tpl_vars['Itemid']; ?>
'><?php echo $this->_tpl_vars['ratings'][$this->_sections['ratingsloop']['index']]->title; ?>
</a>
	 		</td>
	 		<td width="5%">
	 		    <?php echo $this->_tpl_vars['ratings'][$this->_sections['ratingsloop']['index']]->rating; ?>

	 		</td>
	 	</tr>
	 	 <tr class="myrating<?php echo smarty_function_cycle(array('values' => '0,1'), $this);?>
">
	 		<td colspan="3" >
	 		      <div class="msg_text"><?php echo $this->_tpl_vars['ratings'][$this->_sections['ratingsloop']['index']]->message; ?>
</div>
	 		</td>
		 </tr>
    <?php endfor; endif; ?>
<?php else: ?>
      <tr>
      	<td><?php echo @bid_no_ratings; ?>
</td>
      </tr>
<?php endif; ?>
</table>