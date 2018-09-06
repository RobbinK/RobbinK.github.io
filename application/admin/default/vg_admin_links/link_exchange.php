<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: link_exchange.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:56
##########################################################
 */

### call header
abs_admin_inc(l_basic);
abs_admin_inc(l_datepicker);
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
                        <?= L::sidebar_mng_exch; ?>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /Navigation Menu -->

        <!-- Add Link --> 
        <div id="form_div" class="tab-content" style="visibility:visible ">
            <h3 class="heading" id="div_title"></h3>

            <form id="myform" method="post" action="<?= url::itself()->fulluri() ?>" class="form_validation_reg" novalidate="novalidate">
                <input type="hidden" name="id" id="id" class="edit_id" value="" />
                <dl class="dl-horizontal">
                    <dt><label> <?= L::forms_my_url; ?> </label></dt>
                    <dd><div><input type="url" name="local_url" id="local_url" value="" size="50" data-default='<?= HOST_URL ?>' required></div></dd>

                    <dt><label><?= L::forms_partner_title; ?> </label></dt>
                    <dd><div><input type="text" name="partner_title" id="partner_title" value="" size="50" required></div></dd>

                    <dt><label> <?= L::forms_partner_url; ?> </label></dt>
                    <dd><div><input type="url" name="partner_url" id="partner_url" value="" size="60" data-default='http://' required></div></dd>

                    <dt><label><?= L::forms_email; ?> </label></dt>
                    <dd><div><input type="email" name="partner_email" id="partner_email" value="" ></div></dd> 

                    <dt><label> <?= L::forms_show_page_url; ?> </label></dt>
                    <dd><div><input type="url" name="show_page_url" id="show_page_url" value="" size="50" data-default='http://' required></div></dd>

                    <dt><label> <?= L::forms_expire_time; ?> </label></dt>
                    <dd><div>
                            <div class="input-append date" id="dp1" data-date-format="yyyy-mm-dd">
                                <input class="span2" size="16" type="text" name="expire_date" id="expire_date"><span class="add-on"><i class="icon-th"></i></span>
                            </div>
                            <span class='help-block'>YYYY-MM-DD</span> 
                        </div>
                    </dd>

                    <dt><label> <?= L::forms_position; ?>  </label></dt>
                    <dd>
                        <div>
                            <select name="position"  id="position" class="input-medium">                                                    
                                <option value="0"><?= L::forms_homepage_only; ?></option>
                                <option value="1"><?= L::forms_internal_page; ?></option>
                                <option value="2"><?= L::forms_all_pages; ?></option>
                                <option value="3"><?= L::forms_links_page; ?></option>
                            </select> 
                        </div>
                    </dd>

                    <dt><label> <?= L::forms_priority; ?> </label></dt>
                    <dd><div><input type="text" name="priority"  id="priority" value="" size="2" class="input input-mini" required="true" digits="true"></div></dd>

                    <dt><label> <?= L::global_status; ?></label></dt>
                    <dd>
                        <div> 
                            <select name="status"  id="status" class="input-medium">                                                    
                                <option value="1"><?= L::global_enable; ?></option>
                                <option value="0"><?= L::global_disable; ?></option>
                            </select> 
                        </div>
                    </dd>
                    <dd>
                        <div>
                            <input class="btn btn-success" type="submit" value="<?= addslashes(L::global_save);?>" style="width: 120px"/>
                            &nbsp;&nbsp;
                            <input class="btn" type="button" name="reset" value="<?= addslashes(L::forms_new_link);?>" onclick="reset_form();
                                    return false;"/>
                            &nbsp;&nbsp;
                            <input class="btn" type="button" name="close" value="<?= addslashes(L::global_cancel);?>" onclick="close_from();
                                    return false;" style="width: 80px"/>                             
                        </div>
                    </dd> 
                </dl>

            </form>
            <h3 class="heading" id="div_title"></h3>
        </div>
        <!-- /Add Link -->

        <!-- Links List -->
        <h3 class="heading"><?= L::forms_links_list; ?>
            <button class="pull-right btn btn-info  bt_add_new"   onclick=""><?= L::forms_add_new_link; ?></button> 
            <button class="pull-right btn btn-info  bt_cancel" style='display: none' onclick=""><?= L::global_cancel; ?></button> 
        </h3> 
        <table id="dt_e" class="table table-striped table-bordered dTableR" >
            <thead>
                <tr>
                    <th>ID</th>
                    <th><?= L::forms_partner_title; ?></th>
                    <th><?= L::forms_partner_url; ?></th>
                    <th><?= L::forms_show_page_url; ?></th>
                    <th><?= L::forms_expiration_date; ?></th>
                    <th><?= L::forms_position; ?></th>
                    <th><?= L::forms_priority; ?></th>
                    <th><?= L::global_status; ?></th>
                    <th><?= L::forms_last_check; ?></th>
                    <th><?= L::forms_link_status; ?></th>
                    <th><?= L::global_action; ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="dataTables_empty" colspan="9"><?= L::forms_loading_data; ?></td>
                </tr>
            </tbody>
        </table>
        <!-- /Link List -->  
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
                                $(document).ready(function() {
                                    $("#priority").spinner({
                                        min: 1
                                    });

                                    $('#dp1').datepicker();
                                    $('#form_div').fadeOut(0);
                                    oTable = $('#dt_e').dataTable({
                                        bInfo: true,
                                        bLengthChange: true,
                                        sPaginationType: "bootstrap_full",
                                        iDisplayLength: <?=datatable_ipp?>,
                                        aLengthMenu: [[40, 60, 100, -1], ['40', '60', '100', 'All']],
                                        bPaginate: true,
                                        bFilter: true,
                                        bSort: true,
                                        bProcessing: true,
                                        bServerSide: true,
                                        sAjaxSource: "<?= url::itself()->fulluri(array('dt' => 1)) ?>",
                                        aaSorting: [[0, 'desc']],
                                        aoColumnDefs: [
                                            {bSearchable: false, bVisible: false, aTargets: [0]},
                                            {aTargets: [1], sWidth: '120px'},
                                            {aTargets: [2], sWidth: '120px'},
                                            {aTargets: [3]},
                                            {aTargets: [4], sWidth: '80px'},
                                            {aTargets: [5], sWidth: '60px'},
                                            {aTargets: [6], sWidth: '60px'},
                                            {aTargets: [7], sWidth: '60px'},
                                            {bSearchable: false, bVisible: false, aTargets: [8]},
                                            {aTargets: [9], sWidth: '85px'},
                                            {bSortable: false, aTargets: [10], sWidth: '70px'}
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
                                            reg_dt_refresh();
                                            reg_dt_edit();
                                            reg_dt_row_click();
                                        }
                                    });

                                    $("div.toolbar").html('<div class="sepH_a" id="toolbar_inside">\n\
                                <button class="btn btn-mini sepV_a sall"><li class="icon-th-list"></li> <?= L::global_select_all; ?></button>\n\
                                <button class="btn btn-mini sepV_a dall" style="display:none"><li class="icon-ban-circle"></li> <?= L::global_deselect_all; ?></button>\n\
                                <button class="btn btn-mini sepV_a btn-danger mdel" style="display:none"><li class="icon-trash"></li> <?= L::global_delete_selected; ?></button>\n\
                                <button class="btn btn-mini sepV_a btn-info mcheck" style="display:none"><li class="icon-refresh"></li> <?= L::forms_check_selected; ?></button>\n\
                                </div>');

                                    reg_select_all();
                                    reg_deselect_all();
                                    reg_multidelete();
                                    reg_multicheck();
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

                                    fValidation.resetForm();
                                    $('.form_validation_reg div').removeClass("f_error");
                                    $('#div_title').html('Add New Link');
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
                                jQuery.validator.addMethod("url", function(value, element) {
                                    return this.optional(element) || /^(https?:\/\/)?((localhost|[a-z0-9\-]+(\.[a-z0-9\-]+)+)(:[0-9]+)?(\/.*)?)?$/.test(value);
                                }, "<?= addslashes(L::alert_invalid_link);?>");
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
                                                    if ($('.form_validation_reg .edit_id').val() == '') {
                                                        oTable.fnReloadAjax();
                                                        reset_form();
                                                    }
                                                    else {
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
                                        $('.toolbar .mcheck').fadeIn(300);
                                    } else {
                                        $('.toolbar .mdel').fadeOut(300);
                                        $('.toolbar .dall').fadeOut(300);
                                        $('.toolbar .mcheck').fadeOut(300);
                                    }
                                }
                                // Delete Link Handler
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

                                function  reg_dt_refresh() {
                                    $('.chk,.check').click(function(e) {
                                        e.stopPropagation();
                                        //label label-important
                                        var $this = $(this);
                                        $this.parent().find('.check').addClass('refreshing');
                                        $.ajax({
                                            beforeSend: function() {
                                            },
                                            type: 'POST',
                                            dataType: 'json',
                                            data: {check_status: 1, btn_id: $this.closest('td').find('.row_id_ref').val()},
                                            url: "<?= url::itself()->url_nonqry() ?>",
                                            success: function(res) {
                                                $this.closest('td').find('.label').removeClass('label-important').removeClass('label-info').removeClass('label-success');
                                                switch (res.st)
                                                {
                                                    case 1:
                                                        $this.closest('td').find('.label').addClass('label-success');
                                                        break;
                                                    case 0:
                                                        $this.closest('td').find('.label').addClass('label-info');
                                                        break;
                                                    case -1:
                                                        $this.closest('td').find('.label').addClass('label-important');
                                                        break;
                                                }

                                                $this.closest('td').find('span').html(res.txt);
                                                $this.parent().find('.check').removeClass('refreshing');
                                            }
                                        });
                                    });
                                }
                                // Edit Link Handler 
                                function  reg_dt_edit() {
                                    $('.edit').click(function() {
                                        var eid = $(this).closest('td').find('.row_id').val();
                                        reset_form();
                                        $('#div_title').html('<?= addslashes(L::forms_edit_link);?>');
                                        open_form();
                                        $('#myform').showLoading(loading_config);
                                        $.ajax({
                                            type: 'POST',
                                            data: {'id': eid},
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
                                            }
                                        });
                                    });
                                }


                                function reg_multicheck() {
                                    $('.toolbar .mcheck').click(function() {
                                        $('#dt_e .row_selected').each(function() {
                                            $(this).find('.check').trigger('click');
                                        });
                                    });
                                }
</script>
<?php
get_footer();
?>