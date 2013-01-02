var vinupdate=false;
var lastvin=new String();

 function changeBIN()
 {
	var frm=document.auctionForm;
	var div=document.getElementById("BIN_price");
	if (frm.bin_OPTION.value==0){
		div.style.display='none';
	}else{
		div.style.display='block';
	}
 }

 fValidate.prototype.bin = function()
 {
	if ( this.typeMismatch( 'text' ) ) return;
	var otherElem = this.form.elements['initial_price'];
	var sel = this.form.elements['bin_OPTION'];
	if (sel.value==0){
		this.elem.validated=true;
		this.elemPass = true;
		return;
	}

	if ( this.elem.value >0)
	{
		this.number(1,0.01,9999999999);
		if (parseFloat(this.elem.value) <parseFloat(otherElem.value)){
			this.elem.validated=false;
			this.elemPass = false;
			alert(language["bid_err_bin_must_be_greater"]);

		}
	}else if( parseFloat(this.elem.value == 0)) {
			this.elem.validated=false;
			this.elemPass = false;
			alert(language["bid_auction_bin_zero"]);
	}
 }


fValidate.prototype.title = function()
{
	var title = this.form.elements['title'];

	if(!title.value){
		this.elem.validated=false;
		this.elemPass=false;
		alert(language["bid_err_title_valid"]);
	}

}

fValidate.prototype.published = function()
{
	var pub0 = this.form.elements['published0'];
	var pub1 = this.form.elements['published1'];
	if(!pub0.checked && !pub1.checked){
		this.elem.validated=false;
		this.elemPass=false;
		alert(language["bid_err_published_valid"]);
	}
}


fValidate.prototype.auction_type = function()
{
	var at = this.form.elements['auction_type'];

	if(!at.value){
		this.elem.validated=false;
		this.elemPass=false;
		alert(language["bid_err_auction_type_valid"]);
	}
}

fValidate.prototype.payment = function()
{
	var pay = this.form.elements['payment'];

	if(pay.value==0){
		this.elem.validated=false;
		this.elemPass=false;
		alert(language["bid_err_payment_valid"]);
	}
}

fValidate.prototype.start_date = function()
{
	var start_date = this.form.elements['start_date'];
	start_date.value = start_date.value.slice(0,10);
	var currentTime = new Date();
	var month = currentTime.getMonth()+1;
	var day = currentTime.getDate();
	var year = currentTime.getFullYear();
	if(day < 10) {
		day = '0'+day;
	}
	if(month < 10) {
		month = '0'+month;
	}
    var nowDate = month+'/'+day+'/'+year;


	var joomlaformat=Calendar._TT["DEF_DATE_FORMAT"];

	joomlaformat=joomlaformat.replace('mm','M');

	//'yyyy-MM-dd'
	if(!isDate(start_date.value,joomlaformat)){
		this.elem.validated=false;
		this.elemPass=false;
		alert(language["bid_err_start_date_valid"]);
	}
	if(compareDates(start_date.value,joomlaformat,nowDate,joomlaformat)==0){
		var start_hour = this.form.elements['start_hour'];
		var end_hour   = this.form.elements['end_hour'];
		if(Number(start_hour) > Number(end_hour)) {
			this.elem.validated=false;
			this.elemPass=false;
			alert(language["bid_err_start_date_valid"]);
		}
	}else if(compareDates(start_date.value,joomlaformat,nowDate,joomlaformat)<=0){
		
		this.elem.validated=false;
		this.elemPass=false;
		alert(language["bid_err_start_date_valid"]);
	}
}

fValidate.prototype.end_date = function()
 {
	var start_date = this.form.elements['start_date'];
	var end_date = this.form.elements['end_date'];
	var joomlaformat=Calendar._TT["DEF_DATE_FORMAT"];

	joomlaformat=joomlaformat.replace('mm','M');

	if(!isDate(end_date.value,joomlaformat)){
		this.elem.validated=false;
		this.elemPass=false;
		alert(language["bid_err_end_date_valid"]);
	}
	if(compareDates(end_date.value,joomlaformat,start_date.value,joomlaformat)==0){
		var start_hour = this.form.elements['start_hour'];
		var end_hour   = this.form.elements['end_hour'];
		if(Number(start_hour) > Number(end_hour)) {
			this.elem.validated=false;
			this.elemPass=false;
			alert(language["bid_err_start_date_valid"]);
		}
	}else if(compareDates(end_date.value,joomlaformat,start_date.value,joomlaformat) < 0) {

		this.elem.validated=false;
		this.elemPass=false;
		alert(language["bid_err_end_date_valid"]);
	}
	var d1= new Date(getDateFromFormat(start_date.value,joomlaformat));
	d1.setMonth(d1.getMonth() + bid_max_availability);
	var d2=getDateFromFormat(end_date.value,joomlaformat);

	if(bid_max_availability>0 && d1.getTime()<d2){
		this.elem.validated=false;
		this.elemPass=false;
		alert(language["bid_err_max_valability"]);
	}

 }

 fValidate.prototype.initial_price = function()
 {
	if(!this.elem.value){
		this.elem.validated=false;
		this.elemPass=false;
		alert(language["bid_err_initial_price_valid"]);
	}
	if(parseFloat(this.elem.value)==0){
			this.elem.validated=false;
			this.elemPass = false;
			alert(language["bid_err_initial_price_zero"]);
	}
 }

 fValidate.prototype.reserve_price = function()
 {
 	if(this.elem.value!="" && this.elem.value!="0"){
		if(!parseFloat(this.elem.value)){
			this.elem.validated=false;
			this.elemPass=false;
			alert(language["bid_err_reserve_price_valid"]);
		}
 	}
 }

 fValidate.prototype.min_increase = function()
 {
 	if(this.elem.value!="" && this.elem.value!="0"){
		if(!parseFloat(this.elem.value)){
			this.elem.validated=false;
			this.elemPass=false;
			alert(language["bid_err_min_increase_valid"]);
		}
 	}
 }

fValidate.prototype.image_file = function()
{

    if (!this.elem.value){
        this.elem.validated=false;
        this.elemPass = false;
        alert(language["bid_err_picture_is_required"]);

    }
}

