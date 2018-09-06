{include file="header.tpl" title='Settings'}

<div class="container">
<div class="row">
<div class="page-header">
<h3>Settings</h3>
</div>

{if $msg}<p class="bg-success" style="padding:5px 10px">{$msg}</p>{/if}
{if $error}<p class="bg-danger" style="padding:5px 10px">{$error}</p>{/if}

<ul class="nav nav-tabs">
<li role="presentation"{if $tab=='home'} class="active"{/if}><a href="{$settings['siteurl']}/{$settings['adminfolder']}/settings.php?view=home">General</a></li>
<li role="presentation"{if $tab=='feeds'} class="active"{/if}><a href="{$settings['siteurl']}/{$settings['adminfolder']}/settings.php?view=feeds">Game Feeds</a></li>
<li role="presentation"{if $tab=='ads'} class="active"{/if}><a href="{$settings['siteurl']}/{$settings['adminfolder']}/settings.php?view=ads">Ads</a></li>
<li role="presentation"{if $tab=='seo'} class="active"{/if}><a href="{$settings['siteurl']}/{$settings['adminfolder']}/settings.php?view=seo">SEO</a></li>
<li role="presentation"{if $tab=='email'} class="active"{/if}><a href="{$settings['siteurl']}/{$settings['adminfolder']}/settings.php?view=email">Email</a></li>
<li role="presentation"{if $tab=='admin'} class="active"{/if}><a href="{$settings['siteurl']}/{$settings['adminfolder']}/settings.php?view=admin">Admin</a></li>
</ul>

<form method="post" action="{$settings['siteurl']}/{$settings['adminfolder']}/settings.php?view={$tab}&submt=true" id="loginit">
{if $tab=='home'}	
<div class="form-group" style="padding-top:15px">
<label for="exampleInputEmail1">Language</label>
<select>
<option value="en">English</option>
</select>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Website Url</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="siteurl" placeholder="http://yoursite.com" value="{$settings['siteurl']}" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Website Name</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="sitename" placeholder="Demo Website" value="{$settings['sitename']}" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Admin folder (if you change you need to rename folder in ftp)</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="adminfolder" placeholder="admin" value="{$settings['adminfolder']}" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Image Host (default to yoursite.com/thumbs)</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="imgcdn" placeholder="http://yoursite.com/thumbs" value="{$settings['imgcdn']}" />
</div>

<div class="form-group">
<label for="exampleInputEmail5">Analytics (Include Script Tags)</label><br>
<textarea name="analytics" cols="50" rows="5" style="width:100%" id="exampleInputEmail5">{$settings['analytics']}</textarea>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Veedi ID</label>
<input type="text" class="form-control" id="exampleInputEmail1" name="veedi" placeholder="App ID" value="{$settings['veedi']}" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Facebook App ID</label>
<input type="text" class="form-control" id="exampleInputEmail1" name="facebook" placeholder="App ID" value="{$settings['facebook']}" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Twitter Page</label>
<input type="text" class="form-control" id="exampleInputEmail1" name="twitter" placeholder="TwitterID" value="{$settings['twitter']}" />
</div>

<div class="form-group">
<label for="exampleInputEmail5">Privacy Policy</label><br>
<textarea name="privacy" cols="50" rows="5" style="width:100%"  id="exampleInputEmail5" required>{$settings['privacy']}</textarea>
</div>
{else if $tab=='feeds'}
<div class="jumbotron" style="margin-top:15px">
<h1>Game Feeds</h1>
<p>The best html5 games from Lagged, Spil, Poki and more!</p>
</div>
<table class="table table-striped">
<thead> <tr><th>Feed</th><th>Affiliate ID</th><th>Auto Install</th></tr></thead>
<tbody>
{foreach $gamefeeds as $f}
<tr><td>
<h4><a href="{$settings['siteurl']}/{$settings['adminfolder']}/gamefeeds.php?feed={$f.feedid}" target="_blank">{$f.title}</a></h4>
<p>{$f.descrpt}</p>
</td>
<td {if $f.revenue==0}style="line-height: 68px;"{/if}>
{if $f.revenue==1}
<input type="text" required class="form-control" id="exampleInputEmail1" name="aff[{$f.feedid}]" placeholder="your aff code" value="{$f.aff_id}" style="max-width:180px;margin-top:10px" />
<a href="{$f.signup}" target="_blank">Sign up</a>
{else}
N/A
{/if}
</td>
<td>
<input type="checkbox" {if $f.auto==1}checked{/if} data-toggle="toggle" name="auto[{$f.feedid}]" value="1">
</td></tr>
{/foreach}
</table>
</div>
{else if $tab=='ads'}
<div class="form-group" style="padding-top:15px">
<label for="exampleInputEmail5">Banner Ads (Use responsive ads)</label><br>
<textarea name="adcode" cols="50" rows="5" style="width:100%" id="exampleInputEmail5">{$settings['adcode']}</textarea>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Adsense ID (For <a href="https://support.google.com/adxseller/answer/6068103?hl=en" target="_blank">page level ads</a>, <small>must be enabled on your account</small>)</label>
<input type="text" class="form-control" id="exampleInputEmail1" name="pagelevel" placeholder="ca-pub-000000" value="{$settings['adsense-pagelevel']}" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Adsense for Games ID</label>
<input type="text" class="form-control" id="exampleInputEmail1" name="gamesid" placeholder="ca-games-pub-000000" value="{$settings['adsense-games']}" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Adsense for Games Channel ID (optional)</label>
<input type="text" class="form-control" id="exampleInputEmail1" name="channel" placeholder="4333186796" value="{$settings['ad-games-channel']}" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Enable game ads on all games (Strongly discouraged)</label>
<input type="checkbox" {if $settings['autoads']==='true'}checked{/if} data-toggle="toggle" name="autoads" value="true">
</div>
{else if $tab=='seo'}
<div class="page-header" style="margin-top:10px">
 <h1><small>Home Page</small></h1>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Title</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="home_title" value="{$pagesettings['home']['title']}" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Description</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="home_desc" value="{$pagesettings['home']['desc']}" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Keywords</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="home_keywords" value="{$pagesettings['home']['keywords']}" />
