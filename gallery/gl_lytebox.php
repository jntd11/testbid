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
class gl_lytebox extends auct_gallery{
	var $_pathToJS;
	var $_pathToImages;

	function gl_lytebox(&$db,$pathToImages)
	{
		global $mosConfig_live_site;
		$this->_db=$db;
		$this->_pathToJS=$mosConfig_live_site."/components/com_bids/gallery/js";
		$this->_pathToImages=$pathToImages;
	}
	function getJS()
	{
        $width= intval(bid_opt_medium_width);
        $height= intval(bid_opt_medium_height);
		$code="
		<script type=\"text/javascript\">
		if(typeof window.jQuery == 'undefined') {
		  document.writeln('<scr'+'ipt type=\"text/javascript\" src=\"$this->_pathToJS/jquery.js\"></scr'+'ipt>');
		}
        if(typeof window.jQuery != 'undefined') {
            jQuery.noConflict();
        }
		</script>
		<script type=\"text/javascript\" src=\"$this->_pathToJS/jquery.jcarousel.js\"></script>
		<script type=\"text/javascript\" src=\"$this->_pathToJS/thickbox/thickbox.js\"></script>
		<link rel=\"stylesheet\" href=\"$this->_pathToJS/jquery.jcarousel.css\" type=\"text/css\" media=\"screen\" />
		<link rel=\"stylesheet\" href=\"$this->_pathToJS/thickbox/thickbox.css\" type=\"text/css\" media=\"screen\" />
		<link rel=\"stylesheet\" href=\"$this->_pathToJS/skin.css\" type=\"text/css\" media=\"screen\" />
		<style type=\"text/css\">
            .jcarousel-skin-tango.jcarousel-container-horizontal {
                width: ".($width)."px;
                padding: 20px 40px;

            }
            .jcarousel-skin-tango .jcarousel-clip-horizontal {
                width:  ".($width)."px;
                height: ".($height)."px;
            }
            .jcarousel-skin-tango .jcarousel-item {
                width: ".($width)."px;
                height: ".($height)."px;
            }
		</style>
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
		       $img='<img src="'.$this->_pathToImages.'/resize_'.$this->imagelist[$thumbnr].'" border="0" onmouseover="overlib(\''.htmlspecialchars($img_middle).'\',WIDTH,100);" onmouseout="nd();">';
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

	function getGallery()
	{
        $width= intval(bid_opt_medium_width);
	    if (count($this->imagelist)>0){

	    	$nr=0;
	        for($i=0;$i<count($this->imagelist);$i++){
	        	if ($this->imagelist[$i]=='') continue;
	        	$nr++;
	        }

	        $img.='
	           <script type="text/javascript">
                jQuery(document).ready(function() {
                    jQuery(\'#mycarousel\').jcarousel({
                        size: '.$nr.',
                        scroll: 1

                    });
                });
                </script>
	        ';
	        $img.='<ul id="mycarousel" class="jcarousel-skin-tango" >';
	        for($i=0;$i<count($this->imagelist);$i++){
	            if ($this->imagelist[$i]=='') continue;
	            $img.='<li style="background-image:url(); list-style:none;width:'.($width).'px">'
	            	 .'<a href="'.$this->_pathToImages.'/'.$this->imagelist[$i].'" class="thickbox">'
	            	 .'<img src="'.$this->_pathToImages.'/middle_'.$this->imagelist[$i].'" alt="" border=0 >'
	            	 .'</a>'
	            	 .'</li>'."\n";
	        }
            $img.='
                        </ul>

	        ';

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