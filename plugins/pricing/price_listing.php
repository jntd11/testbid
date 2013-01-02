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

class price_listing extends generic_pricing
{
    var $price=null;
    var $price_powerseller=null;
    var $price_verified=null;
	var $_param_members=array('price_powerseller','price_verified');
    function price_listing(&$db)
    {
        $this->classname='price_listing';
        $this->classdescription='Module for buyingAuction listings';
        parent::generic_pricing($db);

    }
    function getHelp($admin=null)
    {
    	if ($admin) return "If you enable this feature, auctioneers will be charged with a fixed price for every auction they publish.";
    	else return "Auctioneers will be charged a fixed price for evey auction they publish.";
    }
    function getPrice($d)
    {
    	global $my;
    	$u = new mosBidUsers($this->_db);
    	$u->getUserDetails($my->id);
    	
    	if ($u->powerseller){
    		return $this->price_powerseller;
    	}
    	if ($u->verified){
    		return $this->price_verified;
    	}
    	
		if(bid_opt_preferential_cats=="1"){
			$cati = mosGetParam($_SESSION["auction_session"],"categoree");
			if($cati){
				if( strstr(bid_opt_paylist_cats,$cati) ){
					$ptype= "listing";
					$sql = " SELECT price ".
							" FROM jos_bid_custom_prices ".
							" WHERE category = '$cati' AND price_type= '$ptype'";
							$this->_db->setQuery($sql);
					$custom_price_Result = $this->_db->loadResult();
					if($custom_price_Result)
						return $custom_price_Result;		
				}
			}
		}
		
    	return $this->price;
    }

