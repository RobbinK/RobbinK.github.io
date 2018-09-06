<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: massemail.php
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
                        Mass Email
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /Navigation Menu -->

        <!-- Add Category --> 
        <div id="form_div" class="tab-content" style="visibility:visible ">
            <h3 class="heading" id="div_title"></h3>

            <form id="myform" method="post" action="<?= url::itself()->fulluri() ?>" class="form_validation_reg" novalidate="novalidate">
                <input type="hidden" name="cid" id="cid" class="edit_id" value="" />
                <dl class="dl-horizontal"> 

                    <dt><label> From (Replyto): </label></dt>
                    <dd><div><input type="text" name="from" id="from" value="" required /></div></dd>

                    <dt><label> To (BCC) : </label></dt>
                    <dd><div><input type="text" name="to" id="to" value="" required /></div></dd>

                    <dt><label> Subject: </label></dt>
                    <dd><div><input type="text" name="subject" id="subject"  value="" /></div></dd>

                    <dt><label> Attachment 1 : </label></dt>
                    <dd>
                        <div>
                            <input type="button" id="at1" class="btn btn-large clearfix" value="<?= addslashes(L::forms_select_file);?>"/>
                            <input type="hidden" name="attach1" id="attach1"  value="" />
                            <span class="help-inline"><i>PNG, JPG, or GIF (500K max file size)</i></span>
                            <div id="errormsg_at1" class="clearfix redtext"></div>	              
                            <div id="pic-progress-wrap_at1" class="progress-wrap" style="margin-bottom:10px;"></div>	
                            <div id="filebox_at1" class="clear" style="position: relative;height:60px;padding-top:0px;padding-bottom:10px;"></div>
                    </dd>

                    <dt><label> Attachment 2 : </label></dt>
                    <dd>
                        <div>
                            <input type="button" id="at2" class="btn btn-large clearfix" value="<?= addslashes(L::forms_select_file);?>"/>
                            <input type="hidden" name="attach2" id="attach2"  value="" />
                            <span class="help-inline"><i>PNG, JPG, or GIF (500K max file size)</i></span>
                            <div id="errormsg" class="clearfix redtext"></div>	              
                            <div id="pic-progress-wrap" class="progress-wrap" style="margin-bottom:10px;"></div>	
                            <div id="filebox" class="clear" style="position: relative;height:60px;padding-top:0px;padding-bottom:10px;"></div>
                    </dd>

                    <dd>
                        <div>
                            <select name="is_active" id="is_active" style="width: 100px">                                                    
                                <option value="1"><?= L::global_enable; ?></option>
                                <option value="0"><?= L::global_disable; ?></option>
                            </select> 
                        </div>
                    </dd>
                    <dt></dt>
                    <dd> 
                        <div>
                            <input class="btn btn-success" type="submit" value="<?= addslashes(L::global_save);?>" style="width: 120px"/>
                            &nbsp;&nbsp;
                            <input class="btn" type="button" name="reset" value="Reset" onclick="reset_form();
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
        <!-- /Add Category -->

        <!-- Categories List -->
        <h3 class="heading">Categories List
            <button class="pull-right btn btn-info  bt_add_new"   onclick="">Add New Category</button> 
            <button class="pull-right btn btn-info  bt_cancel" style='display: none'   onclick=""><?= L::global_cancel; ?></button> 
        </h3>
        <table id="dt_e" class="table table-striped table-bordered dTableR" >
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>Meta Keywords</th>
                    <th>Meta Description</th>
                    <th>Featured</th>
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
        <!-- /Categories List -->


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
                                    reg_uploaders();
                                    $('#form_div').fadeOut(0);
                                    oTable = $('#dt_e').dataTable({
                                        bInfo: true,
                                        bLengthChange: true,
                                        sPaginationType: "bootstrap_full", /*full_numbers , two_button*/
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
                                            {aTargets: [1]},
                                            {aTargets: [2]},
                                            {aTargets: [3]},
                                            {aTargets: [4]},
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
                                    $('.form_validation_reg').find('input:text, input[type=url], input[type=hidden], input:password, input:file, select, textarea').val('');
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

                                    //filebox
                                    close_uploader_imagebox('#filebox');
                                    fValidation.resetForm();
                                    $('.form_validation_reg div').removeClass("f_error");
                                    $('#div_title').html('Add New Category');
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
                                        reset_form();
                                        $('#div_title').html('Edit');
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
                                                setTimeout(function() {
                                                    if (typeof(data.icon) != 'undefined' && data.icon != '') {
                                                        open_uploader_imagebox('#filebox', 'icon', data.icon);
                                                        $('#icon').val(data.icon);
                                                    }
                                                }, 600);
                                            }
                                        });
                                    });
                                }

                                function reg_uploaders() {

                                    var btn = document.getElementById('upload-btn'),
                                            wrap = document.getElementById('pic-progress-wrap'),
                                            filebox = document.getElementById('filebox'),
                                            errBox = document.getElementById('errormsg');

                                    var uploader = new ss.SimpleUpload({
                                        button: btn,
                                        url: '<?= url::itself()->url_nonqry() ?>',
                                        progressUrl: '<?= url::itself()->url_nonqry() ?>',
                                        name: 'uploadfile',
                                        multiple: false,
                                        maxUploads: 2,
                                        maxSize: 200*1024,
                                        allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
                                        accept: 'image/*',
                                        debug: false,
                                        hoverClass: 'btn-hover',
                                        focusClass: 'active',
                                        disabledClass: 'disabled',
                                        responseType: 'json',
                                        onExtError: function(filename, extension) {
                                            alert('<?= addslashes(L::alert_invalid_image_format);?>');
                                        },
                                        onSizeError: function(filename, fileSize) {
                                            alert('<?= addslashes(L::alert_invalid_file_size);?>. (<?= L::forms_max_file_size; ?> : 200 <?= L::global_kilo_byte ?>)');
                                        },
                                        onSubmit: function(filename, ext) {
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
                                            btn.value = '<?= addslashes(L::forms_select_another_file);?>';
                                        },
                                        onComplete: function(file, response) {
                                            if (response.success === true) {
                                                open_uploader_imagebox('#filebox', 'icon', response.file);
                                                $('#icon').val(response.file);
                                            } else {
                                                errBox.innerHTML = response.msg;
                                            }
                                        }
                                    });

                                }

                                function close_uploader_imagebox(jQid) {
                                    if ($(jQid).hasClass('noimg')) {
                                        $(jQid).find('img.thumbnail').siblings().remove();
                                        $(jQid).find('img.thumbnail').show();
                                    } else if ($(jQid).length) {
                                        $(jQid).html('').fadeOut();
                                    }
                                }
                                function open_uploader_imagebox(jQid, dbField, filename) {
                                    if (!$(jQid).length)
                                        return false;
                                    if (filename == '' || typeof(filename) == undefined || filename == null) {
                                        close_uploader_imagebox(jQid);
                                        return false;
                                    }
                                    $helpi = $(jQid).parent().find('span.help-inline');
                                    if (!$helpi.find('.shoimageloading').length) {
                                        $("<img src='<?= static_url() ?>/images/loading/loading-9.gif' class='shoimageloading'/>").css({margin: 10}).appendTo($helpi);
                                    }

                                    $(jQid).fadeOut(300, function() {
                                        html = '<img src="<?= url::itself()->url_nonqry() ?>?showimage=' + encodeURIComponent(filename) + '" rel="clbox" >';
                                        html += '<span style="top: 0px; position: absolute; margin:0 10px;">' + filename + '</span>';
                                        html += '<a style="position: absolute; top: 23px; margin:0 10px;cursor:pointer;" onclick="delete_file(\'' + dbField + '\');return false;" class="btn-danger btn-mini deleteicon">Delete</a>';
                                        if ($(jQid).hasClass('noimg')) {
                                            $(jQid).find('img.thumbnail').hide();
                                            $(jQid).find('img.thumbnail').siblings().remove();
                                            $(jQid).append(html);
                                            $(jQid).find('img:not(.thumbnail)').imagesLoaded(function() {
                                                reg_colorbox('auto');
                                                $(jQid).fadeIn(300);
                                                $(jQid).parent().find('.shoimageloading').remove();
                                            });
                                        }
                                        else {
                                            $(jQid).html(html);
                                            $(jQid).find('img:not(.thumbnail)').imagesLoaded(function() {
                                                reg_colorbox('auto');
                                                $(jQid).fadeIn(300);
                                                $(jQid).parent().find('.shoimageloading').remove();
                                            });
                                        }
                                    });
                                }



                                function  delete_file(db_field) {
                                    var did = $('#myform .edit_id').val();
                                    smoke.confirm('<?= addslashes(L::alert_del_file_warning);?>', function(e) {
                                        if (e) {
                                            st1 = $.sticky('<?= addslashes(L::alert_deleting_file);?>', {autoclose: false, position: "top-right", type: "st-info", speed: "fast"});
                                            $.ajax({
                                                type: 'POST',
                                                data: {'id': did, 'db_field': db_field},
                                                url: "<?= url::itself()->url_nonqry(array('del_file' => 1)) ?>",
                                                success: function(result) {
                                                    $.stickyhide(st1.id);
                                                    obj = JSON.parse(result);
                                                    if (obj.delete_code === 1)
                                                        $.sticky(obj.delete_txt, {autoclose: 5000, position: "top-right", type: "st-success", speed: "fast"});
                                                    else
                                                        $.sticky(obj.delete_txt, {autoclose: 5000, position: "top-right", type: "st-error", speed: "fast"});
                                                    //$.sticky(result, {autoclose: 5000, position: "top-right", type: "st-success", speed: "fast"});
                                                    pid = $('#' + db_field).parent().find("div[id^='filebox']").attr('id');
                                                    close_uploader_imagebox('#' + pid);

                                                }
                                            });
                                        }
                                    }, {});
                                }

                                function reg_colorbox(size) {
                                    size = size || 'auto';
                                    $('img[rel=clbox]').unbind('click').click(function(e) {
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



</script>
<?php
get_footer();
?>