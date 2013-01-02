{* Include Overlib initialisation *}
{include file='t_overlib.tpl'}

{set_css}

{literal}
<script type="text/javascript">
	function calculateTotal(){
		var sIn = document.getElementById('s');
		var totalIn = document.getElementById('amount');
		var creditPrice = {/literal}{$item_object->price}{literal};
		if(sIn.value<1 || isNaN(sIn.value))
			sIn.value = 1;
		totalIn.value = sIn.value*creditPrice;
	}
</script>
{/literal}

<h2>Buy a contact</h2>
<form method="POST" action="index.php" name="purchase_item">
<input name="option" type="hidden" value="com_bids">
<input name="Itemid" type="hidden" value="{$Itemid}">
<input name="task" type="hidden" value="purchase">
<input name="paymenttype" type="hidden" value="{$paymenttype}">
<input name="act" type="hidden" value="checkout">
<input name="return_url" type="hidden" value="{$return_url}">
<input type="hidden" name="itemname" value="{$item_object->itemname}">

Buy Contacts:   <select name="nr_contacts" id="s" onblur="calculateTotal()" onchange="calculateTotal()">
<option value="1">1 Contact</option>
<option value="2">2 Contact</option>
<option value="5">5 Contact</option>
<option value="10">10 Contact</option>
<option value="15">15 Contact</option>
<option value="20">20 Contact</option>
<option value="50">50 Contact</option>
<option value="100">100 Contact</option>
</select>

<br />
Price per contact: {$item_object->price} {$item_object->currency} / credit<br />
Amount/Cost: <input type="text" name="itemprice" class="inputbox" id="amount" value="{$item_object->price}" readonly="readonly" size="7">
<br />
<input type="submit" name="submit" value="{$smarty.const.bid_payment_purchase}">

</form>