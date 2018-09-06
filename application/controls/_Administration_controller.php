<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: _Administration_controller.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class AdministrationController extends BaseController
{

    function __construct()
    {
        parent::__construct();
        $this->MapViewThemesFolder('/application/admin');
        $this->MapViewTemeplateName('default');
        $this->MapViewFolder(null);

        function _syserror_get_msgID()
        {
            $i = 0;
            while (++$i <= 100) {
                if (!isAlert('syserror' . $i))
                    return 'syserror' . $i;
            }
        }

        $this->language_functions_init();

        if (defined('CONFIG_DB_AFFECTING')) {
            if (Admin::isLogin() && preg_match('/(?:www\.)?arcadebooster\.org/i', HOST_NAME) && Admin::data('username') == 'demo')
                $_SESSION['DB_AFFECTING'] = false;
            else
                $_SESSION['DB_AFFECTING'] = true;
        }

        function _dbaffecting()
        {
            return (!isset($_SESSION['DB_AFFECTING']) || $_SESSION['DB_AFFECTING']);
        }

        if (!file_exists(ab_upload_dir))
            if (!rmkdir(ab_upload_dir))
                perror('<strong>' . L::global_error . '! </strong> - ' . L::alert_no_folder_permission . ' /content/')->Id('syserror');


        if (!file_exists(ab_tmp_dir))
            rmkdir(ab_tmp_dir);

        /* games */
        if (!file_exists(ab_game_files_dir))
            rmkdir(ab_game_files_dir);
        if (!file_exists(ab_game_images_dir))
            rmkdir(ab_game_images_dir);

        /* submission */
        if (!file_exists(ab_submission_files_dir))
            rmkdir(ab_submission_files_dir);
        if (!file_exists(ab_submission_images_dir))
            rmkdir(ab_submission_images_dir);

        if (file_exists(root_path() . '/_pfiles') || file_exists(root_path() . '/_dbchanges'))
            $this->install_manual_patches();

        if (!isset($_COOKIE['dismissCronjobs_error']) && generatingStats()) {
            if (!isset($_SESSION['cronjobs_error'])) {
                $model = new Visit_daily;
                if (!$model->select()->where(array(
                    "`date`>='" . date("Y-m-d", strtotime('-2 days')) . "'",
                    "`date`<='" . date("Y-m-d", strtotime('-1 days')) . "'"
                ))->exec()->found()
                )
                    $_SESSION['cronjobs_error'] = true;
            }
            if (isset($_SESSION['cronjobs_error'])) {
                perror('<strong data-cookiename="dismissCronjobs_error">' . L::global_warning . '! </strong>' . L::alert_cronjobs_warning . ' <a href="' . root_url() . '/Read Me.txt">' . L::global_instruction . '</a>')->Id('syserror');
            }
        }

        /* master paths */
        define('master_upload_url', master_url . '/content/upload');
        define('master_theme_images_dir', master_url . '/content/upload/themes/images');

        define('master_feed_games_images_dir', master_url . '/content/upload/games/images');
        define('master_feed_games_file_dir', master_url . '/content/upload/games/files');

        define('master_rev_sharing_games_images_dir', master_url . '/content/upload/devgames/thumbnails');
        define('master_rev_sharing_games_file_dir', master_url . '/content/upload/devgames/files');

        define('master_import_games_images_dir', master_url . '/content/upload/import/images');
        define('master_import_games_file_dir', master_url . '/content/upload/import/files');


        if ($datatable_ipp = setting::get_data('datatable_ipp', 'val'))
            define('datatable_ipp', $datatable_ipp);
        else
            define('datatable_ipp', 10);


        /* =============================== */
        /* ======== Daily Ws Func ======== */

        function get_daily_ws($index = null)
        {
            static $data;
            if (!function_exists('syncdailyws')) {

                function syncdailyws()
                {
                    pengu_user_load_class('ws', $ws);
                    $result = $ws->get_from_feed_by_ws('webservicesController.get_daily_packdata');
                    if (!is_array($result) || empty($result))
                        return false;
                    return $result;
                }

            }
            if (!isset($data)) {
                $s = new pengu_cache(cache_path() . '/etc/ws', 'data_');
                $s->setCacheKey('daily_ws_data');
                $s->expireTime(24 * 3600);
                if ($s->isCached()) {
                    $data = $s->read();
                } else {
                    $data = syncdailyws();

                    /* ==== some custom function === */
                    //-- revenue sharing
                    if (!empty($data['latest_revenue_sharing_games'])) {
                        foreach ($data['latest_revenue_sharing_games'] as &$feed) {
                            if (Game::check_InstalledFromFeed($feed['fid'])) {
                                $feed['installed'] = true;
                            }
                        }
                    }
                    //-- latest themes
                    if (!empty($data['latest_themes'])) {
                        foreach ($data['latest_themes'] as &$theme) {
                            if (!empty($theme['thumb'])) {
                                $theme['thumb'] = master_theme_images_dir . "/{$theme['thumb']}";
                            }
                        }
                    }
                    //-- feed vars
                    if (!empty($data['feedvars'])) {
                        Setting::save_value('feed', 'feed_categories', @$data['feedvars']['feedcats']);
                        Setting::save_value('feed', 'feed_source', @$data['feedvars']['feedsource']);
                    }

                    $s->write($data);
                }
            }
            if (isset($data)) {
                if (isset($data[$index]))
                    return $data[$index];
                elseif ($index === null)
                    return $data;
            }
            return false;
        }

        /* -------------EndWsFunc------------- */
        /* ----------------------------------- */
        pengu_user_load_lib('ab_admin_backup');

        if (isset($_GET['showswf'])) {
            $this->preview_swf($_GET['showswf']);
            exit;
        }

        if (isset($_GET['changetheme']) && in_array($_GET['changetheme'], array('dark', 'blue', 'brown', 'eastern_blue', 'green', 'tamarillo'))) {
            setcookie('ab_admin_theme', $_GET['changetheme'], time() + 256 * 24 * 3600, '/');
            ref(url::itself()->fulluri(array('changetheme' => null)))->redirect();
        }

        if (!validate::_is_ajax_request()) {
            MsgOptions::set(array(
                'css' => array(
                    'info' => 'alert alert-info ',
                    'success' => 'alert alert-success',
                    'warning' => 'alert alert-warning',
                    'error' => 'alert alert-error',
                    'maintenance' => ''
                ),
                'padding' => 10
            ));
            pengu_user_load_lib('ab_admin_cssjs_include_ulib');
            $this->get_all_statuses();
        }

        if (isset($_GET['generatesitemap'])) {
            $sitemap = $this->generate_sitemap();
            if (!validate::_is_ajax_request())
                ref(url::itself()->fulluri(array('generatesitemap' => null)))->redirect();
            if ($sitemap > -1)
                exit('1');
            else
                exit('0');
        }

        if (isset($_GET['delallcaches'])) {
            $this->cleanAllCache();
            if (route_name() == 'admindashboard')
                psuccess("All caches were deleted.")->Id('syserror');
            if (!validate::_is_ajax_request())
                ref(url::itself()->fulluri(array('delallcaches' => null)))->redirect();
            exit('1');
        }
        if (isset($_GET['delmysqlcaches'])) {
            $this->cleanMysqlCache();
            if (!validate::_is_ajax_request())
                ref(url::itself()->fulluri(array('delmysqlcaches' => null)))->redirect();
            exit('1');
        }


        function get_available_version()
        {
            return get_daily_ws('available_version');
        }

        global $agoLanguage;
        $agoLanguage = array(
            'style' => array(
                'rtl' => 'style="direction:rtl;"',
                'ltr' => 'style="direction:ltr;"')
        ,
            'times' => array(
                'single' => array(L::global_second, L::global_minute, L::global_hour, L::global_day, L::global_week, L::global_month, L::global_year, L::global_Decade),
                'plural' => array(L::global_seconds, L::global_minutes, L::global_hours, L::global_days, L::global_week, L::global_months, L::global_years, L::global_Decades),
            ),
            'ago' => L::global_ago
        );
    }

    function language_functions_init()
    {
        /* language setup */
        if (isset($_GET['lang']) && agent::code_to_lang($_GET['lang']) != false) {
            setcookie('ab_admin_lang', strtolower($_GET['lang']), time() + 3600 * 24 * 7, '/');
            $_SESSION['ab_admin_lang'] = strtolower($_GET['lang']);
        }

        if (!isset($_SESSION['ab_admin_lang']) && isset($_COOKIE['ab_admin_lang'])) {
            $_SESSION['ab_admin_lang'] = $_COOKIE['ab_admin_lang'];
        }

        function lang()
        {
            return isset($_SESSION['ab_admin_lang']) ? $_SESSION['ab_admin_lang'] : 'en';
        }

        function lang_country_code()
        {
            if (isset($_SESSION['ab_admin_lang'])) {
                switch ($_SESSION['ab_admin_lang']) {
                    case 'fa':
                        return 'ir';
                        break;
                    case 'en':
                        return 'gb';
                        break;
                    case 'ar':
                        return 'sa';
                        break;
                    case 'pt':
                        return 'br';
                        break;
                    case 'ur':
                        return 'pk';
                        break;
                    case 'tr':
                        return 'tr';
                        break;
                    case 'hi':
                        return 'in';
                        break;
                    case 'es':
                        return 'es';
                        break;
                    case 'de':
                        return 'de';
                        break;
                    case 'fr':
                        return 'fr';
                        break;
                    case 'it':
                        return 'it';
                        break;
                }
            } else
                return 'gb';
        }

        function lang_isrtl()
        {
            return in_array(lang(), array('fa', 'ar', 'ur')) ? true : false;
        }

        PenguI18n::install(cache_path() . '/lang/admin');
        /* ================ */
    }

    function get_all_statuses()
    {

        //==Broken Games 
        $model = new Game_broken();
        $brokenGames_unread = $model->select()->where("ifnull(status,0)=0")->getcount();
        $this->view->brokenGames_unread = $brokenGames_unread;

        //==Comment Games 
        $model = new Member();
        $unactive_member = $model->alias('M')->select()
            ->innerjoin('abs_games', 'G')->on('C.group=G.gid')
            ->innerjoin('abs_members_group', 'G')->on('M.group=G.id')
            ->where("ifnull(M.status,0)=0")
            ->getcount();
        $this->view->unactive_member = $unactive_member;

        //==Comment Games 
        $model = new Comment();
        $commentGames_unread = $model->alias('C')
            ->innerjoin('abs_games', 'G')->on('C.group=G.gid')
            ->where("ifnull(C.reviewed,0)=0")
            ->getcount();
        $this->view->gamecomment_unread = $commentGames_unread;

        //==Link Exchange Requests 
        $model = new Comment();
        $linkRequests_unread = $model
            ->where(array("ifnull(status,0)=0", 'type' => 2))
            ->getcount();
        $this->view->linkRequests_unread = $linkRequests_unread;

        //==Trade Requests 
        $model = new Comment();
        $tradeRequests_unread = $model
            ->where(array("ifnull(status,0)=0", 'type' => 1))
            ->getcount();
        $this->view->tradeRequests_unread = $tradeRequests_unread;

        //==Messages 
        $model = new Comment();
        $comments_unread = $model
            ->where(array("ifnull(status,0)=0", 'type in (3,4,5)'))
            ->getcount();
        $this->view->comments_unread = $comments_unread;

        //==Submited Games 
        $model = new Game_submited();
        $submitedGames_unread = $model->alias('s')
            ->leftjoin('abs_members')->on('abs_members.id=s.user_id')
            ->where("ifnull(s.status,0)=0")
            ->getcount();
        $this->view->submitedGames_unread = $submitedGames_unread;


        if (!isLocalServer()) {
            //$this->marketplace();
        }
    }

    private function marketplace()
    {

        if (!isset($_COOKIE['synced_ws_marketplace'])) {
            setcookie('synced_ws_marketplace', 1, time() + 3600, '/');

            function sync($post_type)
            {
                /*  CAll WebService  */
                pengu_user_load_class('ws', $ws);
                $result = $ws->get_from_main_by_ws('webservicesController.get_post_list', array(
                    $post_type,
                    intval(setting::get_data('last_id_' . $post_type, 'val'))
                ));
                if (!is_array($result) || empty($result))
                    return false;
                $model = new Post_Log();
                foreach ($result as $key => $val) {
                    $model->insert(array_merge($val, array('ws_time' => pengu_date()->getTimeStamp(), 'ws_date' => pengu_date()->toString('Y-m-d'))))->exec();
                }
                /* save last id */
                end($result);
                if ($last = current($result))
                    Setting::save_value('status', 'last_id_' . $post_type, $last['post_id']);
            }

            sync(1);
            sync(2);
            sync(3);
            sync(4);
            sync(5);
            sync(6);
        }
        /* Number of link sale */

        $model = new Post_Log();
        $data = $model->select('post_type,count(*) as cnt')->where(array('is_read' => 0))->groupby('post_type')->exec();

        $total = 0;
        $this->view->link_sale = 0;
        $this->view->arcades_for_sale = 0;
        $this->view->domain_for_sale = 0;
        $this->view->game_sponsership = 0;
        $this->view->link_exchanges = 0;
        $this->view->requests = 0;

        while ($data->fetch()) {
            switch ($data->current()->post_type) {
                case 1:
                    $this->view->link_sale = intval($data->current()->cnt);
                    break;
                case 2:
                    $this->view->arcades_for_sale = intval($data->current()->cnt);
                    break;
                case 3:
                    $this->view->domain_for_sale = intval($data->current()->cnt);
                    break;
                case 4:
                    $this->view->game_sponsership = intval($data->current()->cnt);
                    break;
                case 5:
                    $this->view->link_exchanges = intval($data->current()->cnt);
                    break;
                case 6:
                    $this->view->requests = intval($data->current()->cnt);
                    break;
            }
            $total += intval($data->current()->cnt);
        }

        $this->view->markateplace_total = $total;
    }

    protected function islogin()
    {
        global $route;
        $model = new Admin;
        if (!validate::_is_ajax_request()) {
            if (!$model->isLogin() && $route->getName() == 'admindashboard') {
                ref(url::router('adminlogin'))->redirect();
                exit;
            }
            $model->setLogoutPage(url::router('adminlogin'));
        }
        if (!$model->CheckLogin() && validate::_is_ajax_request())
            exit(L::alert_session_expired);

        $permission_access[4] = 'adminchangepass,adminchangetopremium,admindashboard,admingames,admingames2,editgame,admin-poolimobilegamse,admingamesbroken,adminsubmitedgames,adminimportpacks,admin-profile,adminlatestfeeds,adminrevenuegames,adminopenfeed';

        if (isset($permission_access[Admin::data('group')])) {
            if (strpos($permission_access[Admin::data('group')], route_name()) === false)
                $this->forbidden();
        }
    }

    protected function cleanAllCache()
    {
        rrmdir(tmp_path() . '/cache', '*.*');
        rrmdir(tmp_path() . '/logs', '*.*');
    }

    protected function cleanMysqlCache($folder = null)
    {
        rrmdir(cache_path() . '/mysql' . path::leftSlashes($folder), '*.dat');
    }

    function generate_sitemap2()
    {
        if (!convert::to_bool(setting::get_data('sitemap_generating', 'val')))
            return -1;
        $filename = setting::get_data('sitemap_file_name', 'val');


        function create_MultiSitemap($filepath, $content)
        {
            if ($fileHandle = @fopen($filepath, "w")) {
                fwrite($fileHandle, '<?xml version="1.0" encoding="UTF-8"?> <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL);
                if (is_array($content)) {
                    foreach ($content as $url) {
                        $c = "<sitemap> <loc>{$url}</loc><lastmod>" . date('Y-m-d') . "</lastmod></sitemap>" . PHP_EOL;
                        fwrite($fileHandle, $c);
                    }

                } else
                    fwrite($fileHandle, $content);

                fwrite($fileHandle, '</sitemapindex>');
                fclose($fileHandle);
                return true;
            }
            return false;
        }

        function create_sitemap($filepath, $content)
        {
            if ($fileHandle = @fopen($filepath, "w")) {

                fwrite($fileHandle,
                    '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
                    '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"> ' . PHP_EOL
                );
                if (is_array($content))
                    fwrite($fileHandle, join(PHP_EOL, $content));
                else
                    fwrite($fileHandle, $content);

                fwrite($fileHandle, '</urlset>');
                fclose($fileHandle);
                return true;
            }
            return false;
        }


        $host = lib::wwwurl(HOST_URL);


        /* Root */
        $xml ['main'][] = '<url>' . PHP_EOL .
            '<loc>' . lib::wwwurl($host) . '</loc>' . PHP_EOL .
            '<lastmod>' . date('Y-m-d') . '</lastmod>' . PHP_EOL .
            '<changefreq>daily</changefreq>' . PHP_EOL .
            '<priority>0.9</priority>' . PHP_EOL .
            '</url>';

        /* Categories */
        $model = new Category();
        $catsData = $model->AllCategories(null, false);

        if (!empty($catsData)) {
            foreach ($catsData as $cat) {
                if (empty($cat['seo_title']))
                    continue;
                $url_without_page = url::router('allgames_cat', array(
                    'category_id' => $cat['cid'],
                    'category_seo' => $cat['seo_title']
                ));

                //-- pages
                $theme_setting_path = ROOT_PATH . '/' . DEFAUT_THEMES_DIR . '/' . DefaultTemplate . '/theme_setting.php';
                if (file_exists($theme_setting_path))
                    include_once $theme_setting_path;
                if (defined('_item_per_category_page')) {
                    $model2 = new Game;
                    $model2->getGames(null, $cat['seo_title']);


                    $pg = new pengu_pagination;
                    $pg->items_per_page = _item_per_category_page;
                    $pg->merge_model($model2);
                    $i = 0;
                    while (++$i <= $pg->total_pages) {
                        if (defined('_indexing_type') && _indexing_type == 'ajax-crawlable') {
                            $xml ['cats'][] = '<url>' . PHP_EOL .
                                '<loc>' . $host . $url_without_page . '#!page' . $i . '</loc>' . PHP_EOL .
                                '<lastmod>' . date('Y-m-d') . '</lastmod>' . PHP_EOL .
                                '<changefreq>daily</changefreq>' . PHP_EOL .
                                '<priority>0.8</priority>' . PHP_EOL .
                                '</url>';
                        } else {
                            $url_paging = url::router('allgames_cat_page', array(
                                'category_id' => $cat['cid'],
                                'category_seo' => $cat['seo_title'],
                                'page' => $i
                            ));
                            $xml ['cats'][] = '<url>' . PHP_EOL .
                                '<loc>' . $host . $url_paging . '</loc>' . PHP_EOL .
                                '<lastmod>' . date('Y-m-d') . '</lastmod>' . PHP_EOL .
                                '<changefreq>daily</changefreq>' . PHP_EOL .
                                '<priority>0.8</priority>' . PHP_EOL .
                                '</url>';
                        }
                    }
                }
            }
        }
        /* Tags */
        /*
        $model = new Game_tag();
        $tagsData = $model->Alltags(null, false);
        if (!empty($tagsData)) {
            foreach ($tagsData as $tag) {
                if (empty($tag['seo_name']))
                    continue;
                $url = url::router('tag', array(
                    'tag_id' => $tag['id'],
                    'tag_seo' => $tag['seo_name']
                ));
                $xml ['tags'][] = '<url>' . PHP_EOL .
                    '<loc>' . $host . $url . '</loc>' . PHP_EOL .
                    '<lastmod>' . date('Y-m-d') . '</lastmod>' . PHP_EOL .
                    '<changefreq>daily</changefreq>' . PHP_EOL .
                    '<priority>0.8</priority>' . PHP_EOL .
                    '</url>';
            }
        }
        */

        $showpre = convert::to_bool(setting::get_data('show_prepage', 'val'));

        $model = new Game;
        $numgames = $model->select()->getcount();
        $step = 10000;
        for ($i = 0; $i < ceil($numgames / $step); $i++) {
            $start = ($i * $step);
            $res = $model->getGames()->limit($start, $step)->exec();

            if ($res->numrows()) {
                while ($game = $res->fetch()) {

                    //pre-page
                    if ($showpre) {
                        if (!empty($game->category_seotitle))
                            $url = $host . url::router('pregame', array(
                                    'category_seo' => @$game->category_seotitle,
                                    'category_id' => $game->category_id,
                                    'game_id' => $game->id,
                                    'game_seo' => $game->seotitle
                                ))->fulluri();
                        else
                            $url = $host . url::router('pregame2', array(
                                    'game_id' => $game->id,
                                    'game_seo' => $game->seotitle
                                ))->fulluri();
                        $xml ['games'][] = '<url>' . PHP_EOL .
                            '<loc>' . $url . '</loc>' . PHP_EOL .
                            '<lastmod>' . ($game->upd_date > 0 ? date('Y-m-d', $game->upd_date) : date('Y-m-d')) . '</lastmod>' . PHP_EOL .
                            '<changefreq>weekly</changefreq>' . PHP_EOL .
                            '<priority>0.6</priority>' . PHP_EOL .
                            '</url>';
                    }

                    //play-page
                    if (!empty($game->category_seotitle))
                        $url = $host . url::router('playgame', array(
                                'category_seo' => @$game->category_seotitle,
                                'category_id' => $game->category_id,
                                'game_id' => $game->id,
                                'game_seo' => $game->seotitle
                            ))->fulluri();
                    else
                        $url = $host . url::router('playgame2', array(
                                'game_id' => $game->id,
                                'game_seo' => $game->seotitle
                            ))->fulluri();
                    $xml ['games'][] = '<url>' . PHP_EOL .
                        '<loc>' . $url . '</loc>' . PHP_EOL .
                        '<lastmod>' . ($game->upd_date > 0 ? date('Y-m-d', $game->upd_date) : date('Y-m-d')) . '</lastmod>' . PHP_EOL .
                        '<changefreq>weekly</changefreq>' . PHP_EOL .
                        '<priority>0.6</priority>' . PHP_EOL .
                        '</url>';
                }
            }
        }
        if ($xml) {

            $sitemaps = array();


            if (!file_exists(tmp_path() . '/sitemaps'))
                rmkdir(tmp_path() . '/sitemaps');
            //main sitemap
            if (isset($xml['main'])) {
                create_sitemap(tmp_path() . "/sitemaps/sitemap_internal_links.xml", $xml['main']);
                $sitemaps[] = tmp_url() . "/sitemaps/sitemap_internal_links.xml";
            }

            //cats
            if (isset($xml['cats'])) {
                create_sitemap(tmp_path() . "/sitemaps/sitemap_categories.xml", $xml['cats']);
                $sitemaps[] = tmp_url() . "/sitemaps/sitemap_categories.xml";
            }

            //tags
            /*
            if (isset($xml['tags'])) {
                $splited_xml = array_chunk($xml['tags'], 100);
                $i = 0;
                foreach ($splited_xml as $data) {
                    $i++;
                    create_sitemap(tmp_path() . "/sitemaps/sitemap_tags{$i}.xml", $data);
                    $sitemaps[] = tmp_url() . "/sitemaps/sitemap_tags{$i}.xml";
                }
            }
            */

            //games
            $splited_xml = array_chunk($xml['games'], 100);
            $i = 0;
            foreach ($splited_xml as $data) {
                $i++;
                create_sitemap(tmp_path() . "/sitemaps/sitemap_games{$i}.xml", $data);
                $sitemaps[] = tmp_url() . "/sitemaps/sitemap_games{$i}.xml";
            }

            // saving multiple sitemaps
            if ($sitemaps) {
                create_MultiSitemap(root_path() . "/sitemap.xml", $sitemaps);
            }
        }
        return @memory_get_peak_usage();
    }

    function generate_sitemap()
    {
        if (intval(setting::get_data('sitemap_method', 'val')) == 2)
            return $this->generate_sitemap2();
        else
            return $this->generate_sitemap1();
    }

    function generate_sitemap1()
    {
        if (!convert::to_bool(setting::get_data('sitemap_generating', 'val')))
            return -1;
        $filename = setting::get_data('sitemap_file_name', 'val');
        if ($fileHandle = @fopen(root_path() . "/" . ($filename ? $filename : 'sitemap.xml'), "w")) {
            fwrite($fileHandle, '<?xml version="1.0" encoding="UTF-8"?>' .
                '<?xml-stylesheet type="text/xsl" href="application/static/css/sitemap/sitemap.xsl"?>' . PHP_EOL .
                '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' .
                ' xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ' .
                ' xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9' .
                ' http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . PHP_EOL
            );

            $host = lib::wwwurl(HOST_URL);


            /* Root */
            $xml = '<url>' . PHP_EOL .
                '<loc>' . lib::wwwurl($host) . '</loc>' . PHP_EOL .
                '<lastmod>' . date('Y-m-d') . '</lastmod>' . PHP_EOL .
                '<changefreq>daily</changefreq>' . PHP_EOL .
                '<priority>0.9</priority>' . PHP_EOL .
                '</url>' . PHP_EOL;
            fwrite($fileHandle, $xml);

            /* All Games */
            $xml = '<url>' . PHP_EOL .
                '<loc>' . $host . url::router('allgames') . '</loc>' . PHP_EOL .
                '<lastmod>' . date('Y-m-d') . '</lastmod>' . PHP_EOL .
                '<changefreq>daily</changefreq>' . PHP_EOL .
                '<priority>0.9</priority>' . PHP_EOL .
                '</url>' . PHP_EOL;
            fwrite($fileHandle, $xml);

            /* Categories */
            $model = new Category();
            $catsData = $model->AllCategories(null, false);
            $xml = '';
            if (!empty($catsData)) {
                foreach ($catsData as $cat) {
                    if (empty($cat['seo_title']))
                        continue;
                    $url_without_page = url::router('allgames_cat', array(
                        'category_id' => $cat['cid'],
                        'category_seo' => $cat['seo_title']
                    ));

                    //-- pages
                    $theme_setting_path = ROOT_PATH . '/' . DEFAUT_THEMES_DIR . '/' . DefaultTemplate . '/theme_setting.php';
                    if (file_exists($theme_setting_path))
                        include_once $theme_setting_path;
                    if (defined('_item_per_category_page')) {
                        $model2 = new Game;
                        $model2->getGames(null, $cat['seo_title']);


                        $pg = new pengu_pagination;
                        $pg->items_per_page = _item_per_category_page;
                        $pg->merge_model($model2);
                        $i = 0;
                        while (++$i <= $pg->total_pages) {
                            if (defined('_indexing_type') && _indexing_type == 'ajax-crawlable') {
                                $xml .= '<url>' . PHP_EOL .
                                    '<loc>' . $host . $url_without_page . '#!page' . $i . '</loc>' . PHP_EOL .
                                    '<lastmod>' . date('Y-m-d') . '</lastmod>' . PHP_EOL .
                                    '<changefreq>daily</changefreq>' . PHP_EOL .
                                    '<priority>0.8</priority>' . PHP_EOL .
                                    '</url>' . PHP_EOL;
                            } else {
                                $url_paging = url::router('allgames_cat_page', array(
                                    'category_id' => $cat['cid'],
                                    'category_seo' => $cat['seo_title'],
                                    'page' => $i
                                ));
                                $xml .= '<url>' . PHP_EOL .
                                    '<loc>' . $host . $url_paging . '</loc>' . PHP_EOL .
                                    '<lastmod>' . date('Y-m-d') . '</lastmod>' . PHP_EOL .
                                    '<changefreq>daily</changefreq>' . PHP_EOL .
                                    '<priority>0.8</priority>' . PHP_EOL .
                                    '</url>' . PHP_EOL;
                            }
                        }
                    }
                }
                fwrite($fileHandle, $xml);
            }
            /* Tags */
            /*
            $model = new Game_tag();
            $tagsData = $model->Alltags(null, false);
            $xml = '';
            if (!empty($tagsData)) {
                foreach ($tagsData as $tag) {
                    if (empty($tag['seo_name']))
                        continue;
                    $url = url::router('tag', array(
                        'tag_id' => $tag['id'],
                        'tag_seo' => $tag['seo_name']
                    ));
                    $xml .= '<url>' . PHP_EOL .
                        '<loc>' . $host . $url . '</loc>' . PHP_EOL .
                        '<lastmod>' . date('Y-m-d') . '</lastmod>' . PHP_EOL .
                        '<changefreq>daily</changefreq>' . PHP_EOL .
                        '<priority>0.8</priority>' . PHP_EOL .
                        '</url>' . PHP_EOL;
                }
                fwrite($fileHandle, $xml);
            }
            */

            $showpre = convert::to_bool(setting::get_data('show_prepage', 'val'));

            $model = new Game;
            $numgames = $model->select()->getcount();
            $step = 10000;
            for ($i = 0; $i < ceil($numgames / $step); $i++) {
                $start = ($i * $step);
                $res = $model->getGames()->limit($start, $step)->exec();

                if ($res->numrows()) {
                    $xml = '';
                    while ($game = $res->fetch()) {

                        //pre-page
                        if ($showpre) {
                            if (!empty($game->category_seotitle))
                                $url = $host . url::router('pregame', array(
                                        'category_seo' => @$game->category_seotitle,
                                        'category_id' => $game->category_id,
                                        'game_id' => $game->id,
                                        'game_seo' => $game->seotitle
                                    ))->fulluri();
                            else
                                $url = $host . url::router('pregame2', array(
                                        'game_id' => $game->id,
                                        'game_seo' => $game->seotitle
                                    ))->fulluri();
                            $xml .= '<url>' . PHP_EOL .
                                '<loc>' . $url . '</loc>' . PHP_EOL .
                                '<lastmod>' . ($game->upd_date > 0 ? date('Y-m-d', $game->upd_date) : date('Y-m-d')) . '</lastmod>' . PHP_EOL .
                                '<changefreq>weekly</changefreq>' . PHP_EOL .
                                '<priority>0.6</priority>' . PHP_EOL .
                                '</url>' . PHP_EOL;
                        }

                        //play-page
                        if (!empty($game->category_seotitle))
                            $url = $host . url::router('playgame', array(
                                    'category_seo' => @$game->category_seotitle,
                                    'category_id' => $game->category_id,
                                    'game_id' => $game->id,
                                    'game_seo' => $game->seotitle
                                ))->fulluri();
                        else
                            $url = $host . url::router('playgame2', array(
                                    'game_id' => $game->id,
                                    'game_seo' => $game->seotitle
                                ))->fulluri();
                        $xml .= '<url>' . PHP_EOL .
                            '<loc>' . $url . '</loc>' . PHP_EOL .
                            '<lastmod>' . ($game->upd_date > 0 ? date('Y-m-d', $game->upd_date) : date('Y-m-d')) . '</lastmod>' . PHP_EOL .
                            '<changefreq>weekly</changefreq>' . PHP_EOL .
                            '<priority>0.6</priority>' . PHP_EOL .
                            '</url>' . PHP_EOL;
                    }
                    fwrite($fileHandle, $xml);
                }
            }
            fwrite($fileHandle, '</urlset>');
            fclose($fileHandle);
            return @memory_get_peak_usage();
        } else {
            return false;
        }
    }

    function revfeed_prepare_file(&$data)
    {
        /* make flash */
        $data['file'] = null;
        if (!empty($data['flash_file'])) {
            if (!preg_match('/[^A-Za-z0-9\s_\.\-]/', $data['flash_file'])) {
                $data['file'] = master_rev_sharing_games_file_dir . path::leftSlashes($data['flash_file']);
            } elseif (validate::_is_URL($data['flash_file'])) {
                $data['file'] = $data['flash_file'];
            }
        }
    }

    function feed_prepare_file(&$data)
    {
        /* make flash */
        $data['file'] = null;
        if (!empty($data['flash_file'])) {
            if (!preg_match('/[^A-Za-z0-9\s_\.\-]/', $data['flash_file'])) {
                $data['file'] = master_feed_games_file_dir . path::leftSlashes($data['flash_file']);
            } elseif (validate::_is_URL($data['flash_file'])) {
                $data['file'] = $data['flash_file'];
            }
        }
    }

    function feed_prepare_thumbs(&$data)
    {
        static $feed_thumb_size;
        if (!isset($feed_thumb_size))
            $feed_thumb_size = _get_theme_setting('feed_thumb_size');
        /* make thumb */
        $data['thumbnail'] = null;

        if (!empty($feed_thumb_size) && !empty($data['thumbnail_' . $feed_thumb_size])) {
            $data['thumbnail'] = $data['thumbnail_' . $feed_thumb_size];
        } else {
            if (!empty($data['thumbnail_150x150']))
                $data['thumbnail'] = $data['thumbnail_150x150'];
            elseif (!empty($data['thumbnail_100x100']))
                $data['thumbnail'] = $data['thumbnail_100x100'];
        }

        if (!preg_match('/[^A-Za-z0-9\s_\.\-]/', $data['thumbnail']))
            $data['thumbnail'] = master_feed_games_images_dir . path::leftSlashes($data['thumbnail']);
    }

    function revfeed_prepare_thumbs(&$data)
    {
        static $feed_thumb_size;
        if (!isset($feed_thumb_size))
            $feed_thumb_size = _get_theme_setting('feed_thumb_size');
        /* make thumb */
        $data['thumbnail'] = null;

        if (!empty($feed_thumb_size) && !empty($data['thumbnail_' . $feed_thumb_size])) {
            $data['thumbnail'] = $data['thumbnail_' . $feed_thumb_size];
        } else {
            if (!empty($data['thumbnail_150x150']))
                $data['thumbnail'] = $data['thumbnail_150x150'];
            elseif (!empty($data['thumbnail_100x100']))
                $data['thumbnail'] = $data['thumbnail_100x100'];
        }

        if (!preg_match('/[^A-Za-z0-9\s_\.\-]/', $data['thumbnail']))
            $data['thumbnail'] = master_rev_sharing_games_images_dir . path::leftSlashes($data['thumbnail']);
    }

    function install_manual_patches()
    {
        global $install_manually;
        $install_manually = true;
        pengu_user_load_lib('ab_update_funcs');
        $log_error = null;
        $done = 0;

        function load_to_view($msg)
        {
            $messages = array();
            if (is_string($msg))
                $messages[] = $msg;
            if (is_array($msg))
                $messages = $msg;
            include template_path() . '/vg_admin_update/simple_update.php';
            exit;
        }

        //--install _pfiles
        $manual_installed_pfiles = Setting::get_data('manual_installed_pfiles', 'val');
        $pfiles = glob(root_path() . '/_pfiles/*.php');
        if (is_array($pfiles) && !empty($pfiles)) {
            @natsort($pfiles);
            $installed = null;

            foreach ($pfiles as $file) {
                $file_ver = path::get_filename($file);
                if (strpos($manual_installed_pfiles, ',' . $file_ver) === false) {
                    @include_once($file);
                    $manual_installed_pfiles .= ',' . $file_ver;
                    Setting::save_value('update', 'manual_installed_pfiles', $manual_installed_pfiles);
                    $done++;
                }
            }
        }
        //--install _dbchanges
        $manual_installed_dbchanges = Setting::get_data('manual_installed_dbchanges', 'val');
        $sqlfiles = glob(root_path() . '/_dbchanges/*.sql');
        $error = 0;
        if (is_array($sqlfiles) && !empty($sqlfiles)) {
            @natsort($sqlfiles);
            $model = new Model();
            foreach ($sqlfiles as $file) {
                $file_ver = path::get_filename($file);
                if (strpos($manual_installed_dbchanges, ',' . $file_ver) === false) {

                    $sqldata = @file_get_contents($file);
                    $error = 0;
                    if (strpos($sqldata, '/*sep*/')) {
                        $sqlrows = explode('/*sep*/', $sqldata);
                        foreach ($sqlrows as $srows) {
                            if (!empty($srows) && !DEVELOP)
                                if ($model->query($srows)->exec() === false)
                                    $error++;
                        }
                    } else {
                        if (!empty($sqldata) && !DEVELOP)
                            if (!$model->multiquery($sqldata)->exec() === false)
                                $error++;
                    }
                    if (!$error) {
                        $manual_installed_dbchanges .= ',' . $file_ver;
                        Setting::save_value('update', 'manual_installed_dbchanges', $manual_installed_dbchanges);
                        $done++;
                    } else
                        $log_error .= "<font class='notify error'>{$file} ----- {$error} error(s) occurred!</font>";
                }
            }
        }

        if ($done) {
            $log[] = "<font class='notify success'>Your copy was updated successfully .</font>";
            psuccess('Your ArcadeBooster script was updated successfully to the new version! Your currnet version is: ' . sys_ver);
            $this->cleanAllCache();
        }
        if ($log_error) {
            $log[] = "<font class='notify error'>Some error was occurred!.</font>";
            $log[] = $log_error;
        }

        if (file_exists(root_path() . '/_dbchanges') || file_exists(root_path() . '/_pfiles'))
            $log[] = "<font class='notify info'>To access to admin area please delete  <font style='color:#FF4D00'>/_dbchanges</font> and <font style='color:#FF4D00'>/_pfiles</font> folders from your script path!</font>";

        load_to_view(@$log);
        exit;
    }

    function notfound()
    {
        if (validate::_is_ajax_request())
            exit(L::alert_request_not_found);
        $this->MapViewFile_groupFolder(null);
        $this->MapViewFileName('notfound.php');
    }

    function forbidden()
    {
        if (validate::_is_ajax_request())
            exit('You don`t have permission to access!');
        $this->MapViewFile_groupFolder(null);
        $this->MapViewFileName('forbidden.php');
    }

}
