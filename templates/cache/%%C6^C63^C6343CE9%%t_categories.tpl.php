<?php /* Smarty version 2.6.18, created on 2009-08-08 16:18:05
         compiled from t_categories.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'set_css', 't_categories.tpl', 1, false),)), $this); ?>
<?php echo smarty_set_css(array(), $this);?>

<h2><?php echo @bid_cat_head; ?>
</h2>
<?php if ($_GET['cat'] > 0): ?>
<a href="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/index.php?option=com_bids&task=listcats&Itemid=<?php echo $this->_tpl_vars['Itemid']; ?>
">All</a>
<?php endif; ?>
<table id="auction_categories" cellspacing="0" cellpadding="0">
<?php unset($this->_sections['category']);
$this->_sections['category']['name'] = 'category';
$this->_sections['category']['loop'] = is_array($_loop=$this->_tpl_vars['categories']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['category']['show'] = true;
$this->_sections['category']['max'] = $this->_sections['category']['loop'];
$this->_sections['category']['step'] = 1;
$this->_sections['category']['start'] = $this->_sections['category']['step'] > 0 ? 0 : $this->_sections['category']['loop']-1;
if ($this->_sections['category']['show']) {
    $this->_sections['category']['total'] = $this->_sections['category']['loop'];
    if ($this->_sections['category']['total'] == 0)
        $this->_sections['category']['show'] = false;
} else
    $this->_sections['category']['total'] = 0;
if ($this->_sections['category']['show']):

            for ($this->_sections['category']['index'] = $this->_sections['category']['start'], $this->_sections['category']['iteration'] = 1;
                 $this->_sections['category']['iteration'] <= $this->_sections['category']['total'];
                 $this->_sections['category']['index'] += $this->_sections['category']['step'], $this->_sections['category']['iteration']++):
$this->_sections['category']['rownum'] = $this->_sections['category']['iteration'];
$this->_sections['category']['index_prev'] = $this->_sections['category']['index'] - $this->_sections['category']['step'];
$this->_sections['category']['index_next'] = $this->_sections['category']['index'] + $this->_sections['category']['step'];
$this->_sections['category']['first']      = ($this->_sections['category']['iteration'] == 1);
$this->_sections['category']['last']       = ($this->_sections['category']['iteration'] == $this->_sections['category']['total']);
?>
<?php if ((1 & $this->_sections['category']['rownum'])): ?>
<tr>
<?php endif; ?>
	<td width="50%" id="auction_catcell" valign="top">
		<div id="auction_maincat" style="background:url(<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/images/cat_bg.gif); border:1px solid #FFFFFF; padding-left:2px;">
			<a href="<?php if ($this->_tpl_vars['categories'][$this->_sections['category']['index']]->kids > 0): ?><?php echo $this->_tpl_vars['categories'][$this->_sections['category']['index']]->view; ?>
<?php else: ?><?php echo $this->_tpl_vars['categories'][$this->_sections['category']['index']]->link; ?>
<?php endif; ?>" title="<?php if ($this->_tpl_vars['categories'][$this->_sections['category']['index']]->kids > 0): ?>Subcategories<?php else: ?>View listings<?php endif; ?>"><strong><?php echo $this->_tpl_vars['categories'][$this->_sections['category']['index']]->catname; ?>
</strong></a>
			<a href="index.php?option=com_bids&task=rss&cat=<?php echo $this->_tpl_vars['categories'][$this->_sections['category']['index']]->id; ?>
" target="_blank"><img src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/images/f_rss.jpg" width="10" border="0" alt="rss" /></a>
			<a style="font-size:12px !important;" href="<?php echo $this->_tpl_vars['categories'][$this->_sections['category']['index']]->link; ?>
"><img src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/images/category.gif" width="10" border="0" alt="view listings" /></a>
		</div>
		<div id="auction_subcat" style="background:#F5F5F5; border:1px solid #FFFFFF; " >
		<span style="font-size:12px;">Subcategories:   <?php echo $this->_tpl_vars['categories'][$this->_sections['category']['index']]->kids; ?>
 Auctions: <?php echo $this->_tpl_vars['categories'][$this->_sections['category']['index']]->nr_a; ?>
</span>
		<br />
		<?php unset($this->_sections['subcategory']);
$this->_sections['subcategory']['name'] = 'subcategory';
$this->_sections['subcategory']['loop'] = is_array($_loop=$this->_tpl_vars['categories'][$this->_sections['category']['index']]->subcategories) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['subcategory']['show'] = true;
$this->_sections['subcategory']['max'] = $this->_sections['subcategory']['loop'];
$this->_sections['subcategory']['step'] = 1;
$this->_sections['subcategory']['start'] = $this->_sections['subcategory']['step'] > 0 ? 0 : $this->_sections['subcategory']['loop']-1;
if ($this->_sections['subcategory']['show']) {
    $this->_sections['subcategory']['total'] = $this->_sections['subcategory']['loop'];
    if ($this->_sections['subcategory']['total'] == 0)
        $this->_sections['subcategory']['show'] = false;
} else
    $this->_sections['subcategory']['total'] = 0;
if ($this->_sections['subcategory']['show']):

            for ($this->_sections['subcategory']['index'] = $this->_sections['subcategory']['start'], $this->_sections['subcategory']['iteration'] = 1;
                 $this->_sections['subcategory']['iteration'] <= $this->_sections['subcategory']['total'];
                 $this->_sections['subcategory']['index'] += $this->_sections['subcategory']['step'], $this->_sections['subcategory']['iteration']++):
$this->_sections['subcategory']['rownum'] = $this->_sections['subcategory']['iteration'];
$this->_sections['subcategory']['index_prev'] = $this->_sections['subcategory']['index'] - $this->_sections['subcategory']['step'];
$this->_sections['subcategory']['index_next'] = $this->_sections['subcategory']['index'] + $this->_sections['subcategory']['step'];
$this->_sections['subcategory']['first']      = ($this->_sections['subcategory']['iteration'] == 1);
$this->_sections['subcategory']['last']       = ($this->_sections['subcategory']['iteration'] == $this->_sections['subcategory']['total']);
?>
			<a href="<?php if ($this->_tpl_vars['categories'][$this->_sections['category']['index']]->subcategories[$this->_sections['subcategory']['index']]->kids > 0): ?><?php echo $this->_tpl_vars['categories'][$this->_sections['category']['index']]->subcategories[$this->_sections['subcategory']['index']]->view; ?>
<?php else: ?><?php echo $this->_tpl_vars['categories'][$this->_sections['category']['index']]->subcategories[$this->_sections['subcategory']['index']]->link; ?>
<?php endif; ?>"><?php echo $this->_tpl_vars['categories'][$this->_sections['category']['index']]->subcategories[$this->_sections['subcategory']['index']]->catname; ?>
 </a> (<?php echo $this->_tpl_vars['categories'][$this->_sections['category']['index']]->subcategories[$this->_sections['subcategory']['index']]->kids; ?>
 subcats ) (<?php echo $this->_tpl_vars['categories'][$this->_sections['category']['index']]->subcategories[$this->_sections['subcategory']['index']]->nr_a; ?>
 auctions)
    		<a href="index.php?option=com_bids&task=rss&cat=<?php echo $this->_tpl_vars['categories'][$this->_sections['category']['index']]->subcategories[$this->_sections['subcategory']['index']]->id; ?>
" target="_blank"><img src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/images/f_rss.jpg" width="10" border="0" alt="rss" /></a> <a style="font-size:9px !important;" href="<?php echo $this->_tpl_vars['categories'][$this->_sections['category']['index']]->subcategories[$this->_sections['subcategory']['index']]->link; ?>
"><img src="<?php echo $this->_tpl_vars['mosConfig_live_site']; ?>
/components/com_bids/images/category.gif" width="10" border="0" alt="view listings" /></a>
			<br />
		<?php endfor; endif; ?>
		</div>
	</td>
<?php if (!((1 & $this->_sections['category']['rownum']))): ?>
</tr>
<?php endif; ?>
<?php endfor; endif; ?>
</table>