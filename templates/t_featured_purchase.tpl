{* Include Overlib initialisation *}
{include file='t_overlib.tpl'}

{set_css}

<h2>{$smarty.const.bid_paymentitem_desc_featured}</h2>
<form method="POST" action="index.php" name="purchase_item">
<input name="option" type="hidden" value="com_bids">
<input name="task" type="hidden" value="purchase">
<input name="paymenttype" type="hidden" value="{$paymenttype}">
<input name="act" type="hidden" value="checkout">
<input name="return_url" type="hidden" value="{$return_url}">

    {section name=itemloop loop=$pricing}
        {assign var=price value=`$pricing[itemloop]`}
        {if $price->itemname|substr:0:8 == 'featured' }

            {assign var=txt value=$price->itemname|substr:9}

            {if $selected_type==$txt}
                {assign var=sel value="checked='yes'"}
            {else}
                {assign var=sel value=""}
            {/if}

            <input type="radio" name="itemname" value="{$price->itemname}" {$sel}>
            {assign var=txt value="bid_payment_featured_"|cat:$txt}
            {assign var=txt2 value=$txt|cat:"_help"}
            {$smarty.const.$txt } {infobullet text=$smarty.const.$txt2}<br/>

        {/if}
    {/section}

<input type="submit" name="submit" class="inputbox" value="{$smarty.const.bid_payment_purchase}">

</form>