/**
* modified 3.06.2009
* added: SelectTab();
*/
var alreadyrunflag=0
if (document.addEventListener)
{
	document.addEventListener("DOMContentLoaded", function(){alreadyrunflag=2; FillRatings();SelectTab();if (nrcounters) setTimeLeft(nrcounters,typecount);}, false)
}
else if (document.all && !window.opera){
	document.write('<script type="text/javascript" id="contentloadtag" defer="defer" src="javascript:void(0)"><\/script>')

	var contentloadtag=document.getElementById("contentloadtag")
	alreadyrunflag=1
	contentloadtag.onreadystatechange=function(){
		if (this.readyState=="complete"){
			alreadyrunflag=2
			FillRatings();
			SelectTab();
			if (nrcounters) setTimeLeft(nrcounters);
		}
	}
}
window.onload=function(){
	if (!alreadyrunflag && alreadyrunflag!=2) setTimeout("if (!alreadyrunflag && alreadyrunflag!=2) FillRatings();SelectTab();if (nrcounters) setTimeLeft(nrcounters);", 0)
}
