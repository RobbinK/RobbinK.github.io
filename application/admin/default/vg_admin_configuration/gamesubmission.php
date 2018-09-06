<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: gamesubmission.php
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
                        Game Submission Setting
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /Navigation Menu -->
        <div class="row-fluid">
            <div>
                <h3 class="heading">Game Submission </h3> 
                <form class="form_validation_reg" id="myform" name="myform" method="post" action="<?= url::itself()->fulluri() ?>"  novalidate="novalidate" enctype="multipart/form-data" onsubmit="return false">
                    <dl class="dl-horizontal">
                        <dt><label>Allow Game Submissions</label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('submission_allow');
                            echo $comment = getcomment($data);
                            $AllowGameSubmited = @$data['val'];
                            ?> 
                            <select  name="game_submission[submission_allow]" id="allow_game_submissions">
                                <option value="on">on</option>
                                <option value="off">off</option>
                                <option value="Members_Only">Members Only</option> 
                            </select>
                            <?php if ($comment) echo '</a>'; ?> 
                        </dd>
                        <dt><label>Max Game File Size</label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('submission_max_file_size');
                            $comment = getcomment($data);
                            ?>
                            <div>
                                <?= $comment ?>
                                <input type="text" class="input-mini"   name="game_submission[submission_max_file_size]"  value="<?= $data['val'] ? $data['val'] : 5000; ?>" id="submission_max_file_size" class="span3" />
                                <span class="help-inline">KB</span>
                                <?php if ($comment) echo '</a>'; ?>  
                                <em></em>
                            </div>
                        </dd> 
                        <dt><label>Max Thumb size</label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('submission_max_thumb_size');
                            $comment = getcomment($data);
                            ?>
                            <div>
                                <?= $comment ?>
                                <input type="text" class="input-mini"  name="game_submission[submission_max_thumb_size]" id="Max_Thumb_size" value="<?= $data['val'] ? $data['val'] : 25; ?>" id="submission_max_thumb_size" class="span3" />
                                <span class="help-inline">KB</span>
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

                    selectOptionByValue('allow_game_submissions', '<?= @$AllowGameSubmited ?>');
                    var fValidation;
                    var loading_config = {
                        'indicatorZIndex': 990,
                        'overlayZIndex': 990
                    };
                    // Validation Options
                    fValidation = $("#myform").validate({
                        rules: {
                            'game_submission[submission_max_file_size]': {
                                number: true
                            },
                            'game_submission[submission_max_thumb_size]': {
                                number: true
                            }
                        },
                        messages: {
                            email: "Please enter a valid email address",
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