<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: updatescript_step1.php
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
    .input-append input  { 
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
                     <?=L::forms_updating_script;?> 
                    </li>
                    <li>
                      <?=L::forms_connection_info;?> 
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /Navigation Menu --> 
        <div class="row-fluid">
            <div>
                <h3 class="heading"><?=L::forms_connection_info;?></h3> 
                <form class="form_validation_reg" id="myform" name="myform" method="post" action="<?= url::itself()->fulluri() ?>"  novalidate="novalidate" enctype="multipart/form-data" onsubmit="return false">
                    <dl class="dl-horizontal">

                        <div class="alert alert-info">
                            <i class='icon-wrench'></i>
                            <?= L::alert_ftp_info; ?> 
                        </div> 
                        <?= alert('updatescriipt') ?>
                        <dt><label><?=L::forms_ftp_addr;?></label></dt>
                        <dd>
                            <div>
                                <?php
                                $host = setting::get_data('ftp_host','val');
                                ?> 
                                <input type="text"   name="ftp[ftp_host]" id="ftp_host" style="direction: ltr"  class="input-large" value="<?= !empty($host) ? $host : 'ftp.' . lib::get_domain(HOST_NAME) ?>" /> 
                                <em></em>
                            </div>
                        </dd>  
                        <dt><label><?=L::forms_script_path;?></label></dt>
                        <dd>
                            <div>
                                <?php
                                $path = setting::get_data('ftp_script_path','val');
                                ?> 
                                <input type="text"   name="ftp[ftp_script_path]" id="ftp_script_path" style="direction: ltr"  class="input-large" value="<?= isset($path) ? $path : '/public_html' ?>"  /> 
                                <em></em>
                            </div>
                        </dd>  
                        <dt><label><?=L::forms_ftp_username;?></label></dt>
                        <dd>
                            <div>
                                <?php
                                $user = setting::get_data('ftp_username','val');
                                ?> 
                                <input type="text"   name="ftp[ftp_username]" id="ftp_username" style="direction: ltr"  class="input-large" value="<?= $user ?>" /> 
                                <em></em>
                            </div>
                        </dd>  
                        <dt><label><?=L::forms_ftp_pwd;?></label></dt>
                        <dd>
                            <div>
                                <?php
                                $pass = setting::get_data('ftp_password','val'); 
                                ?> 
                                <input type="text"   name="ftp[ftp_password]" id="ftp_password" style="direction: ltr"  class="input-large" value="<?= $pass  ?>" />    
                                <em></em>
                            </div>
                        </dd>   
                        <dt></dt>
                        <dd> 
                            <div class="controls">
                                <a class="btn btn-abs" onclick="$('#myform').submit()"><?=L::forms_proceed;?></a>
                            </div>
                        </dd> 
                        <div class="formSep"></div>
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

                    $(function() {
                    });

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
                            //checkbox 
                            $('input:checkbox').each(function() {
                                if ($(this).is(':checked'))
                                    eval("$.extend(data || {}, {'" + $(this).attr('name') + "':'" + $(this).val() + "'});");
                                else
                                    eval("$.extend(data || {}, {'" + $(this).attr('name') + "':''});");
                            });

                            // encode and slashes
                            // $.each(data, function (k, v) {
                            //   data[k] = base64.encode(v);
                            // });

                            $.ajax({
                                type: 'POST',
                                data:{'encodedData':encodePostData(data)},
                                url: "<?= url::itself()->url_nonqry(array('save' => 1)) ?>",
                                success: function(result) {
                                    $('#myform').hideLoading();
                                    obj = JSON.parse(result);
                                    if (obj.save_code === 1) {
                                        $.sticky(obj.save_txt, {autoclose: 5000, position: "top-right", type: "st-success", speed: "fast"});
                                        setTimeout(function() {
<?= ref(url::itself()->fulluri(array('step' => 2)))->locate(); ?>
                                        }, 1000);
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