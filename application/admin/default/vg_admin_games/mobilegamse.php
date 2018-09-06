<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: mobilegamse.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:56
##########################################################
 */

### call header
abs_admin_inc(l_basic);
abs_admin_inc(l_colorbox);
abs_admin_inc(l_datepicker);
abs_admin_inc(l_validate);
abs_admin_inc(l_datatable);
abs_admin_inc(l_sticky);
abs_admin_inc(l_smoke);
abs_admin_inc(l_unserializeForm);
abs_admin_inc(l_multiselect);
abs_admin_inc_js(template_path() . '/lib/simple_ajax_uploader/SimpleAjaxUploader.min.js');
get_header();
#**************
?>
<!-- main content -->
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
                        <?= L::sidebar_games_mng; ?>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /Navigation Menu -->

        <!-- Add Game --> 
        <div id="form_div" class="tab-content" style="visibility:visible ">
            <h3 class="heading" id="div_title"></h3>

            <form id="myform" method="post"  class="form_validation_reg" novalidate="novalidate">
                <input type="hidden" name="gid" id="gid" class="edit_id"  />
                <dl class="dl-horizontal">

                    <dt><label> <?= L::forms_game_name; ?> </label></dt>
                    <dd><div><input type="text" name="game_name" id="game_name"  required></div></dd>

                    <dt><label> <?= L::forms_game_categories; ?></label></dt>
                    <dd><div>
                            <select name="game_categories" id="game_categories"  style="width:300px" class="hidden"  multiple="multiple">
                                <?php
                                if (isset($categoriesaout))
                                    while (current($categoriesaout)) : extract(current($categoriesaout));
                                        echo "<option value={$cid}>{$title}</option>";
                                        next($categoriesaout);
                                    endwhile;
                                ?>
                            </select>                                    
                        </div></dd>

                    <dt><label> <?= L::forms_game_description; ?> </label></dt>
                    <dd><div><textarea name="game_description" id="game_description" class="input-xxlarge auto_expand"></textarea></div></dd>

                    <div class="formSep"></div>

                    <!-- <Upload Game Image> --> 
                    <dt><label><?= L::forms_image_source; ?></label></dt>
                    <dd><div>
                            <select name="game_image_source" id="game_image_source" style="width: 200px">
                                <option value="0"><?= L::forms_upload_game_image; ?></option>
                                <option value="1"><?= L::forms_grab_remote_image; ?></option> 
                            </select>
                        </div></dd>

                    <dt><label><?= L::forms_game_thumb; ?></label></dt>
                    <dd id="game_img_wrapper">
                        <input type="hidden" name="game_img" id="game_img"   />
                        <div class="manual">
                            <input type="button" id="upload_game_img" class="btn btn-large clearfix" value="<?= addslashes(L::forms_select_file); ?>"/>
                            <span class="help-inline"><i>PNG, JPG, GIF (<?= L::forms_max_file_size; ?> : 200 <?= L::global_kilo_byte; ?>)</i></span>
                        </div>

                        <div class="grab">
                            <div class="input-append" style="margin-bottom: 0px;">
                                <input type="url" name="grab_game_img" id="grab_game_img"  data-default="http://">
                                <div class="btn-group">
                                    <a class="btn"  onclick="grabbing_game_img();
                                            return false;"><?= L::forms_grab; ?></a>
                                    <button class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="javascript:void(0);" onclick="$('#grab_game_img').val($('#grab_game_img').data('default') || '');
                                                return false"><i class="icon-ban-circle"></i> <?= L::forms_clean; ?></a></li> 
                                    </ul>
                                </div>
                            </div>
                            <em></em>
                            <span class="help-inline"></span>
                        </div>

                        <div id="game_img_attachment" style="margin-bottom:15px;">
                            <div id="errormsg-game_img" class="clearfix uploaderror label label-important "></div>	              
                            <div id="pic-progress-wrap-game_img" class="progress-wrap" style="margin-bottom:10px;"></div>	
                            <div id="filebox-game_img" class="clear" style="position: relative;padding-top:0px;"></div>
                        </div>
                    </dd>

                    <dt><label><?= L::forms_featured_image; ?></label></dt>
                    <dd id="featured_img_wraper">
                        <input type="hidden" name="featured_img" id="featured_img"   />
                        <div class="manual">
                            <input type="button" id="upload_featured_img" class="btn btn-large clearfix" value="<?= addslashes(L::forms_select_file); ?>"/>
                            <span class="help-inline"><i>PNG, JPG, GIF (<?= L::forms_max_file_size; ?> : 200 <?= L::global_kilo_byte; ?>)</i></span>
                        </div>

                        <div class="grab"> 
                            <div class="input-append" style="margin-bottom: 0px;">
                                <input type="url" name="grab_featured_img" id="grab_featured_img"  data-default="http://">
                                <div class="btn-group">
                                    <a class="btn"  onclick="grabbing_featured_img();
                                            return false;"><?= L::forms_grab; ?></a>
                                    <button class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="javascript:void(0);" onclick="$('#grab_featured_img').val($('#grab_featured_img').data('default') || '');
                                                return false"><i class="icon-ban-circle"></i> <?= L::forms_clean; ?></a></li> 
                                    </ul>
                                </div>
                            </div>
                            <em></em>
                            <span class="help-inline"></span> 
                        </div> 

                        <div id="featured_img_attachment"  style="margin-bottom:15px;">
                            <div id="errormsg-featured_img" class="clearfix uploaderror label label-important "></div>	              
                            <div id="pic-progress-wrap-featured_img" class="progress-wrap" style="margin-bottom:10px;"></div>	
                            <div id="filebox-featured_img" class="clear" style="position: relative;padding-top:0px;"></div>
                        </div>
                    </dd> 
                    <!-- </Upload Game Image> --> 

                    <div class="formSep"></div>


                    <!-- <Upload File> --> 


                    <dt><label><?= L::forms_android_install_link; ?></label></dt>
                    <dd>
                        <div class="input-prepend">
                            <span class="add-on"><img src="img/android.png" style="height: 20px"/></span>
                            <input type="url" name="game_android_link" id="game_android_link"  data-default="http://" class="input-xxlarge">
                        </div> 
                    </dd>


                    <dt><label><?= L::forms_ios_install_link; ?> </label></dt>
                    <dd>
                        <div class="input-prepend">
                            <span class="add-on" style="width:20px"><img src="img/apple.png" style="height: 20px"/></span>
                            <input type="url" name="game_ios_link" id="game_ios_link"  data-default="http://"  class="input-xxlarge"/> 
                        </div>
                    </dd> 

                    <div class="formSep"></div>

                    <dt><label><?= L::forms_html5_link; ?> </label></dt>
                    <dd>
                        <div class="input-prepend">
                            <span class="add-on" style="width:20px"><img src="img/html5.png" style="height: 20px"/></span>
                            <input type="url" name="game_html5_link" id="game_html5_link"  data-default="http://"  class="input-xxlarge"/> 
                        </div>
                    </dd> 

                    <div class="formSep"></div> 

                    <dt><label> <?= L::global_featured; ?> </label></dt>
                    <dd><div>
                            <select name="game_is_featured"  id="game_is_featured" class="input-mini">
                                <option value="0"><?= L::global_state_no; ?></option>
                                <option value="1"><?= L::global_state_yes; ?></option>
                            </select>
                        </div></dd>

                    <dt><label> <?= L::global_status; ?></label></dt>
                    <dd><div>
                            <select name="game_is_active" id="game_is_active" class="input-medium">
                                <option value="1"><?= L::global_enable; ?></option>
                                <option value="0"><?= L::forms_move_to_queue_list; ?></option>
                                <option value="-1"><?= L::global_disable; ?></option>
                            </select>
                        </div></dd>
                    <dt></dt>
                    <dd>
                        <div>
                            <input class="btn btn-success" type="submit" value="<?= addslashes(L::global_save); ?>" style="width: 120px"/>
                            &nbsp;&nbsp;
                            <input class="btn" type="button" name="reset" value="<?= addslashes(L::forms_add_new_game); ?>" onclick="reset_form();
                                    trigger_selectuploaders();
                                    return false;"/>
                            &nbsp;&nbsp;
                            <input class="btn" type="button" name="close" value="<?= addslashes(L::global_cancel); ?>" onclick="close_from();
                                    return false;" style="width: 80px"/>
                        </div>
                    </dd>

                </dl>

            </form>
            <div class="formSep"></div>
        </div>
        <!-- /Add Game -->

        <!-- Game List -->
        <h3 class="heading"><?= L::sidebar_mob_games; ?>
            <button class="pull-right btn btn-info  bt_add_new"   onclick=""><?= L::forms_add_new_game; ?></button>
            <button class="pull-right btn btn-info  bt_cancel" style='display: none'   onclick=""><?= L::global_cancel; ?></button>
        </h3>
        <table id="dt_e" class="table table-striped table-bordered dTableR" >
            <thead>
                <tr> 
                    <th>id</th> 
                    <th>game_rating</th>
                    <th>game_is_featured</th>
                    <th>featured_img</th>
                    <th><?= L::global_image; ?></th>
                    <th><?= L::forms_game_name; ?></th>
                    <th><?= L::forms_categories; ?></th>  
                    <th><?= L::forms_played_today; ?></th>  
                    <th><?= L::forms_total_plays; ?></th>  
                    <th><?= L::forms_last_played; ?></th>  
                    <th><?= L::global_type; ?></th>  
                    <th><?= L::global_status; ?></th>
                    <th><?= L::global_action; ?></th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td class="dataTables_empty" colspan="7"><?= L::forms_loading_data; ?></td>
                </tr>
            </tbody>
        </table>
        <!-- /Game List -->


    </div>
