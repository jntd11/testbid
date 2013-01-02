{* Load Overlib *}
{literal}
<script language="javascript">
  if ( !document.getElementById('overDiv') ) { 
     document.writeln('<div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>'); 
     {/literal}
     document.writeln('<scr'+'ipt language="Javascript" src="{$mosConfig_live_site}/includes/js/overlib_mini.js"></scr'+'ipt>'); 
     {literal}
	}
</script>
{/literal}