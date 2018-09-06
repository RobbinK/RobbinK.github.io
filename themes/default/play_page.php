<?php
get_header();
css::load(array(
    template_url() . '/css/jquery-ui/custom-greenlight/jquery-ui-1.10.3.custom.min.css',
    static_url() . '/js/jquery.showloading/showLoading.css',
));
js::load(array(
    static_path() . '/js/jquery-deparam/jquery.deparam.js',
), array(JS_MINIFY => true));
js_play_libraries();
?>
<div class="content">
    <div class="single_box_outer_most_game">
        <div class="box1_wrap" id="game_wrapper">
            <div class="box1_header"><?= ab_page_heading() ?></div>
            <div style=" float: left;width: 100%;">
                <div class="clear"></div>
                <br/>

                <div class="box_zoom">
                    <div class="zoom_out"></div>
                    <div id="slider"></div>
                    <div class="zoom_in"></div>
                    <a href="#" class="requestfullscreen"></a>
                </div>
                <br/>
                <a href="#" class="exitfullscreen" style="display: none"><?= L::forms_exit_full_screen; ?></a>
                <?= ab_game_file($current_game->width, $current_game->height) ?>
                <input type="hidden" id="gameid" value="<?= $current_game->id ?>"/>
            </div>
        </div>
        <!--box1_wrap-->
        <div class="side_ad">
            <div class="side_ad_title"><?= L::forms_advertisement; ?></div>
            <div class="clear"></div>
            <div class="box_side_ad">
                <?= ab_show_ad('160x600-wide-skyscraper') ?>
                <!--Ads 160x600 -->
            </div>
        </div>
        <div style="float: left">
            <div class="left">
                <div class="box3_wrap">
                    <div>
                        <script type="text/javascript">
                            $(function () {
                                $("#tabs").tabs();
                            });</script>
                        <div id="tabs">
                            <ul>
                                <li><a href="#tabs-1"><?= L::forms_game_info; ?></a></li>
                                <li><a href="#tabs-2"><?= L::forms_share; ?></a></li>
                                <li><a href="#tabs-3"><?= L::forms_report_broken; ?></a></li>
                                <li><a href="#tabs-4"><?= L::forms_instruction; ?></a></li>
                            </ul>
                            <div id="tabs-1">

                                <!-- Game Info -->
                                <div class="gamedesc" style="position: relative">
                                    <div style="width:100%;padding: 2px;float: left">


                                        <!--Add to fav-->
                                        <div id="adfav_downbox" style="width:50%;float: left">
                                            <?php if (!ab_is_myfavourite()) : ?>
                                                <div style="display: none;" class="center" id="adfav_loading"><img
                                                        src="<?= ab_template_images() ?>/loading1.gif"/></div>
                                                <button id="addtofav"
                                                        onclick="addtofavorit(<?= $current_game->id ?>)"><?= L::forms_add_to_fav; ?></button>
                                                <div style="display: none;" id="adfav_msg">
                                                    <div id="adfav_msg_text"></div>
                                                </div>
                                            <?php else: ?>
                                                <span class="addedtofav"><?= L::alert_added_fav; ?></span>
                                            <?php endif; ?>
                                        </div>


                                        <div style="width: 210px;float: right;">
                                            <div
                                                style="text-align: right;padding: 0 40px 2px;"><?= L::forms_like_game; ?></div>
                                            <span class="btnrate right" data-val="no" id="rateno"></span>

                                            <div id="progressbar" style="width:150px;height:15px;float: right">
                                                <div class="progress-label"><?= L::forms_loading; ?></div>
                                            </div>
                                            <span class="btnrate right" data-val="yes" id="rateyes"></span>

                                            <div id="gamerate_downbox" style="clear: right;">
                                                <div style="display: none;" class="center" id="gamerate_loading"><img
                                                        src="<?= ab_template_images() ?>/loading1.gif"/></div>

                                                <div style="display: none;" id="gamerate_msg">
                                                    <div id="gamerate_msg_text"></div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                    <strong>
                                        <?= $current_game->name ?>
                                    </strong> <br/>
                                    <br/>
                                    <?= $current_game->description; ?>
                                    <div class="tags">
                                        <?= ab_show_tags($current_game); ?>
                                    </div>
                                </div>
                                <!-- //Game Info -->
                            </div>
                            <div id="tabs-2">

                                <div style="display: block;text-align: left">
                                    <p>
                                        <a href="http://twitter.com/home?status=<?= urlencode($current_game->name . ' - ' . $current_game->play_url) ?>"
                                           rel="nofollow" target="_blank"><img
                                                src="<?= ab_template_images() ?>/share/twitter.png" title="Twitter"
                                                alt="Twitter"></a>
                                        <a href="http://digg.com/submit?url=<?= urlencode($current_game->play_url) ?>&title=<?= $current_game->name ?>"
                                           rel="nofollow" target="_blank"><img
                                                src="<?= ab_template_images() ?>/share/digg.png" title="Digg"
                                                alt="Digg"></a>
                                        <a href="http://www.facebook.com/sharer.php?u=<?= urlencode($current_game->play_url) ?>&t=<?= $current_game->name ?>"
                                           rel="nofollow" target="_blank"><img
                                                src="<?= ab_template_images() ?>/share/facebook.png" title="Facebook"
                                                alt="Facebook"></a>
                                        <a href="http://del.icio.us/post?url=<?= urlencode($current_game->play_url) ?>&title=<?= $current_game->name ?>"
                                           rel="nofollow" target="_blank"><img
                                                src="<?= ab_template_images() ?>/share/delicious.png" title="Delicious"
                                                alt="Delicious"></a>
                                        <a href="http://www.stumbleupon.com/submit?url=<?= urlencode($current_game->play_url) ?>&title=<?= $current_game->name ?>"
                                           rel="nofollow" target="_blank"><img
                                                src="<?= ab_template_images() ?>/share/stumbleupon.png"
                                                title="StumbleUpon" alt="StumbleUpon"></a>
                                    </p>
                                    <h4><?= L::forms_add_to_site; ?>:</h4>

                                    <p>
                                        <textarea class="comment" style="width:400px;height: 66px;" readonly="true"
                                                  onclick="this.select();" oncontextmenu="this.select();">&lt;center&gt;&lt;iframe
                                            width="<?= $current_game->base_width ?>"
                                            height="<?= $current_game->base_height ?>"
                                            src="<?= url::link($current_game->play_url)->fulluri(array('iframe' => 1)) ?>
                                            " frameborder="0"&gt;&lt;/iframe&gt; &lt;br&gt; &lt;a
                                            href="<?= root_url() ?>"&gt;<?= ab_site_name() ?>&lt;/a&gt;
                                            &lt;/center&gt;</textarea>
                                    </p>
                                </div>

                            </div>
                            <div id="tabs-3" style="text-align:left">

                                <!-- Report Broken -->
                                <form id="broken" name="broken" method="post">
                                    <input type="hidden" name="gameid" value="<?= $current_game->id ?>"/>

                                    <div class="alert"></div>
                                    <p><?= L::forms_whats_wrong; ?></p>
                                    <textarea name="broken_comment" style="width:500px;height: 60px"></textarea>

                                    <p>
                                        <img src="" id="captcha2"/><br/>
                                        <small><?= L::forms_cant_read_image; ?> <a style="cursor: pointer;color:blue"
                                                                                   id="change-captcha"><?= L::forms_generate_new_image; ?></a>
                                        </small>
                                        <br/><br>

                                        <label for='message'><?= L::forms_enter_code; ?>:</label><br>
                                        <input maxlength="15" name="broken_captcha" autocomplete="off" type="text"/><br>
                                    </p>
                                    <button id="submitobroken"><?= L::forms_submit; ?></button>
                                </form>
                                <!-- //Report Broken -->
                            </div>
                            <div id="tabs-4">
                                <strong>
                                    <?= $current_game->name ?>
                                </strong> <br/>
                                <br/>
                                <?= $current_game->instruction; ?>
                                <!-- //Report Broken -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="left">
                <div class="box3_wrap">
                    <div class="box3_header"><?= L::forms_comments; ?></div>
                    <div class="box_container">
                        <?php
                        ab_game_comments();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--single_box_outer_most_game-->
</div><!--content-->
<div class="clear"></div>
<?php get_footer(); ?>
