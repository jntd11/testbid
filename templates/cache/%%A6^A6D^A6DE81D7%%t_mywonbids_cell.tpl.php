<?php /* Smarty version 2.6.18, created on 2009-11-18 06:17:40
         compiled from t_mywonbids_cell.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'cat', 't_mywonbids_cell.tpl', 12, false),array('modifier', 'number_format', 't_mywonbids_cell.tpl', 63, false),array('modifier', 'date_format', 't_mywonbids_cell.tpl', 99, false),)), $this); ?>
<?php if ((1 & $this->_tpl_vars['current_row']->rownr)): ?>
	<?php $this->assign('class', '1'); ?>
<?php else: ?>
	<?php $this->assign('class', '2'); ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['current_row']->featured && $this->_tpl_vars['current_row']->featured != 'none'): ?>
	<?php $this->assign('class_featured', ((is_array($_tmp="listing-")) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['current_row']->featured) : smarty_modifier_cat($_tmp, $this->_tpl_vars['current_row']->featured))); ?>
<?php else: ?>
	<?php $this->assign('class_featured', ""); ?>
<?php endif; ?>

    <tr id="auction_row<?php echo $this->_tpl_vars['class']; ?>
" class="<?php echo $this->_tpl_vars['class_featured']; ?>
">
		<td id="auction_thumb<?php echo $this->_tpl_vars['class']; ?>
"  valign="top">
			  <a href="<?php echo $this->_tpl_vars['current_row']->links['auctiondetails']; ?>
"><?php echo $this->_tpl_vars['current_row']->thumbnail; ?>
</a>
		</td>
		<td valign="top" id="auction_cell" >
			<table width="100%">
			<tr>
    			<td colspan="2" valign="top">
    				<div id="auction_title">
    					<a href="<?php echo $this->_tpl_vars['current_row']->links['auctiondetails']; ?>
"><?php echo $this->_tpl_vars['current_row']->title; ?>
</a>
    					<?php if ($this->_tpl_vars['current_row']->auction_type == @AUCTION_TYPE_PRIVATE): ?>
    							<span id="auction_private"><?php echo @bid_private; ?>
</span>
    					<?php endif; ?>
    				</div>
												<?php if (@bid_opt_allow_startdate == 1): ?>

    				<div id="auction_date">
    					<span><?php echo @bid_start_date; ?>
 </span>:&nbsp;<?php echo $this->_tpl_vars['current_row']->start_date_text; ?>

    				</div>
				<?php endif; ?>
				    			</td>
			</tr>
			<tr>
    			<td id="auction_middle" valign="top" colspan="2">
    				<div id="auction_container">
    					<div id="auction_info">
																					<?php if (@bid_opt_allow_auctioneer == 1): ?>
	    							<?php echo @bid_bid_auctioneer; ?>
:&nbsp;<a href="<?php echo $this->_tpl_vars['current_row']->links['otherauctions']; ?>
" alt="<?php echo @bid_more_offers_user; ?>
"><?php echo $this->_tpl_vars['current_row']->username; ?>
</a>
	    							<?php if ($this->_tpl_vars['current_row']->verified_auctioneer): ?><img src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/images/verified_1.gif"  id='auction_star' height="16" border="0" onmouseover="overlib('<?php echo @bid_user_verified; ?>
');" onmouseout="nd();"/><?php endif; ?>
							<?php endif; ?>
																												<?php if (@bid_opt_allow_rating == 1): ?>
								<?php echo @bid_rate_title; ?>
:<a href="<?php echo $this->_tpl_vars['current_row']->links['auctioneer_profile']; ?>
" alt="<?php echo @_DETAILS_TITLE; ?>
"><span id="rating_user" rating="<?php echo $this->_tpl_vars['current_row']->rating_overall; ?>
"></span></a>
								<?php if ($this->_tpl_vars['current_row']->must_rate): ?>
								    &nbsp;<a href="<?php echo $this->_tpl_vars['current_row']->links['rate_auction']; ?>
" alt="<?php echo @bid_rate; ?>
"><?php echo @bid_rate; ?>
</a>
								    <?php endif; ?>
							<?php endif; ?>
							    							<?php if ($this->_tpl_vars['current_row']->winning_bid): ?>
    								<br/><span id="auction_price_bold"><?php echo @bid_winning_bid; ?>
: <?php echo $this->_tpl_vars['current_row']->currency_name; ?>
&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['current_row']->winning_bid)) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : number_format($_tmp, 0)); ?>
</span>
    							<?php endif; ?>
								<?php if ($this->_tpl_vars['current_row']->nr_bidders): ?>
    								<br/><?php echo @bid_bidders; ?>
: <?php echo $this->_tpl_vars['current_row']->nr_bidders; ?>

    							<?php endif; ?>
    					</div>
    					<?php if ($this->_tpl_vars['current_row']->paypalemail): ?>
    					<div id="paypal_button">
                            <form name='paypalForm' action="https://www.paypal.com/cgi-bin/webscr" method="post" name="paypal">
                    		<input type="hidden" name="cmd" value="_xclick">
                    		<input type="hidden" name="business" value="<?php echo $this->_tpl_vars['current_row']->paypalemail; ?>
">
                    		<input type="hidden" name="item_name" value="<?php echo $this->_tpl_vars['current_row']->title; ?>
">
                    		<input type="hidden" name="item_number" value="<?php echo $this->_tpl_vars['current_row']->id; ?>
">
                    		<input type="hidden" name="invoice" value="<?php echo $this->_tpl_vars['current_row']->auction_nr; ?>
">
                    		<input type="hidden" name="amount" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['current_row']->total_price)) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : number_format($_tmp, 0)); ?>
">
                    		<input type="hidden" name="quantity" value="<?php echo $this->_tpl_vars['current_row']->nr_items; ?>
">
                    		<input type="hidden" name="return" value="<?php echo $this->_tpl_vars['current_row']->links['auctiondetails']; ?>
">
                    		<input type="hidden" name="tax" value="0" />
                    		<input type="hidden" name="rm" value="2" />
                    		<input type="hidden" name="no_note" value="1" />
                    		<input type="hidden" name="no_shipping" value="1" />
                    		<input type="hidden" name="currency_code" value="<?php echo $this->_tpl_vars['current_row']->currency_name; ?>
">
                    		<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but06.gif" name="submit" alt="<?php echo @bid_paypal_buynow; ?>
" style="margin-left: 50px;">

                            </form>
    					</div>
    					<?php endif; ?>
    				 </div>
					<div id="auction_info_bottom">
					<span class='canceled_on'>
					<?php if ($this->_tpl_vars['current_row']->end_date > $this->_tpl_vars['current_row']->closed_date && ! $this->_tpl_vars['current_row']->winning_bid): ?>
						<?php echo @bid_canceled_on; ?>

					<?php else: ?>
						<?php echo @bid_closed_on_date; ?>

					<?php endif; ?>:
					</span>
					<?php echo ((is_array($_tmp=$this->_tpl_vars['current_row']->closed_date)) ? $this->_run_mod_handler('date_format', true, $_tmp) : smarty_modifier_date_format($_tmp)); ?>

					</div>
    			</td>
			</tr>
			<tr>
    			<td colspan="2" valign="top">
    				<div id="auction_info_bottom">
    				<span id='new_message'><a href='<?php echo $this->_tpl_vars['current_row']->links['messages']; ?>
'>
    				<?php if ($this->_tpl_vars['current_row']->nr_new_messages): ?>
    					<img src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/images/f_message_1.png" title="<?php echo @bid_newmessages; ?>
" alt="<?php echo @bid_newmessages; ?>
" />
    				<?php else: ?>
    					<img src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/images/f_message_0.png" title="<?php echo @bid_no_new_messages; ?>
" alt="<?php echo @bid_no_new_messages; ?>
" />
    				<?php endif; ?>
    				</a></span>
    				<span id="auction_number"><?php echo @bid_auction_number; ?>
: <?php echo $this->_tpl_vars['current_row']->auction_nr; ?>
</span>
    				</div>
    			</td>
			</tr>
									<?php if (@bid_opt_allow_tag == 1): ?>
				<?php if ($this->_tpl_vars['current_row']->links['tags']): ?>
				<tr>
				<td colspan="2" valign="top">
					<?php echo @bid_tags; ?>
:&nbsp;<?php echo $this->_tpl_vars['current_row']->links['tags']; ?>

				</td>
				</tr>
				<?php endif; ?>
			<?php endif; ?>
									</table>
		</td>
	</tr>

