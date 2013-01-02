<?php
/**
 * @package AuctionsFactory
 * @version 1.5.0
 * @copyright www.thefactory.ro
 * @license: commercial
*/
defined('_JEXEC') or die('Restricted access');

class mod_bidscloudHelper
{
	function getTags()
	{
		$db		=& JFactory::getDBO();
		$result	= null;
		$query = "SELECT `tagname` , `auction_id`  FROM #__bid_tags"; 
		
		$db->setQuery($query);
		$result = $db->loadObjectList();
		
		return $result;	  
	} 

	function shuffle_assoc(&$array) {
		if (count($array)>1) {
		$keys = array_rand($array, count($array));
		
		foreach($keys as $key)
		$new[$key] = $array[$key];
		
		$array = $new;
		}
		return true;
	}
}

?>
