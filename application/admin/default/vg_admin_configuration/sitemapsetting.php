<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: sitemapsetting.php
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
                        <?= L::sidebar_sitemap; ?>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /Navigation Menu -->
        <div class="row-fluid">
            <div>
                <h3 class="heading"><?= L::sidebar_sitemap; ?> </h3> 
                <form class="form_validation_reg" id="myform" name="myform" method="post" action="<?= url::itself()->fulluri() ?>"  novalidate="novalidate" enctype="multipart/form-data" onsubmit="return false">
                    <dl class="dl-horizontal">

                        <dt><label><?= L::forms_generating_sitemap; ?></label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('sitemap_generating');
                            echo $comment = getcomment($data);
                            $sitemap_generating = @$data['val'];
                            ?> 
                            <select  name="sitemap[sitemap_generating]" id="sitemap_generating" class="input-small">
                                <option value="enable"><?= L::global_enable; ?></option>
                                <option value="disable"><?= L::global_disable; ?></option> 
                            </select>
                            <?php if ($comment) echo '</a>'; ?> 

                        </dd>
                        <dt><label>Sitemap Generating Method </label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('sitemap_method');
                            echo $comment = getcomment($data);
                            $sitemap_method = @$data['val'];
                            ?>
                            <select  name="sitemap[sitemap_method]" id="sitemap_method" class="input-medium">
                                <option value="1">Single Sitemap</option>
                                <option value="2">Multiple Sitemaps</option>
                            </select>
                            <?php if ($comment) echo '</a>'; ?>

                        </dd>
                        <dt><label><?= L::forms_sitemap_file_name; ?></label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('sitemap_file_name');
                            $comment = getcomment($data);
                            ?> 
                            <div>
                                <?= $comment ?>
                                <input type="text"   name="sitemap[sitemap_file_name]" id="sitemap_file_name"  class="input-medium" value="<?= $data['val'] ?>" /> 
                                <a href="javascript:void(0);" class="help- btn btn-mini btn-success" onclick="return generateSitemap();"><?= L::forms_generate; ?></a>
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
                    selectOptionByValue('sitemap_generating', '<?= @$sitemap_generating ?>');
                    selectOptionByValue('sitemap_method', '<?= @$sitemap_method ?>');

                    var fValidation;
                    var loading_config = {
                        'indicatorZIndex': 990,
                        'overlayZIndex': 990
                    };

                    function generateSitemap() {
                        $('#myform').showLoading(loading_config);
                        $.ajax({
                            type: 'get',
                            url: '<?= url::itself()->url_nonqry(array('generatesitemap' => 1)) ?>',
                            success: function(result) {
                                $('#myform').hideLoading();
                                if (result == 1) {
                                    $.sticky('sitemap was genereted succesfuly.', {autoclose: 5000, position: 'top-right', type: 'st-success', speed: 'fast'});
                                    return true;
                                } else {
                                    $.sticky('faild to generate sitemap !', {autoclose: 5000, position: 'top-right', type: 'st-error', speed: 'fast'});
                                    return true;
                                }
                            }
                        });
                        return false;
                    }

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

</script>
<?php
get_footer();
?>