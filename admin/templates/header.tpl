<!DOCTYPE html><html lang="en"><head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
<title>{$title}</title>
<script src="https://code.jquery.com/jquery-3.1.0.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<style>
.toggle.btn {
    margin: 18px 0 0 9px;
}
</style>
</head><body>
<nav class="navbar navbar-default">
<div class="container-fluid">
<div class="navbar-header">
<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
<span class="sr-only">Toggle navigation</span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
</button>
<a class="navbar-brand" href="{$settings['siteurl']}/" target="_blank" title="{$settings['sitename']}">{$settings['sitename']}</a>
</div>
<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
<ul class="nav navbar-nav">
<li{if $page=='home'} class="active"{/if}><a href="{$settings['siteurl']}/{$settings['adminfolder']}/">Admin Home</a></li>
<li{if $page=='games'} class="active"{/if}><a href="{$settings['siteurl']}/{$settings['adminfolder']}/games.php">Games</a></li>
<li{if $page=='cats'} class="active"{/if}><a href="{$settings['siteurl']}/{$settings['adminfolder']}/category.php">Categories</a></li>
<li{if $page=='users'} class="active"{/if}><a href="{$settings['siteurl']}/{$settings['adminfolder']}/users.php">Users</a></li>
<li{if $page=='msg'} class="active"{/if}><a href="{$settings['siteurl']}/{$settings['adminfolder']}/messages.php">Messages{if $messages>0} <span class="badge">{$messages}</span>{/if}</a></li>
<li{if $page=='settings'} class="active"{/if}>
<a href="{$settings['siteurl']}/{$settings['adminfolder']}/theme.php">Theme</a></li>
<li{if $page=='theme'} class="active"{/if}>
<a href="{$settings['siteurl']}/{$settings['adminfolder']}/settings.php">Settings</a></li>
<li{if $page=='support'} class="active"{/if}><a href="{$settings['siteurl']}/{$settings['adminfolder']}/support.php">Support</a></li>
</ul>
<a href="{$settings['siteurl']}/logout" role="button" class="btn btn-default navbar-btn navbar-right">Log Out</a>
</div>
</nav>

