{include file="header.tpl" title=$title}

<div class="wrapit extrapad noftgames">
<div class="page-header">
<ul class="nav nav-pills">
<li role="presentation"><a href="{$settings['siteurl']}/login">Log In</a></li>
<li role="presentation" class="active"><a href="{$settings['siteurl']}/signup">{$title}</a></li>
</ul>
</div>
{if $errors}<p class="bg-danger">{$errors}</p>{/if}
<form method="post" onsubmit="goform();" action="{$settings['siteurl']}/signup?submit" id="loginit">
<div class="form-group">
<label for="exampleInputEmail1">Nickname</label>
<input autofocus type="text" name="name" value="{$name}" class="form-control" id="exampleInputEmail1" placeholder="Nickname" required>
</div>
<div class="form-group">
<label for="exampleInputEmail1">Email address (verification required)</label>
<input type="email" name="email" value="{$email}" class="form-control" id="exampleInputEmail2" placeholder="Valid Email" required>
</div>
<div class="form-group">
<label for="exampleInputPassword1">Password</label>
<input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password: At least 6 characters" autocomplete="off" required>
</div>
<button type="submit" class="btn btn-full btn-bigtxt" id="submitbntn">Create Account</button>
</form>

</div>
<script src="https://code.jquery.com/jquery-3.1.0.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s="   crossorigin="anonymous"></script>
{literal}
<script>
function validateEmail(email) {
var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
return re.test(email);
}
var goform=function(){
var name = $("#exampleInputEmail1").val();
var email = $("#exampleInputEmail2").val();
var password = $("#exampleInputPassword1").val();
if(name.length>0&&email.length>0&&password.length>0){
if (validateEmail(email)) {
$("#submitbntn").html('wait...');
$("#submitbntn").prop("disabled",true);
}
}
}
</script>
{/literal}
{include file="footer.tpl"}