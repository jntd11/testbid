<?php
/**
 * @package AuctionsFactory
 * @version 1.6.0
 * @copyright www.thefactory.ro
 * @license: commercial
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

/*** access Joomla's configuration file ***/
    $my_path = dirname(__FILE__);

    if( file_exists($my_path."/../../../../configuration.php")) {
        require_once($my_path."/../../../../configuration.php");
		define( 'JPATH_BASE', dirname( $my_path."/../../../../configuration.php") );
    }elseif( file_exists($my_path."/../../../configuration.php")) {
        require_once($my_path."/../../../configuration.php");
		define( 'JPATH_BASE', dirname( $my_path."/../../../configuration.php") );
    }
    elseif( file_exists($my_path."/../../configuration.php")){
        require_once($my_path."/../../configuration.php");
		define( 'JPATH_BASE', dirname( $my_path."/../../configuration.php") );
    }
    elseif( file_exists($my_path."/configuration.php")){
        require_once( $my_path."/configuration.php" );
		define( 'JPATH_BASE', dirname( $my_path."/configuration.php") );
    }
    else
        die( "Joomla Configuration File not found!" );


    if( class_exists( 'jconfig' ) ) {
			define( '_JEXEC', 1 );
			define( 'DS', DIRECTORY_SEPARATOR );

			define( 'JPATH_CONFIGURATION', constant('JPATH_BASE') );

			// Load the framework
			require_once ( JPATH_BASE . DS . 'includes' . DS . 'defines.php' );
			require_once ( JPATH_BASE . DS . 'includes' . DS . 'framework.php' );

			// create the mainframe object
			$mainframe = & JFactory::getApplication( 'site' );

			// Initialize the framework
			$mainframe->initialise();

			//create the database object
			$database = &JFactory::getDBO();

            JPluginHelper::importPlugin('system');
    }else{
    	require_once($mosConfig_absolute_path. '/includes/database.php');
    	require_once($mosConfig_absolute_path. '/includes/joomla.php');


        // load Joomla Language File
        if (file_exists( $mosConfig_absolute_path. '/language/'.$mosConfig_lang.'.php' )) {
            require_once( $mosConfig_absolute_path. '/language/'.$mosConfig_lang.'.php' );
        }
        elseif (file_exists( $mosConfig_absolute_path. '/language/english.php' )) {
            require_once( $mosConfig_absolute_path. '/language/english.php' );
        }
    }

?>
