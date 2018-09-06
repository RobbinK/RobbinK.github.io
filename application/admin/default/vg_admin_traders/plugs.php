<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: plugs.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:56
##########################################################
 */

### call header
abs_admin_inc(l_basic);
abs_admin_inc(l_sticky);
abs_admin_inc(l_smoke);
abs_admin_inc(l_validate);
abs_admin_inc(l_datatable);
abs_admin_inc(l_unserializeForm);
abs_admin_inc(l_jquery_ui_js);
abs_admin_inc_css(static_url() . '/js/select2-3.4.5/select2.css');
abs_admin_inc_js(static_path() . '/js/select2-3.4.5/select2.js', array(JS_MINIFY => false));
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
                        <a href="<?= url::router('admin-poolitraders'); ?>"><?=L::sidebar_trd_mng;?></a>
                    </li>
                    <li class="last">
                        <?=L::forms_plugs;?>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /Navigation Menu -->

        <font style="font:15px 'PT sans';"><?=L::dashboard_trader;?> </font> 
        <span class="label label-success sepH_c">
            <font style="font:15px 'PT sans';"><?= $trader_title ?></font>
        </span> / 
        <span class="label label-warning sepH_c">
            <font style="font:15px 'PT sans';"><?= $trader_domain ?></font>
        </span>

        <?php
        if (isset($sysdetected)) :
            ?>
            <div class="well sysdetected">
                <div class="alert alert-success">
                    <a class="close" data-dismiss="alert">×</a>
                    <strong>Found!</strong> 
                    ArcadeBooster detects the site's trade system .
                    so you can grab all of its games that they have the same name in your database .
                </div>
                <ul class="list_d">
                    <li>trader's system : <b><?= $systitle ?></b></li>
                    <li>number of discovered games : <b><?= $sysnumgames ?></b></li> 
                </ul>
                <button class="btn btn-success grabtradergames" data-sys='<?= $sysname ?>' data-numgames='<?= $sysnumgames ?>'>Grab the same games</button>
            </div>
            <?php
        endif;
        ?>

        <!-- Add plugs --> 
        <div id="form_div" class="tab-content" style="visibility:visible ">
            <h3 class="heading" id="div_title"></h3>

            <form class="form_validation_reg" id="myform" name="myform" method="post"    novalidate="novalidate" enctype="multipart/form-data" onsubmit="return false">
                <input type="hidden" name="id" id="id" class="edit_id" value="" /> 
                <dl class="dl-horizontal"> 
                    <dt><label> <?=L::global_game;?> </label></dt>
                    <dd class="sepH_b"><div> 
                            <select id="gid" style="width:300px;" class="populate" name="gid"> 
                                <?php
                                $model = new Game;
                                $data = $model->select("gid,game_name,game_img")->where(array('game_is_active' => 1))->exec();
                                $images = null;
                                while ($data->fetch()) {
                                    $images.="<li id='game" . $data->current()->gid . "'>" . ab_game_create_img($data->current()->game_img, 30, 30) . "</li>";
                                    ?><option value="<?= $data->current()->gid ?>" ><?= $data->current()->game_name ?></option><?php
                                }
                                ?>
                            </select><br/>
                            <ul style="display: none" id="images">
                                <?php echo $images; ?>
                            </ul>
                        </div>
                    </dd>

                    <dt><label> <?=L::forms_plug_url;?> </label></dt>
                    <dd><div><input type="url" name="url" id="url" value="" data-default="http://" required /></div></dd>


                    <dt><label> <?=L::global_status;?></label></dt>
                    <dd>
                        <div> 
                            <select name="status" id="status" style="width: 100px">                                                    
                                <option value="1"><?=L::global_enable;?></option>
                                <option value="0"><?=L::global_disable;?></option>
                            </select>  
                        </div>
                    </dd>
                    <dt></dt>
                    <dd> 
                        <div>
                            <input class="btn btn-success" type="submit" value="<?= addslashes(L::global_save);?>" style="width: 120px"/>
                            &nbsp;&nbsp;
                            <input class="btn" type="button" name="reset" value="<?= addslashes(L::forms_add_new_plug);?>" onclick="reset_form();
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
        <!-- /Add plugs -->

        <!-- plugs List -->
        <h3 class="heading"><?=L::forms_Plugs_list;?>
            <button class="pull-right btn btn-info  bt_add_new"   onclick=""><?=L::forms_add_new_plug;?></button> 
            <button class="pull-right btn btn-info  bt_cancel" style='display: none'   onclick=""><?=L::global_cancel;?></button> 
        </h3>
        <table id="dt_e" class="table table-striped table-bordered dTableR" >
            <thead>
                <tr>
                    <th>ID</th>
                    <th><?=L::global_image;?></th> 
                    <th><?=L::forms_game_name;?></th> 
                    <th><?=L::global_url;?></th>
                    <th><?=L::global_status;?></th>  
                    <th><?=L::global_action;?></th>  
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="dataTables_empty" colspan="7"><?=L::forms_loading_data;?></td>
                </tr>
            </tbody>
        </table>
        <!-- /plugs list -->


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
                    $('#form_div').fadeOut();

                    function format(state) {
                        var originalOption = state.element;
                        id = $(originalOption).attr('value');
                        if (!id)
                            return;
                        return "<img class='flag' src='" + $("#images").find("#game" + id).html() + "'/>&nbsp;&nbsp;&nbsp;" + state.text;
                    }
                    $("#gid").select2({
                        formatResult: format,
                        formatSelection: format,
                        escapeMarkup: function(m) {
                            return m;
                        }
                    });

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
                        aaSorting: [[0, 'desc']],
                        aoColumnDefs: [
                            {bSearchable: false, bVisible: false, aTargets: [0]},
                            {aTargets: [1], bSearchable: false, sWidth: '60px'},
                            {aTargets: [2], sWidth: '180px'},
                            {aTargets: [3]},
                            {bSortable: false, aTargets: [4], sWidth: '40px'},
                            {bSortable: false, aTargets: [5], sWidth: '70px'}
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
                                <button class="btn btn-mini sepV_a sall"><li class="icon-th-list"></li> <?=L::global_select_all;?></button>\n\
                                <button class="btn btn-mini sepV_a dall" style="display:none"><li class="icon-ban-circle"></li> <?=L::global_deselect_all;?></button>\n\
                                <button class="btn btn-mini sepV_a btn-danger mdel" style="display:none"><li class="icon-trash"></li> <?=L::global_delete_selected;?></button>\n\
                                </div>');

                    reg_select_all();
                    reg_deselect_all();
                    reg_multidelete();

                    $('.grabtradergames').click(function() {
                        $('.sysdetected').showLoading(loading_config);
                        $.ajax({
                            url: '<?= url::itself()->fulluri(array('grab' => 1)) ?>',
                            type: 'POST',
                            dataType: 'json',
                            data: {'sys': $(this).data('sys'), 'numgames': $(this).data('numgames')},
                            success: function(obj) {
                                $('.sysdetected').hideLoading();
                                if (obj.grabbed == 1) {
                                    $.sticky(obj.msg, {autoclose: 5000, position: "top-right", type: "st-success", speed: "fast"});
                                    oTable.fnReloadAjax();
                                } else {
                                    $.sticky(obj.msg, {autoclose: 5000, position: "top-right", type: "st-error", speed: "fast"});
                                }
                            }
                        });
                    });
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

                    if (typeof($.fn.select2) != 'undefined')
                    {
                        $("#gid").select2("data", {id: 0, text: ""});
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
                    $('#div_title').html('<?= addslashes(L::forms_add_new_plug);?>');


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
                            url: "<?= url::itself()->fulluri(array('save' => 1)) ?>",
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
                    } else {
                        $('.toolbar .mdel').fadeOut(300);
                        $('.toolbar .dall').fadeOut(300);
                    }
                }
                // Delete plugs Handler
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
                                    url: "<?= url::itself()->fulluri(array('mdel' => 1)) ?>",
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
                                    url: "<?= url::itself()->fulluri(array('del' => 1)) ?>",
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
                // Edit plugs Handler 
                function  reg_dt_edit() {
                    $('.edit').click(function() {
                        var eid = $(this).closest('td').find('.row_id').val();
                        reset_form();
                        $('#div_title').html('<?= addslashes(L::forms_edit_plug);?>');
                        open_form();
                        $('#myform').showLoading(loading_config);
                        $.ajax({
                            type: 'POST',
                            data: {'id': eid},
                            url: "<?= url::itself()->fulluri(array('edit' => 1)) ?>",
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
                                if (typeof($.fn.select2) != 'undefined') {
                                    $("#gid").select2("val", data.gid);
                                }
                            }
                        });
                    });
                }
</script>
<?php
get_footer();
?>