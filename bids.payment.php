<?php
/**
 * @package AuctionsFactory
 * @version 1.6.0
 * @copyright www.thefactory.ro
 * @license: commercial
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );
class mosBidsPayment{

    var $_db=null;
    var $payment_type=null;
    var $payment_obj=null;
    var $_request_array=null;
    var $payment_items=null;

    function mosBidsPayment(&$db,&$d)
    {
        $this->_db=$db;
        $this->_request_array=$d;
        $this->payment_items=$this->getPaymentItems(true);

    }
    function &getInstance($db=null,$d=null)
    {
        static $instance;

        if (!isset ($instance))
        {
            global $database;
            if (!$db) $db=$database;
            if (!$d) $d=$_REQUEST;

            $instance=new mosBidsPayment($db,$d);
        }
        return $instance;
    }
    function getPaymentItems($justEnabled=true)
    {
        $this->_db->setQuery("select * from #__bid_pricing where ".
                    ($justEnabled?"enabled=1":" 1 ").
                    " order by ordering");
        $rows=$this->_db->LoadObjectList();
        $payment_items=array();
        for($i=0;$i<count($rows);$i++){
            $itemname=$rows[$i]->itemname;
            $classname="price_$itemname";
            $classfile=BIDS_COMPONENT_PATH."/plugins/pricing/$classname.php";
            if (file_exists($classfile)){
                require_once($classfile);
                $obj=new $classname ($this->_db);
                $payment_items[]=$obj;
            }
            //unset($rows[$i]);
        }
        return $payment_items;
    }
    function getPaymentItem($itemname)
    {
        for($i=0;$i<count($this->payment_items);$i++){
            /*@var $obj generic_pricing*/
            $obj=&$this->payment_items[$i];
            if ("price_$itemname"==$obj->classname) return $obj;
        }
        $classfile=BIDS_COMPONENT_PATH."/plugins/pricing/price_$itemname.php";
        if (file_exists($classfile)){
            require_once($classfile);
            $obj=new $classname ($this->_db);
            return $obj;
        }
        return null;

    }
    function process()
    {
        $act=mosGetParam($this->_request_array,'act','');
        switch ($act)
        {
            case "checkout":
                    $this->payment_type=$this->GetCurrentPaymentSystem();
                    if ($this->payment_type){
                        $classname=$this->payment_type;
                        require_once(BIDS_COMPONENT_PATH."/plugins/payment/$classname.php");
                        $this->payment_obj=new $classname ($this->_db);
                        $this->payment_obj->checkout($this->_request_array);
                    }
                break;
            case "return":
                $this->checktask('payment',$this->_request_array);
                HTML_Auction::Payment_Thank_You();
                break;
            case "cancel":
                HTML_Auction::Payment_Cancel();
                break;

        }
    }
    function checktask($task,$d)
    {

        for($i=0;$i<count($this->payment_items);$i++){
            $obj=&$this->payment_items[$i];
            if ($obj->checktask($task,$d)){
                return true;
            }
        }
        return false;
    }

    /**
     * $ Since 1.5.2
     *
     * @param $task
     * @param $d
     * @param $smarty
     * @return boolean
     */
    function processTemplate($task,$d, &$smarty)
    {
        if($task == "ViewDetails")
            for($i=0;$i<count($this->payment_items);$i++){
                $obj=&$this->payment_items[$i];
                if(method_exists($obj, "processTemplate"))
	               $obj->processTemplate($task,$d, $smarty);
            }
    }
    function GetCurrentPaymentSystem()
    {
        global $mosConfig_absolute_path;
        $database=$this->_db;
        $pay_type=mosGetParam($this->_request_array,'paymenttype','');
        if (bid_opt_multiple_payments && !$pay_type) {
            $pay_arr=$this->getAvailablePaymentList(true);

            if (count($pay_arr)>1){
                session_start();
                $_SESSION['prev_request']=serialize($this->_request_array);

                HTML_Auction::showChoosePayment($pay_arr);
                return;
            }else{
                $pay_type=$pay_arr[0]->classname;
            }
        }elseif (!$pay_type){
            $database->setQuery("select classname from #__bid_paysystems where isdefault>0 and enabled=1");
            $pay_type=$database->loadResult();
            if (!$pay_type){
                $database->setQuery("select classname from #__bid_paysystems where enabled=1 order by `ordering`",0,1);
                $pay_type=$database->loadResult();
            }
        }

        if(!isset($_SESSION))
        	session_start();
        if (isset($_SESSION['prev_request'])){

            $this->_request_array=unserialize($_SESSION['prev_request']);
            $this->_request_array['paymenttype']=$pay_type;
            unset($_SESSION['prev_request']);
        }
        return $pay_type;
    }
    function getAvailablePaymentList($just_enabled=false)
    {
        include_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/components/com_bids/admin.payment.php' );

        $database=$this->_db;

        $files=getFilesInDir("pay_","payment");
        $pay_plugins=array();

        for($i=0;$i<count($files);$i++){
            require_once(BIDS_PLUGIN_DIR."/payment/".$files[$i]);
            extract_file_ext($files[$i]);
            $classname=$files[$i];

            if (class_exists($classname)){
                $obj=new $classname($database);
                if (!$just_enabled || $obj->enabled)
                    $pay_plugins[]= $obj;
            }
        }
    	return $pay_plugins;
    }
    function processCron($daily=null)
    {
        for($i=0;$i<count($this->payment_items);$i++){
            $obj=&$this->payment_items[$i];
            if(method_exists($obj, "CronProcesssing"))
               $obj->CronProcesssing($daily);
        }

    }
}


?>