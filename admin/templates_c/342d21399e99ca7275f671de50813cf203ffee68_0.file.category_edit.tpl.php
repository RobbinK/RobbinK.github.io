<?php
/* Smarty version 3.1.29, created on 2017-01-18 17:37:37
  from "/home/gbapi/public_html/lagged/admin/templates/category_edit.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_58801861a2d4b4_87014556',
  'file_dependency' => 
  array (
    '342d21399e99ca7275f671de50813cf203ffee68' => 
    array (
      0 => '/home/gbapi/public_html/lagged/admin/templates/category_edit.tpl',
      1 => 1484789856,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
  ),
),false)) {
function content_58801861a2d4b4_87014556 ($_smarty_tpl) {
if (!is_callable('smarty_modifier_capitalize')) require_once '/home/gbapi/public_html/lagged/libs/plugins/modifier.capitalize.php';
if (!is_callable('smarty_modifier_replace')) require_once '/home/gbapi/public_html/lagged/libs/plugins/modifier.replace.php';
$_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('title'=>'Edit Category'), 0, false);
?>

<div class="container">
<div class="row">
<div class="page-header">
<h4>Edit Category <?php echo stripslashes($_smarty_tpl->tpl_vars['game']->value['name']);?>
<a href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
/<?php echo $_smarty_tpl->tpl_vars['settings']->value['adminfolder'];?>
/category_edit.php?type=delete&id=<?php echo $_smarty_tpl->tpl_vars['tag']->value['id'];?>
" type="button" class="btn btn-danger" style="float:right" onclick="return confirm('Are you sure?')">Delete</a></h4>
</div>

<?php if ($_smarty_tpl->tpl_vars['errors']->value) {?><p class="bg-danger"><?php echo $_smarty_tpl->tpl_vars['errors']->value;?>
</p><?php }?>

<form method="post" action="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
/<?php echo $_smarty_tpl->tpl_vars['settings']->value['adminfolder'];?>
/category_edit.php?type=edit&id=<?php echo $_smarty_tpl->tpl_vars['tag']->value['id'];?>
&submt=true" id="loginit" enctype="multipart/form-data">
<div class="form-group">
<label for="exampleInputEmail1">Name</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="name" placeholder="name" value="<?php echo smarty_modifier_replace(smarty_modifier_capitalize($_smarty_tpl->tpl_vars['tag']->value['name']),'-',' ');?>
" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Description</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="descc" placeholder="name" value="<?php echo stripslashes($_smarty_tpl->tpl_vars['tag']->value['seodesc']);?>
" />
</div>

<button type="submit" class="btn btn-full btn-bigtxt btn-primary" style="float:left;clear:both">Submit</button>
</form>

<?php echo '</script'; ?>
><?php }
}
