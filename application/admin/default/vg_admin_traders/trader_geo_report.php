<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: trader_geo_report.php
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
                        <a href="<?= url::router('admin-poolitraders'); ?>"><?=L::sidebar_trd_mng;?></a>
                    </li>
                    <li>
                        <?=L::sidebar_geo_rep;?>
                    </li>
                </ul>
            </div>
        </nav>


        <font style="font:15px 'PT sans';"><?=L::dashboard_trader;?> </font> 
        <span class="label label-success sepH_c">
            <font style="font:15px 'PT sans';"><?= $trader_title ?></font>
        </span> 

        <!-- /Navigation Menu --> 
        <table id="dt_e" class="table table-striped table-bordered dTableR" >
            <thead>
                <tr> 
                    <th><?=L::global_country;?></th>
                    <th><?=L::forms_in_today;?></th>
                    <th><?=L::forms_out_today;?></th> 
                    <th><?=L::forms_total_in;?></th>  
                    <th><?=L::forms_total_out;?></th>  
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td class="dataTables_empty" colspan="7"><?=L::forms_loading_data;?></td>
                </tr>
            </tbody>
        </table>
        <!-- /records -->

    </div>
</div>


<?php
get_sidebar();
get_footer('_script');
?>


<script type="text/javascript">
    var oTable;
    var loading_config = {
        'indicatorZIndex': 990,
        'overlayZIndex': 990
    };

    $(document).ready(function() {
        reset_form();

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
            bServerSide: false,
            sAjaxSource: "<?= url::itself()->fulluri(array('dt' => 1)) ?>",
            aaSorting: [[1, 'desc']],
            aoColumnDefs: [
                {aTargets: [0], sWidth: '100px'},
                {aTargets: [1], sWidth: '50px', "sType": "currency"},
                {aTargets: [2], sWidth: '50px', "sType": "currency"},
                {aTargets: [3], sWidth: '50px', "sType": "currency"},
                {aTargets: [4], sWidth: '50px', "sType": "currency"}
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
            }
        });
    });


    function reset_form() {
        $('#myform').find('input:text, input[type=url],input[type=hidden], input:password, input:file, select, textarea').val('');
        $('#myform').find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
        //tinymce
        if (typeof(tinyMCE) != 'undefined') {
            $('textarea.tinymce').each(function() {
                tinyMCE.get($(this).attr('id')).setContent('');
                tinyMCE.DOM.setStyle(tinyMCE.DOM.get($(this).attr('id') + '_ifr'), 'height', 120 + 'px');
            });
        }
        //select
        $('#myform').find('select').each(function() {
            $(this).find('option:first').attr('selected', 'true');
        });

        //date
        $('#myform').find('input').each(function() {
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
        $('#myform').find('input').each(function() {
            if ($(this).attr('data-default')) {
                $(this).val($(this).data('default'));
            }
        });
    }




</script>
<?php
get_footer();
?>