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
class price_featured_bronze  extends generic_pricing
{
    var $featured_type='bronze';

    function price_featured_bronze(&$db)
    {
        $this->classname='price_featured_bronze';
        $this->classdescription='Module for buying featured Auctions (bronze)';
        parent::generic_pricing($db);

    }
    function checktask($task,$d)
    {
        global $my,$mosConfig_live_site;

        if ($task=='set_featured' && $d['featured']=='bronze'){
            $auctionid=mosGetParam($d,'id','');
            $this->_db->setQuery("select sum(amount) from #__bid_credits where userid='$my->id' and credittype='$this->itemname'");
            if ($this->_db->LoadResult()>0){
                 $this->_db->setQuery("update #__bid_auctions set featured='$this->featured_type' where id='$auctionid'");
                 $this->_db->query();

                 $this->_db->setQuery("update #__bid_credits set amount=amount-1 where userid='$my->id' and credittype='$this->itemname'");
                 $this->_db->query();
                 mosRedirect('index.php?option=com_bids&task=viewbids&id='.$auctionid);
                 return true;

            }else{
	            $d['return_url']=$mosConfig_live_site.'/index.php?option=com_bids&task=set_featured&featured=bronze&id='.$auctionid;
                $this->ShowPurchaseDialog($d);
                return true;
            }
        }

        if ($task=='buy_featured' && $d['featured']=='bronze'){
            $this->ShowPurchaseDialog($d);
            return true;
        }

        return false;

    }

    function ShowPurchaseDialog($d)
    {
        global $my;
        $smarty=HTML_Auction::LoadSmarty();

        $this->_db->setQuery("select * from #__bid_pricing where enabled=1");
        $rows=$this->_db->LoadObjectList();


        $smarty->assign('pricing',$rows);
        $smarty->assign('my',$my);
        $smarty->assign('item_object',$this);
        $smarty->assign('paymenttype','');
        $smarty->assign('selected_type',$this->featured_type);
        $smarty->assign('return_url',mosGetParam($d,'return_url',''));

        $smarty->display('t_featured_purchase.tpl');

    }

}

?>