<?php /* Smarty version 2.6.18, created on 2009-08-04 13:56:23
         compiled from t_auctiondetails_plugins.tpl */ ?>
<?php if ($this->_tpl_vars['auction']->is_my_auction && ( $this->_tpl_vars['auction']->featured == 'none' || ! $this->_tpl_vars['auction']->featured ) && ! $this->_tpl_vars['auction']->expired && ! $this->_tpl_vars['auction']->close_offer): ?>

<?php $this->assign('gold_auction', ($this->_tpl_vars['mosConfig_live_site'])."/index.php?option=com_bids&task=set_featured&featured=gold&id=".($this->_tpl_vars['auction']->id)); ?>
<?php $this->assign('silver_auction', ($this->_tpl_vars['mosConfig_live_site'])."/index.php?option=com_bids&task=set_featured&featured=silver&id=".($this->_tpl_vars['auction']->id)); ?>
<?php $this->assign('bronze_auction', ($this->_tpl_vars['mosConfig_live_site'])."/index.php?option=com_bids&task=set_featured&featured=bronze&id=".($this->_tpl_vars['auction']->id)); ?>
    <?php if ($this->_tpl_vars['pricing_plugins']['featured_gold'] || $this->_tpl_vars['pricing_plugins']['featured_silver'] || $this->_tpl_vars['pricing_plugins']['featured_bronze']): ?>
        <table>
            <tr>
                <td colspan="3"><?php echo @bid_upgrade_listing; ?>
:</td>
            </tr>
            <tr>
            <?php if ($this->_tpl_vars['pricing_plugins']['featured_gold']): ?>
             <td><input type="button" onclick="window.location = '<?php echo $this->_tpl_vars['gold_auction']; ?>
';" class="back_button" value="<?php echo @bid_payment_featured_gold; ?>
">
             </td>
            <?php endif; ?>
            <?php if ($this->_tpl_vars['pricing_plugins']['featured_silver']): ?>
             <td>
                 <input type="button" onclick="window.location = '<?php echo $this->_tpl_vars['silver_auction']; ?>
';" class="back_button" value="<?php echo @bid_payment_featured_silver; ?>
">
             </td>
            <?php endif; ?>
            <?php if ($this->_tpl_vars['pricing_plugins']['featured_bronze']): ?>
             <td>
                 <input type="button" onclick="window.location = '<?php echo $this->_tpl_vars['bronze_auction']; ?>
';" class="back_button" value="<?php echo @bid_payment_featured_bronze; ?>
">
             </td>
            <?php endif; ?>
            </tr>
        </table>
    <?php endif; ?>
<?php endif; ?>