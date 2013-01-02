<?php /* Smarty version 2.6.18, created on 2012-05-12 11:12:36
         compiled from t_mywatchlistpicture_cell.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'cat', 't_mywatchlistpicture_cell.tpl', 9, false),array('modifier', 'date_format', 't_mywatchlistpicture_cell.tpl', 27, false),array('modifier', 'number_format', 't_mywatchlistpicture_cell.tpl', 76, false),array('function', 'infobullet', 't_mywatchlistpicture_cell.tpl', 143, false),)), $this); ?>
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

											<form action="index.php" method="post" name="auctionForm" onsubmit="return FormValidate2(<?php echo $this->_tpl_vars['current_row']->id; ?>
);">
		  <input type="hidden" name="option" value="com_bids">
		  <input type="hidden" name="pageId" value="<?php echo $this->_tpl_vars['current_row']->pageId; ?>
">
		  <input type="hidden" name="task" value="sendbid">
		  <input type="hidden" name="id" value="<?php echo $this->_tpl_vars['current_row']->id; ?>
">
		  <input type="hidden" name="leadbid" id="leadbid<?php echo $this->_tpl_vars['current_row']->id; ?>
" value="<?php echo $this->_tpl_vars['current_row']->mine; ?>
">
		  <input type="hidden" name="initial_price<?php echo $this->_tpl_vars['current_row']->id; ?>
" id="initial_price<?php echo $this->_tpl_vars['current_row']->id; ?>
" value="<?php echo $this->_tpl_vars['current_row']->initial_price; ?>
">
		  <input type="hidden" name="bin_price<?php echo $this->_tpl_vars['current_row']->id; ?>
" id="bin_price<?php echo $this->_tpl_vars['current_row']->id; ?>
" value="<?php echo $this->_tpl_vars['current_row']->BIN_price; ?>
">
		  <input type="hidden" name="mylastbid<?php echo $this->_tpl_vars['current_row']->id; ?>
" id="mylastbid<?php echo $this->_tpl_vars['current_row']->id; ?>
" value="<?php echo $this->_tpl_vars['current_row']->bid_price; ?>
">
		  <input type="hidden" name="min_increase<?php echo $this->_tpl_vars['current_row']->id; ?>
"  id="min_increase<?php echo $this->_tpl_vars['current_row']->id; ?>
" value="<?php echo $this->_tpl_vars['current_row']->min_increase; ?>
">
		  <input type="hidden" name="start_date<?php echo $this->_tpl_vars['current_row']->id; ?>
" id="start_date<?php echo $this->_tpl_vars['current_row']->id; ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['current_row']->start_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m/%d/%Y %H:%M") : smarty_modifier_date_format($_tmp, "%m/%d/%Y %H:%M")); ?>
">
		  <input type="hidden" name="maxbid<?php echo $this->_tpl_vars['current_row']->id; ?>
" id="maxbid<?php echo $this->_tpl_vars['current_row']->id; ?>
" value="<?php echo $this->_tpl_vars['current_row']->bid_price; ?>
">
         	  <input type="hidden" name="Itemid" value="<?php echo $this->_tpl_vars['Itemid']; ?>
">
		  <input type="hidden" name="auction_id" id="auction_id<?php echo $this->_tpl_vars['current_row']->id; ?>
" value="<?php echo $this->_tpl_vars['current_row']->id; ?>
">
    <tr id="auction_row<?php echo $this->_tpl_vars['class']; ?>
">		
		
  							<td id="auction_thumb<?php echo $this->_tpl_vars['class']; ?>
_old"  valign="top" width="20%">
			<a href="<?php if ($this->_tpl_vars['task'] == 'mybids'): ?><?php echo $this->_tpl_vars['current_row']->links['bid_list']; ?>
<?php else: ?><?php echo $this->_tpl_vars['current_row']->links['auctiondetails']; ?>
<?php endif; ?>"><?php echo $this->_tpl_vars['current_row']->gallery; ?>
</a>
			<?php if ($this->_tpl_vars['current_row']->link_extern != ""): ?>
			<br>
			<input type="button" onclick="window.open('<?php echo $this->_tpl_vars['current_row']->link_extern; ?>
','targetWindow', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=720,height=480')" class="button art-button" id="refresh" value="<?php echo @external_link; ?>
">
			<?php endif; ?>
		</td>
		<td valign="top" id="auction_cell" width="80%">
			<table width="100%">
			<tr>
    			<td colspan="2" valign="top">
				<table width="100%">
				<tr>
										<td width="10%">
						<div id="auction_title">
							<a class="linkpicture" href="<?php if ($this->_tpl_vars['task'] == 'mybids'): ?><?php echo $this->_tpl_vars['current_row']->links['bid_list']; ?>
<?php else: ?><?php echo $this->_tpl_vars['current_row']->links['auctiondetails']; ?>
<?php endif; ?>"><span id="auctitlelabel<?php echo $this->_tpl_vars['current_row']->id; ?>
"><?php echo @auction_title; ?>
</span></a>
							<a class="linkpicture" href="<?php if ($this->_tpl_vars['task'] == 'mybids'): ?><?php echo $this->_tpl_vars['current_row']->links['bid_list']; ?>
<?php else: ?><?php echo $this->_tpl_vars['current_row']->links['auctiondetails']; ?>
<?php endif; ?>">
							<span id="auctitle<?php echo $this->_tpl_vars['current_row']->id; ?>
"><?php echo $this->_tpl_vars['current_row']->title; ?>
</span>
							</a>
							<?php if ($this->_tpl_vars['current_row']->auction_type == @AUCTION_TYPE_PRIVATE): ?>
									<span id="auction_private"><?php echo @bid_private; ?>
</span>
							<?php endif; ?>
						</div>
					</td>
					<td width="20%">
						<a class="linkpicture" href="<?php if ($this->_tpl_vars['task'] == 'mybids'): ?><?php echo $this->_tpl_vars['current_row']->links['bid_list']; ?>
<?php else: ?><?php echo $this->_tpl_vars['current_row']->links['auctiondetails']; ?>
<?php endif; ?>"><span id="labelshortdesc<?php echo $this->_tpl_vars['current_row']->id; ?>
"><?php echo @label_short_desc; ?>
</span></a>
						<a class="linkpicture" href="<?php if ($this->_tpl_vars['task'] == 'mybids'): ?><?php echo $this->_tpl_vars['current_row']->links['bid_list']; ?>
<?php else: ?><?php echo $this->_tpl_vars['current_row']->links['auctiondetails']; ?>
<?php endif; ?>"><span id="shortdesc<?php echo $this->_tpl_vars['current_row']->id; ?>
"><?php echo $this->_tpl_vars['current_row']->shortdescription; ?>
</span></a>
					</td>
					<td width="45%">
											<a class="linkpicture" href="<?php if ($this->_tpl_vars['task'] == 'mybids'): ?><?php echo $this->_tpl_vars['current_row']->links['bid_list']; ?>
<?php else: ?><?php echo $this->_tpl_vars['current_row']->links['auctiondetails']; ?>
<?php endif; ?>"><?php echo @label_custom_field1; ?>
</a>
						<a class="linkpicture" href="<?php if ($this->_tpl_vars['task'] == 'mybids'): ?><?php echo $this->_tpl_vars['current_row']->links['bid_list']; ?>
<?php else: ?><?php echo $this->_tpl_vars['current_row']->links['auctiondetails']; ?>
<?php endif; ?>"><?php echo $this->_tpl_vars['current_row']->custom_fld1; ?>
</a>
					</td>
					<td width="35%">
					<div id="auction_date">
						<span><?php echo @bid_bid_price; ?>
</span>&nbsp;
						<span id="auction_price_bold">
							<?php if ($this->_tpl_vars['current_row']->bid_price != 0): ?>
								<?php echo $this->_tpl_vars['current_row']->currency_name; ?>
&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['current_row']->bid_price)) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : number_format($_tmp, 0)); ?>

							<?php endif; ?>
						</span>
					</div>
					<div id="auction_date">
						<span><?php echo @current_bidder; ?>
</span>&nbsp;
						<span id="auction_price_bold">
							<?php echo $this->_tpl_vars['current_row']->current_bidder; ?>

						</span>
					</div>
					</td>
				</tr>
				<tr>
		<?php if ($this->_tpl_vars['optionRowspan'] == 2): ?>
					<td colspan="3" rowspan="4" valign="top">
						<a class="linkpicture" href="<?php if ($this->_tpl_vars['task'] == 'mybids'): ?><?php echo $this->_tpl_vars['current_row']->links['bid_list']; ?>
<?php else: ?><?php echo $this->_tpl_vars['current_row']->links['auctiondetails']; ?>
<?php endif; ?>"><?php echo $this->_tpl_vars['current_row']->description; ?>
</a>
					</td>
	<?php else: ?>

					<td>
						<a class="linkpicture" href="<?php if ($this->_tpl_vars['task'] == 'mybids'): ?><?php echo $this->_tpl_vars['current_row']->links['bid_list']; ?>
<?php else: ?><?php echo $this->_tpl_vars['current_row']->links['auctiondetails']; ?>
<?php endif; ?>"><?php echo @label_custom_field2; ?>
</a>
						<a class="linkpicture" href="<?php if ($this->_tpl_vars['task'] == 'mybids'): ?><?php echo $this->_tpl_vars['current_row']->links['bid_list']; ?>
<?php else: ?><?php echo $this->_tpl_vars['current_row']->links['auctiondetails']; ?>
<?php endif; ?>"><?php echo $this->_tpl_vars['current_row']->custom_fld2; ?>
</a>
					</td>
					<td>
						<a class="linkpicture" href="<?php if ($this->_tpl_vars['task'] == 'mybids'): ?><?php echo $this->_tpl_vars['current_row']->links['bid_list']; ?>
<?php else: ?><?php echo $this->_tpl_vars['current_row']->links['auctiondetails']; ?>
<?php endif; ?>"><?php echo @label_custom_field3; ?>
</a>
						<a class="linkpicture" href="<?php if ($this->_tpl_vars['task'] == 'mybids'): ?><?php echo $this->_tpl_vars['current_row']->links['bid_list']; ?>
<?php else: ?><?php echo $this->_tpl_vars['current_row']->links['auctiondetails']; ?>
<?php endif; ?>"><?php echo $this->_tpl_vars['current_row']->custom_fld3; ?>
</a>
					</td>
					<td>
						<a class="linkpicture" href="<?php if ($this->_tpl_vars['task'] == 'mybids'): ?><?php echo $this->_tpl_vars['current_row']->links['bid_list']; ?>
<?php else: ?><?php echo $this->_tpl_vars['current_row']->links['auctiondetails']; ?>
<?php endif; ?>"><?php echo @label_custom_field4; ?>
</a>
						<a class="linkpicture" href="<?php if ($this->_tpl_vars['task'] == 'mybids'): ?><?php echo $this->_tpl_vars['current_row']->links['bid_list']; ?>
<?php else: ?><?php echo $this->_tpl_vars['current_row']->links['auctiondetails']; ?>
<?php endif; ?>"><?php echo $this->_tpl_vars['current_row']->custom_fld4; ?>
</a>
					</td>
	<?php endif; ?>
						<td rowspan="4" valign="top">
					<?php if (@next_bid_allow == 1): ?>
						<span><?php echo @next_bid; ?>
</span>&nbsp;
						<span id="auction_price_bold">
							<?php if ($this->_tpl_vars['current_row']->bid_next != 'SOLD' && $this->_tpl_vars['current_row']->bid_next != 'ENDED' && $this->_tpl_vars['current_row']->bid_next != 'PENDING'): ?>
								<?php echo $this->_tpl_vars['current_row']->currency_name; ?>
&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['current_row']->bid_next)) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : number_format($_tmp, 0)); ?>

							<?php else: ?>
								<?php echo $this->_tpl_vars['current_row']->bid_next; ?>

							<?php endif; ?>

						</span>
					<?php endif; ?>
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
					<?php if ($this->_tpl_vars['current_row']->proxyplus_price != 0): ?>
						 <div class="spacing-top"><input type="hidden" name="maxproxyplus<?php echo $this->_tpl_vars['current_row']->id; ?>
" id="maxproxyplus<?php echo $this->_tpl_vars['current_row']->id; ?>
" value="<?php echo $this->_tpl_vars['current_row']->proxyplus_price; ?>
">
						<?php echo @proxyplusLabel; ?>
 <?php echo $this->_tpl_vars['current_row']->currency_name; ?>
&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['current_row']->proxyplus_price)) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : number_format($_tmp, 0)); ?>

						<?php if ($this->_tpl_vars['current_row']->outbid == 1): ?> OUTBID <?php endif; ?>
						</div>
					<?php else: ?>
						 <input type="hidden" name="maxproxyplus<?php echo $this->_tpl_vars['current_row']->id; ?>
" id="maxproxyplus<?php echo $this->_tpl_vars['current_row']->id; ?>
" value="0">
					<?php endif; ?>

					<?php if (! $this->_tpl_vars['current_row']->expired && @bid_opt_allow_proxy == 1 && $this->_tpl_vars['current_row']->is_my_auction != 1): ?>
					<div class="spacing-top"><input type="hidden" name="prxo<?php echo $this->_tpl_vars['current_row']->id; ?>
" id="prxo<?php echo $this->_tpl_vars['current_row']->id; ?>
" value="0">
					<input <?php echo $this->_tpl_vars['disable_bids']; ?>
 type="checkbox" class="inputbox" name="proxy" id="proxy" value="1" onclick="ProxyClick1(this,<?php echo $this->_tpl_vars['current_row']->id; ?>
);">&nbsp;<?php echo @bid_proxy; ?>

					<?php echo smarty_infobullet(array('text' => @bid_help_proxy_bidding), $this);?>

					</div>
					<?php endif; ?>

					 <?php if (@bid_allow == 1): ?> 
						  <?php if (! $this->_tpl_vars['current_row']->expired && $this->_tpl_vars['current_row']->is_my_auction != 1): ?>
							<div class="spacing-top"><span id="bid<?php echo $this->_tpl_vars['current_row']->id; ?>
" >
							<?php if ($this->_tpl_vars['auction']->highest_bid > 0): ?>
								<?php echo @bid_my_bids; ?>

							<?php else: ?>
								<?php echo @bid_my_bids; ?>

							<?php endif; ?>
							</span>
						<?php echo $this->_tpl_vars['current_row']->currency_name; ?>
&nbsp;<input name="amount<?php echo $this->_tpl_vars['current_row']->id; ?>
" id="amount<?php echo $this->_tpl_vars['current_row']->id; ?>
" class="inputbox" type="text" value="" size="20" alt="bid" <?php echo $this->_tpl_vars['disable_bids']; ?>
>&nbsp;
						</div>
						<?php endif; ?>
					 <?php endif; ?>
					<?php if (! $this->_tpl_vars['current_row']->expired && $this->_tpl_vars['current_row']->is_my_auction != 1 && @bid_allow == 1): ?>
						<div class="spacing-top"><input type="submit" name="send" value="<?php echo @but_send_bid; ?>
"  class="button art-button" <?php echo $this->_tpl_vars['disable_bids']; ?>
 /></div>
					<?php endif; ?>

						 <?php if ($this->_tpl_vars['current_row']->expired): ?>
    							<div id="auction_info_bottom" class="spacing-top">
    						   <font class='expired'><?php echo @bid_expired; ?>
</font>
    							</div>
    						<?php elseif (@bid_opt_enable_countdown): ?>
    							<div id="auction_info_bottom" class="spacing-top">
							<?php if ($this->_tpl_vars['current_row']->countdowntype == 1): ?>
    								<?php echo @bid_starts_in; ?>
:
							<?php else: ?>
								<?php echo @bid_expires_in; ?>
:
							<?php endif; ?>
							&nbsp;<span id="time<?php echo $this->_tpl_vars['current_row']->rownr; ?>
"><?php echo $this->_tpl_vars['current_row']->countdown; ?>
</span>

    							</div>
    						<?php endif; ?>


					</div>
					</td>
				</tr>
				<tr>
		<?php if ($this->_tpl_vars['optionRowspan'] == 1): ?>
					<td colspan="3" rowspan="3" valign="top">
						<a class="linkpicture" href="<?php if ($this->_tpl_vars['task'] == 'mybids'): ?><?php echo $this->_tpl_vars['current_row']->links['bid_list']; ?>
<?php else: ?><?php echo $this->_tpl_vars['current_row']->links['auctiondetails']; ?>
<?php endif; ?>"><?php echo $this->_tpl_vars['current_row']->description; ?>
</a>
					</td>
	<?php elseif ($this->_tpl_vars['optionRowspan'] != 2): ?>

					<td>
																					<?php if (@bid_opt_allow_auctioneer == 1): ?>
								<?php echo @bid_bid_seller; ?>
:&nbsp;<a href="<?php echo $this->_tpl_vars['current_row']->links['otherauctions']; ?>
" alt="<?php echo @bid_more_offers_user; ?>
"><?php echo $this->_tpl_vars['current_row']->username; ?>
</a><br/>
							<?php endif; ?>
					</td>
					<td>
						<a class="linkpicture" href="<?php if ($this->_tpl_vars['task'] == 'mybids'): ?><?php echo $this->_tpl_vars['current_row']->links['bid_list']; ?>
<?php else: ?><?php echo $this->_tpl_vars['current_row']->links['auctiondetails']; ?>
<?php endif; ?>"><?php echo @label_custom_field5; ?>
</a>
						<a class="linkpicture" href="<?php if ($this->_tpl_vars['task'] == 'mybids'): ?><?php echo $this->_tpl_vars['current_row']->links['bid_list']; ?>
<?php else: ?><?php echo $this->_tpl_vars['current_row']->links['auctiondetails']; ?>
<?php endif; ?>"><?php echo $this->_tpl_vars['current_row']->custom_fld5; ?>
</a>
					</td>
	<?php endif; ?>
						<td>
						&nbsp;
					</td>
				</tr>		
				<tr>
		<?php if ($this->_tpl_vars['optionRowspan'] != 1 && $this->_tpl_vars['optionRowspan'] != 2): ?>
					<td colspan="3" rowspan="2">
						<a class="linkpicture" href="<?php if ($this->_tpl_vars['task'] == 'mybids'): ?><?php echo $this->_tpl_vars['current_row']->links['bid_list']; ?>
<?php else: ?><?php echo $this->_tpl_vars['current_row']->links['auctiondetails']; ?>
<?php endif; ?>"><?php echo $this->_tpl_vars['current_row']->description; ?>
</a>
					</td>
	<?php endif; ?>
					</tr>		
				</table>
    			</td>
			</tr>

			</table>
		</td>
	</tr>
	</form>