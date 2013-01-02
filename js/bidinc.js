function validate() {
	var lastrow = 0;
	for(var i=1;i <=5; i++) {
		var bidinc = $("#bid_incre"+i).val();
	    bidinc = bidinc.replace(",","");
		bidinc = Number(bidinc);
		var bidto = $("#bid_range_to"+i).val();
	    bidto  = bidto.replace(",","");
		bidto = Number(bidto);

		if(bidinc == "" && bidto == "") {
			continue;
		}
		if(bidinc != 0 && !isNaN(bidinc)) {
			bidinc = Number(bidinc)+1;
		}
		else {
			bidinc = 0;
		}
		if(Number($("#bid_range_from"+i).val()) > bidto) {
			alert("Range should be corrected");
			$("#bid_range_to"+i).focus();
			return false;
		}
		if($("#bid_range_to"+i).val() >= 1000000) {
			alert("Max range should be 999,999 only");
			$("#bid_range_to"+i).focus();
			return false;
		}
		if(bidinc < 1 || bidinc >= 9999) {
			alert("Bid Increment should be between $1and $9,999 only");
			$("#bid_incre"+i).focus();
			return false;
		}
		if(bidinc != "" && bidinc > 0) {
			lastrow = i;
		}
	}
	var finalrange = $("#bid_range_to"+lastrow).val();
	finalrange = finalrange.replace(",","");

	if(finalrange != "" && finalrange != 0 && !isNaN(finalrange)) {
		finalrange = Number(finalrange);
	}
	else {
		finalrange = 0;
	}
	if(finalrange != 999999) {
			alert("Final value should be $999,999 only");
			 $("#bid_range_to"+lastrow).focus();
			return false;
	}
  return true;
}
function fillrange(id){
  var currid = id - 1;
  var bidto = $("#bid_range_to"+currid).val();
  bidto = bidto.replace(",","");
  if(bidto == "") {
	  return false;
  }
  bidto = Number(bidto)+1;
  $("#lblbid_range_from"+id).html(bidto);
  $("#bid_range_from"+id).val(bidto);
  if($("#bid_range_to"+id) == "") {
	$("#bid_range_to"+id).val("999,999");
  }
}