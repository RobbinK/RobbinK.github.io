<!DOCTYPE html><html lang="en"><head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<title>{$title}</title>
{if $description}<meta name="description" content="{$description}" />
{/if}
{if $keywords}<meta name="keywords" content="{$keywords}" />
{/if}
{if $game}
<link rel="image_src" href="{$settings['imgcdn']}/{$game.thumb}" />
<meta property="og:image" content="{$settings['imgcdn']}/{$game.thumb}" />
<meta property="og:title" content="{$game.name}" />
<meta property="og:description" content="Play {$game.name} for free online.">
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@{$settings['twitter']}" />
<meta name="twitter:title" content="{$game.name}" />
<meta name="twitter:description" content="{$game.name} is a fun game you can play on any device." />
<meta name="twitter:image" content="{$settings['imgcdn']}/{$game.thumb}" />
<link rel="canonical" href="{$settings['siteurl']}/play/{$game.id}/" />
<meta property="og:url" content="{$settings['siteurl']}/g/{$game.url_key}" />
{foreach $gtags as $t}
<meta property="article:tag" content="{$t|capitalize|replace:'-':' '}" />
{/foreach}
{else if $tag}
<link rel="image_src" href="{$settings['imgcdn']}/{$res[0].thumb}" />
<meta property="og:image" content="{$settings['imgcdn']}/{$res[0].thumb}" />
<meta property="og:title" content="{$tag|capitalize|replace:'-':' '}" />
{if $p==1}
<link rel="canonical" href="{$settings['siteurl']}/tag/{$tag|lower}" />
{if $p != $total_pages}<link rel="next" href="{$settings['siteurl']}/tag/{$tag|lower}/2" />{/if}
{else}
<link rel="canonical" href="{$settings['siteurl']}/tag/{$tag|lower}/{$p}" />
<link rel="previous" href="{$settings['siteurl']}/{if $p-1 != 1}tag/{$tag|lower}/{$p-1}{else}tag/{$tag|lower}{/if}" />
{if $p != $total_pages}<link rel="next" href="{$settings['siteurl']}/tag/{$tag|lower}/{$p+1}" />{/if}
{/if}
{else}
<meta property="og:title" content="{$title}" />
{/if}
<meta property="og:site_name" content="{$settings['sitename']}">
<meta property="og:type" content="game" />
{if $s=='home'}
<meta property="og:description" content="Play awesome games online.">
<link rel="canonical" href="{$settings['siteurl']}/" />
<meta property="og:url" content="{$settings['siteurl']}/" />
{else if $s=='profile'}
<link rel="canonical" href="{$settings['siteurl']}/profile/{$profile.id}" />
{else if $s=='awardpage'}
{if $p==1}
<link rel="canonical" href="{$settings['siteurl']}/awards" />
{else}
<link rel="canonical" href="{$settings['siteurl']}/awards/all/{$p}" />
{/if}
{/if}
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
<link rel='stylesheet' type='text/css' href="{$settings['siteurl']}/templates/styles.css?v=1" />
<link rel='stylesheet' type='text/css' href="{$settings['siteurl']}/templates/rrssb.css?v=1" />
<link rel="icon" type="image/png" href="{$settings['siteurl']}/favicon-192.png" sizes="192x192">
{if $s!='signup'&&$s!='edit'&&$settings['adsense-pagelevel']}
{literal}<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
(adsbygoogle = window.adsbygoogle || []).push({
google_ad_client: "{/literal}{$settings['adsense-pagelevel']}{literal}",
enable_page_level_ads: true
});
</script>{/literal}
{/if}
</head><body>
<div id="wrapper"><div id="header"><div id="logo">
<div class="wrapit"><div class="topperleft">
<a class="sitename" href="{$settings['siteurl']}" title="{$settings['sitename']}">{$settings['sitename']}</a>
</div>
<div class="topperight">
<div class="categorie_dropdown topcorners">
{if $user.id>0}
<a href="{$settings['siteurl']}/profile/{$user.id}" role="button"><img src="{$settings['siteurl']}/images/avatars/{if $user.avatar}{$user.avatar}{else}default-avatar.jpg{/if}" /><span>{$user.username}</span><span class="level">{$user.level}</span></a>
{else}
<a class="loginer" href="{$settings['siteurl']}/login" role="button">Log in</a>
{if $ach_count>0}
<span class="achcount">{$ach_count}</span>
{/if}
{/if}	
</div>
<a id="showMenuBtn" class="searchlink" onclick="openMenu()">Open</a>
</div>
<div id="menu" class="default">
<form style="margin:0 5% 20px 5%" action="{$settings['siteurl']}/search/" method="post" id="loginit" class="menusearch">
<div class="form-group lesspad">
<label for="searchInput">Search:</label>
<input type='text' name="query" placeholder="search for a game" class="form-control" value="" autofocus required>
</div>
<button type="submit" class="btn btn-default btn-full" style="border:0">Search</button>
</form>
<a class="cat_menu_link" href="{$settings['siteurl']}/awards">Achievement Games</a>
<a class="cat_menu_link" href="{$settings['siteurl']}/page/recent">Recent Games</a>
<a class="cat_menu_link" href="{$settings['siteurl']}/page/popular">Popular Games</a>

<!-- loop categories -->
{foreach $categoriesloop as $cg}
<a class="cat_menu_link" href="{$settings['siteurl']}/tag/{$cg.name}">{$cg.name|capitalize|replace:'-':' '} Games</a>
{/foreach}

{if $user.id>0}
<a class="cat_menu_link searchmenulink" href="{$settings['siteurl']}/user-search/">User Search</a>
<a class="cat_menu_link" href="{$settings['siteurl']}/edit/profile">Edit Profile</a>
<a class="cat_menu_link" href="{$settings['siteurl']}/logout">Logout</a>
{/if}
</div>

</div></div></div>