    function checktask($task,$d)
    {
        global $my,$Itemid;

        if ($task=='newauction' || $task=='republish'){
			if(bid_opt_preferential_cats=="1")
				return false;
            $this->_db->setQuery("select sum(amount) from #__bid_credits where userid='$my->id' and credittype='$this->itemname'");
            if ($this->_db->LoadResult()<=0){
                $this->ShowPurchaseDialog($d);
                return true;
            }
        }
        if ($task=='saveauction' ){
            $auctionid=mosGetParam($d,'id','');
            if ($auctionid) return false;//Edit
        	$errors=ValidateSaveAuction();
        	if ($errors) {
                echo "<script>alert('".$errors."');</script>";
        		echo "<script>history.go(-1);</script>";
        		exit;
          
			}
			
			if(bid_opt_preferential_cats=="1")
				{
					$cati = mosGetParam($_POST,"cat");
					if(strpos(bid_opt_paylist_cats,$cati)===false)
						return false;
					$_SESSION["auction_session"]["categoree"] = $cati; 	
				}

            
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

        if ($task=='buy_listing' ){
            $this->ShowPurchaseDialog($d);
            return true;
        }
        if ($task=='payment'){
            $itemname=mosGetParam($_REQUEST,'itemname','');
            $act=mosGetParam($_REQUEST,'act','');
            if ($itemname==$this->itemname && $act=='return'){
                //Just bought a listing --> go to new listing!
                mosRedirect($mosConfig_live_site.'/index.php?option=com_bids&Itemid='.$Itemid,bid_payment_listing_pending);
                //mosRedirect($mosConfig_live_site.'/index.php?option=com_bids&task=newauction&Itemid='.$Itemid,bid_payment_listing_success);
            }
        }
        if ($task=='importcsv' || $task=='bulkimport'){
            echo bid_payment_listing_bulkimport;
            echo "<br>";
            return true;
        }
        return false;

    }

    function ShowPurchaseDialog($d)
    {
        global $Itemid,$mosConfig_live_site;
        $task=mosGetParam($d,'task','');
        $id=mosGetParam($d,'id','');

		if ($task=='newauction' || $task=='republish') {
			// $return_url = urlencode($mosConfig_live_site.'/index.php?option=com_bids&task='.$task.'&id='.$id);
			$return_url=urlencode($mosConfig_live_site.'/index.php?option=com_bids&task=payment&itemname='.$this->itemname.'&paymenttype=pay_paypal&act=return');
        }

        mosRedirect($mosConfig_live_site.'/index.php?act=checkout&itemname='.$this->itemname.'&option=com_bids&paymenttype=&task=purchase&Itemid='.$Itemid.'&return_url='.$return_url);

    }

	function show_admin_config()
	{
		
		global $database;
		if(bid_opt_paylist_cats)
			$f_paylist_cats = explode(",",bid_opt_paylist_cats);
		else
			$f_paylist_cats = null;
			
		$customListingPricesCategories = get_CustomPricesList("listing");
		
		$exceptions = array();
		for($co = 0 ; $co < count($customListingPricesCategories); $co++) {
			$customListingPricesCategories[$co]->checked = in_array($customListingPricesCategories[$co]->id,$f_paylist_cats);
			$exceptions[] = $customListingPricesCategories[$co]->catid;
		}
		$filter = array();
		if(count($exceptions)>0){
			$c = implode(",",$exceptions);
			$filters[] = " AND id NOT IN ($c)";
		}
		$filter = implode("",$filters);
		 
		$cats = makeCatTreeFiltered($filter);
		$otherListingPricesCategories = mosHTML::selectList($cats,'custom_pricing_category',' class="inputbox" style="width:190px;" "','value', 'text');
		
      ?>
      	
		<form action="index2.php" method="post" name="adminForm">
			<table width="100%">
			<tr>
				<td width="120px">Price per listing regular users: </td>
				<td><input size="5" name="price" class="inputbox" value="<?php echo $this->price;?>"></td>
				<td>Price for placing an auction. Applies to all "normal" users</td>
			</tr>
			<tr>
				<td width="120px">Price per listing  for power sellers: </td>
				<td><input size="5" name="price_powerseller" class="inputbox" value="<?php echo $this->price_powerseller;?>"></td>
				<td>Price for placing an auction for powersellers (you can give them a discouted comission)</td>
			</tr>
			<tr>
				<td width="120px">Price per listing for verified users: </td>
				<td><input size="5" name="price_verified" class="inputbox" value="<?php echo $this->price_verified;?>"></td>
				<td>Price for placing an auction for verified users (you can give them a discouted comission)</td>
			</tr>
            <tr>
                <td width="120px"><?php echo bid_payment_item_currency;?>: </td>
                <td><input size="5" name="currency" class="inputbox" value="<?php echo $this->currency;?>"></td>
            </tr>
			</table>
			<?php 
			// ==> Custom Prices enabled
			if(bid_opt_various_commissions=="1"){
			?>
			<hr />
			<table>
				<tr>
					<td>
					<h2>Custom prices</h2><br />
					If no category price added but preferential pay per listing cats are selected from Auction settings,
					the price will be the default
					</td>
				</tr>
			</table>
			<table class="adminlist">
				<tr>
					<th width="80" ></th><th>Category</th><th width="200" >Price</th><th align="center" width="200">Selected as custom price</th>
				</tr>
				<?php
				foreach( $customListingPricesCategories as $i => $customCategory ){ ?>
				<tr>
					<td align="right">
					<a style="color:#FF0000 !important;" href="index2.php?option=com_bids&task=savepaymentitemsconfig&itemname=listing&action=del_custom_price&cat=<?php echo $customCategory->catid; ?>">remove x</a>
					</td>
					<td><?php echo $customCategory->catname; ?></td>
					<td align="center" ><?php echo $customCategory->price;?> <?php echo $this->currency;?> </td>
					<td align="center" style="color:<?php if($customCategory->checked) echo "#DF0000;"; else echo "#00DF00"; ?>;"><?php if($customCategory->checked) echo "Yes"; else echo "No"; ?></td>
				</tr>
				<?php
				} ?>
			</table>
			<table>
				<tr>
					<td colspan="2">
						<h2>Add new price</h2>
					</td>
				</tr>
				<tr>
					<td>Category:</td>
					<td>
						<?php
						echo $otherListingPricesCategories; ?>
					</td>
				</tr>
				<tr>
					<td>Price</td>
					<td>
					<input name="custom_pricing_category_price" type="text" />
					<a href="#" onclick="document.getElementById('pl_task').value='add_custom_pricing';document.adminForm.submit();">Add</a>
					</td>
				</tr>
			</table>
			<?php }
			// <== Custom Prices enabled
			?>

			<input type="hidden" name="option" value="com_bids"/>
			<input type="hidden" id="pl_task" name="action" value="" />
			<input type="hidden" name="task" value="savepaymentitemsconfig" />
			<input type="hidden" name="itemname" value="<?php echo $this->itemname;?>" />
		</form>
      <?php
    }
    
    function save_admin_config()
    {
        $action = mosGetParam($_REQUEST,'action','');
		//exit;
        switch($action){
        	case "add_custom_pricing":
        		$this->add_custom_pricing();
        		return;
        	break;
        	case "del_custom_price":
        		$this->delete_custom_pricing();
        		return;
        	break;
        }
		parent::save_admin_config();
    }
    
    function delete_custom_pricing(){
        global $database;
        $cat = mosGetParam($_GET,'cat','');
        $database->setQuery("DELETE FROM #__bid_custom_prices WHERE category = '{$cat}'");
        $database->query();
        //echo $database->_sql;exit;
    }
    
    function add_custom_pricing(){
        global $database;
        $cat = mosGetParam($_POST,'custom_pricing_category','');
        $price = (int)mosGetParam($_POST,'custom_pricing_category_price','');
        $price_type = "listing";
        
        // some low validation: administrators ain't hackers
        if( $price > 0){
	        $database->setQuery("SELECT * FROM #__bid_custom_prices WHERE category = '{$cat}' AND price_type ='$price_type' ");
	        $database->query();
	        $cur = $database->getNumRows();
	        if($cur==0){
		        $database->setQuery("INSERT INTO #__bid_custom_prices SET category = '{$cat}', price = '{$price}',price_type ='$price_type'");
		        $database->query();
	        }
        }
        
    }

}

?>