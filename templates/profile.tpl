{include file="header.tpl" title=$title}
<div class="wrapit profilepage extrapad">
<h2 class="usernamebig"><a{if $user.id==$profile.id && $is_profile} href="{$settings['siteurl']}/edit/profile"{/if}><img src="{$settings['siteurl']}/images/avatars/{if $profile.avatar}{$profile.avatar}{else}default-avatar.jpg{/if}" alt="{$profile.username}" /></a>{$profile.username}</h2>
{if $user.id==$profile.id && $is_profile}
<div class="page-header profiletabs" style="margin-bottom:5px">
<ul class="nav nav-pills">
<li role="presentation"><a href="{$settings['siteurl']}/edit/profile">Edit Profile</a></li>
<li role="presentation"><a href="{$settings['siteurl']}/logout">Logout</a></li>
</ul>
</div>
{else}
{if $user.id>0}
{if $isfriend}
<button id="friendBtn" class="minibtn removefriends" onClick="addFriends('remove',{$profile.id})"><span>Friends</span></button>
{else}
<button id="friendBtn" class="minibtn" onClick="addFriends('add',{$profile.id})"><span>Add to Friends</span></button>
{/if}
{else}
<a href="{$settings['siteurl']}/login" class="minibtn addtofriends">Add to Friends</a>
{/if}
{/if}
<div class="level_block">
<div class="level">Level <span>{$profile.level}</span></div>
<div class="points">{$profile.xp|number_format}<span>xp</span></div>
<div class="level_up_bar">
<div class="next_xp">XP until next level: <b>{$profile.xpmax|number_format}</b></div>
<div class="progress_bar">
<div class="prog_bit" style="width:{$profile.user_perc}%"></div>
</div>
<div class="current_place">
Ranked <b>#{$userrank}</b> on {$settings['sitename']}. <a href="{$settings['siteurl']}/top">View Top 10</a>.<br>Ranked <b>#{$friend_rank}</b> between friends.
</div>
</div>
</div>
<div class="page-header" style="margin:15px 0 5px 0;width:100%">
<ul class="nav nav-pills">
<li role="presentation" class="active"><a href="{$settings['siteurl']}/profile_more/awards/{$profile.id}">{$award_count} Awards</a></li>
<li role="presentation"><a href="{$settings['siteurl']}/profile_more/friends/{$profile.id}">{$friends_count} Friends</a></li>
<li role="presentation"><a href="{$settings['siteurl']}/profile_more/followers/{$profile.id}">{$followers_count} Followers</a></li>
</ul>
</div>
{foreach $awards as $a}
<div class="awards_bit noimgpagging">
<a href="{$settings['siteurl']}/g/{$a.url_key}" title="Play {$a.gname}">
<img src="{$settings['imgcdn']}/{$a.thumb}" width="40" height="40" alt="Award" />
<div>
<span class="title">{$a.name}</span>
<span class="desc"><b>{$a.gname}:</b> {$a.textdesc}</span>
</div>
<div class="pointstoearn">+{$a.points}<span>xp</span></div>
</a>
</div>
{foreachelse}
<p class="bg-caution">No awards earned. <a href="{$settings['siteurl']}/awards">Play award games</a>.</p>
{/foreach}
{if $awards|count>4}<a href="{$settings['siteurl']}/profile_more/awards/{$profile.id}" class="minibtn">View all</a>{/if}
</div>
{include file="footer.tpl"}
{if $user.id>0}{literal}
<script type="text/javascript">
var hasClass=function(e,s){return(" "+e.className+" ").indexOf(" "+s+" ")>-1},addFriends=function(e,s){var n=document.getElementById("friendBtn");if("add"==e&&hasClass(n,"removefriends")?e="remove":"remove"!=e||hasClass(n,"removefriends")||(e="add"),n.classList.toggle("removefriends"),s>0){var d=new XMLHttpRequest;d.open("GET","{/literal}{$SiteUrl}{literal}/json.php?action=friend&fid="+s+"&method="+e,!0),d.send()}};
</script>
{/literal}{/if}