{include file="header.tpl" title=$title keywords=$keywords description=$seodesc}
<h2 class="homepage">{$title}</h2>
<div class="wrapit extrapad gamemaindiv">
<div class="page-header" style="margin-left:0;margin-right:0;width:100%">
<ul class="nav nav-pills">
<li role="presentation"{if $s=='new'} class="active"{/if}><a href="{$settings['siteurl']}/category/recent">Recent</a></li>
<li role="presentation"{if $s=='popular'} class="active"{/if}><a href="{$settings['siteurl']}/category/popular">Popular</a></li>
<li role="presentation" class="active"><a href="{$settings['siteurl']}/awards">Achievements</a></li>
</ul>
</div>
{foreach $games as $r}
<div class="thumbWrapper"><div>
<a href="{$settings['siteurl']}/g/{$r.url_key}">{$r.name}</a>
<img src="{$settings['imgcdn']}/{$r.thumb}" width="200" height="200" alt="{$r.name}" />
<span class="thumbname"><span>{$r.name}</span></span>
<span class="awards-bit">Achievements</span>
</div></div>
{/foreach}
{if $total_pages > 1}
<div class="paging">
<div class="pagewrap">
{if $p != 1}
<a href="{$settings['siteurl']}/awards/{$page}{if $p-1 != 1}/{$p-1}{/if}">PREV</a>
{/if}
{section name=page start=$page_from loop=$page_to+1 step=1}
<a href="{$settings['siteurl']}/awards/{$page}{if $smarty.section.page.index != 1}/{$smarty.section.page.index}{/if}" class="greybtn{if $p == $smarty.section.page.index} active{/if}">{$smarty.section.page.index}</a>
{/section}
{if $p != $total_pages}
<a href="{$settings['siteurl']}/awards/{$page}/{$p+1}">NEXT</a>
{/if}
</div></div>
{/if}
<div class="ad970 hei280">
{$settings['adcode']}
</div>
</div>
{include file="footer.tpl"}