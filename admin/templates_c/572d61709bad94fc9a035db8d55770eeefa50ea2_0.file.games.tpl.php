<?php
/* Smarty version 3.1.29, created on 2017-01-18 15:58:03
  from "/home/gbapi/public_html/lagged/admin/templates/games.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5880010b7bc254_48793677',
  'file_dependency' => 
  array (
    '572d61709bad94fc9a035db8d55770eeefa50ea2' => 
    array (
      0 => '/home/gbapi/public_html/lagged/admin/templates/games.tpl',
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
function content_5880010b7bc254_48793677 ($_smarty_tpl) {
$_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('title'=>'Games'), 0, false);
?>


<div class="container">
<div class="row">
<div class="page-header">
<h4>Games (<?php echo number_format($_smarty_tpl->tpl_vars['total_items']->value);?>
)</h4>

<form class="form-inline" action="?" method="get">
  <div class="form-group">
    <label class="sr-only" for="exampleInputAmount">Search</label>
    <div class="input-group">
      <div class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></div>
      <input type="text" placeholder="Search for a game" value="<?php echo $_smarty_tpl->tpl_vars['query']->value;?>
" class="form-control" id="query" name="query">
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Search</button>
</form>
</div>

<?php if ($_smarty_tpl->tpl_vars['msg']->value) {?><p class="bg-success" style="padding:5px 10px"><?php echo $_smarty_tpl->tpl_vars['msg']->value;?>
</p><?php }?>

<table class="table table-striped">
<thead> <tr><th>#</th> <th>Name</th> <th>Plays</th> <th>Edit/Delete</th> </tr> </thead>
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
<th scope=row><?php echo $_smarty_tpl->tpl_vars['g']->value['id'];?>
</th>
<td><a href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
/play/<?php echo $_smarty_tpl->tpl_vars['g']->value['id'];?>
/" target="_blank" style="line-height:30px"><img src="<?php echo $_smarty_tpl->tpl_vars['settings']->value['imgcdn'];?>
/<?php echo $_smarty_tpl->tpl_vars['g']->value['thumb'];?>
" width="30" height="30" /> <?php echo $_smarty_tpl->tpl_vars['g']->value['name'];?>
</a></td>
<td><?php echo number_format($_smarty_tpl->tpl_vars['g']->value['play_count']);?>
</td>
<td><a href="games_edit.php?type=edit&id=<?php echo $_smarty_tpl->tpl_vars['g']->value['id'];?>
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

<?php if ($_smarty_tpl->tpl_vars['total_pages']->value > 1) {?>
<div class="paging">
<div class="pagewrap">
<?php if ($_smarty_tpl->tpl_vars['p']->value != 1) {?>
<a href="games.php?<?php if ($_smarty_tpl->tpl_vars['query']->value) {?>query=<?php echo $_smarty_tpl->tpl_vars['query']->value;?>
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
<a href="games.php?<?php if ($_smarty_tpl->tpl_vars['query']->value) {?>query=<?php echo $_smarty_tpl->tpl_vars['query']->value;?>
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
<a href="games.php?<?php if ($_smarty_tpl->tpl_vars['query']->value) {?>query=<?php echo $_smarty_tpl->tpl_vars['query']->value;?>
&<?php }?>p=<?php echo $_smarty_tpl->tpl_vars['p']->value+1;?>
" class="btn btn-default">Next</a>
<?php }?>
</div></div>
<?php }?>

</div></div>
<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
