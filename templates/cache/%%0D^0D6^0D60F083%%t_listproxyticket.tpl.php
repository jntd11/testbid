<?php /* Smarty version 2.6.18, created on 2012-05-29 09:43:07
         compiled from t_listproxyticket.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'set_css', 't_listproxyticket.tpl', 8, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 't_overlib.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php echo smarty_set_css(array(), $this);?>


<form action="index.php?option=com_bids&Itemid=<?php echo $this->_tpl_vars['Itemid']; ?>
" method="post" name="auctionForm" onSubmit="return validateProxyPlus();">
<input type="hidden" name="task" id="task" value="saveproxyplus">
<?php if ($this->_tpl_vars['sessionproxy'] > 0): ?>
			<input type="hidden" name="currentid" id="currentid" value="<?php echo $this->_tpl_vars['sessionproxy']; ?>
" />
<?php else: ?>
			<input type="hidden" name="currentid" id="currentid" value="<?php if ($this->_tpl_vars['ticketid'] > 0): ?><?php echo $this->_tpl_vars['countBids']; ?>
<?php elseif ($this->_tpl_vars['fromProxy'] > 0): ?>1<?php else: ?>0<?php endif; ?>" />
<?php endif; ?>

<input type="hidden" name="maxid" id="maxid" value="<?php if ($this->_tpl_vars['ticketid'] > 0): ?> <?php echo $this->_tpl_vars['countBids']; ?>
 <?php else: ?> 0 <?php endif; ?>" />
<input type="hidden" name="totid" id="totid" value="<?php if ($this->_tpl_vars['ticketid'] > 0): ?> <?php echo $this->_tpl_vars['countBids']; ?>
 <?php else: ?> 0 <?php endif; ?>" />

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
<script type="text/javascript" src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/js/popup.js"></script>
<?php echo '
<script>
function setCookie(c_name,value,expiredays)
{
var exdate=new Date();
exdate.setDate(exdate.getDate()+expiredays);
document.cookie=c_name+ "=" +escape(value)+
((expiredays==null) ? "" : ";expires="+exdate.toGMTString());
}
function getCookie(c_name)
{
if (document.cookie.length>0)
  {
  c_start=document.cookie.indexOf(c_name + "=");
  if (c_start!=-1)
    {
    c_start=c_start + c_name.length+1;
    c_end=document.cookie.indexOf(";",c_start);
    if (c_end==-1) c_end=document.cookie.length;
    return unescape(document.cookie.substring(c_start,c_end));
    }
  }
return "";
}
 function refreshMe() {
	window.location.reload();
 }
function toggle(id,callid, userid) {
		$("#"+id).toggle();
		if(id == "proxyticketmessage") {
		   if(getCookie("infomain"+userid) == "") {
			 setCookie("infomain"+userid,"less",30);
		   }else {
			 setCookie("infomain"+userid,"",30);
		   }
		}
		else {
			if(callid.value == "More Info") {
				setCookie("info"+userid,"less",30);
				$("#"+callid.id).val("Less Info");
			}else {
				setCookie("info"+userid,"more",30);
				$("#"+callid.id).val("More Info");
			}
		}
}

function editticket1() {
	$("input:text").attr("readonly","");
	$("#alertmess").attr(\'style\',\'display: none;\');
	$("#confirmticket").toggle();
	$("#sendbid").toggle();
	$("#sendbid").removeAttr("disabled");
	$("#editticket").toggle();
	var currid = $("#currentid").val();
	var nextid = Number(currid);
	//$("#row"+nextid).toggle();
	for(var i=1; i<=5; i++) {
		currid = nextid;
		nextid = nextid + 1;
		jQuery("#row"+currid).after(\'<tr id="row\'+nextid+\'" style="display: none;" class="auction_row1"></tr>\');
		jQuery("#row"+nextid).html(\'<td width="20%" class="styleborder" id="prioritycol\'+nextid+\'">\'+nextid+\'<input type="hidden" name="id\'+nextid+\'" id="id\'+nextid+\'" value="\'+nextid+\'"></td><td class="styleborder" width="20%" id="lotcol\'+nextid+\'"><input class="proxy"  type="text" name="lotno\'+nextid+\'" id="lotno\'+nextid+\'" onBlur="showNextRow(\'+nextid+\');" onMouseDown="showNextRow(\'+nextid+\');"><input type="hidden" name="auction_id\'+nextid+\'" id="auction_id\'+nextid+\'"></td><td class="styleborder" width="20%" id="mybidcol\'+nextid+\'"><input class="proxy" type="text" name="mybid\'+nextid+\'" id="mybid\'+nextid+\'" onkeypress="return onlyNumbers(event);" onBlur="checkbid(\'+nextid+\')" onMouseDown="checkbid(\'+nextid+\')"></td><td class="styleborder" width="20%" id="desccol\'+nextid+\'"></td><td class="styleborder" width="20%" id="nextbidcol\'+nextid+\'"></td>\');
		jQuery("#row"+nextid).show();
	}
	//$("#currentid").val(currid);
}
function editticket2(currid){
	var currid = $("#currentid").val();
	var nextid = Number(currid);
	for(var i=1; i<=5; i++) {
		currid = nextid;
		nextid = nextid + 1;
		jQuery("#row"+currid).after(\'<tr id="row\'+nextid+\'" style="display: none;" class="auction_row1"></tr>\');
		jQuery("#row"+nextid).html(\'<td width="20%" class="styleborder" id="prioritycol\'+nextid+\'">\'+nextid+\'<input type="hidden" name="id\'+nextid+\'" id="id\'+nextid+\'" value="\'+nextid+\'"></td><td class="styleborder" width="20%" id="lotcol\'+nextid+\'"><input class="proxy"  type="text" name="lotno\'+nextid+\'" id="lotno\'+nextid+\'" onBlur="showNextRow(\'+nextid+\');" onMouseDown="showNextRow(\'+nextid+\');"><input type="hidden" name="auction_id\'+nextid+\'" id="auction_id\'+nextid+\'"></td><td class="styleborder" width="20%" id="mybidcol\'+nextid+\'"><input class="proxy" type="text" name="mybid\'+nextid+\'" id="mybid\'+nextid+\'" onkeypress="return onlyNumbers(event);" onBlur="checkbid(\'+nextid+\')" onMouseDown="checkbid(\'+nextid+\')"></td><td class="styleborder" width="20%" id="desccol\'+nextid+\'"></td><td class="styleborder" width="20%" id="nextbidcol\'+nextid+\'"></td>\');
		jQuery("#row"+nextid).show();
	}
	//$("#currentid").val(currid);
}

function confirmProxyPlus(type) {
	
	var sessionvar = "";
	var orgcount = Number($("#currentid").val());
	//Commented on 11/2/2011 - 
	//var totalcount = Number($("#currentid").val()) + 1;
	var totalcount = Number($("#currentid").val()) + 5;
	var maxid = Number($("#maxid").val());
	var lotdesired = $("#lotdesired").val();
	var countLots = 0;
	var isRun;
	var oldlotdesired = 0;
	oldlotdesired = $("#oldlotdesired").val();
	isRun = $("#run").val();

	if(Number(totalcount) <= 1 &&  Number(maxid) < 1){
		alert("Atleast One Lot should be entered");
		return false;
	}

	if(isRun == 1 &&  Number(oldlotdesired) > Number(lotdesired)) {
		alert("You can\'t decrease the lot desired");
		$("#lotdesired").val(oldlotdesired);
		return false;
	}

	if(type == 2){
		$("#sendbid").attr(\'style\',"display:none");
		$("#editticket").attr(\'style\',"display:none");
	}

	totalrow = Number($("#maxid").val()) + 15;
	/*
	@date: 3/1/2010
	@code: JaiSTARTO
	@comment: Proxy Bug Multiple 
	*/
	if(totalrow < totalcount){
		totalrow = totalcount;
	}
	/*
	@date: 3/1/2010
	@code: JaiENDO
	@comment: Proxy Bug Multiple 
	*/
	status = 0;
	var isNextRow;
	for(var i = 1; i <= totalrow; i++) {
		isNextRow = false;
		if($("#lotno"+i).val() == "" && $("#mybid"+i).val() == "") {
			// COmmented 3/27/2011
			
			/**
			 * New way of decrementing the next rows
			 * Done on 3/13/2011
			 **/
				 // Moved to top - 4/28/2011
				 //isNextRow = false;
				 for(var j = i; j <= orgcount; j++) {
					var nextrow = Number(j) + 1;
					if($("#lotno"+nextrow).val() != "" && $("#mybid"+nextrow).val() != ""){
						isNextRow = true;
					}
					$("#auction_id"+j).val($("#auction_id"+nextrow).val());
					$("#lotno"+j).val($("#lotno"+nextrow).val());
					$("#mybid"+j).val($("#mybid"+nextrow).val());
					$("#desccol"+j).html($("#desccol"+nextrow).html());
					$("#nextbidcol"+j).html($("#nextbidcol"+nextrow).html());
					$("#lotno"+nextrow).val("");
					$("#mybid"+nextrow).val("");
				 }
				 if(isNextRow){
					i--;
				 }else{
					$("#row"+i).remove();
				 }
			/** END of decrement */
			totalcount = totalcount - 1;
			if(i <= $("#currentid").val()) {
				totalcount = Number($("#currentid").val())-1;
				//$("#currentid").val(totalcount);
			}else{
				//$("#currentid").val(totalcount);
			}
			if(totalcount < 1) { 
				//return false;
			}
		}else if($("#lotno"+i).val() == undefined || $("#mybid"+i).val() == undefined) {
			if(totalcount > orgcount){
				totalcount = totalcount - 1;
				//$("#currentid").val(totalcount);
			}
		}
		if($("#lotno"+i).val() == "" || $("#mybid"+i).val() == ""){
			if(confirm("Row number "+i+" doesn\'t have values. Are you sure want to delete this row?")) {
				//$("#row"+i).remove();
				
				/**
				 * New way of decrementing the next rows
				 * Done on 3/13/2011
				 **/
				 for(var j = i; j <= orgcount; j++) {
					var nextrow = Number(j) + 1;
					$("#auction_id"+j).val($("#auction_id"+nextrow).val());
					$("#lotno"+j).val($("#lotno"+nextrow).val());
					$("#mybid"+j).val($("#mybid"+nextrow).val());
					$("#desccol"+j).html($("#desccol"+nextrow).html());
					$("#nextbidcol"+j).html($("#nextbidcol"+nextrow).html());
					$("#lotno"+nextrow).val("");
					$("#mybid"+nextrow).val("");
				 }
				 i--;
				 /** END of decrement */
				totalcount  = totalcount - 1;
				//$("#currentid").val(totalcount);
				//return false;
			}else{
				return false;
			}
		}else if($("#lotno"+i).val() != "" && $("#mybid"+i).val() != "" && $("#mybid"+i).val() != null && typeof($("#mybid"+i).val()) != "undefined") {
			if(!isNextRow){
				countLots = countLots + 1;
				sessionvar = sessionvar + $("#id"+i).val()+"|"+$("#mybid"+i).val()+"|"+$("#lotno"+i).val()+"|"+$("#desccol"+i).html()+"|"+$("#nextbidcol"+i).html() + "|"
				+ $("#lotdesired").val() + "|" + $("#ticketid").val() + "|" + $("#auction_id"+i).val() + "$$$";
			}
			
		}
		if($("#mybid"+i).val() != "" && $("#mybid"+i).val() != undefined && isNaN($("#mybid"+i).val())) {
			status = 2;
		}
	 }

	//Added a New Way of counting.
	$("#currentid").val(countLots);
	$("input:text").each(function() {
			if(this.value == "" && this.className == "proxy") {
				status = 1;
			}
	});
	if(status == 1) {
		alert("Please fill all the fields");
		return false;
	}
	if(status == 2) {
		alert("Bids should be Numeric");
		return false;
	}
	var validat = validateDesired();

	if(validat > 0){
	   return false;
	}

	if(type == 1){
		$("input:text").attr("readonly","readonly");
		$("input[name=\'username\']").removeAttr("readonly");
		
		$("#alertmess").attr(\'style\',\'display: ""\');
		$("#confirmticket").toggle();
		$("#sendbid").toggle();
		$("#sendbid").removeAttr("disabled");
		$("#editticket").toggle();
		$.post("index.php", {
					option: "com_bids",
					task: "checksession",
					data: sessionvar
				}, function(data) {
					if(data != \'1\') {
						alert("Not autorized to enter bids, Please login");
						return false;
					}
					//alert(data);
				}
		);
	}
    return true;
}


