<?php
/* Smarty version 3.1.29, created on 2017-01-18 16:02:23
  from "/home/gbapi/public_html/lagged/admin/templates/users.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5880020f40ac60_44801032',
  'file_dependency' => 
  array (
    'a9d6ee4102e57187f1a99a4f0bf49c77e9158977' => 
    array (
      0 => '/home/gbapi/public_html/lagged/admin/templates/users.tpl',
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
function content_5880020f40ac60_44801032 ($_smarty_tpl) {
$_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('title'=>'Users'), 0, false);
?>


<div class="container">
<div class="row">
<div class="page-header">
<h4>Users (<?php echo number_format($_smarty_tpl->tpl_vars['total_items']->value);?>
)</h4>

<form class="form-inline" action="?" method="get">
  <div class="form-group">
    <label class="sr-only" for="exampleInputAmount">Search</label>
    <div class="input-group">
      <div class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></div>
      <input type="text" placeholder="Search for a user" value="<?php echo $_smarty_tpl->tpl_vars['query']->value;?>
" class="form-control" id="query" name="query">
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Search</button>
</form>
</div>


<table class="table table-striped">
<thead> <tr><th>#</th> <th>Username</th> <th>Email</th> <th>Edit/Delete</th> </tr> </thead>
<tbody>
<?php
$_from = $_smarty_tpl->tpl_vars['users']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_u_0_saved_item = isset($_smarty_tpl->tpl_vars['u']) ? $_smarty_tpl->tpl_vars['u'] : false;
$_smarty_tpl->tpl_vars['u'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['u']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['u']->value) {
$_smarty_tpl->tpl_vars['u']->_loop = true;
$__foreach_u_0_saved_local_item = $_smarty_tpl->tpl_vars['u'];
?>
<tr style="line-height:30px"> 
<th scope=row><?php echo $_smarty_tpl->tpl_vars['u']->value['id'];?>
</th>
<td><a href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
/profile/<?php echo $_smarty_tpl->tpl_vars['u']->value['id'];?>
" target="_blank" style="line-height:30px"><img src="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
/images/avatars/<?php if ($_smarty_tpl->tpl_vars['u']->value['avatar']) {
echo $_smarty_tpl->tpl_vars['u']->value['avatar'];
} else { ?>default-avatar.jpg<?php }?>" width="30" height="30" /> <?php echo $_smarty_tpl->tpl_vars['u']->value['username'];?>
</a></td>
<td><?php echo $_smarty_tpl->tpl_vars['u']->value['user_email'];?>
</td>
<td><a href="users_edit.php?type=edit&id=<?php echo $_smarty_tpl->tpl_vars['u']->value['id'];?>
">Modify</a></td>
</tr>
<?php
$_smarty_tpl->tpl_vars['u'] = $__foreach_u_0_saved_local_item;
}
if ($__foreach_u_0_saved_item) {
$_smarty_tpl->tpl_vars['u'] = $__foreach_u_0_saved_item;
}
?>
</tbody>
</table>

<?php if ($_smarty_tpl->tpl_vars['total_pages']->value > 1) {?>
<div class="paging">
<div class="pagewrap">
<?php if ($_smarty_tpl->tpl_vars['p']->value != 1) {?>
<a href="users.php?<?php if ($_smarty_tpl->tpl_vars['query']->value) {?>query=<?php echo $_smarty_tpl->tpl_vars['query']->value;?>
&<?php }?>p=<?php echo $_smarty_tpl->tpl_vars['p']->value-1;?>
" class="btn btn-default">Prev</a>
<?php }
$__section_page_0_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_page']) ? $_smarty_tpl->tpl_vars['__smarty_section_page'] : false;
$__section_page_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['page_to']->value+1) ? count($_loop) : max(0, (int) $_loop));
$__section_page_0_start = (int)@$_smarty_tpl->tpl_vars['page_from']->value < 0 ? max(0, (int)@$_smarty_tpl->tpl_vars['page_from']->value + $__section_page_0_loop) : min((int)@$_smarty_tpl->tpl_vars['page_from']->value, $__section_page_0_loop);
$__section_page_0_total = min(($__section_page_0_loop - $__section_page_0_start), $__section_page_0_loop);
$_smarty_tpl->tpl_vars['__smarty_section_page'] = new Smarty_Variable(array());
if ($__section_page_0_total != 0) {
for ($__section_page_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_page']->value['index'] = $__section_page_0_start; $__section_page_0_iteration <= $__section_page_0_total; $__section_page_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_page']->value['index']++){
?>
<a href="users.php?<?php if ($_smarty_tpl->tpl_vars['query']->value) {?>query=<?php echo $_smarty_tpl->tpl_vars['query']->value;?>
&<?php }?>p=<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_page']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_page']->value['index'] : null);?>
" class="btn btn-default<?php if ($_smarty_tpl->tpl_vars['p']->value == (isset($_smarty_tpl->tpl_vars['__smarty_section_page']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_page']->value['index'] : null)) {?> active<?php }?>"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_page']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_page']->value['index'] : null);?>
</a>
<?php
}
}
if ($__section_page_0_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_page'] = $__section_page_0_saved;
}
if ($_smarty_tpl->tpl_vars['p']->value != $_smarty_tpl->tpl_vars['total_pages']->value) {?>
<a href="users.php?<?php if ($_smarty_tpl->tpl_vars['query']->value) {?>query=<?php echo $_smarty_tpl->tpl_vars['query']->value;?>
&<?php }?>p=<?php echo $_smarty_tpl->tpl_vars['p']->value+1;?>
" class="btn btn-default">Next</a>
<?php }?>
</div></div>
<?php }?>

</div></div>
<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
