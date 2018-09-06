<?php
/* Smarty version 3.1.29, created on 2017-01-18 15:41:28
  from "/home/gbapi/public_html/lagged/admin/templates/gamefeeds.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_587ffd2825ced6_55890187',
  'file_dependency' => 
  array (
    'ae37783ccc26739d30c802e370f73180268f6051' => 
    array (
      0 => '/home/gbapi/public_html/lagged/admin/templates/gamefeeds.tpl',
      1 => 1484601529,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_587ffd2825ced6_55890187 ($_smarty_tpl) {
if (!is_callable('smarty_modifier_truncate')) require_once '/home/gbapi/public_html/lagged/libs/plugins/modifier.truncate.php';
$_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('title'=>ucfirst($_smarty_tpl->tpl_vars['feed']->value)), 0, false);
?>

<div class="container">
<div class="row">
<div class="page-header">
<h4><?php echo ucfirst($_smarty_tpl->tpl_vars['feed']->value);?>
 Feed</h4>
</div>
<p>Preview or install a game. <small>If game is already installed it will be faded.</small></p>
<div class="row">
<?php
$_from = $_smarty_tpl->tpl_vars['games']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_g_0_saved_item = isset($_smarty_tpl->tpl_vars['g']) ? $_smarty_tpl->tpl_vars['g'] : false;
$_smarty_tpl->tpl_vars['g'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['g']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['g']->value) {
$_smarty_tpl->tpl_vars['g']->_loop = true;
$__foreach_g_0_saved_local_item = $_smarty_tpl->tpl_vars['g'];
?>
<div class="col-sm-6 col-md-4">
<div class="thumbnail"<?php if ($_smarty_tpl->tpl_vars['g']->value['installed'] == 1) {?> style="opacity: 0.3"<?php }?>>
<a href="<?php echo $_smarty_tpl->tpl_vars['g']->value['swf'];?>
" class="imgwrapclick" target="_blank"><img src="<?php echo $_smarty_tpl->tpl_vars['g']->value['thumb'];?>
" alt="<?php echo stripslashes($_smarty_tpl->tpl_vars['g']->value['name']);?>
" /></a>
<div class="caption">
<h3><a href="<?php echo $_smarty_tpl->tpl_vars['g']->value['swf'];?>
" target="_blank"><?php echo smarty_modifier_truncate(stripslashes($_smarty_tpl->tpl_vars['g']->value['name']),27,"..",true);?>
</a></h3>
<p><a href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
/<?php echo $_smarty_tpl->tpl_vars['settings']->value['adminfolder'];?>
/install.php?source=feed&id=<?php echo $_smarty_tpl->tpl_vars['g']->value['id'];?>
" class="btn btn-primary" role="button">Install</a></p>
</div>
</div>
</div>
<?php
$_smarty_tpl->tpl_vars['g'] = $__foreach_g_0_saved_local_item;
}
if ($__foreach_g_0_saved_item) {
$_smarty_tpl->tpl_vars['g'] = $__foreach_g_0_saved_item;
}
?>
</div></div>
<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
