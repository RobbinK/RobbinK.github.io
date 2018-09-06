<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: games2(unused).php
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
abs_admin_inc(l_multiselect);
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
                        Manage Games (type two)
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /Navigation Menu --> 
        <!-- Game List -->

        <!-- /Game List -->


    </div>
</div>




<?php
get_sidebar();
get_footer('_script');
?>

<script type="text/javascript">
    var debug = true;
    var loading_config = {
        'indicatorZIndex': 990,
        'overlayZIndex': 990
    };

    $(document).ready(function() {

    });

    function reg_xhr_setup() {
        $.xhrPool = [];
        $.xhrPool.abortAll = function() {
            $(this).each(function(idx, jqXHR) {
                jqXHR.abort();
            });
            $.xhrPool.length = 0
        };

        $.ajaxSetup({
            beforeSend: function(jqXHR) {
                $.xhrPool.push(jqXHR);
            },
            complete: function(jqXHR) {
                var index = $.inArray(jqXHR, $.xhrPool);
                if (index > -1) {
                    $.xhrPool.splice(index, 1);
                }
            }
        });

        $.ajaxSetup({
            error: function(x, e) {
                if (x.status == 500) {
                    alert('Internel Server Error.');
                    abortAllAjax();
                }
            }
        });
    }

    function abortAllAjax() {
        $.xhrPool.abortAll();
        $('.loading-indicator-overlay,.loading-indicator').remove();
        $('.sticky-queue').remove();
        $('.shoimageloading').remove();
    }



    function reg_select_all() {
    }

    function reg_deselect_all() {
    }

    function reg_multidelete() {
        $('.toolbar .mdel').click(function() {
            var ids = [];
            /*
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
             */
        });
    }


    function  reg_dt_delete() {
        $('.del').click(function() {
            /* 
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
             */
        });
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
    function reg_showswf_colorbox() {
        $('a.showswf').unbind('click').click(function() {
            var s = $(this).attr('href');
            try {
                if ($(s).length)
                    s = $(s).val();
            } catch (e) {
            }
            $.colorbox({
                href: '<?= url::itself()->url_nonqry() ?>?showswf=' + encodeURIComponent(s),
                maxWidth: '98%',
                maxHeight: '98%',
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