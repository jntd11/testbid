<?php
	$query   = "SELECT lot_desired, ppt.id as id, ppt.userid FROM #__proxyplus_tickets ppt JOIN #__proxyplus_bids ppb ON (ppt.id = ppb.ticket_id) ";
		$query   .= " WHERE ppb.auction_id = $id_offer  ";
		/* Jai Commented - 1/10/2010 16:00 To avoid proxyplus condition need to check
		if($isProxyPlus){
			$query   .= " AND ppt.id <> ".$isProxyPlus;
		}
		*/
		echo "<br>".$query   .= " ORDER BY ppt.datemodified, ppt.userid ASC";
		$database->setQuery( $query);
		$rowsProxyPlusTickets = $database->loadObjectList();
		$proxyPlusBids = array();
		if(count($rowsProxyPlusTickets) > 0){
			$countpriorityBids=0;
			foreach($rowsProxyPlusTickets as $rowProxyPlusTickets) {
				echo "<br>".$query   = "SELECT * FROM #__proxyplus_bids AS b WHERE ticket_id = ".$rowProxyPlusTickets->id ." AND outbid = 0 ORDER BY priority, datemodified ASC LIMIT ".$rowProxyPlusTickets->lot_desired;
				$database->setQuery( $query);
				$rowsProxyPlusbids = $database->loadObjectList();
				foreach($rowsProxyPlusbids as $rowProxyPlusbids) {
					if($rowProxyPlusbids->auction_id == $id_offer) {
						$proxyPlusBids[strtotime($rowProxyPlusbids->datemodified)] = array(
														"id"=>$rowProxyPlusbids->id,
														"ticket_id"=>$rowProxyPlusbids->ticket_id,
														"lot_desired"=>$rowProxyPlusTickets->lot_desired,
														"auction_id"=>$rowProxyPlusbids->auction_id,
														"my_bid"=>$rowProxyPlusbids->my_bid,
														"priority"=>$rowProxyPlusbids->priority,
														"datemodified"=>$rowProxyPlusbids->datemodified,
														"outbid"=>$rowProxyPlusbids->outbid,
														"userid"=>$rowProxyPlusTickets->userid
													  );
						$countpriorityBids++;
					}
				}
			}
		}
		ksort($proxyPlusBids);
		print_r($proxyPlusBids);
		/*
		*  Amount:				 actuall highest amount
		*  userid:				 Original userid
		*  currentuserid:		 Highest bidder
		*  currentBid			 Highest bidder bid, but minimum value he can bid to stay highest bidder
		*  currentHighestEqual   If set to 1, already equal amount is there from some user who 
		*/
		$currentHighestEqual = 0;
		do{
				$loopcount1 = 0;
				foreach($proxyPlusBids as $keyBids=>$rowPlaceBids){
						echo "<br> currentuserid = ".$currentuserid;
						echo "<br> amount = ".$amount;
						print_r($rowPlaceBids);
						$loopcount1++;

						//Same bidder
						if($currentuserid == $rowPlaceBids['userid']) {
							$proxyplusstatus++;
							if(count($proxyPlusBids) <= 1) {
								unset($proxyPlusBids[$keyBids]);
							}
							continue;
						}
						if($amount == $rowPlaceBids['my_bid']){
							$currentBid	   = $amount;
							echo "<br> currentHighestEqual".$currentHighestEqual;
							if(!$currentHighestEqual){
								$currentuserid = $rowPlaceBids['userid'];
							}
							$currentHighestEqual = 1;
							$bidType       = "pp";
							
							$query1= "UPDATE #__proxyplus_bids SET outbid = 1 WHERE auction_id = $id_offer AND id = ".$rowPlaceBids['id'];
							$database->setQuery($query1);
							$database->query();
							unset($proxyPlusBids[$keyBids]);
							
							$querylots = "SELECT * FROM `#__proxyplus_bids` ppb where ticket_id = ".$rowPlaceBids['ticket_id']." and outbid = 0 ORDER BY priority limit ".($rowPlaceBids['lot_desired']-1).", 1";
							$database->setQuery($querylots);
							$resultslots = $database->loadObjectList();
							$resultslots[0]->userid  = $rowPlaceBids['userid'];
							$proxyplusTOBID[]        = $resultslots[0];
							$proxyplusstatus         = 0;
						}elseif(($amount + findBidIncrement($amount)) <= $rowPlaceBids['my_bid']){
								$currentuserid       = $rowPlaceBids['userid'];
								$currentBid	         = $amount + findBidIncrement($amount);
								$amount		         = $rowPlaceBids['my_bid'];
								$bidType             = "pp";
								$currentHighestEqual = 1;
						}elseif($amount < $rowPlaceBids['my_bid'] && $amount + findBidIncrement($amount) > $rowPlaceBids['my_bid']){
								$currentuserid       = $rowPlaceBids['userid'];
								$currentBid	         = $amount + findBidIncrement($amount);
								//added 1/27/2010 
								//Need to check
								$currentBid	         = $rowPlaceBids['my_bid'];
								$amount		         = $rowPlaceBids['my_bid'];
								$bidType			 = "pp";
								$currentHighestEqual = 1;
						}elseif($amount > $rowPlaceBids['my_bid']) {

							$query1= "UPDATE #__proxyplus_bids SET outbid = 1 WHERE auction_id = $id_offer AND id = ".$rowPlaceBids['id'];
							$database->setQuery($query1);
							$database->query();
							unset($proxyPlusBids[$keyBids]);

							if($rowPlaceBids['my_bid'] > $currentBid) {
								$currentBid	   = $rowPlaceBids['my_bid'] + findBidIncrement($rowPlaceBids['my_bid']);
							}

							$querylots = "SELECT * FROM `#__proxyplus_bids` ppb where ticket_id = ".$rowPlaceBids['ticket_id']." and outbid = 0 ORDER BY priority limit ".($rowPlaceBids['lot_desired']-1).", 1";
							$database->setQuery($querylots);
							$resultslots = $database->loadObjectList();
							$resultslots[0]->userid = $rowPlaceBids['userid'];
							$proxyplusTOBID[] = $resultslots[0];
							$proxyplusstatus = 0;
							continue;
						}

				}
		}while(count($proxyPlusBids) > 0);
	}

	echo "currentuserid $currentuserid, currentBid $currentBid, amount $amount, bidType $bidType";
	//JaiEndH


	/*
	 * Bidding Process START
	 */
	$bestBid=null;
	if($auction->auction_type != AUCTION_TYPE_PRIVATE) $bestBid = $auction->GetBestBid($id_offer);

	$queryBidUser = "select * from #__bids where  cancel=0 and userid='".$currentuserid."' and id_offer='".$id_offer."' ";
	$database->setQuery($queryBidUser);
	$head_message=new mosBids($database);//new head message

	if(!$database->loadObject($head_message)) {
		$head_message->userid        = $currentuserid;
		$head_message->id_offer      = $id_offer;
		$head_message->bidtype       = $bidType;
		$head_message->bid_price     = $currentBid;
		$head_message->initial_bid   = $currentBid;
		$head_message->modified      = date("Y-m-d H:i:s",time());
		$head_message->accept		 = 0;
		$head_message->cancel		 = 0;
		$head_message->store();

		$owner_user		=	new mosUser($database);
		$owner_user->load($auction->userid);

		if($amount > 0){
			$auction->SendMails($watches,'new_bid_watchlist');
			$auction->SendMails(array($owner_user),'new_bid');

			$userMail	=	new mosUser($database);
			$userMail->load($currentuserid);
			$auction->SendMails(array($userMail),'bid_new_mybid');
			
		}else{
			$auction->SendMails(array($owner_user),'new_message');
		}
		if($currentuserid != $bestBid->userid) { 
			$userMail	=	new mosUser($database);
			$userMail->load($bestBid->userid);
			$auction->SendMails(array($userMail),'bid_outbid');
		}
	}else {
		if($head_message->cancel){
			echo bid_err_was_canceled;
			return;
		}
		if($currentBid >= $head_message->bid_price){
			$head_message->bidtype       = $bidType;
			$head_message->bid_price     = $currentBid;
			$head_message->modified      = date("Y-m-d H:i:s",time());
			$head_message->store();
			
			$owner_user	=	new mosUser($database);
			$owner_user->load($auction->userid);
			if($amount	>	0) {
				$auction->SendMails($watches,'new_bid_watchlist');
				$auction->SendMails(array($owner_user),'new_bid');

				$userMail	=	new mosUser($database);
				$userMail->load($currentuserid);
				$auction->SendMails(array($userMail),'bid_new_mybid');

			}else{
				$auction->SendMails(array($owner_user),'new_message');
			}
			if($currentuserid != $bestBid->userid) { 
				$userMail	=	new mosUser($database);
				$userMail->load($bestBid->userid);
				$auction->SendMails(array($userMail),'bid_outbid');
			}

		}else{
			//must be bigger
			echo "TODO - SERVER Validation";
			echo bid_err_must_be_greater_mybid;
			//mosRedirect($redirect_link,bid_err_must_be_greater_mybid);
			//return;
		}
	}

	/*
	 * Bidding Process END
	 */


