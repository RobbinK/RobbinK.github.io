{include file="header.tpl" title=$title}
{if $page=='unsub'}
<div class="wrapit maindiv noftgames">
<div class="page-header">
<h1>{$title}</h1>
</div>

{if $error}
<p class="bg-danger">{$error}</p>
{/if}

{if $success}
<p class="bg-caution signuppage">
<span>Success!</span> {$success}
</p>
{/if}

{else if $page=='password'}
<div class="wrapit maindiv noftgames">
<div class="page-header">
<h1>{$title}</h1>
</div>

{if $error}
<p class="bg-danger">{$error}</p>
{/if}

{if $success}
<p class="bg-caution signuppage">
<span>Success!</span>	{$success}
</p>
{else if !$errorbad}
{if $key=='password'}
<p class="loginmsg" style="margin:0 5%">
Enter in your account email below and we will send a password reset link.<br>
<a href="{$settings['siteurl']}/contact">Contact us</a> if you need help.
</p>
<form method="post" action="{$settings['siteurl']}/help/password/?submit" id="loginit">
<div class="form-group">
<label for="exampleInputEmail1">Your Email</label><input autofocus id="exampleInputEmail1" class="form-control" type="text" name="email" placeholder="Email" value="" autofocus required />
</div>
<button type="submit" class="btn btn-default">Submit</button>
</form>
{/if}
{if $key=='recover'}
<p class="loginmsg" style="margin:0 5%">
Enter your new password below.
</p>

<form method="post" action="{$settings['siteurl']}/help/password/recover/{$key2}" name="loginform" id="loginit">
<div class="form-group">
<label for="exampleInputEmail1">New password</label><input id="exampleInputEmail1" type="password" name="password_1" placeholder="Password" value="" autofocus class="form-control" />
</div>
<div class="form-group">
<label for="exampleInputEmail2">Confirm password</label><input id="exampleInputEmail1" class="form-control" type="password" name="password_2" placeholder="Password" value="" />
</div>
<button type="submit" class="btn btn-default">Submit</button>
</form>
{/if}

{/if}


{else}
<div class="wrapit maindiv noftgames">
<div class="page-header">
<h1>Error!</h1>
</div>
<p class="bg-danger">This page does not exist</p>
{/if}
</div>
{include file="footer.tpl"}