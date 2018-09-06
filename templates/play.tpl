<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"/>
<title>{$title}</title>
<meta name="description" content="{$seodesc}"/>
<meta name="keywords" content="{$keywords}"/>
<link rel="canonical" href="{$settings['siteurl']}/play/{$game.id}/" />
<link rel="icon" type="image/png" href="{$settings['siteurl']}/favicon-192.png" sizes="192x192">
<link rel="stylesheet" type="text/css" href="{$settings['siteurl']}/templates/styles_gamepage.css?v=1" />
<meta itemprop="name" content="{$game.name}">
<meta property="og:title" content="{$game.name}" />
<meta property="og:type" content="game" /> 
<meta property="og:description" content="Play {$game.name}, a free online game on {$settings['sitename']}. {$game.name} is a must play!" />
<meta property="og:image" content="{$settings['imgcdn']}/{$game.thumb}"/>
<meta property="og:url" content="{$settings['siteurl']}/play/{$game.id}/" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@{$settings['twitter']}" />
<meta name="twitter:title" content="{$game.name}" />
<meta name="twitter:description" content="Play {$game.name}, a free online game on {$settings['sitename']}. {$game.name} is a must play!" />
<meta name="twitter:image" content="{$settings['imgcdn']}/{$game.thumb}" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, minimal-ui" />
<script>
var descUrl="{$settings['siteurl']}/g/{$game.url_key}";
var channelid="{$settings['ad-games-channel']}";
var clientid="{$settings['adsense-games']}";
var siteurl="{$settings['siteurl']}";
var facebookapp="{$settings['facebook']}";
var veedimute=true;
</script>
{if $game.ads && $settings['adsense-games']}
<style>
{literal}.shownoads{display:none}{/literal}
</style>
<script type="text/javascript" src="//imasdk.googleapis.com/js/sdkloader/ima3.js"></script>
{/if}
</head><body>
{if $game.ads&&$settings['adsense-games']}	
<div id="mainContainer">
<video id="contentElement">
</video>
<div id="adContainer"></div>
</div><div id="playButton">
<img src="{$settings['imgcdn']}/{$game.thumb}" alt="{$game.name}" />
<button>Tap to Play</button>
</div><div id="preloader"></div>
{/if}
<button onclick="showMenu()" id="mainbtn" class="shownoads"></button>
{if $awards || $game.has_scores==1}
<button onclick="showAwards()" id="awardbtn" class="shownoads"></button>
<div id="awards_menu">
<h2><a href="{$settings['siteurl']}" class="logo">{$settings['sitename']}</a></h2>
{if $user.id==0}
<p class="loginmsg"><a href="{$settings['siteurl']}/signup" target="_blank">Sign up</a> to save achievements and scores!</p>
{/if}
{if $awards}
<h3 class="awards"><a href="{$settings['siteurl']}/g/{$game.url_key}">Achievements</a></h3>
{foreach $awards as $a}
<div id="{$a.ach_id}" class="awards_bit{if !$a.saved} notearned{/if}">
<img src="{$settings['siteurl']}/images/trophy.png" width="40" height="40" alt="Award" />
<div>
<span class="title">{$a.name}</span>
<span class="desc">{$a.textdesc}</span>
</div>
<div class="pointstoearn">+{$a.points}<span>xp</span></div>
</div>
{/foreach}
{/if}
{if $game.has_scores==1}
<h3 class="awards"><a href="{$settings['siteurl']}/g/{$game.url_key}">High Scores</a></h3>
<div class="hs_main_wrap insideofmenu">
{foreach $boards as $board}
{if $boards|count > 1}<div class="cat_menu_link hsboarddss">{$board.name}</div>{/if}
{foreach $highscores[$board@iteration-1] as $s}
<div class="hs_main_userscore{if $s@first} firstscore{else if $s@last} lastchild{/if}">
<div class="userscore_name"><a href="{$settings['siteurl']}/profile/{$s.uid}" target="_blank">{if !$s@first}<span>{$s.rank}.</span> {/if}{$s.username}</a></div><div class="userscore_score">{$s.scores|number_format}</div></div>
{/foreach}
{/foreach}
</div>
{/if}
</div>
{/if}
{if $game.vidthumb || $settings['veedi']}
<button onclick="showWalkthrough()" id="videobtn" class="shownoads{if $awards || $game.has_scores==1} withaward{/if}"></button>
<div id="videomenu" class="thisishidden">
{if $game.vidthumb}
<iframe src="http://lagged.com/vid/video.php?vid={$game.install_id}" id="video_inline"></iframe>
{else if $settings['veedi']}
<div id="veediInit"></div>
{literal}
<script type="text/javascript" id="veediInit">
var initVeedi=function(){	
var _v,settings = {
game : "{/literal}{$game.name}{literal}",
publisherId : {/literal}{$settings['veedi']}{literal},
onVideoNotFound:function() {
document.getElementById('videobtn').style.display = "none";
},
onVideoFound:function() {
document.getElementById('videobtn').style.display = "block";
},
width : 900
};
(function(settings)  {
var vScript = document.createElement('script'); 
vScript.type = 'text/javascript'; vScript.async = true;
vScript.src = 'http://www.veedi.com/player/embed/veediEmbed.js';
vScript.onload = function(){_v = new VeediEmbed(settings);};
var veedi = document.getElementById('veediInit'); veedi.parentNode.insertBefore(vScript, veedi);
})(settings);	
}
window.addEventListener('adsfinished', function (e) {
initVeedi();
}, false);
if(!clientid){
initVeedi();
}
</script>
{/literal}
{/if}
</div>
{/if}
<div id="menu">
<h2><a href="{$settings['siteurl']}" class="logo">{$settings['sitename']}</a></h2>
<div class="cat_menu_link"><a href="{$settings['siteurl']}/">{$settings['sitename']}</a></div>
<div class="cat_menu_link"><a href="{$settings['siteurl']}/awards">Achievement Games</a></div>
<div class="cat_menu_link"><a href="{$settings['siteurl']}/category/recent">Recent Games</a></div>
<h3 style="font-size:15px;padding-bottom:4px">Related Games</h3>
{foreach $related as $r}
<div class="thumbWrapper"><div>
<a href="{$settings['siteurl']}/play/{$r.id}/">{$r.name}</a>
<img src="{$settings['imgcdn']}/{$r.thumb}" width="200" height="200" alt="Game icon" />
</div></div>
{/foreach}
<a href="{$settings['siteurl']}">&laquo; Back</a>
</div>
<object id="gamePlayer" width="100%" height="100%" data="{$game.swf}" class="shownoads"></object>
{if $game.ads&&$settings['adsense-games']}
<script type="text/javascript" src="{$settings['siteurl']}/js/game.js?v=1"></script>
{/if}
<script type="text/javascript" src="{$settings['siteurl']}/js/api.js?v=1"></script>
</body></html>
{$settings['analytics']}