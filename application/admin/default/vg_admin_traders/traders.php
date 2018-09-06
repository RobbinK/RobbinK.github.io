<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: traders.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:56
##########################################################
 */

### call header
abs_admin_inc(l_basic);
abs_admin_inc(l_colorbox);
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
                        <?=L::sidebar_trd_mng;?>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /Navigation Menu -->

        <!-- Add Trader --> 
        <div id="form_div" class="tab-content" style="visibility:visible ">
            <h3 class="heading" id="div_title"></h3>

            <form id="myform" method="post" action="<?= url::itself()->fulluri() ?>" class="form_validation_reg" novalidate="novalidate">
                <input type="hidden" name="id" id="id" class="edit_id" value="" />
                <dl class="dl-horizontal">

                    <dt><label> <?=L::forms_trader_title;?> </label></dt>
                    <dd><div><input type="text" name="title" id="title" value="" required /></div></dd>

                    <dt><label> <?=L::forms_description;?> </label></dt>
                    <dd><div><textarea  name="description" id="description" class="input-xxlarge"></textarea></div></dd>


                    <dt><label> <?=L::forms_email;?> </label></dt>
                    <dd><div><input type="email" name="trader_email" id="trader_email" value="" /></div></dd>


                    <?php
                    $default_trade_ratio = setting::get_data('default_trade_ratio', 'val') > 0 ? setting::get_data('default_trade_ratio', 'val') : 1;
                    ?>
                    <dt><label> <?=L::forms_trade_ratio;?></label></dt>
                    <dd><div><input type="text" name="trade_ratio" id="trade_ratio" value="" required data-default="<?= $default_trade_ratio ?>" class="input-mini"/></div></dd>


                    <dt><label> <?=L::forms_daily_cap;?> </label></dt>
                    <dd><div><input type="text" name="daily_cap" id="daily_cap" required data-default="99999" class="input-mini" /></div></dd>


                    <dt><label> <?=L::forms_forced_hits;?> </label></dt>
                    <dd><div class="sepH_c"><input type="text" name="forced_hits" id="forced_hits" required data-default="0" class="input-mini" /></div></dd>

                    <dt><label><?=L::forms_tire1_credit;?> (<?=L::forms_add_remove;?>) </label></dt>
                    <dd><div>
                            <input type="text" name="tier_1_credit" id="tier_1_credit" required data-default="0" class="input-mini" />
                            <span class="help-inline"><?=L::forms_current_amount;?> : <em>0</em></span>
                        </div></dd>

                     <dt><label><?=L::forms_tire2_credit;?> (<?=L::forms_add_remove;?>) </label></dt>
                    <dd><div>
                            <input type="text" name="tier_2_credit" id="tier_2_credit" required data-default="0" class="input-mini" />
                            <span class="help-inline"><?=L::forms_current_amount;?> : <em>0</em></span>
                        </div></dd>

                     <dt><label><?=L::forms_tire3_credit;?> (<?=L::forms_add_remove;?>) </label></dt>
                    <dd><div  class="sepH_c">
                            <input type="text" name="tier_3_credit" id="tier_3_credit" required data-default="0" class="input-mini" />
                            <span class="help-inline"><?=L::forms_current_amount;?> : <em>0</em></span>
                        </div></dd>


                    <dt><label><?=L::forms_sites;?> </label></dt>
                    <dd>
                        <div  id="sites" class="sepH_c" style="background-color: #FEFEFE;width: 60%;padding: 10px;border: solid 1px #E1E2E4;border-radius: 5px;"></div>
                    </dd>

                    <dt><label> <?=L::forms_sending_policy;?> </label></dt>
                    <dd><div>
                            <select name="send_to_homepage" id="send_to_homepage"  style="width:400px">                                                    
                                <option value="1"><?=L::forms_send_to_homepage;?>(<?=L::forms_if_no_plugs;?>)</option>
                                <option value="0"><?=L::forms_use_the_same_plugs_only;?></option>
                            </select> 
                        </div></dd>

                    <dt><label> <?=L::forms_speed;?> </label></dt>
                    <dd><div>
                            <select name="speed" id="speed" style="width: 150px">                                                    
                                <option value="1"><?=L::global_very_slow;?></option>
                                <option value="2"><?=L::global_slow;?></option>
                                <option value="3"><?=L::global_normal;?></option>
                                <option value="4"><?=L::global_fast;?></option>
                                <option value="5"><?=L::global_very_fast;?></option>
                            </select> 
                        </div></dd>

                    <dt><label> <?= L::global_status; ?></label></dt>
                    <dd>
                        <div>
                            <select name="status" id="status" style="width: 100px">                                                    
                                <option value="1"><?=L::global_active;?></option>
                                <option value="0"><?=L::global_inactive;?></option>
                            </select> 
                        </div>
                    </dd>
                    <dt></dt>
                    <dd> 
                        <div>
                            <input class="btn btn-success" type="submit" value="<?= addslashes(L::global_save);?>" style="width: 120px"/>
                            &nbsp;&nbsp;
                            <input class="btn" type="button" name="reset" value="<?= addslashes(L::forms_add_new_trader);?>" onclick="reset_form();
                                    return false;" />
                            &nbsp;&nbsp;
                            <input class="btn" type="button" name="close" value="<?= addslashes(L::global_cancel);?>" onclick="close_from();
                                    return false;" style="width: 80px"/>           
                        </div>
                    </dd> 

                </dl>

            </form>
            <h3 class="heading" id="div_title"></h3>
        </div>
        <!-- /Add Trader -->

        <!-- Traders List -->
        <h3 class="heading"><?=L::forms_traders_list;?>
            <button class="pull-right btn btn-info  bt_add_new"   onclick=""><?=L::forms_add_new_trader;?></button> 
            <button class="pull-right btn btn-info  bt_cancel" style='display: none'   onclick=""><?= L::global_cancel; ?></button> 
        </h3>
        <table id="dt_e" class="table table-striped table-bordered dTableR" >
            <thead>
                <tr>
                    <th>ID</th>
                    <th><?=L::forms_trader_title;?></th>
                    <th><?=L::forms_out_today;?></th>
                    <th><?=L::forms_in_today;?></th> 
                    <th><?=L::forms_today_ratio;?></th>
                    <th><?=L::forms_out_overall;?></th>
                    <th><?=L::forms_in_overall;?></th>
                    <th><?=L::forms_convert_overall;?></th>
                    <th><?=L::forms_credits;?></th> 
                    <th><?=L::forms_trade_ratio;?></th>
                    <th><?=L::forms_speed;?></th>
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
        <!-- /Traders List -->


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

    .speedBtnGroup{
        width: 14px;
        position: relative;
    }

    .speedBtnGroup .up{
        position: absolute;
        height: 13px;
        top:-3px;
        z-index: 1;
        cursor: pointer;
    }
    .speedBtnGroup .down{
        position: absolute;
        top: 8px;
        cursor: pointer;
    }

    .RunBtnGroup{
        width: 14px;
        position: relative;
        margin: 0;
    } 
    .RunBtnGroup i{
        cursor: pointer;
    }
