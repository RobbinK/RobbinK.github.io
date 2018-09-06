{include file="header.tpl" title=$title}
<div class="wrapit maindiv noftgames">	
<form action="{$settings['siteurl']}/user-search/" method="post" id="loginit">
<div class="form-group lesspad">
<label for="searchInput">Search:</label>
<input type='text' name="query" placeholder="search for a user" class="form-control" value="{$tag}" autofocus required>
</div>
<button type="submit" class="btn btn-default btn-full">Search</button>
</form>
{foreach $res as $r}
<div class="awards_bit noimgpagging">
<a href="{$settings['siteurl']}/profile/{$r.id}" title="{$r.username}">
<img src="{$settings['siteurl']}/images/avatars/{if $r.avatar}{$r.avatar}{else}default-avatar.jpg{/if}" width="40" height="40" alt="{$r.username}" />
<div>
<span class="title">{$r.username}</span>
</div>
</a>
</div>
{foreachelse}
{if $tag}
<div class="recentlyplayed" style="margin:60px 0 20px 5%;padding:0">
<div class="bigtitle">No matches</div>
<p>Sorry, no users found! Try again.</p>
</div>
{/if}
{/foreach}
</div>
{include file="footer.tpl"}