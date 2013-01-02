<?php
/**
 * @package AuctionsFactory
 * @version 1.6.0
 * @copyright www.thefactory.ro
 * @license: commercial
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );
define('PAY_PAYPAL_LOG',0);
require_once(BIDS_COMPONENT_PATH."/plugins/payment/payment_object.php");
class pay_paypal extends payment_object{
    var $_db=null;
    var $classname="pay_paypal";
    var $classdescription="Paypal Payment method";

    var $ipn_response=null;
    var $action=null;

    function pay_paypal(&$db){
        parent::payment_object($db);

        $use_sandbox=$this->params->get('use_sandbox','');
        if ($use_sandbox){
            $this->action="https://www.sandbox.paypal.com/cgi-bin/webscr";
        }else{
            $this->action="https://www.paypal.com/cgi-bin/webscr";
        }

    }

    function ipn($d){
        ob_clean();
        $log=new mosBidPayLog($this->_db);

        $business = trim(stripslashes($_POST['business']));
        $item_name = trim(stripslashes($_POST['item_name']));
        $item_number = trim(stripslashes(@$_POST['item_number']));
        $payment_status = trim(stripslashes($_POST['payment_status']));

        // The order total amount including taxes, shipping and discounts
        $mc_gross = trim(stripslashes($_POST['mc_gross']));

        // Can be USD, GBP, EUR, CAD, JPY
        $currency_code =  trim(stripslashes($_POST['mc_currency']));

        $txn_id = trim(stripslashes($_POST['txn_id']));
        $receiver_email = trim(stripslashes($_POST['receiver_email']));
        $payer_email = trim(stripslashes($_POST['payer_email']));
        $payment_date = trim(stripslashes($_POST['payment_date']));

        // The Order Number (not order_id !)
        $invoice =  trim(stripslashes($_POST['invoice']));

        $amount =  trim(stripslashes(@$_POST['amount']));

        $quantity = trim(stripslashes($_POST['quantity']));
        $pending_reason = trim(stripslashes(@$_POST['pending_reason']));
        $payment_method = trim(stripslashes(@$_POST['payment_method'])); // deprecated
        $payment_type = trim(stripslashes(@$_POST['payment_type']));

        // Billto
        $first_name = trim(stripslashes($_POST['first_name']));
        $last_name = trim(stripslashes($_POST['last_name']));
        $address_street = trim(stripslashes(@$_POST['address_street']));
        $address_city = trim(stripslashes(@$_POST['address_city']));
        $address_state = trim(stripslashes(@$_POST['address_state']));
        $address_zipcode = trim(stripslashes(@$_POST['address_zip']));
        $address_country = trim(stripslashes(@$_POST['address_country']));
        $residence_country = trim(stripslashes(@$_POST['residence_country']));

        $address_status = trim(stripslashes(@$_POST['address_status']));

        $payer_status = trim(stripslashes($_POST['payer_status']));
        $notify_version = trim(stripslashes($_POST['notify_version']));
        $verify_sign = trim(stripslashes($_POST['verify_sign']));
        $custom = trim(stripslashes(@$_POST['custom']));
        $txn_type = trim(stripslashes($_POST['txn_type']));

        $option_selection[1] = trim(stripslashes($_POST['option_selection1']));
        $option_selection[2] = trim(stripslashes($_POST['option_selection2']));
        $option_selection[3] = trim(stripslashes($_POST['option_selection3']));
        $option_selection[4] = trim(stripslashes($_POST['option_selection4']));


        $log->date=date('Y-m-d h:i:s');
        $log->amount=$mc_gross;
        $log->currency=$currency_code;
        $log->refnumber=$txn_id;
        $log->invoice=$invoice;
        $log->ipn_response=print_r($_REQUEST,true);
        $log->ipn_ip=$_SERVER['REMOTE_ADDR'];
        $log->comission_id=0;
        $log->userid=$option_selection[1];
        $log->itemname=$item_number;
        $log->payment_method=$this->classname;

        switch  ($payment_status){
            case "Completed":
            case "Processed":
                $log->status='ok';
            break;
            case "Failed":
            case "Denied":
            case "Canceled-Reversal":
            case "Expired":
            case "Voided":
            case "Reversed":
            case "Refunded":
                $log->status='error';
            break;
            default:
            case "In-Progress":
            case "Pending":
                $log->status='manual_check';
            break;
        }
        $log->store();
        $validate=$this->validate_ipn() && ($receiver_email==$paypal_address=$this->params->get('paypal_email',''));

        if ($validate){
            //validate amounts and add item
            $price_classname="price_$item_number";
            if (file_exists(BIDS_COMPONENT_PATH."/plugins/pricing/$price_classname.php")){
                require_once(BIDS_COMPONENT_PATH."/plugins/pricing/$price_classname.php");
                $price_class=new $price_classname($this->_db);
                $value=$quantity*$price_class->getPrice($d);
                $currency=$price_class->getCurrency($d);

                if($mc_gross==$value && $currency_code==$currency && $log->status=='ok'){
                    $price_class->acceptOrder($option_selection[1],$quantity);
                }else{

                    $log->status='manual_check';
                    $log->store();
                }
            }

        }else {

            $log->status='error';
            $log->store();

        }
        exit;
    }
    function show_admin_config()
    {

        $paypal_address=$this->params->get('paypal_email','');
        $use_sandbox=$this->params->get('use_sandbox','');
        $sandbox=mosHTML::yesnoSelectList('use_sandbox','class="inputbox"',$use_sandbox);
        $enabled=mosHTML::yesnoSelectList('enabled','',$this->enabled);
        ?>
        <form action="index2.php" method="post" name="adminForm">
        <table width="100%">
        <tr>
            <td width="120px"><?php echo bid_paypal_paypalemail;?>: </td>
            <td><input size="40" name="paypalemail" class="inputbox" value="<?php echo $paypal_address;?>"></td>
        </tr>
        <tr>
            <td><?php echo bid_paypal_use_sandbox;?>: </td>
            <td><?php echo $sandbox;?></td>
        </tr>
        <tr>
            <td width="120px"><?php echo bid_enabled;?>: </td>
            <td><?php echo $enabled; ?></td>
        </tr>
        </table>
        <input type="hidden" name="option" value="com_bids"/>
        <input type="hidden" name="task" value="savepaymentconfig"/>
        <input type="hidden" name="paymenttype" value="<?php echo $this->classname;?>"/>
        </form>
        <?php

    }
    function save_admin_config()
    {
        $email=mosGetParam($_REQUEST,'paypalemail','');
        $use_sandbox=mosGetParam($_REQUEST,'use_sandbox',2);
        $enabled=mosGetParam($_REQUEST,'enabled','');

        $text="paypal_email=$email\nuse_sandbox=$use_sandbox\n";
        $this->_db->setQuery("update #__bid_paysystems set params='$text',enabled='$enabled' where classname='$this->classname'");
        $this->_db->query();

    }

    function show_payment_form($order_id,$item_description,$itemname,$quantity,$price,$currency,$return_url=null)
    {
        global $mosConfig_live_site,$my;
        $paypal_address=$this->params->get('paypal_email','');
        $action=$this->action;
        if (!$return_url) $return_url=$mosConfig_live_site."/index.php?option=com_bids&task=payment&itemname=".$itemname."&paymenttype=".$this->classname."&act=return";
        ?>
            <div><strong><?php echo $item_description?></strong>&nbsp;-&nbsp;<?php echo number_format($price*$quantity,2,".","")," ",$currency;?></div>
            <form name='paypalForm' action="<?php echo $action;?>" method="post" name="paypal" onsubmit=''>
    		<input type="hidden" name="cmd" value="_xclick">
    		<input type="hidden" name="business" value="<?php echo $paypal_address; ?>">
    		<input type="hidden" name="item_name" value="<?php echo $item_description; ?>">
    		<input type="hidden" name="item_number" value="<?php echo $itemname; ?>">
    		<input type="hidden" name="invoice" value="<?php echo $order_id; ?>">
    		<input type="hidden" name="amount" value="<?php echo $price; ?>">
    		<input type="hidden" name="quantity" value="<?php echo $quantity; ?>">
    		<input type="hidden" name="return" value="<?php echo $return_url;?>">
    		<input type="hidden" name="cancel_return" value="<?php echo $mosConfig_live_site ?>/index.php?option=com_bids&task=payment&itemname=<?php echo $itemname;?>&paymenttype=<?php echo $this->classname;?>&act=cancel">
    		<input type="hidden" name="notify_url" value="<?php echo $mosConfig_live_site ?>/components/com_bids/plugins/payment/pay_paypal.notify.php">
            <input type="hidden" name="on0" value="userid" />
            <input type="hidden" name="os0" value="<?php echo $my->id; ?>" />
    		<input type="hidden" name="tax" value="0" />
    		<input type="hidden" name="rm" value="2" />
    		<input type="hidden" name="no_note" value="1" />
    		<input type="hidden" name="no_shipping" value="1" />
    		<input type="hidden" name="currency_code" value="<?php echo $currency;?>">
    		<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but23.gif" name="submit" alt="<?php echo bid_paypal_buynow;?>" style="margin-left: 30px;">

            </form>
            <div><?php echo bid_paypal_disclaimer;?></div>
        <?php
    }
    function log_ipn_results($success) {
       global $mosConfig_absolute_path;
      if (!PAY_PAYPAL_LOG) return;  // is logging turned off?
      // Timestamp
      $text = '['.date('m/d/Y g:i A').'] - ';

      // Success or failure being logged?
      if ($success) $text .= "SUCCESS!\n";
      else $text .= 'FAIL: '.$this->last_error."\n";

      // Log the POST variables
      $text .= "IPN POST Vars from Paypal:\n";
      foreach ($_POST as $field=>$value) {
         $text .= "$field=$value, ";
      }

      // Log the response from the paypal server
      $text .= "\nIPN Response from Paypal Server:\n ".$this->ipn_response;


   }
   function validate_remote_ip()
   {
        $paypal_iplist = gethostbynamel('www.paypal.com');
		$paypal_iplist2 = gethostbynamel('notify.paypal.com');
        $paypal_iplist = array_merge( $paypal_iplist, $paypal_iplist2 );

        $paypal_sandbox_hostname = 'ipn.sandbox.paypal.com';
        $remote_hostname = gethostbyaddr( $_SERVER['REMOTE_ADDR'] );

        $valid_ip = false;

        if( $paypal_sandbox_hostname == $remote_hostname ) {
            $valid_ip = true;
            $hostname = 'www.sandbox.paypal.com';
        }
        else {
            $ips = "";
            // Loop through all allowed IPs and test if the remote IP connected here
            // is a valid IP address
            foreach( $paypal_iplist as $ip ) {
                $ips .= "$ip,\n";
                $parts = explode( ".", $ip );
                $first_three = $parts[0].".".$parts[1].".".$parts[2];
                if( preg_match("/^$first_three/", $_SERVER['REMOTE_ADDR']) ) {
                    $valid_ip = true;
                }
            }
            $hostname = 'www.paypal.com';
        }
       return $valid_ip;
   }
   function validate_ipn() {

      // parse the paypal URL
      $url_parsed=parse_url($this->action);

      // generate the post string from the _POST vars aswell as load the
      // _POST vars into an arry so we can play with them from the calling
      // script.
      $post_string = '';
      foreach ($_POST as $field=>$value) {
         $post_string .= $field.'='.urlencode($value).'&';
      }
      $post_string.="cmd=_notify-validate"; // append ipn command

      // open the connection to paypal
      $fp = fsockopen($url_parsed[host],"80",$err_num,$err_str,30);
      if(!$fp) {

         // could not open the connection.  If loggin is on, the error message
         // will be in the log.
         $this->ipn_response = "fsockopen error no. $errnum: $errstr";
         $this->log_ipn_results(false);
         return false;

      } else {

         // Post the data back to paypal
         fputs($fp, "POST $url_parsed[path] HTTP/1.1\r\n");
         fputs($fp, "Host: $url_parsed[host]\r\n");
         fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
         fputs($fp, "Content-length: ".strlen($post_string)."\r\n");
         fputs($fp, "Connection: close\r\n\r\n");
         fputs($fp, $post_string . "\r\n\r\n");

         // loop through the response from the server and append to variable
         while(!feof($fp)) {
            $this->ipn_response .= fgets($fp, 1024);
         }

         fclose($fp); // close connection

      }

      if (eregi("VERIFIED",$this->ipn_response)) {

         // Valid IPN transaction.
         $this->log_ipn_results(true);
         return true;

      } else {

         // Invalid IPN transaction.  Check the log for details.
         $this->log_ipn_results(false);
         return false;

      }

   }
   function getLogo()
   {
       return "https://www.paypalobjects.com/WEBSCR-550-20081223-1/en_US/i/btn/btn_xpressCheckout.gif";
   }
}

?>