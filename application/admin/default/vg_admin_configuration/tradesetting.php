<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: tradesetting.php
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
                            <?= L::sidebar_trd_set; ?>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- /Navigation Menu -->
            <div class="row-fluid">
                <div>
                    <h3 class="heading"><?= L::sidebar_trd_set; ?> </h3>

                    <form class="form_validation_reg" id="myform" name="myform" method="post"
                          action="<?= url::itself()->fulluri() ?>" novalidate="novalidate" enctype="multipart/form-data"
                          onsubmit="return false">
                        <dl class="dl-horizontal">

                            <dt><label><?= L::forms_active_trading; ?></label></dt>
                            <dd>
                                <?php
                                $data = setting::get_data('active_trading');
                                $comment = getcomment($data);
                                $active_trading = @$data['val'];
                                ?>
                                <div>
                                    <?= $comment ?>
                                    <select name="trade[active_trading]" id="active_trading">
                                        <option value="on"><?= L::global_state_on; ?></option>
                                        <option value="off"><?= L::global_state_off; ?></option>
                                    </select>
                                    <?php if ($comment) echo '</a>'; ?>
                                </div>
                            </dd>
                            <dt><label><?= L::forms_perform_trade_after_play; ?> </label></dt>
                            <dd>
                                <?php
                                $data = setting::get_data('max_visitor_played');
                                $comment = getcomment($data);
                                ?>
                                <div style="margin-left: 0">
                                    <?= $comment ?>
                                    <span style="margin-left: 0">
                                    <input name="trade[max_visitor_played]" id="max_visitor_played"
                                           class="hb-spinner-box input-mini" type="text"
                                           value="<?= $data['val'] ? $data['val'] : 4; ?>" autocomplete="off"/>
                                </span>
                                    <span class="help-inline"><?= L::forms_games; ?></span>
                                    <?php if ($comment) echo '</a>'; ?>
                                    <em></em>
                                </div>
                            </dd>

                            <dt><label><?= L::forms_default_trader_ratio; ?> </label></dt>
                            <dd>
                                <?php
                                $data = setting::get_data('default_trade_ratio');
                                $comment = getcomment($data);
                                ?>
                                <div style="margin-left: 0">
                                    <?= $comment ?>
                                    <span style="margin-left: 0">
                                    <input name="trade[default_trade_ratio]" id="default_trade_ratio"
                                           class="hb-spinner-box input-mini" type="text"
                                           value="<?= $data['val'] ? $data['val'] : 1.2; ?>" autocomplete="off"/>
                                </span>
                                    <?php if ($comment) echo '</a>'; ?>
                                    <em></em>
                                </div>
                            </dd>
                            <dt><label><?= L::forms_receive_traffic_page; ?>  </label></dt>
                            <dd>
                                <?php
                                $data = setting::get_data('trade_recive_page');
                                $comment = getcomment($data);
                                $trade_recive_page = @$data['val'];
                                ?>
                                <div style="margin-left: 0">
                                    <?= $comment ?>
                                    <select name="trade[trade_recive_page]" id="trade_recive_page">
                                        <option value="pre"><?= L::forms_pre_play_page; ?></option>
                                        <option value="play"><?= L::forms_play_page; ?></option>
                                    </select>
                                    <?php if ($comment) echo '</a>'; ?>
                                </div>
                            </dd>
                            <dt><label><?= L::forms_default_redirect_url; ?></label></dt>
                            <dd>
                                <?php
                                $data = setting::get_data('send_url_if_no_trader');
                                $comment = getcomment($data);
                                ?>
                                <div style="margin-left: 0">
                                    <?= $comment ?>
                                    <span style="margin-left: 0">
                                    <input name="trade[send_url_if_no_trader]" id="send_url_if_no_trader"
                                           class="input-large" type="url"
                                           value="<?= $data['val'] ? $data['val'] : 'http://'; ?>" autocomplete="off"/>
                                </span>
                                    <span class="help-inline"><?= L::forms_if_no_traders_found; ?></span>
                                    <?php if ($comment) echo '</a>'; ?>
                                    <em></em>
                                </div>
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
        selectOptionByValue('active_trading', '<?= @$active_trading ?>');
        selectOptionByValue('trade_recive_page', '<?= @$trade_recive_page ?>');

        $(document).ready(function () {
            $("#max_visitor_played").spinner({
                min: 1
            });
            $("#default_trade_ratio").spinner({
                step: 0.05,
                numberFormat: "n"
            });
        });

        var fValidation;
        var loading_config = {
            'indicatorZIndex': 990,
            'overlayZIndex': 990
        };
        // Validation Options
        jQuery.validator.addMethod("url", function (value, element) {
            return this.optional(element) || /^(https?:\/\/)?((localhost|[a-z0-9\-]+(\.[a-z0-9\-]+)+)(:[0-9]+)?(\/.*)?)?$/.test(value);
        }, "<?= addslashes(L::alert_invalid_link);?>");
        fValidation = $("#myform").validate({
            rules: {
                'trade[max_visitor_played]': {
                    number: true
                },
                'trade[default_trade_ratio]': {
                    number: true
                },
            },
            messages: {
                'trade[max_visitor_played]': "Please enter a valid number",
            },
            debug: false,
            errorPlacement: function (error, element) {
                error.appendTo(element.closest('div').find("em"));
            },
            onfocusout: false,
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
                    data: {'encodedData': encodePostData(data)},
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