</div>




<?php
get_sidebar();
get_footer('_script');
?>

<style>

    table.table tr.even.row_selected td {
        background-color: #DAEAF8;
    }

    table.table tr.odd.row_selected td {
        background-color: #E3F0FF;
    }
</style>

<script type="text/javascript">
    var debug = false;
    var fValidation;
    var oTable;
    var loading_config = {
        'indicatorZIndex': 990,
        'overlayZIndex': 990
    };

    $(document).ready(function () {

        reg_xhr_setup();
        reg_uploaders_game_img();
        reg_uploaders_featured_img();
        $('#form_div').fadeOut(0);
        $("select[multiple='multiple']").multipleSelect({selectAllText: '<?= addslashes(L::global_select_all); ?>'});
        // Image Source
        $('#game_image_source').on('change', function () {
            if (debug)
                console.log('trigger:  game_image_source (val:' + this.value + ')\n');

            $('#game_img_wrapper').find('.manual,.grab').each(function () {
                $(this).fadeOut(300);
            });
            $('#featured_img_wraper').find('.manual,.grab').each(function () {
                $(this).fadeOut(300);
            });
            if ($(this).val() == 1)
                $('#game_img_wrapper .grab,#featured_img_wraper .grab').delay(300).fadeIn(300);
            else
                $('#game_img_wrapper .manual,#featured_img_wraper .manual').delay(300).fadeIn(300);

        });

        trigger_selectuploaders();
        oTable = $('#dt_e').dataTable({
            bInfo: true,
            bLengthChange: true,
            sPaginationType: "bootstrap_full", /*full_numbers , two_button */
            iDisplayLength: <?=datatable_ipp?>,
            aLengthMenu: [[10, 20, 50, -1], ['10', '20', '50', 'All']],
            bPaginate: true,
            bFilter: true,
            bSort: true,
            bProcessing: true,
            bServerSide: true,
            sAjaxSource: "<?= url::itself()->url_nonqry(array('dt' => 1)) ?>",
            aaSorting: [[0, 'desc']],
            aoColumnDefs: [
                {bVisible: false, aTargets: [0]},
                {bVisible: false, aTargets: [1]},
                {bVisible: false, aTargets: [2]},
                {bVisible: false, aTargets: [3]},
                {aTargets: [4], sWidth: '60px'},
                {aTargets: [5]},
                {aTargets: [6]},
                {aTargets: [7], sWidth: '80px'},
                {aTargets: [8], sWidth: '80px'},
                {aTargets: [9], sWidth: '90px'},
                {aTargets: [10], sWidth: '40px', sClass: 'center'},
                {aTargets: [11], sWidth: '40px'},
                {bSortable: false, aTargets: [12], sWidth: '50px'}
            ],
            sDom: 'f<"toolbar">rtip',
            oLanguage: dataTablesLanguages,
            fnDrawCallback: function () {
                $('#dt_e tbody td a').click(function (e) {
                    if ($(this).attr('href') != '#' && $(this).attr('href') != '')
                        window.open(this.href, $(this).attr('target') || '_self');
                    e.preventDefault();
                    return false;
                });
                $('#dt_e tbody td:last-child').click(function (e) {
                    e.preventDefault();
                    return false;
                });
                dt_selection_stats();
                reg_dt_delete();
                reg_dt_edit();
                reg_dt_row_click();
                reg_colorbox('auto');
            }
        });
        $("div.toolbar").html('<div class="sepH_a" id="toolbar_inside">\n\
<button class="btn btn-mini sepV_a sall"><li class="icon-th-list"></li> <?= L::global_select_all; ?></button>\n\
<button class="btn btn-mini sepV_a dall" style="display:none"><li class="icon-ban-circle"></li> <?= L::global_deselect_all; ?></button>\n\
<button class="btn btn-mini sepV_a btn-danger mdel" style="display:none"><li class="icon-trash"></li> <?= L::global_delete_selected; ?></button>\n\
</div>');
        reg_select_all();
        reg_deselect_all();
        reg_multidelete();
<?php
if (isset($_GET['new']))
    echo "$('.bt_add_new').trigger('click');";
?>
    });
    function reg_xhr_setup() {
        $.xhrPool = [];
        $.xhrPool.abortAll = function () {
            $(this).each(function (idx, jqXHR) {
                jqXHR.abort();
            });
            $.xhrPool.length = 0
        };
        $.ajaxSetup({
            beforeSend: function (jqXHR) {
                $.xhrPool.push(jqXHR);
            },
            complete: function (jqXHR) {
                var index = $.inArray(jqXHR, $.xhrPool);
                if (index > -1) {
                    $.xhrPool.splice(index, 1);
                }
            }
        });

        $.ajaxSetup({
            error: function(x, e) {
                if (x.status == 500) {
                    alert('Internel Server Error.');
                    abortAllAjax();
                }
            }
        });
    }

    function abortAllAjax() {
        $.xhrPool.abortAll();
        $('.loading-indicator-overlay,.loading-indicator').remove();
        $('.sticky-queue').remove();
        $('.shoimageloading').remove();
        $('.dataTables_processing').css({visibility: 'hidden'});
    }

    function is_edit_st() {
        if (parseInt($('.form_validation_reg .edit_id').val()) > 0)
            return true;
        return false;
    }

    function reset_form() {
        $('.form_validation_reg').find('input:text, input[type=url], input[type=hidden], input:password, input:file, select, textarea').val('');
        $('.form_validation_reg').find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
        //tinymce
        if (typeof (tinyMCE) != 'undefined') {
            $('textarea.tinymce').each(function () {
                tinyMCE.get($(this).attr('id')).setContent('');
                tinyMCE.DOM.setStyle(tinyMCE.DOM.get($(this).attr('id') + '_ifr'), 'height', 120 + 'px');
            });
        }
        //select
        $('.form_validation_reg').find('select').each(function () {
            $(this).find('option:first').attr('selected', 'true');
            $(this).trigger('change');
        });
        //default
        $('.form_validation_reg').find('input').each(function () {
            if ($(this).attr('data-default')) {
                $(this).val($(this).data('default'));
            }
        });

        //date
        $('.form_validation_reg').find('input').each(function () {
            if ($(this).closest('div').attr('data-date-format')) {
                if (!$("#" + $(this).attr('id') + "[data-default]").length) {
                    format = ($(this).closest('div').data('date-format')).toLowerCase().replace('yyyy', 'yy');
                    t = new Date();
                    newd = $.datepicker.formatDate(format, t);
                    $(this).val(newd);
                } else {
                    $(this).val($(this).data('default'));
                }
            }
        });

        if (typeof $.fn.multipleSelect != 'undefined') {
            $("select[multiple='multiple']").multipleSelect("uncheckAll");
        }

        //filebox
        close_uploader_box('#filebox-game_img');
        close_uploader_box('#filebox-featured_img');
        $('#upload_game_img').val('<?= addslashes(L::forms_select_file); ?>');
        $('#upload_featured_img').val('<?= addslashes(L::forms_select_file); ?>');
        $('.uploaderror').html('');
        fValidation.resetForm();
        $('.form_validation_reg div').removeClass("f_error");
        $('#div_title').html('<?= addslashes(L::forms_add_new_game); ?>');

        $('.auto_expand').each(function () {
            $(this).css({'height': $(this).data('default-height'), 'min-height': $(this).data('default-height')});
        });
    }



    $('.bt_add_new').click(function () {
        reset_form();
        open_form();
        trigger_selectuploaders();

    });

    $('.bt_cancel').click(function () {
        close_from();
    });
    function trigger_selectuploaders() {
        $('#game_image_source').trigger('change');
    }
    function open_form() {
        $('.bt_add_new').hide();
        $('.bt_cancel').show();
        $(window).scrollTop(0);
        $('#form_div').slideDown(200);
    }

    function close_from() {
        abortAllAjax();
        $('.bt_add_new').show();
        $('.bt_cancel').hide();
        reset_form();
        $('#form_div').slideUp(200);
    }
    // Validation Options
    window.callbackjob = 0;

    jQuery.validator.addMethod("url", function (value, element) {
        return this.optional(element) || /^(https?:\/\/)?((localhost|[a-z0-9\-]+(\.[a-z0-9\-]+)+)(:[0-9]+)?(\/.*)?)?$/.test(value);
    }, "<?= addslashes(L::alert_invalid_link); ?>");

    fValidation = $("#myform").validate({
        debug: false,
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
            window.submitmyform = function () {
                if (window.callbackjob > 0)
                    return false;
                $('#myform').showLoading(loading_config);
                data = $.deparam($('#myform').serialize());
                //tinymce
                if (typeof (tinyMCE) != 'undefined') {
                    $('textarea.tinymce').each(function () {
                        $tinyval = tinyMCE.get($(this).attr('id')).getContent();
                        eval("$.extend(data || {}, {" + $(this).attr('name') + ":$tinyval});");
                    });
                }

                if (typeof $.fn.multipleSelect != 'undefined') {
                    $("select[multiple='multiple']").each(function () {
                        $multis = $(this).multipleSelect("getSelects");
                        eval("$.extend(data || {}, {" + $(this).attr('name') + ":$multis});");
                    });
                }


                // encode and slashes
                // $.each(data, function (k, v) {
                //   data[k] = base64.encode(v);
                // });

                $.ajax({
                    type: 'POST',
                    data:{'encodedData':encodePostData(data)},
                    url: "<?= url::itself()->url_nonqry(array('save' => 1)) ?>",
                    success: function (result) {
                        $('#myform').hideLoading();
                        obj = JSON.parse(result);
                        if (obj.save_code === 1) {
                            $.sticky(obj.save_txt, {autoclose: 5000, position: "top-right", type: "st-success", speed: "fast"});
                            if (!is_edit_st()) {
                                oTable.fnReloadAjax();
                                reset_form();
                            }
                            else {
                                oTable.fnStandingRedraw();
                                open_uploader_imagebox('#filebox-game_img', 'game_img', obj.game_img);
                                open_uploader_imagebox('#filebox-featured_img', 'featured_img', obj.featured_img);

                                $('#game_img').val(obj.game_img);
                                $('#featured_img').val(obj.featured_img);
                            }
                            return true;
                        }
                        else {
                            $.sticky("<?= addslashes(L::global_error); ?>! " + obj.save_txt, {autoclose: 5000, position: "top-right", type: "st-error", speed: "fast"});
                            return false;
                        }
                    }
                });
            };
            if ($('#game_image_source').val() == 1) {
                grabbing_game_img('window.submitmyform');
                grabbing_featured_img('window.submitmyform');
            }
            window.submitmyform();
        }
    });
    function reg_select_all() {
        $('.toolbar .sall').click(function () {
            $('table.table tbody tr').addClass('row_selected');
            dt_selection_stats();
        });
    }

    function reg_deselect_all() {
        $('.toolbar .dall').click(function () {
            $('table.table tbody tr').removeClass('row_selected');
            dt_selection_stats();
        });
    }

    function dt_selection_stats() {
        if ($('#dt_e .row_selected').length) {
            $('.toolbar .mdel').fadeIn(300);
            $('.toolbar .dall').fadeIn(300);
        } else {
            $('.toolbar .mdel').fadeOut(300);
            $('.toolbar .dall').fadeOut(300);
        }
    }

    function reg_multidelete() {
        $('.toolbar .mdel').click(function () {
            var ids = [];
            $('#dt_e .row_selected').each(function () {
                id = $(this).find('input.row_id').val();
                ids.push(id);
            });
            smoke.confirm('<?= addslashes(L::alert_del_warning); ?>', function (e) {
                if (e) {
                    st1 = $.sticky('<?= addslashes(L::alert_deleting_records); ?>', {autoclose: false, position: "top-right", type: "st-info", speed: "fast"});
                    $.ajax({
                        type: 'POST',
                        data: {id: ids},
                        url: "<?= url::itself()->url_nonqry(array('mdel' => 1)) ?>",
                        success: function (result) {
                            $.stickyhide(st1.id);
                            $.sticky(result, {autoclose: 5000, position: "top-right", type: "st-success", speed: "fast"});
                            oTable.fnStandingRedraw();
                        }
                    });
                }
            }, {});
        });
    }

    function reg_dt_row_click() {
        $('#dt_e tbody tr').click(function () {
            $(this).toggleClass('row_selected');
            dt_selection_stats();
        });
    }

    function  reg_dt_delete() {
        $('.del').click(function () {
            var did = $(this).closest('td').find('.row_id').val();
            smoke.confirm('<?= addslashes(L::alert_del_warning); ?>', function (e) {
                if (e) {
                    st1 = $.sticky('<?= addslashes(L::alert_deleting_records); ?>', {autoclose: false, position: "top-right", type: "st-info", speed: "fast"});
                    $.ajax({
                        type: 'POST',
                        data: {id: did},
                        url: "<?= url::itself()->url_nonqry(array('del' => 1)) ?>",
                        success: function (result) {
                            $.stickyhide(st1.id);
                            $.sticky(result, {autoclose: 5000, position: "top-right", type: "st-success", speed: "fast"});
                            oTable.fnStandingRedraw();
                        }
                    });
                }
            }, {});
        });
    }

    function reg_dt_edit() {
        $('.edit').click(function () {
            var eid = $(this).closest('td').find('.row_id').val();
            reset_form();
            $('#div_title').html('<?= addslashes(L::forms_edit_game); ?>');
            open_form();
            $('#myform').showLoading(loading_config);
            $.ajax({
                type: 'POST',
                data: {'id': eid},
                url: "<?= url::itself()->url_nonqry(array('edit' => 1)) ?>",
                success: function (result) {
                    $('#myform').hideLoading();
                    data = JSON.parse(result);
                    $('#myform').unserializeForm($.param(data));
                    if (typeof (tinyMCE) != 'undefined') {
                        $.each(data, function (k, v) {
                            if ($('textarea.tinymce[name=' + k + ']').length) {
                                id = $('textarea.tinymce[name=' + k + ']').attr('id');
                                tinyMCE.get(id).setContent(v);
                            }
                        });
                    }
                    if (typeof $.fn.multipleSelect != 'undefined') {
                        $("select[multiple='multiple']").each(function () {
                            if (data[$(this).attr('name')].length > 0)
                                $(this).multipleSelect("setSelects", data[$(this).attr('name')]);
                        });
                    }
                    trigger_selectuploaders();
                    setTimeout(function () {
                        if (typeof (data.game_img) != 'undefined' && data.game_img != '') {
                            $('#game_img').val(data.game_img);
                            open_uploader_imagebox('#filebox-game_img', 'game_img', data.game_img);
                        }

                        if (typeof (data.featured_img) != 'undefined' && data.featured_img != '') {
                            $('#featured_img').val(data.featured_img);
                            open_uploader_imagebox('#filebox-featured_img', 'featured_img', data.featured_img);
                        }
                    }, 600);
                }
            });
        });
    }


    function reg_uploaders_game_img() {

        var btn = document.getElementById('upload_game_img'),
                wrap = document.getElementById('pic-progress-wrap-game_img'),
                filebox = document.getElementById('filebox-game_img'),
                errBox = document.getElementById('errormsg-game_img');
        var uploader = new ss.SimpleUpload({
            button: btn,
            url: '<?= url::itself()->url_nonqry() ?>',
            progressUrl: '<?= url::itself()->url_nonqry() ?>',
            name: 'up_game_img',
            multiple: false,
            maxUploads: 2,
            maxSize: 200 * 1024,
            allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
            accept: 'image/*',
            debug: false,
            hoverClass: 'btn-hover',
            focusClass: 'active',
            disabledClass: 'disabled',
            responseType: 'json',
            onChange: function () {
                this.setData({
                    gid: $('#myform .edit_id').val(),
                    gamename: encodeURIComponent($('#myform #game_name').val())
                });
            },
            onExtError: function (filename, extension) {
                alert('<?= addslashes(L::alert_invalid_image_format); ?>');
            },
            onSizeError: function (filename, fileSize) {
                alert('<?= addslashes(L::alert_invalid_file_size); ?>. (<?= L::forms_max_file_size; ?> : 200 <?= L::global_kilo_byte ?>)');
            },
            onSubmit: function (filename, ext) {
                var prog = document.createElement('div'),
                        outer = document.createElement('div'),
                        bar = document.createElement('div'),
                        size = document.createElement('div'),
                        self = this;
                prog.className = 'prog';
                size.className = 'size';
                outer.className = 'progress progress-info input-medium';
                bar.className = 'bar';
                outer.appendChild(bar);
                prog.innerHTML = '<span style="vertical-align:middle;">' + strip_tags(filename) + ' - </span>';
                prog.appendChild(size);
                prog.appendChild(outer);
                wrap.appendChild(prog); // 'wrap' is an element on the page

                self.setProgressBar(bar);
                self.setProgressContainer(prog);
                self.setFileSizeBox(size);
                errBox.innerHTML = '';
                btn.value = '<?= addslashes(L::forms_select_another_file); ?>';
            },
            onComplete: function (file, response) {
                if (response.success === true) {
                    open_uploader_imagebox('#filebox-game_img', 'game_img', response.file);
                    $('#game_img').val(response.file);
                } else {
                    errBox.innerHTML = response.msg;
                    $('#game_img').val('');
                }
            }
        });
    }

    function reg_uploaders_featured_img() {

        var btn = document.getElementById('upload_featured_img'),
                wrap = document.getElementById('pic-progress-wrap-featured_img'),
                filebox = document.getElementById('filebox-featured_img'),
                errBox = document.getElementById('errormsg-featured_img');
        var uploader = new ss.SimpleUpload({
            button: btn,
            url: '<?= url::itself()->url_nonqry() ?>',
            progressUrl: '<?= url::itself()->url_nonqry() ?>',
            name: 'up_featured_img',
            multiple: false,
            maxUploads: 2,
            maxSize: 200 * 1024,
            allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
            accept: 'image/*',
            debug: false,
            hoverClass: 'btn-hover',
            focusClass: 'active',
            disabledClass: 'disabled',
            responseType: 'json',
            onChange: function () {
                this.setData({
                    gid: $('#myform .edit_id').val(),
                    gamename: encodeURIComponent($('#myform #game_name').val())
                });
            },
            onExtError: function (filename, extension) {
                alert('<?= addslashes(L::alert_invalid_image_format); ?>');
            },
            onSizeError: function (filename, fileSize) {
                alert('<?= addslashes(L::alert_invalid_file_size); ?>. (<?= L::forms_max_file_size; ?> : 200 <?= L::global_kilo_byte ?>)');
            },
            onSubmit: function (filename, ext) {
                var prog = document.createElement('div'),
                        outer = document.createElement('div'),
                        bar = document.createElement('div'),
                        size = document.createElement('div'),
                        self = this;
                prog.className = 'prog';
                size.className = 'size';
                outer.className = 'progress progress-info input-medium';
                bar.className = 'bar';
                outer.appendChild(bar);
                prog.innerHTML = '<span style="vertical-align:middle;">' + strip_tags(filename) + ' - </span>';
                prog.appendChild(size);
                prog.appendChild(outer);
                wrap.appendChild(prog); // 'wrap' is an element on the page

                self.setProgressBar(bar);
                self.setProgressContainer(prog);
                self.setFileSizeBox(size);
                errBox.innerHTML = '';
                btn.value = '<?= addslashes(L::forms_select_another_file); ?>';
            },
            onComplete: function (file, response) {
                if (response.success === true) {
                    open_uploader_imagebox('#filebox-featured_img', 'featured_img', response.file);
                    $('#featured_img').val(response.file);
                } else {
                    errBox.innerHTML = response.msg;
                    $('#featured_img').val('');
                }
            }
        });
    }

    function close_uploader_box(jQid) {
        if (debug)
            console.log('close_uploader_box\n');
        if ($(jQid).hasClass('noimg')) {
            $(jQid).find('img.thumbnail').siblings().remove();
            $(jQid).find('img.thumbnail').show();
        } else if ($(jQid).length) {
            $(jQid).html('').fadeOut();
        }

    }

    function open_uploader_imagebox(jQid, dbField, filename) {
        if (debug)
            console.log('open_uploader_imagebox\n');
        if (!$(jQid).length)
            return false;
        if (filename == '' || typeof (filename) == undefined || filename == null) {
            close_uploader_box(jQid);
            return false;
        }
        if (!$(jQid).parent().find('.shoimageloading').length) {
            $(jQid).before("<img src='<?= static_url() ?>/images/loading/loading-9.gif' class='shoimageloading'/>");
        }

        $(jQid).fadeOut(300, function () {
            html = '<img src="<?= url::itself()->url_nonqry() ?>?showimage=' + encodeURIComponent(filename) + '&size=70xnull" rel="clbox" >';
            html += '<span style="top: 0px; position: absolute; margin:0 10px;">' + summarize(filename, 25, true, '') + '</span>';
            html += '<a style="position: absolute; top: 23px; margin:0 10px;cursor:pointer;" onclick="delete_file(\'' + dbField + '\',\'' + filename + '\');return false;" class="btn-danger btn-mini deleteicon"><?= L::global_remove; ?></a>';
            if ($(jQid).hasClass('noimg')) {
                $(jQid).find('img.thumbnail').hide();
                $(jQid).find('img.thumbnail').siblings().remove();
                $(jQid).append(html);
                $(jQid).find('img:not(.thumbnail)').imagesLoaded(function () {
                    reg_colorbox('auto');
                    $(jQid).fadeIn(300);
                    $(jQid).parent().find('.shoimageloading').remove();
                });
            }
            else {
                $(jQid).html(html);
                $(jQid).find('img:not(.thumbnail)').imagesLoaded(function () {
                    reg_colorbox('auto');
                    $(jQid).fadeIn(300);
                    $(jQid).parent().find('.shoimageloading').remove();
                });
            }
        });
    }


    function  delete_file(db_field, filename) {
        var did = $('#myform .edit_id').val();
        smoke.confirm('<?= addslashes(L::alert_del_file_warning); ?>', function (e) {
            if (e) {
                st1 = $.sticky('<?= addslashes(L::alert_deleting_file); ?>', {autoclose: false, position: "top-right", type: "st-info", speed: "fast"});
                $.ajax({
                    type: 'POST',
                    'data': {'id': did, 'db_field': db_field, 'filename': filename},
                    url: "<?= url::itself()->url_nonqry(array('del_file' => 1)) ?>",
                    success: function (result) {
                        $.stickyhide(st1.id);
                        obj = JSON.parse(result);
                        if (obj.delete_code === 1)
                            $.sticky(obj.delete_txt, {autoclose: 5000, position: "top-right", type: "st-success", speed: "fast"});
                        else
                            $.sticky(obj.delete_txt, {autoclose: 5000, position: "top-right", type: "st-error", speed: "fast"});
                        pid = $('#' + db_field).parent().find("div[id^='filebox']").attr('id');
                        $('#' + db_field).val('');
                        close_uploader_box('#' + pid);
                    }
                });
            }
        }, {});
    }

    function reg_colorbox(size) {
        size = size || 'auto';
        $('.dataTable img[rel=clbox]').unbind('click').click(function (e) {
            e.stopPropagation();
            $.colorbox({
                href: $(this).data('fullimage'),
                photo: true,
                maxWidth: '90%',
                maxHeight: '90%',
                opacity: '0.2',
                loop: false,
                fixed: true
            });
        });
        $('#myform img[rel=clbox]').unbind('click').click(function (e) {
            e.stopPropagation();
            $.colorbox({
                href: $(this).attr('src') + '&size=' + size,
                photo: true,
                maxWidth: '90%',
                maxHeight: '90%',
                opacity: '0.2',
                loop: false,
                fixed: true
            });

        });

    }

    function grabbing_game_img(callback) {
        var did = $('#myform .edit_id').val();
        var gamename = $('#myform #game_name').val();
        if (($('#grab_game_img').val() == $('#grab_game_img').data('default')) || ($('#grab_game_img').val() == ''))
            return false;
        if (typeof (callback) != 'undefined')
            window.callbackjob++;
        var file_addr = $('#grab_game_img').val();
        st2 = $.sticky('<?= addslashes(L::alert_grabbing_file); ?>', {autoclose: false, position: "top-right", type: "st-info", speed: "fast"});
        if (!$('#grab_game_img').closest('.grab').find('.shoimageloading').length) {
            $('#grab_game_img').closest('.grab').append("<img src='<?= static_url() ?>/images/loading/loading-9.gif' class='shoimageloading'/>");
        }
        // encode and slashes
        data = {'id': did, 'from': file_addr, 'gamename': gamename};
        $.each(data, function (k, v) {
            data[k] = encodeURIComponent(v);
        });
        $.ajax({
            type: 'POST',
            data: data,
            url: "<?= url::itself()->url_nonqry(array('act_grab_game_img' => 1)) ?>",
            success: function (result) {
                $('#grab_game_img').closest('.grab').find('.shoimageloading').remove();
                $.stickyhide(st2.id);
                obj = JSON.parse(result);
                if (obj.grab_code === 1) {
                    $.sticky(obj.grab_txt, {autoclose: 5000, position: "top-right", type: "st-success", speed: "fast"});
                    if (typeof (callback) == 'undefined') {
                        open_uploader_imagebox('#filebox-game_img', 'game_img', obj.file);
                    }
                    $('#game_img').val(obj.file);
                }
                else {
                    $.sticky(obj.grab_txt, {autoclose: 5000, position: "top-right", type: "st-error", speed: "fast"});
                    $('#filebox-game_img').html('').fadeOut();
                    $('#game_img').val('');
                }
                $('#grab_game_img').val($('#grab_game_img').data('default') || '');
                if (typeof (callback) != 'undefined') {
                    if (debug)
                        console.log('job upload game file is completed.');
                    window.callbackjob--;
                    eval(callback + '();');
                }
            }
        });
    }

    function grabbing_featured_img(callback) {
        var did = $('#myform .edit_id').val();
        var gamename = $('#myform #game_name').val();
        if (($('#grab_featured_img').val() == $('#grab_featured_img').data('default')) || ($('#grab_featured_img').val() == ''))
            return false;
        if (typeof (callback) != 'undefined')
            window.callbackjob++;
        var file_addr = $('#grab_featured_img').val();
        st3 = $.sticky('<?= addslashes(L::alert_grabbing_file); ?>', {autoclose: false, position: "top-right", type: "st-info", speed: "fast"});
        if (!$('#grab_featured_img').closest('.grab').find('.shoimageloading').length) {
            $('#grab_featured_img').closest('.grab').append("<img src='<?= static_url() ?>/images/loading/loading-9.gif' class='shoimageloading'/>");
        }
        // encode and slashes
        data = {'id': did, 'from': file_addr, 'gamename': gamename};
        $.each(data, function (k, v) {
            data[k] = encodeURIComponent(v);
        });
        $.ajax({
            type: 'POST',
            data: data,
            url: "<?= url::itself()->url_nonqry(array('act_grab_featured_img' => 1)) ?>",
            success: function (result) {
                $('#grab_featured_img').closest('.grab').find('.shoimageloading').remove();
                $.stickyhide(st3.id);
                obj = JSON.parse(result);
                if (obj.grab_code === 1) {
                    $.sticky(obj.grab_txt, {autoclose: 5000, position: "top-right", type: "st-success", speed: "fast"});
                    if (typeof (callback) == 'undefined') {
                        open_uploader_imagebox('#filebox-featured_img', 'featured_img', obj.file);
                    }
                    $('#featured_img').val(obj.file);
                }
                else {
                    $.sticky(obj.grab_txt, {autoclose: 5000, position: "top-right", type: "st-error", speed: "fast"});
                    $('#filebox-featured_img').html('').fadeOut();
                    $('#featured_img').val('');
                }
                $('#grab_featured_img').val($('#grab_featured_img').data('default') || '');
                if (typeof (callback) != 'undefined') {
                    if (debug)
                        console.log('job upload game file is completed.');
                    window.callbackjob--;
                    eval(callback + '();');
                }
            }
        });
    }

</script>
<?php
get_footer();
?>