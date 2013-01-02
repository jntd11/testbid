function ValidateBidPrice(bid_amount,mylastbid,maxbid,minincrease,proxy,initial_price){
   if (parseFloat(bid_amount.value)<=0 || isNaN(parseFloat(bid_amount.value))){
	   alert(language["bid_err_empty_bid"]);
	   return false;

   }
	if(parseFloat(maxbid.value)>0){
		accepted_price = parseFloat(maxbid.value) + parseFloat(minincrease.value);
		if(accepted_price>parseFloat(bid_amount.value)){
			alert(language["bid_err_increase"]+'  $' + accepted_price + '. ' + language["bid_err_increase_comma"]);
			return false;
		}
	}
	
	
	if(parseFloat(mylastbid.value)>0){
		accepted_price = parseFloat(mylastbid.value) + parseFloat(minincrease.value);
		if(accepted_price>parseFloat(bid_amount.value)){
			alert(language["bid_err_must_be_greater_mybid"]+' ' + accepted_price + ' ' + auction_currency);
			return false;
		}
	}
	if(parseFloat(initial_price.value)>0){
		accepted_price = parseFloat(initial_price.value);
		if(accepted_price>parseFloat(bid_amount.value)){
			alert(language["bid_err_must_be_greater"]+'  $' + accepted_price + ' ' +  language["bid_err_increase_comma"]);
			return false;
		}
	}
	return true;

}
function ValidateBidPrice1(bid_amount,mylastbid,maxbid,minincrease,proxy,initial_price)
{
   if (parseFloat(bid_amount)<=0 || isNaN(parseFloat(bid_amount))){
	   alert(language["bid_err_empty_bid"]);
	   return false;

   }
	if(parseFloat(maxbid)>0){
		accepted_price = parseFloat(maxbid) + parseFloat(minincrease);
		if(accepted_price>parseFloat(bid_amount)){
			alert(language["bid_err_increase"]+'  $' + auction_currency+ accepted_price + '. ' + language["bid_err_increase_comma"]);
			return false;
		}
	}
	if(parseFloat(mylastbid)>0){
		accepted_price = parseFloat(mylastbid) + parseFloat(minincrease);
		if(accepted_price>parseFloat(bid_amount)){
			alert(language["bid_err_must_be_greater_mybid"]+' ' + accepted_price + ' ' + auction_currency);
			return false;
		}
	}
	if(parseFloat(initial_price)>0){
		accepted_price = parseFloat(initial_price);
		if(accepted_price>parseFloat(bid_amount)){
				alert(language["bid_err_must_be_greater"]+'  $' + accepted_price + ' ' +  language["bid_err_increase_comma"]);
			return false;
		}
	}
	return true;

}

