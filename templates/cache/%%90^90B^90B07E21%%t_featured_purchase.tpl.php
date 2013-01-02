<?php /* Smarty version 2.6.18, created on 2009-08-08 16:15:35
         compiled from t_featured_purchase.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'set_css', 't_featured_purchase.tpl', 4, false),array('function', 'infobullet', 't_featured_purchase.tpl', 29, false),array('modifier', 'substr', 't_featured_purchase.tpl', 16, false),array('modifier', 'cat', 't_featured_purchase.tpl', 27, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 't_overlib.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php echo smarty_set_css(array(), $this);?>


<h2><?php echo @bid_paymentitem_desc_featured; ?>
</h2>
<form method="POST" action="index.php" name="purchase_item">
<input name="option" type="hidden" value="com_bids">
<input name="task" type="hidden" value="purchase">
<input name="paymenttype" type="hidden" value="<?php echo $this->_tpl_vars['paymenttype']; ?>
">
<input name="act" type="hidden" value="checkout">
<input name="return_url" type="hidden" value="<?php echo $this->_tpl_vars['return_url']; ?>
">

    <?php unset($this->_sections['itemloop']);
$this->_sections['itemloop']['name'] = 'itemloop';
$this->_sections['itemloop']['loop'] = is_array($_loop=$this->_tpl_vars['pricing']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['itemloop']['show'] = true;
$this->_sections['itemloop']['max'] = $this->_sections['itemloop']['loop'];
$this->_sections['itemloop']['step'] = 1;
$this->_sections['itemloop']['start'] = $this->_sections['itemloop']['step'] > 0 ? 0 : $this->_sections['itemloop']['loop']-1;
if ($this->_sections['itemloop']['show']) {
    $this->_sections['itemloop']['total'] = $this->_sections['itemloop']['loop'];
    if ($this->_sections['itemloop']['total'] == 0)
        $this->_sections['itemloop']['show'] = false;
} else
    $this->_sections['itemloop']['total'] = 0;
if ($this->_sections['itemloop']['show']):

            for ($this->_sections['itemloop']['index'] = $this->_sections['itemloop']['start'], $this->_sections['itemloop']['iteration'] = 1;
                 $this->_sections['itemloop']['iteration'] <= $this->_sections['itemloop']['total'];
                 $this->_sections['itemloop']['index'] += $this->_sections['itemloop']['step'], $this->_sections['itemloop']['iteration']++):
$this->_sections['itemloop']['rownum'] = $this->_sections['itemloop']['iteration'];
$this->_sections['itemloop']['index_prev'] = $this->_sections['itemloop']['index'] - $this->_sections['itemloop']['step'];
$this->_sections['itemloop']['index_next'] = $this->_sections['itemloop']['index'] + $this->_sections['itemloop']['step'];
$this->_sections['itemloop']['first']      = ($this->_sections['itemloop']['iteration'] == 1);
$this->_sections['itemloop']['last']       = ($this->_sections['itemloop']['iteration'] == $this->_sections['itemloop']['total']);
?>
        <?php $this->assign('price', ($this->_tpl_vars['pricing'][$this->_sections['itemloop']['index']])); ?>
        <?php if (((is_array($_tmp=$this->_tpl_vars['price']->itemname)) ? $this->_run_mod_handler('substr', true, $_tmp, 0, 8) : substr($_tmp, 0, 8)) == 'featured'): ?>

            <?php $this->assign('txt', ((is_array($_tmp=$this->_tpl_vars['price']->itemname)) ? $this->_run_mod_handler('substr', true, $_tmp, 9) : substr($_tmp, 9))); ?>

            <?php if ($this->_tpl_vars['selected_type'] == $this->_tpl_vars['txt']): ?>
                <?php $this->assign('sel', "checked='yes'"); ?>
            <?php else: ?>
                <?php $this->assign('sel', ""); ?>
            <?php endif; ?>

            <input type="radio" name="itemname" value="<?php echo $this->_tpl_vars['price']->itemname; ?>
" <?php echo $this->_tpl_vars['sel']; ?>
>
            <?php $this->assign('txt', ((is_array($_tmp='bid_payment_featured_')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['txt']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['txt']))); ?>
            <?php $this->assign('txt2', ((is_array($_tmp=$this->_tpl_vars['txt'])) ? $this->_run_mod_handler('cat', true, $_tmp, '_help') : smarty_modifier_cat($_tmp, '_help'))); ?>
            <?php echo @constant($this->_tpl_vars['txt']); ?>
 <?php echo smarty_infobullet(array('text' => @constant($this->_tpl_vars['txt2'])), $this);?>
<br/>

        <?php endif; ?>
    <?php endfor; endif; ?>

<input type="submit" name="submit" class="inputbox" value="<?php echo @bid_payment_purchase; ?>
">

</form>