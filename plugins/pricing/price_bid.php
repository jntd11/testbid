<?php
/**
 * @package AuctionsFactory
 * @version 1.6.0
 * @copyright www.thefactory.ro
 * @license: commercial
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );
require_once(BIDS_COMPONENT_PATH."/plugins/pricing/pricing_object.php");

class price_bid extends generic_pricing
{

    function price_bid(&$db)
    {
        $this->classname='price_bid';
        $this->classdescription='Module for charging per Bid';
        parent::generic_pricing($db);

    }
    function getDescription()
    {
        return "Pay for bidding on an Auction";
    }
    function getHelp($admin=false)
    {
    	if ($admin) return "If you enable this option, then bidders must pay for evey bid they make on an Auction";
    	else return "In order to place bids on Auctions you must purchase bid-credits. For every bid you will spend one credit.";
    }
    function checktask($task,$d)
    {
        global $my,$Itemid;

        if ($task=='sendbid'){
            $this->_db->setQuery("select count(*) from #__bid_credits where userid='$my->id' and credittype='$this->itemname'");
            if ($this->_db->LoadResult()>0){
                 $this->_db->setQuery("update #__bid_credits set amount=amount-1 where userid='$my->id' and credittype='$this->itemname'");
                 $this->_db->query();
                 return false;

            }else{
                $this->ShowPurchaseDialog($d);
                return true;
            }
        }

        if ($task=='buy_bidding' ){
            $this->ShowPurchaseDialog($d);
            return true;
        }
        if ($task=='payment'){
            $itemname=mosGetParam($_REQUEST,'itemname','');
            $act=mosGetParam($_REQUEST,'act','');
            if ($itemname==$this->itemname && $act=='return'){
                //Just bought a listing --> go to new listing!
                mosRedirect($mosConfig_live_site.'/index.php?option=com_bids&Itemid='.$Itemid,bid_payment_perbid_success);
            }
        }
        return false;

    }

    function ShowPurchaseDialog($d)
    {
        global $Itemid,$mosConfig_live_site;
        $payment_type='';
        $task=mosGetParam($d,'task','');
        $id=mosGetParam($d,'id','');

        $bin_price=mosGetParam($d,'bin_price','');
        $prxo=mosGetParam($d,'prxo','');
        $max_proxy_price=mosGetParam($d,'max_proxy_price','');
        $proxy=mosGetParam($d,'proxy','');
        $amount=mosGetParam($d,'amount','');

        $return_url=urlencode($mosConfig_live_site."/index.php?option=com_bids&task={$task}&id={$id}&bin_price={$bin_price}&prxo={$prxo}&max_proxy_price={$max_proxy_price}&proxy={$proxy}&amount={$amount}");

        mosRedirect($mosConfig_live_site.'/index.php?act=checkout&itemname='.$this->itemname.'&option=com_bids&paymenttype='.$payment_type.'&task=purchase&Itemid='.$Itemid.'&return_url='.$return_url);

    }

}

?>