</div>

<div class="page-header" style="margin-top:20px">
 <h1><small>Game Page</small></h1>
<p>Variables: <b>:-gamename:</b> <b>:-tag1:</b> <b>:-tag2:</b></p>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Title</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="game_title" value="{$pagesettings['game']['title']}" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Description</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="game_desc" value="{$pagesettings['game']['desc']}" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Keywords</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="game_keywords" value="{$pagesettings['game']['keywords']}" />
</div>

<div class="page-header" style="margin-top:20px">
 <h1><small>Tag Pages</small></h1>
<p>Variables: <b>:-tag:</b></p>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Title</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="tags_title" value="{$pagesettings['tags']['title']}" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Description</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="tags_desc" value="{$pagesettings['tags']['desc']}" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Keywords</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="tags_keywords" value="{$pagesettings['tags']['keywords']}" />
</div>

<div class="page-header" style="margin-top:20px">
 <h1><small>New/Popular Game Pages</small></h1>
<p>Variables: <b>:-sort:</b></p>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Title</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="category_title" value="{$pagesettings['category']['title']}" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Description</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="category_desc" value="{$pagesettings['category']['desc']}" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Keywords</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="category_keywords" value="{$pagesettings['category']['keywords']}" />
</div>

<div class="page-header" style="margin-top:20px">
 <h1><small>Achievement Games</small></h1>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Title</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="awards_title" value="{$pagesettings['awards']['title']}" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Description</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="awards_desc" value="{$pagesettings['awards']['desc']}" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Keywords</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="awards_keywords" value="{$pagesettings['awards']['keywords']}" />
</div>

<div class="page-header" style="margin-top:20px">
 <h1><small>Profile Page</small></h1>
<p>Variables: <b>:-username:</b></p>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Title</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="profile_title" value="{$pagesettings['profile']['title']}" />
</div>

<div class="page-header" style="margin-top:20px">
 <h1><small>Search Page</small></h1>
<p>Variables: <b>:-term:</b></p>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Title</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="search_title" value="{$pagesettings['search']['title']}" />
</div>

<div class="page-header" style="margin-top:20px">
 <h1><small>Contact Page</small></h1>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Title</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="contact_title" value="{$pagesettings['contact']['title']}" />
</div>

<div class="page-header" style="margin-top:20px">
 <h1><small>Invite Friends Page</small></h1>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Title</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="invite_title" value="{$pagesettings['invite']['title']}" />
</div>

<div class="page-header" style="margin-top:20px">
 <h1><small>Login Page</small></h1>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Title</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="login_title" value="{$pagesettings['login']['title']}" />
</div>

<div class="page-header" style="margin-top:20px">
 <h1><small>Signup Page</small></h1>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Title</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="signup_title" value="{$pagesettings['signup']['title']}" />
</div>

<div class="page-header" style="margin-top:20px">
 <h1><small>Privacy Policy Page</small></h1>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Title</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="privacy_title" value="{$pagesettings['privacy']['title']}" />
</div>

<div class="page-header" style="margin-top:20px">
 <h1><small>Top Users Page</small></h1>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Title</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="top_title" value="{$pagesettings['top']['title']}" />
</div>

<div class="page-header" style="margin-top:20px">
 <h1><small>Welcome Page</small></h1>
</div>

<div class="form-group" style="padding-bottom:20px">
<label for="exampleInputEmail1">Title</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="welcome_title" value="{$pagesettings['welcome']['title']}" />
</div>
{else if $tab=='email'}
<p style="padding-top:15px">Used to send email to users. Leave blank to disable mail.</p>
<div class="form-group">
<label for="exampleInputEmail1">Email Host</label>
<input type="text" class="form-control" id="exampleInputEmail1" autocomplete="off" name="email_host" value="{$settings['email_host']}" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Email Address</label>
<input type="text" class="form-control" id="exampleInputEmail1" autocomplete="off" name="email_address" value="{$settings['email_address']}" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Email Password</label>
<input type="password" class="form-control" id="exampleInputEmail1" autocomplete="off" name="email_password" value="{$settings['email_password']}" />
</div>
{else if $tab=='admin'}
<div class="form-group" style="padding-top:15px">
<label for="exampleInputEmail1">Current Password</label>
<input type="password" required class="form-control" id="exampleInputEmail1" name="password" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">New Password</label>
<input type="password" required class="form-control" id="exampleInputEmail1" name="new_password" />
</div>
{/if}
<button type="submit" class="btn btn-full btn-bigtxt btn-primary" style="float:left;clear:both">Save Changes</button>
</form>

</div></div>
{include file="footer.tpl"}