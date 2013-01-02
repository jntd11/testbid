{* Include Overlib initialisation *}
{include file='t_overlib.tpl'}
{set_css}
{literal}
    <script type="text/javascript">
    function get_radio_value(radio)
    {
        for (var i=0; i < radio.length; i++)
       {
       if (radio[i].checked)
          {
            return radio[i].value;
          }
       }
       return false;
    }


    function checkForm()
    {
        var frm=document.auctionForm;
        if (get_radio_value(frm.paymenttype)==false){
            alert('{/literal}{$smarty.const.bid_choose_gateway}{literal}');
            return false;
        }
        else
            return true;
    }
    </script>
{/literal}
<div>{$smarty.const.bid_choose_gateway}</div>
<form action="index.php" method="get" name="auctionForm" onsubmit="return checkForm();">
<input type="hidden" name="option" value="com_bids">
<input type="hidden" name="task" value="{$task}">
<input type="hidden" name="itemname" value="{$itemname}">
<input type="hidden" name="return_url" value="{$return_url}">
<input type="hidden" name="act" value="{$act}">
<input type="hidden" name="Itemid" value="{$Itemid}">

<table align="center" cellpadding="0" cellspacing="0" width="100%" id="auction_list_container">
    {section name=payloop loop=$payment_systems}
        {assign var=pay value=`$payment_systems[payloop]`}
        <tr>
            <td><input name="paymenttype" type="radio" value="{$pay->classname}"></td>
            <td><img src='{$pay->thumbnail}' border="0"></td>
            <td>{$pay->classdescription}</td>
        </tr>
    {/section}
        <tr>
            <td colspan="3"><input name="submit" class="inputbox" type="submit" value="{$smarty.const.bid_choose}"></td>
        </tr>
</table>


</form>
