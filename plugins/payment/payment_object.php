<?php
/**
 * @package AuctionsFactory
 * @version 1.6.0
 * @copyright www.thefactory.ro
 * @license: commercial
*/
// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

class payment_object
{
    var $_db=null;
    var $classname="payment_object";
    var $classdescription="generic Payment method";
    var $param_text;
    var $params=null;
    var $enabled=null;

	function payment_object(&$db)
	{
            $this->_db=$db;
            $this->_db->setQuery("select params,enabled,isdefault from #__bid_paysystems where classname='$this->classname'");
            $r=$this->_db->loadRow();

            $this->param_text=$r[0];
            $this->enabled=$r[1];
            $this->isdefault=$r[2];

            $this->params=new mosParameters($this->param_text);
            $this->loadPluginLanguage($this->classname.bid_opt_language);

	}
	function loadPluginLanguage($filename=null)
	{
	    if ($filename && file_exists(BIDS_COMPONENT_PATH."/plugins/payment/$filename")){
	        require_once(BIDS_COMPONENT_PATH."/plugins/payment/$filename");
	    }elseif(file_exists(BIDS_COMPONENT_PATH."/plugins/payment/{$this->classname}.en.php")){
	        require_once(BIDS_COMPONENT_PATH."/plugins/payment/{$this->classname}.en.php");
	    }

	}
	function ipn($d)
	{
	    //IPN Processing
	    return;
	}
    function checkout($d)
    {
        global $my;
        //Checkout

        $itemprice=mosGetParam($d,'itemprice','');
        $itemname=mosGetParam($d,'itemname','');
        $itemamount=mosGetParam($d,'itemamount',1);
        $return_url=mosGetParam($d,'return_url',null);
        $price_classname="price_$itemname";
        if (file_exists(BIDS_COMPONENT_PATH."/plugins/pricing/$price_classname.php")){
            require_once(BIDS_COMPONENT_PATH."/plugins/pricing/$price_classname.php");
        }else{
            echo bid_err_no_payment_item;
            return;
        }

        $price_class=new $price_classname($this->_db);

        if($itemprice)
			$value=$itemprice;
       	else
			$value=$price_class->getPrice($d);
        $item_description=$price_class->getDescription();
        $value=$price_class->getPrice($d);
        $currency=$price_class->getCurrency($d);
        $order_id=$price_class->initiateOrder();

        $this->show_payment_form($order_id,$item_description,$itemname,$itemamount,$value,$currency,$return_url);

    }
    function show_payment_form($order_id,$item_description,$itemname,$quantity,$price,$currency,$return_url=null)
    {
        //Here comes the HTML for the payment form

    }
    function show_admin_config()
    {
        //admin

    }
    function save_admin_config()
    {
        //save
    }
    function accept_ipn($d,$order_id,$price_classname,$quantity,$amount_received,$currency_code)
    {
        if (file_exists(BIDS_COMPONENT_PATH."/plugins/pricing/$price_classname.php")){
            require_once(BIDS_COMPONENT_PATH."/plugins/pricing/$price_classname.php");

            $price_class=new $price_classname($this->_db);

            $value=$quantity*$price_class->getPrice($d);
            $currency=$price_class->getCurrency($d);

            if($amount_received==$value && $currency_code==$currency){
                $price_class->acceptOrder($order_id,$quantity);
                return true;
            }else{
                return false;
            }
        }else return false;

    }
   function getLogo()
   {
       global $mosConfig_live_site;
       if (file_exists(BIDS_COMPONENT_PATH."/plugins/payment/{$this->classname}.gif"))
           return "$mosConfig_live_site/components/com_bids//plugins/payment/{$this->classname}.gif";
       if (file_exists(BIDS_COMPONENT_PATH."/plugins/payment/{$this->classname}.png"))
           return "$mosConfig_live_site/components/com_bids//plugins/payment/{$this->classname}.png";
       if (file_exists(BIDS_COMPONENT_PATH."/plugins/payment/{$this->classname}.jpg"))
           return "$mosConfig_live_site/components/com_bids//plugins/payment/{$this->classname}.jpg";

       return "$mosConfig_live_site/components/com_bids//plugins/payment/payment.jpg";
   }


}



?>