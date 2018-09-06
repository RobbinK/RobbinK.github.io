<?php

/* -------------------------------------------------------------------------------------------------------------
 * Don't modify this file; because during each ArcadeBooster update, this file will be replaced with the new one.
 * In order to change your site urls structure for SEO purposes, you can make your desired changes through
 * this file: /config/routes.custom.config.php
 */

$router = new Router();
$router->setBasePath(get_subdir());
#-----------------------------------------------------------------------------------------------------------
//********************************* ArcadeBooster Administration Routers ***********************************
#-----------------------------------------------------------------------------------------------------------

$router->map('/admin/login.html', 'admin:login', array('methods' => 'GET,POST', 'name' => 'adminlogin'));
$router->map('/admin/logout.html', 'admin:logout', array('methods' => 'GET', 'name' => 'adminlogout'));
$router->map('/admin/changepass.html', 'admin:changepass', array('methods' => 'GET,POST', 'name' => 'adminchangepass'));
$router->map('/admin/chpremium.html', 'admin:changeto_premium', array('methods' => 'GET', 'name' => 'adminchangetopremium'));
$router->map('/admin', 'admin_dashboards:dashboard', array('methods' => 'GET', 'name' => 'admindashboard'));
$router->map('/admin/', 'admin_dashboards:dashboard', array('methods' => 'GET', 'name' => 'admindashboard'));
$router->map('/admin/dashboard.html', 'admin_dashboards:dashboard', array('methods' => 'GET', 'name' => 'admindashboard'));
$router->map('/admin/categories.html', 'admin_category:categories', array('methods' => 'GET,POST', 'name' => 'admincategories'));
$router->map('/admin/games.html', 'admin_games:games', array('methods' => 'GET,POST', 'name' => 'admingames'));
$router->map('/admin/games2.html', 'admin_games:games2', array('methods' => 'GET,POST', 'name' => 'admingames2'));
$router->map('/admin/editgame.html', 'admin_games:editgame', array('methods' => 'GET,POST', 'name' => 'editgame'));
$router->map('/admin/mobilegamse.html', 'admin_games:mobilegamse', array('methods' => 'GET,POST', 'name' => 'admin-poolimobilegamse'));
$router->map('/admin/brokengames.html', 'admin_games:games_broken', array('methods' => 'GET,POST', 'name' => 'admingamesbroken'));
$router->map('/admin/gamecomments.html', 'admin_games:gamecomments', array('methods' => 'GET,POST', 'name' => 'admingamecomments'));
$router->map('/admin/submitedgames.html', 'admin_games:games_submited', array('methods' => 'GET,POST', 'name' => 'adminsubmitedgames'));
$router->map('/admin/importpacks.html', 'admin_games:games_import', array('methods' => 'GET,POST', 'name' => 'adminimportpacks'));


$router->map('/admin/pages.html', 'admin_contents:pages', array('methods' => 'GET,POST', 'name' => 'adminpages'));
$router->map('/admin/blocks.html', 'admin_contents:blocks', array('methods' => 'GET,POST', 'name' => 'adminblocks'));


$router->map('/admin/members.html', 'admin_members:members', array('methods' => 'GET,POST', 'name' => 'adminmembers'));
$router->map('/admin/bannedmembers.html', 'admin_members:bannedmembers', array('methods' => 'GET,POST', 'name' => 'adminbannedmembers'));
$router->map('/admin/massemail.html', 'admin_members:massemail', array('methods' => 'GET,POST', 'name' => 'adminmassemail'));
$router->map('/admin/adminprofile.html', 'admin_members:adminprofile', array('methods' => 'GET,POST', 'name' => 'admin-profile'));

