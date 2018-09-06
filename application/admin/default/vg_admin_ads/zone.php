<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: zone.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:56
##########################################################
 */

### call header
abs_admin_inc(l_basic);
abs_admin_inc(l_validate);
abs_admin_inc(l_datatable);
abs_admin_inc(l_sticky);
abs_admin_inc(l_smoke);
abs_admin_inc(l_unserializeForm);
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
                        <?= L::sidebar_mng_ad_zone; ?>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /Navigation Menu -->

        <!-- Add Zone --> 
        <div id="form_div" class="tab-content" style="visibility:visible ">
            <h3 class="heading" id="div_title"></h3>

            <form class="form_validation_reg" id="myform" name="myform" method="post" action="<?= url::itself()->fulluri() ?>"  novalidate="novalidate" enctype="multipart/form-data" onsubmit="return false">
                <input type="hidden" name="id" id="id" class="edit_id" value="" />
                <dl class="dl-horizontal">

                    <p class="well well-small" id="zone_snipped">
                        <?= L::forms_place_code; ?><br><br>
                        <code style='direction:ltr'></code>
                    </p>

                    <dt><label> <?= L::forms_zone_name; ?> </label></dt>
                    <dd>
                        <div><input type="text" name="zone_name" id="zone_name" value="" size="50" required/></div> 
                    </dd>
                    <dt><label> <?= L::global_type; ?> </label></dt>
                    <dd> 
                        <div>
                            <select  id="type" name="type" onchange="changetype()">
                                <option value="banner"><?= L::forms_banner_area; ?></option>
                                <option value="popunder"><?= L::forms_popunder; ?></option> 
                                <option value="skin"><?= L::forms_skin_ads; ?></option> 
                                <option value="anchor"><?= L::forms_anchore_ads; ?></option> 
                            </select>
                        </div>
                    </dd>  
                    <dt class="adsizebox"><label> <?= L::forms_zone_size; ?> </label></dt>
                    <dd class="adsizebox"> 
                        <div>
                            <select id="adsize" name="adsize" onchange="changeadsize()">
                                <option value="100x100">100x100</option>
                                <option value="120x600">120x600</option>
                                <option value="160x600">160x600</option>
                                <option value="200x200">200x200</option>
                                <option value="300x250">300x250</option>
                                <option value="336x280">336x280</option>
                                <option value="468x60">468x60</option>
                                <option value="728x90">728x90</option>
                                <option value="custom">custom</option>
                            </select>
                        </div>
                    </dd> 

                    <dt class="widthbox"><label> <?= L::global_width; ?> </label></dt>
                    <dd class="widthbox"> 
                        <div class="span7" style="margin-left: 0"> 
                            <input id="width"  class="input-mini"   type="text" data-default="100"  name="width" autocomplete="off" required/>
                            <span class="help-inline"><?= L::forms_size_px; ?></span>
                            <em></em>

                        </div>
                    </dd> 
                    <dt class="heightbox"><label> <?= L::global_height; ?></label></dt> 
                    <dd class="heightbox"> 
                        <div class="span7" style="margin-left: 0;"> 
                            <input id="height" class="input-mini"  type="text"  data-default="100" name="height"  autocomplete="off" required>
                            <span class="help-inline"><?= L::forms_size_px; ?></span>
                            <em></em>
                        </div>
                    </dd>  
                    <dt><label> <?= L::forms_ad_show_policies; ?> </label></dt>
                    <dd> 
                        <div>
                            <select  name="show_ad" >
                                <option value="1"><?= L::forms_single_ads; ?></option>
                                <option value="2"><?= L::forms_multiple_ads_random; ?></option> 
                                <option value="3"><?= L::forms_multiple_ads_ordered; ?></option> 
                            </select>
                        </div>
                    </dd> 
                    <dt style="width:200px"> </dt>
                    <dd class="pull-left"> 
                        <div>
                            <input class="btn btn-success" type="submit" value="<?= addslashes(L::global_save);?>" style="width: 60px"/>
                            &nbsp;&nbsp;
                            <input class="btn btn-success savecontinue" type="button" value="<?= addslashes(L::global_save_continue);?>" onclick="save_continue()" />
                            &nbsp;&nbsp;
                            <input class="btn" type="button" name="reset" value="<?= addslashes(L::forms_new_zone);?>" onclick="reset_form();
                    return false;"/>
                            &nbsp;&nbsp;
                            <input class="btn" type="button" name="close" value="<?= addslashes(L::global_cancel);?>" onclick="close_from();
                    return false;" style="width: 80px"/>   
                            &nbsp;&nbsp;
                            <input class="btn" id="managebtn" type="button"  value="<?= addslashes(L::forms_manage_ads);?>"  disabled="disabled"/>                         
                        </div>
                    </dd>  
                </dl>

            </form>
            <h3 class="heading" id="div_title"></h3>
        </div>
        <!-- /Add Zone -->

        <!-- Zone List -->
        <h3 class="heading"><?= L::forms_zone_list; ?>
            <button class="pull-right btn btn-info  bt_add_new"   onclick=""><?= L::forms_add_new_zone; ?></button> 
            <button class="pull-right btn btn-info  bt_cancel" style='display: none'   onclick=""><?= L::global_cancel; ?></button> 
        </h3>
        <table id="dt_e" class="table table-striped table-bordered dTableR" >
            <thead>
                <tr>
                    <th>ID</th>
                    <th><?= L::forms_zone_name; ?></th>
                    <th><?= L::forms_ad_type; ?></th>
                    <th width="80"><?= L::forms_ad_size; ?></th>
                    <th width="100"><?= L::forms_total_ads; ?></th>
                    <th width="200"><?= L::forms_ad_show_policies; ?></th> 
                    <th width="100"><?= L::global_action; ?></th>  
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="dataTables_empty" colspan="7"><?= L::forms_loading_data; ?></td>
                </tr>
            </tbody>
        </table>
        <!-- /Zone List -->


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
                var fValidation;
                var oTable;
                var loading_config = {
                    'indicatorZIndex': 990,
                    'overlayZIndex': 990
                };

                var scontinue = false;
                function save_continue() {
                    scontinue = true;
                    $("#myform").submit();
                }


                function changeadsize() {
                    if ($('#adsize').val() == 'custom') {
                        $('.heightbox').fadeIn(300);
                        $('.widthbox').fadeIn(300);
                    } else {
                        $('.heightbox').fadeOut(300);
                        $('.widthbox').fadeOut(300);
                    }
                }
                function changetype() {
                    if ($('#type').val() == 'banner') {
                        $('.adsizebox').fadeIn(300);
                        changeadsize();

                    } else {
                        $('.adsizebox').fadeOut(300);
                        $('.heightbox').fadeOut(300);
                        $('.widthbox').fadeOut(300);
                    }
                }
                $(document).ready(function() {
                    $('#form_div').fadeOut();
                    $("#width").spinner({min: 1});
                    $("#height").spinner({min: 1});
                    oTable = $('#dt_e').dataTable({
                        bInfo: true,
                        bLengthChange: true,
                        sPaginationType: "bootstrap_full", /*full_numbers , two_button*/
                        iDisplayLength: <?=datatable_ipp?>,
                        aLengthMenu: [[10, 20, 50, -1], ['10', '20', '50', 'All']],
                        bPaginate: false,
                        bFilter: true,
                        bSort: true,
                        bProcessing: true,
                        bServerSide: true,
                        sAjaxSource: "<?= url::itself()->fulluri(array('dt' => 1)) ?>",
                        aaSorting: [[0, 'desc']],
                        aoColumnDefs: [
                            {bSearchable: false, bVisible: false, aTargets: [0]},
                            {aTargets: [1]},
                            {aTargets: [2]},
                            {aTargets: [3]},
                            {bSortable: false, aTargets: [4]},
                            {aTargets: [5]},
                            {bSortable: false, aTargets: [6]}
                        ],
                        sDom: 'f<"toolbar">rtip',
                        oLanguage: dataTablesLanguages,
                        fnDrawCallback: function() {
                            $('#dt_e tbody td a').click(function(e) {
                                if ($(this).attr('href') != '#' && $(this).attr('href') != '')
                                    window.open(this.href, $(this).attr('target') || '_self');
                                e.preventDefault();
                                return false;
                            });
                            $('#dt_e tbody td:last-child').click(function(e) {
                                e.preventDefault();
                                return false;
                            });
                            dt_selection_stats();
                            reg_dt_delete();
                            reg_dt_edit();
                            reg_dt_row_click();
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
                });

                function reset_form() {

                    $('.form_validation_reg').find('input:text, input[type=url],input[type=hidden], input:password, input:file, select, textarea').val('');
                    $('.form_validation_reg').find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
                    //tinymce
                    if (typeof(tinyMCE) != 'undefined') {
                        $('textarea.tinymce').each(function() {
                            tinyMCE.get($(this).attr('id')).setContent('');
                            tinyMCE.DOM.setStyle(tinyMCE.DOM.get($(this).attr('id') + '_ifr'), 'height', 120 + 'px');
                        });
                    }
                    //select
                    $('.form_validation_reg').find('select').each(function() {
                        $(this).find('option:first').attr('selected', 'true');
                        $(this).trigger('change');
                    });
                    //default
                    $('.form_validation_reg').find('input').each(function() {
                        if ($(this).attr('data-default')) {
                            $(this).val($(this).data('default'));
                        }
                    });
                    //date
                    $('.form_validation_reg').find('input').each(function() {
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
                    $('.adsizebox').fadeIn();
                    $('.heightbox').fadeOut();
                    $('.widthbox').fadeOut();

                    fValidation.resetForm();
                    $('.form_validation_reg div').removeClass("f_error");
                    $('#div_title').html('<?= addslashes(L::forms_add_new_zone);?>');
                    $('#managebtn').attr('disabled', true);
                    $('#zone_snipped').hide();
                    $('.savecontinue').show();

                }

                $('#managebtn').click(function() {
                    var editid = $(this).closest('#myform').find('.edit_id').val();
                    var url = "<?= url::router('admin-ads') ?>?zone_id=" + editid;
                    document.location.href = url;
                });


                $('.bt_add_new').click(function() {
                    reset_form();
                    open_form();
                    $('.heightbox').hide();
                    $('.widthbox').hide();

                });
                $('.bt_cancel').click(function() {
                    close_from();
                });

                function open_form() {
                    $('.bt_add_new').hide();
                    $('.bt_cancel').show();
                    $(window).scrollTop(0);
                    $('#form_div').slideDown(200);
                }

                function close_from() {
                    $('.bt_add_new').show();
                    $('.bt_cancel').hide();
                    reset_form();
                    $('#form_div').slideUp(200);

                }


                // Validation Options
                fValidation = $("#myform").validate({
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
                    onfocusout: false,
                    rules: {
                        width: {
                            min: 1,
                            required: true
                        },
                        height: {
                            min: 1,
                            required: true

                        }
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
                                    if (typeof obj.insid != 'undefined' && scontinue == true) {
                                        scontinue = false;
                                        document.location.href = '<?= url::router('admin-ads') ?>?zone_id=' + obj.insid;
                                    }

                                    if ($('.form_validation_reg .edit_id').val() == '') {
                                        oTable.fnReloadAjax();
                                        ajax_edit(obj.insid);
                                    }
                                    else {
                                        oTable.fnStandingRedraw();
                                        $('#zone_snipped').show().find('code').html(obj.zone_snipped);
                                    }
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


                function reg_select_all() {
                    $('.toolbar .sall').click(function() {
                        $('table.table tbody tr').addClass('row_selected');
                        dt_selection_stats();
                    });

                }
                function reg_deselect_all() {
                    $('.toolbar .dall').click(function() {
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
                // Delete Zone Handler
                function reg_multidelete() {
                    $('.toolbar .mdel').click(function() {
                        var ids = [];
                        $('#dt_e .row_selected').each(function() {
                            id = $(this).find('input.row_id').val();
                            ids.push(id);
                        });
                        smoke.confirm('<?= addslashes(L::alert_del_warning);?>', function(e) {
                            if (e) {
                                st1 = $.sticky('<?= addslashes(L::alert_deleting_records);?>', {autoclose: false, position: "top-right", type: "st-info", speed: "fast"});
                                $.ajax({
                                    type: 'POST',
                                    data: {id: ids},
                                    url: "<?= url::itself()->url_nonqry(array('mdel' => 1)) ?>",
                                    success: function(result) {
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
                    $('#dt_e tbody tr').click(function() {
                        $(this).toggleClass('row_selected');
                        dt_selection_stats();
                    });
                }
                function  reg_dt_delete() {
                    $('.del').click(function() {
                        var did = $(this).closest('td').find('.row_id').val();
                        smoke.confirm('<?= addslashes(L::alert_del_warning);?>', function(e) {
                            if (e) {
                                st1 = $.sticky('<?= addslashes(L::alert_deleting_records);?>', {autoclose: false, position: "top-right", type: "st-info", speed: "fast"});
                                $.ajax({
                                    type: 'POST',
                                    data: {id: did},
                                    url: "<?= url::itself()->url_nonqry(array('del' => 1)) ?>",
                                    success: function(result) {
                                        $.stickyhide(st1.id);
                                        $.sticky(result, {autoclose: 5000, position: "top-right", type: "st-success", speed: "fast"});
                                        oTable.fnStandingRedraw();
                                    }
                                });

                            }
                        }, {});
                    });
                }
                // Edit Zone Handler 
                function  reg_dt_edit() {
                    $('.edit').click(function() {
                        var eid = $(this).closest('td').find('.row_id').val();
                        ajax_edit(eid);
                    });
                }

                function ajax_edit(id) {
                    reset_form();
                    $('#div_title').html('<?= addslashes(L::forms_edit_zone);?>');
                    open_form();
                    $('#myform').showLoading(loading_config);
                    $.ajax({
                        type: 'POST',
                        data: {'id': id},
                        url: "<?= url::itself()->url_nonqry(array('edit' => 1)) ?>",
                        success: function(result) {
                            $('#myform').hideLoading();
                            data = JSON.parse(result);
                            $('#myform').unserializeForm($.param(data));
                            if (typeof(tinyMCE) != 'undefined') {
                                $.each(data, function(k, v) {
                                    if ($('textarea.tinymce[name=' + k + ']').length) {
                                        id = $('textarea.tinymce[name=' + k + ']').attr('id');
                                        tinyMCE.get(id).setContent(v);
                                    }
                                });
                            }
                            setTimeout(function() {
                                if (typeof(data.icon) != 'undefined' && data.icon != '') {
                                    open_uploader_imagebox('#filebox', 'icon', data.icon);
                                    $('#icon').val(data.icon);
                                }
                            }, 600);
                            $('#managebtn').removeAttr('disabled');
                            $('.savecontinue').hide();
                            $('#zone_snipped').show().find('code').html(data.zone_snipped);
                            changetype();
                            changeadsize();
                        }
                    });
                }


</script>
<?php
get_footer();
?>