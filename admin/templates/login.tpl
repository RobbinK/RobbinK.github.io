{include file="header.tpl" title='Admin Login'}

<div class="container">
<div class="row">
<div class="page-header">
<h4>Admin Login</h4>
</div>

{if $errors}<p class="bg-danger">{$errors}</p>{/if}

<form method="post" action="{$settings['siteurl']}/{$settings['adminfolder']}/login.php?submt" id="loginit">
  <div class="form-group">
    <label for="exampleInputPassword1">Admin Password</label>
    <input autofocus type="password" class="form-control" id="exampleInputPassword1" name="user_password" placeholder="Password" autocomplete="off" required>
  </div>
<button type="submit" class="btn btn-full btn-bigtxt btn-primary">Log in</button>
</form>

</div></div>
{include file="footer.tpl"}