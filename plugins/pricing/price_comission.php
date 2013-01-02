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
require_once(BIDS_COMPONENT_PATH."/plugins/pricing/pricing_object.php");

class price_comission extends generic_pricing
{
	var $classname='price_comission';
	var $classdescription='Module for paying Auction comission';
	var $_db=null;
    var $itemname="comission";
    var $price=null;
    var $price_powerseller=null;
    var $price_verified=null;
    var $currency=null;
    var $enabled=null;
    var $params=null;
    var $param_obj=null;
	var $notify_days=null;
	var $_param_members=array('price_powerseller','price_verified','notify_days');

    function getCurrency($d)
    {
    	$currency = mosGetParam($_REQUEST,'currency', "");
        return $currency;
    }
	function getPrice($d)
    {
    	$price = mosGetParam($_REQUEST,'itemprice', 1);
    	return $price;
    }
    function getComissionValue($user=null)
    {
    	global $my;
    	if (!$user->id){
    		$user=$my;
    	}
    	$u = new mosBidUsers($this->_db);
    	$u->getUserDetails($user->id);
    	
    	if ($u->powerseller){
    		return $this->price_powerseller;
    	}
    	if ($u->verified){
    		return $this->price_verified;
    	}
    	
		if(bid_opt_preferential_cats=="1"){
			$cati = mosGetParam($_SESSION["auction_commission_session"],"categoree");
			if($cati){
				if( strstr(bid_opt_commission_cats,$cati) ){
					$ptype= "commission";
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
    function getHelp($admin=null)
    {
    	if ($admin) return "If you enable this feature, auctioneers will be charged with a comission (percent from the winning bid). There is a balance keeping for all auctioneers - and notification for them to pay monthly";
    	else return "Auctioneers will be charged a percent from the winning bid.";
    }
    
    function checktask($task,$d)
    {
    	global $my,$Itemid,$mosConfig_live_site,$database;
    	
    	switch ($task) {
	    	case "pay_comission":{
	
	    		$d['return_url']=$mosConfig_live_site.'/index.php?option=com_bids&task=pay_comission_set';
				$this->ShowPurchaseDialog( $d );
				return true;
				break;
	
			}
	        case "pay_comission_set":{
	
	        	// pay_return
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
					$currency=$_POST["mc_currency"];
					$this->_db->setQuery("INSERT INTO #__bid_payments SET user_id='$my->id', amount = '$_amount', paydate = NOW(), currency= '$currency', automatic = '1'");
					$this->_db->query();
		        	$this->acceptOrder($my->id, $_balance_update, $_POST["mc_currency"]);
					mosRedirect($mosConfig_live_site.'/index.php?option=com_bids&task=auctions&Itemid='.$Itemid,"Comission payed succesfully!");
	        	}
	        	break;
			}
	
	        case "accept":{
				$bid_id = mosGetParam($_REQUEST,'bid','');
	
				$this->_db->setQuery("select * from #__bids where id='$bid_id'");
				$this->_db->loadObject($bid_res);
	
				$auct_id = $bid_res->id_offer;
				$bid_price = $bid_res->bid_price;
				
				$auction = new mosBidOffers($database);
				$auction->load($auct_id);
				
				if(bid_opt_preferential_cats=="1"){
					if(strpos(bid_opt_commission_cats,$auction->cat)===false)
						return false;
					$_SESSION["auction_commission_session"] = $auction->cat; 
				}
				
				$bid_comission = $this->_applyCommission($bid_price, $bid_id, $auction);
				$this->updateBalance($my->id,$bid_comission, $auction->currency);
	        	break;
			}
	        case "bin":{
				$auct_id = mosGetParam($_REQUEST,'id','');
				
				$redirect_link = saveBid("com_bids",$auct_id,$task);
				$auction = new mosBidOffers($database);
				$auction->load($auct_id);
				
				if (bid_opt_preferential_cats == "1" ){
					if (strpos(bid_opt_commission_cats, $auction->cat)===false)
						return false;
					$_SESSION["auction_commission_session"] = $auction->cat; 
				}
				
				$bid_id = $this->_getWinnerBid($auction->winner_id,$auct_id);
				
				if ($auction->GetParam( 'auto_accept_bin' )=='1' || $auction->automatic){
					$bid_comission = $this->_applyCommission($auction->BIN_price, $bid_id , $auction);
					$this->updateBalance($auction->userid,$bid_comission, $auction->currency);
				}
				mosRedirect($redirect_link);
				break;
			}
		}
    	
    	return false;
	}
	
	function _getWinnerBid($user, $auct_id){
		$this->_db->setQuery("select * from #__bids where userid='$user' and id_offer = '$auct_id'" );
		$this->_db->loadObject($bidtmp);
		return $bidtmp->id;
	}
	
	function _applyCommission($bid_price, $bid, $auction_obj){
		// comission calculation
		$bid_comission = ($bid_price * $this->getComissionValue($my)) / 100;
		// reverse: $my in $auction_obj->userid
		$this->_db->setQuery("INSERT INTO #__bid_comissions SET userid='$auction_obj->userid', bid_id='$bid_id', auction_id='$auction_obj->id', amount = '$bid_comission', comission_date = NOW(), currency = '$auction_obj->currency'");
		$this->_db->query();
		return $bid_comission;
	}
	
	
    function updateBalance($userid,$amount,$currency,$update_date=null)
    {

		$this->_db->setQuery("select count(*) from #__bid_balance where auctioneer='$userid' AND currency  = $currency");
		if ($update_date) $date=",last_pay = NOW()";
		else $date="";
		if($this->_db->LoadResult()){
			$this->_db->setQuery("UPDATE #__bid_balance SET amount =amount+ '$amount' $date where auctioneer = '$userid' AND  currency = $currency ");
			$this->_db->query();
		}else{
		 	$this->_db->setQuery("INSERT INTO #__bid_balance SET  currency = $currency, amount = '$amount' ,auctioneer = '$userid' $date");
		 	$this->_db->query();
		}

    }
    function acceptOrder($order_id,$amount, $currency_name="")
    {
		if( $order_id!="" ){
			if($currency_name){
				$this->_db->setQuery("select * from #__bid_currency where name='$currency_name'");
				$this->_db->loadObject($currency);
			}else{
			    $id = intval( mosGetParam( $_REQUEST, 'id', 0 ) );
			    if (!$id){
			    	$cid = mosGetParam( $_REQUEST, 'cid', array() ) ;
			    	$id=$cid[0];
			    }
				$this->_db->setQuery("select currency from #__bid_paylog where id='$id'");
				$this->_db->loadObject($currency_tmp);
				$this->_db->setQuery("select * from #__bid_currency where name='$currency_tmp->currency'");
				$this->_db->loadObject($currency);
			}

		    $this->updateBalance($order_id,-$amount,$currency->id,1);
		}
    }

    function ShowPurchaseDialog($d)
    {
    global $my, $database;

    $userid = $my->id;
	$opts=null;
	$database->setQuery( "SELECT c.name as text, c.name as value, b.* FROM #__bid_balance b LEFT JOIN #__bid_currency c ON b.currency = c.id WHERE b.auctioneer = $my->id" );
	$opts = $database->loadObjectList();

	?>
	<script type="text/javascript">function val_purchase_form(){var price = document.getElementById('itemprice').value;if(price!="" && parseFloat(price))return true;else{alert("Enter a valid amount!");return false;}return false;}</script>
<?php if ($userid!="") { ?>
	<h2>Auction Comission Payment:</h2>
	Your actual comission reached the following amount :
	<br /> <br />
	You can pay the full amount or a partial amount: <br>
	<?php foreach ($opts as $c => $val)
			if($val->amount>0) { ?>
	<form method="POST" action="index.php" name="purchase_item" onsubmit="return val_purchase_form();">
		<input name="option" type="hidden" value="com_bids">
		<input name="task" type="hidden" value="purchase">
		<input name="paymenttype" type="hidden" value="">
		<input name="itemname" type="hidden" value="comission">
		<input id="itemprice" name="itemprice" type="text" value="<?php echo $val->amount; ?>">
		<input type="hidden" name="currency" value="<?php echo $val->text;?>" ><strong><?php echo $val->text;?></strong>
		<input name="act" type="hidden" value="checkout">
		<input name="return_url" type="hidden" value="<?php echo mosGetParam($d,'return_url','');?>">
		<input type="submit" name="submit" value="Pay now">
	</form>
			    <?php
				}
		}
		else echo "You have to login in able to pay!";
    }

	function plugin_menu(){
	    global $mosConfig_live_site;
		?>
		<table width="320">
		<tr>
		  <td width="80">
        	<a href="index2.php?option=com_bids&task=paymentitemsconfig&itemname=<?php echo $this->itemname;?>">
        	<img src="<?php echo $mosConfig_live_site;?>/components/com_bids/images/plugin_config.png" height="40" border="0"><br>
        	<strong>Config</strong></a>
		  </td>
		  <td width="80">
       		<a href="index2.php?option=com_bids&task=paymentitemsconfig&itemname=<?php echo $this->itemname;?>&action=list">
       		<img src="<?php echo $mosConfig_live_site;?>/components/com_bids/images/payments.png" height="40" border="0"><br>
       		<strong>Auctioneers Balances</strong></a>
		  </td>
		  <td width="80">
    		<a href="index2.php?option=com_bids&task=paymentitemsconfig&itemname=<?php echo $this->itemname;?>&action=payments">
    		<img src="<?php echo $mosConfig_live_site;?>/components/com_bids/images/paymentconfig.png" height="40" border="0"><br>
    		<strong>Payments</strong></a>
		  </td>
		  <td width="80">
		    <a href="index2.php?option=com_bids&task=paymentitemsconfig&itemname=<?php echo $this->itemname;?>&action=send_notice_form">
		    <img src="<?php echo $mosConfig_live_site;?>/components/com_bids/images/plugin_email.png" height="40" border="0"><br>
		    <strong>Send notices</strong></a>
		  </td>
		</tr>
		</table>
		<?php
	}
	function show_admin_config()
    {
		$action = mosGetParam($_REQUEST,"action","");
		switch ($action){
			case "list": $this->list_items(); break;
			case "view": $this->list_comissions_list(); break;
			case "payments": $this->list_payments_list();break;
			case "add_payment": $this->add_payment();break;
			case "save_payment": $this->save_payment();break;
			case "send_notice": $this->sendNotice(); break;
			case "send_notice_form": $this->sendNoticeForm(); break;
			case "send_notice_all": $this->sendNoticeAll(); break;
			default:$this->config();break;
		}
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
        $price_type = "commission";
        
        // some low validation: administrators ain't hackers
        if( $price > 0){
	        $database->setQuery("SELECT * FROM #__bid_custom_prices WHERE category = '{$cat}' AND price_type ='$price_type'");
	        $database->query();
	        $cur = $database->getNumRows();
	        if($cur==0){
		        $database->setQuery("INSERT INTO #__bid_custom_prices SET category = '{$cat}', price = '{$price}',price_type ='$price_type'");
		        $database->query();
	        }
        }
        
    }
    

	function config(){
    	$this->plugin_menu();
      ?>
		<form action="index2.php" method="post" name="adminForm">
			<table width="100%">
			<tr>
				<td width="120px">Comission (percent): </td>
				<td><input size="5" name="price" class="inputbox" value="<?php echo $this->price;?>">%</td>
				<td>Comission charged from winning bid for an auction. Applies to all "normal" users</td>
			</tr>
			<tr>
				<td width="120px">Comission (percent) for power sellers: </td>
				<td><input size="5" name="price_powerseller" class="inputbox" value="<?php echo $this->price_powerseller;?>">%</td>
				<td>Comission percentage for powersellers (you can give them a discouted comission)</td>
			</tr>
			<tr>
				<td width="120px">Comission (percent) for verified users: </td>
				<td><input size="5" name="price_verified" class="inputbox" value="<?php echo $this->price_verified;?>">%</td>
				<td>Comission percentage for verified users (you can give them a discouted comission)</td>
			</tr>
			<tr>
				<td colspan="3">Notify users if their last comission payment was not received in the last x days </td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td colspan="2"><input name="notify_days" value="<?php echo $this->notify_days;?>" type="text" class="inputbox" size="2"> days</td>
			</tr>

			</table>
			<?php 
			// ==> Custom Prices enabled
			
			global $database;
			if(bid_opt_commission_cats)
				$f_paylist_cats = explode(",",bid_opt_commission_cats);
			else
				$f_paylist_cats = null;
				
			$customListingPricesCategories = get_CustomPricesList("commission");
			
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
			
			if(count($filters)>0)
				$filter = implode("",$filters);
			else 
				$filter = ""; 
			 
			$cats = makeCatTreeFiltered($filter);
			$otherListingPricesCategories = mosHTML::selectList($cats,'custom_pricing_category',' class="inputbox" style="width:190px;" "','value', 'text');
			
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
					<a style="color:#FF0000 !important;" href="index2.php?option=com_bids&task=savepaymentitemsconfig&itemname=comission&action=del_custom_price&cat=<?php echo $customCategory->catid; ?>">remove x</a>
					</td>
					<td><?php echo $customCategory->catname; ?></td>
					<td align="center" ><?php echo $customCategory->price;?> % </td>
					<td align="center" style="color:<?php if($customCategory->checked) echo "#DF0000;"; else echo "#00DF00"; ?>;"><?php if($customCategory->checked) echo "Yes"; else echo "No"; ?></td>
				</tr>
				<?php
				} ?>
			</table>
			<table>
				<tr>
					<td colspan="2">
						<h2>Add new percent</h2>
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
					<td>Percent</td>
					<td>
					<input name="custom_pricing_category_price" type="text" /> %
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
			<input type="hidden" name="itemname" value="<?php echo $this->itemname;?>"/>
		</form>
      <?php
    }

 /**
  * List auctioneers balances
  *
 */
	function list_items()
    {
    	global $mosConfig_live_site, $mosConfig_list_limit, $mosConfig_absolute_path;
    	$this->plugin_menu();

    	$limit 		= intval( mosGetParam( $_REQUEST, 'limit',  $mosConfig_list_limit) );
		$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );
		require_once( $mosConfig_absolute_path . '/administrator/includes/pageNavigation.php' );

		$rows = $this->getComissionList($limitstart, $limit, $pageNav);
		?>
	<form name="adminForm">
		<input type="hidden" name="option" value="com_bids" >
		<input type="hidden" name="task" value="paymentitemsconfig">
		<input type="hidden" name="itemname" value="<?php echo $this->itemname;?>" >
		<input type="hidden" name="action" value="list" >
	<table width="100%" class="adminlist">
    	<tr>
    		<th>#</th><th width="*%" align="left">Auctioneer</th><th width="15%">Current Balance</th>
    		<th width="15%">Last Payment Date</th>
    		<th width="15%" align="center">Notice sent</th>
    		<th width="15%">Details</th>
    	</tr>
			<?php
			 if(count($rows)>0) {
			     for($i=0;$i<count($rows);$i++) {
			    $row=$rows[$i];
			    ?>
    	<tr>
    		<td width="25" align="center"><?php echo $i+1;?></td>
    		<td><?php echo $row->username;?></td>
    		<td align="center"><?php echo number_format($row->amount,2)." ".$row->currency_name;?></td>
    		<td width="250" align="center"><?php echo $row->last_pay; ?></td>
    		<td width="250" align="center"><?php if(isset($row->sent_message) && $row->sent_message==1) echo "Sent"; else echo "---"; ?></td>
    		<td width="25" align="center">
    		  <a href="index2.php?option=com_bids&task=paymentitemsconfig&itemname=<?php echo $this->itemname;?>&action=view&auctioneer=<?php echo $row->userid;?>&currency=<?php echo $row->currency;?>">
    		  <img src="<?php echo $mosConfig_live_site;?>/images/edit_f2.png" width="20" border="0" ></a></td>
    	</tr>
			<?php }
			} ?>
	</table>
	<?php echo $pageNav->getListFooter();?>
	</form>
		<?php
    }

/**
 * All payments done (manual + IPN based)
 *
*/
	function list_payments_list()
    {
    	global $mosConfig_live_site, $mosConfig_list_limit, $mosConfig_absolute_path;
    	$this->plugin_menu();

    	$limit 		= intval( mosGetParam( $_REQUEST, 'limit',  $mosConfig_list_limit) );
		$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );
		require_once( $mosConfig_absolute_path . '/administrator/includes/pageNavigation.php' );

		$rows = $this->getPayments($limitstart, $limit, $pageNav);
		?>
	<br />
	<form name="adminForm">
		<input type="hidden" name="option" value="com_bids" >
		<input type="hidden" name="task" value="paymentitemsconfig">
		<input type="hidden" name="itemname" value="<?php echo $this->itemname;?>" >
		<input type="hidden" name="action" value="payments" >
	<table width="100%">
	<tr>
		<td>
		  <a href="index2.php?option=com_bids&task=paymentitemsconfig&itemname=<?php echo $this->itemname?>&action=add_payment">
		  <img src="<?php echo $mosConfig_live_site;?>/components/com_bids/images/add_funds.png" height="40" border="0"><br>
		  <strong>Add manual Payment</strong></a>
		</td>
	</tr>
	</table>
	<table width="100%" class="adminlist">
    	<tr>
    		<th>#</th><th>Auctioneer</th><th>Amount</th><th>Payment Mode</th><th>Date</th>
    	</tr>
		<?php if(count($rows)>0) { ?>
		<?php foreach ($rows as $key => $row) { ?>
    	<tr>
    		<td width="25" align="center"><?php echo $key+1;?></td>
    		<td><?php echo $row->username;?></td>
    		<td align="center"><?php echo $row->amount;?></td>
    		<td align="center"><?php if($row->automatic) echo "Automatic"; else echo "Manual";?></td>
    		<td width="250" align="center"><?php echo $row->paydate;?></td>
    	</tr>
		<?php }
			} ?>
	</table>
	<?php echo $pageNav->getListFooter();?>
	</form>
		<?php
    }

