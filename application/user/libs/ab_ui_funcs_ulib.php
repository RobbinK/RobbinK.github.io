<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: ab_ui_funcs_ulib.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */

pengu_user_load_lib('ab_defined_seo');

function ab_router($routerName, $params = array(), $qs = null)
{
    global $abQS;
    $aqs = null;
    foreach ($abQS as $v) {
        if (isset($_GET[$v]))
            $aqs .= '&' . $v . '=' . $_GET[$v];
    }

    if (!empty($aqs)) {
        $qs = '?' . ltrim($qs, '?') . $aqs;
    } elseif (in_array($routerName, array('ajaxgate', 'userlogin')))
        $qs = '?' . ltrim($qs, '?');
    else
        $qs = $qs ? '?' . ltrim($qs, '?') : null;
    global $router;
    return HOST_URL . $router->generate($routerName, $params) . $qs;
}

function ab_fragment_meta($content = '!')
{
    if (!isset($_GET['_escaped_fragment_']) && in_array(action(), array('page_index', 'page_new_games', 'page_popular_games_today', 'page_popular_games', 'page_top_rate_games', 'page_all', 'page_tag'))) {
        echo "<meta name='fragment' content='{$content}'>\n";
    }
}


function ab_canonical()
{
    $canonical = setting::get_data('canonical_link', 'val');
    if ($canonical && $canonical !== '0')
        return "<link rel=\"canonical\" href=\"" . ab_canonical_link($canonical) . "\" />\n";
}

function ab_canonical_link($canonical = null)
{
    global $current_category, $current_game;
    $canlink = urldecode( url::itself()->fulluri());
    $canlink=str_replace('&a​mp;','&',$canlink);

    $show_pre = convert::to_bool(setting::get_data('show_prepage', 'val'));
    if ($canonical === null)
        $canonical = setting::get_data('canonical_link', 'val');
    if (!$show_pre && $canonical == 'pre')
        $canonical = 'play';
    //play
    if ((route_name() == 'playgame' || route_name() == 'playgame2') && $canonical == 'pre') {
        if (isset($current_category->seo_title) && isset($current_game->seotitle))
            $canlink = ab_router('pregame', array('category_seo' => $current_category->seo_title, 'game_seo' => $current_game->seotitle));
        elseif (isset($current_game->seotitle))
            $canlink = ab_router('pregame2', array('game_seo' => $current_game->seotitle));
    }
    //pre
    if ((route_name() == 'pregame' || route_name() == 'pregame2') && $canonical == 'play') {
        if (isset($current_category->seo_title) && isset($current_game->seotitle))
            $canlink = ab_router('playgame', array('category_seo' => $current_category->seo_title, 'game_seo' => $current_game->seotitle));
        elseif (isset($current_game->seotitle))
            $canlink = ab_router('playgame2', array('game_seo' => $current_game->seotitle));
    }

    if (strpos($canlink, '_escaped_fragment_') !== false) {
        $canlink= preg_replace('/(?:\?|\&)_escaped_[^_]*_\=/i','#!',$canlink,1);
        $canlink= preg_replace('/(?:\?|\&)_escaped_.*/i','',$canlink);
        $canlink = preg_replace('/\#\!$/', '', $canlink);
    }

    return lib::wwwurl($canlink);
}

function ab_get_setting($key)
{
    return _get_theme_setting($key);
}

function ab_lang_code($language)
{
    return agent::lang_to_code($language);
}

function ab_page_title()
{
    eval(globals_st($GLOBALS));
    /* page_meta */
    if (isset($page_title))
        return $page_title;

    return __defined_seo_call('title');
}

function ab_meta_description()
{
    eval(globals_st($GLOBALS));
    /* page_meta */
    if (isset($meta_description))
        return $meta_description;

    return __defined_seo_call('description');
}

function ab_meta_keywords()
{
    eval(globals_st($GLOBALS));
    /* page_meta */
    if (isset($meta_keywords))
        return $meta_keywords;

    return __defined_seo_call('keywords');
}

function ab_page_heading()
{
    eval(globals_st($GLOBALS));
    /* page_meta */
    if (isset($page_title))
        return $page_title;

    return __defined_seo_call('heading');
}

