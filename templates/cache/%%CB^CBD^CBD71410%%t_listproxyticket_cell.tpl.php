<?php /* Smarty version 2.6.18, created on 2012-05-29 09:43:07
         compiled from t_listproxyticket_cell.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 't_listproxyticket_cell.tpl', 48, false),)), $this); ?>
<div id="popupContact">
		<a id="popupContactClose">x</a>
		<div id="contactArea">
		<INPUT TYPE="button" id="popupcontinue" class="button art-button" VALUE="Continue bid confirm" onClick="" /> <br />
		<INPUT class="button art-button"  id="popupsavelater" TYPE="button" VALUE="Save choices for later submission" onClick="" /><br />
		<INPUT class="button art-button"  id="popupcancel" TYPE="button" VALUE="Cancel and exit" onClick="" /><br />
		</div>
	</div>
	<div id="backgroundPopup"></div>
<?php if ($this->_tpl_vars['task'] != editproxyticket): ?>
<tr id="" >	
	  <td  colspan="2">
		
		<table cellpadding="2" cellspacing="2" width="80%" align="center">
		<tr>
			<td colspan="2" style="font-size: 14px; font-weight: bold;"><?php echo @ticket; ?>
 <?php echo $this->_tpl_vars['userticket'][$this->_tpl_vars['myId']]; ?>
 <?php if ($this->_tpl_vars['myId'] == 0): ?>  <input name="ticketid" id="ticketid" type="hidden" value="1"> <?php else: ?>  <input name="ticketid" id="ticketid" type="hidden" value="<?php echo $this->_tpl_vars['myId']; ?>
"><?php endif; ?> <input name="run" id="run" type="hidden" value="0"></td>
			<td colspan="3" align="left" style="font-size: 24px; color: red; font-weight: bold;"><?php echo @lot_desired; ?>
 
			<input class="proxy" type="text" name="lotdesired" id="lotdesired" size="5" value="<?php echo $this->_tpl_vars['lots_desired'][$this->_tpl_vars['myId']]; ?>
" readonly="readonly"/>
			</td>
		</tr>
		<tr>
			<td colspan="5" style="padding: 3px;"></td>
		</tr>
		<tr class="proxyheader">
			<td width="" class="styleborder">Priority</td>
			<td width="" class="styleborder">Lot #</td>
			<td width="" class="styleborder">My Max Bid</td>
			<td width="" class="styleborder"><?php echo @label_short_desc; ?>
</td>
			<td width="" class="styleborder">Next Bid</td>
		</tr>
		<tr>
			<td colspan="5" style="padding: 1px;"></td>
		</tr>
		<?php $_from = $this->_tpl_vars['item1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['myId1'] => $this->_tpl_vars['item2']):
?>
			<tr id="row1" class="auction_row1" >
				<td width="" id="prioritycol1" class="styleborder"><?php echo $this->_tpl_vars['item2']->priority; ?>
</td>
				<td width="" id="lotcol1" class="styleborder"><?php echo $this->_tpl_vars['item2']->title; ?>
</td>
				<td width="" id="mybidcol1" class="styleborder"><?php echo $this->_tpl_vars['item2']->my_bid; ?>
</td>
				<td width="" id="desccol1" class="styleborder"><?php echo $this->_tpl_vars['item2']->shortdesc; ?>
</td>
				<td width="" id="nextbidcol1" class="styleborder"><?php echo $this->_tpl_vars['countdownmess']; ?>
<?php if ($this->_tpl_vars['countdown'] != -1): ?><?php echo $this->_tpl_vars['item2']->bid_next; ?>
<?php else: ?><?php echo $this->_tpl_vars['item2']->countdownmess; ?>
<?php endif; ?></td>
			</tr>
		<?php endforeach; endif; unset($_from); ?>
		<tr>
			<td colspan="5" >&nbsp;</td>
		</tr>
		<tr id="rowbutton"><td colspan="5" align="right">
		<?php if ($this->_tpl_vars['start_date'] > ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S'))): ?>
			<input type="button" value="Delete Entire ticket" id="delticket" class="button art-button"  onClick="delTicket(<?php echo $this->_tpl_vars['myId']; ?>
)">
			<a href='<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/index.php?option=com_bids&task=editproxyticket&ticketid=<?php echo $this->_tpl_vars['myId']; ?>
' style="text-decoration: none;"><input type="button" value="Edit Ticket" class="button art-button"  id="editticket" onClick="window.location='<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/index.php?option=com_bids&task=editproxyticket&ticketid=<?php echo $this->_tpl_vars['myId']; ?>
';"></a> 
		<?php else: ?>
			<a href='<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/index.php?option=com_bids&task=editproxyticket&ticketid=<?php echo $this->_tpl_vars['myId']; ?>
&run=1' style="text-decoration: none;"><input type="button" value="Edit Ticket" class="button art-button"  id="editticket" onClick="window.location='<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/index.php?option=com_bids&task=editproxyticket&ticketid=<?php echo $this->_tpl_vars['myId']; ?>
&run=1';"></a> 
		<?php endif; ?>
		
		</td></tr>
		</table>
	  </td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<?php elseif ($this->_tpl_vars['ticketid'] > 0 || $this->_tpl_vars['sessionproxy'] > 0): ?>
<tr id="" >	
	  <td  colspan="2">
		
		<table cellpadding="2" cellspacing="2" width="80%" align="center">
		<tr>
			<td colspan="2" style="font-size: 14px; font-weight: bold;"><?php echo @ticket; ?>
 <?php echo $this->_tpl_vars['userticket'][$this->_tpl_vars['myId']]; ?>
 
			<?php if ($this->_tpl_vars['sessionproxy'] > 0): ?>
				<input name="ticketid" id="ticketid" type="hidden" value="<?php echo $this->_tpl_vars['session']['proxy']['1']['6']; ?>
"> 
			<?php else: ?>
				<input name="ticketid" id="ticketid" type="hidden" value="<?php echo $this->_tpl_vars['ticketid']; ?>
"> 
			<?php endif; ?>
			
			<input type="hidden" value="edit" id="proxyplustype" name="proxyplustype">
			<input name="run" id="run" type="hidden" value="<?php echo $this->_tpl_vars['isRun']; ?>
">
			</td>
			<td colspan="3" align="left" style="font-size: 24px; color: red; font-weight: bold;"><?php echo @lot_desired; ?>

			<?php if ($this->_tpl_vars['sessionproxy'] > 0): ?>
				<input class="proxy"  type="text" name="lotdesired" id="lotdesired" size="5" value="<?php echo $this->_tpl_vars['session']['proxy']['1']['5']; ?>
"/>
				<input class="proxy"  type="hidden" name="oldlotdesired" id="oldlotdesired" size="5" value="<?php echo $this->_tpl_vars['session']['proxy']['1']['5']; ?>
"/>
			<?php else: ?>
				<input class="proxy"  type="text" name="lotdesired" id="lotdesired" size="5" value="<?php echo $this->_tpl_vars['lots_desired'][$this->_tpl_vars['myId']]; ?>
"/>
				<input class="proxy"  type="hidden" name="oldlotdesired" id="oldlotdesired" size="5" value="<?php echo $this->_tpl_vars['lots_desired'][$this->_tpl_vars['myId']]; ?>
"/>
			<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td colspan="5" style="padding: 1px;"></td>
		</tr>
		<tr class="proxyheader">
			<td width="" class="styleborder">Priority</td>
			<td width="" class="styleborder">Lot #</td>
			<td width="" class="styleborder">My Max Bid</td>
			<td width="" class="styleborder"><?php echo @label_short_desc; ?>
</td>
			<td width="" class="styleborder">Next Bid</td>
		</tr>
		<tr>
			<td colspan="5" style="padding: 2px;"></td>
		</tr>
		
		<?php if ($this->_tpl_vars['sessionproxy'] > 0): ?>
		  <?php $_from = $this->_tpl_vars['session']['proxy']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['myId'] => $this->_tpl_vars['item1']):
?>
			<tr id="row<?php echo $this->_tpl_vars['myId']; ?>
" class="auction_row1" >
				<td width="20%" id="prioritycol<?php echo $this->_tpl_vars['myId']; ?>
" class="styleborder">
					<input class="proxy" name="id<?php echo $this->_tpl_vars['myId']; ?>
" id="id<?php echo $this->_tpl_vars['myId']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['myId']; ?>
"><?php echo $this->_tpl_vars['myId']; ?>

				</td>
				<td width="20%" id="lotcol<?php echo $this->_tpl_vars['myId']; ?>
" class="styleborder">
					<input class="proxy"  type="text" name="lotno<?php echo $this->_tpl_vars['myId']; ?>
" id="lotno<?php echo $this->_tpl_vars['myId']; ?>
" value="<?php echo $this->_tpl_vars['item1']['2']; ?>
" onBlur="showNextRow(<?php echo $this->_tpl_vars['myId']; ?>
);" onMouseDown="showNextRow(<?php echo $this->_tpl_vars['myId']; ?>
);"  >
					<input type="hidden" name="oldlotno<?php echo $this->_tpl_vars['item1']['0']; ?>
" id="oldlotno<?php echo $this->_tpl_vars['item1']['0']; ?>
" value="<?php echo $this->_tpl_vars['item1']['1']; ?>
" >
					<input type="hidden" name="auction_id<?php echo $this->_tpl_vars['myId']; ?>
" id="auction_id<?php echo $this->_tpl_vars['myId']; ?>
" value="<?php echo $this->_tpl_vars['item1']['7']; ?>
">
				</td>
				<td width="20%" id="mybidcol<?php echo $this->_tpl_vars['myId']; ?>
" class="styleborder">
				    <input class="proxy" type="text" name="mybid<?php echo $this->_tpl_vars['myId']; ?>
" id="mybid<?php echo $this->_tpl_vars['myId']; ?>
" value="<?php echo $this->_tpl_vars['item1']['1']; ?>
"  onkeypress="return onlyNumbers(event);">
				</td>
				<td width="20%" id="desccol<?php echo $this->_tpl_vars['myId']; ?>
" class="styleborder"><?php echo $this->_tpl_vars['item1']['3']; ?>
</td>
				<td width="20%" id="nextbidcol<?php echo $this->_tpl_vars['myId']; ?>
" class="styleborder"><?php echo $this->_tpl_vars['item1']['4']; ?>
</td>
			</tr>
		  <?php endforeach; endif; unset($_from); ?>
		  <?php echo '
				<script>
					editticket2();
				</script>
		  '; ?>

			  <tr id="row<?php echo $this->_tpl_vars['item1']['0']+6; ?>
" style="display: none;" class="auction_row1"></tr>
		<?php else: ?>
		<?php $_from = $this->_tpl_vars['item1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['myId1'] => $this->_tpl_vars['item2']):
?>
			<tr id="row<?php echo $this->_tpl_vars['item2']->priority; ?>
" class="auction_row1" >
				<td width="20%" id="prioritycol<?php echo $this->_tpl_vars['item2']->priority; ?>
" class="styleborder">
				    <input class="proxy" name="id<?php echo $this->_tpl_vars['item2']->priority; ?>
" id="id<?php echo $this->_tpl_vars['item2']->priority; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['item2']->priority; ?>
"><?php echo $this->_tpl_vars['item2']->priority; ?>

				</td>
				<td width="20%" id="lotcol<?php echo $this->_tpl_vars['item2']->priority; ?>
" class="styleborder">
				<?php if ($this->_tpl_vars['isRun'] == 1): ?>
				    <input class="proxy"  type="text" name="lotno<?php echo $this->_tpl_vars['item2']->priority; ?>
" id="lotno<?php echo $this->_tpl_vars['item2']->priority; ?>
" readonly="readonly" disabled="disabled" value="<?php echo $this->_tpl_vars['item2']->title; ?>
" onBlur="showNextRow(<?php echo $this->_tpl_vars['item2']->priority; ?>
);" onMouseDown="showNextRow(<?php echo $this->_tpl_vars['item2']->priority; ?>
);" >
				<?php else: ?>
				    <input class="proxy"  type="text" name="lotno<?php echo $this->_tpl_vars['item2']->priority; ?>
" id="lotno<?php echo $this->_tpl_vars['item2']->priority; ?>
" value="<?php echo $this->_tpl_vars['item2']->title; ?>
" onBlur="showNextRow(<?php echo $this->_tpl_vars['item2']->priority; ?>
);" onMouseDown="showNextRow(<?php echo $this->_tpl_vars['item2']->priority; ?>
);" >
				<?php endif; ?>
					<input type="hidden" name="oldlotno<?php echo $this->_tpl_vars['item2']->priority; ?>
" id="oldlotno<?php echo $this->_tpl_vars['item2']->priority; ?>
" value="<?php echo $this->_tpl_vars['item2']->title; ?>
" ><input type="hidden" name="auction_id<?php echo $this->_tpl_vars['item2']->priority; ?>
" id="auction_id<?php echo $this->_tpl_vars['item2']->priority; ?>
" value="<?php echo $this->_tpl_vars['item2']->auction_id; ?>
">
				</td>
				<td width="20%" id="mybidcol<?php echo $this->_tpl_vars['item2']->priority; ?>
" class="styleborder">
				    <input class="proxy" type="text" name="mybid<?php echo $this->_tpl_vars['item2']->priority; ?>
" onkeypress="return onlyNumbers(event);" id="mybid<?php echo $this->_tpl_vars['item2']->priority; ?>
" value="<?php echo $this->_tpl_vars['item2']->my_bid; ?>
" onBlur="checkbid(<?php echo $this->_tpl_vars['item2']->priority; ?>
);" onMouseDown="checkbid(<?php echo $this->_tpl_vars['item2']->priority; ?>
);" >
				    <input type="hidden" name="oldmybid<?php echo $this->_tpl_vars['item2']->priority; ?>
" id="oldmybid<?php echo $this->_tpl_vars['item2']->priority; ?>
" value="<?php echo $this->_tpl_vars['item2']->my_bid; ?>
">
					<input type="hidden" name="is_outbid<?php echo $this->_tpl_vars['item2']->priority; ?>
" id="is_outbid<?php echo $this->_tpl_vars['item2']->priority; ?>
" value="<?php echo $this->_tpl_vars['item2']->outbid; ?>
">
				</td>
				<td width="20%" id="desccol<?php echo $this->_tpl_vars['item2']->priority; ?>
" class="styleborder"><?php echo $this->_tpl_vars['item2']->shortdesc; ?>
</td>
				<td width="20%" id="nextbidcol<?php echo $this->_tpl_vars['item2']->priority; ?>
" class="styleborder"><?php echo $this->_tpl_vars['item2']->bid_next; ?>
</td>
			</tr>
		<?php endforeach; endif; unset($_from); ?>
		<?php echo '
				<script>
					editticket2();
				</script>
		'; ?>

		<tr id="row<?php echo $this->_tpl_vars['item2']->priority+6; ?>
" style="display: none;" class="auction_row1"></tr>
		<?php endif; ?>
		
		<tr>
			<td colspan="5" >&nbsp;</td>
		</tr>
		<tr id="rowbutton"><td colspan="5" align="right">
		<?php if ($this->_tpl_vars['sessionproxy'] == 0 && $this->_tpl_vars['start_date'] > ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S'))): ?>
			<input type="button" value="Delete Entire ticket" id="delticket" class="button art-button"  onClick="delTicket(<?php echo $this->_tpl_vars['ticketid']; ?>
)"> 
		<?php else: ?>
			<input type="button" value="Edit Ticket" id="editticket" class="button art-button"  onClick="editticket1();" style="display: none;">
		<?php endif; ?>
		<input type="button" value="Continue to Confirm" class="button art-button"  id="confirmticket" onClick="confirmProxyPlus(1);"> <input type="submit" value="SEND BIDS" class="button art-button"  id="sendbid" name="sendbid" style="display:none;" disabled="true">
		<?php if ($this->_tpl_vars['sessionproxy'] > 0): ?>
			
			<input type="hidden" name="type" id="type" value="1">
		<?php else: ?>
			<input type="hidden" name="type" id="type" value="2">
		<?php endif; ?>
		</td></tr>
		</table>
	  </td>
</tr>
<?php else: ?>
<tr id="" >	
	  <td  colspan="2">
		
		<table cellpadding="2" cellspacing="2" width="80%" align="center">
		<tr>
			<td colspan="2" style="font-size: 14px; font-weight: bold;"><?php echo @ticket; ?>
 <?php if ($this->_tpl_vars['count'] == 0): ?> 1 <input name="ticketid" id="ticketid" type="hidden" value="1"> <?php else: ?> <?php echo $this->_tpl_vars['count']+1; ?>
 <input name="ticketid" id="ticketid" type="hidden" value="<?php echo $this->_tpl_vars['count']+1; ?>
"><?php endif; ?> 
			<input type="hidden" value="new" id="proxyplustype" name="proxyplustype">
			<input name="run" id="run" type="hidden" value="0">
			</td>
			<td colspan="3" align="left" style="font-size: 24px; color: red; font-weight: bold;"><?php echo @lot_desired; ?>
 <input class="proxy" type="text" name="lotdesired" id="lotdesired" size="5" <?php if ($this->_tpl_vars['fromProxy'] > 0): ?> value="1" <?php endif; ?> />
			</td>
		</tr>
		<tr>
			<td colspan="5" style="padding: 1px;"></td>
		</tr>
		<tr class="proxyheader">
			<td width="" class="styleborder">Priority</td>
			<td width="" class="styleborder">Lot #</td>
			<td width="" class="styleborder">My Max Bid</td>
			<td width="" class="styleborder"><?php echo @label_short_desc; ?>
</td>
			<td width="" class="styleborder">Next Bid</td>
		</tr>
		<tr>
			<td colspan="5" style="padding: 2px;"></td>
		</tr>
		<?php if ($this->_tpl_vars['fromProxy'] > 0): ?>
		<tr id="row1" class="auction_row1" >
			<td width="20%" id="prioritycol1" class="styleborder">1 <input type="hidden" name="id1" id="id1" value="1"></td>
			<td width="20%" id="lotcol1" class="styleborder"><input class="proxy" type="text" name="lotno1" id="lotno1" value="<?php echo $this->_tpl_vars['rowProxy']->title; ?>
" onBlur="showNextRow('1');" onMouseDown="showNextRow('1');"><input type="hidden" name="auction_id1" id="auction_id1" value="<?php echo $this->_tpl_vars['fromProxy']; ?>
"></td>
			<td width="20%" id="mybidcol1" class="styleborder"><input class="proxy" type="text" name="mybid1" id="mybid1" value="<?php echo $this->_tpl_vars['amountProxy']; ?>
" onBlur="checkbid(1);" onMouseDown="checkbid(1);" onkeypress="return onlyNumbers(event);"></td>
			<td width="20%" id="desccol1" class="styleborder"><?php echo $this->_tpl_vars['rowProxy']->shortdescription; ?>
</td>
			<td width="20%" id="nextbidcol1" class="styleborder"><?php echo $this->_tpl_vars['rowProxy']->bid_next; ?>
</td>
		</tr>
		<tr id="row2" class="auction_row2" >
			<td width="20%" id="prioritycol2" class="styleborder">2 <input type="hidden" name="id2" id="id2" value="2"></td>
			<td width="20%" id="lotcol2" class="styleborder"><input class="proxy" type="text" name="lotno2" id="lotno2" onBlur="showNextRow('2');" onMouseDown="showNextRow('2');" ><input type="hidden" name="auction_id2" id="auction_id2" value=""></td>
			<td width="20%" id="mybidcol2" class="styleborder"><input class="proxy" type="text" name="mybid2" id="mybid2" onkeypress="return onlyNumbers(event);" onBlur="checkbid(2);" onMouseDown="checkbid(2);"></td>
			<td width="20%" id="desccol2" class="styleborder"></td>
			<td width="20%" id="nextbidcol2" class="styleborder"></td>
		</tr>
		<tr id="row3" class="auction_row1" >
			<td width="" id="prioritycol3" class="styleborder">3<input type="hidden" name="id3" id="id3" value="3"></td>
			<td width="" id="lotcol3" class="styleborder"><input class="proxy" type="text" name="lotno3" id="lotno3" onBlur="showNextRow('3');" onMouseDown="showNextRow('3');"><input type="hidden" name="auction_id3" id="auction_id3" value=""></td>
			<td width="" id="mybidcol3" class="styleborder"><input class="proxy" type="text" name="mybid3" onkeypress="return onlyNumbers(event);" id="mybid3" onBlur="checkbid(3);" onMouseDown="checkbid(3);"></td>
			<td width="" id="desccol3" class="styleborder"></td>
			<td width="" id="nextbidcol3" class="styleborder"></td>
		</tr>
		<tr id="row4" class="auction_row2" >
			<td width="" id="prioritycol4" class="styleborder">4 <input type="hidden" name="id4" id="id4" value="4"></td>
			<td width="" id="lotcol4" class="styleborder"><input class="proxy" type="text" name="lotno4" id="lotno4" onBlur="showNextRow('4');" onMouseDown="showNextRow('4');"><input type="hidden" name="auction_id4" id="auction_id4" value=""></td>
			<td width="" id="mybidcol4" class="styleborder"><input class="proxy" type="text" name="mybid4" id="mybid4" onkeypress="return onlyNumbers(event);" onBlur="checkbid(4);" onMouseDown="checkbid(4);"></td>
			<td width="" id="desccol4" class="styleborder"></td>
			<td width="" id="nextbidcol4" class="styleborder"></td>
		</tr>
		<tr id="row5" class="auction_row1" >
			<td width="" id="prioritycol5" class="styleborder">5 <input type="hidden" name="id5" id="id5" value="5"></td>
			<td width="" id="lotcol5" class="styleborder"><input class="proxy" type="text" name="lotno5" id="lotno5" onBlur="showNextRow('5');" onMouseDown="showNextRow('5');"><input type="hidden" name="auction_id5" id="auction_id5" value=""></td>
			<td width="" id="mybidcol5" class="styleborder"><input class="proxy" type="text" name="mybid5" id="mybid5" onkeypress="return onlyNumbers(event);" onBlur="checkbid(5);" onMouseDown="checkbid(5);"></td>
			<td width="" id="desccol5" class="styleborder"></td>
			<td width="" id="nextbidcol5" class="styleborder"></td>
		</tr>
		<?php else: ?>
		<tr id="row1" class="auction_row1" >
			<td width="20%" id="prioritycol1" class="styleborder">1 <input type="hidden" name="id1" id="id1" value="1"></td>
			<td width="20%" id="lotcol1" class="styleborder"><input class="proxy" type="text" name="lotno1" id="lotno1" onBlur="showNextRow('1');" onMouseDown="showNextRow('1');"><input type="hidden" name="auction_id1" id="auction_id1" value=""></td>
			<td width="20%" id="mybidcol1" class="styleborder"><input class="proxy" type="text" name="mybid1" id="mybid1" onkeypress="return onlyNumbers(event);" onBlur="checkbid(1);" onMouseDown="checkbid(1);"></td>
			<td width="20%" id="desccol1" class="styleborder"></td>
			<td width="20%" id="nextbidcol1" class="styleborder"></td>
		</tr>
		<tr id="row2" class="auction_row2" >
			<td width="" id="prioritycol2" class="styleborder">2 <input type="hidden" name="id2" id="id2" value="2"></td>
			<td width="" id="lotcol2" class="styleborder"><input class="proxy" type="text" name="lotno2" id="lotno2" onBlur="showNextRow('2');" onMouseDown="showNextRow('2');"><input type="hidden" name="auction_id2" id="auction_id2" value=""></td>
			<td width="" id="mybidcol2" class="styleborder"><input class="proxy" type="text" name="mybid2" id="mybid2" onkeypress="return onlyNumbers(event);" onBlur="checkbid(2);" onMouseDown="checkbid(2);"></td>
			<td width="" id="desccol2" class="styleborder"></td>
			<td width="" id="nextbidcol2" class="styleborder"></td>
		</tr>
		<tr id="row3" class="auction_row1" >
			<td width="" id="prioritycol3" class="styleborder">3<input type="hidden" name="id3" id="id3" value="3"></td>
			<td width="" id="lotcol3" class="styleborder"><input class="proxy" type="text" name="lotno3" id="lotno3" onBlur="showNextRow('3');" onMouseDown="showNextRow('3');"><input type="hidden" name="auction_id3" id="auction_id3" value=""></td>
			<td width="" id="mybidcol3" class="styleborder"><input class="proxy" type="text" name="mybid3" id="mybid3" onkeypress="return onlyNumbers(event);" onBlur="checkbid(3);" onMouseDown="checkbid(3);"></td>
			<td width="" id="desccol3" class="styleborder"></td>
			<td width="" id="nextbidcol3" class="styleborder"></td>
		</tr>
		<tr id="row4" class="auction_row2" >
			<td width="" id="prioritycol4" class="styleborder">4 <input type="hidden" name="id4" id="id4" value="4"></td>
			<td width="" id="lotcol4" class="styleborder"><input class="proxy" type="text" name="lotno4" id="lotno4" onBlur="showNextRow('4');" onMouseDown="showNextRow('4');"><input type="hidden" name="auction_id4" id="auction_id4" value=""></td>
			<td width="" id="mybidcol4" class="styleborder"><input class="proxy" type="text" name="mybid4" id="mybid4" onkeypress="return onlyNumbers(event);" onBlur="checkbid(4);" onMouseDown="checkbid(4);"></td>
			<td width="" id="desccol4" class="styleborder"></td>
			<td width="" id="nextbidcol4" class="styleborder"></td>
		</tr>
		<tr id="row5" class="auction_row1" >
			<td width="" id="prioritycol5" class="styleborder">5 <input type="hidden" name="id5" id="id5" value="5"></td>
			<td width="" id="lotcol5" class="styleborder"><input class="proxy" type="text" name="lotno5" id="lotno5" onBlur="showNextRow('5');" onMouseDown="showNextRow('5');"><input type="hidden" name="auction_id5" id="auction_id5" value=""></td>
			<td width="" id="mybidcol5" class="styleborder"><input class="proxy" type="text" name="mybid5" id="mybid5" onkeypress="return onlyNumbers(event);" onBlur="checkbid(5);" onMouseDown="checkbid(5);"></td>
			<td width="" id="desccol5" class="styleborder"></td>
			<td width="" id="nextbidcol5" class="styleborder"></td>
		</tr>
		<?php endif; ?>
		<tr id="row6" style="display: none;" class="auction_row1"></tr>
		<tr id="alertmess" style="display: none"><td colspan="5" align="right" style="color: red; font-size: 12px;">Warning: This ticket CANNOT be changed when bidding has begun.</td></tr>
		<tr id="rowbutton"><td colspan="5" align="right">
		<input type="button" value="Edit Ticket" id="editticket" class="button art-button"  onClick="editticket1();" style="display: none;">
		<input type="button" value="Continue to Confirm" class="button art-button"  id="confirmticket" onClick="confirmProxyPlus(1);">
		<input type="submit" value="SEND BIDS" class="button art-button"  id="sendbid" name="sendbid" onClick="confirmProxyPlus(2);" style="display: none;" disabled="true">
		<input type="hidden" name="type" id="type" value="1"></td></tr>
		</table>
	  </td>
</tr>

<?php endif; ?>