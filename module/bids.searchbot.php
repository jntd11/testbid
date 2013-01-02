<?php
// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

$_MAMBOTS->registerFunction( 'onSearch', 'botSearchAuctionFactory' );
function botSearchAuctionFactory( $text, $phrase='', $ordering='' ) {
	global $database, $my, $_MAMBOTS;

    if (class_exists('JConfig')){
        define('JOOMLA_VERSION',5);
    }else{
        define('JOOMLA_VERSION',1);
    }

	// check if param query has previously been processed
	if ( !isset($_MAMBOTS->_search_mambot_params['bids']) ) {
		if(JOOMLA_VERSION=="1"){
		// load mambot params info
		$query = "SELECT params"
		. "\n FROM #__mambots"
		. "\n WHERE element = 'bids.searchbot'"
		. "\n AND folder = 'search'"
		;
		$database->setQuery( $query );
		$database->loadObject($mambot);
		}else{
 		$mambot	=& JPluginHelper::getPlugin('search', 'bids.searchbot');
		}

		// save query to class variable
		$_MAMBOTS->_search_mambot_params['bids'] = $mambot;
	}

	// pull query data from class variable
	$mambot = $_MAMBOTS->_search_mambot_params['bids'];

	$botParams = new mosParameters( $mambot->params );

	$limit = $botParams->def( 'search_limit', 50 );
    $search_in_desc=$botParams->def( 'search_in_description', 2 );
	$text = trim( strtolower($text) );
	if ($text == '') return array();


	$wheres = array();
	switch ($phrase) {
		case 'exact':
			$wheres2 = array();
			$wheres2[] = "LOWER(title) LIKE '%$text%'";
			$wheres2[] = "LOWER(shortdescription) LIKE '%$text%'";
            if ($search_in_desc) $wheres2[] = "LOWER(description) LIKE '%$text%'";
			$where = '(' . implode( ') OR (', $wheres2 ) . ')';
			break;

		case 'all':
		case 'any':
		default:
			$words = explode( ' ', $text );
			$wheres = array();
			foreach ($words as $word) {
				$wheres2 = array();
		  		$wheres2[] = "LOWER(title) LIKE '%$word%'";
				$wheres2[] = "LOWER(shortdescription) LIKE '%$word%'";
                if ($search_in_desc) $wheres2[] = "LOWER(description) LIKE '%$word%'";
				$wheres[] = implode( ' OR ', $wheres2 );
			}
			$where = '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ')';
			break;
	}


	switch ( $ordering ) {
		case 'alpha':
			$order = 'title ASC';
			break;

		case 'category':
			$order = 'catname ASC, title ASC';
			break;

		case 'oldest':
			$order = 'start_date ASC, title ASC';
			break;
		case 'newest':
			$order = 'start_date DESC, title ASC';
			break;
		case 'popular':
		default:
			$order = 'hits DESC';
	}

	$query = "SELECT title AS title,"
	. "\n start_date AS created,"
	. "\n shortdescription AS text,"
	. "\n concat('Cat: ',catname) AS section,"
	. "\n CONCAT( 'index.php?option=com_bids&task=viewbids&id=', a.id ) AS href,"
	. "\n '1' AS browsernav"
	. "\n FROM #__bid_auctions a "
	. "\n LEFT JOIN #__bid_categories AS b ON b.id = a.cat"
	. "\n WHERE ( $where )"
	. "\n AND a.published = 1 and a.start_date<now()"
	. "\n ORDER BY $order"
	;

	$database->setQuery( $query, 0, $limit );
	$rows = $database->loadObjectList();

	return $rows;
}


?>