function ab_category_heading_newgames()
{
    return __defined_seo_call(null, 'seo_category_new_games_heading');
}

function ab_category_heading_populargames()
{
    return __defined_seo_call(null, 'seo_category_popular_games_heading');
}

function ab_category_heading_topratedgames()
{
    return __defined_seo_call(null, 'seo_category_top_rated_games_heading');
}

function ab_category_heading_moregames()
{
    return __defined_seo_call(null, 'seo_category_more_games');
}

function ab_site_name()
{
    return setting::get_data('site_name', 'val');
}

function ab_maintenance_message()
{
    return setting::get_data('close_site_messages', 'val');
}

function ab_show_ad($zone_name)
{

    $s = new pengu_cache(cache_path() . '/etc/ads', 'zone_');
    $s->bezipped(false);
    $s->setCacheKey($zone_name);

    if (!$s->isCached()) {
        $data = zone::getZone($zone_name);
        $s->write($data);
    } else
        $data = $s->read();

    /* scripts */
    pengu_user_load_lib('ab_ads_funcs');
    return _ab_show_adcode($data['id'], $data['show_ad'], 'script'); // all Ad code mode changed to script
}

function ab_game_is_featured($game = null)
{
    global $current_game;
    if (!$game)
        $game = $current_game;
    if (!empty($game->featured_img) && @$game->featured)
        return true;
    return false;
}

function ab_isactive_ctg($cat)
{
    if (isset($cat['seo_title']))
        $c = $cat['seo_title'];
    else
        $c = $cat;
    global $category_seo;
    if (isset($category_seo) && $category_seo == $c)
        return true;
    return false;
}

function ab_game_tags($game, $seprator = ',')
{
    $tag = mobileApp() ? new MobileTag() : new Game_tag();
    $data = array();
    if (is_string($game) && strpos($game, ',') !== false)
        $data = $tag->getTagsByIds(explode(',', $game));
    elseif (is_array($game) && !empty($game))
        $data = $tag->getTagsByIds($game);
    elseif (isset($game->tags) && strpos($game->tags, ',') !== false)
        $data = $tag->getTagsByIds(explode(',', $game->tags));
    if (!empty($seprator)) {
        $out = array();
        foreach ($data as $d)
            $out[] = $d['name'];
        return join($seprator, $out);
    }
    return $data;
}

///// URLS
function ab_page_url($page)
{
    if (is_array($page) && isset($page['pid']))
        return ab_router('page', array(
            'page_seo' => $page['seo_title'],
            'page_id' => $page['pid']
        ));
    elseif (is_object($page) && isset($page->pid))
        return ab_router('page', array(
            'page_seo' => $page->seo_title,
            'page_id' => $page->pid
        ));
}

function ab_category_url($cat)
{
    if (is_array($cat) && isset($cat['cid']))
        return ab_router('allgames_cat', array(
            'category_seo' => $cat['seo_title'],
            'category_id' => $cat['cid']
        ));
    elseif (is_object($cat) && isset($cat->cid))
        return ab_router('allgames_cat', array(
            'category_seo' => $cat->seo_title,
            'category_id' => $cat->cid
        ));
}

function ab_tag_url($tag)
{
    if (isset($tag['id']))
        return ab_router('tag', array(
            'tag_seo' => $tag['seo_name'],
            'tag_id' => $tag['id']
        ));
    elseif (is_object($tag) && isset($tag->id))
        return ab_router('tag', array(
            'tag_seo' => $tag->seo_name,
            'tag_id' => $tag->id
        ));
}