function FormValidate(form)
{
	
	var initial_price = form.elements['initial_price'];
	//var terms =  form.elements['agreement'];
	var bin = form.elements['bin_price'];
	var mylastbid =  form.elements['mylastbid'];
	var minincrease =  form.elements['min_increase'];
	var maxbid = form.elements['maxbid'];
	var proxy = form.elements['prxo'];
	var bid_amount= form.elements['amount'];
	var currentDate = new Date();
	var currentDate1 = currentDate.getTime();
	var currentDate2  = Date.parse(jQuery("#servertime").val());
	var startDate = Date.parse(jQuery("#startdate").html());
	var auctitlelabel = jQuery('#auctitlelabel').html();
	var auctitle = jQuery('#auctitle').html();
	var aucdesc = jQuery('#shortdesc').html();
	var auclabeldesc = jQuery('#labelshortdesc').html();
	var amountProxyPlus = jQuery("#maxproxyplus").val();
	if(jQuery("#islogged").val() == 0 && jQuery("#prxo").val() != 1)  {
		alert("Please Login to Bid");
		return false;
	}

	
	currentDate1 = currentDate1 + (currentDate.getTimezoneOffset() * 60 * 1000)+(language["time_offset"] * 60 * 60 *  1000);
	currentDate.setTime(currentDate1);
	if(Number(bid_amount) < 0) {
		alert("Bid Amount can't be empty or lessthan 0");
		return false;
	}
	/*alert(Number(bid_amount));
	alert(Number(amountProxyPlus));
	alert(Number(amountProxyPlus));
	alert(jQuery("#prxo").val());
	alert(jQuery("#outbid").val());
	*/
	if((Number(bid_amount) < Number(amountProxyPlus) || Number(amountProxyPlus) != 0) && jQuery("#prxo").val() != 0 && jQuery("#outbid").val() != 1) {
		alert("Proxy Choice Bid already exists for this Lot#.  You must now use manual bidding for this Lot#.");
		return false;
	}

	if(Number(currentDate2) < Number(startDate) && jQuery("#prxo").val() == 0) {
		alert("Manual bids may not be entered before auction start time but you may enter a proxy bid.");
		return false;
	}


// verify if Terms and conditions are checked
	
	//TODO Jai Commented
	//JaiSTARTG
	//if( must_accept_term && !terms.checked){
	//	alert(language["bid_err_terms"]);
	//	return false;
	//}
//the bid must be greater than mylastbid and greater then lastbid
	if(!ValidateBidPrice(bid_amount,mylastbid,maxbid,minincrease,proxy,initial_price)){
		return false;
	}
	if (parseFloat(bin.value)>0){
		if( parseFloat(bid_amount.value) >= parseFloat(bin.value)) {
			//greater then BIN - Warn
			var answer = confirm(language["bid_bid_greater_than_bin"]);
			if(answer == 0) return false;
		}
	}
	var txtConfirm =  auctitlelabel+ " = " +auctitle+"\r\n\r\n";
	txtConfirm = txtConfirm + auclabeldesc + " = "+aucdesc+"\r\n\r\n";
	txtConfirm = txtConfirm + "My Bid = "+bid_amount.value+"\r\n\r\n";
	txtConfirm = txtConfirm + "Do you want to send bid?";
	txtConfirm = txtConfirm.replace(/<b>/i,"");
	txtConfirm = txtConfirm.replace(/<\/b>/i,"");

	var txtConfirm1 = "You are bidding against yourself. Do you want to send bid?";
	if(jQuery("#prxo").val() == 0) {
		var ans = confirm(txtConfirm);
		if(ans){
			if(jQuery('#leadbid').val() == 1) {
			 var ans1 = confirm(txtConfirm1);
				if(ans1){
					return true;
				}else {
					return false;
				}
			}
		  return true;
		}else {
		  return false;
		}
	}else{
		return true;
	}
}