function saveBid($option,$id_offer,$task, $amountAuto = 0, $proxy = 0, $isProxyPlus=0, $userid=0){
	global $database,  $mosConfig_absolute_path,  $Itemid, $my;
	global  $mosConfig_live_site, $bidType;

	/*
	* Variable for maintaining outbid
	*/
	$bidType = 0;
	$aidorg =  $id_offer;
	/*
	* Auto Bidding on sale start time
	*/
	if(!$proxy) {
		$proxy = mosGetParam($_REQUEST,'prxo',0);
	}
	$mylastbid = mosGetParam($_REQUEST,'mylastbid'.$_REQUEST['auction_id']);
	if($userid) {
		$mylastbid=0;
	}
	$database->setQuery("SELECT max(bid_price) from #__bids where id_offer={$id_offer}");
	$maxbid = $database->loadResult();
	$proxyplusTOBID = array();
	//$maxbid = mosGetParam($_REQUEST,'maxbid');
	$msg = "";
	$redirect_link = sefRelToAbs("index.php?option=com_bids&task=viewbids&id=$id_offer&Itemid=$Itemid");
	$auction = new mosBidOffers($database);
	if(!$auction->load($id_offer)){
		echo bid_does_not_exist;
		return;
	}
	/*
	*Jai - added for Setting up start date & end date from buyer's choice
	*/
	$manaSettings = $auction->getManagerSettings();

	/*
	* Auto Bidding on sale start time
	*/
	if($amountAuto) {
		$amount = $amountAuto;
	}
	if(!isset($orgmy)){
		$orgmy = $my;
	}
	if($userid) {
		$my->id = $userid;
	}
	if ($auction->userid==$my->id){
		echo bid_err_no_self_bidding;
		return;
	}

	if ($auction->close_offer){
		echo bid_auction_is_closed;
		return;
	}
	if ($auction->published !=1){
		echo bid_does_not_exist;
		return;
	}
	//security check :)
	if (!$my->id && $task != "autobid"){
		mosNotAuth();
		return;
	}


	if($task == 'bin'){
		$amount = $auction->BIN_price;
		if ($amount<=0) {
		    echo bid_no_bin;
		    return ;
		}
	}else{
		if(!$amountAuto){
			$amount=floatval( mosGetParam( $_REQUEST, 'amount',  0) );
			if($amount == 0) {
				$amount=floatval( mosGetParam( $_REQUEST, 'amount'.$_REQUEST['auction_id'],  0) );
			}
		}
		$orgamount = $amount;
		if(bid_opt_allow_proxy && $auction->auction_type!=AUCTION_TYPE_PRIVATE){
				$auction->writelog("\r\n amount -- $amount");
				$prxid=Proxy($amount,$auction,$proxy);
				$auction->writelog("\r\n prxid[0] -- $prxid[0]");
				if($proxy) {
					$amount=$prxid[0];
				}
				$auction->writelog("\r\n PROXY -- $amount");
		}
		if($amount<$auction->initial_price || $amount<=0){
			mosRedirect($redirect_link,bid_err_price);
			return;
		}
	}

	
	$query = "SELECT u.* from #__users u
			  left join #__bid_watchlist w on u.id=w.userid
			  where not(w.userid is null) and w.auctionid='$id_offer'
			  and (w.userid<>'$my->id')
			  ";
	$database->setQuery($query);
	$watches = $database->loadObjectList();

	$comment=mosGetParam( $_REQUEST, 'message',  '');
	//if the auction it's not private test if bid is right

	//  to fix:

	
	if($auction->auction_type != AUCTION_TYPE_PRIVATE && $task != 'bin'){
		$my_acceptedpr = $mylastbid + findBidIncrement($mylastbid);// my bid must be greater than my last bid + minincrease
		$acceptedpr = $maxbid + findBidIncrement($maxbid);// my bid must be greater than max bid + minincrease
		
		//if($userid) 
			//exit;
		if( $amount < $my_acceptedpr ){
			mosRedirect($redirect_link, bid_err_price_mybid);
			return;
		}
		if($amount < $acceptedpr ){
			mosRedirect($redirect_link, bid_err_price_maxbid);
			return;
		}
	}
	//JaiStartI
	$enddate = strtotime($auction->end_date);
	$diff = $enddate - time();
	if($diff > 0) {
		$minutes = ($diff/60);
		$manaSettings->inactivity;
		if($minutes < $manaSettings->inactivity) {
			if($manaSettings->buyerschoice == "Buyer's Choice") {
				$comProf = new mosComProfiler($database);
				$comProf->_tbl_key='id';
				$comProf->id = $manaSettings->comprofid;
				$comProf->cb_enddate = date("Y-m-d H:i:s", (time()+($manaSettings->inactivity * 60)));
				$comProf->store();
			}else{
					$auction->end_date = date("Y-m-d H:i:s", (time()+($manaSettings->inactivity * 60)));
					$auction->store($id_offer);
			}
		}
	}
	//JaiEndI


	/*
	*JaiSTARTH
	* Jai added for checking any bids with same amount in proxy or proxyplus for this lot with higher precedence
	*/
	$priorityBids = array();
	if(strtotime($auction->start_date) < time() && !$proxy && !$isProxyPlus) {
		$auction->writelog("\r\n----INSIDE PROXYPLUS I = ".$amount." auction->id = ".$auction->id);
		//$nextIncrement = $amount + findBidIncrement($amount);
		$nextIncrement = $amount;
		echo $query = "SELECT *, bp.id as bid_id FROM #__bid_auctions auc JOIN #__bid_proxy bp ON ( auc.id = bp.auction_id )
				  WHERE active =1 AND auction_id = '$id_offer' AND max_proxy_price >=".$nextIncrement." AND datemodified <= NOW( ) ORDER BY datemodified";
		$database->setQuery($query);
		$priorityproxybids = $database->loadObjectList();
		$countpriorityBids = 0;
		if(count($priorityproxybids) > 0) {
			foreach($priorityproxybids as $rowpriorityproxybids) {
				$priorityBids[strtotime($rowpriorityproxybids->datemodified)] = array(
														"id"=>$rowpriorityproxybids->bid_id,
														"auction_id"=>$rowpriorityproxybids->auction_id,
														"max_bid"=>$rowpriorityproxybids->max_proxy_price,
														"datemodified"=>$rowpriorityproxybids->datemodified,
														"type"=>"p",
														"userid"=>$rowpriorityproxybids->user_id
													  );
				$countpriorityBids++;
			}
		}

		$query   = "SELECT lot_desired, ppt.id as id, ppt.userid FROM #__proxyplus_tickets ppt JOIN #__proxyplus_bids ppb ON (ppt.id = ppb.ticket_id) ";
		$query  .= " WHERE ppb.auction_id = $id_offer  ";
		$query  .= " AND ppb.outbid = 0 ";
		$query  .= " AND ppb.my_bid >= $nextIncrement ";
		echo "<br>".$query  .= " ORDER BY ppt.datemodified ASC";
		$database->setQuery( $query);
		$priorityproxyplusbids = $database->loadObjectList();
		if(count($priorityproxyplusbids) > 0) {
			foreach($priorityproxyplusbids as $rowProxyPlusTickets) {
				$query   = "SELECT * FROM #__proxyplus_bids AS b WHERE ticket_id = ".$rowProxyPlusTickets->id;
				$query  .= " AND auction_id = $id_offer";
				$query  .= " AND my_bid >= $nextIncrement ";
				echo $query  .= " AND outbid = 0 ORDER BY priority, datemodified ASC LIMIT ".$rowProxyPlusTickets->lot_desired;
				$database->setQuery( $query);
				$rowsProxyPlusbids = $database->loadObjectList();
				foreach($rowsProxyPlusbids as $rowProxyPlusbids) {
						$priorityBids[strtotime($rowProxyPlusbids->datemodified)] = array(
														"id"=>$rowProxyPlusbids->id,
														"auction_id"=>$rowProxyPlusbids->auction_id,
														"max_bid"=>$rowProxyPlusbids->my_bid,
														"datemodified"=>$rowProxyPlusbids->datemodified,
														"type"=>"pp",
														"userid"=>$rowProxyPlusTickets->userid
													  );
						$countpriorityBids++;
				}
			}
		}
		ksort($priorityBids);
		echo "<pre>";
		print_r($priorityBids);
		$outbidpriorityBid = 0;
		$countpriorityBids =0;
		foreach($priorityBids as $key =>$priorityBid){
			print_r($priorityBid);
			$countpriorityBids++;
			if($amount >= $priorityBid->max_bid) {
				if($amount > $priorityBid->max_bid && ($amount + findBidIncrement($amount)) <= $priorityBid->max_bid && $countprioritybids == 1){
					$amount += findBidIncrement($amount);
				}
				$database->setQuery("select * from #__bids where cancel=0 and userid = '".$priorityBid['userid']."' and id_offer = '".$priorityBid->auction_id."'");
				$head_message = new mosBids($database);//new head message
				if(!$database->loadObject($head_message)) {
					$head_message->userid=$priorityBid['userid'];
					$head_message->id_offer=$priorityBid['auction_id'];
					if($priorityBid->type == "p"){
						$head_message->id_proxy=$priorityBid['id'];
					}
					$head_message->bid_price=$amount;
					$head_message->initial_bid=$amount;
					
					$head_message->modified = date("Y-m-d H:i:s",time());
					$head_message->accept=0;
					$head_message->cancel=0;
					$head_message->bidtype=$priorityBid['type'];
					$head_message->store();
					echo "here1";
					print_r($head_message);
					$bestBid5=null;
					$bestBid5=$auction->GetBestBid();
					if($priorityBid['userid'] != $bestBid5->userid) { 
						$database->setQuery("select u.* from #__users u where id=".$bestBid5->userid);
						$users = $database->loadObjectList();
						$auction->SendMails($users,'bid_outbid');
					}

					$database->setQuery("select u.* from #__users u where id=".$priorityBid['userid']);
					$users = $database->loadObjectList();
					$auction->SendMails($users,'bid_new_mybid');
				}else{
						if($priorityBid['type'] == "p"){
							$head_message->id_proxy=$priorityBid['id'];
						}
						$head_message->userid=$priorityBid['userid'];
						$head_message->id_offer=$priorityBid['auction_id'];
						$head_message->bid_price=$amount;
						$head_message->modified=date("Y-m-d H:i:s",time());
						$head_message->bidtype=$priorityBid['type'];
						$head_message->store();
						echo "here2";
						print_r($head_message);
						$bestBid5=null;
						$bestBid5=$auction->GetBestBid();
						if($priorityBid['userid'] != $bestBid5->userid) { 
							$database->setQuery("select u.* from #__users u where id=".$bestBid5->userid);
							$users = $database->loadObjectList();
							$auction->SendMails($users,'bid_outbid');
						}

						$database->setQuery("select u.* from #__users u where id=".$priorityBid['userid']);
						$users = $database->loadObjectList();
						$auction->SendMails($users,'bid_new_mybid');
				}

				if($amount == $priorityBid->max_bid){
					if($priorityBid->type == "pp"){
						$query1= "UPDATE #__proxyplus_bids SET outbid = 1 WHERE auction_id = ".$priorityBid['auction_id']." AND id = ".$priorityBid->id;
						$database->setQuery($query1);
						$database->query();
					}else {
						$query1= "UPDATE #__bid_proxy SET active = 0 WHERE auction_id = ".$priorityBid['auction_id']."AND id = ".$priorityBid['id'];
						$database->setQuery($query1);
						$database->query();
					}
				}

				$outbidpriorityBid = 1;
				$amount = $amount + findBidIncrement($amount);
			}else {

				if($priorityBid->type == "pp"){
					$query1= "UPDATE #__proxyplus_bids SET outbid = 1 WHERE auction_id = ".$priorityBid['auction_id']." AND id = ".$priorityBid['id'];
					$database->setQuery($query1);
					$database->query();
				}else {
					$query1= "UPDATE #__bid_proxy SET active = 0 WHERE auction_id = ".$priorityBid['auction_id']." AND id = ".$priorityBid['id'];
					$database->setQuery($query1);
					$database->query();
				}

				$database->setQuery("select u.* from #__users u where id=".$priorityBid->userid);
				$users = $database->loadObjectList();
				$auction->SendMails($users,'bid_outbid');
			}
		}
		$rowsProxyPlusbids = array();
	}

	/*
	* END 
	*/
	$bestBid=null;
	if($auction->auction_type != AUCTION_TYPE_PRIVATE) $bestBid = $auction->GetBestBid();
	$database->setQuery("select * from #__bids where  cancel=0 and userid='$my->id' and id_offer='$id_offer' ");
	$manualbidactive = 0;
	if((strtotime($auction->start_date) < time() || $prxid[1])) {
			$auction->writelog("\r\n----INSIDE MANUAL = ".$amount." auction->id = ".$auction->id);
			$head_message=new mosBids($database);//new head message
			if (!$database->loadObject($head_message)) {
				$head_message->userid=$my->id;
				$head_message->id_offer=$id_offer;
				$head_message->id_proxy=$prxid[1];
				$head_message->bidtype="m";
				if($outbidpriorityBid) {
					$head_message->bid_price=$orgamount;
					$head_message->initial_bid=$orgamount;
				}else{
					$head_message->bid_price=$amount;
					$head_message->initial_bid=$amount;
				}
				/*
				* Jai - Added for not to bid (proxy) before start time
				*/
				if(strtotime($auction->start_date) >= time() && $prxid[1]) {
					$head_message->bid_price= 0;
					$head_message->initial_bid= $auction->initial_price;
				}
				
				$head_message->modified=date("Y-m-d H:i:s",time());
				$head_message->accept=0;
				$head_message->cancel=0;
				$head_message->store();

					$owner_user=new mosUser($database);
					$owner_user->load($auction->userid);

					if ($amount>0) {
						$auction->SendMails($watches,'new_bid_watchlist');
						$auction->SendMails(array($owner_user),'new_bid');
				
						if($userid) {
							$userMail=new mosUser($database);
							$userMail->load($userid);
							$auction->SendMails(array($userMail),'bid_new_mybid');
						}else {
							$auction->SendMails(array($my),'bid_new_mybid');
						}
					}else{
						$auction->SendMails(array($owner_user),'new_message');
					}
					if($outbidpriorityBid) {
							$userMail=new mosUser($database);
							$userMail->load($my->id);
							$auction->SendMails(array($userMail),'bid_outbid');
					}

					$manualbidactive = 1;
	
			} else {
				if ($head_message->cancel){
					echo bid_err_was_canceled;
					return;
				}
				if ($amount>=$head_message->bid_price){
					$head_message->id_proxy=$prxid[1];
					if($outbidpriorityBid) {
						$head_message->bid_price=$orgamount;
					}else {
						$head_message->bid_price=$amount;
					}
					$head_message->bidtype="m";
					/*
					* Jai - Added for not to bid (proxy) before start time
					*/
					if(strtotime($auction->start_date) >= time() && $prxid[1]) {
						$head_message->bid_price=0;
					}
					$head_message->modified=date("Y-m-d H:i:s",time());
					$head_message->store();
					
					$owner_user=new mosUser($database);
					$owner_user->load($auction->userid);
					if ($amount>0) {
						$auction->SendMails($watches,'new_bid_watchlist');
						$auction->SendMails(array($owner_user),'new_bid');
						if($userid) {
							$userMail=new mosUser($database);
							$userMail->load($userid);
							$auction->SendMails(array($userMail),'bid_new_mybid');
						}else {
							$auction->SendMails(array($my),'bid_new_mybid');
						}

					}else{
						$auction->SendMails(array($owner_user),'new_message');
					}
					$manualbidactive = 1;
					if($outbidpriorityBid) {
							$userMail=new mosUser($database);
							$userMail->load($my->id);
							$auction->SendMails(array($userMail),'bid_outbid');
					}

				}else{
					//must be bigger
					mosRedirect($redirect_link,bid_err_must_be_greater_mybid);
					return;
				}
			}
	}elseif($outbidpriorityBid) {
			mosRedirect($redirect_link,bid_err_must_be_greater_mybid);
			return;
	}
		
	//JaiStartH
	/*
	Jai ProxyPlus
	*/
	$loopcount = $loopcount1 = 0;
do{
	$loopcount = $loopcount1 = 0;
	$proxyplusstatus = 0;
	$proxystatus = 0;
	if(strtotime($auction->start_date) < time()){
		$auction->writelog("<br>----PROXY PLUS II = ".$amount." auction->id = ".$auction->id);
		$query   = "SELECT lot_desired, ppt.id as id, ppt.userid FROM #__proxyplus_tickets ppt JOIN #__proxyplus_bids ppb ON (ppt.id = ppb.ticket_id) ";
		$query   .= " WHERE ppb.auction_id = $id_offer  ";
			
		/* Jai Commented - 1/10/2010 16:00 To avoid proxyplus condition need to check
		if($isProxyPlus){
			$query   .= " AND ppt.id <> ".$isProxyPlus;
		}
		*/
		echo $query   .= " ORDER BY ppt.userid asc";
		$database->setQuery( $query);
		$rowsProxyPlusTickets = $database->loadObjectList();
		$proxyPlusBids = array();
		if(count($rowsProxyPlusTickets) > 0) {
			foreach($rowsProxyPlusTickets as $rowProxyPlusTickets) {
				echo $query   = "SELECT * FROM #__proxyplus_bids AS b WHERE ticket_id = ".$rowProxyPlusTickets->id ." AND outbid = 0 ORDER BY priority LIMIT ".$rowProxyPlusTickets->lot_desired;
				$database->setQuery( $query);
				$rowsProxyPlusbids = $database->loadObjectList();
				foreach($rowsProxyPlusbids as $rowProxyPlusbids) {
					if($rowProxyPlusbids->auction_id == $id_offer) {
						$proxyPlusBids[$rowProxyPlusTickets->userid] = array(
														"id"=>$rowProxyPlusbids->id,
														"ticket_id"=>$rowProxyPlusbids->ticket_id,
														"lot_desired"=>$rowProxyPlusTickets->lot_desired,
														"auction_id"=>$rowProxyPlusbids->auction_id,
														"my_bid"=>$rowProxyPlusbids->my_bid,
														"priority"=>$rowProxyPlusbids->priority,
														"datemodified"=>$rowProxyPlusbids->datemodified,
														"outbid"=>$rowProxyPlusbids->outbid,
														"userid"=>$rowProxyPlusTickets->userid
													  );
					}
				}
			}
		}
		do {
				$loopcount1 = 0;
				echo "<pre>";
				foreach($proxyPlusBids as $rowPlaceBids){
						print_r($rowPlaceBids);
						$loopcount1++;
						//Jai - Coding for bidding
						$bestBid=null;
						if($auction->auction_type != AUCTION_TYPE_PRIVATE ) $bestBid=$auction->GetBestBid();
						//Same bidder
						if($bestBid->userid == $rowPlaceBids['userid']) {
							echo "jai-1 $amount";
							$proxyplusstatus++;
							if(count($proxyPlusBids) <= 1) {
								unset($proxyPlusBids[$rowPlaceBids['userid']]);
							}
							continue;
						}
						$amount += findBidIncrement($amount);
						echo "jai-2-before $amount $rowPlaceBids[my_bid]";
						if($amount > $rowPlaceBids['my_bid']) {
							echo "jai-2 $amount";
							$query1= "UPDATE #__proxyplus_bids SET outbid = 1 WHERE auction_id = $id_offer AND id = ".$rowPlaceBids['id'];
							$database->setQuery($query1);
							$database->query();
							$database->setQuery("select u.* from #__users u where id=".$rowPlaceBids['userid']);
							$users = $database->loadObjectList();
							$auction->SendMails($users,'bid_outbid');
							unset($proxyPlusBids[$rowPlaceBids['userid']]);
							echo $querylots = "SELECT * FROM `#__proxyplus_bids` ppb where ticket_id = ".$rowPlaceBids['ticket_id']." and outbid = 0 ORDER BY priority limit ".($rowPlaceBids['lot_desired']-1).", 1";
							$database->setQuery($querylots);
							$resultslots = $database->loadObjectList();
							$resultslots[0]->userid = $rowPlaceBids['userid'];
							$proxyplusTOBID[] = $resultslots[0];
							$proxyplusstatus = 0;
							continue;
						}
						$database->setQuery("select * from #__bids where  cancel=0 and userid='".$rowPlaceBids['userid']."' and id_offer='$id_offer' ");
						$head_message=new mosBids($database);//new head message
						if(!$database->loadObject($head_message)) {
							$head_message->userid=$rowPlaceBids['userid'];
							$head_message->id_offer=$id_offer;
							$head_message->id_proxy=$prxid[1];
							$head_message->bid_price=$amount;
							$head_message->initial_bid=$amount;
							$head_message->modified=date("Y-m-d H:i:s",time());
							$head_message->bidtype="pp";
							$head_message->accept=0;
							$head_message->cancel=0;
							$head_message->store();
							$bidType = 1;
							if($manualbidactive) {
								$manualbidactive = 0;
								$database->setQuery("select u.* from #__users u where id=".$my->id);
								$users = $database->loadObjectList();
								$auction->SendMails($users,'bid_outbid');
							}
							$database->setQuery("select u.* from #__users u where id=".$rowPlaceBids['userid']);
							$users = $database->loadObjectList();
							$auction->SendMails($users,'bid_new_mybid');

						} else {
							if($amount >= $head_message->bid_price){
								$head_message->id_proxy=$prxid[1];
								$head_message->bid_price=$amount;
								$head_message->modified=date("Y-m-d H:i:s",time());
								$head_message->bidtype="pp";
								$head_message->store();
								$bidType=1;
								if($manualbidactive) {
									$manualbidactive = 0;
									$database->setQuery("select u.* from #__users u where id=".$my->id);
									$users = $database->loadObjectList();
									$auction->SendMails($users,'bid_outbid');
								}
								$database->setQuery("select u.* from #__users u where id=".$rowPlaceBids['userid']);
								$users = $database->loadObjectList();
								$auction->SendMails($users,'bid_new_mybid');
							}
						}
						//Jai End coding for bidding
				}
		}while(count($proxyPlusBids) > 0);
		$auction->writelog("proxyplusstatus = $proxyplusstatus");
		/*
		if($loopcount && !$loopcount1) {
			$amount += findBidIncrement($amount);
		}
		$auction->writelog("updateProxies $amount, $auction->id");
		*/
		$successproxy = updateProxies($amount,$auction->id);
		

		$bestBid5=null;
		$bestBid5=$auction->GetBestBid();

		$amountMax=0;
		$amountMaxProxy=0;
		$queryMax = "select max_proxy_price as max_proxy_price from #__bid_proxy jbp where auction_id = ".$auction->id." and active = 1 and user_id = ".$my->id;
		$database->setQuery($queryMax);
		$amountMaxProxy = $database->loadResult();
		if($amountMaxProxy == "") $amountMaxProxy = 0;
		if($bestBid5->userid != $my->id && $amountMaxProxy > $amount){
			echo $queryMax = "select max(bid_price) as bid_price from #__bids jb where id_offer = ".$auction->id;
			$database->setQuery($queryMax);
			$amountMax = $database->loadResult();
			$auction->writelog("\r\n----INSIDE==== amountMax = $amountMax proxy amount = ".$amount." auction->id = ".$auction->id);
			if($amountMax) {
				$amount = $amountMax + findBidIncrement($amountMax);
				if($amountMaxProxy < $amount){
					continue;
				}
			}
			$auction->SendMails($users,'bid_outbid');
			$auction->writelog("\r\n----INSIDE====proxy amount = ".$amount." auction->id = ".$auction->id);
						$database->setQuery("select * from #__bids where  cancel=0 and userid='".$my->id."' and id_offer='$auction->id' ");
						$head_message=new mosBids($database);//new head message
						if(!$database->loadObject($head_message)) {
							$head_message->userid=$my->id;
							$head_message->id_offer=$id_offer;
							$head_message->id_proxy=$prxid[1];
							$head_message->bid_price=$amount;
							$head_message->initial_bid=$amount;
							$head_message->modified=date("Y-m-d H:i:s",time());
							$head_message->accept=0;
							$head_message->cancel=0;
							$head_message->bidtype="p";
							$head_message->store();
							$bidType = 2;
							$database->setQuery("select u.* from #__users u where id=".$bestBid5->userid);
							$users = $database->loadObjectList();
							$auction->SendMails($users,'bid_outbid');

							$database->setQuery("select u.* from #__users u where id=".$my->id);
							$users = $database->loadObjectList();
							$auction->SendMails($users,'bid_new_mybid');

						} else {
							if ($amount >= $head_message->bid_price){
								$head_message->id_proxy=$prxid[1];
								$head_message->bid_price=$amount;
								$head_message->modified=date("Y-m-d H:i:s",time());
								$head_message->bidtype="p";
								$head_message->store();
								$bidType = 2;
								$database->setQuery("select u.* from #__users u where id=".$bestBid5->userid);
								$users = $database->loadObjectList();
								$auction->SendMails($users,'bid_outbid');

								$database->setQuery("select u.* from #__users u where id=".$my->id);
								$users = $database->loadObjectList();
								$auction->SendMails($users,'bid_new_mybid');
							}
						}

			//saveBid($option,$auction->id,$task,$amount, 0, 0,0);
		}else {
			//commented on 1/10/2010 15:30 Jai
			$proxyplusstatus = 0;
		}

	}
	//JaiEndH
	$loopcount++;
	$auction->writelog("($bestBid5->userid != $my->id && $amountMaxProxy > $amount) || $proxyplusstatus");
	$i++;
	echo "<br> $i = ".$i;
	if($i > 4) 
		exit;
}while(($bestBid5->userid != $my->id && $amountMaxProxy > $amount) || $proxyplusstatus);


	$bestBid2=null;
	if($auction->auction_type != AUCTION_TYPE_PRIVATE) $bestBid2=$auction->GetBestBid();
	/*@var $bestBid mosBids*/
	/*@var $bestBid2 mosBids*/
	
	$auction->writelog("<br>END-1---$bestBid->userid--2---$bestBid2->userid--");
	if($bestBid && $bestBid2 && $bestBid->userid != $bestBid2->userid && $auction->auction_type != AUCTION_TYPE_PRIVATE ){
		$outbidded=new mosUser($database);
		$outbidded->load($bestBid->userid);
		$auction->writelog("<br>$bestBid->userid<br>OUTBIDDED");
		$auction->SendMails(array($outbidded),'bid_outbid'); //Outbidded
	}
	$auction->writelog($successproxy);


	/*JAISTARTJ
	* Moved to top
	* 12/25/2009
	
	$owner_user=new mosUser($database);
	$owner_user->load($auction->userid);

	if ($amount>0) {
		$auction->SendMails($watches,'new_bid_watchlist');
		$auction->SendMails(array($owner_user),'new_bid');
		$auction->SendMails(array($my),'bid_new_mybid');
	}else{
		$auction->SendMails(array($owner_user),'new_message');
	}
	*/
	
	/*
	* JaiSTARTJ
	* To send Proxy accepted mails
	*/
	if($successproxy) {
		$outbidded=new mosUser($database);
		$outbidded->load($my->id);
		$auction->writelog("<br>$my->id<br>OUTBIDDED");
		$auction->SendMails(array($outbidded),'bid_outbid'); //Outbidded
	}
	/*
	*
	* JaiEndJ
	*/
	if ($amount>=$auction->BIN_price && $auction->BIN_price>0){
		if ($auction->GetParam( 'auto_accept_bin' )=='1' || $auction->automatic){
			//accept and close and send emails
			$head_message->accept=1;
			$head_message->store();

			$auction->winner_id = $my->id;
			$auction->published=1;
			$auction->close_offer=1;
			$auction->closed_date=date('Y-m-d H:i:s',time());
			$auction->store();

			$database->setQuery("delete from #__bid_watchlist where auctionid = $auction->id");
			$database->query();

			$auction->SendMails($watches,'alert_bin_accepted');

			$auction->SendMails(array($owner_user),'new_bid_auto_bin');
			$auction->SendMails(array($my),'bin_accepted');
		} else {
			$auction->SendMails($watches,'alert_new_bid_bin');
			//just notify
			$auction->SendMails(array($owner_user),'new_bid_bin');
			$auction->SendMails(array($my),'bin_wait_approval');
		}
	}
	foreach($proxyplusTOBID as $keyproxyplusTOBID=>$proxyplusTOBIDS){
		echo $queryCurrent = "SELECT if(max(b.bid_price)!='',max(b.bid_price),a.initial_price) as bid_next FROM jos_bids AS b RIGHT JOIN jos_bid_auctions AS a ON a.id = b.id_offer WHERE a.id = ".$proxyplusTOBIDS->auction_id." ORDER BY bid_price DESC";
		$database->setQuery($queryCurrent);
		$amount = $database->loadResult();
		$auction->writelog("<br> INSIDE saveBids1 $option,$proxyplusTOBIDS->auction_id,$task,$amount, 0, $proxyplusTOBIDS->ticket_id, $proxyplusTOBIDS->userid ");
		//OLD No Ticket ids
		saveBid($option,$proxyplusTOBIDS->auction_id,$task,$amount, 0, $proxyplusTOBIDS->ticket_id, $proxyplusTOBIDS->userid);
		$bidType = 1;
	}

	$bidType = 0;	
	$bestBid2=null;
	$bestBid2=$auction->GetBestBid($aidorg);
	$auction->writelog("<br> $bestBid2->userid != ".$_SESSION['__default']['user']->id);
	if($bestBid2->userid != $_SESSION['__default']['user']->id) {
		$bidType = ($bestBid2->bidtype == "pp")?1:2;
	}
	if($bidType){
		echo $redirect_link=$mosConfig_live_site."/index.php?option=com_bids&task=viewbids&id=$aidorg&Itemid=$Itemid&ou=$bidType&mosmsg=".bid_succes_special_new."#bid_list";
	}else {
		echo $redirect_link=$mosConfig_live_site."/index.php?option=com_bids&task=viewbids&id=$aidorg&Itemid=$Itemid&mosmsg=".bid_succes_special."#bid_list";
	}
	$auction->writelog("<br> \n".$redirect_link);
	return $redirect_link;
}
?>
