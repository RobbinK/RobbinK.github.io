<?php
/* Smarty version 3.1.29, created on 2017-01-18 16:22:05
  from "/home/gbapi/public_html/lagged/admin/templates/support.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_588006ad3f9a64_81432231',
  'file_dependency' => 
  array (
    '727ecb53163a3e8b217b6a39049bb194dba23af7' => 
    array (
      0 => '/home/gbapi/public_html/lagged/admin/templates/support.tpl',
      1 => 1484785324,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_588006ad3f9a64_81432231 ($_smarty_tpl) {
$_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('title'=>'Support'), 0, false);
?>


<div class="container">
<div class="row">
<div class="page-header">
<h3>Support</h3>
</div>

<h4>Report Bugs/Request Features</h4>
<p>Contact us to report bugs, request features</p>
<p><b>dab@lagged.com</b></p>
<br><br>
<h4>Implement our API</h4>
<p>Implement our high score and achievement api into your games. Follow the link for detailed instructions</p>
<a type="button" href="http://pub.lagged.com/api" target="_blank" class="btn btn-primary btn-lg">API Docs</a>
<br><br>
<h4>Check for Updates</h4>
<p>Are you up to date?</p>
<a type="button" href="http://pub.lagged.com/update?v=3" target="_blank" class="btn btn-primary btn-lg">Check Updates</a>
</div></div>
<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
