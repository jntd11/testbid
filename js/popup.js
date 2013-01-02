/***************************/
//@Author: Adrian "yEnS" Mato Gondelle
//@website: www.yensdesign.com
//@email: yensamg@gmail.com
//@license: Feel free to use it, but keep this credits please!					
/***************************/

//SETTING UP OUR POPUP
//0 means disabled; 1 means enabled;
var popupStatus = 0;

//loading popup with jQuery magic!
function loadPopup(menus){
	//loads popup only if it is disabled
	if(popupStatus==0){
		$("#backgroundPopup").css({
			"opacity": "0.7"
		});
		$("#backgroundPopup").fadeIn("slow");
		$("#popupContact").fadeIn("slow");
		popupStatus = 1;
		$('#popupcancel').bind('click', function() {
			cancelProxy(menus);
		});
		$('#popupsavelater').bind('click', function() {
			saveProxyMove(menus);
		});
		$('#popupcontinue').bind('click', function() {
			if($("#sendbid").is(":visible")) {
				//$("#sendbid").click();
			}else{
				$("#confirmticket").click();
			}
			disablePopup();
		});
		
	}
}

function cancelProxy(menus){
		$.post("index.php", {option: "com_bids", task: "clearsession"}, function(data) {
				window.location.href = menus.href;
		});
}
/** Save function to save existing proxies while moving away*/
function saveProxyMove(menus){
	 var totalrow = Number($("#maxid").val()) + 15;
	 var countLots = 0;
	 var sessionvar = "";
	 for(var i = 1; i <= totalrow; i++) {
		if($("#lotno"+i).val() != "" && $("#mybid"+i).val() != "" && $("#mybid"+i).val() != null && typeof($("#mybid"+i).val()) != "undefined") {
				countLots = countLots + 1;
				sessionvar = sessionvar + $("#id"+i).val()+"|"+$("#mybid"+i).val()+"|"+$("#lotno"+i).val()+"|"+$("#desccol"+i).html()+"|"+$("#nextbidcol"+i).html() + "|"
				+ $("#lotdesired").val() + "|" + $("#ticketid").val() + "|" + $("#auction_id"+i).val() + "$$$";
		}
	 }
	 $.post("index.php", {
					option: "com_bids",
					task: "checksession",
					data: sessionvar
				}, function(data) {
					if(data != '1') {
						alert("Not autorized to enter bids, Please login");
						return false;
					}
					window.location.href = menus.href;
				}
	);
}
//disabling popup with jQuery magic!
function disablePopup(){
	//disables popup only if it is enabled
	if(popupStatus==1){
		$("#backgroundPopup").fadeOut("slow");
		$("#popupContact").fadeOut("slow");
		popupStatus = 0;
	}
}

//centering popup
function centerPopup(){
	//request data for centering
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	var popupHeight = $("#popupContact").height();
	var popupWidth = $("#popupContact").width();
	//centering
	$("#popupContact").css({
		"position": "absolute",
		"top": windowHeight/4-popupHeight/4,
		"left": windowWidth/4-popupWidth/4
	});
	//only need force for IE6
	
	$("#backgroundPopup").css({
		"height": windowHeight
	});
}


//CONTROLLING EVENTS IN jQuery
$(document).ready(function(){
	
	//LOADING POPUP
	//Click the button event!
	//$("#sendbid").click(function(){
		//centering with css
	//	centerPopup();
		//load popup
	//	loadPopup();
	//});
				
	//CLOSING POPUP
	//Click the x event!
	$("#popupContactClose").click(function(){
		disablePopup();
	});
	//Click out event!
	$("#backgroundPopup").click(function(){
		disablePopup();
	});
	//Press Escape event!
	$(document).keypress(function(e){
		if(e.keyCode==27 && popupStatus==1){
			disablePopup();
		}
	});

});
function confirmpopup(menus)
{
	var totalcount = Number($("#currentid").val()) + 1;
	var maxid = Number($("#maxid").val());
	if(Number(totalcount) <= 1 &&  Number(maxid) < 1){
		return true;
	}
	if($("#anotherbid").val() != undefined) {
		return true;
	}
	if(($("#lotdesired").val() == "")) {
		return true;
	}

	centerPopup();
	loadPopup(menus);
	return false;
};

