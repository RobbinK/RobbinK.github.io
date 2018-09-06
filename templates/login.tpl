{include file="header.tpl" title=$title}

<div class="wrapit extrapad noftgames">
<div class="page-header">
<ul class="nav nav-pills">
  <li role="presentation" class="active"><a href="{$settings['siteurl']}/login">{$title}</a></li>
  <li role="presentation"><a href="{$settings['siteurl']}/signup">Sign Up</a></li>
</ul>
</div>

{if $errors}<p class="bg-danger">{$errors}</p>{/if}

<form method="post" action="{$settings['siteurl']}/login?submit" id="loginit">
  <div class="form-group">
    <label for="exampleInputEmail1">Email address</label>
    <input autofocus type="email" name="user_name" class="form-control" value="{$email}" id="exampleInputEmail1" placeholder="Email" required>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1" name="user_password" placeholder="Password" autocomplete="off" required>
  </div>
<button type="submit" class="btn btn-full btn-bigtxt">Log in</button>
</form>

<div class="panel panel-default">
  <div class="panel-body">
    <a href="{$settings['siteurl']}/help/password/">Forgot Password?</a>
  </div>
</div>
</div>
{include file="footer.tpl"}