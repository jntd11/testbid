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
class price_contact  extends generic_pricing
{
    function price_contact(&$db) {
		$this->classname='price_contact';
    	$this->classdescription='Module for buying contacts';
		parent::generic_pricing($db);
    }

    function getPrice($d)
    {
    	$price = mosGetParam($_REQUEST,'itemprice', 1);
    	return $price;
    }
    function getHelp($admin=null)
    {
    	if ($admin) return "If you enable this feature, users will be charged for seeing other users contact details.";
    	else return "Users get charged to receive other users details.";
    }

	function checktask($task,$d) {
        global $my,$mosConfig_live_site;

		if ($task=='buy_contact') {
			$d['return_url']=$mosConfig_live_site.'/index.php?option=com_bids&task=set_contact&id='.$_GET["id"];
			$this->ShowPurchaseDialog($d);
			return true;
        }
        // pay_return
		if ($task=='set_contact'){
			$_amount = trim(stripslashes($_POST['mc_gross']));
            $_payment_status = trim(stripslashes($_POST['payment_status']));
            switch  ($_payment_status){
            	case "Completed":
                case "Processed":{
                    $_status='ok';
                    $_balance_update = $amount;
	                break;
                    }
                case "Failed":
                case "Denied":
                case "Canceled-Reversal":
                case "Expired":
                case "Voided":
                case "Reversed":
                case "Refunded":
                    $_status='error';
                break;
                default:
                case "In-Progress":
                case "Pending":
                	{
                    $_status='manual_check';
                    $_balance_update = 0;
                	break;
                	}
            }
        	if($_status!="error"){
	        	$this->acceptOrder($my->id, $_balance_update, $this->getCurrency($d));
				mosRedirect($mosConfig_live_site.'/index.php?option=com_bids&task=auctions&Itemid='.$Itemid,bid_success_constant_payed);
        	}
		}

        return false;

    }

    function ShowPurchaseDialog($d)
    {
        global $my, $database,$Itemid,$mosConfig_live_site;
    	$id = mosGetParam($_REQUEST,'id', '');

        $return_url=$mosConfig_live_site."/index.php?option=com_bids&task=ViewDetails&id={$id}&Itemid={$Itemid}";

        $smarty=HTML_Auction::LoadSmarty();

        $this->_db->setQuery("select * from #__bid_pricing where enabled=1");
        $rows=$this->_db->LoadObjectList();
        $smarty->assign('pricing',$rows);
        $smarty->assign('user',$my);
        $smarty->assign('item_object',$this);
        $smarty->assign('paymenttype','');
        $smarty->assign('return_url',$return_url);
		$smarty->display('t_buy_contact.tpl');
    }

    function processTemplate($task,$d, &$smarty){
        global $mosConfig_live_site;
    	global $my, $database, $Itemid;
    	$id = mosGetParam($_REQUEST,'id', '');

    	if($smarty){
    		if($task=="ViewDetails"){
		    	$contactId = $_GET["id"];
				$uri = $mosConfig_live_site.'/index.php?option=com_bids&task='.$task.'&id='.$contactId.'&Itemid='.$Itemid."&contact=1";
    			if(!$my->id)
    				{ mosNotAuth(); return ; }

    			if( $my->id!=$_GET["id"] ){
    				$credits = $this->getGredits($my->id, $contactId);
					$contact_bought = $this->verifyContact($my->id, $contactId);

					if($contact_bought==0) {
	    				if( isset($credits[0])  && $credits[0]->amount>=1 ){
							// if you have not bought the contact it and no request to consume contact link to consume contact
							if($_GET["contact"]!="1"){
    		    				$smarty->_tpl_vars["user"]->surname = "hidden";
    		    				$smarty->_tpl_vars["user"]->phone = "hidden";
    		    				$smarty->_tpl_vars["user"]->address = "hidden";
    		    				$smarty->_tpl_vars["user"]->paypalemail = "hidden";
			    				$smarty->_tpl_vars["user"]->name = "<a href='".$uri."'>".bid_contact_consume."</a>";
	    					}elseif ($_GET["contact"]=="1"){
	    						// consuming a credit and view
	    						$this->decreaseCredit($my->id, $contactId);
	    					}
	    				}elseif($credits[0]->amount==0){
	    					// no credits, buy
		    				$smarty->_tpl_vars["user"]->surname = "hidden";
		    				$smarty->_tpl_vars["user"]->phone = "hidden";
		    				$smarty->_tpl_vars["user"]->address = "hidden";
		    				$smarty->_tpl_vars["user"]->paypalemail = "hidden";
		    				$smarty->_tpl_vars["user"]->name = "hidden&nbsp;<a href='index.php?option=com_bids&task=buy_contact&id=".$contactId."&ItemId=".$Itemid."'>".bid_buy_contact_head."</a>";
	    				}
					}
    			}
    		}
    	}
    }
    function getDescription()
    {
    	$nr = mosGetParam($_REQUEST,'nr_contacts', '');
    	if ($nr) $nr="($nr contacts)";
        return "Pay for user Contact information $nr";
    }

    function getGredits($userId, $contact_id) {
		$sql = "
			SELECT itemname,amount,price,currency,task_pay FROM
			#__bid_pricing AS p
			LEFT JOIN #__bid_credits c ON p.itemname=c.credittype AND c.userid='$userId'
			WHERE  p.itemname='".$this->itemname."'";
		$this->_db->setQuery( $sql );
		return $this->_db->loadObjectList() ;
    }

    function verifyContact($userId, $contact_id){
		$sql = "
			SELECT count(*) FROM
			#__contacts_buy
			WHERE  userid='".$userId."' AND contact_id = '".$contact_id."'";
		$this->_db->setQuery( $sql );
		return $this->_db->loadResult();
    }

    function decreaseCredit($userId, $contact_id){
		$this->_db->setQuery("UPDATE #__bid_credits set amount=amount-1 where userid='$userId' and credittype='$this->itemname'");
		$this->_db->query();
		$this->_db->setQuery("INSERT INTO #__contacts_buy SET userid='$userId' , contact_id = '$contact_id'");
		$this->_db->query();
	}

}

?>