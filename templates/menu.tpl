{include file="header.tpl" title="Menu"}
<div class="wrapit extrapad">
<h3 class="homepage menumain">Menu</h3>
<div class="cat_menu_link searchmenulink"><a href="{$settings['siteurl']}/sitesearch/">Game Search</a></div>
{if $user.id>0}
<div class="cat_menu_link searchmenulink"><a href="{$settings['siteurl']}/user-search/">User Search</a></div>
{/if}
<div class="cat_menu_link"><a href="{$settings['siteurl']}/awards">Achievement Games</a></div>
<div class="cat_menu_link"><a href="{$settings['siteurl']}/category/recent">Recent Games</a></div>
<div class="cat_menu_link"><a href="{$settings['siteurl']}/category/popular">Popular Games</a></div>
<div class="cat_menu_link"><a href="{$settings['siteurl']}/tag/action">Action Games</a></div>
<div class="cat_menu_link"><a href="{$settings['siteurl']}/tag/girl">Girl Games</a></div>
<div class="cat_menu_link"><a href="{$settings['siteurl']}/tag/shooting">Shooting Games</a></div>
<div class="cat_menu_link"><a href="{$settings['siteurl']}/tag/skill">Skill Games</a></div>
<div class="cat_menu_link"><a href="{$settings['siteurl']}/tag/sports">Sports Games</a></div>
{if $user.id>0}
<div class="cat_menu_link"><a href="{$settings['siteurl']}/edit/profile">Edit Profile</a></div>
<div class="cat_menu_link"><a href="{$settings['siteurl']}/logout">Logout</a></div>
{/if}
</div>
{include file="footer.tpl"}