function ab_game_url($game, &$target = null)
{
    static $show_pre;
    global $current_game;

    if (!isset($game->seotitle))
        return;

    $targe = '_self';
    if (!isset($show_pre))
        $show_pre = convert::to_bool(setting::get_data('show_prepage', 'val'));

    if (!$show_pre || (action() == 'page_play' && $game->seotitle == @$current_game->seotitle))
        if (!empty($game->category_seotitle))
            $r1 = 'playgame';
        else
            $r2 = 'playgame2';
    else {
        if (action() == 'page_pre' && isset($game->is_the_pre)) {
            //pre page 
            if (convert::to_bool(setting::get_data('active_trading', 'val')) && !_visitor_valid()) {
                $target = '_blank';
                return ab_router('visitorshootout', array(
                    'gid' => $game->id,
                    'game_seo' => $game->seotitle
                ));
            }
            if (!empty($game->category_seotitle))
                $r1 = 'playgame';
            else
                $r2 = 'playgame2';
        } else {
            if (!empty($game->category_seotitle))
                $r1 = 'pregame';
            else
                $r2 = 'pregame2';
        }
    }
    if (isset($r1)) {
        return ab_router($r1, array(
            'category_seo' => @$game->category_seotitle,
            'category_id' => $game->category_id,
            'game_id' => $game->id,
            'game_seo' => $game->seotitle
        ));
    } elseif (isset($r2)) {
        return ab_router($r2, array(
            'game_id' => $game->id,
            'game_seo' => $game->seotitle
        ));
    }
}

