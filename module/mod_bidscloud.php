<?php
/**
 * @package AuctionsFactory
 * @version 1.5.0
 * @copyright www.thefactory.ro
 * @license: commercial
*/

// no direct access

	defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once

	require_once (dirname(__FILE__).DS.'helper.php');

	$max_tags   = intval( $params->get( 'max_tags', 40 ) );
	$max_word_length = 15; $min_word_length = 4;
	$max_font   = 20; $min_font   = 10;

	$tags = mod_bidscloudHelper::getTags();

  	if(count($tags)>=1){

  		foreach($tags as $tag)
			$list[] = $tag->tagname;
		$list = implode(' ',$list);

		$tag_list = preg_split("/ /", $list);
		$tag_list = array_count_values( $tag_list );

		arsort( $tag_list);

	  	$i = 1;
	  	$ordered_tag_list = array();
		foreach($tag_list as $k=>$v) {

			if ($i<=$max_tags AND strlen($k)>=$min_word_length AND strlen($k)<=$max_word_length)
			{
				$ordered_tag_list[$k] = $v;
		  		$i++;
			}
		}

        if (count($ordered_tag_list)){
    		$minimum_count = min(array_values($ordered_tag_list));
    		$maximum_count = max(array_values($ordered_tag_list));
    		$rank_font = $max_font - $min_font;
    		$rank_freq = $maximum_count - $minimum_count;
    		if($rank_freq == 0) {$rank_freq=1;}

    		mod_bidscloudHelper::shuffle_assoc ($ordered_tag_list);
        }
	}
	require(JModuleHelper::getLayoutPath('mod_bidscloud'));