////configuration
$router->map('/admin/seo.html', 'admin_configurations:seo', array('methods' => 'GET,POST', 'name' => 'adminseo'));
$router->map('/admin/mainsetting.html', 'admin_configurations:mainsetting', array('methods' => 'GET,POST', 'name' => 'adminmainsetting'));
$router->map('/admin/commentsetting.html', 'admin_configurations:commentsetting', array('methods' => 'GET,POST', 'name' => 'admin-commentsetting'));
$router->map('/admin/gamesubmission.html', 'admin_configurations:gamesubmission', array('methods' => 'GET,POST', 'name' => 'admin-gamesubmission'));
$router->map('/admin/membersetting.html', 'admin_configurations:membersetting', array('methods' => 'GET,POST', 'name' => 'admin-membersetting'));
$router->map('/admin/cachesetting.html', 'admin_configurations:cachesetting', array('methods' => 'GET,POST', 'name' => 'admin-cachesetting'));
$router->map('/admin/sitemapsetting.html', 'admin_configurations:sitemapsetting', array('methods' => 'GET,POST', 'name' => 'admin-sitemapsetting'));
$router->map('/admin/scriptsetting.html', 'admin_configurations:scriptsetting', array('methods' => 'GET,POST', 'name' => 'admin-scriptsetting'));
$router->map('/admin/arcadeboostersetting.html', 'admin_configurations:arcadeboostersetting', array('methods' => 'GET,POST', 'name' => 'admin-arcadeboostersetting'));
$router->map('/admin/feedsetting.html', 'admin_configurations:feedsetting', array('methods' => 'GET,POST', 'name' => 'admin-feedsetting'));
$router->map('/admin/linkexchangesetting.html', 'admin_configurations:linkexchange', array('methods' => 'GET,POST', 'name' => 'admin-linkexchange-setting'));
$router->map('/admin/tradesetting.html', 'admin_configurations:tradesetting', array('methods' => 'GET,POST', 'name' => 'admin-poolitradesetting'));
$router->map('/admin/themesetting.html', 'admin_configurations:themesetting', array('methods' => 'GET,POST', 'name' => 'admin-themesettings'));
$router->map('/admin/updatescript.html', 'admin_update:updatescript', array('methods' => 'GET,POST', 'name' => 'admin-updatescript'));

$router->map('/admin/links.html', 'admin_links:links', array('methods' => 'GET,POST', 'name' => 'adminlinks'));
$router->map('/admin/linkexchange.html', 'admin_links:link_exchange', array('methods' => 'GET,POST', 'name' => 'adminlinkexchange'));
$router->map('/admin/requestlink.html', 'admin_links:request_comments', array('methods' => 'GET,POST', 'name' => 'adminrequestlink'));

$router->map('/admin/linksale.html', 'admin_posts:link_sale', array('methods' => 'GET,POST', 'name' => 'adminlinksale'));
$router->map('/admin/sitesale.html', 'admin_posts:site_sale', array('methods' => 'GET,POST', 'name' => 'adminsitesale'));
$router->map('/admin/domainsale.html', 'admin_posts:domain_sale', array('methods' => 'GET,POST', 'name' => 'admindomainsale'));
$router->map('/admin/gamesponsorship.html', 'admin_posts:game_sponsorship', array('methods' => 'GET,POST', 'name' => 'admingamesponsorship'));
$router->map('/admin/linkexchangerequest.html', 'admin_posts:link_exchange', array('methods' => 'GET,POST', 'name' => 'adminlinkexchangerequests'));
$router->map('/admin/arcadediscussions.html', 'admin_posts:arcade_discussions', array('methods' => 'GET,POST', 'name' => 'adminarcadediscussions'));

#zone
$router->map('/admin/zone.html', 'admin_ads:zone', array('methods' => 'GET,POST', 'name' => 'admin-zone'));
$router->map('/admin/ads.html', 'admin_ads:ads', array('methods' => 'GET,POST', 'name' => 'admin-ads'));
$router->map('/admin/countries.html', 'admin_ads:countries', array('methods' => 'GET,POST', 'name' => 'admincountriesads'));

#traders 
$router->map('/admin/traders.html', 'admin_traders:traders', array('methods' => 'GET,POST', 'name' => 'admin-poolitraders'));
$router->map('/admin/traderequests.html', 'admin_traders:request_comments', array('methods' => 'GET,POST', 'name' => 'admin-poolitraderequest'));
$router->map('/admin/plugs.html', 'admin_traders:plugs', array('methods' => 'GET,POST', 'name' => 'admin-pooliplugs'));
$router->map('/admin/tradergeo.html', 'admin_traders:trader_geo_report', array('methods' => 'GET,POST', 'name' => 'admin-poolitradergeo'));
$router->map('/admin/traderhistory.html', 'admin_traders:trader_history', array('methods' => 'GET,POST', 'name' => 'admin-poolihistory'));
$router->map('/trade/trtradedetect.html', 'admin_traders:interface_trader_detector', array('methods' => 'GET,POST', 'name' => 'interface-trtradedetect'));
$router->map('/trade/generateplugs.html', 'admin_traders:interface_generate_plugs', array('methods' => 'GET,POST', 'name' => 'interface-generateplugs'));

#Reports
$router->map('/admin/repabstats.html', 'admin_reports:ab_stats', array('methods' => 'GET,POST', 'name' => 'repabstats'));
$router->map('/admin/trafficreport.html', 'admin_reports:traffic_report', array('methods' => 'GET,POST', 'name' => 'trafficreport'));
$router->map('/admin/georeport.html', 'admin_reports:geo_report', array('methods' => 'GET,POST', 'name' => 'georeport'));

