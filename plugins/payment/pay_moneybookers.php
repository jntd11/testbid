<?php
/**
 * @package AuctionsFactory
 * @version 1.6.0
 * @copyright www.thefactory.ro
 * @license: commercial
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );
define('MONEYBOOKERS_LOG',0);
require_once(BIDS_COMPONENT_PATH."/plugins/payment/payment_object.php");
class pay_moneybookers extends payment_object{
    var $_db=null;
    var $classname="pay_moneybookers";
    var $classdescription="Moneybookers Payment method";

    var $ipn_response=null;
    var $action=null;

    function pay_moneybookers(&$db){
        parent::payment_object($db);

        $this->action="https://www.moneybookers.com/app/payment.pl";

    }

    function ipn($d){
        ob_clean();
        $log=new mosBidPayLog($this->_db);

        $mbook_email = trim(stripslashes($_POST['pay_to_email']));
        $from_email = trim(stripslashes($_POST['pay_from_email']));
        $order_id= trim(stripslashes($_POST['transaction_id']));
        $payment_status = trim(stripslashes($_POST['payment_status']));


        $txn_id = trim(stripslashes($_POST['mb_transaction_id']));

        $mb_amount = trim(stripslashes($_POST['mb_amount']));
        $mb_currency = trim(stripslashes($_POST['mb_currency']));
        $payment_status = trim(stripslashes($_POST['status']));

        $md5sig = trim(stripslashes($_POST['md5sig']));
        $amount = trim(stripslashes($_POST['amount']));
        $currency = trim(stripslashes($_POST['currency']));
        $payment_type = trim(stripslashes($_POST['payment_type']));

        $m_userid = trim(stripslashes($_POST['m_userid']));
        $item_number = trim(stripslashes(@$_POST['m_itemnr']));
        $quantity = trim(stripslashes(@$_POST['m_quantity']));


        $log->date=date('Y-m-d h:i:s');
        $log->amount=$amount;
        $log->currency=$currency;
        $log->refnumber=$txn_id;
        $log->invoice=$order_id;
        $log->ipn_response=print_r($_REQUEST,true);
        $log->ipn_ip=$_SERVER['REMOTE_ADDR'];
        $log->comission_id=0;
        $log->userid=$m_userid;
        $log->itemname=$item_number;
        $log->payment_method=$this->classname;

        switch  ($payment_status){
            case "2":
                $log->status='ok';
            break;
            case "-1":
            case "-2":
            case "-3":
                $log->status='error';
            break;
            default:
            case "0":
                $log->status='manual_check';
            break;
        }
        $log->store();

        if ($this->validate_ipn()){
            if (! $this->accept_ipn($d,$m_userid,"price_$item_number",$quantity,$amount,$currency))
            {
                $log->status='manual_check';
                $log->store();
            }
        }else {
            $log->status='error';
            $log->store();
        }
        exit;
    }
    function show_admin_config()
    {

        $email_address=$this->params->get('email','');
        $enabled=mosHTML::yesnoSelectList('enabled','',$this->enabled);

        ?>
        <form action="index2.php" method="post" name="adminForm">
        <table width="100%">
        <tr>
            <td width="120px"><?php echo bid_email;?>: </td>
            <td><input size="40" name="email" class="inputbox" value="<?php echo $email_address;?>"></td>
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
        $email=mosGetParam($_REQUEST,'email','');
        $enabled=mosGetParam($_REQUEST,'enabled','');

        $text="email=$email\n";
        $this->_db->setQuery("update #__bid_paysystems set params='$text',enabled='$enabled' where classname='$this->classname'");
        $this->_db->query();

    }

    function show_payment_form($order_id,$item_description,$itemname,$quantity,$price,$currency,$return_url=null)
    {
        global $mosConfig_live_site,$my;
        $mbooker_address=$this->params->get('email','');
        $action=$this->action;
        if (!$return_url) $return_url=$mosConfig_live_site."/index.php?option=com_bids&task=payment&itemname=".$itemname."&paymenttype=".$this->classname."&act=return";

        ?>
            <div><strong><?php echo $item_description?></strong>&nbsp;-&nbsp;<?php echo number_format($price*$quantity,2,".","")," ",$currency;?></div>
            <form name='paypalForm' action="<?php echo $action;?>" method="post" name="mbooker" onsubmit=''>
    		<input type="hidden" name="pay_to_email" value="<?php echo $mbooker_address; ?>">
    		<input type="hidden" name="recipient_description" value="">
    		<input type="hidden" name="logo_url" value="">
    		<input type="hidden" name="language" value="<?php $mosConfig_language;?>">
    		<input type="hidden" name="hide_login" value="0">
    		<input type="hidden" name="merchant_fields" value="m_userid,m_itemnr,m_quantity">
    		<input type="hidden" name="m_userid" value="<?php echo $my->id; ?>">
    		<input type="hidden" name="m_itemnr" value="<?php echo $itemname; ?>">
    		<input type="hidden" name="m_quantity" value="<?php echo $quantity; ?>">
    		<input type="hidden" name="pay_from_email" value="">

    		<input type="hidden" name="transaction_id" value="<?php echo $order_id; ?>">
    		<input type="hidden" name="return_url" value="<?php echo $return_url;?>">
    		<input type="hidden" name="cancel_url" value="<?php echo $mosConfig_live_site ?>/index.php?option=com_bids&task=payment&itemname=<?php echo $itemname;?>&paymenttype=<?php echo $this->classname;?>&act=cancel">
    		<input type="hidden" name="status_url" value="<?php echo $mosConfig_live_site ?>/components/com_bids/plugins/payment/pay_moneybookers.notify.php">
    		<input type="hidden" name="amount" value="<?php echo $price; ?>">
    		<input type="hidden" name="currency" value="<?php echo $currency;?>">
    		<input type="hidden" name="detail1_description" value="<?php echo $item_description; ?>">

    		<input type="image" src="http://www.moneybookers.com/images/logos/checkout_logos/checkout_120x40px.gif" name="submit" alt="<?php echo bid_mbookers_buynow;?>" style="margin-left: 30px;">

            </form>
            <div><?php echo bid_mbookers_disclaimer;?></div>
        <?php
    }
    function log_ipn_results($success) {
       global $mosConfig_absolute_path;
      if (!MONEYBOOKERS_LOG) return;  // is logging turned off?
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
       //not known
       return true;
   }
   function validate_ipn()
   {
      $pay_to_email = trim(stripslashes($_POST['pay_to_email']));
      $md5sig = trim(stripslashes($_POST['md5sig']));
      //to do MD5 check
      $mbooker_address=trim($this->params->get('email',''));
      $payment_status = trim(stripslashes($_POST['status']));

      $verified_ok=(strtolower($mbooker_address)==strtolower($pay_to_email) && $payment_status=='2');

      $this->log_ipn_results($verified_ok);

      return $verified_ok;

   }
   function getLogo()
   {
       return "http://www.moneybookers.com/images/logos/checkout_logos/checkout_120x40px.gif";
   }

}

?>