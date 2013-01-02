<?php /* Smarty version 2.6.18, created on 2009-08-03 13:07:03
         compiled from t_header_filter.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 't_header_filter.tpl', 2, false),)), $this); ?>
<?php if (count($this->_tpl_vars['filters']) > 0): ?>
<span id="auction_searchdetails">
<?php if ($this->_tpl_vars['task'] == 'showSearchResults'): ?>
    <?php echo @search_text; ?>

<?php elseif ($this->_tpl_vars['task'] == 'tags'): ?>
&nbsp;
<?php else: ?>
    <?php echo @bid_filter; ?>
 -
<?php endif; ?>
<?php $_from = $this->_tpl_vars['filters']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['filter']):
?>
    <?php echo $this->_tpl_vars['k']; ?>
: <?php echo $this->_tpl_vars['filter']; ?>
&nbsp;,
<?php endforeach; endif; unset($_from); ?>
<?php $_from = $this->_tpl_vars['sfilters']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['filter']):
?>
    <?php if ($this->_tpl_vars['sfilters'][$this->_tpl_vars['k']]): ?>
        <input type="hidden" name="<?php echo $this->_tpl_vars['k']; ?>
" value="<?php echo $this->_tpl_vars['sfilters'][$this->_tpl_vars['k']]; ?>
">
        <?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</span>
<?php endif; ?>