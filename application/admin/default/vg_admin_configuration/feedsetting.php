<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: feedsetting.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:56
##########################################################
 */

### call header
abs_admin_inc(l_basic);
abs_admin_inc(l_sticky);
abs_admin_inc(l_validate);
abs_admin_inc(l_unserializeForm);
get_header();
#************** 
css::load(template_url() . '/css/configuration_style' . (lang_isrtl() ? '_rtl' : null) . '.css');
?> 
<div id="contentwrapper">
    <div class="main_content"> 
        <!-- Navigation Menu -->
        <nav>
            <div id="jCrumbs" class="breadCrumb module">
                <ul>
                    <li>
                        <a href="<?= url::router('admindashboard'); ?>"><i class="icon-home"></i></a>
                    </li>
                    <li>
                        <?= L::sidebar_feed_set; ?>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /Navigation Menu -->
        <div class="row-fluid">
            <div>
                <h3 class="heading"><?= L::forms_feed_setting; ?></h3>
                <form class="form_validation_reg" id="myform" method="post" action="<?= url::itself()->fulluri() ?>"  novalidate="novalidate" enctype="multipart/form-data" onsubmit="return false">
                    <dl class="dl-horizontal">
                        <dt><label><?= L::forms_game_feed_auto_downloader; ?></label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('feed_auto_downloader');
                            echo $comment = getcomment($data);
                            $feed_auto_downloader = @$data['val'];
                            ?>
                            <select  name="feed[feed_auto_downloader]" id="feed_auto_downloader" class="input-small">
                                <option value="enable"><?= L::global_enable; ?></option>
                                <option value="disable"><?= L::global_disable; ?></option> 
                            </select>
                            <?php if ($comment) echo '</a>'; ?> 
                        </dd>

                        <dt><label><?= L::forms_feed_thumb_size; ?></label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('feed_thumb_size');
                            echo $comment = getcomment($data);
                            $feed_thumb_size = @$data['val'];
                            ?>
                            <select  name="feed[feed_thumb_size]" id="feed_thumb_size" class="input-small">
                                <option></option>
                                <option value="100x100">100x100</option>
                                <option value="150x150">150x150</option> 
                                <option value="180x135">180x135</option> 
                                <option value="90x120">90x120</option> 
                                <option value="hex">Hex</option> 
                            </select>
                            <?php if ($comment) echo '</a>'; ?> 
                        </dd> 
                        <dt><label><?= L::forms_auto_activate_games_per_day; ?></label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('daily_game_installation');
                            echo $comment = getcomment($data);
                            ?>
                            <div>
                                <input type="text"  name="feed[daily_game_installation]" id="daily_game_installation"  class="input-mini" value="<?= @$data['val'] ?>" /> 
                                <?php if ($comment) echo '</a>'; ?> 
                                <em></em> 
                            </div>
                        </dd>


                        <div class="formSep"></div>
                        <dt></dt>
                        <dd>
                            <div class="controls">
                                <a class="btn btn-abs" onclick="$('#myform').submit()"><?= L::forms_save_changes; ?></a>
                            </div>
                        </dd>
                    </dl>
                </form>
            </div>
        </div>
    </div>
</div> 

<?php
get_sidebar();
get_footer('_script');
?>
<script type="text/javascript">
                    var fValidation;
                    var loading_config = {
                        'indicatorZIndex': 990,
                        'overlayZIndex': 990
                    };
                    // Validation Options
                    fValidation = $("#myform").validate({
                        rules: {
                            'feed[daily_game_installation]': {
                                min: 0,
                                max: 10,
                            },
                        },
                        messages: {
                            'feed[daily_game_installation]': {
                                min: "minimum number is 0",
                            }
                        },
                        debug: false,
                        highlight: function(element) {
                            if ($(element).closest('dd').find('em').length)
                                $(element).closest('dd').find('em').closest('div,dd').addClass("f_error");
                            else
                                $(element).closest('div').addClass("f_error");
                        },
                        unhighlight: function(element) {
                            if ($(element).closest('dd').find('em').length)
                                $(element).closest('dd').find('em').closest('div,dd').removeClass("f_error");
                            else
                                $(element).closest('div').removeClass("f_error");
                        },
                        errorPlacement: function(error, element) {
                            if ($(element).closest('dd').find('em').length)
                                error.appendTo($(element).closest('dd').find('em'));
                            else
                                error.insertAfter(element);
                        },
                        submitHandler: function(form) {
                            $('#myform').showLoading(loading_config);
                            data = $.deparam($('#myform').serialize());

                            //tinymce
                            if (typeof(tinyMCE) != 'undefined') {
                                $('textarea.tinymce').each(function() {
                                    $tinyval = tinyMCE.get($(this).attr('id')).getContent();
                                    eval("$.extend(data || {}, {" + $(this).attr('name') + ":$tinyval});");
                                });
                            }

                            // encode and slashes
                            /*
                             serialize = function (obj, prefix) {
                             var str = [];
                             for (var p in obj) {
                             var k = prefix ? prefix + "[" + p + "]" : p, v = obj[p];
                             str.push(typeof v == "object" ?
                             serialize(v, k) :
                             encodeURIComponent(k) + "=" + encodeURIComponent(v));
                             }
                             return str.join("&");
                             };
                             data = serialize(data);
                             */
                            $.ajax({
                                type: 'POST',
                                data:{'encodedData':encodePostData(data)},
                                url: "<?= url::itself()->url_nonqry(array('save' => 1)) ?>",
                                success: function(result) {
                                    $('#myform').hideLoading();
                                    obj = JSON.parse(result);
                                    if (obj.save_code === 1) {
                                        $.sticky(obj.save_txt, {autoclose: 5000, position: "top-right", type: "st-success", speed: "fast"});
                                        return true;
                                    }
                                    else {
                                        $.sticky("<?= addslashes(L::global_error);?>! " + obj.save_txt, {autoclose: 5000, position: "top-right", type: "st-error", speed: "fast"});
                                        return false;
                                    }
                                }
                            });
                        }
                    });
                    $(document).ready(function() {
                        $("#daily_game_installation").spinner({
                            min: 0,
                            max: 10
                        });
                    });
                    selectOptionByValue('feed_auto_downloader', '<?= $feed_auto_downloader ?>');
                    selectOptionByValue('feed_thumb_size', '<?= $feed_thumb_size ?>');
</script>
<?php
get_footer();
?>