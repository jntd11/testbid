<?php
/**
 * @package AuctionsFactory
 * @version 1.5.7
 * @copyright www.thefactory.ro
 * @license: commercial
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

class auct_gallery{
	/*@var $_db DBTable */
	var $_db=null;
	var $imagelist=array();
	var $imagelist_ids=array();
	function getGalleryForAuction($auction)
	{
	/*@var $auction mosBidOffers; */
        if (class_exists('JParameter')){
   			$pars=new JParameter($auction->params);
        }else{
            $pars=new mosParameters($auction->params);
        }

		if($auction->picture && $pars->get('picture','1')==1){
		  $this->addImage($auction->picture);
		}else {
		    ;//$this->addImage('');
		}
		if($pars->get('add_picture','1')==1){
			$sql = "select * from #__bid_pictures a where id_offer='$auction->id' ";
			$this->_db->setQuery($sql);
			$pictures = $this->_db->loadObjectList();

			for($i=0;$i<count($pictures);$i++){
			  $this->addImage($pictures[$i]->picture,$pictures[$i]->id);
			}
		}


	}
	function addImage($imagename,$id=-1)
	{
		$this->imagelist[]=$imagename;
		$this->imagelist_ids[]=$id;

	}
	function clearImages()
	{
		$this->imagelist=array();
		$this->imagelist_ids=array();

	}

}

?>