<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Games_controller.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class GamesController extends InterfaceController
{

    protected $_model = null;
    private $loginpage;
    private $logoutpage;

    function __construct()
    {
        global $router;
        parent::__construct();
        $this->logoutpage = ab_router('homepage');
        $this->loginpage = ab_router('homepage');
    }

    function dosearch()
    {
        global $router;
        $qs = null;
        if (!@empty($_POST['search'])) {
            $surl = ab_router('search', array('text' => convert::seoText($_POST['search'])));
            ref($surl)->redirect();
        }
        ref(ab_router('homepage'))->redirect();
    }

    function page_index()
    {
        global $router_numpage;
        if (isset($_GET['abviewfile']))
            $this->MapViewFileName($_GET['abviewfile']);
        elseif (isset($_GET['_escaped_fragment_']) && file_exists(viewfolder_path() . '/allgames_snapshot.php')) {
            $this->MapViewFileName('allgames_snapshot.php');
            //--decode fragment paramas
            $params = array();
            $data = explode('&', urldecode($_GET['_escaped_fragment_']));
            $this->view->fragment = $data;
            foreach ($data as $p) {
                if (strpos($p, '=')) {
                    list($k, $v) = explode('=', $p);
                    $params[$k] = $v;
                } else
                    $params[$p] = null;
            }
            $this->view->fragment_params = $params;
        } else
            $this->MapViewFileName('index.php');
    }

    function rate($gameid, $vote)
    {
        //from ajaxgate
        $ip = agent::remote_info_ip();
        $rate = new Rate;
        if ($rate->select()->where(array('gid' => $gameid, 'ip' => $ip))->getcount() > 0) {
            $msg = "$('#gamerate_msg_text').html('<div class=\\'red bold center\\'>you have already voted</div>');";
            $msg .= "$('.btnrate').unbind('click');";
            if (isset($_GET['isjson']))
                die(json_encode(array('error' => 'you have already voted', 'text' => $msg)));
            echo $msg;
            exit;
        }
        $rate->insert(array('gid' => $gameid, 'ip' => $ip))->exec();

        switch ($vote) {
            case 'yes':
                $vote = '+100';
                break;
            case 'no':
                $vote = '+0';
                break;
            case is_numeric($vote):
                $vote = '+' . ($vote * 10);
                break;
            default:
                exit('tokhmatic addad');
        }

        $changes = array(
            'game_rating' => array('(`game_rating` *`game_votes` ' . $vote . ') / (`game_votes` +1)'),
            'game_votes' => array('`game_votes` +1 '),
        );
        $model = new Game;
        if ($model->update($changes)->where(array('gid' => $gameid))->exec()) {
            $msg = "$('#gamerate_msg_text').html('<div class=\\'green bold center\\'>thanks for the vote</div>');";
            $msg .= "$('input.btnrate').attr('disabled','disabled');";
            if (isset($_GET['isjson']))
                die(json_encode(array('success' => 'thanks for the vote', 'text' => $msg)));
            echo $msg;
        }
        exit;
    }

    function showrate($gameid)
    {
        if (!is_numeric($gameid))
            exit("progressbar.progress(0);");

        $model = new Game;
        $rate = $model->showrate($gameid);
        echo "progressbar.progress(" . (int)$rate . ");";
        exit;
    }

    function submitbroken()
    {
        $this->getPOST($_POST);

        $ip = agent::remote_info_ip();
        $country = agent::remote_info_country();
        $comment = input::post('broken_comment');
        $gameid = input::post('gameid');

        /* captcha validator */
        if (isset($_SESSION['captcha2'])) {
            if ($_POST['broken_captcha'] != $_SESSION['captcha2']) {
                echo "$('#broken .alert').html('<div class=\\'warning\\'>Captcha code is not correct !</div>');";
                exit;
            }
            /* remove captcha */
            unset($_SESSION['captcha2']);
        } else
            exit;

        $model = new Game_broken();
        if ($model->insert(array(
            'game_id' => $gameid,
            'comment' => $comment,
            'user' => @Member::data('username'),
            'ip' => $ip,
            'country' => $country,
            'date' => time()
        ))->exec()
        ) {
            echo "$('#broken').html('<div class=\\'alert\\'><div class=\\'success\\'>Your Message has been successfuly submited .</div></div>');";
        }
        exit;
    }

    function page_search($args)
    {
        global $router_numpage, $route;
        $this->MapViewFileName('search.php');

        $routeName = $route->getName();
        $router_numpage = 1;

        $category_seo = null;

        if (isset($args['category_seo']))
            $category_seo = input::safe($args['category_seo'], true, true);
        elseif (isset($args['category_id']))
            $category_seo = category::getCategorySeoById($args['category_id']);

        switch ($routeName) {
            case 'search':
                $this->view->search_text = input::safe($args['text']);
                $this->view->search_title = ucwords(strtr(input::safe($args['text'], true, true), '-_', '  '));
                break;
            case 'search_page':
                $this->view->search_text = input::safe($args['text']);
                $this->view->search_title = ucwords(strtr(input::safe($args['text'], true, true), '-_', '  '));
                $router_numpage = isset($args['page']) && intval($args['page']) ? intval($args['page']) : 1;
                break;
            case 'search_cat':
                $this->view->category_seo = $category_seo;
                $this->view->category_title = category::getCategoryTitleBySeo($category_seo);
                $this->view->search_text = input::safe($args['text']);
                $this->view->search_title = ucwords(strtr(input::safe($args['text'], true, true), '-_', '  '));
                break;
            case 'search_cat_page':
                $this->view->category_seo = $category_seo;
                $this->view->category_title = category::getCategoryTitleBySeo($category_seo);
                $this->view->search_text = input::safe($args['text']);
                $this->view->search_title = ucwords(strtr(input::safe($args['text'], true, true), '-_', '  '));
                $router_numpage = isset($args['page']) && intval($args['page']) ? intval($args['page']) : 1;
                break;
        }
    }

    function page_pre($args)
    {
        if (isset($_GET['abviewfile'])) {
            $this->MapViewFileName($_GET['abviewfile']);
        } else
            $this->MapViewFileName('pre_page.php');

        if (isset($_GET['abnocontroller']))
            return;

        if (isset($args['game_seo']))
            $ab_result = ab_select_game($args['game_seo']);
        elseif (isset($args['game_id']))
            $ab_result = ab_select_game_byid($args['game_id']);

        if ($ab_result->have_games()) {
            $current_game = $ab_result->the_game();
            if ($current_game->file_source == 0 || $current_game->file_source == 1) {
                $current_game->file_path = content_path() . '/upload/games/files/' . $current_game->file;
                $current_game->file = content_url() . '/upload/games/files/' . $current_game->file;
            }
            /* Game width/height */
            $maxGW = ab_get_setting(template_name() . '_max_game_width');
            if (!intval($maxGW))
                $maxGW = ab_get_setting('max_game_width');

            $maxGH = ab_get_setting(template_name() . '_max_game_height');
            if (!intval($maxGH))
                $maxGH = ab_get_setting('max_game_height');

            /* if (width,height)=0 get its size */
            if (!empty($current_game->file_path) && file_exists($current_game->file_path) && $current_game->width == 0 && $current_game->width == 0) {
                @list($current_game->width, $current_game->height) = getimagesize($current_game->file_path);
            }

            if (intval($current_game->width) <= 0)
                $current_game->width = $maxGW;
            if (intval($current_game->height) <= 0)
                $current_game->height = $maxGH;

            $current_game->base_width = $current_game->width;
            $current_game->base_height = $current_game->height;

            if ($maxGW > 0 && $current_game->width > $maxGW) {
                $current_game->height = ($maxGW / $current_game->width) * $current_game->height;
                $current_game->width = $maxGW;
            }
            if ($maxGH > 0 && $current_game->height > $maxGH) {
                $current_game->width = ($maxGH / $current_game->height) * $current_game->width;
                $current_game->height = $maxGH;
            }

            $current_game->is_the_pre = true;
            $current_game->opening_mode = null;
            $current_game->play_url = ab_game_url($current_game, $current_game->opening_mode);
            $this->view->current_game = $current_game;

            /* select category */
            $this->view->category_seo = null;
            $this->view->category_title = null;
            if (isset($args['category_seo']))
                $this->view->category_seo = input::safe($args['category_seo'], true, true);
            elseif (isset($args['category_id']))
                $this->view->category_seo = category::getCategorySeoById($args['category_id']);
            else
                $this->view->category_seo = $current_game->category_seotitle;
            $current_ctg_res = ab_category($this->view->category_seo);
            if (!empty($current_ctg_res)) {
                $this->view->category_seo = $current_ctg_res->seo_title;
                $this->view->category_title = $current_ctg_res->title;
                $this->view->current_category = $current_ctg_res;
            }


            if (!$this->view->exists())
                if (!empty($current_ctg_res))
                    ref(ab_router('playgame', array(
                        'category_seo' => $current_ctg_res->seo_title,
                        'category_id' => $current_ctg_res->id,
                        'game_seo' => $current_game->seotitle,
                        'game_id' => $current_game->id
                    )))->redirect();
                else
                    ref(ab_router('playgame2', array(
                        'game_seo' => $current_game->seotitle,
                        'game_id' => $current_game->id
                    )))->redirect();
        } else
            $this->page404();
    }

    function page_play($args)
    {
        global $current_game, $current_category;
        if (isset($_GET['abviewfile']))
            $this->MapViewFileName($_GET['abviewfile']);
        else
            $this->MapViewFileName('play_page.php');

        if (isset($_GET['abnocontroller']))
            return;

        _visitor_hitgame();

        if (isset($args['game_seo']))
            $ab_result = ab_select_game($args['game_seo']);
        elseif (isset($args['game_id']))
            $ab_result = ab_select_game_byid($args['game_id']);

        if ($ab_result->have_games()) {
            $current_game = $ab_result->the_game();
            //hit
            $ob = new Game;
            $ob->gamehits($current_game->seotitle);

            if ($current_game->file_source == 0 || $current_game->file_source == 1) {
                $current_game->file_path = content_path() . '/upload/games/files/' . $current_game->file;
                $current_game->file = content_url() . '/upload/games/files/' . $current_game->file;
            }
            /* Game width/height */
            $maxGW = ab_get_setting(ab_template_id() . '_max_game_width');
            if (!intval($maxGW))
                $maxGW = ab_get_setting('max_game_width');

            $maxGH = ab_get_setting(ab_template_id() . '_max_game_height');
            if (!intval($maxGH))
                $maxGH = ab_get_setting('max_game_height');

            /* if (width,height)=0 get its size */
            if (!empty($current_game->file_path) && file_exists($current_game->file_path) && $current_game->width == 0 && $current_game->width == 0) {
                @list($current_game->width, $current_game->height) = getimagesize($current_game->file_path);
            }

            if (intval($current_game->width) <= 0)
                $current_game->width = $maxGW;
            if (intval($current_game->height) <= 0)
                $current_game->height = $maxGH;

            $current_game->base_width = $current_game->width;
            $current_game->base_height = $current_game->height;

            if ($maxGW > 0 && $current_game->width > $maxGW) {
                $current_game->height = ($maxGW / $current_game->width) * $current_game->height;
                $current_game->width = $maxGW;
            }
            if ($maxGH > 0 && $current_game->height > $maxGH) {
                $current_game->width = ($maxGH / $current_game->height) * $current_game->width;
                $current_game->height = $maxGH;
            }
            $current_game->opening_mode = null;
            $current_game->play_url = ab_game_url($current_game, $current_game->opening_mode);
            $this->view->current_game = $current_game;

            /* select category */
            $this->view->category_seo = null;
            $this->view->category_title = null;
            if (isset($args['category_seo']))
                $this->view->category_seo = input::safe($args['category_seo'], true, true);
            elseif (isset($args['category_id']))
                $this->view->category_seo = category::getCategorySeoById($args['category_id']);
            else
                $this->view->category_seo = $current_game->category_seotitle;
            $current_ctg_res = ab_category($this->view->category_seo);
            if (!empty($current_ctg_res)) {
                $this->view->category_seo = $current_ctg_res->seo_title;
                $this->view->category_title = $current_ctg_res->title;
                $this->view->current_category = $current_ctg_res;
            }

            /* add in last played */
            $cookieName = 'ab_' . md5((isset($ab_result->LastPlayedCookieName) ? $ab_result->LastPlayedCookieName : 'last_played'));
            if (isset($_COOKIE[$cookieName])) {
                $lastPlayGames = @explode(',', $_COOKIE[$cookieName]);
                if (in_array($current_game->id, $lastPlayGames))
                    unset($lastPlayGames[array_search($current_game->id, $lastPlayGames)]);
                $lastPlayGames[] = $current_game->id;
                setcookie($cookieName, join(',', $lastPlayGames), time() + 200 * 24 * 3600, '/');
            } else
                setcookie($cookieName, $current_game->id, time() + 200 * 24 * 3600, '/');


            /* play in iframe */
            if (isset($_GET['iframe'])) {
                ?>
                <html>
                <head>
                    <style>body {
                            margin: 0;
                            padding: 0;
                        }</style>
                    <?php js::loadJquery(true); ?>
                    <meta name="robots" content="noindex">
                </head>
                <body>
                <center>
                    <?= ab_game_file($current_game->width, $current_game->height); ?>
                    <br>
                    <a href='<?=
                    ab_router('allgames_cat', array(
                        'category_seo' => @$current_ctg_res->seo_title,
                        'category_id' => @$current_ctg_res->cid
                    ))
                    ?>' target='_blank'><?= @$current_ctg_res->title ?></a>
                </center>
                </body>
                </html>
                <?php
                exit;
            }
        } else
            $this->page404();
    }

    function page_all($args)
    {
        global $router_numpage, $route;
        if (isset($_GET['abviewfile']))
            $this->MapViewFileName($_GET['abviewfile']);
        elseif (validate::_is_ajax_request() && file_exists(viewfolder_path() . '/ajax_allgames.php'))
            $this->MapViewFileName('ajax_allgames.php');
        elseif (isset($_GET['_escaped_fragment_']) && file_exists(viewfolder_path() . '/allgames_snapshot.php')) {
            $this->MapViewFileName('allgames_snapshot.php');
            //--decode fragment paramas
            $params = array();
            $data = explode('&', urldecode($_GET['_escaped_fragment_']));
            $this->view->fragment = $data;
            foreach ($data as $p) {
                if (strpos($p, '=')) {
                    list($k, $v) = explode('=', $p);
                    $params[$k] = $v;
                } else
                    $params[$p] = null;
            }
            $this->view->fragment_params = $params;
        } else
            $this->MapViewFileName('allgames.php');

        if (isset($_GET['abnocontroller']))
            return;

        $routeName = $route->getName();
        $router_numpage = 1;
        $category_seo = null;
        $category_title = null;
        if (isset($args['category_seo']))
            $category_seo = input::safe($args['category_seo'], true, true);
        elseif (isset($args['category_id']))
            $category_seo = category::getCategorySeoById($args['category_id']);
        $category_title = !empty($category_seo) ? category::getCategoryTitleBySeo($category_seo) : null;

        switch ($routeName) {
            case 'allgames_cat':
                $this->view->category_seo = $category_seo;
                $this->view->category_title = $category_title;
                break;
            case 'allgames_page':
                $router_numpage = isset($args['page']) && intval($args['page']) ? intval($args['page']) : 1;
                break;
            case 'allgames_cat_page':
                $this->view->category_seo = $category_seo;
                $this->view->category_title = $category_title;
                $router_numpage = isset($args['page']) && intval($args['page']) ? intval($args['page']) : 1;
                break;
            default:
                //ajax gate
                $this->view->category_seo = $category_seo;
                $this->view->category_title = $category_title;
                $router_numpage = isset($args['page']) && intval($args['page']) ? intval($args['page']) : 1;
                $this->view->item_per_page = isset($args['ipp']) && intval($args['ipp']) ? intval($args['ipp']) : null;
                $this->view->limit = isset($args['limit']) && intval($args['limit']) ? intval($args['limit']) : null;
                break;
        }
        if (!empty($category_seo)) {
            $ab_result = ab_category($category_seo);
            if (!empty($ab_result))
                $this->view->current_category = $ab_result;
            else {
                //not found
                $s = new stdClass();
                $s->title = "null";
                $s->seo_title = 'null';
                $this->view->current_category = $s;
            }
        }
        if (isset($_GET['getcount'])) {
            echo ab_all_games(null, $category_seo, null, true);
            exit;
        }
    }

    ######################################

    function page_tag($args)
    {
        global $router_numpage, $route;
        if (isset($_GET['abviewfile']))
            $this->MapViewFileName($_GET['abviewfile']);
        elseif (validate::_is_ajax_request() && file_exists(viewfolder_path() . '/ajax_allgames.php'))
            $this->MapViewFileName('ajax_allgames.php');
        elseif (isset($_GET['_escaped_fragment_']) && file_exists(viewfolder_path() . '/allgames_snapshot.php')) {
            $this->MapViewFileName('allgames_snapshot.php');
            //--decode fragment paramas
            $params = array();
            $data = explode('&', urldecode($_GET['_escaped_fragment_']));
            $this->view->fragment = $data;
            foreach ($data as $p) {
                if (strpos($p, '=')) {
                    list($k, $v) = explode('=', $p);
                    $params[$k] = $v;
                } else
                    $params[$p] = null;
            }
            $this->view->fragment_params = $params;
        } else
            $this->MapViewFileName('allgames.php');

        if (isset($_GET['abnocontroller']))
            return;

        $routeName = $route->getName();
        $router_numpage = 1;
        $tag_seo = null;
        $tag_title = null;
        if (isset($args['tag_seo']))
            $tag_seo = input::safe($args['tag_seo'], true, true);
        elseif (isset($args['tag_id']))
            $tag_seo = Game_tag::getTagSeoById($args['tag_id']);
        $tag_title = !empty($tag_seo) ? Game_tag::getTagNameBySeo($tag_seo) : null;

        switch ($routeName) {
            case 'tag':
                $this->view->category_seo = $tag_seo;
                $this->view->category_title = $tag_title;
                break;
            case 'tag_page':
                $this->view->category_seo = $tag_seo;
                $this->view->category_title = $tag_title;
                $router_numpage = isset($args['page']) && intval($args['page']) ? intval($args['page']) : 1;
                break;
            default:
                //ajax gate 
                $this->view->category_seo = $tag_seo;
                $this->view->category_title = $tag_title;
                $router_numpage = isset($args['page']) && intval($args['page']) ? intval($args['page']) : 1;
                $this->view->item_per_page = isset($args['ipp']) && intval($args['ipp']) ? intval($args['ipp']) : null;
                $this->view->limit = isset($args['limit']) && intval($args['limit']) ? intval($args['limit']) : null;
                break;
        }
        if (isset($tag_seo)) {
            $ab_result = ab_tag($tag_seo);
            if (!empty($ab_result)) {
                $this->view->current_category = $ab_result;
                $this->view->current_category->title = $this->view->current_category->name;
                $this->view->current_category->seo_title = $this->view->current_category->seo_name;
            } else {
                //not found
                $s = new stdClass();
                $s->name = "null";
                $s->seo_name = 'null';
                $s->title = "null";
                $s->seo_title = 'null';
                $this->view->current_category = $s;
            }
        }
    }

    ######################################

    function page_new_games($args)
    {
        global $router_numpage, $route;
        if (isset($_GET['abviewfile']))
            $this->MapViewFileName($_GET['abviewfile']);
        elseif (validate::_is_ajax_request() && file_exists(viewfolder_path() . '/ajax_newgames.php'))
            $this->MapViewFileName('ajax_newgames.php');
        elseif (isset($_GET['_escaped_fragment_']) && file_exists(viewfolder_path() . '/newgames_snapshot.php')) {
            $this->MapViewFileName('newgames_snapshot.php');
            //--decode fragment paramas
            $params = array();
            $data = explode('&', urldecode($_GET['_escaped_fragment_']));
            $this->view->fragment = $data;
            foreach ($data as $p) {
                if (strpos($p, '=')) {
                    list($k, $v) = explode('=', $p);
                    $params[$k] = $v;
                } else
                    $params[$p] = null;
            }
            $this->view->fragment_params = $params;
        } else
            $this->MapViewFileName('newgames.php');

        if (isset($_GET['abnocontroller']))
            return;

        $routeName = $route->getName();
        $router_numpage = 1;
        $category_seo = null;
        $category_title = null;
        if (isset($args['category_seo']))
            $category_seo = input::safe($args['category_seo'], true, true);
        elseif (isset($args['category_id']))
            $category_seo = category::getCategorySeoById($args['category_id']);
        $category_title = !empty($category_seo) ? Category::getCategoryTitleBySeo($category_seo) : null;

        switch ($routeName) {
            case 'newgames_cat':
                $this->view->category_seo = $category_seo;
                $this->view->category_title = $category_title;
                break;
            case 'newgames_page':
                $router_numpage = isset($args['page']) && intval($args['page']) ? intval($args['page']) : 1;
                break;
            case 'newgames_cat_page':
                $this->view->category_seo = $category_seo;
                $this->view->category_title = $category_title;
                $router_numpage = isset($args['page']) && intval($args['page']) ? intval($args['page']) : 1;
                break;
            default:
                //ajax gate
                $this->view->category_seo = $category_seo;
                $this->view->category_title = $category_title;
                $router_numpage = isset($args['page']) && intval($args['page']) ? intval($args['page']) : 1;
                $this->view->item_per_page = isset($args['ipp']) && intval($args['ipp']) ? intval($args['ipp']) : null;
                $this->view->limit = isset($args['limit']) && intval($args['limit']) ? intval($args['limit']) : null;
                break;
        }
        if (!empty($category_seo)) {
            $ab_result = ab_category($category_seo);
            if (!empty($ab_result))
                $this->view->current_category = $ab_result;
            else {
                //not found
                $s = new stdClass();
                $s->title = "null";
                $s->seo_title = 'null';
                $this->view->current_category = $s;
            }
        }
        if (isset($_GET['getcount'])) {
            echo ab_new_games(null, $category_seo, null, true);
            exit;
        }
    }

    function page_popular_games($args)
    {
        global $router_numpage, $route;
        if (isset($_GET['abviewfile']))
            $this->MapViewFileName($_GET['abviewfile']);
        elseif (validate::_is_ajax_request() && file_exists(viewfolder_path() . '/ajax_populargames.php'))
            $this->MapViewFileName('ajax_populargames.php');
        elseif (isset($_GET['_escaped_fragment_']) && file_exists(viewfolder_path() . '/populargames_snapshot.php')) {
            $this->MapViewFileName('populargames_snapshot.php');
            //--decode fragment paramas
            $params = array();
            $data = explode('&', urldecode($_GET['_escaped_fragment_']));
            $this->view->fragment = $data;
            foreach ($data as $p) {
                if (strpos($p, '=')) {
                    list($k, $v) = explode('=', $p);
                    $params[$k] = $v;
                } else
                    $params[$p] = null;
            }
            $this->view->fragment_params = $params;
        } else
            $this->MapViewFileName('populargames.php');

        if (isset($_GET['abnocontroller']))
            return;

        $routeName = $route->getName();
        $router_numpage = 1;
        $category_seo = null;
        $category_title = null;
        if (isset($args['category_seo']))
            $category_seo = input::safe($args['category_seo'], true, true);
        elseif (isset($args['category_id']))
            $category_seo = category::getCategorySeoById($args['category_id']);
        $category_title = !empty($category_seo) ? Category::getCategoryTitleBySeo($category_seo) : null;

        switch ($routeName) {
            case 'populargames_cat':
                $this->view->category_seo = $category_seo;
                $this->view->category_title = $category_title;
                break;
            case 'populargames_page':
                $router_numpage = isset($args['page']) && intval($args['page']) ? intval($args['page']) : 1;
                break;
            case 'populargames_cat_page':
                $this->view->category_seo = $category_seo;
                $this->view->category_title = $category_title;
                $router_numpage = isset($args['page']) && intval($args['page']) ? intval($args['page']) : 1;
                break;
            default:
                //ajax gate
                $this->view->category_seo = $category_seo;
                $this->view->category_title = $category_title;
                $router_numpage = isset($args['page']) && intval($args['page']) ? intval($args['page']) : 1;
                $this->view->item_per_page = isset($args['ipp']) && intval($args['ipp']) ? intval($args['ipp']) : null;
                $this->view->limit = isset($args['limit']) && intval($args['limit']) ? intval($args['limit']) : null;
                break;
        }
        if (!empty($category_seo)) {
            $ab_result = ab_category($category_seo);
            if (!empty($ab_result))
                $this->view->current_category = $ab_result;
            else {
                //not found
                $s = new stdClass();
                $s->title = "null";
                $s->seo_title = 'null';
                $this->view->current_category = $s;
            }
        }
        if (isset($_GET['getcount'])) {
            echo ab_popular_games(null, $category_seo, null, true);
            exit;
        }
    }

    function page_popular_games_today($args)
    {
        global $router_numpage, $route;
        if (isset($_GET['abviewfile']))
            $this->MapViewFileName($_GET['abviewfile']);
        elseif (validate::_is_ajax_request() && file_exists(viewfolder_path() . '/ajax_populargamestoday.php'))
            $this->MapViewFileName('ajax_populargamestoday.php');
        elseif (isset($_GET['_escaped_fragment_']) && file_exists(viewfolder_path() . '/populargamestoday_snapshot.php')) {
            $this->MapViewFileName('populargamestoday_snapshot.php');
            //--decode fragment paramas
            $params = array();
            $data = explode('&', urldecode($_GET['_escaped_fragment_']));
            $this->view->fragment = $data;
            foreach ($data as $p) {
                if (strpos($p, '=')) {
                    list($k, $v) = explode('=', $p);
                    $params[$k] = $v;
                } else
                    $params[$p] = null;
            }
            $this->view->fragment_params = $params;
        } else
            $this->MapViewFileName('populargamestoday.php');

        if (isset($_GET['abnocontroller']))
            return;

        $routeName = $route->getName();
        $router_numpage = 1;
        $category_seo = null;
        $category_title = null;
        if (isset($args['category_seo']))
            $category_seo = input::safe($args['category_seo'], true, true);
        elseif (isset($args['category_id']))
            $category_seo = category::getCategorySeoById($args['category_id']);
        $category_title = !empty($category_seo) ? Category::getCategoryTitleBySeo($category_seo) : null;

        switch ($routeName) {
            case 'populargamestoday_cat':
                $this->view->category_seo = $category_seo;
                $this->view->category_title = $category_title;
                break;
            case 'populargamestoday_page':
                $router_numpage = isset($args['page']) && intval($args['page']) ? intval($args['page']) : 1;
                break;
            case 'populargamestoday_cat_page':
                $this->view->category_seo = $category_seo;
                $this->view->category_title = $category_title;
                $router_numpage = isset($args['page']) && intval($args['page']) ? intval($args['page']) : 1;
                break;
            default:
                //ajax gate
                $this->view->category_seo = $category_seo;
                $this->view->category_title = $category_title;
                $router_numpage = isset($args['page']) && intval($args['page']) ? intval($args['page']) : 1;
                $this->view->item_per_page = isset($args['ipp']) && intval($args['ipp']) ? intval($args['ipp']) : null;
                $this->view->limit = isset($args['limit']) && intval($args['limit']) ? intval($args['limit']) : null;
                break;
        }
        if (!empty($category_seo)) {
            $ab_result = ab_category($category_seo);
            if (!empty($ab_result))
                $this->view->current_category = $ab_result;
            else {
                //not found
                $s = new stdClass();
                $s->title = "null";
                $s->seo_title = 'null';
                $this->view->current_category = $s;
            }
        }
        if (isset($_GET['getcount'])) {
            echo ab_popular_games_today(null, $category_seo, null, true);
            exit;
        }
    }

    function page_top_rate_games($args)
    {
        global $router_numpage, $route;
        if (isset($_GET['abviewfile']))
            $this->MapViewFileName($_GET['abviewfile']);
        elseif (validate::_is_ajax_request() && file_exists(viewfolder_path() . '/ajax_toprategames.php'))
            $this->MapViewFileName('ajax_toprategames.php');
        elseif (isset($_GET['_escaped_fragment_']) && file_exists(viewfolder_path() . '/toprategames_snapshot.php')) {
            $this->MapViewFileName('toprategames_snapshot.php');
            //--decode fragment paramas
            $params = array();
            $data = explode('&', urldecode($_GET['_escaped_fragment_']));
            $this->view->fragment = $data;
            foreach ($data as $p) {
                if (strpos($p, '=')) {
                    list($k, $v) = explode('=', $p);
                    $params[$k] = $v;
                } else
                    $params[$p] = null;
            }
            $this->view->fragment_params = $params;
        } else
            $this->MapViewFileName('toprategames.php');

        if (isset($_GET['abnocontroller']))
            return;

        $routeName = $route->getName();
        $router_numpage = 1;
        $category_seo = null;
        $category_title = null;
        if (isset($args['category_seo']))
            $category_seo = input::safe($args['category_seo'], true, true);
        elseif (isset($args['category_id']))
            $category_seo = category::getCategorySeoById($args['category_id']);
        $category_title = !empty($category_seo) ? Category::getCategoryTitleBySeo($category_seo) : null;

        switch ($routeName) {
            case 'toprategames_cat':
                $this->view->category_seo = $category_seo;
                $this->view->category_title = $category_title;
                break;
            case 'toprategames_page':
                $router_numpage = isset($args['page']) && intval($args['page']) ? intval($args['page']) : 1;
                break;
            case 'toprategames_cat_page':
                $this->view->category_seo = $category_seo;
                $this->view->category_title = $category_title;
                $router_numpage = isset($args['page']) && intval($args['page']) ? intval($args['page']) : 1;
                break;
            default:
                //ajax gate
                $this->view->category_seo = $category_seo;
                $this->view->category_title = $category_title;
                $router_numpage = isset($args['page']) && intval($args['page']) ? intval($args['page']) : 1;
                $this->view->item_per_page = isset($args['ipp']) && intval($args['ipp']) ? intval($args['ipp']) : null;
                $this->view->limit = isset($args['limit']) && intval($args['limit']) ? intval($args['limit']) : null;
                break;
        }
        if (!empty($category_seo)) {
            $ab_result = ab_category($category_seo);
            if (!empty($ab_result))
                $this->view->current_category = $ab_result;
            else {
                //not found
                $s = new stdClass();
                $s->title = "null";
                $s->seo_title = 'null';
                $this->view->current_category = $s;
            }
        }
        if (isset($_GET['getcount'])) {
            echo ab_top_rated_games(null, $category_seo, null, true);
            exit;
        }
    }

    function page_featured_games($args)
    {
        global $router_numpage, $route;
        if (isset($_GET['abviewfile']))
            $this->MapViewFileName($_GET['abviewfile']);
        elseif (validate::_is_ajax_request() && file_exists(viewfolder_path() . '/ajax_featuredgames.php'))
            $this->MapViewFileName('ajax_featuredgames.php');
        elseif (isset($_GET['_escaped_fragment_']) && file_exists(viewfolder_path() . '/featuredgames_snapshot.php')) {
            $this->MapViewFileName('featuredgames_snapshot.php');
            //--decode fragment paramas
            $params = array();
            $data = explode('&', urldecode($_GET['_escaped_fragment_']));
            $this->view->fragment = $data;
            foreach ($data as $p) {
                if (strpos($p, '=')) {
                    list($k, $v) = explode('=', $p);
                    $params[$k] = $v;
                } else
                    $params[$p] = null;
            }
            $this->view->fragment_params = $params;
        } else
            $this->MapViewFileName('featuredgames.php');

        if (isset($_GET['abnocontroller']))
            return;

        $routeName = $route->getName();
        $router_numpage = 1;
        switch ($routeName) {
            case 'featuredgames_page':
                $router_numpage = isset($args['page']) && intval($args['page']) ? intval($args['page']) : 1;
                break;
        }

        if (isset($_GET['getcount'])) {
            echo ab_featured_games(null, null, null, true);
            exit;
        }
    }

    function page_last_played_games($args)
    {
        global $router_numpage, $route;
        if (isset($_GET['abviewfile']))
            $this->MapViewFileName($_GET['abviewfile']);
        elseif (validate::_is_ajax_request() && file_exists(viewfolder_path() . '/ajax_lastplayedgames.php'))
            $this->MapViewFileName('ajax_lastplayedgames.php');
        elseif (isset($_GET['_escaped_fragment_']) && file_exists(viewfolder_path() . '/lastplayedgames_snapshot.php')) {
            $this->MapViewFileName('lastplayedgames_snapshot.php');
            //--decode fragment paramas
            $params = array();
            $data = explode('&', urldecode($_GET['_escaped_fragment_']));
            $this->view->fragment = $data;
            foreach ($data as $p) {
                if (strpos($p, '=')) {
                    list($k, $v) = explode('=', $p);
                    $params[$k] = $v;
                } else
                    $params[$p] = null;
            }
            $this->view->fragment_params = $params;
        } else
            $this->MapViewFileName('lastplayedgames.php');

        if (isset($_GET['abnocontroller']))
            return;

        $routeName = $route->getName();
        $router_numpage = 1;
        switch ($routeName) {
            case 'lastplayedgames_page':
                $router_numpage = isset($args['page']) && intval($args['page']) ? intval($args['page']) : 1;
                break;
            default:
                //ajax gate
                $router_numpage = isset($args['page']) && intval($args['page']) ? intval($args['page']) : 1;
                $this->view->item_per_page = isset($args['ipp']) && intval($args['ipp']) ? intval($args['ipp']) : null;
                $this->view->limit = isset($args['limit']) && intval($args['limit']) ? intval($args['limit']) : null;
                break;
        }

        if (isset($_GET['getcount'])) {
            echo ab_last_played_games(null, null, null, true);
            exit;
        }
    }

    public function page_favorites($args)
    {
        $routeName = route_name();
        if ($routeName == 'favorites')
            return $this->page_favorites_cookie($args);
        elseif (isset($_GET['abviewfile']))
            $this->MapViewFileName($_GET['abviewfile']);
        elseif (validate::_is_ajax_request() && file_exists(viewfolder_path() . '/ajax_users_favorites.php'))
            $this->MapViewFileName('ajax_users_favorites.php');
        else
            $this->MapViewFileName('users_favorites.php');

        if (isset($_GET['abnocontroller']))
            return;

        $user = new Member;
        $user->setLogoutPage(ab_router('userlogin'));
        $user->CheckLogin();


        global $router_numpage;
        $router_numpage = 1;
        $category_seo = null;
        $category_title = null;
        if (isset($args['category_seo']))
            $category_seo = input::safe($args['category_seo'], true, true);
        elseif (isset($args['category_id']))
            $category_seo = category::getCategorySeoById($args['category_id']);
        $category_title = !empty($category_seo) ? Category::getCategoryTitleBySeo($category_seo) : null;

        switch ($routeName) {
            case 'userfavorites_page':
                $router_numpage = isset($args['page']) && intval($args['page']) ? intval($args['page']) : 1;
                break;
            default:
                //ajax gate
                $this->view->category_seo = $category_seo;
                $this->view->category_title = $category_title;
                $router_numpage = isset($args['page']) && intval($args['page']) ? intval($args['page']) : 1;
                $this->view->item_per_page = isset($args['ipp']) && intval($args['ipp']) ? intval($args['ipp']) : null;
                $this->view->limit = isset($args['limit']) && intval($args['limit']) ? intval($args['limit']) : null;
                break;
        }

        if (isset($_GET['getcount'])) {
            echo ab_user_favorite_games(null, null, null, true);
            exit;
        }
    }

    function page_favorites_cookie()
    {
        if (isset($_GET['abviewfile']))
            $this->MapViewFileName($_GET['abviewfile']);
        else
            $this->MapViewFileName('favorites.php');

        if (isset($_GET['abnocontroller']))
            return;

        global $router_numpage;
        $router_numpage = 1;
        $category_seo = null;
        $category_title = null;
        if (isset($args['category_seo']))
            $category_seo = input::safe($args['category_seo'], true, true);
        elseif (isset($args['category_id']))
            $category_seo = category::getCategorySeoById($args['category_id']);
        $category_title = !empty($category_seo) ? Category::getCategoryTitleBySeo($category_seo) : null;
        $this->view->category_seo = $category_seo;
        $this->view->category_title = $category_title;
        $this->view->limit = isset($args['limit']) && intval($args['limit']) ? intval($args['limit']) : null;
    }

    function totalgame($args)
    {
        $category_seo = null;
        if (isset($args['category_seo']))
            $category_seo = input::safe($args['category_seo'], true, true);
        elseif (isset($args['category_id']))
            $category_seo = category::getCategorySeoById($args['category_id']);

        $model = new Game();
        if (!empty($category_seo)) {
            return $model->select()
                ->innerjoin('abs_categories', 'C')
                ->on('C.cid=abs_games.game_categories')
                ->where(array('ifnull(abs_games.game_is_active,0)=1', 'C.seo_title' => $category_seo))
                ->getcount();
        } else {
            return $model->select()->getcount();
        }
    }

    function ajaxgate()
    {
        $action = input::get('action');

        function page_requested()
        {
            return (int)@$_GET['page'] ? (int)@$_GET['page'] : 1;
        }

        function limit_requested()
        {
            return (int)@$_GET['limit'] ? (int)@$_GET['limit'] : null;
        }

        function item_per_page_requested()
        {
            return (int)@$_GET['ipp'] ? (int)@$_GET['ipp'] : null;
        }

        ############ 
        if ($action == 'getthumbs') {
            direction::$currentAction = 'page_all';
            $cat = !empty($_GET['cat']) ? input::get('cat') : null;
            $this->page_all(array(
                'category_seo' => $cat,
                'page' => page_requested(),
                'ipp' => item_per_page_requested()
            ));
            return;
        }
        if ($action == 'getthumbs_tag') {
            direction::$currentAction = 'page_tag';
            $cat = !empty($_GET['cat']) ? input::get('cat') : null;
            $this->page_tag(array(
                'tag_seo' => $cat,
                'page' => page_requested(),
                'ipp' => item_per_page_requested()
            ));
            return;
        }

        if ($action == 'getpopularthumbs') {
            direction::$currentAction = 'page_popular_games';
            $this->page_popular_games(array(
                'category_seo' => null,
                'page' => page_requested(),
                'ipp' => item_per_page_requested()
            ));
            return;
        }

        if ($action == 'getpopulartodaythumbs') {
            direction::$currentAction = 'page_popular_games_today';
            $this->page_popular_games_today(array(
                'category_seo' => null,
                'page' => page_requested(),
                'ipp' => item_per_page_requested()
            ));
            return;
        }

        if ($action == 'gettopratethumbs') {
            direction::$currentAction = 'page_top_rate_games';
            $this->page_top_rate_games(array(
                'category_seo' => null,
                'page' => page_requested(),
                'ipp' => item_per_page_requested(),
                'limit' => limit_requested()
            ));
            return;
        }

        if ($action == 'getlastplayed') {
            direction::$currentAction = 'page_last_played_games';
            $this->page_last_played_games(array(
                'page' => page_requested(),
                'ipp' => item_per_page_requested(),
                'limit' => limit_requested()
            ));
            return;
        }

        if ($action == 'getfav') {
            direction::$currentAction = 'page_favorites';
            $this->page_favorites(array(
                'category_seo' => null,
                'page' => page_requested(),
                'ipp' => item_per_page_requested(),
                'limit' => limit_requested()
            ));
            return;
        }
        if ($action == 'cookie_addtofav') {
            direction::$currentAction = 'page_favorites';
            $fid = isset($_GET['idfield']) ? $_GET['idfield'] : 'gameid';
            $gameID = isset($_POST[$fid]) ? $_POST[$fid] : @$_GET[$fid];
            $res = ab_cookie_addto_fav($gameID);
            echo $res;
            exit;
        }

        if ($action == 'getrelated') {
            direction::$currentAction = 'ajax_relatedgames';
            $this->ajax_relatedgames(input::get('category_seo'), limit_requested());
            return;
        }

        if ($action == 'search') {
            $keyword = @$_GET['keyword'];
            direction::$currentAction = 'livesearch';
            $this->livesearch(input::get('keyword'), limit_requested());
            return;
        }

        if ($action == 'rate' || $action == 'rating') {
            direction::$currentAction = 'rate';
            $fid = isset($_GET['idfield']) ? $_GET['idfield'] : 'gameid';
            $fvote = isset($_GET['votefield']) ? $_GET['votefield'] : 'vote';
            $gameID = isset($_POST[$fid]) ? $_POST[$fid] : @$_GET[$fid];
            $vote = isset($_POST[$fvote]) ? $_POST[$fvote] : @$_GET[$fvote];
            $this->rate($gameID, $vote);
            return;
        }

        if ($action == 'showrate') {
            direction::$currentAction = 'showrate';
            $fid = isset($_GET['idfield']) ? $_GET['idfield'] : 'gameid';
            $gameID = isset($_POST[$fid]) ? $_POST[$fid] : @$_GET[$fid];
            $this->showrate($gameID);
            return;
        }

        if ($action == 'submitbroken') {
            direction::$currentAction = 'submitbroken';
            $this->submitbroken();
            return;
        }
    }

    function livesearch()
    {
        $args = func_get_args();
        $this->MapViewFileName('ajax_livesearch.php');
        if (!isset($args[0]) || strlen($args[0]) < 1)
            exit;
        $this->view->keyword = $args[0];
        $this->view->limit = isset($args[1]) ? intval($args[1]) : 20;
    }

    function ajax_relatedgames()
    {
        $args = func_get_args();
        $this->MapViewFileName('ajax_relatedgames.php');

        if (isset($args[0]))
            $this->view->category_seo = $args[0];
        if (isset($args[1]))
            $this->view->limit = intval($args[1]);
    }

    function sitemap()
    {
        $filename = setting::get_data('sitemap_file_name', 'val');
        if (file_exists(root_path() . "/{$filename}")) {
            header('Content-Type: application/xml; charset=utf-8');
            readfile(root_path() . "/{$filename}");
        } else
            die(L::alert_sitemap_not_exists);
    }

    function shootout($args)
    {
        $gid = $args['gid'];
        _visitor_outbound($gid);
        exit;
    }

}