function validateDesired() {

if((Number($("#lotdesired").val()) > Number($("#currentid").val())) || ($("#lotdesired").val() <= 0)) {
	$("#lotdesired").val("");
	alert("# of Lots Desired must be between 1 and the total number of Proxy Choice bids on this screen.");
	$("#lotdesired").focus();
	editticket2($("#currentid").val());
	return 1;
   }
}
function red() {
	window.location.href = "index.php?option=com_bids&task=editproxyticket";
}
function validateProxyPlus(){
   if($("#lotno1").val() == "") {
     alert("Blank bids can\'t be submitted");
     return false;
   }
	totalrow = $("#currentid").val();
	   for(var i = 1; i <= totalrow; i++) {
		if($("#lotno"+i).val() == "") {
			alert("Fields can\'t be empty");
			$("#lotno"+i).focus();
			return false;
		}
	   }
   return true;
}
function delTicket(type) {
	if(type == 0) {
	  if(confirm("Are you sure you want to delete entire ticket")) {
		window.location = "index.php?option=com_bids&task=listproxyticket";
	  }
	}else {
		if(confirm("Are you sure you want to delete entire ticket")) {
			window.location = "index.php?option=com_bids&task=delproxyticket&id="+type;	
		}
	}
}
function checkbid(id){
	var nextbids = $("#nextbidcol"+id).html();
	var mybids;
	var isRun;
	var oldmybid = 0;
	var oldlotdesired = 0;
	var lotdesired;
	lotdesired = $("#lotdesired").val();
	oldlotdesired = $("#oldlotdesired").val();
	nextbids = nextbids.replace(/,/,"");
	mybids = $("#mybid"+id).val();
	oldbids = $("#oldmybid"+id).val();
	isRun = $("#run").val();
	if(mybids == "") {
		return true;
	}
	if(isRun == 1 &&  Number(oldbids) > Number(mybids)) {
		alert("You can\'t decrease the bid");
		$("#mybid"+id).val(oldbids);
		return false;
	}
	if(Number(oldbids) == Number(mybids)) {
		return false;
	}
	if(Number(mybids) < Number(nextbids)) {
		if(isRun == 1){
			alert("Bid should be greater than or equal to Next bid");
		}else{
			alert("Bid should be greater than or equal to Next bid");
			$("#mybid"+id).val("");
			$("#lotno"+id).focus();
		}
		return false;
	}
	if($("#lot"+(id+1)).val() == "" && $("#mybid"+(id+1)).val() == ""){
		$("#maxid").val(Number($("#maxid").val())+1);
	}
}
</script>
'; ?>

