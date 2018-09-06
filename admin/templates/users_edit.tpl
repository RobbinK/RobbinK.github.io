{include file="header.tpl" title='Edit User'}
<div class="container">
<div class="row">
<div class="page-header">
<h4>Edit {$profile.username}<a href="{$settings['siteurl']}/{$settings['adminfolder']}/users_edit.php?type=delete&id={$profile.id}" type="button" class="btn btn-danger" style="float:right" onclick="return confirm('Are you sure?')">Delete</a></h4>
</div>

{if $errors}<p class="bg-danger">{$errors}</p>{/if}
{if $msg}<p class="bg-success">{$msg}<br>{/if}

<form method="post" action="{$settings['siteurl']}/{$settings['adminfolder']}/users_edit.php?type=edit&id={$profile.id}" id="loginit">
<div class="form-group">
<label for="exampleInputEmail1">Name</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="username" placeholder="user name" value="{$profile.username|stripslashes}" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Email</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="email" placeholder="user email" value="{$profile.user_email}" />
</div>


<div class="form-group">
<label for="exampleInputEmail1">Avatar</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="avatar" placeholder="avatar" value="{if $profile.avatar}{$profile.avatar}{else}default-avatar.jpg{/if}" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">XP</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="xp" placeholder="xp" value="{$profile.xp}" />
</div>


<button type="submit" class="btn btn-full btn-bigtxt btn-primary" style="float:left;clear:both">Submit</button>
</form>
</div></div>
{include file="footer.tpl"}