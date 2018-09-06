{include file="header.tpl" title=$title}
<h2 class="homepage">{$title}</h2>
<div class="wrapit maindiv">
<div class="hs_main_wrap">
{foreach $res as $r}
<div class="hs_main_userscore toppage {if $r@iteration==1} firstscore{/if}">
<div class="userscore_name"><a href="{$settings['siteurl']}/profile/{$r.id}" target="_blank">{if $r@iteration==1}<img src="{$settings['siteurl']}/images/avatars/{if $r.avatar}{$r.avatar}{else}default-avatar.jpg{/if}" alt="{$r.username}" />{else}<span>#{$r.rank}</span> {/if}{$r.username}</a></div><div class="userscore_score">{$r.xp|number_format}<span>xp</span></div></div>
{/foreach}</div>
</div>
{include file="footer.tpl"}