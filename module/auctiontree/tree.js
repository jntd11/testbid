var stare;
	
function adauga(sir,val)
{
	
    if(sir.indexOf("."+val)==-1) return sir+"."+val; 
	                        else return sir;
}

function sterge(sir,val)
{
	valn="."+val;
	poz=sir.indexOf(valn);
	sirn=sir.substr(0,poz)+sir.substr(poz+valn.length);
	return sirn;
}

function setcookie(nume,val)
{
 document.cookie=nume+ "=" +escape(val)+"";
}

function getcookie(nume)
{
	//alert(nume);
if (document.cookie.length>0)
  {
  c_start=document.cookie.indexOf(nume + "=");
  if (c_start!=-1)
    { 
    c_start=c_start + nume.length+1; 
    c_end=document.cookie.indexOf(";",c_start);
    if (c_end==-1) c_end=document.cookie.length;
    
    return unescape(document.cookie.substring(c_start,c_end));
    } 
  }
return "";
}

function afis(id,img)
{
	//alert(id);
 document.getElementById(id).style.display="block";
 document.getElementById("pic" + id).src= img + "arrow1.gif";
}

function afis_meniu(sir,img)
{
//	alert(sir);
vect=sir.split(".");

for(i=1;i<vect.length;i++)
 {
 	//alert(vect[i]);
  afis(vect[i], img);
 }
}
	
function loadXMLDoc(root) 
{
					
	var response=null;
	var url=root + "tree_db.xml?" + Math.random(1000) ;
	var imgRoot=root + "img/"; 
	
		// code for IE
		if (window.ActiveXObject) {
			
			req=new ActiveXObject("Microsoft.XMLDOM");
			req.async=false;
			req.load(url);
			parseXML(req, imgRoot);
			  stare=getcookie("meniu");
		}
		
		// code for Mozilla, Firefox, Opera, etc.
		else if (document.implementation && document.implementation.createDocument) {
		
			req=new XMLHttpRequest();
			req.onreadystatechange = proReqChange;
			req.open("GET", url, true);
			req.send("");
			
		}
		
		else {
				alert("Your browser cannot handle this script");
				
}
		
		
		
function proReqChange() 
{
		
			if(req.readyState == 4) {
			
				response=req.responseXML;
				parseXML(response, imgRoot);
				//citesc valoarea din cookie
	            //deschid meniu coresp
	
	            stare=getcookie("meniu");
				afis_meniu(stare,imgRoot);								            			
			} 
			else { }
		
		}
		
	
}
	

function hideShow(id, imgRoot) 
{

		if(document.getElementById(id).style.display=="none") {
			document.getElementById(id).style.display="block";
			document.getElementById("pic" + id).src= imgRoot + "arrow1.gif";
			
			//adaug in vector
			stare=adauga(stare,id);
			setcookie("meniu",stare);
								
		} else { document.getElementById(id).style.display="none"; 
		         document.getElementById("pic" + id).src= imgRoot + "arrow2.gif"; 
			     
				 //scot din vector
				 stare=sterge(stare,id);
				 setcookie("meniu",stare);
				// alert(stare);
			///alert(document.cookie);	
				}
	
}
	
	
function openLink(tarlink, target) 
{
	    //incarca valoarea in cookie
		window.open(tarlink, target);
	
}
	

function parseXML(response, imgRoot) 
{
	
	var nodePre, nodenum;
		
	document.getElementById("Tree").innerHTML="";
	
	nodePre=response.getElementsByTagName("node");
	nodenum=nodePre.length;
	
	linkPre=response.getElementsByTagName("link");
	linknum=linkPre.length;
	
	
		for(i=1;i<nodenum;i++) {
		
			id=nodePre[i].getAttribute('id');
			pid=nodePre[i].parentNode.getAttribute("id");
			label=nodePre[i].getAttribute("label");
			
			var newNode=document.createElement("div");
			newNode.setAttribute("id","styFolder");
			newNode.innerHTML += "<span onclick=hideShow('" + id + "','" + imgRoot +
								 "')><img id='pic"+ id +"' src='" + imgRoot + 
			                     "002-1.gif' with='12' height='12'/>&nbsp;" + label +
								 "</span><div class='styNodeRegion' style='display:none' id='" + 
								 id + "'>";
			
			document.getElementById(pid).appendChild(newNode);
		
		}
		
		for(i=0;i<linknum;i++) {
		
			pid=linkPre[i].parentNode.getAttribute("id");
			label=linkPre[i].getAttribute("label");
			
			var newLink=document.createElement("div");
			newLink.style.cursor="pointer";
			newLink.setAttribute("id","styLink");
			/*newLink.setAttribute("onclick","openLink('" + linkPre[i].getAttribute("href") + "')");*/
			newLink.innerHTML +="<span onclick='openLink(\"" + linkPre[i].getAttribute("href") +                                "\",\"" + linkPre[i].getAttribute("target") + 																																                                "\")'><img src='"+ imgRoot +"arrow1.gif'>" + label + 
			                    "</span>";
			
			document.getElementById(pid).appendChild(newLink);
		
		}
		
		
}
function load(root){
	stare=getcookie("meniu");
	//alert(document.getElementById("styLink"));
	afis_meniu(stare,root);

	
}
