<?php
/**
 * @package AuctionsFactory
 * @version 1.6.0
 * @copyright www.thefactory.ro
 * @license: commercial
 * @modified: 28-05-2009 (dd-mm-YYYY)
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );
class mosBidACL{
    var $_taskmapping=array(
            'viewbids'=>'all',
            'mybids'=>'bidder',
            'mywonbids'=>'bidder',
            'bin'=>'bidder',
            'sendbid'=>'bidder',
            'report_auction'=>'all',
            'terms_and_conditions'=>'all',
            'rate'=>'all',
            'listauctions'=>'all',
            'myauctions'=>'seller',
            'editauction'=>'seller',
            'republish'=>'seller',
            'saveauction'=>'seller',
            'newauction'=>'seller',
            'cancelauction'=>'seller',
            'accept'=>'seller',
            'watchlist'=>'all',
            'mywatchlist'=>'all',
            'delwatch'=>'all',
            'savemessage'=>'all',
            'publish'=>'seller',
            'listcats'=>'all',
            'rss'=>'all',
            'UserDetails'=>'all',
            'ViewDetails'=>'all',
            'saveUserDetails'=>'all',
            'canceluser'=>'all',
            'details'=>'all',
            'myratings'=>'all',
            'search'=>'all',
            'showSearchResults'=>'all',
            'tags'=>'all',
            'bulkimport'=>'seller',
            'importcsv'=>'seller',
            'payment'=>'all',
            'buy_featured'=>'all',
            'set_featured'=>'all',
            'purchase'=>'all',
            'buy_listing'=>'all'

    );
    var $_publicTasks=array("viewbids","listauctions","search","showSearchResults","auction","ViewDetails","listcats","terms_and_conditions","rss","tags", "pay_comission", "pay_comission_set","listbigboard","checkbb",'listauctionspicture','autobid','editproxyticket','listproxyticket','checksession','sendbid',"clearsession");
    var $_anonTasks=array("UserDetails","saveUserDetails","canceluser","viewbids","listbids","listauctions", "rss", "listcats");
    var $_my=null;
    /*@var $_my mosUser*/
    function mosBidACL()
    {
        //
        global $my;
        $this->_my=$my;

    }
    function acl_check($task)
    {
        global $mosConfig_live_site;
        if(bid_opt_allow_guest_messaging)
        	array_push($this->_publicTasks,"savemessage");
        if (!$this->checkPublicTask($task)){
            mosNotAuth();
	        return false;
        }

        if (!$this->checkProfile($task)){
            mosRedirect($mosConfig_live_site.'/index.php?option=com_bids&task=UserDetails',bid_err_more_user_details);
            return false;
        }
        if (bid_opt_enable_acl){
            if(!$this->checkTaskACL($task)){
                mosNotAuth();
    	        return false;
            }
        }
        return true;
    }
    function checkPublicTask($task)
    {
        if ((!$this->_my->id)&& !in_array($task,$this->_publicTasks)){
        	return false;
        }
        return true;
    }
    function checkProfile($task)
    {
        global $database;
        if (!$this->_my->id) return true;
        if (CB_DETECT) return true;
        if (in_array($task,$this->_anonTasks)) return true;

        $bid_user=new mosBidUsers($database);
        if ($bid_user->load($this->_my->id)) return true;

        return false;
    }
    function checkTaskACL($task,$user=null)
    {
        global $database,$acl,$cb_fieldmap;
        if (!$user) $user=$this->_my;

        if ($this->_taskmapping[$task]=='all'){
            return true;
        }
        if (!$user->id){
                return false;
        }
        $isBidder=false;
        $isSeller=false;
        if (bid_opt_acl_type=='groups'){
            if(JOOMLA_VERSION==1)
        		$user_groups=$acl->get_object_groups('users',$user->id,'aro');
        	else
        		$user_groups=$acl->acl_get_groups('users',$user->id);
            if (!is_array($user_groups)) return false;
            if (in_array(bid_opt_acl_bidder,$user_groups)) $isBidder=true;
            if (in_array(bid_opt_acl_seller,$user_groups)) $isSeller=true;

         }
        if (bid_opt_acl_type=='profile'){
            if (CB_DETECT){
                $seller_field=$cb_fieldmap['isSeller'];
                $bidder_field=$cb_fieldmap['isBidder'];

                if($seller_field){
                    $database->setQuery("select $seller_field from #__comprofiler where user_id='".$user->id."'");
                    $isSeller=$database->loadResult();
                }else{
                    //if no field defined, all are sellers
                    $isSeller=true;
                }
                if($bidder_field){
                    $database->setQuery("select $bidder_field from #__comprofiler where user_id='".$user->id."'");
                    $isBidder=$database->loadResult();
                }else{
                    //if no field defined, all are sellers
                    $isBidder=true;
                }

            }else{
                $database->setQuery("select isBidder,isSeller from #__bid_users where userid='".$user->id."'");
                $r=$database->loadRowList();
                $isBidder=$r[0][0];
                $isSeller=$r[0][1];

            }

        }

       if ($this->_taskmapping[$task]=='seller' && $isSeller){
            return true;
        }
        if ($this->_taskmapping[$task]=='bidder' && $isBidder){
            return true;
        }
        return false;


    }
    function isBidder()
    {
        global $acl,$database,$cb_fieldmap;

        if (bid_opt_acl_type=='groups'){
            $user_groups=$acl->get_object_groups('users',$this->_my->id,'aro');
            if (!is_array($user_groups)) return false;
            if (in_array(bid_opt_acl_bidder,$user_groups)) return true;

         }
        if (bid_opt_acl_type=='profile'){
            if (CB_DETECT){
                $bidder_field=$cb_fieldmap['isBidder'];
                if($bidder_field){
                    $database->setQuery("select $bidder_field from #__comprofiler where user_id='".$this->_my->id."'");
                    return $database->loadResult();
                }else{
                    //if no field defined, all are sellers
                    return true;
                }

            }else{
                $database->setQuery("select isBidder from #__bid_users where userid='".$this->_my->id."'");
                return $database->loadResult();
            }

        }

    }
    function isSeller()
    {
        global $acl,$database,$cb_fieldmap;
        if (bid_opt_acl_type=='groups'){
            $user_groups=$acl->get_object_groups('users',$this->_my->id,'aro');
            if (!is_array($user_groups)) return false;
            if (in_array(bid_opt_acl_seller,$user_groups)) return true;

         }
        if (bid_opt_acl_type=='profile'){
            if (CB_DETECT){
                $seller_field=$cb_fieldmap['isSeller'];

                if($seller_field){
                    $database->setQuery("select $seller_field from #__comprofiler where user_id='".$this->_my->id."'");

                    return $database->loadResult();
                }else{
                    //if no field defined, all are sellers
                    return true;
                }
            }else{
                $database->setQuery("select isSeller from #__bid_users where userid='".$this->_my->id."'");
                return $database->loadResult();
            }

        }

    }
    function _isVerified($userid)
    {
        global $acl,$database,$cb_fieldmap;

        if (CB_DETECT){
            $seller_field=$cb_fieldmap['verified'];

            if($seller_field){
                $database->setQuery("select `$seller_field` from #__comprofiler where user_id='$userid'");
                return $database->loadResult();
            }else{
                //if no field defined, all are sellers
                return false;
            }
        }else{
            $database->setQuery("select `verified` from #__bid_users where userid='$userid'");
            return $database->loadResult();
        }

    }
    function isVerified()
    {
        return $this->_isVerified($this->_my->id);
    }

}


?>