function ab_game_file($width = '100%', $height = '100%')
{
    global $current_game;
    $wUnit = 'px';
    $hUnit = 'px';
    if (preg_match('/(\d*)(%|px)?/', $width, $matches)) {
        $width = $matches[1];
        if (!empty($matches[2]))
            $wUnit = $matches[2];
    }
    if (preg_match('/(\d*)(%|px)?/', $height, $matches)) {
        $height = $matches[1];
        if (!empty($matches[2]))
            $hUnit = $matches[2];
    }
    $width .= $wUnit;
    $height .= $hUnit;

    $ext = path::get_extension($current_game->file);
    if (!isset($current_game->file))
        return;

    if ($current_game->file_source == 4) :
        //Embeded Code (JS,...) 
        if (function_exists('embeded_playcode')) {
            embeded_playcode($current_game->file, $width, $height);
            return;
        }
        ?>
        <div id="GameFileWrapper" style="width:<?= $width ?>;height:<?= $height ?>;overflow: hidden;margin:0 auto;">
            <?= $current_game->file ?>
        </div>
    <?php
    elseif ($current_game->file_source == 2) :
        //Remote iFrame Link (HTML5, swf, unity3d, dcr,…) 
        if (function_exists('remote_iframe_playcode')) {
            remote_iframe_playcode($current_game->file, $width, $height);
            return;
        }
        ?>
        <div id="GameFileWrapper" style="width:<?= $width ?>;height:<?= $height ?>;overflow: hidden;margin:0 auto;">
            <iframe style="width:100%;height:100%;margin:0 auto;" src="<?= $current_game->file ?>" frameborder="0"
                    scrolling="no"></iframe>
        </div>
    <?php
    //Grab Remote File
    //uploaded file
    //Remote Game File Link (swf, unity3d, dcr)
    elseif ($ext == 'unity3d'):
        if (!empty($current_game->parameters)) {
            $params = array();
            foreach (explode('&', $current_game->parameters) as $p) {
                @list($k, $v) = explode('=', $p);
                $params[$k] = !empty($v) ? $v : '';
            }
            $fileurl = url::link($current_game->file)->fulluri($params);
        } else
            $fileurl = $current_game->file;

        if (function_exists('unity3d_file_playcode')) {
            unity3d_file_playcode($fileurl, $width, $height);
            return;
        }

        event::register_onLoadView('_load_unity_files', 8);

        function _load_unity_files(&$ViewContent)
        {
            global $current_game;
            $jsContent = null;
            ob_start();
            js::loadJquery(true);
            js::loadJquery_migrate(true);
            js::load(static_path() . '/js/jquery.unity3d.js', array(JS_FORCELOAD => true));
            ?>
            <script type="text/javascript">
                $(function () {
                    $("#unityPlayer").unity3d({
                        file: "<?= $current_game->file ?>",
                        width: '100%',
                        height: '100%'
                    });
                });
            </script>
            <?php
            $jsContent = ob_get_clean();
            if (preg_match("/<\/body>/i", $ViewContent)) {
                $ViewContent = preg_replace("/<\/body>/i", "{$jsContent}</body>", $ViewContent);
            }
        }

        ?>
        <div id="GameFileWrapper" style="width:<?= $width ?>;height:<?= $height ?>;margin:0 auto;">
            <div id="unityPlayer">
                <div class="missing">
                    <a href="http://unity3d.com/webplayer/" title="Unity Web Player. Install now!">
                        <img alt="Unity Web Player. Install now!"
                             src="http://webplayer.unity3d.com/installation/getunity.png" width="193" height="63"/>
                    </a>
                </div>
            </div>
        </div>

    <?php
    elseif ($ext == 'dcr'):
        if (!empty($current_game->parameters)) {
            $params = array();
            foreach (explode('&', $current_game->parameters) as $p) {
                @list($k, $v) = explode('=', $p);
                $params[$k] = !empty($v) ? $v : '';
            }
            $fileurl = url::link($current_game->file)->fulluri($params);
        } else
            $fileurl = $current_game->file;

        if (function_exists('dcr_file_playcode')) {
            dcr_file_playcode($fileurl, $width, $height);
            return;
        }
        ?>
        <div id="GameFileWrapper" style="width:<?= $width ?>;height:<?= $height ?>;margin:0 auto;">
            <object classid="clsid:166B1BCA-3F9C-11CF-8075-444553540000"
                    codebase="http://download.macromedia.com/pub/shockwave/cabs/director/sw.cab#version=11,0,0,09"
                    id="soccermomroadtrip" width="100%" height="100%">
                <param name="src" value="<?= $fileurl ?>"/>
                <param name="swStretchStyle" value="fill"/>
                <param name="swRemote"
                       value="swSaveEnabled='true' swVolume='true' swRestart='true' swPausePlay='true' swFastForward='true' swContextMenu='true' "/>
                <param name="bgColor" value="#000000/"/>
                <param name="PlayerVersion" value="11"/>
                <param name='wmode' value='transparent'/>
                <param name='allowFullScreenInteractive' value='true'/>
                <embed src="<?= $fileurl ?>" width="100%" height="100%" bgcolor="#000000" base="."
                       name="soccermomroadtrip" sw1="140315" sw2="110705" swliveconnect="true" playerversion="11"
                       swstretchstyle="fill" sw8="16a1e53cde1caebc58b55fb66da7c6b1" sw9="soccer-mom-road-trip"
                       swremote="swSaveEnabled='true' swVolume='true' swRestart='true' swPausePlay='true' swFastForward='true' swContextMenu='true' "
                       swlist="" type="application/x-director"
                       pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveDirector"></embed>
            </object>
        </div>
    <?php
    elseif ($ext == 'swf' || empty($ext)):
        if (!empty($current_game->parameters)) {
            $params = array();
            foreach (explode('&', $current_game->parameters) as $p) {
                @list($k, $v) = explode('=', $p);
                $params[$k] = !empty($v) ? $v : '';
            }
            $fileurl = url::link($current_game->file)->fulluri($params);
        } else
            $fileurl = $current_game->file;


        if (function_exists('swf_file_playcode')) {
            swf_file_playcode($fileurl, $width, $height);
            return;
        }
        ?>
        <div id="GameFileWrapper" style="width:<?= $width ?>;height:<?= $height ?>;margin:0 auto;">
            <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
                    codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,16,0"
                    id="nxGame" width="100%" height="100%">
                <param name="movie" value="<?= $fileurl ?>"/>
                <param name="quality" value="high"/>
                <param name='wmode' value='transparent'/>
                <!--param name="wmode" value="transparent" /-->
                <param name="allowscriptaccess" value="never"/>
                <!--[if !IE]>-->
                <object type="application/x-shockwave-flash" data="<?= $fileurl ?>" width="100%" height="100%">
                    <param name="movie" value="<?= $fileurl ?>">
                    <param name="quality" value="high">
                    <param name='wmode' value='transparent'/>
                    <param name='allowFullScreenInteractive' value='true'/>
                    <!--<![endif]-->
                    <a href="http://www.adobe.com/go/getflash">
                        <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif"
                             alt="Get Adobe Flash Player"/>
                    </a>
                    <!--[if !IE]>-->
                </object>
                <!--<![endif]-->
            </object>
            </object>
        </div>
    <?php
    endif;
}

function ab_game_file_size($current_game, $byte = false)
{
    if (isset($current_game->file_path) && is_file($current_game->file_path)) {
        $filePath = $current_game->file_path;
    } else if (isset($current_game->file) && validate::_is_URL($current_game->file)) {
        $filePath = $current_game->file;
    } else if (is_string($current_game) && is_file($current_game->file_path)) {
        $filePath = $current_game;
    } else
        return false;
    if (!$byte) {
        $bt = path::get_file_size($filePath, true);
        if ($bt == 0)
            return false;
        return convert::formatSizeUnits($bt);
    } else
        path::get_file_size($filePath, true);
}

