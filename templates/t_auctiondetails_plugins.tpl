{if $auction->is_my_auction && ($auction->featured=='none' || !$auction->featured) && !$auction->expired && !$auction->close_offer}

{assign var="gold_auction" value="$mosConfig_live_site/index.php?option=com_bids&task=set_featured&featured=gold&id=`$auction->id`"}
{assign var="silver_auction" value="$mosConfig_live_site/index.php?option=com_bids&task=set_featured&featured=silver&id=`$auction->id`"}
{assign var="bronze_auction" value="$mosConfig_live_site/index.php?option=com_bids&task=set_featured&featured=bronze&id=`$auction->id`"}
    {if $pricing_plugins.featured_gold || $pricing_plugins.featured_silver || $pricing_plugins.featured_bronze}
        <table>
            <tr>
                <td colspan="3">{$smarty.const.bid_upgrade_listing}:</td>
            </tr>
            <tr>
            {if $pricing_plugins.featured_gold}
             <td><input type="button" onclick="window.location = '{$gold_auction}';" class="back_button" value="{$smarty.const.bid_payment_featured_gold}">
             </td>
            {/if}
            {if $pricing_plugins.featured_silver}
             <td>
                 <input type="button" onclick="window.location = '{$silver_auction}';" class="back_button" value="{$smarty.const.bid_payment_featured_silver}">
             </td>
            {/if}
            {if $pricing_plugins.featured_bronze}
             <td>
                 <input type="button" onclick="window.location = '{$bronze_auction}';" class="back_button" value="{$smarty.const.bid_payment_featured_bronze}">
             </td>
            {/if}
            </tr>
        </table>
    {/if}
{/if}