<?php
/**
 * @package AuctionsFactory
 * @version 1.6.0
 * @copyright www.thefactory.ro
 * @license: commercial
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );
class generic_pricing
{
    var $classname='override this';
    var $classdescription='Generic Module for pricing';
    var $_db=null;
    var $itemname=null;
    var $price=null;
    var $currency=null;
    var $enabled=null;
    var $params=null;
    var $param_obj=null;
	var $_param_members=array();
    function generic_pricing(&$db)
    {
        $itemname=substr($this->classname,6);
        $this->_db=$db;
        $this->_db->setQuery("select * from #__bid_pricing where itemname='$itemname'");
        $this->_db->LoadObject($this);
        $param_obj=new mosParameters($this->params);
		$this->loadParams($this->params);
		$this->loadPluginLanguage($this->classname.bid_opt_language);
    }
    function getPrice($d)
    {
          return $this->price;
    }
    function getCurrency($d)
    {
        return $this->currency;
    }
    function getDescription()
    {
        if(defined("bid_paymentitem_desc_".$this->itemname))
            return  constant("bid_paymentitem_desc_".$this->itemname);
        else
            return $this->classdescription;

    }
    function initiateOrder()
    {
        global $my;
        return md5(microtime())."_".$my->id;
    }
    function acceptOrder($order_id,$amount)
    {

        $this->_db->setQuery("select count(*) from #__bid_credits where userid='$order_id' and credittype='$this->itemname'");
        if ($this->_db->LoadResult()){
            $this->_db->setQuery("update #__bid_credits set amount=amount+$amount where userid='$order_id' and credittype='$this->itemname'");
        }else{
            $this->_db->setQuery("insert into #__bid_credits set amount=$amount , userid='$order_id' , credittype='$this->itemname'");
        }
        $this->_db->query();

    }
    function cancelOrder($order_id)
    {
        return;
    }
    function show_admin_config()
    {
          ?>
            <form action="index2.php" method="post" name="adminForm">
            <table width="100%">
            <tr>
                <td width="120px"><?php echo bid_payment_item_price;?>: </td>
                <td><input size="5" name="price" class="inputbox" value="<?php echo $this->price;?>"></td>
            </tr>
            <tr>
                <td width="120px"><?php echo bid_payment_item_currency;?>: </td>
                <td><input size="5" name="currency" class="inputbox" value="<?php echo $this->currency;?>"></td>
            </tr>
            </table>
            <input type="hidden" name="option" value="com_bids"/>
            <input type="hidden" name="task" value="savepaymentitemsconfig"/>
            <input type="hidden" name="itemname" value="<?php echo $this->itemname;?>"/>
            </form>
            <?php
    }
    function save_admin_config()
    {
        $price=mosGetParam($_REQUEST,'price','');
        $currecy=mosGetParam($_REQUEST,'currency','');
        $itemname=mosGetParam($_REQUEST,'itemname','');
        $this->price=$price;
        $this->currency=$currecy;
        $this->_db->setQuery("update  #__bid_pricing set price='$price',currency='$currecy' where itemname='{$this->itemname}'");
        $this->_db->query();
        $this->saveParams($_REQUEST);

    }
    function loadParams($paramtext=null)
    {
    	if ($paramtext===null){
			$this->_db->setQuery("select params from  #__bid_pricing  where itemname='{$this->itemname}'");
        	$paramtext=$this->_db->LoadResult();
    	}
    	$arr=explode("\n",$paramtext);

    	for($i=0;$i<count($arr);$i++){
    		$v=explode("=",$arr[$i]);
    		if (in_array($v[0],$this->_param_members)) {
    			$member=$v[0];
    			$this->$member=$v[1];
    		}
    	}
    }
    function saveParams($d=null)
    {
    	$paramtext='';
    	for($i=0;$i<count($this->_param_members);$i++){
    		$member=$this->_param_members[$i];
    		if ($d===null){
    			$value=$this->$member;
    		}else{
    			$value=$d[$member];
    		}
    		$paramtext.=$member.'='.$value."\n";
    	}
		$this->_db->setQuery("update #__bid_pricing set params='{$paramtext}' where itemname='{$this->itemname}'");
    	$this->_db->query();

    }
    function checktask($task,$d)
    {
        return false;
    }
    function getHelp($admin=null)
    {
    	return "";
    }

    function ShowPurchaseDialog()
    {
        return;
    }
	function CronProcesssing($daily=null)
	{
		return;
	}
	function loadPluginLanguage($filename=null)
	{
	    if ($filename && file_exists(BIDS_COMPONENT_PATH."/plugins/pricing/$filename")){
	        require_once(BIDS_COMPONENT_PATH."/plugins/pricing/$filename");
	    }elseif(file_exists(BIDS_COMPONENT_PATH."/plugins/pricing/{$this->classname}.en.php")){
	        require_once(BIDS_COMPONENT_PATH."/plugins/pricing/{$this->classname}.en.php");
	    }

	}
    function processTemplate($task,$d, &$smarty){
        /* alters the template if needed*/
        return;
    }
    
    // SINCE 1.5.9
    function pricingNavigator(){
    	$payment=&mosBidsPayment::getInstance(); 
    	$plugins = $payment->getPaymentItems(null);
    	global $option;
    	$itemname = mosGetParam($_GET,"itemname"); 
    	?>
    	<table class="adminform">
    		<tr>
    			<td>
					<strong><a href="index2.php?option=<?php echo $option;?>&task=paymentitems">Pricing items</a> </strong>
				</td>
    			<?php foreach($plugins as $pi =>$pitem){
    				?>
    			<td>
				<a <?php if($itemname==$pitem->itemname){ ?> style="font-weight:bold;font-size:15px;" <?php } ?>" href="index2.php?option=<?php echo $option;?>&task=paymentitemsconfig&itemname=<?php echo $pitem->itemname;?>"><?php echo ucfirst($pitem->itemname);?></a>
				</td>
    				<?php
    			} ?>
   			</tr>
    	</table>
    	<?php
    }

}

?>