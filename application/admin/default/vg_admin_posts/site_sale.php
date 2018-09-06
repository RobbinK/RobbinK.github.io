<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: site_sale.php
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
                    <li><a href="<?= url::router('admindashboard'); ?>"><i class="icon-home"></i></a></li>
                    <li><?= L::header_marketplace; ?></li>
                    <?= L::header_arcades_for_sale; ?>
                </ul>
            </div>
        </nav>
        <!-- /Navigation Menu -->
        <!-- Page Lists -->
        <h3 class="heading"><?= L::header_arcades_for_sale; ?></h3> 
        <div class="row-fluid">
            <div class="span12">
                <div class="mbox">
                    <div class="tabbable">

                        <div class="tab-pane active" id="mbox_inbox">
                            <table data-msg_rowlink="a" class="table table_vam mbox_table dTableR" id="dt_e">
                                <thead>
                                    <tr> 
                                        <th>id</th>
                                        <th><i class="splashy-mail_light_down"></i></th>
                                        <th><?= L::global_subject; ?></th>
                                        <th><?= L::global_sender; ?></th>
                                        <th><?= L::global_time; ?></th> 
                                        <th><?= L::global_action; ?></th>
                                    </tr>
                                </thead>
                                <tbody>  
                                    <tr>
                                        <td class="dataTables_empty" colspan="5"><?= L::forms_loading_data; ?></td>
                                    </tr>
                                </tbody>
                            </table>     
                        </div> 

                    </div>
                </div>

            </div>
        </div>
        <!-- /Navigation Menu -->



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
        oTable = $('#dt_e').dataTable({
            "oLanguage": dataTablesLanguages,
            "sDom": "<'row'<'span6'<'dt_actions'>l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap_full",
            iDisplayLength: <?=datatable_ipp?>,
            aLengthMenu: [[10, 20, 50, -1], ['10', '20', '50', '<?= addslashes(L::global_all_rec);?>']],
            sAjaxSource: "<?= url::itself()->fulluri(array('dt' => 1)) ?>",
            bPaginate: true,
            bFilter: true,
            bSort: true,
            bProcessing: true,
            bServerSide: true,
            "aaSorting": [
                [4, "desc"]
            ],
            "aoColumns": [
                {bSearchable: false, bVisible: false, aTargets: [0]},
                {"bSortable": false, 'sWidth': '16px'},
                {"sType": "string"},
                {"sType": "string", 'sWidth': '100px'},
                {"sType": "eu_date", 'sWidth': '130px'},
                {"bSortable": false, 'sWidth': '40'}
            ],
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
                reg_dt_show();
                reg_dt_row_click();
                reg_dt_check_unread();
            }
        });


        $('#dt_e_wrapper .dt_actions').html('<div class="btn-group">\n\
                 <a href="javascript:void(0)" class="btn sall" title="<?= addslashes(L::global_select_all);?>"><i class="icon-th-list"></i></a>\n\
                 <a href="javascript:void(0);" class="delete_msg btn mdel" title="<?= addslashes(L::global_delete);?>" data-tableid="dt_e" style="display:none"><i class="icon-trash"></i></a>\n\
                 <a href="javascript:void(0);" class="delete_msg btn mread" title="<?= addslashes(L::forms_mark_as_read);?>" data-tableid="dt_e"  style="display:none" ><i class="splashy-mail_light_stuffed"></i></a>\n\
                </div>');
        reg_select_all();
        reg_multidelete();
        reg_multiread();
    });



    function dt_selection_stats() {
        if ($('#dt_e .row_selected').length) {
            $('#dt_e_wrapper .dt_actions .sall i').attr('class', 'icon-ban-circle');
            $('#dt_e_wrapper .dt_actions .mdel').fadeIn(300);
            $('#dt_e_wrapper .dt_actions .dall').fadeIn(300);
            $('#dt_e_wrapper .dt_actions .mread').fadeIn(300);
        } else {
            $('#dt_e_wrapper .dt_actions .sall i').attr('class', 'icon-th-list');
            $('#dt_e_wrapper .dt_actions .mdel').fadeOut(300);
            $('#dt_e_wrapper .dt_actions .dall').fadeOut(300);
            $('#dt_e_wrapper .dt_actions .mread').fadeOut(300);
        }
    }

    function reg_select_all() {
        $('#dt_e_wrapper .dt_actions .sall').click(function() {
            if ($(this).find('i').hasClass('icon-th-list'))
            {
                $(this).find('i').attr('class', 'icon-ban-circle');
                $('table.table tbody tr').addClass('row_selected');
                dt_selection_stats();
            } else {
                $(this).find('i').attr('class', 'icon-th-list');
                $('table.table tbody tr').removeClass('row_selected');
                dt_selection_stats();
            }
        });
    }

    function reg_dt_check_unread() {
        $('table.table tbody tr').each(function() {
            if ($(this).find('i.splashy-mail_light_down').length > 0)
                $(this).addClass('unread');
        });
    }


    function reg_multiread() {
        $('#dt_e_wrapper .dt_actions .mread').click(function() {
            var ids = [];
            $('#dt_e .row_selected').each(function() {
                id = $(this).find('input.row_id').val();
                ids.push(id);
            });
            st1 = $.sticky('<?= addslashes(L::alert_marking_as_unread);?>', {autoclose: false, position: "top-right", type: "st-info", speed: "fast"});
            $.ajax({
                type: 'POST',
                data: {id: ids},
                url: "<?= url::itself()->url_nonqry(array('unread' => 1)) ?>",
                success: function(result) {
                    $.stickyhide(st1.id);
                    if (result != '')
                        $.sticky(result, {autoclose: 5000, position: "top-right", type: "st-success", speed: "fast"});
                    else
                        $.sticky('<?= addslashes(L::alert_no_response);?>', {autoclose: 5000, position: "top-right", type: "st-error", speed: "fast"});
                    oTable.fnStandingRedraw();
                }
            });
            return false;
        });
    }

    function reg_multidelete() {
        $('#dt_e_wrapper .dt_actions .mdel').click(function() {
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
                            if (result != 'undefined')
                                $.sticky(result, {autoclose: 5000, position: "top-right", type: "st-success", speed: "fast"});
                            else
                                $.sticky('<?= addslashes(L::alert_no_response);?>', {autoclose: 5000, position: "top-right", type: "st-error", speed: "fast"});
                            oTable.fnStandingRedraw();
                        }
                    });
                }
            }, {});
            return false;
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
                            if (result != 'undefined')
                                $.sticky(result, {autoclose: 5000, position: "top-right", type: "st-success", speed: "fast"});
                            else
                                $.sticky('<?= addslashes(L::alert_no_response);?>', {autoclose: 5000, position: "top-right", type: "st-error", speed: "fast"});
                            oTable.fnStandingRedraw();
                        }
                    });
                }
            }, {});
        });
    }

    function  reg_dt_show() {
        $('.open').unbind('click').click(function() {
            var id = $(this).closest('td').find('.row_id').val();
            $.colorbox({
                href: window.myself_url_nonqry + '?showid=' + id,
                maxWidth: '70%',
                maxHeight: '60%',
                opacity: '0.2',
                loop: false,
                fixed: true
            });
            return false;
        });
    }
</script>
<?php
get_footer();
?>