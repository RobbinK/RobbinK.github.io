{include file="header.tpl" title=$title keywords=$keywords description=$seodesc}
<div class="wrapit extrapad gamemaindiv">
<div class="page-header" style="margin-left:0;margin-right:0;width:100%">
<ul class="nav nav-pills">
<li role="presentation"{if $s=='recent'} class="active"{/if}><a href="{$settings['siteurl']}/category/recent">Recent</a></li>
<li role="presentation"{if $s=='popular'} class="active"{/if}><a href="{$settings['siteurl']}/category/popular">Popular</a></li>
<li role="presentation"><a href="{$settings['siteurl']}/awards">Achievements</a></li>
</ul>
</div>
<div class="ad970">
{$settings['adcode']}
</div>
{foreach $res as $r}
<div class="thumbWrapper"><div name="{$r.name}">
<a href="{$settings['siteurl']}/g/{$r.url_key}">{$r.name}</a>
<img src="{$settings['imgcdn']}/{$r.thumb}" width="200" height="200" alt="{$r.name}" />
<span class="thumbname"><span>{$r.name}</span></span>
{if $r.has_achs==1 || $r.has_scores==1}
<span class="awards-bit">Achievements</span>
{/if}
</div></div>
{/foreach}
</div>
<div id="loading"></div>
<script src="https://code.jquery.com/jquery-3.1.0.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s="   crossorigin="anonymous"></script>
<script>
var loading=false;
var page=0;
var keeploading=true;
var lastScrollTop = 0;
//auto load more games
$(function() {
$(window).scroll(function() {
var st = $(this).scrollTop();
if (st > lastScrollTop){
var end = $("#footer").offset().top;
var viewEnd = $(window).scrollTop() + $(window).height();
var distance = end - viewEnd;
if(distance < 40 && !loading && keeploading){
page++;
loading=true;
$( "#loading" ).show();
var jqxhr = $.getJSON( "{$SiteUrl}/json.php?action=catload&sort={$s}&page="+page, function() {
loading=false;
$( "#loading" ).hide();
}).done(function(data) {
if(data.errors){
keeploading=false;	
}else{
$.each(data.games, function( key, value ) {
var html='<div class="thumbWrapper"><div><a href="{$SiteUrl}/g/'+value.url_key+'">'+value.name+'</a><img src="{$imgCDN}/'+value.thumb+'" width="200" height="200" alt="'+value.name+'" /><span class="thumbname"><span>'+value.name+'</span></span>';
if(value.has_achs==1||value.has_scores==1){
html+='<span class="awards-bit">Awards</span>';	
}
html+='</div></div>';
$( ".gamemaindiv" ).append(html);
});
}
}).fail(function(error) {
console.log(error)
})
}
}
lastScrollTop = st;
});
});
</script>
{include file="footer.tpl"}