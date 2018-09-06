<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: cachesetting.php
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
    <style>
        .input-append .add-on {
            margin-left: -1px !important;
            -webkit-border-radius: 0 4px 4px 0 !important;
            -moz-border-radius: 0 4px 4px 0 !important;
            border-radius: 0 4px 4px 0 !important;
            direction: ltr;
        }

        .input-append input {
            float: left;
            -webkit-border-radius: 4px 0 0 4px !important;
            -moz-border-radius: 4px 0 0 4px !important;
            border-radius: 4px 0 0 4px !important;
        }
    </style>
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
                            <?= L::sidebar_cdn_set; ?>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- /Navigation Menu -->
            <div class="row-fluid">
                <div>
                    <h3 class="heading"><?= L::sidebar_cdn_set; ?></h3>

                    <form class="form_validation_reg" id="myform" name="myform" method="post"
                          action="<?= url::itself()->fulluri() ?>" novalidate="novalidate" enctype="multipart/form-data"
                          onsubmit="return false">
                        <dl class="dl-horizontal">

                            <dt><label><?= L::forms_caching_system; ?></label></dt>
                            <dd>
                                <?php
                                $data = setting::get_data('cache');
                                echo $comment = getcomment($data);
                                $cache = @$data['val'];
                                ?>
                                <select name="cache[cache]" id="cache" class="input-small">
                                    <option value="enable"><?= L::global_enable; ?></option>
                                    <option value="disable"><?= L::global_disable; ?></option>
                                </select>
                                <a href="javascript:void(0);" class="help-inline" style="color:#2CADE9;"
                                   onclick="return clearCaches();"><?= L::forms_clear_cache; ?></a>
                                <?php if ($comment) echo '</a>'; ?>

                            </dd>
                            <dt><label><?= L::forms_cache_expire_time; ?></label></dt>
                            <dd>
                                <?php
                                $data = setting::get_data('cache_time');
                                $comment = getcomment($data);
                                ?>
                                <div>
                                    <?= $comment ?>
                                    <input type="text" name="cache[cache_time]" id="cache_time" class="input-mini"
                                           value="<?= is_numeric($data['val']) ? round($data['val'] / 60) : null ?>"/>
                                    <span class="help-inline"><?= L::global_minutes; ?></span>
                                    <?php if ($comment) echo '</a>'; ?>
                                    <em></em>
                                </div>
                            </dd>
                            <div class="formSep"></div>
                            <?php
                            $domain = lib::get_domain(root_url(), false);
                            ?>
                            <dt><label> <?= L::forms_images_cdn; ?> </label></dt>
                            <dd>
                                <?php
                                $data_cdn = setting::get_data('images_cdn');
                                $data_cdn_zone = setting::get_data('images_cdn_zone');
                                $comment = getcomment($data_cdn);
                                ?>
                                <?= $comment ?>
                                <input type="text" class="input-mini" name="cache[images_cdn]"
                                       value="<?= $data_cdn['val'] ?>"/>
                                .
                                <input type="text" class="input-medium" name="cache[images_cdn_zone]"
                                       value="<?= !empty($data_cdn_zone['val'])?$data_cdn_zone['val']:$domain ?>"/>
                                <?php if ($comment) echo '</a>'; ?>
                                <em></em>
                            </dd>
                            <dt><label><?= L::forms_js_files_cdn; ?></label></dt>
                            <dd>
                                <?php
                                $data_cdn = setting::get_data('js_cdn');
                                $data_cdn_zone = setting::get_data('js_cdn_zone');
                                $comment = getcomment($data_cdn);
                                ?>
                                <?= $comment ?>
                                <input type="text" class="input-mini" name="cache[js_cdn]" value="<?= $data_cdn['val'] ?>"/>
                                .
                                <input type="text" class="input-medium" name="cache[js_cdn_zone]"
                                       value="<?= !empty($data_cdn_zone['val'])?$data_cdn_zone['val']:$domain  ?>"/>

                                <?php if ($comment) echo '</a>'; ?>
                                <em></em>

                            </dd>
                            <dt><label><?= L::forms_css_files_cdn; ?></label></dt>
                            <dd>
                                <?php
                                $data_cdn = setting::get_data('css_cdn');
                                $data_cdn_zone = setting::get_data('css_cdn_zone');
                                $comment = getcomment($data_cdn);
                                ?>
                                <?= $comment ?>
                                <input type="text" class="input-mini" name="cache[css_cdn]"
                                       value="<?= $data_cdn['val'] ?>"/>
                                .
                                <input type="text" class="input-medium" name="cache[css_cdn_zone]"
                                       value="<?= !empty($data_cdn_zone['val'])?$data_cdn_zone['val']:$domain  ?>"/>
                                <?php if ($comment) echo '</a>'; ?>
                                <em></em>
                            </dd>
                            <div class="formSep"></div>
                            <dt></dt>
                            <dd>
                                <div class="controls">
                                    <a class="btn btn-abs"
                                       onclick="$('#myform').submit()"><?= L::forms_save_changes; ?></a>
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
        selectOptionByValue('cache', '<?= @$cache ?>');

        var fValidation;
        var loading_config = {
            'indicatorZIndex': 990,
            'overlayZIndex': 990
        };

        $(function () {
            $("#cache_time").spinner({
                min: 1
            });
        });

        function clearCaches() {
            abs_cache.clean_mysql();
            return false;
        }

        // Validation Options
        fValidation = $("#myform").validate({
            rules: {
                'cache[cache_time]': {
                    number: true
                },
            },
            messages: {
                'cache[cache_time]': "Please enter a valid number",
            },
            debug: false,
            highlight: function (element) {
                if ($(element).closest('dd').find('em').length)
                    $(element).closest('dd').find('em').closest('div,dd').addClass("f_error");
                else
                    $(element).closest('div').addClass("f_error");
            },
            unhighlight: function (element) {
                if ($(element).closest('dd').find('em').length)
                    $(element).closest('dd').find('em').closest('div,dd').removeClass("f_error");
                else
                    $(element).closest('div').removeClass("f_error");
            },
            errorPlacement: function (error, element) {
                if ($(element).closest('dd').find('em').length)
                    error.appendTo($(element).closest('dd').find('em'));
                else
                    error.insertAfter(element);
            },
            submitHandler: function (form) {
                $('#myform').showLoading(loading_config);
                data = $.deparam($('#myform').serialize());
                //tinymce
                if (typeof(tinyMCE) != 'undefined') {
                    $('textarea.tinymce').each(function () {
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
                    success: function (result) {
                        $('#myform').hideLoading();
                        obj = JSON.parse(result);
                        if (obj.save_code === 1) {
                            $.sticky(obj.save_txt, {
                                autoclose: 5000,
                                position: "top-right",
                                type: "st-success",
                                speed: "fast"
                            });
                            return true;
                        }
                        else {
                            $.sticky("<?= addslashes(L::global_error);?>! " + obj.save_txt, {
                                autoclose: 5000,
                                position: "top-right",
                                type: "st-error",
                                speed: "fast"
                            });
                            return false;
                        }
                    }
                });
            }
        });

    </script>
<?php
get_footer();
?>