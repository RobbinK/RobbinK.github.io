<?php
/* Smarty version 3.1.29, created on 2017-01-18 15:41:32
  from "/home/gbapi/public_html/lagged/admin/templates/messages.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_587ffd2cc208f4_57685738',
  'file_dependency' => 
  array (
    '18567e16cfe41f269a8834e51515c1bf66a7951f' => 
    array (
      0 => '/home/gbapi/public_html/lagged/admin/templates/messages.tpl',
      1 => 1484601531,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_587ffd2cc208f4_57685738 ($_smarty_tpl) {
$_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('title'=>'Messages'), 0, false);
?>


<div class="container">
<div class="row">
<div class="page-header">
<h4>Messages</h4>
</div>

<table class="table table-striped">
<thead> <tr> <th>#</th> <th>Email</th> <th>Title</th> <th>Message</th> </tr> </thead>
<tbody>
<?php
$_from = $_smarty_tpl->tpl_vars['results']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_m_0_saved_item = isset($_smarty_tpl->tpl_vars['m']) ? $_smarty_tpl->tpl_vars['m'] : false;
$_smarty_tpl->tpl_vars['m'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['m']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['m']->value) {
$_smarty_tpl->tpl_vars['m']->_loop = true;
$__foreach_m_0_saved_local_item = $_smarty_tpl->tpl_vars['m'];
?>
<tr> 
	<th scope=row><?php echo $_smarty_tpl->tpl_vars['m']->value['id'];?>
</th>
	<td><a href="mailto:<?php echo $_smarty_tpl->tpl_vars['m']->value['email'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['m']->value['email'];?>
</a></td>
	<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['m']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
</td>
	<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['m']->value['message'], ENT_QUOTES, 'UTF-8', true);?>
</td>
</tr>
<?php
$_smarty_tpl->tpl_vars['m'] = $__foreach_m_0_saved_local_item;
}
if ($__foreach_m_0_saved_item) {
$_smarty_tpl->tpl_vars['m'] = $__foreach_m_0_saved_item;
}
?>
</tbody>
</table>

</div></div>
<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
