<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: membersetting.php
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
                        <?= L::sidebar_mem_set; ?>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /Navigation Menu -->
        <div class="row-fluid">
            <div>
                <h3 class="heading"><?= L::forms_members_setting; ?> </h3>
                <form class="form_validation_reg" id="myform" name="myform" method="post" action="<?= url::itself()->fulluri() ?>"  novalidate="novalidate" enctype="multipart/form-data" onsubmit="return false">
                    <dl class="dl-horizontal">

                        <dt><label><?= L::forms_membership_system; ?></label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('membership_system');
                            echo $comment = getcomment($data);
                            $Membership_System = @$data['val'];
                            ?>
                            <select  name="members[membership_system]" id="Membership_System">
                                <option value="on"><?= L::global_state_on; ?></option>
                                <option value="off"><?= L::global_state_off; ?></option>
                            </select>
                            <?php if ($comment) echo '</a>'; ?>
                        </dd>
                        <dt><label><?=L::forms_members_approval;?></label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('membership_approval_system');
                            echo $comment = getcomment($data);
                            $membership_approval_system = @$data['val'];
                            ?>
                            <select  name="members[membership_approval_system]" id="Membership_Approval_System">
                                <option value="auto"><?=L::forms_auto_approve;?></option>
                                <option value="email"><?=L::forms_email_confirmation;?></option>
                                <option value="admin"><?=L::forms_admin_approval;?></option>
                            </select>
                            <?php if ($comment) echo '</a>'; ?>
                        </dd>
                        <div class="formSep"></div>
                        <dt></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('members_avatar_uploading');
                            $comment = getcomment($data);
                            ?>
                            <div>
                                <label class="uni-checkbox">
                                    <?= $comment ?>
                                    <input type="checkbox" id="Avatar_Uploading" name="members[members_avatar_uploading]" <?= @convert::to_bool($data['val']) ? 'checked="true"' : null ?> <?php if (!isset($data['val'])) echo 'checked="true"'; ?> value="1" onclick="avatarcheck()"/><?= L::forms_allow_members_upload_avatar; ?>

                                    <?php if ($comment) echo '</a>'; ?>
                                </label>
                            </div>
                        </dd>
                        <dt><label><?= L::forms_max_avatar_filesize; ?></label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('members_max_avatar_filesize');
                            $comment = getcomment($data);
                            ?>
                            <div>
                                <?= $comment ?>
                                <input type="text"   name="members[members_max_avatar_filesize]" id="Max_Avatar_Filesize" class="avatar" style="width:100px" value="<?= @$data['val'] ?>"  />
                                <span class="help-inline"><?= L::global_kilo_byte; ?></span>
                                <?php if ($comment) echo '</a>'; ?>
                                <em></em>
                            </div>
                        </dd>
                        <!--dt><label>Avatar Dimentions</label></dt>
                        <dd>
                        <?php
                        /*
                          $data = setting::get_data('members_avatar_dimentions');
                          $result = getcomment($data[$comment_field]);
                          $arr = explode('x', $data['val']);
                         */
                        ?>
                            <div>
                        <?php //=$comment?>
                                    <span for="members[members_Max_Avatar_Filesize_width" style="padding-left: 5px">width</span>
                                    <input type="text"   name="members[members_avatar_dimentions_width]" id="Max_Avatar_Filesize" class="avatar" style="width:60px" value="<!?= @$arr[0] ?>" />
                                    <span class="help-inline">KB</span>

                                    <span for="members[members_Max_Avatar_Filesize_height" style="padding-left: 20px">height</span>
                                    <input type="text"   name="members[members_avatar_dimentions_height]" id="Max_Avatar_Filesize" class="avatar" style="width:60px" value="<!?= @$arr[1] ?>"  />
                                    <span class="help-inline">KB</span>
                        <?php //if ($comment) echo '</a>'; ?> 
                                <em></em>
                            </div>
                        </dd-->
                        <div class="formSep"></div>
                        <!--dt><label>Top Players List</label></dt>
                        <dd>
                        <?php
                        $data = setting::get_data('members_top_players_list');
                        echo $comment = getcomment($data);
                        $Top_Players_List = @$data['val'];
                        ?>
                            <select  name="members[members_top_players_list]" id="Top_Players_List">
                                <option value="on">on</option>
                                <option value="off">off</option> 
                            </select>
                        <?php if ($comment) echo '</a>'; ?> 
                        </dd-->
                        <dt><label><?= L::forms_banned_ips_for_commenting; ?></label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('members_banned_ips');
                            echo $comment = getcomment($data);
                            ?>
                            <textarea name="members[members_banned_ips]" style="width:250px;height:70px;"><?= @$data['val'] ?></textarea>
                            <span class="help-inline"><?= L::forms_separated_with_comma; ?></span>
                            <?php if ($comment) echo '</a>'; ?>
                        </dd>
                        <!--dt><label>Facebook Login</label></dt>
                        <dd>
                        <?php
                        $data = setting::get_data('members_facebook_login');
                        echo $comment = getcomment($data);
                        $Facebook_Login = @$data['val'];
                        ?>
                            <select  name="members[members_facebook_login]" id="Facebook_Login">
                                <option value="on">on</option>
                                <option value="off">off</option> 
                            </select>
                        <?php if ($comment) echo '</a>'; ?> 
                        </dd-->
                        <dt><label><?= L::forms_captcha; ?></label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('members_captcha_system');
                            echo $comment = getcomment($data);
                            $Captcha_Enable = $data['val'];
                            ?>
                            <select  name="members[members_captcha_system]" id="Captcha_System">
                                <option value="enable"><?= L::global_enable; ?></option>
                                <option value="disable"><?= L::global_disable; ?></option>
                            </select>
                            <?php if ($comment) echo '</a>'; ?>
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
                    selectOptionByValue('Membership_System', '<?= @$Membership_System ?>');
                    selectOptionByValue('Membership_Approval_System', '<?= @$membership_approval_system ?>');
                    //selectOptionByValue('Top_Players_List', '<?= @$Top_Players_List ?>');
                    //selectOptionByValue('Facebook_Login', '<?= @$Facebook_Login ?>');
                    selectOptionByValue('Captcha_System', '<?= @$Captcha_Enable ?>');

                    var fValidation;
                    var loading_config = {
                        'indicatorZIndex': 990,
                        'overlayZIndex': 990
                    };
                    avatarcheck();
                    // Validation Options
                    fValidation = $("#myform").validate({
                        rules: {
                            'members[members_Max_Avatar_Filesize]': {
                                number: true
                            },
                            'members[members_Avatar_Dimentions_width]': {
                                number: true
                            },
                            'members[members_Avatar_Dimentions_height]': {
                                number: true
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


                    function avatarcheck() {
                        if (document.getElementById("Avatar_Uploading").checked)
                            $('.avatar').attr('disabled', false);
                        else
                            $('.avatar').attr('disabled', true);

                    }

</script>
<?php
get_footer();
?>