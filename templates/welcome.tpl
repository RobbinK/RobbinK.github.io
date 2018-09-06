{include file="header.tpl" title=$title}
<h2 class="homepage" style="text-align:center;font-weight:bold">Welcome!</h2>
<div style="margin:0 auto;width:98%;padding:0 1%;max-width:500px">
<p class="bg-caution signuppage" style="margin:20px 0 0 0;width:98%;text-align:center">{if $award_count>0}<b>{$award_count}</b> award{if $award_count!=1}s{/if} saved and {/if}<b>{$profile.xp|number_format}xp</b> earned!</p>
<div class="level_block" style="text-align:center">
<div class="level" style="text-align:center;float:none;padding:0">Level <span>{$profile.level}</span></div>
<div class="points" style="float:none">{$profile.xp|number_format}<span>xp</span></div>
<div class="level_up_bar" style="margin-top:6px">
<div class="next_xp">XP until next level: <b>{$profile.xpmax|number_format}</b></div>
<div class="progress_bar" style="margin:0 auto;float:none">
<div class="prog_bit" style="width:{$profile.user_perc}%"></div>
</div>
</div>
</div>	
<h3 style="margin:20px 0 5px 0;text-align:center">Invite your friends</h3>
<!--<p>You earn 25 XP for each friend who signs up</p>-->
<ul class="rrssb-buttons clearfix">
<li class="rrssb-facebook" style="width:33%">
<!--  Replace with your URL. For best results, make sure you page has the proper FB Open Graph tags in header: https://developers.facebook.com/docs/opengraph/howtos/maximizing-distribution-media-content/ -->
<a href="https://www.facebook.com/sharer/sharer.php?u={$settings['siteurl']}/?ref={$user.id}" class="popup" target="_blank">
<span class="rrssb-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 29 29"><path d="M26.4 0H2.6C1.714 0 0 1.715 0 2.6v23.8c0 .884 1.715 2.6 2.6 2.6h12.393V17.988h-3.996v-3.98h3.997v-3.062c0-3.746 2.835-5.97 6.177-5.97 1.6 0 2.444.173 2.845.226v3.792H21.18c-1.817 0-2.156.9-2.156 2.168v2.847h5.045l-.66 3.978h-4.386V29H26.4c.884 0 2.6-1.716 2.6-2.6V2.6c0-.885-1.716-2.6-2.6-2.6z"/></svg></span>
<span class="rrssb-text">facebook</span>
</a>
</li>
<li class="rrssb-twitter" style="width:33%">
<!-- Replace href with your Meta and URL information  -->
<a href="https://twitter.com/intent/tweet?text=Play%20awesome%20games%20for%20free%20{$settings['siteurl']|escape}?ref={$user.id}"
class="popup" target="_blank">
<span class="rrssb-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28"><path d="M24.253 8.756C24.69 17.08 18.297 24.182 9.97 24.62a15.093 15.093 0 0 1-8.86-2.32c2.702.18 5.375-.648 7.507-2.32a5.417 5.417 0 0 1-4.49-3.64c.802.13 1.62.077 2.4-.154a5.416 5.416 0 0 1-4.412-5.11 5.43 5.43 0 0 0 2.168.387A5.416 5.416 0 0 1 2.89 4.498a15.09 15.09 0 0 0 10.913 5.573 5.185 5.185 0 0 1 3.434-6.48 5.18 5.18 0 0 1 5.546 1.682 9.076 9.076 0 0 0 3.33-1.317 5.038 5.038 0 0 1-2.4 2.942 9.068 9.068 0 0 0 3.02-.85 5.05 5.05 0 0 1-2.48 2.71z"/></svg></span>
<span class="rrssb-text">twitter</span>
</a>
</li>
<li class="rrssb-email" style="width:34%">
<!-- Replace subject with your message using URL Endocding: http://meyerweb.com/eric/tools/dencoder/ -->
<a href="mailto:?subject=Play%20Games&amp;body=Come%20join%20me%20at%20{$settings['siteurl']|escape}%20and%20challenge%20me%20to%20some%20games!" target="_blank">
<span class="rrssb-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28"><path d="M20.11 26.147c-2.335 1.05-4.36 1.4-7.124 1.4C6.524 27.548.84 22.916.84 15.284.84 7.343 6.602.45 15.4.45c6.854 0 11.8 4.7 11.8 11.252 0 5.684-3.193 9.265-7.398 9.3-1.83 0-3.153-.934-3.347-2.997h-.077c-1.208 1.986-2.96 2.997-5.023 2.997-2.532 0-4.36-1.868-4.36-5.062 0-4.75 3.503-9.07 9.11-9.07 1.713 0 3.7.4 4.6.972l-1.17 7.203c-.387 2.298-.115 3.3 1 3.4 1.674 0 3.774-2.102 3.774-6.58 0-5.06-3.27-8.994-9.304-8.994C9.05 2.87 3.83 7.545 3.83 14.97c0 6.5 4.2 10.2 10 10.202 1.987 0 4.09-.43 5.647-1.245l.634 2.22zM16.647 10.1c-.31-.078-.7-.155-1.207-.155-2.572 0-4.596 2.53-4.596 5.53 0 1.5.7 2.4 1.9 2.4 1.44 0 2.96-1.83 3.31-4.088l.592-3.72z"/></svg></span>
<span class="rrssb-text">email</span>
</a>
</li>
</ul>
<p style="width: 96%;float:left;clear:both;margin:10px 0 0 2%;text-align: center;">Copy and paste your link</p>
<textarea onclick="this.select()" style="width: 96%;margin:3px 0 0 2%;border: 1px solid #999;text-align: center;font-size: 16px;padding: 0;height: 30px;line-height: 30px;">{$settings['siteurl']}/?ref={$user.id}</textarea>
<a href="{$settings['siteurl']}/" style="float: left;clear: both;width:96%;background-color: #eee;padding: 10px 2%;font-size: 18px;text-align: center;font-weight:bold;border-radius:5px;margin:15px 0 20px 0">Continue &raquo;</a>
</div>
{include file="footer.tpl"}