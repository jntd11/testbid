var prevLink;
var nextLink;
var itemsPerPage = 3;
var visiblePage = 0;
var items;


function paginate(selector,num) {
	items = cssQuery(selector);
	
	itemsPerPage = num;
	
	prevLink = document.getElementById('prevLink');
	prevLink.onclick=function() {
		visiblePage = Math.max(0,visiblePage-1);
		displayPage();
		return false;
	}
	nextLink = document.getElementById('nextLink');
	nextLink.onclick=function() {
		visiblePage = Math.min(Math.floor(items.length/itemsPerPage),visiblePage+1);
		displayPage();	
		return false;	
	}
	
	displayPage();
}

function displayPage() {
	//itterate through all of the items setting style.display to 'none' for 
	//items that are below visiblePage+*itemsPerPage and above 
	//[(visiblePage+1)*itemsPerPage]-1 while setting those in between to 'block'
	for(i=0;i<items.length;i++){
		items[i].style.display='block';
		if (i<visiblePage*itemsPerPage||i>((visiblePage+1)*itemsPerPage)-1) {
			items[i].style.display='none';
		}
	}
}
