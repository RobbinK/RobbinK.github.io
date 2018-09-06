{include file="header.tpl" title=$title}
<div class="wrapit maindiv noftgames">	
<form action="{$settings['siteurl']}/search/" method="post" id="loginit">
<div class="form-group lesspad">
<label for="searchInput">Search:</label>
<input type='text' name="query" placeholder="search for a game" class="form-control" value="{$tag}" autofocus required>
</div>
<button type="submit" class="btn btn-default btn-full">Search</button>
</form>
{foreach $res as $r}
<div class="thumbWrapper t{$r@iteration}"><div name="{$r.name}">
<a href="{$settings['siteurl']}/g/{$r.url_key}">{$r.name}</a>
<img src="{$settings['imgcdn']}/{$r.thumb}" width="150" height="150" alt="{$r.name} Game" />
<span class="thumbname"><span>{$r.name}</span></span>
{if $r.has_achs==1 || $r.has_scores==1}
<span class="awards-bit">Awards</span>
{/if}
</div></div>
{foreachelse}
{if $tag}
<div class="recentlyplayed" style="margin:60px 0 20px 0;padding:0">
<div class="bigtitle">No matches</div>
<p>Sorry, no games found! Try again.</p>
</div>
{/if}
{/foreach}
{if $total_pages > 1}
<div class="paging">
<div class="pagewrap">
{if $p != 1}
<a href="{$settings['siteurl']}/{if $p-1 != 1}sitesearch/{$tag}/{$p-1}{else}sitesearch/{$tag}{/if}">Prev</a>
{/if}
{section name=page start=$page_from loop=$page_to+1 step=1}
<a href="{$settings['siteurl']}/{if $smarty.section.page.index != 1}sitesearch/{$tag}/{$smarty.section.page.index}{else}en/{$tag}{/if}" class="greybtn{if $p == $smarty.section.page.index} active{/if}">{$smarty.section.page.index}</a>
{/section}
{if $p != $total_pages}
<a href="{$settings['siteurl']}/sitesearch/{$tag}/{$p+1}">Next</a>
{/if}
</div>
{/if}
</div>
{include file="footer.tpl"}