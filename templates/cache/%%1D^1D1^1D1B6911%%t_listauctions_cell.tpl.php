<?php /* Smarty version 2.6.18, created on 2009-11-10 10:03:46
         compiled from t_listauctions_cell.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'cat', 't_listauctions_cell.tpl', 8, false),array('modifier', 'number_format', 't_listauctions_cell.tpl', 60, false),array('modifier', 'date_format', 't_listauctions_cell.tpl', 99, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 't_javascript_language.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
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
">		
		
  							<td id="auction_thumb<?php echo $this->_tpl_vars['class']; ?>
"  valign="top">
			<a href="<?php if ($this->_tpl_vars['task'] == 'mybids'): ?><?php echo $this->_tpl_vars['current_row']->links['bid_list']; ?>
<?php else: ?><?php echo $this->_tpl_vars['current_row']->links['auctiondetails']; ?>
<?php endif; ?>"><?php echo $this->_tpl_vars['current_row']->thumbnail; ?>
</a>
		</td>
		<td valign="top" id="auction_cell" >
			<table width="100%">
			<tr>
    			<td colspan="2" valign="top">
    				<div id="auction_title">
    					<a href="<?php if ($this->_tpl_vars['task'] == 'mybids'): ?><?php echo $this->_tpl_vars['current_row']->links['bid_list']; ?>
<?php else: ?><?php echo $this->_tpl_vars['current_row']->links['auctiondetails']; ?>
<?php endif; ?>"><?php echo $this->_tpl_vars['current_row']->title; ?>
</a>
    					<?php if ($this->_tpl_vars['current_row']->auction_type == @AUCTION_TYPE_PRIVATE): ?>
    							<span id="auction_private"><?php echo @bid_private; ?>
</span>
    					<?php endif; ?>
    				</div>
    				<?php if ($this->_tpl_vars['current_row']->vin_number): ?>
    				<div>VIN: <?php echo $this->_tpl_vars['current_row']->vin_number; ?>
</div>
    				<?php endif; ?>
    			</td>
			</tr>
			<tr>
    			<td id="auction_middle" valign="top">
					<div id="auction_description">
															<?php if (@bid_opt_allow_category == 1): ?>
						<span id="auction_category"><?php echo @bid_category; ?>
&nbsp;
							<?php if ($this->_tpl_vars['current_row']->catname): ?>
								<a href="<?php echo $this->_tpl_vars['current_row']->links['filter_cat']; ?>
"><?php echo $this->_tpl_vars['current_row']->catname; ?>
</a><br/>
							<?php else: ?>
								&nbsp;-&nbsp;<br/>
							<?php endif; ?>
						</span>
					<?php endif; ?>
											<?php echo $this->_tpl_vars['current_row']->shortdescription; ?>

					</div>
    				<div id="auction_container">
    					<div id="auction_info">
    							<?php if ($this->_tpl_vars['current_row']->mybid): ?>
    								<span id="auction_price_bold"><?php echo @bid_mybid; ?>
: <?php echo ((is_array($_tmp=$this->_tpl_vars['current_row']->mybid)) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : number_format($_tmp, 0)); ?>
</span><br/>
    							<?php endif; ?>
																					<?php if (@bid_opt_allow_auctioneer == 1): ?>
									<?php echo @bid_bid_auctioneer; ?>
:&nbsp;<a href="<?php echo $this->_tpl_vars['current_row']->links['otherauctions']; ?>
" alt="<?php echo @bid_more_offers_user; ?>
"><?php echo $this->_tpl_vars['current_row']->username; ?>
</a><br/>
							<?php endif; ?>
														<?php if ($this->_tpl_vars['current_row']->verified_auctioneer): ?><img src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/images/verified_1.gif"  id='auction_star' height="16" border="0" onmouseover="overlib('<?php echo @bid_user_verified; ?>
');" onmouseout="nd();"/><?php endif; ?>
																					<?php if (@bid_opt_allow_rating == 1): ?>
								<?php echo @bid_rate_title; ?>
:<a href="<?php echo $this->_tpl_vars['current_row']->links['auctioneer_profile']; ?>
" alt="<?php echo @_DETAILS_TITLE; ?>
"><span id="rating_user" rating="<?php echo $this->_tpl_vars['current_row']->rating_overall; ?>
" ></span></a><br/>
							<?php endif; ?>
							    							<?php if ($this->_tpl_vars['current_row']->winning_bid): ?>
    								<?php echo @bid_winning_bid; ?>
: <?php echo $this->_tpl_vars['current_row']->currency_name; ?>
&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['current_row']->winning_bid)) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : number_format($_tmp, 0)); ?>

    							<?php elseif ($this->_tpl_vars['current_row']->highest_bid): ?>
    								<?php echo @bid_highest_bid; ?>
: <?php echo $this->_tpl_vars['current_row']->currency_name; ?>
&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['current_row']->highest_bid)) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : number_format($_tmp, 0)); ?>

    								<?php if ($this->_tpl_vars['current_row']->nr_bidders): ?>
    									&nbsp;(<?php echo @bid_bidders; ?>
: <?php echo $this->_tpl_vars['current_row']->nr_bidders; ?>
)
    								<?php endif; ?>
    							<?php else: ?>
    								<?php if ($this->_tpl_vars['current_row']->nr_bidders): ?>
    									<br/><?php echo @bid_bidders; ?>
: <?php echo $this->_tpl_vars['current_row']->nr_bidders; ?>

    								<?php endif; ?>
    							<?php endif; ?>

    					</div>
    				 </div>
    						<?php if ($this->_tpl_vars['current_row']->close_offer): ?>
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
    						<?php elseif ($this->_tpl_vars['current_row']->expired): ?>
    							<div id="auction_info_bottom">
    						   <font class='expired'><?php echo @bid_expired; ?>
</font>
    							</div>
    						<?php elseif (@bid_opt_enable_countdown): ?>
    							<div id="auction_info_bottom">
    							<?php echo @bid_expires_in; ?>
:&nbsp;<span id="time<?php echo $this->_tpl_vars['current_row']->rownr; ?>
"><?php echo $this->_tpl_vars['current_row']->countdown; ?>
</span>

    							</div>
    						<?php endif; ?>
    			</td>
    			<td id="auction_right"  valign="top">
    				<div id="auction_date">
    					<span><?php echo @bid_start_bid_text; ?>
</span>&nbsp;
    					<span id="auction_price_bold">
    						<?php echo $this->_tpl_vars['current_row']->currency_name; ?>
&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['current_row']->initial_price)) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>

    					</span>
    				</div>
												<?php if (@bid_opt_allow_bin == 1): ?>
					<div id="auction_price">
						<span><?php echo @bid_bin_text; ?>
</span>&nbsp;
						<span id="auction_price_bold">
						<?php if ($this->_tpl_vars['current_row']->BIN_price > 0): ?>
							<?php echo $this->_tpl_vars['current_row']->currency_name; ?>
&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['current_row']->BIN_price)) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : number_format($_tmp, 0)); ?>

						<?php else: ?>
							<?php echo @bid_no_bin; ?>

						<?php endif; ?>
						</span>
					</div>
				<?php endif; ?>
																<?php if (@bid_opt_allow_startdate == 1): ?>
					<div id="auction_date">
						<span><?php echo @bid_start_date; ?>
 </span>:&nbsp;<?php echo $this->_tpl_vars['current_row']->start_date_text; ?>

					</div>
				<?php endif; ?>
				    				<div id="auction_price">
    				<?php if ($this->_tpl_vars['current_row']->end_date): ?>
    					<span><?php echo @bid_end_date; ?>
</span>:&nbsp;<?php echo $this->_tpl_vars['current_row']->end_date_text; ?>

    				<?php endif; ?>
    				</div>
    				<div id="auction_info_bottom">
        				<?php if ($this->_tpl_vars['current_row']->add_to_watchlist): ?>
        					<span id='add_to_watchlist'><a href='<?php echo $this->_tpl_vars['current_row']->links['add_to_watchlist']; ?>
'>
        						<img src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/images/f_watchlist_1.jpg" title="<?php echo @bid_add_to_watchlist; ?>
" alt="<?php echo @bid_add_to_watchlist; ?>
"/>
        					</a></span>
        				   <?php elseif ($this->_tpl_vars['current_row']->del_from_watchlist): ?>
        					<span id='add_to_watchlist'><a href='<?php echo $this->_tpl_vars['current_row']->links['del_from_watchlist']; ?>
'>
        						<img src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/images/f_watchlist_0.jpg" title="<?php echo @bid_remove_from_watchlist; ?>
" alt="<?php echo @bid_remove_from_watchlist; ?>
"/>
        					</a></span>
        				<?php endif; ?>
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
        				<?php if ($this->_tpl_vars['current_row']->is_my_auction != 1 && ! $this->_tpl_vars['current_row']->close_offer): ?>
        				<a href='<?php echo $this->_tpl_vars['current_row']->links['bids']; ?>
'><img src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/images/f_bid.gif" title="<?php echo @bid_tab_offer_bidnew; ?>
" alt="<?php echo @bid_tab_offer_bidnew; ?>
" /></a>
        				<?php endif; ?>
    				</div>
    			</td>
			</tr>
			<tr>
    			<td colspan="2" valign="top">
    				<div id="auction_info_bottom">
						<?php if (@bid_opt_allow_tag == 1): ?>
				<?php if ($this->_tpl_vars['current_row']->links['tags'] != ""): ?>
                				<?php echo @bid_tags; ?>
:&nbsp;<?php echo $this->_tpl_vars['current_row']->links['tags']; ?>

            			<?php endif; ?>
		<?php endif; ?>
		        				<span id="auction_number"><?php echo @bid_auction_number; ?>
: <?php echo $this->_tpl_vars['current_row']->auction_nr; ?>
</span>
    				</div>
    			</td>
			</tr>
			</table>
		</td>
	</tr>