{include file="header.tpl" title=$title}
<div class="wrapit extrapad">
<div class="page-header">
<h1>{$title}</h1>
</div>
{if $errors}<p class="bg-danger">{$errors}</p>{/if}
{if $msg}<p class="bg-caution signuppage">Message sent. <a href="{$SiteUrl}">Return home</a>.</p>
{else}
<form method="post" action="{$SiteUrl}/contact?submit" id="loginit">
<div class="form-group">
<label for="exampleInputEmail1">Your Email</label>
<input autofocus type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Email" required>
</div>
<div class="form-group">
<label for="exampleInputEmail2">Subject</label>
<input type="text" name="subject" class="form-control" id="exampleInputEmail2" placeholder="Subject" required>
</div>
<div class="form-group">
<label for="exampleInputEmail3">Message</label>
<textarea id="exampleInputEmail3" class="form-control" name="message" placeholder="Your Message" required style="height:100px"></textarea>
</div>
<button type="submit" class="btn btn-full btn-bigtxt">Submit</button>
</form>
{/if}

{include file="footer.tpl"}