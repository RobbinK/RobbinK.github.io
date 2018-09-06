<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: request_comments.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:56
##########################################################
 */

### call header
abs_admin_inc(l_basic);
abs_admin_inc(l_hint);
abs_admin_inc(l_pengu_common);
abs_admin_inc(l_datatable);
abs_admin_inc(l_sticky);
abs_admin_inc(l_smoke);
abs_admin_inc(l_colorbox);
abs_admin_inc(l_yepnope);
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
                        <?=L::sidebar_exch_req;?>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /Navigation Menu -->


        <!-- Members List -->
        <h3 class="heading"><?=L::header_link_exchange_requests;?> </h3>
        <table id="dt_e" class="table table-striped table-bordered dTableR" >
            <thead>
                <tr> 
                    <th>ID</th>   
                    <th><?=L::forms_name;?></th>
                    <th><?=L::forms_email;?></th>
                    <th><?=L::global_time;?></th>
                    <th><?=L::forms_comment;?></th> 
                    <th><?=L::global_status;?></th> 
                    <th><?=L::forms_replied;?></th>
                    <th><?=L::global_action;?></th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td class="dataTables_empty" colspan="7"><?=L::forms_loading_data;?></td>
                </tr>
            </tbody>
        </table>
        <!-- /Members List -->

        <!-- Modal -->
        <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-width="30%">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel"></h3>
            </div>
            <div class="modal-body">
                <p></p>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true"><?=L::global_close;?></button>
                <button class="btn btn-primary"><?=L::forms_save_changes;?></button>
            </div>
        </div>

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
            aaSorting: [[4, 'desc']],
            aoColumnDefs: [
                {bSearchable: false, bVisible: false, aTargets: [0]},
                {aTargets: [1], sWidth: '100px'},
                {aTargets: [2], sWidth: '120px'},
                {aTargets: [3], sWidth: '70px'},
                {aTargets: [4]},
                {bSearchable: false, bSortable: true, aTargets: [5], sWidth: '10px'},
                {bVisible: true, bSearchable: false, bSortable: false, aTargets: [6], sWidth: '10px', sClass: 'center'},
                {bSearchable: false, bSortable: false, aTargets: [7], sWidth: '65px'}
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
                $('.tx-more').click(function(e) {
                    $.colorbox({
                        html: $('.tx-more').parent().find('.tx').html()
                    });
                    e.preventDefault();
                    return false;
                });

                dt_selection_stats();
                reg_dt_delete();
                reg_dt_observe();
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

    function reg_dt_delete() {
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
    function reg_dt_observe() {
        $('.observe').click(function() {
            var eid = $(this).closest('td').find('.row_id').val();
            replycm(eid);

        });
    }
    function replycm(id) {
        $.colorbox({
            href: '<?= url::itself()->url_nonqry() ?>?editcm=1&id=' + id,
            width: '700px',
            maxWidth: '98%',
            maxHeight: '98%',
            opacity: '0.2',
            loop: false,
            fixed: true
        });
        return false;

    }

</script>
<?php
get_footer();
?>