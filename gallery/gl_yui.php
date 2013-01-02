<?php
/**
 * @package AuctionsFactory
 * @version 1.5.7
 * @copyright www.thefactory.ro
 * @license: commercial
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

include_once($mosConfig_absolute_path   ."/components/com_bids/gallery/gallery.php");
class gl_yui extends auct_gallery{
	var $_pathToJS;
	var $_pathToImages;

	function gl_yui(&$db,$pathToImages)
	{
		global $mosConfig_live_site;
		$this->_db=$db;
		$this->_pathToJS=$mosConfig_live_site."/components/com_bids/gallery/yui_js";
		$this->_pathToImages=$pathToImages;
	}
	function getJS()
	{
        $width= intval(bid_opt_medium_width);
        $height= intval(bid_opt_medium_height);
		$code="
		<link rel=\"stylesheet\" type=\"text/css\" href=\"http://yui.yahooapis.com/2.6.0/build/fonts/fonts.css\">
		<link type=\"text/css\" rel=\"stylesheet\" href=\"$this->_pathToJS/yui_skin.css\"><script type=\"text/javascript\" src=\"http://yui.yahooapis.com/2.6.0/build/yahoo-dom-event/yahoo-dom-event.js\"></script>";
		//JaiStartD  
		//<script type=\"text/javascript\" src=\"http://yui.yahooapis.com/2.6.0/build/yahoo-dom-event/yahoo-dom-event.js\"></script>
		//JaiEndD
		$code .= "<script type=\"text/javascript\" src=\"http://yui.yahooapis.com/2.6.0/build/element/element-beta-min.js\"></script>
		<script type=\"text/javascript\" src=\"http://yui.yahooapis.com/2.6.0/build/carousel/carousel-beta-min.js\"></script>
		";
		if(JOOMLA_VERSION=="1")
		$code.="
		<script type=\"text/javascript\">
		if(typeof window.jQuery == 'undefined') {
		  document.writeln('<scr'+'ipt type=\"text/javascript\" src=\"$this->_pathToJS/../js/jquery.js\"></scr'+'ipt>');
		}
        if(typeof window.jQuery != 'undefined') {
            jQuery.noConflict();
        }
		</script>
		<script type=\"text/javascript\" src=\"$this->_pathToJS/../js/thickbox/thickbox.js\"></script>
		<link rel=\"stylesheet\" href=\"$this->_pathToJS/../js/thickbox/thickbox.css\" type=\"text/css\" media=\"screen\" />
		";
		return $code;
	}
	function writeJS()
	{
		echo $this->getJS();
	}

	function getThumb($thumbnr=0,$overlib=1)
	{
		if($this->imagelist[$thumbnr]){
		   if ($overlib){
    	       $img_middle="<img src=\"".$this->_pathToImages.'/middle_'.$this->imagelist[$thumbnr]."\" border=0>";
		       $img ='<img src="'.$this->_pathToImages.'/resize_'.$this->imagelist[$thumbnr].'" border="0" onmouseover="overlib(\''.htmlspecialchars($img_middle).'\',WIDTH,100);" onmouseout="nd();">';
		   }else{
    	       $img_full=$this->_pathToImages.'/'.$this->imagelist[$thumbnr];
    	       $img='<a href="'.$img_full.'" class="thickbox">';
		       $img.='<img src="'.$this->_pathToImages.'/resize_'.$this->imagelist[$thumbnr].'" border="0" />';
		       $img.='</a>';
		   }
		} else {
			$img='<img src="'.$this->_pathToImages.'/no_image.png" border="0">';
		}
        return $img;
	}
	
	function writeThumb($thumbnr=0,$overlib=1)
	{
	    echo $this->getThumb($thumbnr,$overlib);
	}

	function getGallery($resize=0,$detaPage=0)
	{
		if(JOOMLA_VERSION=="5")
			JHTML::_('behavior.modal');
		$img = "";
        $width= intval(bid_opt_medium_width);
	    if (count($this->imagelist)>0){
	    	$nr=0;
	        for($i=0;$i<count($this->imagelist);$i++){
	        	if ($this->imagelist[$i]=='') continue;
	        	$nr++;
	        }
	        /* JaiStartD */
	        $img .="
	        <script>
	    	(function () {
		        var carousel;
		                
		        YAHOO.util.Event.onDOMReady(function (ev) {
		            var carousel    = new YAHOO.widget.Carousel(\"container\", {
		                         isCircular: true, numVisible: 1
		                });
		            carousel.render(); // get ready for rendering the widget
		            carousel.show();   // display the widget
		        });
		    })();
		    </script>
			";

	        $img .='
			<div class="yui-skin-sam">	        
	        <div id="container">
			<div id="carousel">
			';
	        for($i=0;$i<count($this->imagelist);$i++){
	            if ($this->imagelist[$i]=='') continue;
	            
	            if(JOOMLA_VERSION=="1")
	            	$css_class = "thickbox";
	            else
	            	$css_class = "modal";
				if(!$detaPage){
						if($resize == 1) {
							$img.='
							<div>
								<a href="'.$this->_pathToImages.'/'.$this->imagelist[$i].'" class="'.$css_class.'" ><img src="'.$this->_pathToImages.'/resize_'.$this->imagelist[$i].'"></a>
							</div><div>&nbsp;</div>';
						}elseif($resize==2){
							$img.='
							<div>
								<img src="'.$this->_pathToImages.'/resize_'.$this->imagelist[$i].'">
							</div><div>&nbsp;</div>';

						}else {
							$img.='
							<div>
								<a href="'.$this->_pathToImages.'/'.$this->imagelist[$i].'" class="'.$css_class.'" ><img src="'.$this->_pathToImages.'/middle_'.$this->imagelist[$i].'"></a>
							</div><div>&nbsp;</div>';
						}
				}else{
						if($resize == 1) {
							//resize_
							$img.='
							
								<img src="'.$this->_pathToImages.'/'.$this->imagelist[$i].'">
							<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>';
						}else {
							//middle_
							$img.='
							
								<img src="'.$this->_pathToImages.'/'.$this->imagelist[$i].'">
							<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>';
						}
				}
	        }
            $img.='
				</div>
			</div>
			</div>
	        ';
			/* JaiEndD */
	    }else
	    {
	        $img='<img src="'.$this->_pathToImages.'/no_image.png" border="0">';
	    }
		return $img;
	}
	function writeGallery(){
	    echo $this->getGallery();

	}
}



?>