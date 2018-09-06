<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: latest_feeds.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:56
##########################################################
 */

### call header
abs_admin_inc(l_basic);
abs_admin_inc(l_colorbox);
abs_admin_inc(l_validate);
abs_admin_inc(l_datatable);
abs_admin_inc(l_smoke);
abs_admin_inc(l_unserializeForm);
abs_admin_inc(l_yepnope);
abs_admin_inc(l_bootstrap_modal);
get_header();
#**************
$feed_thumb_size = _get_theme_setting('feed_thumb_size');

switch ($feed_thumb_size) {
    case '100x100':$theight = '49px';
        break;
    case '150x150':$theight = '49px';
        break;
    case '90x120':$theight = '65px';
        break;
    case '180x135':$theight = '37px';
        break;
    default:$theight = '49px';
}
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
                        <?= L::sidebar_new_feeds; ?>
                    </li>
                </ul>
            </div>
        </nav> 
        <!-- /Navigation Menu -->
        <!--Attention--> 
        <?php
        if (!convert::to_bool(setting::get_data('feed_auto_downloader', 'val'))):
            ?>
            <div class="alert alert-block alert-error fade in news-attention"  >
                <a class="close" data-dismiss="alert">×</a>
                <?= L::alert_auto_feed_downloader; ?> <a href="<?= url::router('admin-feedsetting') ?>"><?= L::sidebar_feed_set; ?></a>
            </div> 
            <?php
        endif;
        ?>
        <div class="alert alert-success"> 
            <?= L::alert_same_thumb_size; ?>  <?= $feed_thumb_size ?> <br/>
            <?= L::alert_change_thumb_size; ?> <a href="<?= url::router('admin-feedsetting') ?>"><?= L::forms_feed_setting; ?> </a>
        </div>
        <!--end Attention-->
        <!-- form --> 
        <div id="form_div" class="tab-content" style="visibility:visible ">
            <div class="row-fluid">
                <form id="myform" method="post" action="<?= url::itself()->url_nonqry() ?>">
                    <div class="span12 sepH_c">  
                        <lable><?= L::global_source; ?></lable>
                        <select id="source" class="input-medium" style="min-width: 160px">
                            <option value="all"><?= L::global_all_rec; ?></option>
                            <?php
                            if (isset($sources) && is_array($sources)) {
                                foreach ($sources as $source)
                                    echo "<option>{$source}</option>";
                            }
                            ?>
                        </select> 
                        <lable><?= L::forms_category; ?></lable>
                        <select class="input-medium" id="cat" >
                            <option value="all"><?= L::global_all_rec; ?></option>
                            <?php
                            if (isset($cats) && is_array($cats)) {
                                foreach ($cats as $cat)
                                    echo "<option>{$cat}</option>";
                            }
                            ?>
                        </select> 
                        <lable><?= L::forms_ingame_ads_included; ?></lable>
                        <select id="withoutad" class="input-medium">
                            <option value="0"><?= L::forms_not_important; ?></option>
                            <option value="1"><?= L::global_state_no; ?></option>
                        </select> 
                        <button type="button" class="btn btn-info" id="generate_rep" style="margin: 0 0 10px 10px;"><?= L::forms_filter_feeds; ?></button>
                    </div> 
                </form>
            </div> 
        </div>


        <h3 class="heading"><?= L::forms_feed_list; ?> </h3>

        <table id="dt_e" class="table table-striped table-bordered dTableR" >
            <thead>
                <tr>
                    <th>ID</th> 
                    <th>FID</th> 
                    <th><?= L::global_image; ?></th>
                    <th><?= L::forms_game_name; ?></th>
                    <th><?= L::forms_categories; ?></th>    
                    <th><?= L::global_the_date; ?></th>    
                    <th><?= L::forms_ingame_ads; ?></th>    
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
        <!-- /records -->

        <!--Modal-->
        <div id="feed-modal" class="modal hide fade" tabindex="-1">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3><?= L::forms_install; ?></h3>
            </div>
            <div class="modal-body"> 
            </div>
            <div class="modal-footer">  
                <div class="btn-group dropup text-left">
                    <button data-toggle="dropdown" class="btn btn-success dropdown-toggle"><?= L::global_action; ?> <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><a href="javascript:void(0);"  class="install"><?= L::forms_install_activate; ?></a></li>
                        <li><a href="javascript:void(0);"  class="installedit"><?= L::forms_install_edit; ?></a></li>
                        <li><a href="javascript:void(0);"   class="installqueue" ><?= L::forms_install_queue; ?></a></li>
                    </ul>
                </div> 
                <button type="button" data-dismiss="modal" class="btn"><?= L::global_cancel; ?></button>
            </div>
        </div>
        <!----->
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
    lable {
        margin: 0 0 0 20px;
    }
    .thumbnail{
        min-height: <?= $theight ?>;
    }