function FormValidate2(id)
{
	var initial_price = jQuery("#initial_price"+id).val();
	//var terms =  form.elements['agreement'];
	var bin = jQuery("#bin_price"+id).val();
	var mylastbid =  jQuery("#mylastbid"+id).val();
	var minincrease =  jQuery("#min_increase"+id).val();
	var maxbid = jQuery("#maxbid"+id).val();
	var proxy = jQuery("#prxo"+id).val();
	var bid_amount= jQuery("#amount"+id).val();
	var currentDate = new Date();
	var currentDate1 = currentDate.getTime();
	var startDate = Date.parse(jQuery("#start_date"+id).val());
	var currentDate2  = Date.parse(jQuery("#servertime").val());
	var amountProxyPlus = jQuery("#maxproxyplus"+id).val();
	var auctitlelabel = jQuery('#auctitlelabel'+id).html();
	var auctitle = jQuery('#auctitle'+id).html();
	var aucdesc = jQuery('#shortdesc'+id).html();
	var auclabeldesc = jQuery('#labelshortdesc'+id).html();
	if(jQuery("#islogged").val() == 0 && proxy != 1)  {
		alert("Please Login to Bid");
		return false;
	}

	
	jQuery("#amount").val(bid_amount);
	//alert(jQuery("#prxo"+id).val());
	currentDate1 = currentDate1 + (currentDate.getTimezoneOffset() * 60 * 1000)+(language["time_offset"] * 60 * 60 *  1000);
	currentDate.setTime(currentDate1);
	//alert(currentDate1);
	//alert(startDate);
	if(Number(bid_amount) < 0) {
		alert("Bid Amount can't be empty or lessthan 0");
		return false;
	}
	/*
	alert(Number(bid_amount));
	alert(Number(amountProxyPlus));
	alert(Number(amountProxyPlus));
	alert(jQuery("#prxo"+id).val());
	alert(jQuery("#outbid"+id).val());
	*/
	if((Number(bid_amount) < Number(amountProxyPlus) || Number(amountProxyPlus) != 0) && jQuery("#prxo"+id).val() != 0 && jQuery("#outbid"+id).val() != 1) {
		alert("Proxy Choice Bid already exists for this Lot#.  You must now use manual bidding for this Lot#.");
		return false;
	}
	if(Number(currentDate2) < Number(startDate) && jQuery("#prxo"+id).val() == 0) {
		alert("Manual bids may not be entered before auction start time but you may enter a proxy bid.");
		return false;
	}
	

// verify if Terms and conditions are checked
	
	//TODO Jai Commented
	//JaiSTARTG
	//if( must_accept_term && !terms.checked){
	//	alert(language["bid_err_terms"]);
	//	return false;
	//}
//the bid must be greater than mylastbid and greater then lastbid
	if(!ValidateBidPrice1(bid_amount,mylastbid,maxbid,minincrease,proxy,initial_price)){
		return false;
	}
	if (parseFloat(bin.value)>0){
		if( parseFloat(bid_amount.value) >= parseFloat(bin.value)) {
			//greater then BIN - Warn
			var answer = confirm(language["bid_bid_greater_than_bin"]);
			if(answer == 0) return false;
		}
	}
	auctitlelabel = auctitlelabel.replace(new RegExp('('+ auctitlelabel + ')(?![^<]*>|[^<]*</a>)', "g"), auctitlelabel);
	auctitle = auctitle.replace(new RegExp('('+ auctitle + ')(?![^<]*>|[^<]*</a>)', "g"), auctitle);
	auclabeldesc = auclabeldesc.replace(new RegExp('('+ auclabeldesc + ')(?![^<]*>|[^<]*</a>)', "g"), auclabeldesc);
	aucdesc = aucdesc.replace(new RegExp('('+ aucdesc + ')(?![^<]*>|[^<]*</a>)', "g"), aucdesc);

	txtConfirm = auctitlelabel + ": " +auctitle+"\r\n\r\n";
	txtConfirm = txtConfirm + auclabeldesc + " = "+aucdesc+"\r\n\r\n";
	txtConfirm = txtConfirm + "My Bid = "+bid_amount+"\r\n\r\n";
	txtConfirm = txtConfirm +  "Do you want to send bid?";
	txtConfirm = txtConfirm.replace(/<b>/i,"");
	txtConfirm = txtConfirm.replace(/<\/b>/i,"");
	var txtConfirm1 = "You are bidding against yourself. Do you want to send bid?";
			

	if(proxy == 0){
		var ans = confirm(txtConfirm);
		if(ans){
  		  if(jQuery('#leadbid'+id).val() == 1) {
			 var ans1 = confirm(txtConfirm1);
				if(ans1){
					return true;
				}else {
					return false;
				}
		   }
		  return true;
		}else {
		  return false;
		}
	}else {
		return true;
	}
}

