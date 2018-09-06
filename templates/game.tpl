{include file="header.tpl" title=$title keywords=$keywords description=$seodesc}
<div class="wrapit maindiv">
<!-- do not use square ads here, google will send you a warning, use responsive ads -->
<div class="ad970">
{$settings['adcode']}
</div>
<div class="game_info_wrapper">
<div class="game_info_left">
<div class="imgwrapgame">
<a href="{$settings['siteurl']}/play/{$game.id}/">
<img src="{$settings['imgcdn']}/{$game.thumb}" alt="{$game.name}" width="200" height="200" />
</a>
</div>
<div><h2>{$game.name}</h2>
</div>
</div><div class="game_info_right">
<a href="{$settings['siteurl']}/play/{$game.id}/" id="play_btn">Play Now</a>
{if $game.vidthumb}
<button id="walkthroughbtn">Watch Walkthrough</button>
{/if}
<p>{$game.description}</p>
<p>Game directions: {$game.instructions}</p>
{if $game.has_scores==1}
<h3 class="awards">High Scores</h3>
<div class="hs_main_wrap">
{foreach $boards as $board}
{if $boards|count > 1}<div class="cat_menu_link"><h3>{$board.name}</h3></div>{/if}
{foreach $highscores[$board@index] as $s}
<div class="hs_main_userscore{if $s@first} firstscore{/if}">
<div class="userscore_name"><a href="{$settings['siteurl']}/profile/{$s.uid}" target="_blank">{if $s@first}<img src="{$settings['siteurl']}/images/avatars/{if $s.avatar}{$s.avatar}{else}default-avatar.jpg{/if}" alt="{$s.username}" />{else}<span>{$s.rank}.</span> {/if}{$s.username}</a></div><div class="userscore_score">{$s.scores|number_format}</div></div>
{/foreach}
{/foreach}
</div>
{/if}
{if $awards}
<h3 class="awards">{$awards|count} Achievements</h3>
{foreach $awards as $a}
<div class="awards_bit{if !$a.saved} notearned{/if}">
<img src="{$settings['siteurl']}/images/trophy.png" width="40" height="40" alt="Award" />
<div>
<span class="title">{$a.name}</span>
</div>
<div class="pointstoearn">{$a.points}<span>xp</span></div>
</div>
{/foreach}
{/if}
{if $game.vidthumb}
<iframe src="http://lagged.com/vid/video.php?vid={$game.install_id}" id="video_inline"></iframe>
{/if}
<p><strong>Game Tags</strong><br>{if $awards}<a title="Achievement" href="{$settings['siteurl']}/awards">Achievement</a><br>{/if}{foreach $gtags as $t}<a title="{$t|capitalize|replace:'-':' '}" href="{$settings['siteurl']}/tag/{$t|lower}">{$t|capitalize|replace:'-':' '}</a>{/foreach}</p>
</div>
</div>
<div class="ad970 hei280">
{$settings['adcode']}
</div>
<div id="categories">
<h3>More Games to Play</h3>
{foreach $related as $r}
<div class="thumbWrapper"><div>
<a href="{$settings['siteurl']}/g/{$r.url_key}">{$r.name}</a>
<img src="{$settings['imgcdn']}/{$r.thumb}" width="200" height="200" alt="{$r.name} Play" />
<span class="thumbname"><span>{$r.name}</span></span>
{if $r.has_achs==1 || $r.has_scores==1}
<span class="awards-bit">Achievements</span>
{/if}
</div></div>
{/foreach}
</div></div>
{include file="footer.tpl"}
{if $game.vidthumb}{literal}
<script src="https://code.jquery.com/jquery-3.1.0.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous"></script>
<script>
$(function(){$('#walkthroughbtn').click(function() {$('html,body').animate({scrollTop: $("#video_inline").offset().top-8});});});
</script>{/literal}{/if}