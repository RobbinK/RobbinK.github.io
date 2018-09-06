<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: bannedmembers.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:56
##########################################################
 */

### call header
abs_admin_inc(l_basic);
abs_admin_inc(l_pengu_common);
abs_admin_inc(l_datatable);
abs_admin_inc(l_sticky);
abs_admin_inc(l_smoke);
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
                        <?= L::sidebar_ban_mem ?>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /Navigation Menu -->


        <!-- Members List -->
        <h3 class="heading"><?= L::sidebar_ban_mem ?></h3>
        <table id="dt_e" class="table table-striped table-bordered dTableR" >
            <thead>
                <tr> 
                    <th>ID</th>
                    <th><?= L::forms_username ?></th>
                    <th><?= L::forms_group ?></th>
                    <th><?= L::forms_name ?></th>
                    <th><?= L::forms_register_date ?></th>
                    <th><?= L::forms_last_login ?></th>
                    <th><?= L::forms_total_logins ?></th> 
                    <th><?= L::global_enable ?></th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td class="dataTables_empty" colspan="7"><?= L::forms_loading_data; ?></td>
                </tr>
            </tbody>
        </table>
        <!-- /Members List -->


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
//                                    reg_uploaders();
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
                {aTargets: [1], sWidth: '13%'},
                {aTargets: [2], sWidth: '10%'},
                {aTargets: [3], sWidth: '13%'},
                {aTargets: [4], sWidth: '10%'},
                {aTargets: [5], sWidth: '7%'},
                {aTargets: [6], sWidth: '7%'},
                {aTargets: [7], sWidth: '7%'}
            ],
            bAutoWidth: false,
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
//                reg_dt_delete();
//                reg_dt_edit();
                reg_dt_row_click();
            }
        });

        $("div.toolbar").html('<div class="sepH_a" id="toolbar_inside">\n\
                                    <button class="btn btn-mini sepV_a sall"><li class="icon-th-list"></li> <?= L::global_select_all; ?></button>\n\
                                    <button class="btn btn-mini sepV_a dall" style="display:none"><li class="icon-ban-circle"></li> <?= L::global_deselect_all; ?></button>\n\
                                    <button class="btn btn-mini sepV_a btn-success mactive" style="display:none"><li class="splashy-contact_grey_add"></li> Active Selected</button>\n\
                                     </div>');

        reg_select_all();
        reg_deselect_all();
        reg_multiactive();
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
            $('.toolbar .mactive').fadeIn(300);
            $('.toolbar .dall').fadeIn(300);
        } else {
            $('.toolbar .mactive').fadeOut(300);
            $('.toolbar .dall').fadeOut(300);
        }
    }

    function reg_multiactive() {
        $('.toolbar .mactive').click(function() {
            var ids = [];
            $('#dt_e .row_selected').each(function() {
                id = $(this).find('input.row_id').val();
                ids.push(id);
            });
            smoke.confirm('<?= addslashes(L::alert_activating_msg);?>', function(e) {
                if (e) {
                    st1 = $.sticky('<?= addslashes(L::alert_activating_members);?>', {autoclose: false, position: "top-right", type: "st-info", speed: "fast"});
                    $.ajax({
                        type: 'POST',
                        data: {id: ids},
                        url: "<?= url::itself()->url_nonqry(array('mactive' => 1)) ?>",
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


</script>
<?php
get_footer();
?>