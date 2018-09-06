<?php
/* Smarty version 3.1.29, created on 2017-01-18 17:37:40
  from "/home/gbapi/public_html/lagged/admin/templates/category.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_58801864043c19_75152862',
  'file_dependency' => 
  array (
    'ba70bb911c54383da653ebacba57404f605fbc8b' => 
    array (
      0 => '/home/gbapi/public_html/lagged/admin/templates/category.tpl',
      1 => 1484789849,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_58801864043c19_75152862 ($_smarty_tpl) {
if (!is_callable('smarty_modifier_capitalize')) require_once '/home/gbapi/public_html/lagged/libs/plugins/modifier.capitalize.php';
if (!is_callable('smarty_modifier_replace')) require_once '/home/gbapi/public_html/lagged/libs/plugins/modifier.replace.php';
$_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('title'=>'Categories'), 0, false);
?>


<div class="container">
<div class="row">
<div class="page-header">
<h4>Categories (<?php echo number_format($_smarty_tpl->tpl_vars['total_items']->value);?>
)</h4>

<?php if ($_smarty_tpl->tpl_vars['msg']->value) {?><p class="bg-success" style="padding:5px 10px"><?php echo $_smarty_tpl->tpl_vars['msg']->value;?>
</p><?php }?>

<button type="button" style="margin-bottom:10px" class="btn btn-primary" onclick="addcat()">Add Category</button>

<form style="display:none" method="post" action="?" id="loginit" enctype="multipart/form-data">
<div class="form-group">
<label for="exampleInputEmail1">Name</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="name" placeholder="name" value="" />
</div>
<div class="form-group">
<label for="exampleInputEmail2">Description</label>
<input type="text" class="form-control" id="exampleInputEmail2" name="desc" placeholder="description" value="" />
</div>
<button type="submit" class="btn btn-full btn-bigtxt btn-primary" style="float:left;clear:both;margin-bottom:10px">Submit</button>
</form>

<table class="table table-striped">
<thead> <tr><th>Name</th> <th>Description</th> <th>Edit/Delete</th> </tr> </thead>
<tbody>
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
<tr style="line-height:30px"> 
<td><a href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
/tag/<?php echo $_smarty_tpl->tpl_vars['g']->value['name'];?>
" target="_blank" style="line-height:30px"><?php echo smarty_modifier_replace(smarty_modifier_capitalize($_smarty_tpl->tpl_vars['g']->value['name']),'-',' ');?>
</a></td>
<td><?php echo $_smarty_tpl->tpl_vars['g']->value['seodesc'];?>
</td>
<td><a href="category_edit.php?type=edit&id=<?php echo $_smarty_tpl->tpl_vars['g']->value['id'];?>
">Modify</a></td>
</tr>
<?php
$_smarty_tpl->tpl_vars['g'] = $__foreach_g_0_saved_local_item;
}
if ($__foreach_g_0_saved_item) {
$_smarty_tpl->tpl_vars['g'] = $__foreach_g_0_saved_item;
}
?>
</tbody>
</table>


</div></div>
<?php echo '<script'; ?>
>
var addcat=function(){
	$('#loginit').toggle();
}
<?php echo '</script'; ?>
>
<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