function ab_game_comment_alert($game_id = null)
{
    global $current_game;
    if (!$game_id) {
        if (isset($current_game->id)) {
            $game_id = $current_game->id;
        }
    }
    return isAlert($game_id);
}

function ab_game_comments($game_id = null)
{
    global $current_game;
    /* facebook commenting */
    if (Setting::get_data('game_comments', 'val') == 'facebook') {
        $game_url = @ab_game_url($current_game);
        if ($game_url) {
            if (function_exists('facebook_comment'))
                facebook_comment($game_url);
            else {
                ?>
                <div id="fb-root"></div>
                <script type="text/javascript">(function (d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (d.getElementById(id))
                            return;
                        js = d.createElement(s);
                        js.id = id;
                        js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
                        fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));</script>
                <div class="fb-comments" data-href="<?= $game_url ?>" data-numposts="5" data-colorscheme="light"></div>
            <?php
            }
        }
        return;
    }

    if (!$game_id) {
        if (isset($current_game->id)) {
            $game_id = $current_game->id;
        }
    }

    $com = new pengu_comment($game_id);
    $com->set_data_table('abs_comment');
    $com->user_authority = false;
    switch (setting::get_data('game_comments', 'val')) {
        case 'off' :
            return;
        case 'member_only' :
            $com->user_authority = true;
            $com->set_authority_model('Member');
            break;
        case 'facebook':
            break;
    }

    $com->auto_approve = false;
    switch (setting::get_data('comments_approval', 'val')) {
        case 'on' :
            $com->auto_approve = true;
            break;
        case 'member_only' :
            if (Member::isLogin())
                $com->auto_approve = true;
            break;
    }

    $com->avatar = '50x50';
    $com->avatars_folder = content_url() . '/upload';
    $com->show_editbutton = false;
    $com->get_website = false;
    $com->reply_level = 0;
    $com->approve_status_value = 2;
    $com->items_per_page = intval(setting::get_data('comments_per_page', 'val'));

    if (isset($_POST['submit'])) {

        if (convert::to_bool(setting::get_data('comments_bad_words_filter', 'val'))) {
            $list = @explode(',', setting::get_data('comments_bad_words_list', 'val'));
            array_walk($list, create_function('&$v', '$v=preg_quote(trim($v));'));
            $pattern_list = join('|', $list);

            foreach ($_POST as &$p)
                $p = preg_replace("/({$pattern_list})/i", '', $p);
        }

        $error = 0;
        $banned_list = setting::get_data('comments_banned_ips', 'val');
        if (!empty($banned_list)) {
            $denyIps = explode(',', $banned_list);
            array_walk($denyIps, create_function('&$v', '$v=trim($v);'));
            if (in_array(agent::get_client_ip(), $denyIps)) {
                perror("You can't post a comment!")->Id($game_id);
                $error = 1;
            }
        }
        if (!$error)
            $com->save($_POST);
    }
    $com->showposts();
    $com->showform();
}

function ab_latest_game_comments($limit = 10)
{
    $cm = new Comment;
    $cm->Allcomments($limit, 0);
    return $cm;
}

function ab_category_image($cat, $width = null, $height = null)
{
    $image_file = null;
    if (is_string($cat))
        $image_file = $cat;
    else
        if (isset($cat['icon']))
            $image_file = $cat['icon'];

    $url = content_url() . '/upload/' . $image_file;
    $path = content_path() . '/upload/' . $image_file;

    if ((empty($image_file) || !file_exists($path)) && isset($cat['seo_title']) && isset($cat['id'])) {
        /* category image according to theme */
        if ($file_seo = glob(template_path() . '/images/ctg/' . $cat['seo_title'] . ".*"))
            $image_file = basename($file_seo[0]);
        else
            if ($file_id = glob(template_path() . '/images/ctg/' . $cat['id'] . ".*"))
                $image_file = basename($file_id[0]);

        $url = template_url() . '/images/ctg/' . $image_file;
        $path = template_path() . '/images/ctg/' . $image_file;
    }
    return _create_image($url, $path, $width, $height, false, false);
}

