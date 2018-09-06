{include file="header.tpl" title=$title keywords=$keywords description=$seodesc}
<h2 class="homepage">{$settings['sitename']}</h2>
<div class="wrapit maindiv noftgames">
{foreach $games as $g}
<div class="thumbWrapper thumb_{$g@iteration}"><div>
<a href="{$settings['siteurl']}/g/{$g.url_key}">{$g.name}</a>
<img src="{$settings['imgcdn']}/{$g.thumb}" width="200" height="200" alt="{$g.name}" />
<span class="thumbname"><span>{$g.name}</span></span>
{if $g.isnew==1}
<span class="ribbon-wrapper-green"><span class="ribbon-green">NEW</span></span>
{/if}
{if $g.has_achs==1 || $g.has_scores==1}
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
var jqxhr = $.getJSON( "{$SiteUrl}/json.php?action=homeload&page="+page, function() {
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
$( ".maindiv" ).append(html);
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