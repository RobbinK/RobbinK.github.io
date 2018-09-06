<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: linkexchange.php
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
                        Manage LinkExchange Setting
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /Navigation Menu -->
        <div class="row-fluid">
            <div>
                <h3 class="heading">LinkExchange Setting </h3> 
                <form class="form_validation_reg" id="myform" name="myform" method="post" action="<?= url::itself()->fulluri() ?>"  novalidate="novalidate" enctype="multipart/form-data" onsubmit="return false">
                    <dl class="dl-horizontal">
                        <dt></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('exchange_active');
                            $comment = getcomment($data);
                            ?>
                            <label class="uni-checkbox">
                                <?= $comment ?>
                                <input type="checkbox" name="link_exchange[exchange_active]" <?= @convert::to_bool($data['val']) ? 'checked="true"' : null ?> value="1"/>  Link Exchange Active
                                <?php if ($comment) echo '</a>'; ?> 
                            </label>
                        </dd> 
                        <div class="formSep"></div> 

                        <dt><label>Website Title</label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('exchange_website_title');
                            echo $comment = getcomment($data);
                            ?>
                            <input type="text" name="link_exchange[exchange_website_title]"  value="<?= @$data['val'] ?>"  />
                            <?php if ($comment) echo '</a>'; ?> 
                        </dd>
                        <dt><label>Website URL</label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('exchange_website_url');
                            $comment = getcomment($data);
                            ?>
                            <div>
                                <?= $comment ?>
                                <textarea  name="link_exchange[exchange_website_url]" class="auto_expand" class="span6" rows="2" cols="1"><?= @$data['val'] ?></textarea>
                                <?php if ($comment) echo '</a>'; ?> 
                                <em></em>
                            </div>
                        </dd>
                        <dt><label>Website Description</label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('exchange_website_description');
                            echo $comment = getcomment($data);
                            ?>
                            <textarea name="link_exchange[exchange_website_description]" class="auto_expand" class="span6" rows="2" cols="1" ><?= @$data['val'] ?></textarea>
                            <?php if ($comment) echo '</a>'; ?> 
                        </dd>
                        <div class="formSep"></div>
                        <dt><label>Add New Links To</label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('exchange_add_links_to');
                            echo $comment = getcomment($data);
                            $Add_Links_To = @$data['val'];
                            ?>
                            <select  name="link_exchange[exchange_add_links_to" id="Add_Links_To">
                                <option value="bottom">Bottom of list</option>
                                <option value="top">Top of list</option> 
                            </select>
                            <?php if ($comment) echo '</a>'; ?> 
                        </dd>
                        <dt><label>Show Links Per Page</label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('exchange_links_per_page');
                            $comment = getcomment($data);
                            ?>
                            <div>
                                <?= $comment ?>
                                <input type="text" name="link_exchange[exchange_links_per_page]"  value="<?= @$data['val'] ?>"   style="width:60px"/>
                                <?php if ($comment) echo '</a>'; ?> 
                                <em></em>
                            </div>
                        </dd>
                        <div class="formSep"></div>
                        <dt><label>Minimum PR To Accept Website</label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('exchange_minimum_pr');
                            $comment = getcomment($data);
                            ?>
                            <div  style="margin-left: 0">
                                <?= $comment ?>
                                <span  style="margin-left: 0">
                                    <input id="Minimum_PR" class="hb-spinner-box input-mini" type="text" name="link_exchange[exchange_minimum_pr]" autocomplete="off"  value="<?= $data['val']; ?>"/>
                                </span>
                                <?php if ($comment) echo '</a>'; ?> 
                                <em></em>
                            </div> 
                        </dd> 
                        <dt><label>Manually Approve Links</label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('exchange_manually_approve_links');
                            echo $comment = getcomment($data);
                            $Manually_Approve_Links = @$data['val'];
                            ?>
                            <select  name="link_exchange[exchange_manually_approve_links]" id="Manually_Approve_Links">
                                <option value="1">Yes</option>
                                <option value="0">No</option> 
                            </select>
                            <?php if ($comment) echo '</a>'; ?> 
                        </dd>
                        <dt><label>Who Can Link Exchange</label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('exchange_who_link');
                            echo $comment = getcomment($data);
                            $Who_Link_Exchange = @$data['val'];
                            ?>
                            <select  name="link_exchange[exchange_who_link]" id="Who_Link_Exchange">
                                <option value="Members_Only">Members Only</option>
                                <option value="All_Visitors"> All Visitors</option> 
                            </select>
                            <?php if ($comment) echo '</a>'; ?> 
                        </dd>
                        <div class="formSep"></div>
                        <dt><label>Checking Links Time</label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('exchange_checking_links_time');
                            echo $comment = getcomment($data);
                            $Checking_Links_Time = @$data['val'];
                            ?>
                            <select  name="link_exchange[exchange_checking_links_time]" id="Checking_Links_Time">
                                <option value="Manually">Manually</option>
                                <option value="Weekly">Weekly</option> 
                                <option value="Monthly">Monthly</option> 
                            </select>
                            <?php if ($comment) echo '</a>'; ?> 
                        </dd>
                        <dt><label>Check And Block Nofollow Links</label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('exchange_check_nofollow_links');
                            echo $comment = getcomment($data);
                            $Check_Nofollow_Links = @$data['val'];
                            ?>
                            <select  name="link_exchange[exchange_check_nofollow_links]" id="Check_Nofollow_Links">
                                <option value="1">Yes</option>
                                <option value="0">No</option> 
                            </select>
                            <?php if ($comment) echo '</a>'; ?> 
                        </dd>
                        <dt><label>Check And Block Noindex, Nofollow Pages</label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('exchange_check_noindex_pages');
                            echo $comment = getcomment($data);
                            $Check_Noindex_pages = @$data['val'];
                            ?>
                            <select  name="link_exchange[exchange_check_noindex_pages]" id="Check_Noindex_pages">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
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
                    var fValidation;
                    var loading_config = {
                        'indicatorZIndex': 990,
                        'overlayZIndex': 990
                    };
                    // Validation Options
                    fValidation = $("#myform").validate({
                        rules: {
                            'link_exchange[exchange_links_per_page]': {
                                number: true
                            },
                            'link_exchange[exchange_minimum_pr]': {
                                number: true
                            },
                            'link_exchange[exchange_website_url]': {
                                url: true
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
                    selectOptionByValue('Add_Links_To', '<?= @$Add_Links_To ?>');
                    selectOptionByValue('Manually_Approve_Links', '<?= @$Manually_Approve_Links ?>');
                    selectOptionByValue('Who_Link_Exchange', '<?= @$Who_Link_Exchange ?>');
                    selectOptionByValue('Checking_Links_Time', '<?= @$Checking_Links_Time ?>');
                    selectOptionByValue('Check_Nofollow_Links', '<?= @$Check_Nofollow_Links ?>');
                    selectOptionByValue('Check_Noindex_pages', '<?= @$Check_Noindex_pages ?>');

                    $(document).ready(function() {

                        $('#Minimum_PR').spinner({
                            min: 0,
                            max: 10
                        });
                    });
</script>
<?php
get_footer();
?>