function SelectTab()
{

    if (document.location.hash=='#messages' && tabPane1.pages.length>1){
         tabPane1.setSelectedIndex(tabPane1.pages.length-1);
    }
    if (document.location.hash=='#bid' && tabPane1.pages.length>2){
         tabPane1.setSelectedIndex(1);
    }
    if (document.location.hash=='#bid_list' && tabPane1.pages.length>2){
         tabPane1.setSelectedIndex(tabPane1.pages.length-2);
    }
}
function MakeBinBid(url)
{
    if (!confirm(language["bin_js_alert"])) return false;
    window.location=url;
}
function SendMessage(link,message_id,bidder_id,username)
{
    if (!bidder_id) bidder_id=0;
    if (!message_id) message_id=0;

    if (link) link.style.display='none';

    document.getElementById('bidder_id').value=bidder_id;
    document.getElementById('idmsg').value=message_id;
    if (tabPane1.pages.length>1){
         tabPane1.setSelectedIndex(tabPane1.pages.length-1);
    }
    document.getElementById('msg').style.display='block';
    document.getElementById('message_to').innerHTML=username;
    document.getElementById('message').focus();
}
function ProxyClick(checkbox)
{

    if(checkbox.checked) {
            document.getElementById('bid').innerHTML=language["bid_maxpp"];
            document.getElementById('prxo').value=1;
    } else{
         document.getElementById('prxo').value=0;
         document.getElementById('bid').innerHTML=language["bid_my_bids"];
    }
}
function ProxyClick1(checkbox,id)
{

    if(checkbox.checked) {
            document.getElementById('bid'+id).innerHTML=language["bid_maxpp"];
            document.getElementById('prxo'+id).value=1;
    } else{
         document.getElementById('prxo'+id).value=0;
         document.getElementById('bid'+id).innerHTML=language["bid_my_bids"];
    }
}
function infomessagechange(a){
	jQuery('#msgspan').html('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
	jQuery('#messageinfo').html('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
	if(a == 1){
		document.getElementsByName('amount')[0].focus(); 
	}
}
function showNextRow(id) {

	if(jQuery("#lotno"+id).val() == "") {
		return true;
	}

	if(jQuery("#proxyplustype").val() != "edit") {
		var nextid = Number(id) + 4 + 1;
	}else{
		var nextid = Number(id) + 4 + 1;
	}

	/*
	if(jQuery("#type").val() != 2) {
		jQuery("#currentid").val(id);
	}else{
		jQuery("#currentid").val(id);
	}
	*/
	 
	//TEST
	
	if(Number(jQuery("#currentid").val()) < id) {
		jQuery("#currentid").val(id);
	 }
	if(typeof(jQuery("#id"+nextid).val()) == "undefined" && Number(jQuery("#maxid").val()) < Number(nextid)) {
		if(jQuery("#row"+nextid).html() == null) {
				//commented on 2/20/2011 - to avoid multiple rows
				//jQuery("#row"+id).after('<tr id="row'+(nextid)+'" style="display: none;"></tr>');
				jQuery("#row"+(nextid-1)).after('<tr id="row'+(nextid)+'" style="display: none;"></tr>');
		}
		jQuery("#row"+nextid).attr("style","display: ''");
		jQuery("#row"+nextid).attr("class","auction_row1");
		jQuery("#row"+nextid).html('<td width="20%" class="styleborder" id="prioritycol'+nextid+'">'+nextid+'<input type="hidden" name="id'+nextid+'" id="id'+nextid+'" value="'+nextid+'"></td><td class="styleborder" width="20%" id="lotcol'+nextid+'"><input class="proxy"  type="text" name="lotno'+nextid+'" id="lotno'+nextid+'" onBlur="showNextRow('+nextid+');" onMouseDown="showNextRow('+nextid+');"><input type="hidden" name="auction_id'+nextid+'" id="auction_id'+nextid+'"></td><td class="styleborder" width="20%" id="mybidcol'+nextid+'"><input class="proxy" type="text" name="mybid'+nextid+'" id="mybid'+nextid+'" onkeypress="return onlyNumbers(event);" onBlur="checkbid('+nextid+')";></td><td class="styleborder" width="20%" id="desccol'+nextid+'"></td><td class="styleborder" width="20%" id="nextbidcol'+nextid+'"></td>');
		jQuery("#row"+nextid).after('<tr id="row'+(nextid+1)+'" style="display: none;" class="auction_row1"></tr>');
       }
       totalrow = jQuery("#currentid").val();
       for(var i = 1; i <= totalrow; i++) {
			  if(jQuery("#lotno"+i).val() == jQuery("#lotno"+id).val() && i != id && jQuery("#lotno"+i).val() != "") {
					alert("Proxy bid already exists for Lot#");
					jQuery("#lotno"+id).val("");
					jQuery("#lotno"+id).focus();
					return true;
			  }
       }
       if(jQuery("#lotno"+id).val() != "" && jQuery("#lotno"+id).val() != jQuery("#oldlotno"+id).val()) {
			jQuery.post("index.php", {
						option: "com_bids",
						task: "checkbb",
						type: 2,
						tid: jQuery("#ticketid").val(),
						lot: jQuery("#lotno"+id).val()
				}, function(datas) {
					var data1;
					var bidprice;
					var new1;
					var value1;
					bidprice = "";
					
					if(datas == 1) {
					     alert("Proxy bid already exists for Lot#");
					     jQuery("#lotno"+id).val("");
					     jQuery("#lotno"+id).focus()
					     return false;
					 }
					
					if(datas != 0) {
						datas = eval("("+datas+")");
						for (data1 in datas) {
							 var new1 = datas[data1];
							//alert(value1);
							jQuery("#desccol"+id).html(new1['shortdesc']);
							jQuery("#nextbidcol"+id).html(new1['bid_next']);
							jQuery("#auction_id"+id).val(new1['auction_id']);
							jQuery("#bid_price"+id).val(new1['bid_price']);
							if(typeof(new1['bid_price']) != "undefined") {
								bidprice = new1['bid_price'];
							}
						}
						lotdesired = jQuery("#lotdesired").val();
						if((bidprice != 0 && bidprice != "" && typeof(bidprice) != "undefined")) {
							 bidprice = bidprice.replace(",","");
						}
						if(jQuery("#id"+id).val() > lotdesired && lotdesired != "") {
							jQuery.post("index.php", {
									option: "com_bids",
									task: "checkManualLot",
									type: 1,
									lot: jQuery("#auction_id"+id).val()
							}, function(datas1) {
								if(bidprice == 0) {
									return false;
								}
								//Added false on 9/2/2011 - to accept manual bids proxy
								if(datas1 == bidprice && false) {
									alert("Since you currently hold the bid on Lot "+jQuery("#lotno"+id).val()+" and it is not your Priority lot of "+lotdesired+" lots desired, Lot "+jQuery("#lotno"+id).val()+" cannot be part of this Proxy Choice ticket.â€� You may wait until you have been outbid on Lot "+jQuery("#lotno"+id).val()+" to setup this ProxyChoice ticket or you may remove Lot "+jQuery("#lotno"+id).val()+" lot from this ProxyChoice ticket. If you choose to remove "+jQuery("#lotno"+id).val()+" and then submit this ticket, please remember you will still hold the bid on Lot "+jQuery("#lotno"+id).val()+".");
									jQuery("#desccol"+id).html("");
									jQuery("#nextbidcol"+id).html("");
									jQuery("#auction_id"+id).val("");
									jQuery("#lotno"+id).val("");
									jQuery("#lotno"+id).focus()
									return false;
								}
							});
						}else{
							if(lotdesired == "") {
								alert("Enter the lot desired");
								jQuery("#lotno"+id).val("");
								jQuery("#lotno"+id).focus()
								return false;
							}
						}
					}
					if(datas == 0) {
   						alert("Invalid Lot #");
						jQuery("#desccol"+id).html("");
						jQuery("#nextbidcol"+id).html("");
						jQuery("#auction_id"+id).val("");
						jQuery("#lotno"+id).val("");
						jQuery("#lotno"+id).focus();
						return false;
					}
				}); 
	}
}
function onlyNumbers(event){
    var e = event; // for trans-browser compatibility
	var charCode = e.which || e.keyCode;
	if(charCode == 46 || charCode == 37) 
		return false;	
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;

}
