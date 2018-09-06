{include file="header.tpl" title=$title}
<div class="wrapit profilepage extrapad">
<h2 class="usernamebig"><a{if $user.id==$profile.id && $is_profile} href="{$settings['siteurl']}/edit/profile"{/if}><img src="{$settings['siteurl']}/images/avatars/{if $profile.avatar}{$profile.avatar}{else}default-avatar.jpg{/if}" alt="{$profile.username}" /></a>{$profile.username}{if $profile.verified}<a href="{$settings['siteurl']}/faq" rel="nofollow" target="_blank" class="checkmarked" title="Verified Email">&#10003;</a>{/if}</h2>
{if $user.id!=$profile.id}
{if $user.id>0}
{if $isfriend}
<button id="friendBtn" class="minibtn removefriends" onClick="addFriends('remove',{$profile.id})"><span>Friends</span></button>
{else}
<button id="friendBtn" class="minibtn" onClick="addFriends('add',{$profile.id})"><span>Add to Friends</span></button>
{/if}
{else}
<a href="{$settings['siteurl']}/login" class="minibtn addtofriends">Add to Friends</a>
{/if}
{else}
<p class="bg-caution signuppage" style="margin:0 0 10px 0;width:98%"><a href="{$settings['siteurl']}/invite">Invite your friends</a> and earn +25xp for each sign up!</p>
{/if}
<div class="page-header" style="margin-left:0;margin-right:0;width:100%">
<ul class="nav nav-pills">
<li role="presentation"{if $page=='awards'} class="active"{/if}><a href="{$settings['siteurl']}/profile_more/awards/{$profile.id}">{$award_count} Awards</a></li>
<li role="presentation"{if $page=='friends'} class="active"{/if}><a href="{$settings['siteurl']}/profile_more/friends/{$profile.id}">{$friends_count} Friends</a></li>
<li role="presentation"{if $page=='followers'} class="active"{/if}><a href="{$settings['siteurl']}/profile_more/followers/{$profile.id}">{$followers_count} Followers</a></li>
</ul>
</div>
{if $page=='awards'}
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
{/foreach}
{else if $page=='friends'}
{foreach $friends as $uf}
<div class="awards_bit noimgpagging">
<a href="{$settings['siteurl']}/profile/{$uf.id}" title="{$uf.username}">
<img src="{$settings['siteurl']}/images/avatars/{if $uf.avatar}{$uf.avatar}{else}default-avatar.jpg{/if}" width="40" height="40" alt="{$uf.username}" />
<div>
<span class="title">{$uf.username}</span>
</div>
</a>
</div>
{/foreach}
{else if $page=='followers'}
{foreach $followers as $uf}
<div class="awards_bit noimgpagging">
<a href="{$settings['siteurl']}/profile/{$uf.id}" title="{$uf.username}">
<img src="{$settings['siteurl']}/images/avatars/{if $uf.avatar}{$uf.avatar}{else}default-avatar.jpg{/if}" width="40" height="40" alt="{$uf.username}" />
<div>
<span class="title">{$uf.username}</span>
</div>
</a>
</div>
{/foreach}
{/if}
</div>
{include file="footer.tpl"}
{if $user.id>0}{literal}
<script type="text/javascript">
var hasClass=function(e,s){return(" "+e.className+" ").indexOf(" "+s+" ")>-1},addFriends=function(e,s){var n=document.getElementById("friendBtn");if("add"==e&&hasClass(n,"removefriends")?e="remove":"remove"!=e||hasClass(n,"removefriends")||(e="add"),n.classList.toggle("removefriends"),s>0){var d=new XMLHttpRequest;d.open("GET","{/literal}{$SiteUrl}{literal}/json.php?action=friend&fid="+s+"&method="+e,!0),d.send()}};
</script>
{/literal}{/if}