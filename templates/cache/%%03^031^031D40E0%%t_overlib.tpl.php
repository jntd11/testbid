<?php /* Smarty version 2.6.18, created on 2009-08-03 13:07:03
         compiled from t_overlib.tpl */ ?>
<?php echo '
<script language="javascript">
  if ( !document.getElementById(\'overDiv\') ) { 
     document.writeln(\'<div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>\'); 
     '; ?>

     document.writeln('<scr'+'ipt language="Javascript" src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/includes/js/overlib_mini.js"></scr'+'ipt>'); 
     <?php echo '
	}
</script>
'; ?>