<?php
/* Smarty version 3.1.29, created on 2017-01-18 16:07:06
  from "/home/gbapi/public_html/lagged/admin/templates/games_edit.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5880032a3e3828_82175086',
  'file_dependency' => 
  array (
    'de001a2d7a3ecaa73cf021ff0bfa28c362f30ce8' => 
    array (
      0 => '/home/gbapi/public_html/lagged/admin/templates/games_edit.tpl',
      1 => 1484784315,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_5880032a3e3828_82175086 ($_smarty_tpl) {
if (!is_callable('smarty_modifier_capitalize')) require_once '/home/gbapi/public_html/lagged/libs/plugins/modifier.capitalize.php';
if (!is_callable('smarty_modifier_replace')) require_once '/home/gbapi/public_html/lagged/libs/plugins/modifier.replace.php';
$_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('title'=>'Edit Game'), 0, false);
?>

<div class="container">
<div class="row">
<div class="page-header">
<h4>Edit Game <?php echo stripslashes($_smarty_tpl->tpl_vars['game']->value['name']);?>
<a href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
/<?php echo $_smarty_tpl->tpl_vars['settings']->value['adminfolder'];?>
/games_edit.php?type=delete&id=<?php echo $_smarty_tpl->tpl_vars['game']->value['id'];?>
" type="button" class="btn btn-danger" style="float:right" onclick="return confirm('Are you sure?')">Delete</a></h4>
</div>

<?php if ($_smarty_tpl->tpl_vars['errors']->value) {?><p class="bg-danger"><?php echo $_smarty_tpl->tpl_vars['errors']->value;?>
</p><?php }
if ($_smarty_tpl->tpl_vars['msg']->value && $_smarty_tpl->tpl_vars['gamelink']->value) {?><p class="bg-success"><?php echo $_smarty_tpl->tpl_vars['msg']->value;?>
<br>Play it: <a href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
/en/g/<?php echo $_smarty_tpl->tpl_vars['gamelink']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['game_name']->value;?>
</a></p><?php } else { ?>
<form method="post" action="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
/<?php echo $_smarty_tpl->tpl_vars['settings']->value['adminfolder'];?>
/games_edit.php?type=edit&id=<?php echo $_smarty_tpl->tpl_vars['game']->value['id'];?>
&submt=true" id="loginit" enctype="multipart/form-data">
<div class="form-group">
<label for="exampleInputEmail1">Name</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="name" placeholder="name" value="<?php echo stripslashes($_smarty_tpl->tpl_vars['game']->value['name']);?>
" />
</div>


<div class="form-group">
<label class="custom">Category</label>
<select name="cat_id">
<?php
$_from = $_smarty_tpl->tpl_vars['categoriesloop']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_cg_0_saved_item = isset($_smarty_tpl->tpl_vars['cg']) ? $_smarty_tpl->tpl_vars['cg'] : false;
$_smarty_tpl->tpl_vars['cg'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['cg']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['cg']->value) {
$_smarty_tpl->tpl_vars['cg']->_loop = true;
$__foreach_cg_0_saved_local_item = $_smarty_tpl->tpl_vars['cg'];
?>
<option value="<?php echo $_smarty_tpl->tpl_vars['cg']->value['name'];?>
" <?php if ($_smarty_tpl->tpl_vars['game']->value['cat_id'] == $_smarty_tpl->tpl_vars['cg']->value['name']) {?> selected<?php }?>><?php echo smarty_modifier_replace(smarty_modifier_capitalize($_smarty_tpl->tpl_vars['cg']->value['name']),'-',' ');?>
</option>
<?php
$_smarty_tpl->tpl_vars['cg'] = $__foreach_cg_0_saved_local_item;
}
if ($__foreach_cg_0_saved_item) {
$_smarty_tpl->tpl_vars['cg'] = $__foreach_cg_0_saved_item;
}
?>
</select>
</div>

<div class="form-group">
<label for="exampleInputEmail3">Tags (comma seperated)</label>
<input type="text" class="form-control" id="exampleInputEmail3" name="tags" placeholder="tags" value="<?php echo $_smarty_tpl->tpl_vars['game']->value['tags'];?>
" required />
</div>

<div class="form-group">
<label for="exampleInputEmail5">Description</label><br>
<textarea name="description" cols="50" rows="5" id="exampleInputEmail5" required><?php echo stripslashes($_smarty_tpl->tpl_vars['game']->value['description']);?>
</textarea>
</div>

<div class="form-group">
<label for="exampleInputEmail6">Instructions</label><br>
<textarea id="exampleInputEmail6" name="instructions" cols="50" rows="5" required><?php echo stripslashes($_smarty_tpl->tpl_vars['game']->value['instructions']);?>
</textarea>
</div>

<div class="form-group" style="float:left;width:215px;height:auto">
<label for="exampleInputEmail6">Change Thumbnail (Optional)</label><br>
<input type="file" name="avaimage" id="slim">
</div>

<div class="form-group" style="float:left;clear:both">
<label for="exampleInputEmail8">Game link</label><br/>
<textarea name="embed" cols="50" rows="3" required id="exampleInputEmail8"><?php echo $_smarty_tpl->tpl_vars['game']->value['swf'];?>
</textarea>
</div>

<div class="form-group" style="float:left;clear:both">
<label class="checkbox-inline">
  <input type="checkbox" id="inlineCheckbox1" name="feature" value="1" <?php if ($_smarty_tpl->tpl_vars['game']->value['feature'] == 1) {?>checked<?php }?>> Feature
</label>
<label class="checkbox-inline">
  <input type="checkbox" id="inlineCheckbox2" name="hasscores" value="1" <?php if ($_smarty_tpl->tpl_vars['game']->value['has_scores'] == 1) {?>checked<?php }?>> Scores
</label>
<label class="checkbox-inline">
  <input type="checkbox" id="inlineCheckbox3" name="hasawards" value="1" <?php if ($_smarty_tpl->tpl_vars['game']->value['has_achs'] == 1) {?>checked<?php }?>> Awards
</label>
<label class="checkbox-inline">
  <input type="checkbox" id="inlineCheckbox1" name="ads" value="1" <?php if ($_smarty_tpl->tpl_vars['game']->value['ads'] == 1) {?>checked<?php }?>> Ads
</label>
</div>

<div id="highscorediv" class="form-group" style="float:left;clear:both;<?php if ($_smarty_tpl->tpl_vars['game']->value['has_scores'] == 0) {?>display:none<?php }?>">
<label>High score Boards</label>
<?php if ($_smarty_tpl->tpl_vars['game']->value['has_scores'] == 1) {
$_from = $_smarty_tpl->tpl_vars['boards']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_br_1_saved_item = isset($_smarty_tpl->tpl_vars['br']) ? $_smarty_tpl->tpl_vars['br'] : false;
$_smarty_tpl->tpl_vars['br'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['br']->index=-1;
$_smarty_tpl->tpl_vars['br']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['br']->value) {
$_smarty_tpl->tpl_vars['br']->_loop = true;
$_smarty_tpl->tpl_vars['br']->index++;
$__foreach_br_1_saved_local_item = $_smarty_tpl->tpl_vars['br'];
?>
<div class="form-group form-inline">
<input type="text" class="form-control" name="scoreboard[<?php echo $_smarty_tpl->tpl_vars['br']->index;?>
][0]" placeholder="Board id #1" value="<?php echo $_smarty_tpl->tpl_vars['br']->value['board_id'];?>
" />
<input type="text" class="form-control" name="scoreboard[<?php echo $_smarty_tpl->tpl_vars['br']->index;?>
][1]" placeholder="Board Name" value="<?php echo $_smarty_tpl->tpl_vars['br']->value['name'];?>
" />
<input type="hidden" class="form-control" name="scoreboard[<?php echo $_smarty_tpl->tpl_vars['br']->index;?>
][2]" placeholder="Board Name" value="<?php echo $_smarty_tpl->tpl_vars['br']->value['id'];?>
" />
</div>
<?php
$_smarty_tpl->tpl_vars['br'] = $__foreach_br_1_saved_local_item;
}
if ($__foreach_br_1_saved_item) {
$_smarty_tpl->tpl_vars['br'] = $__foreach_br_1_saved_item;
}
?>

<?php } else { ?>
<div class="form-group form-inline">
<input type="text" class="form-control" name="scoreboard[0][0]" placeholder="Board id #1"  />
<input type="text" class="form-control" name="scoreboard[0][1]" placeholder="Board Name"  />
</div>

<div class="form-group form-inline">
<input type="text" class="form-control" name="scoreboard[1][0]" placeholder="Board id #2"  />
<input type="text" class="form-control" name="scoreboard[1][1]" placeholder="Board Name"  />
</div>

<div class="form-group form-inline">
<input type="text" class="form-control" name="scoreboard[2][0]" placeholder="Board id #3"  />
<input type="text" class="form-control" name="scoreboard[2][1]" placeholder="Board Name"  />
</div>
<?php }?>
</div>

<div id="awardsdiv" class="form-group" style="float:left;clear:both;<?php if ($_smarty_tpl->tpl_vars['game']->value['has_achs'] == 0) {?>display:none<?php }?>">
<label>Awards</label>
<?php if ($_smarty_tpl->tpl_vars['game']->value['has_achs'] == 1) {
$_from = $_smarty_tpl->tpl_vars['awards']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_award_2_saved_item = isset($_smarty_tpl->tpl_vars['award']) ? $_smarty_tpl->tpl_vars['award'] : false;
$_smarty_tpl->tpl_vars['award'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['award']->index=-1;
$_smarty_tpl->tpl_vars['award']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['award']->value) {
$_smarty_tpl->tpl_vars['award']->_loop = true;
$_smarty_tpl->tpl_vars['award']->index++;
$__foreach_award_2_saved_local_item = $_smarty_tpl->tpl_vars['award'];
?>
<div class="form-group form-inline">
<input type="text" name="award[<?php echo $_smarty_tpl->tpl_vars['award']->index;?>
][0]" class="form-control" placeholder="Award ID" value="<?php echo $_smarty_tpl->tpl_vars['award']->value['ach_id'];?>
">
<input type="text" name="award[<?php echo $_smarty_tpl->tpl_vars['award']->index;?>
][1]" class="form-control" placeholder="Award Name" value="<?php echo $_smarty_tpl->tpl_vars['award']->value['name'];?>
">
<input type="text" name="award[<?php echo $_smarty_tpl->tpl_vars['award']->index;?>
][2]" class="form-control" placeholder="Points" value="<?php echo $_smarty_tpl->tpl_vars['award']->value['points'];?>
">
<input type="text" name="award[<?php echo $_smarty_tpl->tpl_vars['award']->index;?>
][3]" class="form-control" placeholder="Description" value="<?php echo $_smarty_tpl->tpl_vars['award']->value['textdesc'];?>
">
<input type="hidden" class="form-control" name="award[<?php echo $_smarty_tpl->tpl_vars['award']->index;?>
][4]" placeholder="Board Name" value="<?php echo $_smarty_tpl->tpl_vars['award']->value['id'];?>
" />
</div>
<?php
$_smarty_tpl->tpl_vars['award'] = $__foreach_award_2_saved_local_item;
}
if ($__foreach_award_2_saved_item) {
$_smarty_tpl->tpl_vars['award'] = $__foreach_award_2_saved_item;
}
} else { ?>
<div class="form-group form-inline">
<input type="text" name="award[0][0]" class="form-control" placeholder="Award ID #1">
<input type="text" name="award[0][1]" class="form-control" placeholder="Award Name">
<input type="text" name="award[0][2]" class="form-control" placeholder="Points">
<input type="text" name="award[0][3]" class="form-control" placeholder="Description">
</div>

<div class="form-group form-inline">
<input type="text" name="award[1][0]" class="form-control" placeholder="Award ID #2">
<input type="text" name="award[1][1]" class="form-control" placeholder="Award Name">
<input type="text" name="award[1][2]" class="form-control" placeholder="Points">
<input type="text" name="award[1][3]" class="form-control" placeholder="Description">
</div>

<div class="form-group form-inline">
<input type="text" name="award[2][0]" class="form-control" placeholder="Award ID #3">
<input type="text" name="award[2][1]" class="form-control" placeholder="Award Name">
<input type="text" name="award[2][2]" class="form-control" placeholder="Points">
<input type="text" name="award[2][3]" class="form-control" placeholder="Description">
</div>

<div class="form-group form-inline">
<input type="text" name="award[3][0]" class="form-control" placeholder="Award ID #4">
<input type="text" name="award[3][1]" class="form-control" placeholder="Award Name">
<input type="text" name="award[3][2]" class="form-control" placeholder="Points">
<input type="text" name="award[3][3]" class="form-control" placeholder="Description">
</div>

<div class="form-group form-inline">
<input type="text" name="award[4][0]" class="form-control" placeholder="Award ID #5">
<input type="text" name="award[4][1]" class="form-control" placeholder="Award Name">
<input type="text" name="award[4][2]" class="form-control" placeholder="Points">
<input type="text" name="award[4][3]" class="form-control" placeholder="Description">
</div>
<?php }?>
</div>

<button type="submit" class="btn btn-full btn-bigtxt btn-primary" style="float:left;clear:both">Submit</button>
</form>
<?php }?>
</div></div>
<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<link href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
/libs/imagecrop/slim.css?v=1" rel="stylesheet">
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
/libs/imagecrop/slim.kickstart.js?v=1"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 data-rocketsrc="https://code.jquery.com/jquery-3.1.0.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous" type="text/rocketscript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
$("#inlineCheckbox2").click(function(){
    if($(this).is(":checked")){
       $("#highscorediv").show();
    }else{
        $("#highscorediv").hide();
    }
});

$("#inlineCheckbox3").click(function(){
    if($(this).is(":checked")){
       $("#awardsdiv").show();
    }else{
        $("#awardsdiv").hide();
    }
});
<?php echo '</script'; ?>
><?php }
}
