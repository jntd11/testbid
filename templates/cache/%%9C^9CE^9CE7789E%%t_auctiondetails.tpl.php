<?php /* Smarty version 2.6.18, created on 2012-05-27 10:26:36
         compiled from t_auctiondetails.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'createtab', 't_auctiondetails.tpl', 15, false),array('function', 'set_css', 't_auctiondetails.tpl', 17, false),array('function', 'printdate', 't_auctiondetails.tpl', 183, false),array('function', 'infobullet', 't_auctiondetails.tpl', 322, false),array('function', 'startpane', 't_auctiondetails.tpl', 432, false),array('function', 'starttab', 't_auctiondetails.tpl', 436, false),array('function', 'endtab', 't_auctiondetails.tpl', 451, false),array('function', 'endpane', 't_auctiondetails.tpl', 550, false),array('modifier', 'date_format', 't_auctiondetails.tpl', 28, false),array('modifier', 'number_format', 't_auctiondetails.tpl', 104, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 't_overlib.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 't_javascript_language.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/js/ratings.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/js/ui/jquery.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/js/ui/interface.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/js/ui/jquery.simplemodal.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/js/ui/jqueryForm.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/js/yui/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript">
   jQuery.noConflict();
 </script>
<?php echo smarty_createtab(array(), $this);?>

<?php echo smarty_set_css(array(), $this);?>


<script type="text/javascript" src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/js/ratings.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/js/auctions.js"></script>
<style type="text/css">
<?php echo '
 .tab-page{ z-index:0 !important;}
'; ?>

</style>

<table width="100%"  class="" >
<input type="hidden" name="servertime" id="servertime" value="<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%m/%d/%Y %k:%M') : smarty_modifier_date_format($_tmp, '%m/%d/%Y %k:%M')); ?>
">
<tr>
<td >
<?php if ($this->_tpl_vars['auction']->links['auctiondetails_prev'] == ""): ?>
<input type="button" class="button art-button" value="Previous" disabled="disabled">
<?php else: ?>
<a href="<?php echo $this->_tpl_vars['auction']->links['auctiondetails_prev']; ?>
" onClick="window.location.href='<?php echo $this->_tpl_vars['auction']->links['auctiondetails_prev']; ?>
';"  style="text-decoration: none;"><input type="button" class="button art-button" value="Previous"></a>
<?php endif; ?>
<?php if ($this->_tpl_vars['auction']->links['auctiondetails_next'] == ""): ?>
<input type="button" class="button art-button" value="Next" disabled="disabled">
<?php else: ?>
<a href="<?php echo $this->_tpl_vars['auction']->links['auctiondetails_next']; ?>
" onClick="window.location.href='<?php echo $this->_tpl_vars['auction']->links['auctiondetails_next']; ?>
';" style="text-decoration: none;"><input type="button" class="button art-button" value="Next"></a>
<?php endif; ?>


</td>
</tr>

<tr bgcolor="#efefef">
<td>

   <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 't_auctiondetails_plugins.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
   <table style="background: #efefef;font-weight: bold;background: #f0f0f0;padding: 4px;" width="100%" cellspacing="3" cellpadding="3" border="0">
   <tr>
   <td colspan="4">
	<?php echo $this->_tpl_vars['auction']->gallery; ?>

   </td>
   </tr>
   <tr>
		<td colspan="4">
	<table>
		<?php if ($this->_tpl_vars['auction']->is_my_auction): ?>
		<tr>
			 <td align="left" colspan="3">
			    <?php if ($this->_tpl_vars['auction']->link_extern != ""): ?>
				<input onclick="window.open('<?php echo $this->_tpl_vars['auction']->link_extern; ?>
','targetWindow', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=720,height=480')" type="button" class="button art-button" id="refresh" value="<?php echo @external_link; ?>
">
				<?php endif; ?>
				<?php if ($this->_tpl_vars['auction']->close_offer == 1): ?>
					<input type="button" onclick="window.location = '<?php echo $this->_tpl_vars['auction']->links['republish']; ?>
';" class="back_button" value="<?php echo @bid_img_repub_offer; ?>
">
					<?php if ($this->_tpl_vars['auction']->is_my_auction && ! $this->_tpl_vars['auction']->winner_id): ?>
    					<input type="button" value="<?php echo @bid_img_cancel_offer; ?>
"  onclick="if(confirm('<?php echo @bid_confirm_cancel_auction; ?>
')) window.open('<?php echo $this->_tpl_vars['auction']->links['cancel']; ?>
','_parent');" class="back_button" />
					<?php endif; ?>

				<?php else: ?>
					<input type="button" onclick="window.location = '<?php echo $this->_tpl_vars['auction']->links['edit']; ?>
';" class="back_button" value="<?php echo @bid_img_edit_offer; ?>
">
					<input type="button" value="<?php echo @bid_img_cancel_offer; ?>
"  onclick="if(confirm('<?php echo @bid_confirm_cancel_auction; ?>
')) window.open('<?php echo $this->_tpl_vars['auction']->links['cancel']; ?>
','_parent');" class="back_button" />
				<?php endif; ?>
			 </td>
		</tr>
		<?php elseif ($this->_tpl_vars['auction']->link_extern != ""): ?>
		   <tr>
			 <td colspan="3"><input onclick="window.open('<?php echo $this->_tpl_vars['auction']->link_extern; ?>
','targetWindow', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=720,height=480')" type="button" class="button art-button" id="refresh" value="<?php echo @external_link; ?>
"></td>
		   </tr>
		<?php endif; ?>
			

			<?php if ($this->_tpl_vars['auction']->is_my_auction && $this->_tpl_vars['auction']->close_offer == 1 && ! $this->_tpl_vars['auction']->winner_id && ! empty ( $this->_tpl_vars['bid_list'] )): ?>
    		   <tr width="100%">
    				 <td>
                    <img style="margin:0px;height:20px" src="<?php echo @BIDS_COMPONENT; ?>
/images/choose_winner.png" border="0" alt="<?php echo @bid_choose_a_winner; ?>
" title="<?php echo @bid_choose_a_winner; ?>
"/>
    				 <font color="Red"><?php echo @bid_choose_a_winner; ?>
</font></td>
    			</tr>
			<?php endif; ?>
 </table>
			<?php if ($this->_tpl_vars['auction']->paypalemail && $this->_tpl_vars['auction']->i_am_winner): ?>
			<div id="paypal_button">
				<?php if ($this->_tpl_vars['use_sandbox'] == '1'): ?>
                <form name='paypalForm' action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" name="paypal">
                <?php else: ?>
                <form name='paypalForm' action="https://www.paypal.com/cgi-bin/webscr" method="post" name="paypal">
                <?php endif; ?>
        		<input type="hidden" name="cmd" value="_xclick">
        		<input type="hidden" name="business" value="<?php echo $this->_tpl_vars['auction']->paypalemail; ?>
">
        		<input type="hidden" name="item_name" value="<?php echo $this->_tpl_vars['auction']->title; ?>
">
        		<input type="hidden" name="item_number" value="<?php echo $this->_tpl_vars['auction']->id; ?>
">
        		<input type="hidden" name="invoice" value="<?php echo $this->_tpl_vars['auction']->auction_nr; ?>
">
        		<input type="hidden" name="amount" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['auction']->total_price)) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : number_format($_tmp, 0)); ?>
">
        		<input type="hidden" name="quantity" value="<?php echo $this->_tpl_vars['auction']->nr_items; ?>
">
        		<input type="hidden" name="return" value="<?php echo $this->_tpl_vars['auction']->links['bids']; ?>
">
        		<input type="hidden" name="tax" value="0" />
        		<input type="hidden" name="rm" value="2" />
        		<input type="hidden" name="no_note" value="1" />
        		<input type="hidden" name="no_shipping" value="1" />
        		<input type="hidden" name="currency_code" value="<?php echo $this->_tpl_vars['auction']->currency_name; ?>
">
        		<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but06.gif" name="submit" alt="<?php echo @bid_paypal_buynow; ?>
" style="margin-left: 50px;">

                </form>
			</div>
			<?php endif; ?>
		</td>
	</tr>
	<tr >
	 	  <td width="75%" valign="top" align="left">
		<table>
		<tr>
									 <td class="editbidtitle" width="25%"><span id="auctitlelabel"><?php echo @auction_title; ?>
</span> <span id="auctitle"><b><?php echo $this->_tpl_vars['auction']->title; ?>
</span></b>
				 <?php if ($this->_tpl_vars['auction']->published == 0): ?>
					<br/><b><?php echo @bid_unpublished; ?>
</b>
				 <?php endif; ?>
				</td>
				 <td width="25%"><span id="labelshortdesc"><?php echo @label_short_desc; ?>
</span> <span id="shortdesc"><?php echo $this->_tpl_vars['auction']->shortdescription; ?>
</span></td>
				  <td width="25%"><?php echo @label_custom_field1; ?>
 <?php echo $this->_tpl_vars['auction']->custom_fld1; ?>
</td>
					</tr>
		<tr>
			 
			<?php if (@bid_opt_allow_category == 1): ?>
				<td align="left" ><?php echo @bid_category; ?>
:
				<?php if ($this->_tpl_vars['auction']->catname): ?><a href="<?php echo $this->_tpl_vars['auction']->links['filter_cat']; ?>
"><?php echo $this->_tpl_vars['auction']->catname; ?>
</a>
				<?php else: ?>&nbsp;-&nbsp;<?php endif; ?></td>
			<?php endif; ?>
			
	 		<?php if ($this->_tpl_vars['optionRowspan'] != 2): ?>
				 <td ><?php echo @label_custom_field2; ?>
 <?php echo $this->_tpl_vars['auction']->custom_fld2; ?>
</td>
				 <td ><?php echo @label_custom_field3; ?>
 <?php echo $this->_tpl_vars['auction']->custom_fld3; ?>
</td> 
				 <td ><?php echo @label_custom_field4; ?>
 <?php echo $this->_tpl_vars['auction']->custom_fld4; ?>
</td>
	<?php endif; ?>
	     </tr>
		<?php if ($this->_tpl_vars['optionRowspan'] == 0): ?>
			<tr>
				 <td >	  
				<?php if (@bid_opt_allow_auctioneer == 1): ?>
				<?php echo @bid_user; ?>
:
				    <a href="<?php echo $this->_tpl_vars['auction']->links['auctioneer_profile']; ?>
"><?php echo $this->_tpl_vars['auctioneer']->username; ?>
</a>
				<?php endif; ?>
				    <?php if ($this->_tpl_vars['auction']->verified_auctioneer): ?><img src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/images/verified_1.gif"  id='auction_star' height="16" border="0" onmouseover="overlib('<?php echo @bid_user_verified; ?>
');" onmouseout="nd();"/><?php endif; ?>
				</td>
				 <td ><?php echo @label_custom_field5; ?>
 <?php echo $this->_tpl_vars['auction']->custom_fld5; ?>
</td>
				 <td>&nbsp;</td>
			 </tr>
	<?php endif; ?>
			<tr>
				  <td valign="top" colspan="3"><?php echo $this->_tpl_vars['auction']->description; ?>
</td>
			</tr>

			<?php if ($this->_tpl_vars['auction']->BIN_price > 0): ?>
			<tr>
			 <td ><?php echo @bid_binprice; ?>
</td>
			 <td ><?php echo $this->_tpl_vars['auction']->currency_name; ?>
&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['auction']->BIN_price)) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : number_format($_tmp, 0)); ?>
&nbsp;</td>
			</tr>
			<?php endif; ?>

			<?php if (@bid_opt_allow_rating == 1): ?>
        			<tr>
        				<td valign="top"><?php echo @bid_rate_title; ?>
:
        				<a href="<?php echo $this->_tpl_vars['auction']->links['auctioneer_profile']; ?>
" alt="<?php echo @_DETAILS_TITLE; ?>
">
        				<span id="rating_user" rating="<?php echo $this->_tpl_vars['auction']->rating_overall; ?>
"></span></a>
        				&nbsp;<a href="<?php echo $this->_tpl_vars['auction']->links['otherauctions']; ?>
"><?php echo @bid_auctions_by_user; ?>
</a>
        				</td>
        				<td valign="top"><?php echo @bid_registration_date; ?>
:<?php echo smarty_printdate(array('date' => $this->_tpl_vars['auctioneer']->registerDate,'use_hour' => 0), $this);?>
</td>
        			</tr>
			<?php endif; ?>
			</tr>
			<?php if (@bid_opt_allow_payment == 1): ?>
			<tr>
			 <td valign="top" colspan="2"><?php echo @bid_payment; ?>
:&nbsp;<span><?php echo $this->_tpl_vars['auction']->payment_name; ?>
</span>
			 <?php if ($this->_tpl_vars['auction']->paypalemail): ?>
			     <img src="https://www.paypal.com/en_US/i/btn/x-click-but05.gif" border="0">
			 <?php endif; ?>
			 </td>
			</tr>
			<?php endif; ?>
			<?php if (@bid_opt_allow_shipment == 1): ?>
			<tr>
			 <td valign ="top" colspan="2"><?php echo @bid_shipment; ?>
:&nbsp;<span>
			 <?php if ($this->_tpl_vars['auction']->shipment_info): ?><?php echo $this->_tpl_vars['auction']->shipment_info; ?>
<?php else: ?>-<?php endif; ?></span></td>
			</tr>
			<?php endif; ?>
			<?php if (@bid_opt_allow_shipmentprice == 1): ?>
			<tr>
			 <td valign ="top" colspan="2"><?php echo @bid_shipment_price; ?>
:&nbsp;<span>
			 <?php if ($this->_tpl_vars['auction']->shipment_price): ?><?php echo $this->_tpl_vars['auction']->currency_name; ?>
&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['auction']->shipment_price)) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : number_format($_tmp, 0)); ?>
<?php else: ?>-<?php endif; ?></span></td>
			</tr>
			<?php endif; ?>

		   </table>
	  </td>
	  	            	<td width="2%" valign="top">&nbsp;</td> 
	  		<td width="30%" valign="top">
		  <table width="100%" valign="top">
			<?php if ($this->_tpl_vars['auction']->is_my_auction != 1 && ! $this->_tpl_vars['auction']->close_offer): ?>
			<tr>
			<td colspan="2">
				<table width="100%">
				<tr>
				<td>
					<?php if ($this->_tpl_vars['auction']->add_to_watchlist): ?>
						<span id='add_to_watchlist'>
						<a href='<?php echo $this->_tpl_vars['auction']->links['add_to_watchlist']; ?>
'>
							<img src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/images/f_watchlist_1.jpg" title="<?php echo @bid_add_to_watchlist; ?>
" alt="<?php echo @bid_add_to_watchlist; ?>
"/>
						</a>
						<a href='<?php echo $this->_tpl_vars['auction']->links['add_to_watchlist']; ?>
'>
							Add to Watchlist
						</a>&nbsp;
						</span>
					   <?php elseif ($this->_tpl_vars['auction']->del_from_watchlist): ?>
						<span id='add_to_watchlist'><a href='<?php echo $this->_tpl_vars['auction']->links['del_from_watchlist']; ?>
'>
							<img src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/images/f_watchlist_0.jpg" title="<?php echo @bid_remove_from_watchlist; ?>
" alt="<?php echo @bid_remove_from_watchlist; ?>
"/>
						</a>
					   	<a href='<?php echo $this->_tpl_vars['auction']->links['del_from_watchlist']; ?>
'>
							Remove from Watchlist
						</a>&nbsp;
						</span>
					<?php endif; ?>
				  </td>
				  </tr>
				</table>
			</td>
			</tr>
			<?php endif; ?>
		 <tr>
		 <td colspan="2">	   
			<?php if ($this->_tpl_vars['auction']->is_my_auction != 1 && $this->_tpl_vars['auction']->close_offer != 1 && ! $this->_tpl_vars['auction']->expired): ?>
			   			   			   <?php if (! $this->_tpl_vars['is_logged_in'] && false): ?>
				<?php $this->assign('disable_bids', 'disabled'); ?>
				<h2><strong><?php echo @bid_login_to_bid; ?>
</strong></h2>
			   <?php endif; ?>
		</td>
		</tr>
		<tr>
		  <form action="index.php" method="post" name="auctionForm" onsubmit="return FormValidate(this);">
		  <?php if (! $this->_tpl_vars['is_logged_in']): ?>
			<input type="hidden" name="islogged" id="islogged" value="0">
		  <?php else: ?>
			<input type="hidden" name="islogged" id="islogged" value="1">
		  <?php endif; ?>
		  <input type="hidden" name="option" value="com_bids">
		  <input type="hidden" name="page" value="det">
		  <input type="hidden" name="task" value="sendbid">
		  <input type="hidden" name="id" value="<?php echo $this->_tpl_vars['auction']->id; ?>
">
		  <input type="hidden" name="initial_price" value="<?php echo $this->_tpl_vars['auction']->initial_price; ?>
">
		  <input type="hidden" name="bin_price" value="<?php echo $this->_tpl_vars['auction']->BIN_price; ?>
">
		  <input type="hidden" name="mylastbid" value="<?php echo $this->_tpl_vars['auction']->mybid->bid_price; ?>
">
		  <input type="hidden" name="min_increase" value="<?php echo $this->_tpl_vars['auction']->min_increase; ?>
">
		  <input type="hidden" name="Itemid" value="<?php echo $this->_tpl_vars['Itemid']; ?>
">
		  <input type="hidden" name="maxbid" value="<?php echo $this->_tpl_vars['auction']->highest_bid; ?>
">
		  <input type="hidden" name="prxo" id="prxo" value="0">
		  <input type="hidden" name="leadbid" id="leadbid" value="<?php echo $this->_tpl_vars['auction']->mine; ?>
">
		   <td colspan=2 id="proxy_price" style="display:none;">
					<input type="text" class="inputbox" name="max_proxy_price" value="" <?php echo $this->_tpl_vars['disable_bids']; ?>
>&nbsp;<?php echo @bid_maxpp_text; ?>

			</td>
		 </tr>
		<?php if ($this->_tpl_vars['auction']->highest_bid > 0): ?>
		<tr>
		 <td width="60%" align="left"><?php echo @bid_bid_price; ?>
:&nbsp; <?php echo $this->_tpl_vars['auction']->currency_name; ?>
&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['auction']->highest_bid)) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : number_format($_tmp, 0)); ?>
</td>
		 <td width="40%" align="left"><?php echo @current_bidder; ?>
 &nbsp; <?php echo $this->_tpl_vars['auction']->winning_user; ?>
</td>
		</tr>
		<?php endif; ?>
        <?php if (@next_bid_allow == 1): ?>
		<?php if ($this->_tpl_vars['auction']->highest_bid > 0): ?>
		<tr>
			<td colspan="2"><?php echo @next_bid; ?>
&nbsp; <?php echo $this->_tpl_vars['auction']->currency_name; ?>
&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['auction']->highest_bid+$this->_tpl_vars['auction']->min_increase)) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : number_format($_tmp, 0)); ?>
</td>
		</tr>
		<?php else: ?>
		<tr>
			<td colspan="2"><?php echo @next_bid; ?>
&nbsp; <?php echo $this->_tpl_vars['auction']->currency_name; ?>
&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['auction']->initial_price)) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : number_format($_tmp, 0)); ?>
</td>
		</tr>
		<?php endif; ?>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['auction']->proxy_price > 0): ?>
		<tr>
		<td colspan="2"><?php echo @proxyLabel; ?>
 <?php echo $this->_tpl_vars['auction']->currency_name; ?>
&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['auction']->proxy_price)) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : number_format($_tmp, 0)); ?>

											<?php if ($this->_tpl_vars['auction']->proxy_price < $this->_tpl_vars['auction']->highest_bid): ?> OUTBID <?php endif; ?>
	
		</td>
		</tr>
		<?php endif; ?>
		<input type="hidden" name="outbid" id="outbid" value="<?php if ($this->_tpl_vars['auction']->proxyplus_price < $this->_tpl_vars['auction']->highest_bid): ?>1<?php endif; ?>"> 
		<input type="hidden" name="maxproxyplus" id="maxproxyplus" value="<?php if ($this->_tpl_vars['auction']->proxyplus_price != ''): ?><?php echo $this->_tpl_vars['auction']->proxyplus_price; ?>
<?php else: ?>0<?php endif; ?>">
		<?php if ($this->_tpl_vars['auction']->proxyplus_price > 0): ?>
		<tr>
		<td colspan="2">
		
  		
		<?php echo @proxyplusLabel; ?>
 <?php echo $this->_tpl_vars['auction']->currency_name; ?>
&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['auction']->proxyplus_price)) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : number_format($_tmp, 0)); ?>

													<?php if ($this->_tpl_vars['auction']->proxyplus_price < $this->_tpl_vars['auction']->highest_bid): ?> OUTBID <?php endif; ?>
		</td>
		</tr>
		<?php endif; ?>
		 <?php if ($this->_tpl_vars['auction']->auction_type != AUCTION_TYPE_PRIVATE && @bid_opt_allow_proxy == 1): ?>
		 <tr>
		 <td  colspan="2">
			<input <?php echo $this->_tpl_vars['disable_bids']; ?>
 type="checkbox" class="inputbox" name="proxy" id="proxy" value="1" onclick="ProxyClick(this);">&nbsp;<?php echo @bid_proxy; ?>

			<?php echo smarty_infobullet(array('text' => @bid_help_proxy_bidding), $this);?>

		 </td>
		 </tr>
		 <?php endif; ?>
		 <tr>
		 <td colspan="2">
		   <?php if (@bid_allow == 1): ?>
			<span id="bid" >
			<?php if ($this->_tpl_vars['auction']->highest_bid > 0): ?>
				<?php echo @bid_my_bids; ?>

			<?php else: ?>
				<?php echo @bid_my_bids; ?>

			<?php endif; ?>
			</span>
			<?php if (! $this->_tpl_vars['auction']->expired): ?>
				<?php echo $this->_tpl_vars['auction']->currency_name; ?>
&nbsp;<input id="amount" name="amount" class="inputbox" autocomplete="off" type="text" value="" onkeypress="return onlyNumbers(event);"   size="20" alt="bid" <?php echo $this->_tpl_vars['disable_bids']; ?>
>&nbsp;
			<?php endif; ?>
		   <?php endif; ?>
		  </td>
		 </tr>

		  <tr><td colspan="2">
		  <?php if (! $this->_tpl_vars['auction']->expired && @bid_allow == 1): ?>
			<input type="submit" name="send" value="<?php echo @but_send_bid; ?>
" class="button art-button" <?php echo $this->_tpl_vars['disable_bids']; ?>
 />
		  <?php endif; ?>
		  </td>
		 </tr>
		 <?php if ($this->_tpl_vars['terms_and_conditions'] && 0): ?>
			<tr>
			 <td colspan="2"><input type="checkbox" class="inputbox" name="agreement" value="1"  <?php echo $this->_tpl_vars['disable_bids']; ?>
 />
			  <a href="javascript: void(0);" onclick="window.open('<?php echo $this->_tpl_vars['auction']->links['terms']; ?>
','messwindow','location=1,status=1,scrollbars=1,width=500,height=500')" id="auction_category">
			  </a>
			 </td>
			</tr>
		  <?php endif; ?>
		 </form>
	   <?php endif; ?>
		</td>
		 </tr>
					
			<?php if (@bid_opt_allow_startdate == 1 || $this->_tpl_vars['auction']->isValidateDate == 1): ?>
			<tr style="display:none">
				<td><?php echo @bid_start_date; ?>
</td>
				<td id="startdate"><?php echo $this->_tpl_vars['auction']->start_date_text; ?>
</td>
			</tr>
			<?php endif; ?>
			
			<tr  style="display:none">
			 <td ><?php echo @bid_expired_date; ?>
</td>
			 <td ><?php echo $this->_tpl_vars['auction']->end_date_text; ?>
</td>
			</tr>
			<tr>
				<td>				
				<?php if ($this->_tpl_vars['auction']->countdowntype == 1): ?>
    								<?php echo @bid_starts_in; ?>
:
							<?php else: ?>
								<?php echo @bid_expires_in; ?>
:
				<?php endif; ?>
				</td>
				<td>
			<?php if (@bid_opt_enable_countdown && ! $this->_tpl_vars['auction']->expired && ! $this->_tpl_vars['auction']->close_offer): ?>
        				<span id="time1"><?php echo $this->_tpl_vars['auction']->countdown; ?>
</span>
			<?php elseif ($this->_tpl_vars['auction']->expired): ?>
					<span id=""><font class='expired'><?php echo @bid_expired; ?>
</font></span>
        	<?php endif; ?>
        		</td>
			</tr>
			<?php if ($this->_tpl_vars['auction']->nr_bidders != null): ?>
				<?php if (@bid_opt_show_bidders == 0): ?>
				<tr>
					<td><?php echo @bid_no_bidders; ?>
</td>
					<td><?php echo $this->_tpl_vars['auction']->nr_bidders; ?>
</td>
				</tr>
				<?php elseif ($this->_tpl_vars['auction']->is_my_auction == 1): ?>
					<tr>
						<td><?php echo @bid_no_bidders; ?>
</td>
						<td><?php echo $this->_tpl_vars['auction']->nr_bidders; ?>
</td>
					</tr>
				<?php endif; ?>
			<?php endif; ?>
		</table>
	   </td>
	  	</tr>
   </table>
</td>
</tr>
<tr>
<td >
<?php if ($this->_tpl_vars['auction']->links['auctiondetails_prev'] == ""): ?>
<input type="button" class="button art-button" value="Previous" disabled="disabled">
<?php else: ?>
<a href="<?php echo $this->_tpl_vars['auction']->links['auctiondetails_prev']; ?>
" onClick="window.location.href='<?php echo $this->_tpl_vars['auction']->links['auctiondetails_prev']; ?>
';"  style="text-decoration: none;"><input type="button" class="button art-button" value="Previous"></a>
<?php endif; ?>
<?php if ($this->_tpl_vars['auction']->links['auctiondetails_next'] == ""): ?>
<input type="button" class="button art-button" value="Next" disabled="disabled">
<?php else: ?>
<a href="<?php echo $this->_tpl_vars['auction']->links['auctiondetails_next']; ?>
" onClick="window.location.href='<?php echo $this->_tpl_vars['auction']->links['auctiondetails_next']; ?>
';" style="text-decoration: none;"><input type="button" class="button art-button" value="Next"></a>
<?php endif; ?>


</td>
</tr>

<tr>
<td colspan="2">
	   <?php echo smarty_startpane(array('id' => "content-pane",'usecookies' => 0), $this);?>

		   <?php if ($this->_tpl_vars['auction']->BIN_price > 0 && $this->_tpl_vars['auction']->is_my_auction != 1 && $this->_tpl_vars['auction']->close_offer != 1 && ! $this->_tpl_vars['auction']->expired): ?>
		   		   <?php echo smarty_starttab(array('paneid' => 'tab3','text' => @bid_tab_offer_bin), $this);?>


		   <?php if (! $this->_tpl_vars['is_logged_in']): ?>
			<?php $this->assign('disable_bids', 'disabled'); ?>
			<h2><strong><?php echo @bid_login_to_bid; ?>
</strong></h2>
		   <?php endif; ?>
		   <table>
		   <tr>
			  <td>
			  <input type="button" class="back_button" onclick="MakeBinBid('<?php echo $this->_tpl_vars['auction']->links['bin']; ?>
');"
				value="<?php echo @bid_buy_it_now; ?>
: <?php echo $this->_tpl_vars['auction']->currency_name; ?>
&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['auction']->BIN_price)) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : number_format($_tmp, 0)); ?>
"
			    name="bin_button" <?php echo $this->_tpl_vars['disable_bids']; ?>
 />
			  </td>
		   </tr>
		   </table>
		 <?php echo smarty_endtab(array(), $this);?>

	   <?php endif; ?>
		<?php if ($this->_tpl_vars['is_logged_in']): ?>
	  <?php if (@bid_opt_show_list == 0 || $this->_tpl_vars['auction']->is_my_auction == 1): ?>
	    <?php echo smarty_starttab(array('paneid' => 'tab4','text' => @bid_tab_offer_list), $this);?>

		<table width="100%">
		<tbody>
		 <tr>
		  <th class="auction_bids_list" width="5%"><?php echo @bid_no_short; ?>
</th>
		  <th class="auction_bids_list" width="20%"><?php echo @bid_date; ?>
</th>
		  <th class="auction_bids_list" width="*%"><?php echo @bid_username; ?>
</th>
		  <th class="auction_bids_list" width="20%"><?php echo @bid_bided_amount; ?>
</th>
		  <?php if ($this->_tpl_vars['auction']->is_my_auction && $this->_tpl_vars['auction']->close_offer != 1 && $this->_tpl_vars['auction']->automatic != 1): ?>
			  <th class="auction_bids_list" width="10%">&nbsp;</th>
		  <?php endif; ?>
		  <?php if ($this->_tpl_vars['auction']->must_rate && @bid_opt_allow_rating == 1): ?>
				<th class="auction_bids_list" width="20%">&nbsp;</th>
		  <?php endif; ?>
		  <?php if ($this->_tpl_vars['auction']->my_proxy_bid): ?>
			  <th class="auction_bids_list" width="20%"><?php echo @bid_my_proxy; ?>
</th>
		  <?php endif; ?>
		</tr>
		<?php unset($this->_sections['bids']);
$this->_sections['bids']['name'] = 'bids';
$this->_sections['bids']['loop'] = is_array($_loop=$this->_tpl_vars['bid_list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['bids']['show'] = true;
$this->_sections['bids']['max'] = $this->_sections['bids']['loop'];
$this->_sections['bids']['step'] = 1;
$this->_sections['bids']['start'] = $this->_sections['bids']['step'] > 0 ? 0 : $this->_sections['bids']['loop']-1;
if ($this->_sections['bids']['show']) {
    $this->_sections['bids']['total'] = $this->_sections['bids']['loop'];
    if ($this->_sections['bids']['total'] == 0)
        $this->_sections['bids']['show'] = false;
} else
    $this->_sections['bids']['total'] = 0;
if ($this->_sections['bids']['show']):

            for ($this->_sections['bids']['index'] = $this->_sections['bids']['start'], $this->_sections['bids']['iteration'] = 1;
                 $this->_sections['bids']['iteration'] <= $this->_sections['bids']['total'];
                 $this->_sections['bids']['index'] += $this->_sections['bids']['step'], $this->_sections['bids']['iteration']++):
$this->_sections['bids']['rownum'] = $this->_sections['bids']['iteration'];
$this->_sections['bids']['index_prev'] = $this->_sections['bids']['index'] - $this->_sections['bids']['step'];
$this->_sections['bids']['index_next'] = $this->_sections['bids']['index'] + $this->_sections['bids']['step'];
$this->_sections['bids']['first']      = ($this->_sections['bids']['iteration'] == 1);
$this->_sections['bids']['last']       = ($this->_sections['bids']['iteration'] == $this->_sections['bids']['total']);
?>
			<?php $this->assign('tr_class', "cycle(\'auction_bids_list1\',\'auction_bids_list2\')"); ?>
			<?php if ($this->_tpl_vars['bid_list'][$this->_sections['bids']['index']]->userid == $this->_tpl_vars['userid']): ?>
				<?php $this->assign('tr_class', "cycle(\'auction_bids_mybid1\',\'auction_bids_mybid2\')"); ?>
				<a name='mybid' id='mybid'></a>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['bid_list'][$this->_sections['bids']['index']]->accept): ?>
				<?php $this->assign('tr_class', 'auction_winner'); ?>
			<?php endif; ?>
			<tr class="<?php echo $this->_tpl_vars['tr_class']; ?>
">
			  <td><?php echo $this->_sections['bids']['rownum']; ?>
</td>
			  <td><?php echo smarty_printdate(array('date' => $this->_tpl_vars['bid_list'][$this->_sections['bids']['index']]->modified), $this);?>
</td>
			  <td>
			     <a href="index.php?option=com_bids&task=ViewDetails&id=<?php echo $this->_tpl_vars['bid_list'][$this->_sections['bids']['index']]->userid; ?>
&Itemid=<?php echo $this->_tpl_vars['Itemid']; ?>
"  onmouseover="overlib('<?php echo @bid_view_user_profile; ?>
');" onmouseout="nd();"><?php echo $this->_tpl_vars['bid_list'][$this->_sections['bids']['index']]->username; ?>
</a>
            	<?php if ($this->_tpl_vars['auction']->is_my_auction && @bid_opt_allow_messages): ?>
                    <a href="javascript:void(0);" id="sendm" onclick="SendMessage(this,0,<?php echo $this->_tpl_vars['bid_list'][$this->_sections['bids']['index']]->userid; ?>
,'<?php echo $this->_tpl_vars['bid_list'][$this->_sections['bids']['index']]->username; ?>
');">(<?php echo @but_send_message; ?>
)</a>
            	<?php endif; ?>
			  </td>
			  <td><?php echo $this->_tpl_vars['auction']->currency_name; ?>
&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['bid_list'][$this->_sections['bids']['index']]->bid_price)) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : number_format($_tmp, 0)); ?>

					<?php if ($this->_tpl_vars['auction']->reserve_price > 0 && $this->_tpl_vars['auction']->is_my_auction): ?>
					   <?php if ($this->_tpl_vars['auction']->reserve_price > $this->_tpl_vars['bid_list'][$this->_sections['bids']['index']]->bid_price): ?>
					       <img style="margin:0px;" src="<?php echo @BIDS_COMPONENT; ?>
/images/reserve_price_not_ok.png" border="0" alt="<?php echo @bid_reserve_not_met; ?>
" title="<?php echo @bid_reserve_not_met; ?>
"/>
					   <?php else: ?>
					       <img style="margin:0px;" src="<?php echo @BIDS_COMPONENT; ?>
/images/reserve_price_ok.png" border="0"  alt="<?php echo @bid_reserve_met; ?>
" title="<?php echo @bid_reserve_met; ?>
"/>
					   <?php endif; ?>
					<?php endif; ?>

			  </td>
			  <?php if ($this->_tpl_vars['auction']->is_my_auction && ( $this->_tpl_vars['auction']->close_offer != 1 || ! $this->_tpl_vars['auction']->winner_id ) && $this->_tpl_vars['auction']->automatic != 1): ?>
				 <td><a href="index.php?option=com_bids&task=accept&bid=<?php echo $this->_tpl_vars['bid_list'][$this->_sections['bids']['index']]->id; ?>
&Itemid=<?php echo $this->_tpl_vars['Itemid']; ?>
" onclick="return confirm('<?php echo @bid_confirm_accept_bid; ?>
');">
					<img src="<?php echo @BIDS_COMPONENT; ?>
/images/auctionicon16.gif" border="0" /><?php echo @bid_accept; ?>
</a>
				 </td>
			  <?php endif; ?>
			  <?php if ($this->_tpl_vars['auction']->must_rate && $this->_tpl_vars['bid_list'][$this->_sections['bids']['index']]->accept && @bid_opt_allow_rating == 1): ?>
				 <td>
					<span><a href="javascript:void(0);" onclick="document.getElementById('rate').style.display = 'block';">Rate</a></span>
					<form action="index.php" method="post" name="auctionForm" onsubmit="return validateForm(this);">
					<input type="hidden" name="option" value="com_bids">
					<input type="hidden" name="task" value="rate">
					<input type="hidden" name="Itemid" value="<?php echo $this->_tpl_vars['Itemid']; ?>
">
					<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['auction']->id; ?>
">
					<input type="hidden" name="user_rated" value="<?php if (! $this->_tpl_vars['auction']->is_my_auction): ?><?php echo $this->_tpl_vars['auction']->userid; ?>
<?php else: ?><?php echo $this->_tpl_vars['auction']->winner_id; ?>
<?php endif; ?>">
					<input type="hidden" name="auction_id" value="<?php echo $this->_tpl_vars['auction']->id; ?>
">
					<table id="rate" style="display:none;">
					<tr>
					<td>
						<?php echo $this->_tpl_vars['lists']['ratings']; ?>

						<div style="margin:5px;"><?php echo @bid_comment; ?>
</div>
						<div style="margin:10px;"><textarea name="comment" cols="40" rows="3" class="inputbox" ></textarea></div>
						<div><input type="submit" value="<?php echo @bid_rate; ?>
" class="back_button"></div>
					</td>
					</tr>
					</table>
					</form>
				 </td>
			  <?php endif; ?>
			  <?php if ($this->_tpl_vars['auction']->my_proxy_bid): ?>
				<?php if ($this->_tpl_vars['bid_list'][$this->_sections['bids']['index']]->userid == $this->_tpl_vars['userid']): ?>
					<td class="auction_my_proxy"><?php echo $this->_tpl_vars['auction']->currency_name; ?>
&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['auction']->my_proxy_bid)) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : number_format($_tmp, 0)); ?>
</td>
				<?php else: ?>
					<td>&nbsp;</td>
				<?php endif; ?>
			  <?php endif; ?>
			</tr>
		<?php endfor; else: ?>
  			<?php if ($this->_tpl_vars['auction']->auction_type == @AUCTION_TYPE_PRIVATE): ?>
    			<h2><?php echo @bid_you_have_no_bids; ?>
</h2>
    		<?php else: ?>
    			<h2><?php echo @bid_no_user_bids; ?>
</h2>
    		<?php endif; ?>
		<?php endif; ?>
		</tbody>
		</table>
		<?php echo smarty_endtab(array(), $this);?>

	  <?php endif; ?>
	<?php endif; ?>
	<?php echo smarty_endpane(array(), $this);?>

  </td>
</tr>

<?php if ($this->_tpl_vars['auction']->close_offer): ?>
<tr>
  <td colspan="2">
	<h1><?php echo @bid_auction_closed_on; ?>
: <?php echo smarty_printdate(array('date' => $this->_tpl_vars['auction']->closed_date), $this);?>
</h1>
	<?php if ($this->_tpl_vars['auction']->i_am_winner): ?>
		<h1><?php echo @bid_alt_you_are_winner; ?>
</h1>
	<?php endif; ?>
  </td>
 </tr>
<?php endif; ?>
</table>
<?php if (@bid_opt_enable_countdown): ?>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/js/startcounter.js"></script>
<?php endif; ?>