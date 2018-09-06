<?php
/* Smarty version 3.1.29, created on 2017-01-18 15:39:44
  from "/home/gbapi/public_html/lagged/admin/templates/login.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_587ffcc058d031_82790707',
  'file_dependency' => 
  array (
    '077dcbb85bc188b08d90eb57c6f90455be049231' => 
    array (
      0 => '/home/gbapi/public_html/lagged/admin/templates/login.tpl',
      1 => 1484601530,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_587ffcc058d031_82790707 ($_smarty_tpl) {
$_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('title'=>'Admin Login'), 0, false);
?>


<div class="container">
<div class="row">
<div class="page-header">
<h4>Admin Login</h4>
</div>

<?php if ($_smarty_tpl->tpl_vars['errors']->value) {?><p class="bg-danger"><?php echo $_smarty_tpl->tpl_vars['errors']->value;?>
</p><?php }?>

<form method="post" action="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
/<?php echo $_smarty_tpl->tpl_vars['settings']->value['adminfolder'];?>
/login.php?submt" id="loginit">
  <div class="form-group">
    <label for="exampleInputPassword1">Admin Password</label>
    <input autofocus type="password" class="form-control" id="exampleInputPassword1" name="user_password" placeholder="Password" autocomplete="off" required>
  </div>
<button type="submit" class="btn btn-full btn-bigtxt btn-primary">Log in</button>
</form>

</div></div>
<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
