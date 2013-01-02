<?php /* Smarty version 2.6.18, created on 2009-12-06 18:36:11
         compiled from t_javascript_language.tpl */ ?>
<script language="javascript" type="text/javascript">
var language=Array();
language["bid_err_empty_bid"]='<?php echo @bid_err_empty_bid; ?>
';
language["bid_err_increase"]='<?php echo @bid_err_increase; ?>
';
language["bid_err_increase_comma"]='<?php echo @bid_err_increase_comma; ?>
';
language["bid_err_must_be_greater_mybid"]='<?php echo @bid_err_must_be_greater_mybid; ?>
';
language["bid_err_must_be_greater"]='<?php echo @bid_err_must_be_greater; ?>
';
language["bid_err_terms"]='<?php echo @bid_err_terms; ?>
';
language["bid_bid_greater_than_bin"]='<?php echo @bid_bid_greater_than_bin; ?>
';
language["bid_my_bids"]='<?php echo @bid_my_bids; ?>
';
language["time_offset"]='<?php echo @time_offset; ?>
';

language["bid_confirm_close_auction"]='<?php echo @bid_confirm_close_auction; ?>
';
language["bid_err_bin_must_be_greater"]='<?php echo @bid_err_bin_must_be_greater; ?>
';
language["bid_auction_bin_zero"]='<?php echo @bid_auction_bin_zero; ?>
';
language["bid_err_title_valid"]='<?php echo @bid_err_title_valid; ?>
';
language["bid_err_published_valid"]='<?php echo @bid_err_published_valid; ?>
';
language["bid_err_auction_type_valid"]='<?php echo @bid_err_auction_type_valid; ?>
';
language["bid_err_payment_valid"]='<?php echo @bid_err_payment_valid; ?>
';
language["bid_err_reserve_price_valid"]='<?php echo @bid_err_reserve_price_valid; ?>
';
language["bid_err_min_increase_valid"]='<?php echo @bid_err_min_increase_valid; ?>
';
language["bid_err_start_date_valid"]='<?php echo @bid_err_start_date_valid; ?>
';
language["bid_err_end_date_valid"]='<?php echo @bid_err_end_date_valid; ?>
';
language["bid_err_initial_price_valid"]='<?php echo @bid_err_initial_price_valid; ?>
';
language["bid_err_initial_price_zero"]='<?php echo @bid_err_initial_price_zero; ?>
';
language["bin_js_alert"]='<?php echo @bin_js_alert; ?>
';
language["bid_maxpp"]='<?php echo @bid_maxpp; ?>
';
language["bid_bid_price"]='<?php echo @bid_bid_price; ?>
';
language["bid_err_max_valability"]='<?php echo @bid_not_valid_date_interval; ?>
 : <?php echo @bid_opt_availability; ?>
';
language["bid_err_picture_is_required"]='<?php echo @bid_err_picture_is_required; ?>
';

<?php if ($this->_tpl_vars['terms_and_conditions']): ?>
var must_accept_term= true;
<?php else: ?>
var must_accept_term= false;
<?php endif; ?>

var auction_currency='<?php echo $this->_tpl_vars['auction']->currency_name; ?>
';

var bid_max_availability=<?php echo @bid_opt_availability; ?>
;
if(typeof Calendar != "undefined")
	Calendar._TT["DEF_DATE_FORMAT"]=dateformat('<?php echo @bid_opt_date_format; ?>
');
<?php echo '
function dateformat(php_format)
{
    d=\'y-mm-dd\';
    if (php_format==\'Y-m-d\') d=\'y-mm-dd\';
    if (php_format==\'Y-d-m\') d=\'y-dd-mm\';
    if (php_format==\'m/d/Y\') d=\'mm/dd/y\';
    if (php_format==\'d/m/Y\') d=\'dd/mm/y\';
    if (php_format==\'D, F d Y\') d=\'y-mm-dd\';

    return d;
}
'; ?>

</script>