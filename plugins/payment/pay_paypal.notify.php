<?php
/**
 * @package AuctionsFactory
 * @version 1.6.0
 * @copyright www.thefactory.ro
 * @license: commercial
*/

// no direct access
    header("Status: 200 OK");
    define('_VALID_MOS', '1');
    require_once(dirname(__FILE__)."/pay_paypal.loadframework.php");
    global $mosConfig_absolute_path, $mosConfig_live_site, $mosConfig_lang, $database,$mosConfig_mailfrom, $mosConfig_fromname;

/*              $logfile=$mosConfig_absolute_path."/a_paypal_ipn.log";
              $fp=fopen($logfile,"w+");
              fwrite($fp, print_r($_REQUEST,true) . "\n\n");
              fwrite($fp, print_r($_SERVER,true) . "\n\n");
              fclose($fp);  // close file
*/
    require_once($mosConfig_absolute_path.'/components/com_bids/bids.class.php');

    require_once(dirname(__FILE__)."/pay_paypal.php");

	$payment=new pay_paypal($database);
	$payment->ipn($_REQUEST);
?>