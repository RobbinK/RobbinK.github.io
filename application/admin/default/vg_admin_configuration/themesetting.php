<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: themesetting.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:56
##########################################################
 */

### call header
abs_admin_inc(l_basic);
abs_admin_inc(l_sticky);
abs_admin_inc(l_validate);
abs_admin_inc(l_unserializeForm);
abs_admin_inc(l_colorbox);
get_header();
#************** 
css::load(template_url() . '/css/configuration_style' . (lang_isrtl() ? '_rtl' : null) . '.css');

$jqcode = "";
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
                        <?= L::sidebar_theme_set; ?>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /Navigation Menu -->
        <div class="row-fluid">
            <div>
                <h3 class="heading"><?= L::sidebar_theme_set; ?></h3> 

                <?php
                $error = 1;

                if (defined('DefaultTemplate')) {

                    $path = root_path() . '/themes/' . DefaultTemplate . '/theme_setting.php';
                    if (file_exists($path)) {

                        $themeConfiguration = include $path;
                        if (isset($themeConfiguration) && is_array($themeConfiguration) && count($themeConfiguration) > 0)
                            $error = 0;
                    }
                }

                if (!$error):
                    ?>
                    <form class="form_validation_reg" id="myform" method="post" action="<?= url::itself()->fulluri() ?>"  novalidate="novalidate" enctype="multipart/form-data" onsubmit="return false">
                        <dl class="dl-horizontal">
                            <dt><?= L::forms_site_theme; ?></dt>
                            <dd><b style="height: 26px;display: block;padding: 4px;"><?= ucfirst(DefaultTemplate) ?></b></dd>
                            <?php
                            $html = "";
                            foreach ($themeConfiguration as $key => $cnf) :
                                $data = setting::get_data($key);
                                $default = isset($data['val']) ? $data['val'] : @$cnf['default'];
                                $extendedOption = @$cnf['exParams'];
                                if ($cnf['type'] == 'checkbox')
                                    $html.= "<dt></dt>";
                                else
                                    $html.= "<dt ><label>{$cnf['label']}</label></dt>";
                                $html.="<dd>";

                                //--Type=text
                                if ($cnf['type'] == 'text') {
                                    $html.="<input type='text' name='theme[{$key}]'  id='{$key}' value='" . $default . "' {$extendedOption} /> ";
                                }

                                //--Type=textarea
                                if ($cnf['type'] == 'textarea') {
                                    $html.=" <textarea class='auto_expand' name='theme[{$key}]'  id='{$key}' rows='2' cols='1' {$extendedOption} >" . @htmlspecialchars($default) . "</textarea> ";
                                }

                                //--Type=selectF
                                if ($cnf['type'] == 'select') {
                                    $html.="<select  name='theme[{$key}]'  id='{$key}' {$extendedOption}>";
                                    foreach ($cnf['options'] as $k => $v)
                                        $html.="<option value='{$k}'>{$v}</option>";
                                    $html.="</select>";
                                    $jqcode.="selectOptionByValue('{$key}', '$default');\n";
                                }

                                //--Type=radio
                                if ($cnf['type'] == 'radio') {
                                    foreach ($cnf['options'] as $k => $v) {
                                        $html.="<label class='uni-radio'>";
                                        $html.="<input type='radio' name='theme[{$key}]'  id='{$key}' value='" . $k . "' " . ($k == $default ? 'checked="checked"' : null) . "  {$extendedOption}  /> ";
                                        $html.="{$v}</label>";
                                    }
                                }

                                //--Type=checkbox
                                if ($cnf['type'] == 'checkbox') {
                                    $html.="<label class='uni-checkbox'>";
                                    $html.="<input type='checkbox' name='theme[{$key}]'  id='{$key}' value='" . @$cnf['value'] . "' " . ($default == $cnf['value'] ? 'checked="checked"' : null) . "  {$extendedOption}  /> ";
                                    $html.="{$cnf['label']}</label>";
                                }

                                $html.="</dd>";
                                if (isset($cnf['jqcode']))
                                    $jqcode.=$cnf['jqcode'] . "\n";
                            endforeach;
                            echo $html;
                            ?>
                            <dt></dt> 
                            <div class="formSep"></div>
                            <dt></dt>
                            <dd> 
                                <div class="controls">
                                    <a class="btn btn-abs" onclick="$('#myform').submit()"><?= L::forms_save_changes; ?></a>
                                </div>
                            </dd>
                        </dl>
                    </form>
                <?php else : ?>
                    <?= L::alert_no_configs; ?>
                <?php endif; ?>
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
                        },
                        messages: {
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
<?= $jqcode ?>
                    });



</script>
<?php
get_footer();
?>