#feed
$router->map('/admin/latestfeeds.html', 'Admin_feeds:latest_feed', array('methods' => 'GET,POST', 'name' => 'adminlatestfeeds'));
$router->map('/admin/revenuegames.html', 'Admin_feeds:revenue_games', array('methods' => 'GET,POST', 'name' => 'adminrevenuegames'));
$router->map('/admin/feedopen.html', 'Admin_feeds:feed_open', array('methods' => 'GET,POST', 'name' => 'adminopenfeed'));

#comments
$router->map('/admin/comments.html', 'Admin_posts:comments', array('methods' => 'GET,POST', 'name' => 'admincomments'));

#Cron Jobs
$router->map('/admin/cjobs/manageablerun.html', 'Admin_cjobs:manageable_run', array('methods' => 'GET', 'name' => 'admincjobsmanageablerun'));
$router->map('/admin/cjobs/manageblerun.html', 'Admin_cjobs:manageable_run', array('methods' => 'GET', 'name' => 'admincjobsmanageablerun'));
$router->map('/admin/cjobs/manageablerun.php', 'Admin_cjobs:manageable_run', array('methods' => 'GET', 'name' => 'admincjobsmanageablerun'));

#-----------------------------------------------------------------------------------------------------------
//********************************* Site interface Routers ***********************************
#-----------------------------------------------------------------------------------------------------------

$router->map('/', 'game:page_index', array('methods' => 'GET,POST', 'name' => 'homepage'));
$router->map('/index.html', 'game:page_index', array('methods' => 'GET', 'name' => 'hompage'));

$router->map('/ajaxgate.html', 'game:ajaxgate', array('methods' => 'GET,POST', 'name' => 'ajaxgate'));
$router->map('/sys/:action', 'systems:action', array('methods' => 'GET', 'name' => 'sysinfo'));
$router->map('/sys/:action.html', 'systems:action', array('methods' => 'GET', 'name' => 'sysinfo'));
$router->map('/sys/check/modrewrite.html', 'systems:chkmodrewrite', array('methods' => 'GET', 'name' => 'chkmodrewrite'));
$router->map('/404.html', 'page:page404', array('methods' => 'GET', 'name' => 'page404'));
$router->map('/maintenance.html', 'page:page_maintenance', array('methods' => 'GET', 'name' => 'maintenance'));
$router->map('/sitemap.html', 'game:sitemap', array('methods' => 'GET', 'name' => 'sitemap'));

$router->map('/users/login.html', 'users:login', array('methods' => 'GET,POST', 'name' => 'userlogin'));
$router->map('/users/logout.html', 'users:logout', array('methods' => 'GET', 'name' => 'userlogout'));
$router->map('/users/profile.html', 'users:profile', array('methods' => 'GET,POST', 'name' => 'userprofile'));
$router->map('/users/dashboard.html', 'users:dashboard', array('methods' => 'GET', 'name' => 'userdashboard'));
$router->map('/users/contact.html', 'users:contact', array('methods' => 'GET,POST', 'name' => 'usercontact'));
$router->map('/users/signup.html', 'users:signup', array('methods' => 'GET,POST', 'name' => 'usersignup'));
$router->map('/users/forget.html', 'users:forget', array('methods' => 'GET,POST', 'name' => 'userforget'));
$router->map('/favorites.html', 'game:page_favorites', array('methods' => 'GET', 'name' => 'favorites'));
$router->map('/users/favorites.html', 'game:page_favorites', array('methods' => 'GET', 'name' => 'userfavorites'));
$router->map('/users/favorites/page-:page.html', 'game:page_favorites', array('methods' => 'GET', 'name' => 'userfavorites_page'));
$router->map('/users/addtofavorite.html', 'users:addtofavorit', array('methods' => 'GET', 'name' => 'useraddtofavorit'));
$router->map('/users/submission.html', 'users:users_submission', array('methods' => 'GET,POST', 'name' => 'usersubmission'));

$router->map('/newgames.html', 'game:page_new_games', array('methods' => 'GET', 'name' => 'newgames'));
$router->map('/newgames/page-:page.html', 'game:page_new_games', array('methods' => 'GET', 'name' => 'newgames_page', 'filters' => array('page' => '(\d+)')));
$router->map('/newgames/:category_seo.html', 'game:page_new_games', array('methods' => 'GET', 'name' => 'newgames_cat'));
$router->map('/newgames/:category_seo/page-:page.html', 'game:page_new_games', array('methods' => 'GET', 'name' => 'newgames_cat_page', 'filters' => array('page' => '(\d+)')));

$router->map('/populargames/today.html', 'game:page_popular_games_today', array('methods' => 'GET', 'name' => 'populargamestoday'));
$router->map('/populargames/today/page-:page.html', 'game:page_popular_games_today', array('methods' => 'GET', 'name' => 'populargamestoday_page', 'filters' => array('page' => '(\d+)')));
$router->map('/populargames/today/:category_seo.html', 'game:page_popular_games_today', array('methods' => 'GET', 'name' => 'populargamestoday_cat'));
$router->map('/populargames/today/:category_seo/page-:page.html', 'game:page_popular_games_today', array('methods' => 'GET', 'name' => 'populargamestoday_cat_page', 'filters' => array('page' => '(\d+)')));

