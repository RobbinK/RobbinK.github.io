<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: commentsetting.php
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
                        <?= L::sidebar_cmnt_set; ?>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /Navigation Menu -->
        <div class="row-fluid">
            <div>
                <h3 class="heading"><?= L::forms_comments_setting; ?></h3>
                <form class="form_validation_reg" id="myform" method="post" action="<?= url::itself()->fulluri() ?>"  novalidate="novalidate" enctype="multipart/form-data" onsubmit="return false">
                    <dl class="dl-horizontal"> 
                        <dt><label><?= L::forms_game_commenting; ?></label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('game_comments');
                            echo $comment = getcomment($data);
                            $Game_Comments = @$data['val'];
                            ?>
                            <select  name="comments[game_comments]" id="Game_Comments">
                                <option value="on"><?= L::global_state_on; ?></optiOn>
                                <option value="off"><?= L::global_state_off; ?></option>
                                <option value="member_only"><?= L::forms_members_only; ?></option> 

                                <option value="facebook"><?= L::forms_facebook_commenting; ?></option> 
                            </select>
                            <?php if ($comment) echo '</a>'; ?> 
                        </dd>

                        <div id="commnetOtherSetting">
                            <dt><label><?= L::forms_auto_approve_comments; ?></label></dt>
                            <dd>
                                <?php
                                $data = setting::get_data('comments_approval');
                                echo $comment = getcomment($data);
                                $comments_Approval = @$data['val'];
                                ?>
                                <select  name="comments[comments_approval]" id="comments_Approval">
                                    <option value="on"><?= L::global_state_on; ?></option>
                                    <option value="off"><?= L::global_state_off; ?></option>
                                    <option value="member_only"><?= L::forms_members_only; ?></option> 
                                </select>
                                <?php if ($comment) echo '</a>'; ?> 
                            </dd> 
                            <dt><label><?= L::forms_comments_per_page; ?></label></dt>
                            <dd>
                                <?php
                                $data = setting::get_data('comments_per_page');
                                $comment = getcomment($data);
                                ?>
                                <div  style="margin-left: 0">
                                    <?= $comment ?>
                                    <span style="margin-left: 0">
                                        <input name="comments[comments_per_page]" id="comments_per_page" class="hb-spinner-box input-mini" type="text" value="<?= $data['val'] ? $data['val'] : 10; ?>" autocomplete="off"/>
                                    </span>
                                    <?php if ($comment) echo '</a>'; ?> 
                                    <em></em> 
                                </div> 
                            </dd> 
                            <dt><label><?= L::forms_bad_words_filter; ?></label></dt>
                            <dd>
                                <?php
                                $data = setting::get_data('comments_bad_words_filter');
                                echo $comment = getcomment($data);
                                $comments_bad_words_filter = @$data['val'];
                                ?>
                                <select  name="comments[comments_bad_words_filter]" id="comments_bad_words_filter">
                                    <option value="on"><?= L::global_state_on; ?></option>
                                    <option value="off"><?= L::global_state_off; ?></option> 
                                </select>
                                <?php if ($comment) echo '</a>'; ?> 
                            </dd>
                            <dt><label><?= L::forms_bad_words_list; ?></label></dt>
                            <dd>
                                <?php
                                $data = setting::get_data('comments_bad_words_list');
                                $comment = getcomment($data);
                                ?>
                                <div style="margin-left: 0">
                                    <?= $comment ?>
                                    <textarea name="comments[comments_bad_words_list]" class="auto_expand"   rows="2" cols="1" ><?= @$data['val'] ?></textarea>
                                    <span class="help-inline"><?= L::forms_separated_with_comma; ?></span>
                                    <?php if ($comment) echo '</a>'; ?> 
                                </div>
                            </dd>
                            <dt><label><?= L::forms_banned_ips_for_commenting; ?></label></dt>
                            <dd>
                                <?php
                                $data = setting::get_data('comments_banned_ips');
                                $comment = getcomment($data);
                                ?>
                                <div style="margin-left: 0">
                                    <?= $comment ?>
                                    <textarea class="auto_expand"   rows="2" cols="1" name="comments[comments_Banned_Ips]"  ><?= @$data['val'] ?></textarea>
                                    <span class="help-inline"><?= L::forms_separated_with_comma; ?></span>
                                    <?php if ($comment) echo '</a>'; ?> 
                                </div>
                            </dd> 
                        </div>

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
                            'comments[comments_per_page]': {
                                min: 0
                            }
                        },
                        messages: {
                            'comments[comments_per_Page]': "<?= addslashes(L::alert_invalid_email);?>",
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
                    selectOptionByValue('Game_Comments', '<?= @$Game_Comments ?>');
                    selectOptionByValue('comments_Approval', '<?= @$comments_Approval ?>');
                    selectOptionByValue('comments_bad_words_filter', '<?= @$comments_bad_words_filter ?>');

                    $(document).ready(function() {
                        $("#comments_per_page").spinner({
                            min: 0
                        });

                        $('#Game_Comments').change(function() {
                            if ($(this).val() == 'off' || $(this).val() == 'facebook')
                                $('#commnetOtherSetting').slideUp();
                            else
                                $('#commnetOtherSetting').slideDown();
                        });
                        $('#Game_Comments').trigger('change');
                    });



</script>
<?php
get_footer();
?>