    function list_comissions_list()
    {
    	global $mosConfig_live_site;
		$this->plugin_menu();
		$userid = mosGetParam($_REQUEST, "auctioneer", "");
		$currency = mosGetParam($_REQUEST, "currency", "");
		if($userid!=""){
			$rows = $this->getAuctioneerComissionList($userid, $currency);
			$info = $this->getAuctioneerInfo($userid, $currency);
    	}
    	else
    		mosRedirect("index2.php?option=com_bids&task=paymentitemsconfig&itemname=".$this->itemname."&action=list", "Select an auctioneer to view comissions!");
		?>
	<table width="100%" class="adminlist">
		<tr>
			<td>
				<table>
					<tr><td colspan="2"><a href="index2.php?option=com_bids&task=paymentitemsconfig&itemname=<?php echo $this->itemname;?>&action=list">Back</a></td></tr>
					<tr>
						<td><strong>Auctioneer:</strong></td> <td><?php echo $info->username;?></td>
					</tr>
					<tr>
						<td><strong>Balance:</strong></td> <td><?php echo number_format($info->amount,2)." ".$info->currency_name;?></td>
					</tr>
					<tr>
						<td><strong>Last comission payment:</strong></td> <td><?php if ($info->last_pay) echo $info->last_pay; else echo "Never";?></td>
					</tr>
					<?php if ($info->amount) { ?>
					<tr>
						<td colspan="2" align="center">
						  <input type="button" onclick="document.location='index2.php?option=com_bids&task=paymentitemsconfig&itemname=<?php echo $this->itemname;?>&action=send_notice&auctioneer=<?php echo $userid;?>&currency=<?php echo $currency;?>'" value="Send payment notice" >
						</td>
					</tr>
					<?php } ?>
				</table>
			</td>
		</tr>
	</table>
<table width="100%" class="adminlist">
	<tr>
		<th>#</th><th>Auction Name</th><th align="center">Winning BID</th><th>Amount</th><th>Comission date</th>
	</tr>
		<?php if(count($rows)>0) { ?>
		<?php foreach ($rows as $key => $row) { ?>
	<tr>
		<td width="25" align="center"><?php echo $key+1;?></td>
		<td><a href="<?php echo $mosConfig_live_site;?>/administrator/index2.php?option=com_bids&task=editoffer&id=<?php echo $row->id; ?>" target="_blank"><?php echo mosStripslashes($row->title);?></a></td>
		<td align="center"><?php echo $row->bid_price;?></td>
		<td align="center"><?php echo $row->amount." ".$row->currency_name;?></td>
		<td width="250" align="center"><?php echo $row->comission_date; ?></td>
	</tr>
		<?php }
			} ?>
</table>
		<?php
    }

/**
 * Send auctioneer a payment notification
 *
*/
	function sendNotice(){
    	global $database, $mosConfig_mailfrom, $mosConfig_sitename,$mosConfig_live_site, $mosConfig_absolute_path, $mainframe;
		$userid = mosGetParam($_REQUEST, "auctioneer", "");
		$currency = mosGetParam($_REQUEST, "currency", "");

		$user = new mosUser($database);
		$user->load($userid);
		$email = $user->email;
		$info = $this->getAuctioneerInfo($userid,$currency);

		require_once( $mainframe->getPath( 'front_html',"com_bids" ) );
		$smarty = HTML_Auction::LoadSmarty();
		$smarty->assign("info", $info);
		$html_mail = $smarty->fetch($smarty->template_dir."/t_plugin_mail.tpl");

		$subj = "Auction Payment Notification";
		$first_delimiter = strpos($html_mail,"##");
		if($first_delimiter){
			$snd_delimiter = strpos($html_mail,"##",$first_delimiter+2);
			if($snd_delimiter) $subj = substr($html_mail, $first_delimiter+2, $snd_delimiter- $first_delimiter-2);
		}

		$start = strpos($html_mail,"<html>");
		$html_mail = substr($html_mail, $start);

		$database->setQuery("UPDATE #__bid_balance SET sent_message = '1' WHERE auctioneer = '".$userid."' AND currency = '".$currency."' ");
		$database->query();

		mosMail($mosConfig_mailfrom, $mosConfig_sitename , $email, $subj, $html_mail, true);
		mosRedirect("index2.php?option=com_bids&task=paymentitemsconfig&itemname=".$this->itemname."&action=list", "Message sent!");
    }
/**
 * Send auctioneer a payment notification
 *
*/
	function sendNoticeAll(){
    	global  $mosConfig_mailfrom, $mosConfig_sitename, $mosConfig_live_site, $mosConfig_absolute_path, $mainframe;
		$database=$this->_db;
		$datesend = mosGetParam($_REQUEST, "datesend", 0);
		$start = mosGetParam($_REQUEST, "start_date", "");

		$filter = " WHERE amount > 0 ";
		if($datesend=="1" && $start)
			$filter .= " and last_pay < {$start} 00:00:00";


		$database->setQuery("SELECT * FROM #__bid_balance LEFT JOIN #__users u ON auctioneer = u.id {$filter}");
		$userList = $database->loadObjectList();
		$nr=0;
		foreach ($userList as $k => $user)
		{
			$email = $user->email;
			$info = $this->getAuctioneerInfo($user->auctioneer,$user->currency);

			require_once( $mainframe->getPath( 'front_html',"com_bids" ) );
			$smarty = HTML_Auction::LoadSmarty();
			$smarty->assign("info", $info);
			$html_mail = $smarty->fetch($smarty->template_dir."/t_plugin_mail.tpl");

			$subj = "Auction Payment Notification";
			$first_delimiter = strpos($html_mail,"##");
			if($first_delimiter){
				$snd_delimiter = strpos($html_mail,"##",$first_delimiter+2);
				if($snd_delimiter) $subj = substr($html_mail, $first_delimiter+2, $snd_delimiter- $first_delimiter-2);
			}

			$start = strpos($html_mail,"<html>");
			$html_mail = substr($html_mail, $start);
            $nr++;
			$database->setQuery("UPDATE #__bid_balance SET sent_message = '1' WHERE auctioneer = '".$user->auctioneer."' AND currency = '".$user->currency."' ");
			$database->query();
			mosMail($mosConfig_mailfrom, $mosConfig_sitename , $email, $subj, $html_mail, true);
		}

		$msg = "$nr Messages sent!";
		mosRedirect("index2.php?option=com_bids&task=paymentitemsconfig&itemname=".$this->itemname."&action=list", $msg);
    }
/**
 * Send auctioneer a payment notification
 *
*/
	function sendNoticeForm(){
    	global $database, $mosConfig_live_site, $mosConfig_absolute_path, $mainframe;
		$this->plugin_menu();
		if(JOOMLA_VERSION=="1")	mosCommonHTML::loadCalendar();
		else{
			?>
			<script type="text/javascript" src="<?php echo JURI::root(true);?>/includes/js/calendar/calendar.js"></script>
			<link rel="stylesheet" href="<?php echo JURI::root(true);?>/media/system/css/calendar-jos.css" type="text/css"  title="green"  media="all" />
			<script type="text/javascript" src="<?php echo JURI::root(true);?>/includes/js/calendar/lang/calendar-en-GB.js"></script>
			<?php
		}
    	?>
    <script type="text/javascript">
    	function form_validate(){
    		var datesend = document.getElementById('datesend').checked;
    		var start_date = document.getElementById('start_date').value;
    		if(datesend==true && start_date==""){
    			alert("Select a date!");
    			return false;
    		}
    		return true;
    	}
    </script>
	<form name="adminForm" onsubmit="return form_validate();">
		<h3>Send email notification to users regarding their payment</h3>
		<input type="hidden" name="option" value="com_bids" >
		<input type="hidden" name="task" value="paymentitemsconfig">
		<input type="hidden" name="itemname" value="<?php echo $this->itemname;?>" >
		<input type="hidden" name="action" value="send_notice_all" >
		<input type="checkbox" id="datesend" value="1" name="datesend" > Send only to users with Last Payment Date newer then <input type="text" name="start_date" id="start_date" style="width:60px;" value="" >&nbsp; <input name="reset" class="button" onclick="return showCalendar('start_date', 'y-mm-dd');" value="..." type="reset">
		<br />
		<br /><br />
		<input type="submit" value="Send notices!" >
	</form>
	<?php
	}

/**
 * Save manual added payment
 *
 */
    function save_payment(){
    	$user = mosGetParam($_REQUEST, "user",0);
    	$amount = mosGetParam($_REQUEST, "amount",0);
    	if($user && $amount && !is_nan($amount))
    	{
    		$sql="INSERT INTO #__bid_payments SET user_id = '{$user}', amount = '{$amount}', paydate= NOW()";
    		$this->_db->setQuery($sql);
    		$this->_db->query();

    		$this->updateBalance($user,-$amount,1);

    		mosRedirect("index2.php?option=com_bids&task=paymentitemsconfig&itemname=".$this->itemname."&action=list", "Payment saved");
    	}else{
    		if(!$amount)
	    		mosRedirect("index2.php?option=com_bids&task=paymentitemsconfig&itemname=".$this->itemname."&action=add_payment", "Insert the amount");
    		if(is_nan($amount))
	    		mosRedirect("index2.php?option=com_bids&task=paymentitemsconfig&itemname=".$this->itemname."&action=add_payment", "Amount must be a number");
    			
    	}
    }

/**
 * Manual add payment
 *
 */
    function add_payment(){
    	global $mosConfig_live_site;
    	$this->plugin_menu();
    	$userList = array();
    	$sql = "SELECT * FROM #__users";
    	$this->_db->setQuery($sql);
    	$users = $this->_db->loadObjectList();
		?>
	<form name="adminForm" method="POST" action="index2.php?option=com_bids&task=paymentitemsconfig&itemname=<?php echo $this->itemname?>&action=save_payment">
	<input type="hidden" name="task" value="paymentitemsconfig" >
	<input type="hidden" name="option" value="com_bids" >
	<table width="100%" class="adminlist">
    	<tr>
    		<td width="100">User</td>
    		<td><?php echo mosHTML::selectList($users, "user", "","id","name"); ?></td>
    	</tr>
    	<tr>
    		<td width="100">Amount</td>
    		<td>
			<input type="text" name="amount" />
			<input type="submit" value="Add payment"  />
			</td>
    	</tr>
	</table>
	</form>
		<?php
    }



//****************************************************************************
//******* LISTING UTILS ******************************************************
//****************************************************************************


