<?php /* Smarty version 2.6.18, created on 2011-06-03 11:10:10
         compiled from t_listbigboard.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'set_css', 't_listbigboard.tpl', 8, false),array('modifier', 'string_format', 't_listbigboard.tpl', 85, false),)), $this); ?>
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
<?php echo '
<script type="text/javascript">
   function backOrg(ids) {
	$("#row"+ids).attr("style","");
	var pid = $("#pid"+ids).val();
	$("#colprice"+ids).html(\'<a class="nolink" href="index.php?option=com_bids&amp;task=viewbids&amp;id=\'+ids+\'&orgtask=listbigboard&p=\'+pid+\'">\'+new1[\'bid_price\']+\'</a>\');
	$("#coluser"+ids).html(\'<a class="nolink" href="index.php?option=com_bids&amp;task=viewbids&amp;id=\'+ids+\'&orgtask=listbigboard&p=\'+pid+\'">\'+new1[\'userid\']+\'</a>\');
	$("#colnext"+ids).html(\'<a class="nolink" href="index.php?option=com_bids&amp;task=viewbids&amp;id=\'+ids+\'&orgtask=listbigboard&p=\'+pid+\'">\'+new1[\'bid_next\']+\'</a>\');
   }
   function checkCount(min,colorbg,colorbgmin) {
			$.post("index.php", {
					option: "com_bids",
					task: "checkbb"
			}, function(data) {
				var data1;
				var olddata = data;
				data = eval("(" + data + ")");
				if(data != 0) {
					for (data1 in data) {
						var new1 = data[data1];
						var pid = $("#pid"+new1[\'id_offer\']).val();
						$("#colprice"+new1[\'id_offer\']).html(\'<a class="nolink" href="index.php?option=com_bids&amp;task=viewbids&amp;id=\'+new1[\'id_offer\']+\'&orgtask=listbigboard&p=\'+pid+\'">\'+new1[\'bid_price\']+\'</a>\');
						$("#coluser"+new1[\'id_offer\']).html(\'<a class="nolink" href="index.php?option=com_bids&amp;task=viewbids&amp;id=\'+new1[\'id_offer\']+\'&orgtask=listbigboard&p=\'+pid+\'">\'+new1[\'userid\']+\'</a>\');
						$("#colnext"+new1[\'id_offer\']).html(\'<a class="nolink" href="index.php?option=com_bids&amp;task=viewbids&amp;id=\'+new1[\'id_offer\']+\'&orgtask=listbigboard&p=\'+pid+\'">\'+new1[\'bid_next\']+\'</a>\');
						var currentColor = $("#row"+new1["id_offer"]).attr("style");
						if(currentColor == undefined || currentColor.match("background-color:") == null){
							$("#row"+new1[\'id_offer\']).attr("style","background-color: "+colorbg+";").fadeIn("1000");
							var str1 = \'backOrg(\'+new1["id_offer"]+\');\'
							setTimeout(str1, (colorbgmin * 1000 * 60));
						}
					}
				}
			}); 
	return true;
 }
 function refreshMe() {
	window.location.reload();
 }

</script>
'; ?>

<table width="100%" cellpadding="0" cellspacing="0" border="0" valign="top" style="padding-top: -10px; ">
<tr><td  style="text-align: left; color: green;" width="20%"><b>Click on Lot# to Bid</b></td>
<td align="center"  width="60%"><span style="text-align: center; font-size: 20px; color: blue;" ><b>
<?php if ($this->_tpl_vars['task'] == 'listbigboard'): ?>
	THE BIG BOARD
<?php elseif ($this->_tpl_vars['task'] == 'listmybigboard'): ?>
	MY For Sale BIG BOARD
<?php else: ?>
	MY BIG BOARD
<?php endif; ?>
</b></span></td>
<td align="right"  width="20%"><input type="button" value="Refresh (F5)" id="refresh" class="button art-button"  onClick="refreshMe();"></td></tr>
</table>
<table align="center" cellpadding="2" cellspacing="2" width="100%" id="auction_list_container" border="0" style="border: 1px solid #FFFFFF">
    <tr id="auction_row<?php echo $this->_tpl_vars['class']; ?>
" >	
     <?php unset($this->_sections['loop1']);
$this->_sections['loop1']['name'] = 'loop1';
$this->_sections['loop1']['start'] = (int)0;
$this->_sections['loop1']['loop'] = is_array($_loop=3) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['loop1']['step'] = ((int)1) == 0 ? 1 : (int)1;
$this->_sections['loop1']['show'] = true;
$this->_sections['loop1']['max'] = $this->_sections['loop1']['loop'];
if ($this->_sections['loop1']['start'] < 0)
    $this->_sections['loop1']['start'] = max($this->_sections['loop1']['step'] > 0 ? 0 : -1, $this->_sections['loop1']['loop'] + $this->_sections['loop1']['start']);
else
    $this->_sections['loop1']['start'] = min($this->_sections['loop1']['start'], $this->_sections['loop1']['step'] > 0 ? $this->_sections['loop1']['loop'] : $this->_sections['loop1']['loop']-1);
if ($this->_sections['loop1']['show']) {
    $this->_sections['loop1']['total'] = min(ceil(($this->_sections['loop1']['step'] > 0 ? $this->_sections['loop1']['loop'] - $this->_sections['loop1']['start'] : $this->_sections['loop1']['start']+1)/abs($this->_sections['loop1']['step'])), $this->_sections['loop1']['max']);
    if ($this->_sections['loop1']['total'] == 0)
        $this->_sections['loop1']['show'] = false;
} else
    $this->_sections['loop1']['total'] = 0;
if ($this->_sections['loop1']['show']):

            for ($this->_sections['loop1']['index'] = $this->_sections['loop1']['start'], $this->_sections['loop1']['iteration'] = 1;
                 $this->_sections['loop1']['iteration'] <= $this->_sections['loop1']['total'];
                 $this->_sections['loop1']['index'] += $this->_sections['loop1']['step'], $this->_sections['loop1']['iteration']++):
$this->_sections['loop1']['rownum'] = $this->_sections['loop1']['iteration'];
$this->_sections['loop1']['index_prev'] = $this->_sections['loop1']['index'] - $this->_sections['loop1']['step'];
$this->_sections['loop1']['index_next'] = $this->_sections['loop1']['index'] + $this->_sections['loop1']['step'];
$this->_sections['loop1']['first']      = ($this->_sections['loop1']['iteration'] == 1);
$this->_sections['loop1']['last']       = ($this->_sections['loop1']['iteration'] == $this->_sections['loop1']['total']);
?>
	  <td >
		<table width="100%" style="border: 1px solid #FFFFFF;">
		<tr >
			<td class="col1" width="20%">&nbsp;&nbsp;Lot #</td>
			<td class="col" width="21%"><?php echo ((is_array($_tmp=@label_short_desc)) ? $this->_run_mod_handler('string_format', true, $_tmp, "%10.10s") : smarty_modifier_string_format($_tmp, "%10.10s")); ?>
</td>
			<td  width="20%" colspan="2">Current Bid</td>
			<td  width="20%" align="center">Current Bidder #</td>
			<td  width="20%" colspan="2">Next Bid</td>
		</tr>
		</table>
	  </td>
     <?php endfor; endif; ?>
  </tr>
   <tr>
     <td align="left" valign="top">
		<?php $this->assign('countHead', 0); ?>
		<?php $this->assign('headeriteration', 1); ?>
		<?php $this->assign('headeriterationorg', 1); ?>
		<?php $this->assign('headerinit', '0'); ?>
		<table width="100%" cellpadding="1" cellspacing="0" border="0" valign="top" >
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
			<?php $this->assign('iteration1', $this->_sections['auctionsloop']['iteration']-1); ?>
			<?php if ((1 & $this->_sections['auctionsloop']['iteration']/2)): ?>
				<?php $this->assign('class', '1'); ?>
			<?php else: ?>
				<?php $this->assign('class', '2'); ?>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['headeriteration'] == 1 && $this->_tpl_vars['headerinit'] == 0): ?>
				<tr><td colspan="7" align="center"><?php echo $this->_tpl_vars['headers'][$this->_tpl_vars['headeriteration']]; ?>
</td></tr>
				<?php $this->assign('headerinit', 1); ?>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['countHead'] == $this->_tpl_vars['headersnum'][$this->_tpl_vars['headeriteration']]): ?>
				<?php $this->assign('headeriteration', ($this->_tpl_vars['headeriteration']+1)); ?>
				<?php if ($this->_tpl_vars['headersoption'][$this->_tpl_vars['headeriteration']] == 1 && $this->_tpl_vars['headeriteration'] != 1): ?>
					</table></td>
					<td align="left" valign="top">
					<table width="100%" cellpadding="1" cellspacing="0" border="0" valign="top" >
				<?php endif; ?>
				
				<tr><td colspan="7" align="center"><?php echo $this->_tpl_vars['headers'][$this->_tpl_vars['headeriteration']]; ?>
</td></tr>
				<?php $this->assign('countHead', 1); ?>
			<?php else: ?>
				<?php $this->assign('countHead', ($this->_tpl_vars['countHead']+1)); ?>
			<?php endif; ?>
			<?php $this->assign('current_row', ($this->_tpl_vars['auction_rows'][$this->_sections['auctionsloop']['index']])); ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 't_listbigboard_cell.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			
 		<?php endfor; endif; ?>
		</table>
	 </td>
   </tr>
</table>
</form>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/js/auctions.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/js/startcounter.js"></script>