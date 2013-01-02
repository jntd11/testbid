<?php /* Smarty version 2.6.18, created on 2009-09-18 06:55:09
         compiled from t_mywatchlist.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'set_css', 't_mywatchlist.tpl', 3, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 't_overlib.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php echo smarty_set_css(array(), $this);?>


<form action="index.php" method="get" name="auctionForm">
<input type="hidden" name="option" value="com_bids">
<input type="hidden" name="task" value="<?php echo $this->_tpl_vars['task']; ?>
">
<input type="hidden" name="Itemid" value="<?php echo $this->_tpl_vars['Itemid']; ?>
">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 't_header_filter.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<h2><?php echo @bid_my_watchlist; ?>
</h2>

<script type="text/javascript" src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/js/ratings.js"></script>

<table width="60%" cellpadding="0" cellspacing="0" border="0" id="auction_list_container" >
<tr>
	<td width="20%"><?php echo $this->_tpl_vars['lists']['filter_order_asc']; ?>
</td>
	<td width="20%"><?php echo $this->_tpl_vars['lists']['filter_cats']; ?>
</td>
	<td width="30%"><nobr><?php echo @bid_order_by; ?>
:&nbsp;<?php echo $this->_tpl_vars['lists']['orders']; ?>
</nobr></td>
</tr>
</table>
<table align="center" cellpadding="0" cellspacing="0" width="100%" id="auction_list_container">
    <?php unset($this->_sections['auctionsloop']);
$this->_sections['auctionsloop']['name'] = 'auctionsloop';
$this->_sections['auctionsloop']['loop'] = is_array($_loop=$this->_tpl_vars['auction_rows']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['auctionsloop']['show'] = true;
$this->_sections['auctionsloop']['max'] = $this->_sections['auctionsloop']['loop'];
$this->_sections['auctionsloop']['step'] = 1;
$this->_sections['auctionsloop']['start'] = $this->_sections['auctionsloop']['step'] > 0 ? 0 : $this->_sections['auctionsloop']['loop']-1;
if ($this->_sections['auctionsloop']['show']) {
    $this->_sections['auctionsloop']['total'] = $this->_sections['auctionsloop']['loop'];
    if ($this->_sections['auctionsloop']['total'] == 0)
        $this->_sections['auctionsloop']['show'] = false;
} else
    $this->_sections['auctionsloop']['total'] = 0;
if ($this->_sections['auctionsloop']['show']):

            for ($this->_sections['auctionsloop']['index'] = $this->_sections['auctionsloop']['start'], $this->_sections['auctionsloop']['iteration'] = 1;
                 $this->_sections['auctionsloop']['iteration'] <= $this->_sections['auctionsloop']['total'];
                 $this->_sections['auctionsloop']['index'] += $this->_sections['auctionsloop']['step'], $this->_sections['auctionsloop']['iteration']++):
$this->_sections['auctionsloop']['rownum'] = $this->_sections['auctionsloop']['iteration'];
$this->_sections['auctionsloop']['index_prev'] = $this->_sections['auctionsloop']['index'] - $this->_sections['auctionsloop']['step'];
$this->_sections['auctionsloop']['index_next'] = $this->_sections['auctionsloop']['index'] + $this->_sections['auctionsloop']['step'];
$this->_sections['auctionsloop']['first']      = ($this->_sections['auctionsloop']['iteration'] == 1);
$this->_sections['auctionsloop']['last']       = ($this->_sections['auctionsloop']['iteration'] == $this->_sections['auctionsloop']['total']);
?>
        <?php $this->assign('current_row', ($this->_tpl_vars['auction_rows'][$this->_sections['auctionsloop']['index']])); ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 't_mywatchlist_cell.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endfor; endif; ?>
</table>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 't_listfooter.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/js/startcounter.js"></script>
</form>
															<script type="text/javascript" src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/js/auctions.js"></script>
					