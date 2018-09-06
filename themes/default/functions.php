<?php
define('yCht', 'ggw8G3EbmoGiapR-Viu07DAwU6O1n-W_Hs6yv8n9bExvKvUopYn-NZb6XuXPyLkWMTVU_8aBWPH6d1smDKNb6TH661q1cBquhGjLpBTeW96u15B4yPkPCAjHZcAlaqbI');

function show_social_links() {
    $facebook = ab_get_setting('facebook_link');
    $twitter = ab_get_setting('twitter_link');
    $google = ab_get_setting('google_link');
    $out = '<div class="social_links">';
    if (!empty($facebook) && validate::_is_URL($facebook))
        $out.='<a href="' . $facebook . '" title="Facebook"><img src="' . ab_template_images() . '/social_links/facebook_16.png"/></a>';
    if (!empty($twitter) && validate::_is_URL($twitter))
        $out.='<a href="' . $twitter . '" title="Twitter"><img src="' . ab_template_images() . '/social_links/twitter_16.png"/></a>';
    if (!empty($google) && validate::_is_URL($google))
        $out.='<a href="' . $google . '" title="Google+"><img src="' . ab_template_images() . '/social_links/googleplus_16.png"/></a>';
    $out.="</div>";
    return $out;
}

function ab_show_tags($game) {
    $tags = ab_game_tags($game, null);
    $out = array();
    if (is_array($tags)) {
        foreach ($tags as $tag) {
            if (!empty($tag['seo_name']))
                $out [] = "<a href='" . ab_tag_url($tag) . "'>{$tag['name']}</a>";
        }
        return join(' ', $out);
    }
}

function ab_show_links($limit = null) {
    eval(globals_st($GLOBALS));
    $ab_result = ab_partners_links($limit);
    while ($link = $ab_result->the_link()):
        ?>
        <li><a href="<?= $link['url'] ?>"><?= $link['title'] ?></a></li>
        <?php
    endwhile;
}

function ab_show_pages() {
    eval(globals_st($GLOBALS));
    $ab_result = ab_static_pages();
    while ($page = $ab_result->the_page()):
        ?>
        <li><a href="<?= ab_page_url($page) ?>"><?= $page['page_title'] ?></a></li>
        <?php
    endwhile;
}

function ab_show_categories() {
    $ab_result = ab_categories();
    while ($cat = $ab_result->the_category()) :
        if (ab_isactive_ctg($cat))
            $sclass = 'active';
        else
            $sclass = null;
        ?>
        <li><a class="<?= $sclass ?>" href="<?= ab_category_url($cat) ?>"><?= $cat['title'] ?></a></li>
        <?php
    endwhile;
}

function ab_show_featured_categories() {
    $ab_result = ab_featured_categories();
    while ($cat = $ab_result->the_category()) :
        ?>
        <li><a href="<?= ab_category_url($cat) ?>"><?= $cat['title'] ?></a></li>
        <?php
    endwhile;
}

function ab_show_non_featured_categories() {
    $ab_result = ab_non_featured_categories();
    while ($cat = $ab_result->the_category()) :
        ?>
        <li><a href="<?= ab_category_url($cat) ?>"><?= $cat['title'] ?></a></li>
        <?php
    endwhile;
}

function js_header_libraries() {
    js::loadJquery(false, 604800, '1.11.0');
    js::loadjquery_migrate(false, 604800, '1.2.1');
    js::load(array(
        template_path() . '/js/jRating/jRating.jquery.min.js',
    ));
}

function js_form_libraries() {
    js::loadBootStrap(true);
    js::load(array(
        template_path() . '/js/jquery-ui/jquery-ui.js',
        template_path() . '/js/forms/jquery.autosize.min.js',
        template_path() . '/js/jquery.showloading/jquery.showLoading.min.js',
        template_path() . '/js/jquery-deparam/jquery.deparam.min.js',
        template_path() . '/js/colorbox/jquery.colorbox.min.js',
        template_path() . '/js/jquery-validation-1.11.1/jquery.validate.min.js',
        template_path() . '/js/smoke/smoke.min.js',
        template_path() . '/js/simple_ajax_uploader/SimpleAjaxUploader.min.js',
        template_path() . '/js/safe.js',
        template_path() . '/js/str.js',
        template_path() . '/js/jquery.imagesloaded.min.js',
            ), array(JS_FORCELOAD => true, JS_MINIFY => true));
}

function js_signup_libraries() {
    js::load(array(
        template_path() . '/js/jquery-validation-1.11.1/jquery.validate.min.js',
        template_path() . '/js/jquery.showloading/jquery.showLoading.min.js',
        template_path() . '/js/jquery-deparam/jquery.deparam.min.js',
            ), array(JS_FORCELOAD => true, JS_MINIFY => true));
}

function js_play_libraries() {
    js::load(array(
        template_path() . '/js/jquery-ui/jquery-ui.js',
        template_path() . '/js/fullscreen/jquery.fullscreen-0.4.1.min.js',
        template_path() . '/js/jquery.showloading/jquery.showLoading.min.js',
        template_path() . '/js/playpage.js',
            ), array(JS_FORCELOAD => true, JS_MINIFY => true));
    js::$loadedJqueryUi = true;
}

/* -------- */

function facebook_comment($game_url) {
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
