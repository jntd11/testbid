{* search filters: $filters.keyword,$filters.users,$filters.startdate,$filters.enddate,$filters.tags *}
{if $filters|@count gt 0}
<span id="auction_searchdetails">
{if $task=='showSearchResults'}
    {$smarty.const.search_text}
{elseif $task=='tags'}
&nbsp;
{else}
    {$smarty.const.bid_filter} -
{/if}
{foreach from=$filters key=k item=filter}
    {$k}: {$filter}&nbsp;,
{/foreach}
{foreach from=$sfilters key=k item=filter}
    {if $sfilters.$k}
        <input type="hidden" name="{$k}" value="{$sfilters.$k}">
        {/if}
{/foreach}
</span>
{/if}