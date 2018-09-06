{include file="header.tpl" title=$title description=$description}
<div class="wrapit extrapad"><div class="page-header" style="margin-left:0;margin-right:0;width:100%">
<h2>{$tag|capitalize|replace:'-':' '}</h2></div>
<div class="ad970">
{$settings['adcode']}
</div>
{foreach $res as $r}
<div class="thumbWrapper"><div>
<a href="{$settings['siteurl']}/g/{$r.url_key}">{$r.name}</a>
<img src="{$settings['imgcdn']}/{$r.thumb}" width="200" height="200" alt="{$r.name}" />
<span class="thumbname"><span>{$r.name}</span></span>
{if $r.has_achs==1 || $r.has_scores==1}
<span class="awards-bit">Achievements</span>
{/if}
</div></div>
{/foreach}
{if $total_pages > 1}
<div class="paging">
<div class="pagewrap">
{if $p != 1}
<a href="{$settings['siteurl']}/{if $p-1 != 1}tag/{$tag}/{$p-1}{else}tag/{$tag}{/if}">Prev</a>
{/if}
{section name=page start=$page_from loop=$page_to+1 step=1}
<a href="{$settings['siteurl']}/{if $smarty.section.page.index != 1}tag/{$tag}/{$smarty.section.page.index}{else}tag/{$tag}{/if}" class="greybtn{if $p == $smarty.section.page.index} active{/if}">{$smarty.section.page.index}</a>
{/section}
{if $p != $total_pages}
<a href="{$settings['siteurl']}/tag/{$tag}/{$p+1}">Next</a>
{/if}
</div></div>
{/if}
<!-- Google will send you a warning if you display more then 1 ad on screen in mobile -->
{if $total_items>12}
<div class="ad970 hei280 padtop15">
{$settings['adcode']}
</div>
{/if}
</div>
{include file="footer.tpl"}