	function getComissionList($limitstart, $limit, &$pageNav){

		$query = "SELECT count(*) \r\n
		FROM #__users u  \r\n
		LEFT JOIN #__bid_balance b ON auctioneer = u.id
		";
		$this->_db->setQuery($query);
		$total = $this->_db->loadResult();
		$pageNav = new mosPageNav( $total, $limitstart, $limit  );

		$query = "SELECT b.*, u.username, u.name,u.id as userid, c.name as currency_name \r\n
		FROM #__users u \r\n
		LEFT JOIN #__bid_balance b ON auctioneer = u.id \r\n
		LEFT JOIN #__bid_currency c ON b.currency = c.id
		";
    	$this->_db->setQuery($query, $pageNav->limitstart, $pageNav->limit);

    	$return = $this->_db->loadObjectList();

    	return $return;
    }

    function getAuctioneerComissionList($userid, $currency=""){

		if($userid!="")
			$where = " WHERE c.userid = '{$userid}'";
		if($currency!="")
			$where .=" AND c.currency = $currency ";

		$query = "SELECT c.*, u.username, u.name, cr.name as currency_name, \r\n
		 a.id,a.title,a.closed_date, #__bids.bid_price \r\n
		FROM #__bid_comissions c \r\n
		LEFT JOIN #__users u ON c.userid = u.id \r\n
		LEFT JOIN #__bid_auctions a ON a.id = c.auction_id \r\n
		LEFT JOIN #__bid_currency as cr ON cr.id = c.currency \r\n
		LEFT JOIN #__bids ON #__bids.id = c.bid_id
		{$where}
		";
    	$this->_db->setQuery($query);
    	$return = $this->_db->loadObjectList();
    	unset($query);
    	return $return;
    }