function ab_game_thumb($game, $width = null, $height = null)
{
    if (is_string($game))
        $image_file = $game;
    else
        if (isset($game->img)) {
            $image_file = $game->img;
            if (isset($game->featured) && $game->featured == 1 && !empty($game->featured_img))
                $image_file = $game->featured_img;
        } else
            return;
    return ab_game_create_img($image_file, $width, $height);
}

function ab_game_slide_image($game, $width = null, $height = null)
{
    if (is_string($game))
        $image_file = $game;
    else
        if (isset($game->slideshow_img))
            $image_file = $game->slideshow_img;
        else
            return;
    return ab_game_create_img($image_file, $width, $height);
}

function ab_block_title($block_name)
{
    $block_id = intval(substr($block_name, strpos($block_name, '-') + 1));
    $model = new Block();
    return $model->getBlockById($block_id, 'block_title');
}

function ab_block_content($block_name)
{
    $block_id = intval(substr($block_name, strpos($block_name, '-') + 1));
    $model = new Block();
    return $model->getBlockById($block_id, 'block_content');
}

/* ======= Paths ======== */

function _makeUrlCDN(&$src, $cdn, $cdn_zone = null)
{
    $domain = get_domain($src, false);
    $subdomain = null;
    if (strpos(get_domain($src, true), '.' . $domain) !== false)
        $subdomain = str_replace('.' . $domain, '', get_domain($src, true));

    if (!empty($cdn_zone) && $cdn_zone != $domain) {
        $src = str_replace($domain, $cdn_zone, $src);
    }

    if (!empty($cdn)) {
        $src = preg_replace("/www\./i", "", $src);
        if ($subdomain)
            $src = str_replace($subdomain, $cdn, $src);
        else {
            if (preg_match('/^https?\:\/\//i', $src))
                $src = str_replace('://', "://" . $cdn . '.', $src);
            else
                $src = $cdn . '.' . $src;
        }
    }
}

function ab_template_images()
{
    static $u;
    if (!isset($u)) {
        $u = template_url() . '/images';
        _makeUrlCDN($u, setting::get_data('images_cdn', 'val'), setting::get_data('images_cdn_zone', 'val'));
    }
    return $u;
}

function ab_game_images()
{
    static $u;
    if (!isset($u)) {
        $u = content_url() . '/upload/games/images';
        _makeUrlCDN($u, setting::get_data('images_cdn', 'val'), setting::get_data('images_cdn_zone', 'val'));
    }
    return $u;
}

/* links */
define('_ab_homepage_links', '0');
define('_ab_internal_links', '1,');
define('_ab_allpages_links', '2');
define('_ab_linkspage_links', '3,2,1');

function ab_partners_links($limit = null, $pos = null)
{
    $links = new Link();
    if ($pos === null) {
        if (route_name() == 'homepage')
            $pos = '0,2'; //homepage,allpages
        elseif (route_name() != 'homepage')
            $pos = '1,2'; //internalpages,allpages  
    }
    $links->allLinks($limit, null, $pos);
    return $links;
}

/* mobile */

function ab_game_mobile_link($game)
{
    if (!empty($game->html5_link) && strlen($game->html5_link) > 10)
        return $game->html5_link;
    elseif (is_ios())
        return !empty($game->ios_link) ? $game->ios_link : null;
    else
        return !empty($game->android_link) ? $game->android_link : null;
}

function ab_game_ishtml5($game)
{
    if (!empty($game->html5_link) && strlen($game->html5_link) > 10)
        return true;
    return false;
}

/* ribbon */

function ab_has_ribbon_new($game)
{
    if (!empty($game->ribbon) && $game->ribbon == 'new' && intval($game->ribbon_ex) > time())
        return true;
    return false;
}

function ab_has_ribbon_hot($game)
{
    if (!empty($game->ribbon) && $game->ribbon == 'hot' && intval($game->ribbon_ex) > time())
        return true;
    return false;
}

function ab_has_ribbon_featured($game)
{
    if (!empty($game->ribbon) && $game->ribbon == 'featured' && intval($game->ribbon_ex) > time())
        return true;
    return false;
}
