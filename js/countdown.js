//var days='days,';
//var expired='Expired';

String.prototype.trim = function () {
    return this.replace(/^\s*/, "").replace(/\s*$/, "");
}
String.prototype.copyTo = function (substr) {
    return this.substring(0,this.indexOf(substr)).trim();
}
String.prototype.deleteFrom = function (substr) {
    return this.slice(this.indexOf(substr)+substr.length);
}
function toDoubleDigit(i)
{
	var s=new String(i);
	if (s.length==0) s='00'
	else
		if (s.length==1) s='0'+s;
	return s;
}
function setTimeLeft(maxtimers)
{
	//var maxtimers;
	//maxtimers = maxtimers + 0;
	var timer;
	if(maxtimers != 0) {
		maxtimers=100;
	}
	for(i=1;i<=maxtimers;i++) {
	    el=document.getElementById('time'+i);
	    if (!el) continue;
		val=el.innerHTML;
		if (val==expired) continue;
		d=0;
		h=0;
		m=0;
		s=0;
		if (val.indexOf(days)>=0){
			ds=val.copyTo(days);
			val=val.deleteFrom(days);
			if(parseInt(ds)!=NaN){
				d=parseInt(ds,10);
			}
		}
		if (val.indexOf(':')>=0){
			hs=val.copyTo(':');
			val=val.deleteFrom(':');
			if(parseInt(hs)!=NaN){
				h=parseInt(hs,10);
			}
		}
		if (val.indexOf(':')>=0){
			ms=val.copyTo(':');
			val=val.deleteFrom(':');
			if(parseInt(ms)!=NaN){
				m=parseInt(ms,10);
			}
		}
		if(parseInt(val)!=NaN){
			s=parseInt(val,10);
		}
		timedout=false;
		if (s>0){
			s--;
		}else{
			s=59;
			if(m>0){
				m--;
			}else{
				m=59;
				if(h>0){
					h--;
				}else{
					h=23;
					if(d>0){
						d--;
					}else{
						timedout=true;
					}
				}
			}
		}
		newval='';
		if(!timedout){
			if(d>0)
				newval=d+' '+days+' ';
			newval+=toDoubleDigit(h)+':'+toDoubleDigit(m)+':'+toDoubleDigit(s);
		}else{
			newval=expired;
			if(typecount == 1) {
				//alert(typecount);
				typecount = 0;
				clearTimeout(timer);
				window.location.reload();
			}
		}
		el.innerHTML=newval;

	}
    if(i > 1) {
		timer = window.setTimeout('setTimeLeft('+maxtimers+')',1000);
	}
}