    function getAuctioneerInfo($userid, $currency=""){
		$where = " WHERE u.id = '{$userid}' ";
		if ($currency) $where.=" AND b.currency = '{$currency}' ";

		$query = "SELECT b.*, u.username, u.name, c.name as currency_name \r\n
		FROM  #__users u
		LEFT JOIN #__bid_balance b ON auctioneer = u.id
		LEFT JOIN #__bid_currency c ON currency = c.id
		{$where}
		";
		$this->_db->setQuery($query);
		$obj=null;
    	$this->_db->loadObject($obj);
    	unset($query);
    	return $obj;
    }

	function getPayments($limitstart, $limit, &$pageNav){

		$query = "
		SELECT count(p.*)  \r\n
		FROM #__bid_payments p \r\n
		LEFT JOIN #__users u ON p.user_id = u.id \r\n
		";
		$this->_db->setQuery($query);
		$total = $this->_db->loadResult();
		$pageNav = new mosPageNav( $total, $limitstart, $limit  );

		$query = "
		SELECT p.*, u.username, u.name \r\n
		FROM #__bid_payments p \r\n
		LEFT JOIN #__users u ON p.user_id = u.id \r\n
		";
    	$this->_db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
    	$return = $this->_db->loadObjectList();
	  	unset($query);
		return $return;
    }
	function CronProcesssing($daily=null)
	{
    	global  $mosConfig_mailfrom, $mosConfig_sitename, $mosConfig_live_site, $mosConfig_absolute_path, $mainframe;
		$database=$this->_db;

    	if (!$daily) return;
		/**
		 * since  Auction 1.5.6
		 * addes comission to automaticaly chosen, binned unclosed auctions
		 * begin ==>
		 */
		$aobj = new mosBidOffers($database);
		$database->setQuery("SELECT a.*, b.id as winning_bid, b.bid_price, b.payment as commissioned FROM #__bid_auctions AS a
			INNER JOIN #__bids AS b ON a.winner_id = b.userid AND a.id = b.id_offer 
			WHERE NOW() > a.end_date AND b.payment = 0 
		");
		$auctions = $database->loadObjectList();
		if(count($auctions)>0)
		foreach ($auctions as $ak => $auct){
			// double check
			if ($auct->commissioned==0)
			{
				$database->setQuery("UPDATE #__bids SET payment = 1 WHERE id = '$auct->winning_bid'");
				$database->query();
				$aobj->load($auct->id);
				$bid_comission = $this->_applyCommission($auct->bid_price, $auct->winner_id, $aobj);
				$this->updateBalance($auct->userid, $bid_comission, $aobj->currency);
			}
		}
		/**
		 * <== end
		 * addes comission to automaticaly chosen, binned unclosed auctions
		 */
    	
    	$database->setQuery("SELECT * FROM #__bid_balance
			LEFT JOIN #__users u ON auctioneer = u.id
			where datediff(last_pay,now(),interval day)<{$this->notify_days}
		");
		$userList = $database->loadObjectList();
		$nr=0;
		if(count($userList)>0)
		foreach ($userList as $k => $user)
		{
			$email = $user->email;
			$info = $this->getAuctioneerInfo($user->auctioneer,$user->currency);

			require_once( $mainframe->getPath( 'front_html',"com_bids" ) );
			$smarty = HTML_Auction::LoadSmarty();
			$smarty->assign("info", $info);
			$html_mail = $smarty->fetch($smarty->template_dir."/t_plugin_mail.tpl");

			$subj = "Auction Payment Notification";
			$first_delimiter = strpos($html_mail,"##");
			if($first_delimiter){
				$snd_delimiter = strpos($html_mail,"##",$first_delimiter+2);
				if($snd_delimiter) $subj = substr($html_mail, $first_delimiter+2, $snd_delimiter- $first_delimiter-2);
			}

			$start = strpos($html_mail,"<html>");
			$html_mail = substr($html_mail, $start);
            $nr++;
			$database->setQuery("UPDATE #__bid_balance SET sent_message = '1' WHERE auctioneer = '".$user->auctioneer."' AND currency = '".$user->currency."' ");
			$database->query();
			mosMail($mosConfig_mailfrom, $mosConfig_sitename , $email, $subj, $html_mail, true);
		}
		

	}
}

?>