</style>
<script type="text/javascript">
    var oTable;
    var loading_config = {
        'indicatorZIndex': 990,
        'overlayZIndex': 990
    };

    $(document).ready(function () {
        oTable = $('#dt_e').dataTable({
            bInfo: true,
            bLengthChange: true,
            sPaginationType: "bootstrap_full", /*full_numbers , two_button*/
            aLengthMenu: [[30, 50, 100, -1], ['30', '50', '100', 'All']],
            iDisplayLength: 30,
            bPaginate: true,
            bFilter: true,
            bSort: true,
            bProcessing: true,
            bServerSide: true,
            sAjaxSource: "<?= url::itself()->fulluri(array('dt' => 1)) ?>",
            aaSorting: [[0, 'desc']],
            aoColumnDefs: [
                {bSearchable: false, bVisible: false, aTargets: [0]},
                {bSearchable: false, bVisible: false, aTargets: [1]},
                {aTargets: [2], sWidth: '60px'},
                {aTargets: [3]},
                {aTargets: [4]},
                {aTargets: [5], sWidth: '75px'},
                {aTargets: [6], sWidth: '80px', sClass: 'center', bSearchable: false, },
                {aTargets: [7], sWidth: '90px', sClass: 'center'},
                {bSortable: false, aTargets: [8], sWidth: '50px'}
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
                reg_dt_row_click();
                reg_dt_install();
                reg_colorbox();
            }
        });

        $("div.toolbar").html('<div class="sepH_a" id="toolbar_inside">\n\
                                <button class="btn btn-mini btn-success sepV_a sync" style="min-width: 75px;"><li class="icon-retweet icon-white"></li> Grab </button>\n\
                                <button class="btn btn-mini sepV_a sall"><li class="icon-th-list"></li> <?= L::global_select_all; ?></button>\n\
                                <button class="btn btn-mini sepV_a dall" style="display:none"><li class="icon-ban-circle"></li> <?= L::global_deselect_all; ?></button>\n\
                                <div class="btn-group act text-left">\n\
                                    <button data-toggle="dropdown" style="width:75px" class="btn btn-mini btn-info dropdown-toggle"><?= L::global_action; ?> <span class="caret"></span></button>\n\
                                    <ul class="dropdown-menu">\n\
                                        <li><a href="javascript:void(0);" class="multins finstall"><i class="icon-circle-arrow-down"></i> <?= L::forms_install_activate; ?></a></li>\n\
                                        <li><a href="javascript:void(0);" class="multins finstallqueue"><i class="icon-download"></i> <?= L::forms_install_queue; ?></a></li>\n\
                                        <li><a href="javascript:void(0);" class="mdel"><i class="icon-ban-circle"></i> <?= L::global_remove; ?></a></li>\n\
                                    </ul>\n\
                                </div>\n\
                              </div>');
        reg_select_all();
        reg_deselect_all();
        reg_multidelete();
        reg_multiinstall();

        $('.sync').click(function () {
            st1 = $.sticky('Grabbing new games from feed sources..', {autoclose: false, position: "top-right", type: "st-info", speed: "fast"});
            $.ajax({
                type: 'get',
                url: "<?= url::itself()->url_nonqry(array('forcesync' => 1)) ?>",
                data: {forcesync: 1},
                success: function () {
                    $.stickyhide(st1.id);
                    oTable.fnReloadAjax('<?= url::itself()->fulluri(array('dt' => 1)) ?>');
                }

            });
            return false;
        });

        $('#generate_rep').click(function () {
            var source = $('#source').val();
            var cat = $('#cat').val();
            var withoutad = $('#withoutad').val();
            oTable.fnReloadAjax('<?= url::itself()->fulluri(array('dt' => 1)) ?>' + '&source=' + source + '&cat=' + cat + '&withoutad=' + withoutad);
            return false;
        });
    });
    function reset_form() {
        $('#myform').find('input:text, input[type=url],input[type=hidden], input:password, input:file, select, textarea').val('');
        $('#myform').find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
        //tinymce
        if (typeof (tinyMCE) != 'undefined') {
            $('textarea.tinymce').each(function () {
                tinyMCE.get($(this).attr('id')).setContent('');
                tinyMCE.DOM.setStyle(tinyMCE.DOM.get($(this).attr('id') + '_ifr'), 'height', 120 + 'px');
            });
        }
        //select
        $('#myform').find('select').each(function () {
            $(this).find('option:first').attr('selected', 'true');
        });

        //date
        $('#myform').find('input').each(function () {
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

        //default
        $('#myform').find('input').each(function () {
            if ($(this).attr('data-default')) {
                $(this).val($(this).data('default'));
            }
        });
    }


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
            $('.toolbar .dall').fadeIn(300);
            $('.toolbar .act').fadeIn(300);
        } else {
            $('.toolbar .dall').fadeOut(300);
            $('.toolbar .act').fadeOut(300);
        }
    }
    // Delete Link Handler
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
            return false;
        });

    }



    function reg_dt_row_click() {
        $('#dt_e tbody tr').click(function () {
            $(this).toggleClass('row_selected');
            dt_selection_stats();
        });
    }

    function reg_colorbox() {
        $('img[rel=clbox]').unbind('click').click(function (e) {
            e.stopPropagation();
            $.colorbox({
                href: $(this).attr('src'),
                photo: true,
                maxWidth: '90%',
                maxHeight: '90%',
                opacity: '0.2',
                loop: false,
                fixed: true
            });
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
    window.feedJobs_afterinstall = [];
    function reg_dt_install() {
        $('.ins').click(function () {
            window.feedJobs_afterinstall.push("oTable.fnStandingRedraw();");
            var eid = $(this).closest('td').find('.row_fid').val();
            var $modal = $('#feed-modal');
            $('body').modalmanager('loading');
            setTimeout(function () {
                $modal.find('.modal-body').load("<?= url::router('adminopenfeed') ?>" + '?openg&id=' + eid, function () {
                    $modal.modal({height: 330, width: 600});
                });
            }, 100);
            return false;
        });
    }

    function reg_multiinstall() {
        $('.toolbar .multins').unbind('click').click(function () {
            $('[data-toggle="dropdown"]').parent().removeClass('open');
            install_data = {};

            if ($(this).hasClass('finstall')) {
                install_data['active'] = 1;
            } else if ($(this).hasClass('finstallqueue')) {
                install_data['active'] = 0;
            }

            var $cn = 0;
            $('#dt_e .row_selected').each(function () {
                var $this = $(this);
                if (!$this.find('.ins').length) {
                    $this.removeClass('row_selected');
                    dt_selection_stats();
                } else {
                    $cn++;
                    var id = $this.find('input.row_fid').val();
                    var x = $this.find('td').eq(-2);
                    x.html('<img  class="loading" src="' + window.template_url + '/img/loading.gif">');

                    $.ajax({
                        type: 'POST',
                        data: $.extend({id: id}, install_data),
                        url: "<?= url::itself()->url_nonqry(array('install' => 1)) ?>",
                        success: function (result) {
                            if (result == 1) {
                                x.html('<span class="text-success">Installed</span>');
                                $this.find('.ins').remove();
                                x.closest('tr').removeClass('row_selected');
                            }
                            else if (result == -1) {
                                x.html('<span class="text-info">Already Installed</span>');
                                $this.find('.ins').remove();
                                x.closest('tr').removeClass('row_selected');
                            }
                            else
                                x.html('<span class="text-error"><?= L::forms_not_installed; ?></span>');
                        }
                    });
                }
            });
            $(document).ajaxStop(function () {
                if ($cn > 0) {
                    abs_cache.clean_mysql();
                    dt_selection_stats()
                    $cn = 0;
                }
            });
            return false;
        });
    }

</script>
<?php
get_footer();
?>