</style>

<script type="text/javascript">
                                var fValidation;
                                var oTable;
                                var loading_config = {
                                    'indicatorZIndex': 990,
                                    'overlayZIndex': 990
                                };
                                $(document).ready(function() {
                                    $("#daily_cap,#forced_hits,#tier_1_credit,#tier_2_credit,#tier_3_credit").spinner({
                                        step: 100
                                    });
                                    $("#trade_ratio").spinner({
                                        step: 0.05,
                                        numberFormat: "n"
                                    });
                                    $('#form_div').fadeOut(0);
                                    oTable = $('#dt_e').dataTable({
                                        bInfo: true,
                                        bLengthChange: true,
                                        sPaginationType: "bootstrap_full", /*full_numbers , two_button*/
                                        iDisplayLength: <?=datatable_ipp?>,
                                        aLengthMenu: [[10, 20, 50, -1], ['10', '20', '50', 'All']],
                                        bPaginate: true,
                                        bFilter: true,
                                        bSort: true,
                                        bProcessing: true,
                                        bServerSide: true,
                                        sAjaxSource: "<?= url::itself()->fulluri(array('dt' => 1)) ?>",
                                        aaSorting: [[10, 'desc'], [3, 'desc']],
                                        aoColumnDefs: [
                                            {bSearchable: false, bVisible: false, aTargets: [0]},
                                            {aTargets: [1]},
                                            {aTargets: [2], "sWidth": "70px"},
                                            {aTargets: [3], "sWidth": "70px"},
                                            {aTargets: [4], bVisible: false, "sWidth": "70px"},
                                            {aTargets: [5], "sWidth": "70px"},
                                            {aTargets: [6], "sWidth": "70px"},
                                            {aTargets: [7], "sWidth": "60px"},
                                            {aTargets: [8], "sWidth": "60px"},
                                            {aTargets: [9], "sWidth": "60px"},
                                            {aTargets: [10], "sWidth": "50px"},
                                            {aTargets: [11], "sWidth": "90px"},
                                            {bSortable: false, aTargets: [12], "sWidth": "100px"}
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
                                            reg_dt_custom_buttons();
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
                                    $('.form_validation_reg').find('input:text, input[type=url], input[type=email], input[type=hidden], input:password, input:file, select, textarea').val('');
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

                                    $('#myform #sites').html('');
                                    resetLastCreadit();
                                    addnewForm();
                                    fValidation.resetForm();
                                    $('.form_validation_reg div').removeClass("f_error");
                                    $('#div_title').html('<?= addslashes(L::forms_add_new_trader);?>');
                                }


                                $('.bt_add_new').click(function() {
                                    reset_form();
                                    open_form();
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
                                    onfocusout: false,
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
                                            if (!$(this).closest('#sites').length) {
                                                if ($(this).is(':checked'))
                                                    eval("$.extend(data || {}, {'" + $(this).attr('name') + "':'" + $(this).val() + "'});");
                                                else
                                                    eval("$.extend(data || {}, {'" + $(this).attr('name') + "':0''});");
                                            }
                                        });

                                        $('#tier_1_credit,#tier_2_credit,#tier_3_credit').val(0);

                                        // encode and slashes
                                        // $.each(data, function (k, v) {
                                        //   data[k] = base64.encode(v);
                                        // });

                                        $.ajax({
                                            type: 'POST',
                                            data:{'encodedData':encodePostData(data)},
                                            dataType: 'json',
                                            url: "<?= url::itself()->url_nonqry(array('save' => 1)) ?>",
                                            success: function(obj) {
                                                $('#myform').hideLoading();
                                                if (obj.save_code === 1) {
                                                    $.sticky(obj.save_txt, {autoclose: 5000, position: "top-right", type: "st-success", speed: "fast"});
                                                    if ($('.form_validation_reg .edit_id').val() == '') {
                                                        oTable.fnReloadAjax();
                                                        reset_form();
                                                    }
                                                    else {
                                                        edit($('#myform .edit_id').val());
                                                        oTable.fnStandingRedraw();
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
                                function resetLastCreadit(o) {
                                    $('#tier_1_credit').closest('dd').find('span.help-inline').find('em').html(0);
                                    $('#tier_2_credit').closest('dd').find('span.help-inline').find('em').html(0);
                                    $('#tier_3_credit').closest('dd').find('span.help-inline').find('em').html(0);
                                }
                                function showLastCreadit(o) {
                                    if (typeof o.tier1_credits != 'undefined')
                                        $('#tier_1_credit').closest('dd').find('span.help-inline').find('em').html(o.tier1_credits);
                                    if (typeof o.tier2_credits != 'undefined')
                                        $('#tier_2_credit').closest('dd').find('span.help-inline').find('em').html(o.tier2_credits);
                                    if (typeof o.tier3_credits != 'undefined')
                                        $('#tier_3_credit').closest('dd').find('span.help-inline').find('em').html(o.tier3_credits);
                                }

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

                                function reg_dt_edit() {
                                    $('.edit').click(function() {
                                        var eid = $(this).closest('td').find('.row_id').val();
                                        edit(eid);
                                    });
                                }

                                function edit(eid) {
                                    reset_form();
                                    $('#div_title').html('<?= addslashes(L::forms_edit_trader);?>');
                                    open_form();
                                    $('#myform').showLoading(loading_config);
                                    $.ajax({
                                        type: 'POST',
                                        dataType: 'json',
                                        data: {'id': eid},
                                        url: "<?= url::itself()->url_nonqry(array('edit' => 1)) ?>",
                                        success: function(data) {
                                            $('#myform').hideLoading();
                                            showLastCreadit(data);
                                            $('#myform #sites').html('');
                                            $.each(data.sites, function(k, v) {
                                                addnewForm(k);
                                            });
                                            $('#myform').unserializeForm($.param(data));
                                            checkactive();
                                            if (!$('#myform #sites').find('table').length)
                                                addnewForm();

                                            if (typeof(tinyMCE) != 'undefined') {
                                                $.each(data, function(k, v) {
                                                    if ($('textarea.tinymce[name=' + k + ']').length) {
                                                        id = $('textarea.tinymce[name=' + k + ']').attr('id');
                                                        tinyMCE.get(id).setContent(v);
                                                    }
                                                });
                                            }

                                            build_plugs_events();
                                        }
                                    });
                                }


                                function addnewForm(i) {
                                    if (typeof i == 'undefined')
                                        i = Math.floor((Math.random() * 99999) + 10000);

                                    var TH = "";
                                    if ($("#sites table").length == 0)
                                        TH = "<tr><th></th><th><?=L::global_active;?></th><th><?=L::global_type;?></th><th><?=L::forms_domain;?></th></tr>";
                                    var htmlcode =
                                            "<table  style='width:100%;border-bottom: 1px solid #bdb9b9'  id='addtable" + i + "' style='display: none;' >" +
                                            TH +
                                            "<tr>" +
                                            "<td width='25' style='vertical-align: bottom; text-align: center'>" +
                                            "   <i class='add splashy-add'  style='border:0;' id='arrow' onclick='addnewForm()' ></i>" +
                                            "</td>" +
                                            "<td width='60' style='vertical-align:middle;text-align:center'>" + 
                                            "  <input type='checkbox' name='sites[" + i + "][status]' checked='checked' onclick='checkactive()' />" +
                                            "  <input type='hidden' name='sites[" + i + "][id]' class='domain_id'/>" +
                                            "</td>" +
                                            "<td width='85px;vertical-align:middle'>" +
                                            " <div>" + 
                                            "   <select name='sites[" + i + "][type]' style='margin-bottom:0;width:80px' class='domaintype'>" +
                                            "       <option value='1'><?=L::forms_in;?></option>" +
                                            "       <option value='2'><?=L::forms_out;?></option>" +
                                            "   </select>" +
                                            " </div>" +
                                            "</td>" +
                                            "<td style='vertical-align:middle;width:180px'>" + 
                                            "   <input  type='text' style='margin-bottom:0;' name='sites[" + i + "][site_url]' class='order input-medium'/> " +
                                            "</td>" +
                                            "<td style='vertical-align:middle'>" +
                                            "   <ul class='icon_list_d'> " +
                                            "    <li title='<?= addslashes(L::global_remove);?>' class='delsite' style='cursor: pointer;'><i class='icon-adt_trash'></i></li> " +
                                            "    <li title='<?= addslashes(L::forms_manage_plugs);?>' class='plugs' style='cursor: pointer;display:none'><i class='icon-plug'></i></li> " +
                                            "   </ul>" +
                                            "</td>" +
                                            "</tr>" +
                                            "</table> ";
                                    $('#sites').append(htmlcode);
                                    $('#sites .domaintype').change(function() {
                                        build_plugs_events();
                                    });
                                    $('#sites .delsite').click(function() {
                                        if (!confirm('Are you sure?'))
                                            return false;
                                        var parent = $(this).closest('table');
                                        var did = $(this).closest('table').find('input:hidden').val();
                                        if (did != '') {
                                            $.ajax({
                                                type: 'POST',
                                                dataType: 'json',
                                                data: {'id': did},
                                                url: "<?= url::itself()->url_nonqry(array('delsite' => 1)) ?>",
                                                success: function(data) {
                                                    if (data.save_code == 1) {
                                                        parent.fadeOut(300, function() {
                                                            $(this).remove();
                                                            if (!$('#myform #sites').find('table').length)
                                                                addnewForm();
                                                        });
                                                    }
                                                }
                                            });
                                        } else {
                                            parent.fadeOut(300, function() {
                                                $(this).remove();
                                                if (!$('#myform #sites').find('table').length)
                                                    addnewForm();
                                            });
                                        }
                                    });
                                    $("#myform #sites table:nth-last-child(2)").find('.add').fadeOut();
                                    return '#addtable' + i;
                                }

                                function checkactive() {
                                    $('#myform #sites').find('input[type=checkbox]').each(function() {
                                        if ($(this).is(':checked'))
                                            $(this).parents('table').find('input[type=text],select').removeAttr('disabled');
                                        else
                                            $(this).parents('table').find('input[type=text],select').attr('disabled', true);
                                    });
                                }

                                function build_plugs_events() {
                                    $('.plugs').each(function() {
                                        var parent = $(this).closest('table');
                                        var speed = 600;
                                        if (parent.find('.domaintype').val() == 2) {
                                            if (parent.find("input.domain_id").val() != '') {
                                                $(this).fadeIn(speed).unbind().click(function() {
                                                    var did = $('#myform').find('.edit_id').val();
                                                    var siteid = $(this).closest('table').find("input.domain_id").val();
                                                    if (did != '') {
                                                        document.location.href = "<?= url::router('admin-pooliplugs') ?>?tid=" + did + "&u=" + siteid;
                                                    }
                                                });
                                            } else {
                                                $(this).fadeIn(speed).unbind().click(function() {
                                                    alert("once! you have to save form .");
                                                    return false;
                                                });
                                            }
                                        } else
                                            $(this).fadeOut(speed);

                                    });
                                }

                                function reg_dt_custom_buttons() {
                                    $('.speedBtnGroup .up,.speedBtnGroup .down').unbind('click').click(function(e) {
                                        $this = $(this);
                                        var Tid = $(this).closest('tr').find('.row_id').val();
                                        e.stopPropagation();
                                        if ($(this).hasClass('splashy-arrow_state_grey_collapsed'))
                                            $(this).toggleClass('splashy-arrow_state_grey_collapsed splashy-arrow_state_blue_collapsed');
                                        if ($(this).hasClass('splashy-arrow_state_grey_expanded'))
                                            $(this).toggleClass('splashy-arrow_state_grey_expanded splashy-arrow_state_blue_expanded');

                                        $(this).delay(100).queue(function(next) {
                                            $('.dataTables_processing').css({visibility: 'visible'});
                                            $.ajax({
                                                url: '<?= url::itself()->url_nonqry() ?>?change',
                                                type: 'post',
                                                data: {speed: ($(this).hasClass('up') ? 'up' : 'down'), id: Tid},
                                                success: function(data) {
                                                    $('.dataTables_processing').css({visibility: 'hidden'});
                                                    if (data != -1)
                                                        $this.closest('td').find('span').replaceWith(data);
                                                }
                                            });
                                            if ($(this).hasClass('splashy-arrow_state_blue_collapsed'))
                                                $this.toggleClass('splashy-arrow_state_blue_collapsed splashy-arrow_state_grey_collapsed');
                                            if ($(this).hasClass('splashy-arrow_state_blue_expanded'))
                                                $this.toggleClass('splashy-arrow_state_blue_expanded splashy-arrow_state_grey_expanded');
                                            next();
                                        });
                                    });

                                    $('.RunBtnGroup .play,.RunBtnGroup .pause').unbind('click').click(function(e) {
                                        $this = $(this);
                                        var Tid = $(this).closest('tr').find('.row_id').val();
                                        e.stopPropagation();
                                        $(this).addClass('icon-white').delay(100).queue(function(next) {
                                            $('.dataTables_processing').css({visibility: 'visible'});
                                            $.ajax({
                                                url: '<?= url::itself()->url_nonqry() ?>?change',
                                                type: 'post',
                                                dataType: 'json',
                                                data: {status: ($(this).hasClass('play') ? 1 : 0), id: Tid},
                                                success: function(data) {
                                                    $('.dataTables_processing').css({visibility: 'hidden'});
                                                    if (data != -1)
                                                    {
                                                        if (data.st == 1) {
                                                            $this.closest('td').find('span').replaceWith(data.ss);
                                                            $this.toggleClass('icon-play icon-pause').toggleClass('play pause');
                                                        }
                                                        else if (data.st == 0) {
                                                            $this.closest('td').find('span').replaceWith(data.ss);
                                                            $this.toggleClass('icon-pause icon-play').toggleClass('pause play');
                                                        }
                                                    }
                                                }
                                            });
                                            $(this).removeClass('icon-white');
                                            next();
                                        });
                                    });
                                }

</script>
<?php
get_footer();
?>