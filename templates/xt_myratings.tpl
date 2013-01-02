{* set the custom Auction CSS & Template CSS - after tabbing so we replace css in tabbed output*}
{set_css}
{if $task=='myratings'}<h2>{$smarty.const.bid_user_ratings} : {$user->username}</h2>{/if}
<table width="100%">
{if count($ratings)>0}
	 <tr>
		<th class="list_ratings_header">{$smarty.const.bid_username}</th>
		<th class="list_ratings_header">{$smarty.const.bid_bid_title}</th>
		<th class="list_ratings_header">{$smarty.const.bid_bid_rate}</th>
	 </tr>
    {section name=ratingsloop loop=$ratings}
	 	 <tr class="myrating{cycle values='0,1'}">
	 		<td width="15%" >
	 			<a href='{$mosConfig_live_site}/index.php?option=com_bids&task=ViewDetails&id={$ratings[ratingsloop]->voter}&Itemid={$Itemid}'>{$ratings[ratingsloop]->username}</a>
	 		</td>
	 		<td width="*%">
	 			<a href='{$mosConfig_live_site}/index.php?option=com_bids&task=viewbids&id={$ratings[ratingsloop]->auction_id}&Itemid={$Itemid}'>{$ratings[ratingsloop]->title}</a>
	 		</td>
	 		<td width="5%">
	 		    {$ratings[ratingsloop]->rating}
	 		</td>
	 	</tr>
	 	 <tr class="myrating{cycle values='0,1'}">
	 		<td colspan="3" >
	 		      <div class="msg_text">{$ratings[ratingsloop]->message}</div>
	 		</td>
		 </tr>
    {/section}
{else}
      <tr>
      	<td>{$smarty.const.bid_no_ratings}</td>
      </tr>
{/if}
</table>