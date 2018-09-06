{include file="header.tpl" title=$feed|ucfirst}
<div class="container">
<div class="row">
<div class="page-header">
<h4>{$feed|ucfirst} Feed</h4>
</div>
<p>Preview or install a game. <small>If game is already installed it will be faded.</small></p>
<div class="row">
{foreach $games as $g}
<div class="col-sm-6 col-md-4">
<div class="thumbnail"{if $g.installed==1} style="opacity: 0.3"{/if}>
<a href="{$g.swf}" class="imgwrapclick" target="_blank"><img src="{$g.thumb}" alt="{$g.name|stripslashes}" /></a>
<div class="caption">
<h3><a href="{$g.swf}" target="_blank">{$g.name|stripslashes|truncate:27:"..":true}</a></h3>
<p><a href="{$settings['siteurl']}/{$settings['adminfolder']}/install.php?source=feed&id={$g.id}" class="btn btn-primary" role="button">Install</a></p>
</div>
</div>
</div>
{/foreach}
</div></div>
{include file="footer.tpl"}