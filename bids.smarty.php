<?php
class AuctionSmarty extends Smarty {
	function AuctionSmarty(){
		parent::Smarty();
	}

	function display($tpl_name){
		global $database, $task;
		$payment=&mosBidsPayment::getInstance();
		$payment->processTemplate($task, $_REQUEST,$this);
		parent::display($tpl_name);
	}
}
?>