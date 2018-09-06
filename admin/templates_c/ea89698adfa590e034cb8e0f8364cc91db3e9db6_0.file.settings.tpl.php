<?php
/* Smarty version 3.1.29, created on 2017-01-18 15:45:56
  from "/home/gbapi/public_html/lagged/admin/templates/settings.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_587ffe3478fc45_23555790',
  'file_dependency' => 
  array (
    'ea89698adfa590e034cb8e0f8364cc91db3e9db6' => 
    array (
      0 => '/home/gbapi/public_html/lagged/admin/templates/settings.tpl',
      1 => 1484783155,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_587ffe3478fc45_23555790 ($_smarty_tpl) {
$_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('title'=>'Settings'), 0, false);
?>


<div class="container">
<div class="row">
<div class="page-header">
<h3>Settings</h3>
</div>

<?php if ($_smarty_tpl->tpl_vars['msg']->value) {?><p class="bg-success" style="padding:5px 10px"><?php echo $_smarty_tpl->tpl_vars['msg']->value;?>
</p><?php }
if ($_smarty_tpl->tpl_vars['error']->value) {?><p class="bg-danger" style="padding:5px 10px"><?php echo $_smarty_tpl->tpl_vars['error']->value;?>
</p><?php }?>

<ul class="nav nav-tabs">
<li role="presentation"<?php if ($_smarty_tpl->tpl_vars['tab']->value == 'home') {?> class="active"<?php }?>><a href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
/<?php echo $_smarty_tpl->tpl_vars['settings']->value['adminfolder'];?>
/settings.php?view=home">General</a></li>
<li role="presentation"<?php if ($_smarty_tpl->tpl_vars['tab']->value == 'feeds') {?> class="active"<?php }?>><a href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
/<?php echo $_smarty_tpl->tpl_vars['settings']->value['adminfolder'];?>
/settings.php?view=feeds">Game Feeds</a></li>
<li role="presentation"<?php if ($_smarty_tpl->tpl_vars['tab']->value == 'ads') {?> class="active"<?php }?>><a href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
/<?php echo $_smarty_tpl->tpl_vars['settings']->value['adminfolder'];?>
/settings.php?view=ads">Ads</a></li>
<li role="presentation"<?php if ($_smarty_tpl->tpl_vars['tab']->value == 'seo') {?> class="active"<?php }?>><a href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
/<?php echo $_smarty_tpl->tpl_vars['settings']->value['adminfolder'];?>
/settings.php?view=seo">SEO</a></li>
<li role="presentation"<?php if ($_smarty_tpl->tpl_vars['tab']->value == 'email') {?> class="active"<?php }?>><a href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
/<?php echo $_smarty_tpl->tpl_vars['settings']->value['adminfolder'];?>
/settings.php?view=email">Email</a></li>
<li role="presentation"<?php if ($_smarty_tpl->tpl_vars['tab']->value == 'admin') {?> class="active"<?php }?>><a href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
/<?php echo $_smarty_tpl->tpl_vars['settings']->value['adminfolder'];?>
/settings.php?view=admin">Admin</a></li>
</ul>

<form method="post" action="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
/<?php echo $_smarty_tpl->tpl_vars['settings']->value['adminfolder'];?>
/settings.php?view=<?php echo $_smarty_tpl->tpl_vars['tab']->value;?>
&submt=true" id="loginit">
<?php if ($_smarty_tpl->tpl_vars['tab']->value == 'home') {?>	
<div class="form-group" style="padding-top:15px">
<label for="exampleInputEmail1">Language</label>
<select>
<option value="en">English</option>
</select>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Website Url</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="siteurl" placeholder="http://yoursite.com" value="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Website Name</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="sitename" placeholder="Demo Website" value="<?php echo $_smarty_tpl->tpl_vars['settings']->value['sitename'];?>
" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Admin folder (if you change you need to rename folder in ftp)</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="adminfolder" placeholder="admin" value="<?php echo $_smarty_tpl->tpl_vars['settings']->value['adminfolder'];?>
" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Image Host (default to yoursite.com/thumbs)</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="imgcdn" placeholder="http://yoursite.com/thumbs" value="<?php echo $_smarty_tpl->tpl_vars['settings']->value['imgcdn'];?>
" />
</div>

<div class="form-group">
<label for="exampleInputEmail5">Analytics (Include Script Tags)</label><br>
<textarea name="analytics" cols="50" rows="5" style="width:100%" id="exampleInputEmail5"><?php echo $_smarty_tpl->tpl_vars['settings']->value['analytics'];?>
</textarea>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Veedi ID</label>
<input type="text" class="form-control" id="exampleInputEmail1" name="veedi" placeholder="App ID" value="<?php echo $_smarty_tpl->tpl_vars['settings']->value['veedi'];?>
" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Facebook App ID</label>
<input type="text" class="form-control" id="exampleInputEmail1" name="facebook" placeholder="App ID" value="<?php echo $_smarty_tpl->tpl_vars['settings']->value['facebook'];?>
" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Twitter Page</label>
<input type="text" class="form-control" id="exampleInputEmail1" name="twitter" placeholder="TwitterID" value="<?php echo $_smarty_tpl->tpl_vars['settings']->value['twitter'];?>
" />
</div>

<div class="form-group">
<label for="exampleInputEmail5">Privacy Policy</label><br>
<textarea name="privacy" cols="50" rows="5" style="width:100%"  id="exampleInputEmail5" required><?php echo $_smarty_tpl->tpl_vars['settings']->value['privacy'];?>
</textarea>
</div>
<?php } elseif ($_smarty_tpl->tpl_vars['tab']->value == 'feeds') {?>
<div class="jumbotron" style="margin-top:15px">
<h1>Game Feeds</h1>
<p>The best html5 games from Lagged, Spil, Poki and more!</p>
</div>
<table class="table table-striped">
<thead> <tr><th>Feed</th><th>Affiliate ID</th><th>Auto Install</th></tr></thead>
<tbody>
<?php
$_from = $_smarty_tpl->tpl_vars['gamefeeds']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_f_0_saved_item = isset($_smarty_tpl->tpl_vars['f']) ? $_smarty_tpl->tpl_vars['f'] : false;
$_smarty_tpl->tpl_vars['f'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['f']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['f']->value) {
$_smarty_tpl->tpl_vars['f']->_loop = true;
$__foreach_f_0_saved_local_item = $_smarty_tpl->tpl_vars['f'];
?>
<tr><td>
<h4><a href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['siteurl'];?>
/<?php echo $_smarty_tpl->tpl_vars['settings']->value['adminfolder'];?>
/gamefeeds.php?feed=<?php echo $_smarty_tpl->tpl_vars['f']->value['feedid'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['f']->value['title'];?>
</a></h4>
<p><?php echo $_smarty_tpl->tpl_vars['f']->value['descrpt'];?>
</p>
</td>
<td <?php if ($_smarty_tpl->tpl_vars['f']->value['revenue'] == 0) {?>style="line-height: 68px;"<?php }?>>
<?php if ($_smarty_tpl->tpl_vars['f']->value['revenue'] == 1) {?>
<input type="text" required class="form-control" id="exampleInputEmail1" name="aff[<?php echo $_smarty_tpl->tpl_vars['f']->value['feedid'];?>
]" placeholder="your aff code" value="<?php echo $_smarty_tpl->tpl_vars['f']->value['aff_id'];?>
" style="max-width:180px;margin-top:10px" />
<a href="<?php echo $_smarty_tpl->tpl_vars['f']->value['signup'];?>
" target="_blank">Sign up</a>
<?php } else { ?>
N/A
<?php }?>
</td>
<td>
<input type="checkbox" <?php if ($_smarty_tpl->tpl_vars['f']->value['auto'] == 1) {?>checked<?php }?> data-toggle="toggle" name="auto[<?php echo $_smarty_tpl->tpl_vars['f']->value['feedid'];?>
]" value="1">
</td></tr>
<?php
$_smarty_tpl->tpl_vars['f'] = $__foreach_f_0_saved_local_item;
}
if ($__foreach_f_0_saved_item) {
$_smarty_tpl->tpl_vars['f'] = $__foreach_f_0_saved_item;
}
?>
</table>
</div>
<?php } elseif ($_smarty_tpl->tpl_vars['tab']->value == 'ads') {?>
<div class="form-group" style="padding-top:15px">
<label for="exampleInputEmail5">Banner Ads (Use responsive ads)</label><br>
<textarea name="adcode" cols="50" rows="5" style="width:100%" id="exampleInputEmail5"><?php echo $_smarty_tpl->tpl_vars['settings']->value['adcode'];?>
</textarea>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Adsense ID (For <a href="https://support.google.com/adxseller/answer/6068103?hl=en" target="_blank">page level ads</a>, <small>must be enabled on your account</small>)</label>
<input type="text" class="form-control" id="exampleInputEmail1" name="pagelevel" placeholder="ca-pub-000000" value="<?php echo $_smarty_tpl->tpl_vars['settings']->value['adsense-pagelevel'];?>
" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Adsense for Games ID</label>
<input type="text" class="form-control" id="exampleInputEmail1" name="gamesid" placeholder="ca-games-pub-000000" value="<?php echo $_smarty_tpl->tpl_vars['settings']->value['adsense-games'];?>
" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Adsense for Games Channel ID (optional)</label>
<input type="text" class="form-control" id="exampleInputEmail1" name="channel" placeholder="4333186796" value="<?php echo $_smarty_tpl->tpl_vars['settings']->value['ad-games-channel'];?>
" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Enable game ads on all games (Strongly discouraged)</label>
<input type="checkbox" <?php if ($_smarty_tpl->tpl_vars['settings']->value['autoads'] === 'true') {?>checked<?php }?> data-toggle="toggle" name="autoads" value="true">
</div>
<?php } elseif ($_smarty_tpl->tpl_vars['tab']->value == 'seo') {?>
<div class="page-header" style="margin-top:10px">
 <h1><small>Home Page</small></h1>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Title</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="home_title" value="<?php echo $_smarty_tpl->tpl_vars['pagesettings']->value['home']['title'];?>
" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Description</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="home_desc" value="<?php echo $_smarty_tpl->tpl_vars['pagesettings']->value['home']['desc'];?>
" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Keywords</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="home_keywords" value="<?php echo $_smarty_tpl->tpl_vars['pagesettings']->value['home']['keywords'];?>
" />
</div>

<div class="page-header" style="margin-top:20px">
 <h1><small>Game Page</small></h1>
<p>Variables: <b>:-gamename:</b> <b>:-tag1:</b> <b>:-tag2:</b></p>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Title</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="game_title" value="<?php echo $_smarty_tpl->tpl_vars['pagesettings']->value['game']['title'];?>
" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Description</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="game_desc" value="<?php echo $_smarty_tpl->tpl_vars['pagesettings']->value['game']['desc'];?>
" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Keywords</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="game_keywords" value="<?php echo $_smarty_tpl->tpl_vars['pagesettings']->value['game']['keywords'];?>
" />
</div>

<div class="page-header" style="margin-top:20px">
 <h1><small>Tag Pages</small></h1>
<p>Variables: <b>:-tag:</b></p>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Title</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="tags_title" value="<?php echo $_smarty_tpl->tpl_vars['pagesettings']->value['tags']['title'];?>
" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Description</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="tags_desc" value="<?php echo $_smarty_tpl->tpl_vars['pagesettings']->value['tags']['desc'];?>
" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Keywords</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="tags_keywords" value="<?php echo $_smarty_tpl->tpl_vars['pagesettings']->value['tags']['keywords'];?>
" />
</div>

<div class="page-header" style="margin-top:20px">
 <h1><small>New/Popular Game Pages</small></h1>
<p>Variables: <b>:-sort:</b></p>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Title</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="category_title" value="<?php echo $_smarty_tpl->tpl_vars['pagesettings']->value['category']['title'];?>
" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Description</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="category_desc" value="<?php echo $_smarty_tpl->tpl_vars['pagesettings']->value['category']['desc'];?>
" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Keywords</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="category_keywords" value="<?php echo $_smarty_tpl->tpl_vars['pagesettings']->value['category']['keywords'];?>
" />
</div>

<div class="page-header" style="margin-top:20px">
 <h1><small>Achievement Games</small></h1>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Title</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="awards_title" value="<?php echo $_smarty_tpl->tpl_vars['pagesettings']->value['awards']['title'];?>
" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Description</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="awards_desc" value="<?php echo $_smarty_tpl->tpl_vars['pagesettings']->value['awards']['desc'];?>
" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Keywords</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="awards_keywords" value="<?php echo $_smarty_tpl->tpl_vars['pagesettings']->value['awards']['keywords'];?>
" />
</div>

<div class="page-header" style="margin-top:20px">
 <h1><small>Profile Page</small></h1>
<p>Variables: <b>:-username:</b></p>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Title</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="profile_title" value="<?php echo $_smarty_tpl->tpl_vars['pagesettings']->value['profile']['title'];?>
" />
</div>

<div class="page-header" style="margin-top:20px">
 <h1><small>Search Page</small></h1>
<p>Variables: <b>:-term:</b></p>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Title</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="search_title" value="<?php echo $_smarty_tpl->tpl_vars['pagesettings']->value['search']['title'];?>
" />
</div>

<div class="page-header" style="margin-top:20px">
 <h1><small>Contact Page</small></h1>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Title</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="contact_title" value="<?php echo $_smarty_tpl->tpl_vars['pagesettings']->value['contact']['title'];?>
" />
</div>

<div class="page-header" style="margin-top:20px">
 <h1><small>Invite Friends Page</small></h1>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Title</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="invite_title" value="<?php echo $_smarty_tpl->tpl_vars['pagesettings']->value['invite']['title'];?>
" />
</div>

<div class="page-header" style="margin-top:20px">
 <h1><small>Login Page</small></h1>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Title</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="login_title" value="<?php echo $_smarty_tpl->tpl_vars['pagesettings']->value['login']['title'];?>
" />
</div>

<div class="page-header" style="margin-top:20px">
 <h1><small>Signup Page</small></h1>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Title</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="signup_title" value="<?php echo $_smarty_tpl->tpl_vars['pagesettings']->value['signup']['title'];?>
" />
</div>

<div class="page-header" style="margin-top:20px">
 <h1><small>Privacy Policy Page</small></h1>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Title</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="privacy_title" value="<?php echo $_smarty_tpl->tpl_vars['pagesettings']->value['privacy']['title'];?>
" />
</div>

<div class="page-header" style="margin-top:20px">
 <h1><small>Top Users Page</small></h1>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Title</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="top_title" value="<?php echo $_smarty_tpl->tpl_vars['pagesettings']->value['top']['title'];?>
" />
</div>

<div class="page-header" style="margin-top:20px">
 <h1><small>Welcome Page</small></h1>
</div>

<div class="form-group" style="padding-bottom:20px">
<label for="exampleInputEmail1">Title</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="welcome_title" value="<?php echo $_smarty_tpl->tpl_vars['pagesettings']->value['welcome']['title'];?>
" />
</div>
<?php } elseif ($_smarty_tpl->tpl_vars['tab']->value == 'email') {?>
<p style="padding-top:15px">Used to send email to users. Leave blank to disable mail.</p>
<div class="form-group">
<label for="exampleInputEmail1">Email Host</label>
<input type="text" class="form-control" id="exampleInputEmail1" autocomplete="off" name="email_host" value="<?php echo $_smarty_tpl->tpl_vars['settings']->value['email_host'];?>
" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Email Address</label>
<input type="text" class="form-control" id="exampleInputEmail1" autocomplete="off" name="email_address" value="<?php echo $_smarty_tpl->tpl_vars['settings']->value['email_address'];?>
" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Email Password</label>
<input type="password" class="form-control" id="exampleInputEmail1" autocomplete="off" name="email_password" value="<?php echo $_smarty_tpl->tpl_vars['settings']->value['email_password'];?>
" />
</div>
<?php } elseif ($_smarty_tpl->tpl_vars['tab']->value == 'admin') {?>
<div class="form-group" style="padding-top:15px">
<label for="exampleInputEmail1">Current Password</label>
<input type="password" required class="form-control" id="exampleInputEmail1" name="password" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">New Password</label>
<input type="password" required class="form-control" id="exampleInputEmail1" name="new_password" />
</div>
<?php }?>
<button type="submit" class="btn btn-full btn-bigtxt btn-primary" style="float:left;clear:both">Save Changes</button>
</form>

</div></div>
<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
