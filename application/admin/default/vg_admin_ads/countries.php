<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: countries.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:56
##########################################################
 */

### call header
abs_admin_inc(l_basic);
abs_admin_inc(l_datepicker);
abs_admin_inc(l_validate);
abs_admin_inc(l_sticky);
abs_admin_inc(l_smoke);
abs_admin_inc(l_unserializeForm);
abs_admin_inc(l_timepicker);
get_header();
#**************
$countries = require app_path() . '/lib/agent/countries.php';
?>

<style> 
    .btn-mini {
        padding: 3px 5px;
    }
</style>
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
                        <a href="<?= url::router('admin-zone'); ?>"><?= L::sidebar_mng_ad_zone; ?></a> 
                    </li>
                    <li>
                        <a href="<?= url::router('admin-ads'); ?>"> <?= L::forms_manage_ads_for; ?> ... </a>                       
                    </li>
                    <li>
                        <?= L::global_countries; ?>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /Navigation Menu -->
        <div id="w_sort02" class="w-box">
            <?php
            if (isset($_GET['id'])):
                $country = explode(',', $data);
                ?>
                <div class="w-box-header" style="height:40px;padding-top: 5px">
                    <div> 
                        <a id="multi_upload_browse" class="btn btn-mini plupload_add sepV_b" href="<?= url::router('admin-ads')->fulluri(array('zone_id' => $zone['id'])) ?>"  style="position: relative; z-index: 0;">
                            <i class="splashy-arrow_medium_<?= lang_isrtl() ? 'right' : 'left' ?>"></i>  <?= L::global_back; ?> 
                        </a> 

                        <button id="multi_upload_browse"    value="save" class="btn btn-mini plupload_add sepV_b save" style="position: relative; z-index: 0;" ><i class="splashy-document_a4_okay"></i> <?= L::global_save; ?> </button>
                        <button id="multi_upload_browse" class="btn btn-mini plupload_add sepV_b save" ><i class="splashy-hcards_up"></i> <?= L::global_save_continue; ?></button>

                    </div>
                </div>
                <div class="w-box-content" style="float: left;width:100%;padding: 10px 0">
                    <input type="hidden" id="id" value="<?= $_GET['id'] ?>"/>
                    <div style="float: left;width:300px; padding:  0 30px" >
                        <div class="inside">
                            <table class="table table-striped table-bordered table-condensed " >
                                <thead>
                                    <tr>
                                        <th colspan="3"><?= L::forms_t1_countries; ?></th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td width="25"><input type="checkbox" class="checkall"/> </td>
                                        <td style='width:19px'></td>
                                        <td><b><?= L::global_country; ?></b></td>
                                    </tr>
                                    <?php foreach ($countries as $k => $v): ?>
                                        <?php if ($v['tier'] == 1): ?>
                                            <tr>
                                                <td> <input type="checkbox" class="con"  name="<?= $k ?>" <?= in_array($k, $country) ? 'checked=true' : null ?>/></td>
                                                <td><i class="flag-<?= $k ?>"></i></td>
                                                <td><?= $v['country'] ?></td>
                                            </tr>
                                        <?php endif;
                                        ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <!--tier 2-->
                        <div class="inside" style="padding-top: 20px">
                            <table class="table table-striped table-bordered table-condensed" >
                                <thead>
                                    <tr>
                                        <th colspan="3"><?= L::forms_t2_countries; ?></th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td width="25"><input type="checkbox" class="checkall"/> </td>
                                        <td style='width:19px'></td>
                                        <td><b><?= L::global_country; ?></b></td>
                                    </tr>
                                    <?php foreach ($countries as $k => $v): ?>
                                        <?php if ($v['tier'] == 2): ?>
                                            <tr>
                                                <td> <input type="checkbox" class="con"  name="<?= $k ?>" <?= in_array($k, $country) ? 'checked=true' : null ?>/></td>
                                                <td><i class="flag-<?= $k ?>"></i></td>
                                                <td><?= $v['country'] ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div style="float: left;width:300px;padding: 0 30px">
                        <!--tier 3---->
                        <div class="inside">
                            <table class="table table-striped table-bordered table-condensed" >
                                <thead>
                                    <tr>
                                        <th colspan="3"><?= L::forms_t3_countries; ?></th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td width="25"><input type="checkbox" class="checkall"/> </td>
                                        <td style='width:19px'></td>
                                        <td><b><?= L::global_country; ?></b></td>
                                    </tr>
                                    <?php foreach ($countries as $k => $v): ?>
                                        <?php if ($v['tier'] == 3 && !in_array($k, array('01', 'cc'))): ?>
                                            <tr>
                                                <td> <input type="checkbox" class="con"  name="<?= $k ?>" <?= in_array($k, $country) ? 'checked=true' : null ?>/></td>
                                                <td><i class="flag-<?= $k ?>"></i></td>
                                                <td><?= $v['country'] ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table> 
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div> 
<?php
get_sidebar();
get_footer('_script');
?>
<script type="text/javascript">
    $(document).ready(function() {
        $('.save').attr('disabled', true);

        $(".con").change(function() {
            $('.save').attr('disabled', false);
        });

        $(".checkall").change(function() {
            $('.save').attr('disabled', false);
        });

        $('.save').click(function() {
            var i = 0;
            var con = new Array();
            $("input:checkbox[class=con]:checked").each(function()
            {
                con[i] = $(this).attr('name');
                i++;
            });
            data = {
                id: $('#id').val(),
                cons: con,
                btnsave: trim($(this).html())
            }
            $.ajax({
                type: 'POST',
                data: data,
                url: "<?= url::itself()->url_nonqry(array('save' => 1)) ?>",
                success: function(result) {
                    obj = JSON.parse(result);
                    if (obj.save_code === 1) {
                        $.sticky(obj.save_txt, {autoclose: 5000, position: "top-right", type: "st-success", speed: "fast"});
                        if (obj.url) {
                            $(document).delay(300, function() {
                                document.location.href = obj.url;
                            });
                        }
                        $('.save').attr('disabled', true);
                    }
                    else {
                        $.sticky("<?= addslashes(L::global_error);?>! " + obj.save_txt, {autoclose: 5000, position: "top-right", type: "st-error", speed: "fast"});
                    }
                }
            });
            return false;
        });


        $('.checkall').click(function() {
            $(this).parents('div.inside').find(':checkbox').attr('checked', this.checked);

        });
    });
</script>
<?php
get_footer();
?>