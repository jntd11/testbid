<script language="javascript" type="text/javascript">
var language=Array();
language["bid_err_empty_bid"]='{$smarty.const.bid_err_empty_bid}';
language["bid_err_increase"]='{$smarty.const.bid_err_increase}';
language["bid_err_increase_comma"]='{$smarty.const.bid_err_increase_comma}';
language["bid_err_must_be_greater_mybid"]='{$smarty.const.bid_err_must_be_greater_mybid}';
language["bid_err_must_be_greater"]='{$smarty.const.bid_err_must_be_greater}';
language["bid_err_terms"]='{$smarty.const.bid_err_terms}';
language["bid_bid_greater_than_bin"]='{$smarty.const.bid_bid_greater_than_bin}';
language["bid_my_bids"]='{$smarty.const.bid_my_bids}';
language["time_offset"]='{$smarty.const.time_offset}';

language["bid_confirm_close_auction"]='{$smarty.const.bid_confirm_close_auction}';
language["bid_err_bin_must_be_greater"]='{$smarty.const.bid_err_bin_must_be_greater}';
language["bid_auction_bin_zero"]='{$smarty.const.bid_auction_bin_zero}';
language["bid_err_title_valid"]='{$smarty.const.bid_err_title_valid}';
language["bid_err_published_valid"]='{$smarty.const.bid_err_published_valid}';
language["bid_err_auction_type_valid"]='{$smarty.const.bid_err_auction_type_valid}';
language["bid_err_payment_valid"]='{$smarty.const.bid_err_payment_valid}';
language["bid_err_reserve_price_valid"]='{$smarty.const.bid_err_reserve_price_valid}';
language["bid_err_min_increase_valid"]='{$smarty.const.bid_err_min_increase_valid}';
language["bid_err_start_date_valid"]='{$smarty.const.bid_err_start_date_valid}';
language["bid_err_end_date_valid"]='{$smarty.const.bid_err_end_date_valid}';
language["bid_err_initial_price_valid"]='{$smarty.const.bid_err_initial_price_valid}';
language["bid_err_initial_price_zero"]='{$smarty.const.bid_err_initial_price_zero}';
language["bin_js_alert"]='{$smarty.const.bin_js_alert}';
language["bid_maxpp"]='{$smarty.const.bid_maxpp}';
language["bid_bid_price"]='{$smarty.const.bid_bid_price}';
language["bid_err_max_valability"]='{$smarty.const.bid_not_valid_date_interval} : {$smarty.const.bid_opt_availability}';
language["bid_err_picture_is_required"]='{$smarty.const.bid_err_picture_is_required}';

{if $terms_and_conditions}
var must_accept_term= true;
{else}
var must_accept_term= false;
{/if}

var auction_currency='{$auction->currency_name}';

var bid_max_availability={$smarty.const.bid_opt_availability};
if(typeof Calendar != "undefined")
	Calendar._TT["DEF_DATE_FORMAT"]=dateformat('{$smarty.const.bid_opt_date_format}');
{literal}
function dateformat(php_format)
{
    d='y-mm-dd';
    if (php_format=='Y-m-d') d='y-mm-dd';
    if (php_format=='Y-d-m') d='y-dd-mm';
    if (php_format=='m/d/Y') d='mm/dd/y';
    if (php_format=='d/m/Y') d='dd/mm/y';
    if (php_format=='D, F d Y') d='y-mm-dd';

    return d;
}
{/literal}
</script>