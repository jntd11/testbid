<?php /* Smarty version 2.6.18, created on 2009-11-16 07:04:39
         compiled from t_myauctions_cell.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 't_myauctions_cell.tpl', 75, false),array('modifier', 'number_format', 't_myauctions_cell.tpl', 91, false),)), $this); ?>
<?php if ((1 & $this->_tpl_vars['current_row']->rownr)): ?>
	<?php $this->assign('class', '1'); ?>
<?php else: ?>
	<?php $this->assign('class', '2'); ?>
<?php endif; ?>
    <tr id="auction_row<?php echo $this->_tpl_vars['class']; ?>
">
		<td id="auction_thumb<?php echo $this->_tpl_vars['class']; ?>
"  valign="top">
			  <?php echo $this->_tpl_vars['current_row']->thumbnail; ?>

		</td>
		<td id="auction_cell">
    		<table width="100%">
    		<tr>
        		<td id="auction_middle" valign="top">
        			<div id="auction_title">
        				<a href="<?php echo $this->_tpl_vars['current_row']->links['auctiondetails']; ?>
"><?php echo $this->_tpl_vars['current_row']->title; ?>
</a>
        			</div>
        			<div id="auction_description">
        				<?php echo $this->_tpl_vars['current_row']->shortdescription; ?>

        			</div>
        		<div id="auction_container">
        			<div id="auction_info">
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
							   				<span id='new_message'><a href='<?php echo $this->_tpl_vars['current_row']->links['messages']; ?>
'>
            				<?php if ($this->_tpl_vars['current_row']->nr_new_messages): ?>
            					<img src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/images/f_message_1.png" title="<?php echo @bid_newmessages; ?>
" alt="<?php echo @bid_newmessages; ?>
" /><br/>
            				<?php else: ?>
            					<img src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/images/f_message_0.png" title="<?php echo @bid_no_new_messages; ?>
" alt="<?php echo @bid_no_new_messages; ?>
" /><br/>
            				<?php endif; ?>
            				</a></span>
             				<?php if ($this->_tpl_vars['current_row']->winning_bid): ?>
             				    <?php echo @bid_winning_bid; ?>
 <?php echo $this->_tpl_vars['current_row']->winning_bid; ?>

             				<?php elseif ($this->_tpl_vars['current_row']->highest_bid): ?>
             				    <?php echo @bid_highest_bid; ?>
 <?php echo $this->_tpl_vars['current_row']->highest_bid; ?>

             				<?php endif; ?>
             				<?php if ($this->_tpl_vars['current_row']->nr_bidders): ?>
             				    <br/><?php echo @bid_bidders; ?>
: <?php echo $this->_tpl_vars['current_row']->nr_bidders; ?>

             				<?php endif; ?>

        			</div>
        		 	<div id="auction_buttons">
        				<?php if ($this->_tpl_vars['current_row']->add_to_watchlist): ?>
        				        <span id='add_to_watchlist'><a href='<?php echo $this->_tpl_vars['current_row']->links['add_to_watchlist']; ?>
'><?php echo @bid_add_to_watchlist; ?>
</a></span>
        				   <?php elseif ($this->_tpl_vars['current_row']->del_from_watchlist): ?>
            				    <span id='add_to_watchlist'><a href='<?php echo $this->_tpl_vars['current_row']->links['del_from_watchlist']; ?>
'><?php echo @bid_remove_from_watchlist; ?>
</a></span>
        			    <?php endif; ?>
        	     	</div>
        	     </div>
        		 <div id="auction_info_bottom">
        				<?php if ($this->_tpl_vars['current_row']->close_offer): ?>
        				    <span class='canceled_on'>
        				    <?php if ($this->_tpl_vars['current_row']->end_date > $this->_tpl_vars['current_row']->closed_date): ?>
        				        <?php echo @bid_canceled_on; ?>

        				    <?php else: ?>
        				        <?php echo @bid_closed_on_date; ?>

        				    <?php endif; ?>:
        				    </span>
        				    <?php echo ((is_array($_tmp=$this->_tpl_vars['current_row']->closed_date)) ? $this->_run_mod_handler('date_format', true, $_tmp) : smarty_modifier_date_format($_tmp)); ?>

                        <?php elseif ($this->_tpl_vars['current_row']->expired): ?>
        		           <font class='expired'><?php echo @bid_expired; ?>
</font><br>
        		        <?php endif; ?>
						<?php if (@bid_opt_allow_tag == 1): ?>
        		        <?php if ($this->_tpl_vars['current_row']->links['tags']): ?><?php echo @bid_tags; ?>
:&nbsp;<?php echo $this->_tpl_vars['current_row']->links['tags']; ?>
<?php endif; ?>
		<?php endif; ?>
		        	    </div>
        		</td>
        		<td id="auction_right"  valign="top">
        		 	<div id="auction_date">
        				<span><?php echo @bid_start_bid_text; ?>
</span>&nbsp;
        				<span id="auction_price_bold">
        				    <?php echo $this->_tpl_vars['current_row']->currency_name; ?>
&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['current_row']->initial_price)) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : number_format($_tmp, 0)); ?>

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
						<?php if ($this->_tpl_vars['current_row']->auction_type == @AUCTION_TYPE_PRIVATE): ?>
						    <br/><?php echo @bid_private; ?>

						<?php endif; ?>
					</div>
				<?php endif; ?>
				       												<?php if (@bid_opt_allow_startdate == 1): ?>
					<div id="auction_date">
					<span><?php echo @bid_start_date; ?>
 </span>&nbsp;<?php echo $this->_tpl_vars['current_row']->start_date_text; ?>

					</div>
				<?php endif; ?>
				        		 	<div id="auction_price">
        		 	<?php if ($this->_tpl_vars['current_row']->end_date): ?>
        				<span><?php echo @bid_end_date; ?>
</span>&nbsp;<?php echo $this->_tpl_vars['current_row']->end_date_text; ?>


        				<?php if (@bid_opt_enable_countdown && ! $this->_tpl_vars['current_row']->expired && ! $this->_tpl_vars['current_row']->close_offer): ?>
        				<br/><?php echo @bid_expires_in; ?>
:&nbsp;<span id="time<?php echo $this->_tpl_vars['current_row']->rownr; ?>
"><?php echo $this->_tpl_vars['current_row']->countdown; ?>
</span>
        				<?php endif; ?>
        			<?php endif; ?>
        			</div>
        			<div id="auction_info_bottom">
        			    <span id="auction_number"><?php echo @bid_auction_number; ?>
: <?php echo $this->_tpl_vars['current_row']->auction_nr; ?>
</span>
        			    <?php if ($this->_tpl_vars['current_row']->expired || $this->_tpl_vars['current_row']->close_offer): ?>
        			    <input type="button" onclick="window.location = '<?php echo $this->_tpl_vars['current_row']->links['republish']; ?>
';" class="back_button" value="<?php echo @bid_img_repub_offer; ?>
">
        			    <?php endif; ?>
              		</div>

        		</td>
        	</tr>
            </table>
		</td>
	</tr>