$router->map('/populargames.html', 'game:page_popular_games', array('methods' => 'GET', 'name' => 'populargames'));
$router->map('/populargames/page-:page.html', 'game:page_popular_games', array('methods' => 'GET', 'name' => 'populargames_page', 'filters' => array('page' => '(\d+)')));
$router->map('/populargames/:category_seo.html', 'game:page_popular_games', array('methods' => 'GET', 'name' => 'populargames_cat'));
$router->map('/populargames/:category_seo/page-:page.html', 'game:page_popular_games', array('methods' => 'GET', 'name' => 'populargames_cat_page', 'filters' => array('page' => '(\d+)')));

$router->map('/toprategames.html', 'game:page_top_rate_games', array('methods' => 'GET', 'name' => 'toprategames'));
$router->map('/toprategames/page-:page.html', 'game:page_top_rate_games', array('methods' => 'GET', 'name' => 'toprategames_page', 'filters' => array('page' => '(\d+)')));
$router->map('/toprategames/:category_seo.html', 'game:page_top_rate_games', array('methods' => 'GET', 'name' => 'toprategames_cat'));
$router->map('/toprategames/:category_seo/page-:page.html', 'game:page_top_rate_games', array('methods' => 'GET', 'name' => 'toprategames_cat_page', 'filters' => array('page' => '(\d+)')));

$router->map('/lastplayed.html', 'game:page_last_played_games', array('methods' => 'GET', 'name' => 'lastplayedgames'));
$router->map('/lastplayed/page-:page.html', 'game:page_last_played_games', array('methods' => 'GET', 'name' => 'lastplayedgames_page', 'filters' => array('page' => '(\d+)')));

$router->map('/featured.html', 'game:page_featured_games', array('methods' => 'GET', 'name' => 'featuredgames'));
$router->map('/featured/page-:page.html', 'game:page_featured_games', array('methods' => 'GET', 'name' => 'featuredgames_page', 'filters' => array('page' => '(\d+)')));

$router->map('/links.html', 'page:links', array('methods' => 'GET', 'name' => 'links'));

$router->map('/dosearch.html', 'game:dosearch', array('methods' => 'GET,POST', 'name' => 'dosearch'));
$router->map('/search/:text.html', 'game:page_search', array('methods' => 'GET', 'name' => 'search'));
$router->map('/search/:text/page-:page.html', 'game:page_search', array('methods' => 'GET', 'name' => 'search_page', 'filters' => array('page' => '(\d+)')));
$router->map('/search/:category_seo/:text.html', 'game:page_search', array('methods' => 'GET', 'name' => 'search_cat'));
$router->map('/search/:category_seo/:text/page-:page.html', 'game:page_search', array('methods' => 'GET', 'name' => 'search_cat_page', 'filters' => array('page' => '(\d+)')));

$router->map('/tr/:gid/:game_seo.html', 'game:shootout', array('methods' => 'GET', 'name' => 'visitorshootout', 'filters' => array('gid' => '(\d+)')));

$router->map('/all.html', 'game:page_all', array('methods' => 'GET', 'name' => 'allgames'));
$router->map('/games/page-:page.html', 'game:page_all', array('methods' => 'GET', 'name' => 'allgames_page', array('page' => '(\d+)')));
$router->map('/games/:category_seo.html', 'game:page_all', array('methods' => 'GET', 'name' => 'allgames_cat'));
$router->map('/games/:category_seo/page-:page.html', 'game:page_all', array('methods' => 'GET', 'name' => 'allgames_cat_page', 'filters' => array('page' => '(\d+)')));

$router->map('/pages/:page_seo.html', 'page:show', array('methods' => 'GET', 'name' => 'page'));

$router->map('/tags/:tag_seo.html', 'game:page_tag', array('methods' => 'GET', 'name' => 'tag'));
$router->map('/tags/:tag_seo/page-:page.html', 'game:page_tag', array('methods' => 'GET', 'name' => 'tag_page', 'filters' => array('page' => '(\d+)')));


$router->map('/view/:category_seo/:game_seo.html', 'game:page_pre', array('methods' => 'GET,POST', 'name' => 'pregame'));
$router->map('/view/:game_seo.html', 'game:page_pre', array('methods' => 'GET,POST', 'name' => 'pregame2'));
$router->map('/play/:category_seo/:game_seo.html', 'game:page_play', array('methods' => 'GET,POST', 'name' => 'playgame'));
$router->map('/play/:game_seo.html', 'game:page_play', array('methods' => 'GET,POST', 'name' => 'playgame2'));