<table width="100%" cellpadding="0" cellspacing="0" border="0" valign="top" style="padding-top: -10px; ">
<td align="center"  width="60%">
<h2>My Proxy Choice Tickets</h2></td>
<td align="right"  width="20%"><a href="javascript:void(0);" onClick="toggle('proxyticketmessage',this, <?php echo $this->_tpl_vars['loggeduser']; ?>
)">Info and Example</a></td></tr>
</table>
<table align="center" cellpadding="0" cellspacing="0" width="100%" id="auction_list_container">
    <tr id="proxyticketmessage" <?php if ($this->_tpl_vars['infoStatusMain'] == 'less'): ?>style="display:none;"<?php endif; ?>>	
	  <td width="100%" class="proxyticketmessage" colspan="2">
		<?php echo $this->_tpl_vars['bid_proxyplus_message1']; ?>

		<br /><div id="proxyticketmessagemore" <?php if ($this->_tpl_vars['infoStatus'] == 'more'): ?>style="display:none;"<?php endif; ?>><?php echo $this->_tpl_vars['bid_proxyplus_message2']; ?>
</div>
		<br /><div><input type="button" value="<?php if ($this->_tpl_vars['infoStatus'] == 'more'): ?>More Info<?php else: ?>Less Info<?php endif; ?>" class="button art-button" id="moreinfobutton" onClick="toggle('proxyticketmessagemore',this, <?php echo $this->_tpl_vars['loggeduser']; ?>
)"> &nbsp;&nbsp;&nbsp;&nbsp; <input type="button" value="No Info" class="button art-button"  onClick="toggle('proxyticketmessage',this, <?php echo $this->_tpl_vars['loggeduser']; ?>
)"></div>
		
	  </td>
     </tr>
    <tr>
	<td colspan="2">&nbsp;</td>
    </tr>
    <?php if ($this->_tpl_vars['task'] != editproxyticket): ?>
    <tr>
	<td><input type="button" value="Refresh (F5)" id="refresh" class="button art-button"  onClick="window.location.reload();"></td><td align="right"><span style="align: right;"><input type="button" value="Enter Another Proxy Choice Ticket" class="button art-button"  id="anotherbid" name="anotherbid" onClick="red();"></span></td>
    </tr>
   <?php endif; ?>
   <?php if ($this->_tpl_vars['countdown'] != ""): ?>
    <tr id="salestartinfo"><td></td></tr>
   <?php endif; ?>
    <?php $this->assign('ticketkey', '0'); ?>
    <?php if ($this->_tpl_vars['count'] > 0 && ( $this->_tpl_vars['task'] == listproxyticket || $this->_tpl_vars['ticketid'] > 0 )): ?>
	    <?php $_from = $this->_tpl_vars['auction_rows']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['myId'] => $this->_tpl_vars['item1']):
?>
	     <?php if ($this->_tpl_vars['ticketid'] == 0 || $this->_tpl_vars['ticketid'] == $this->_tpl_vars['myId']): ?>
			<?php $this->assign('ticketkey', ($this->_tpl_vars['ticketkey']+1)); ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 't_listproxyticket_cell.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	     <?php endif; ?>
	    <?php endforeach; endif; unset($_from); ?>
	<?php elseif ($this->_tpl_vars['session']['proxy'] != ""): ?>
	     <?php if ($this->_tpl_vars['ticketid'] == 0 || $this->_tpl_vars['ticketid'] == $this->_tpl_vars['myId']): ?>
			<?php $this->assign('ticketkey', ($this->_tpl_vars['ticketkey']+1)); ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 't_listproxyticket_cell.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	     <?php endif; ?>	 
    <?php else: ?>
	  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 't_listproxyticket_cell.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endif; ?>
</table>

</form>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/js/auctions.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/js/startcounter.js"></script>
<?php echo '
<script>
$("#lotdesired").focus();
</script>
'; ?>

