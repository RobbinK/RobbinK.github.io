{include file="header.tpl" title=$title}
<div class="wrapit maindiv noftgames">
	
	<a href="{$settings['siteurl']}/profile/{$user.id}" style="float:left;clear:both;display:block;font-size:16px;padding-bottom:9px;margin:10px 5% 0 5%;width:90%;">&laquo; Back to your profile</a>
	
<div class="page-header profiletabs" style="padding-bottom:9px;margin:10px 5% 10px;width:90%;">
<ul class="nav nav-pills" >
<li role="presentation"{if $page=='profile'} class="active"{/if}><a href="{$settings['siteurl']}/edit/profile">Edit Profile</a></li>
<li role="presentation"{if $page=='email'} class="active"{/if}><a href="{$settings['siteurl']}/edit/email">Edit Email</a></li>
<li role="presentation"{if $page=='password'} class="active"{/if}><a href="{$settings['siteurl']}/edit/password">Edit Password</a></li>
</ul>
</div>

{if $errors}
<p class="bg-danger">{$errors}</p>
{/if}
{if $success}
<p class="loginmsg" style="margin:0 5%">Success! {$success}</p>
{/if}
{if $page=='profile'}
<form method="post" action="{$settings['siteurl']}/edit/profile?submit" id="loginit" enctype="multipart/form-data">

<div class="form-group" style="float:left;width:110px;height:auto">
<label for="slim">Avatar (100x100)</label>
{if $user.avatar}<img src="{$settings['siteurl']}/images/avatars/{$user.avatar}" alt="" />{/if}
<input type="file" name="avaimage" id="slim">
</div>

<div class="form-group" style="float:left;clear:both;margin-top:15px;width:100%">
<label for="exampleInputEmail0">Your Nickname</label><input autofocus id="exampleInputEmail0" class="form-control" type="text" name="name" placeholder="Nickname" value="{$user.username}" autofocus />
</div>
<button type="submit" class="btn btn-full btn-bigtxt" style="float:left">Save Changes</button>
</form>
{else if $page=='password'}
<form method="post" action="{$settings['siteurl']}/edit/password?submit" id="loginit">
<div class="form-group">
<label for="exampleInputEmail3">Current password</label><input id="exampleInputEmail3" type="password" name="currentpass" placeholder="Your Password" value="" autofocus required class="form-control" />
</div>
<div class="form-group">
<label for="exampleInputEmail1">New password</label><input id="exampleInputEmail1" type="password" name="password_1" placeholder="Password" value="" autofocus required class="form-control" />
</div>
<div class="form-group">
<label for="exampleInputEmail2">Confirm new password</label><input id="exampleInputEmail2" class="form-control" type="password" name="password_2" required placeholder="Password" value="" />
</div>
<button type="submit" class="btn btn-full btn-bigtxt">Save Changes</button>
</form>
{else if $page=='email'}
<form method="post" action="{$settings['siteurl']}/edit/email?submit" id="loginit">
<div class="form-group">
<label for="exampleInputEmail1">Your Email <span style="font-size:11px">(if changed you will need to log in again)</span></label><input autofocus id="exampleInputEmail1" class="form-control" type="text" name="email" placeholder="Email" value="{$user.user_email}" />
</div>
<button type="submit" class="btn btn-full btn-bigtxt">Save Changes</button>
</form>
{else}
<h2>This page does not exist</h2>
{/if}