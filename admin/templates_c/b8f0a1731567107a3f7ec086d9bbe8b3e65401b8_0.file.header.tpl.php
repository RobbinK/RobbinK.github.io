<?php
/* Smarty version 3.1.29, created on 2017-01-18 16:26:21
  from "/home/gbapi/public_html/lagged/admin/templates/header.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_588007adc13fd1_44664833',
  'file_dependency' => 
  array (
    'b8f0a1731567107a3f7ec086d9bbe8b3e65401b8' => 
    array (
      0 => '/home/gbapi/public_html/lagged/admin/templates/header.tpl',
      1 => 1484785580,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_588007adc13fd1_44664833 ($_smarty_tpl) {
?>
<!DOCTYPE html><html lang="en"><head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
<?php echo '<script'; ?>
 src="https://code.jquery.com/jquery-3.1.0.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous"><?php echo '</script'; ?>
>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<?php echo '<script'; ?>
 src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"><?php echo '</script'; ?>
>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<style>
.toggle.btn {
    margin: 18px 0 0 9px;
}
</style>
</head><body>
<nav class="navbar navbar-default">
<div class="container-fluid">
<div class="navbar-header">
<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
<span class="sr-only">Toggle navigation</span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
</button>
<a class="navbar-brand" href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
/" target="_blank" title="<?php echo $_smarty_tpl->tpl_vars['settings']->value['sitename'];?>
"><?php echo $_smarty_tpl->tpl_vars['settings']->value['sitename'];?>
</a>
</div>
<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
<ul class="nav navbar-nav">
<li<?php if ($_smarty_tpl->tpl_vars['page']->value == 'home') {?> class="active"<?php }?>><a href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
/<?php echo $_smarty_tpl->tpl_vars['settings']->value['adminfolder'];?>
/">Admin Home</a></li>
<li<?php if ($_smarty_tpl->tpl_vars['page']->value == 'games') {?> class="active"<?php }?>><a href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
/<?php echo $_smarty_tpl->tpl_vars['settings']->value['adminfolder'];?>
/games.php">Games</a></li>
<li<?php if ($_smarty_tpl->tpl_vars['page']->value == 'cats') {?> class="active"<?php }?>><a href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
/<?php echo $_smarty_tpl->tpl_vars['settings']->value['adminfolder'];?>
/category.php">Categories</a></li>
<li<?php if ($_smarty_tpl->tpl_vars['page']->value == 'users') {?> class="active"<?php }?>><a href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
/<?php echo $_smarty_tpl->tpl_vars['settings']->value['adminfolder'];?>
/users.php">Users</a></li>
<li<?php if ($_smarty_tpl->tpl_vars['page']->value == 'msg') {?> class="active"<?php }?>><a href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
/<?php echo $_smarty_tpl->tpl_vars['settings']->value['adminfolder'];?>
/messages.php">Messages<?php if ($_smarty_tpl->tpl_vars['messages']->value > 0) {?> <span class="badge"><?php echo $_smarty_tpl->tpl_vars['messages']->value;?>
</span><?php }?></a></li>
<li<?php if ($_smarty_tpl->tpl_vars['page']->value == 'settings') {?> class="active"<?php }?>>
<a href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
/<?php echo $_smarty_tpl->tpl_vars['settings']->value['adminfolder'];?>
/theme.php">Theme</a></li>
<li<?php if ($_smarty_tpl->tpl_vars['page']->value == 'theme') {?> class="active"<?php }?>>
<a href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
/<?php echo $_smarty_tpl->tpl_vars['settings']->value['adminfolder'];?>
/settings.php">Settings</a></li>
<li<?php if ($_smarty_tpl->tpl_vars['page']->value == 'support') {?> class="active"<?php }?>><a href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
/<?php echo $_smarty_tpl->tpl_vars['settings']->value['adminfolder'];?>
/support.php">Support</a></li>
</ul>
<a href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
/logout" role="button" class="btn btn-default navbar-btn navbar-right">Log Out</a>
</div>
</nav>

<?php }
}
