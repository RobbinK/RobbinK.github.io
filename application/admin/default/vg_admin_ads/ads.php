<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: ads.php
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
get_header();
#**************  
?>  
<style>
    .myform tr td{padding: 5px 10px;}
    form{  margin: 0px !important}
    .btn-mini {
        padding: 3px 5px;
    }
    .disc-ad{ 
        padding: 15px 20px;
        border: solid 1px #E0D4D4;
        border-radius: 5px;
        min-height: 50px;
        padding: 15px 10px;
        text-align: left;
        height: 170px;
        background-color: #F0F0F0;
    }
    .save{height: 30px} 
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
                        <?= L::forms_manage_ads_for; ?> <?= $zone['zone_name'] ?>                      
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /Navigation Menu -->
        <div id="w_sort02" class="w-box"> 
            <div class="w-box-header" style="height:40px;padding-top: 5px">
                <a id="multi_upload_browse" class="btn btn-mini plupload_add sepV_b"  href="<?= $router->generate('admin-zone') ?>" style="position: relative; z-index: 0;">
                    <i class="splashy-arrow_medium_<?= lang_isrtl() ? 'right' : 'left' ?>"></i>  <?= L::global_back; ?> </a>
                <a id="multi_upload_browse" class="btn btn-mini plupload_add sepV_b save_all" href="javascript:void(0);" style="position: relative; z-index: 0;">
                    <i class="splashy-documents_okay"></i><?= L::global_save_all; ?> </a>
                <a id="multi_upload_browse" class="btn btn-mini plupload_add sepV_b delete_all" href="javascript:void(0);" style="position: relative; z-index: 0;">
                    <i class="splashy-documents_remove"></i> <?= L::global_delete_all; ?></a>

                <img style="display: none" id="loading" src="<?= template_url() . '/img/loading.gif' ?>"/>
            </div>
            <div class="w-box-content">
                <input type="hidden" name="zone_id" id="zone_id" value="<?= @$_GET['zone_id'] ?>"/>
                <input type="hidden"  id="show_hide" value="<?= $zone['show_ad'] ?>"/>
                <div id="main" style="padding-bottom: 10px">
                    <hr style="margin: 0"/>
                    <p  class="well well-small" style="margin:5px 10px;">
                        <?= L::forms_place_code; ?><br><br>
                        <code style='direction:ltr'><?= htmlentities("<?=  ab_show_ad('" . strtolower($zone['zone_name']) . "')?>") ?></code>
                    </p>
                </div> 
            </div> 
        </div>
    </div>

</div>
<?php
get_sidebar();
get_footer('_script');
?> 
<script type="text/javascript">

    var i = 0;
    function active_savebtn() {
        $('.myform').find("input[type=text],textarea,select").unbind('keyup').keyup(function() {
//            if ($(this).closest('form').find('.code').val().length > 0)
            $(this).closest("form").find('.save').attr('disabled', false);
        });
        $('.myform').find("input[type=checkbox],select").unbind('change').change(function() {
            if ($(this).closest('form').find('.code').val().length > 0)
                $(this).closest("form").find('.save').attr('disabled', false);
        });
    }

    $(document).ready(function() {
        getdata();
        window.jobsQueue = -1;
        $('.delete_all').click(function() {
            var Ids = [];
            $('#main').find('.edit_id').each(function() {
                if ($(this).val() !== '')
                    Ids.push($(this).val());
            });
            if (Ids.length > 0) {
                smoke.confirm('<?= addslashes(L::alert_del_file_warning);?>', function(e) {
                    if (e) {
                        st1 = $.sticky('<?= addslashes(L::alert_deleting_file);?>', {autoclose: false, position: "top-right", type: "st-info", speed: "fast"});
                        $.ajax({
                            type: 'POST',
                            data: {ids: Ids},
                            url: "<?= url::itself()->fulluri(array('delall' => 1)) ?>",
                            success: function(result) {
                                $.stickyhide(st1.id);
                                $.sticky(result + " <?= L::alert_records_delete; ?>", {autoclose: 3000, position: "top-right", type: "st-success", speed: "fast"});
                                $('.myform').remove();
                                if (!chknewForm())
                                    addnewForm(0);
                            }
                        });
                    }
                });
            }
            return false;
        });
        $('.save_all').click(function() {

            window.savedRecord = 0;
            window.jobsQueue = $('.myform').find('.save[disabled!="disabled"]:visible').length;
            $('.myform').find('.save[disabled!="disabled"]:visible').trigger("click").attr('disabled', 'disabled');

            $.ajax({
                type: 'POST',
                data: {id: $('#zone_id').val()},
                url: "<?= url::itself()->url_nonqry(array('saveall' => 1)) ?>",
                success: function() {
                }

            });
            return false;
        });
        active_savebtn();

    });
    function checkactive() {
        $("select option:selected").each(function() {
            if ($(this).val() == '1') {
                $(this).parents('table').find('input[type=text],textarea').removeAttr('disabled')
            } else {
                $(this).parents('table').find('input[type=text],textarea').attr('disabled', true);
            }

        });
    }

    function getdata() {
        var id = '';
        var status = false;
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: '<?= url::itself()->fulluri(array('getdata' => 1)) ?>',
            data: {zone_id: $('#zone_id').val()},
            success: function(a) {
                var c = 0;
                $.each(a, function(k, v) {
                    c = v.countries;
                    var id = addnewForm(c);
                    if (v.status == 0) {
                        $('#addtable' + id).find('input[type=text],textarea,input[type=submit]').attr('disabled', true);
                    }

                    $('#addtable' + id).find('.del').fadeIn();
                    $('#addtable' + id).find('.con').fadeIn();
                    $('#addtable' + id).find('.add').fadeOut();
                    $('#addtable' + id).find('.adnetwork_title').val(v.adnetwork_title);
                    $('#addtable' + id).find('.code').val(v.code);
                    $('#addtable' + id).find('.status').val(v.status);
                    $('#addtable' + id).find('.order').val(v.order);
                    $('#addtable' + id).find('.edit_id').val(v.id);
                });

                if ($('#show_hide').val() != '1' || a.length == 0)
                    addnewForm(0);

            }
        });
    }
    function chknewForm() {
        var ret = false;
        $('.myform').find('.edit_id').each(function() {
            if ($(this).val() == '')
                ret = true;
        });
        return ret;
    }
    function addnewForm(countries) {
        i++;
        var options;
        for (j = 0; j <= 10; j++) {
            options += "<option >" + j + "</option>";
        }
<?php
$v = '';
switch (strtolower($zone['type'])) {
    case 'banner':
        $v = '<dt>' . L::forms_ad_type . '<dt> <dd>' . L::forms_banner_area . '</dd>';
        break;
    case 'popunder':
        $v = '<dt>' . L::forms_ad_type . '<dt> <dd>' . L::forms_popunder . '</dd>';
        break;
    case 'skin':
        $v = '<dt>' . L::forms_ad_type . '<dt> <dd>' . L::forms_skin_ads . '</dd>';
        break;
    case 'anchor':
        $v = '<dt>' . L::forms_ad_type . '<dt><dd>' . L::forms_anchore_ads . '</dd>';
        break;
}
if (!empty($zone['adsize']))
    $z = '<dt>' . L::forms_ad_size . '</dt> <dd>' . $zone['adsize'] . '</dd>';
else
    $z = '';
?>

        if (countries)
            countries = "<dt><?= L::global_countries; ?> </dt><dd> <label class='label label-success' title='" + explode(',', countries).length + " <?= L::forms_countries_selected; ?>'>" + explode(',', countries).length + '</label> </dd>';
        else
            countries = "<dt><?= L::global_countries; ?> </dt><dd> <label class='label label-success'><?= L::forms_all_countries_selected; ?></label> </dt>";
        var disc = '<dl class="dl-horizontal">' + '<?= $v ?>' + '<?= $z ?>' + countries + '</dl>';

        var htmlcode = "<form style='margin: 20px 0 0 0 !important' class='myform' method='post' action='' class='form_validation_reg' onsubmit='return false'>" +
                "<label class='label label-info' style='margin:0 11px;'>" +
                "<?= addslashes(L::global_ads);?> #" + i +
                "</label>" +
                "<div class='dl-horizontal well form-inline' style='margin:2px 10px 20px 10px !important;'>" +
                "<table style='width:100%' id='addtable" + i + "' style='display: none;' >" +
                " <tr>" +
                "  <td  style='width:105px;'>" +
                "   <label><?= L::forms_ad_network_title; ?></label>" +
                "  </td>" +
                "  <td style='width:160px'>" +
                "   <input type='text' class='adnetwork_title' name='adnetwork_title'/>" +
                "</td><td>" +
                //"   <a class='btn btn-inverse' data-loading-text='Getting...'>Get Ad code from AbsAds</a>" +
                "  </td>" +
                "  <td rowspan='32' style='vertical-align:top;'>" +
                "   <div class='disc-ad'>" + disc + "</div>" +
                "  </td>" +
                " </tr>" +
                "<tr>" +
                " <td>" +
                "  <label   class='pull-right'><?= L::forms_ad_code; ?></label>" +
                " </td>" +
                " <td colspan='2'>" +
                "  <input type='hidden' name='id' class='edit_id' value=''/>" +
                "  <textarea style='width: 400px;height: 75px;direction:ltr' name='code' class='code' required='true'></textarea>" +
                " </td>" +
                "</tr>" +
                "<tr>" +
                " <td>" +
                "  <label   class='pull-right'><?= L::forms_order; ?></label>" +
                " </td>" +
                " <td>" +
                "  <select  name='order' class='order' style='width:85px' >" + options + "</select>" +
                " </td>" +
                "</tr>" +
                "<tr>" +
                " <td>" +
                "  <label   class='pull-right'><?= L::global_status; ?></label>" +
                " </td>" +
                " <td>" +
                "  <select name='status' class='status' onchange='checkactive()' style='width:85px;'> <option value='1'><?= L::global_enable; ?></option> <option value='0'><?= L::global_disable; ?></option> </select>" +
                " </td>" +
                "</tr>" +
                "<tr>" +
                " <td></td>" +
                " <td  colspan='2'>" +
                "  <input type='submit' class='pull-left btn btn-inverse save' value='<?= addslashes(L::global_save);?>'/>  " +
                "  <a class='btn del' href='javascript:void(0);' class='pull-left' style='display: none;margin:0 6px'><i class='icon-remove'></i><?= L::global_remove; ?></a>" +
                "  <a class='btn con' href='javascript:void(0);' class='pull-left' style='display: none;'><i class='icon-globe'></i> <?= L::global_countries; ?></a>" +
                " </td>" +
                "</tr>" +
                "<tr>" +
                " <td>" +
                " <i class='add splashy-add' id='arrow' onclick='addnewForm(0)' ></i>" +
                " </td>" +
                "</tr>" +
                "</table> " +
                "</div>" +
                "</form> ";

        $('#main').append(htmlcode);

        $('#addtable' + i).find('.del').click(function() {
            var form = $(this).closest('.myform');
            smoke.confirm('<?= addslashes(L::alert_del_warning);?>', function(e) {
                if (e) {
                    $('#loading').fadeIn('slow');
                    st1 = $.sticky('<?= addslashes(L::alert_deleting_records);?>', {autoclose: false, position: "top-right", type: "st-info", speed: "fast"});
                    $.ajax({
                        type: 'POST',
                        data: {id: form.find('.edit_id').val()},
                        url: "<?= url::itself()->fulluri(array('del' => 1)) ?>",
                        success: function(result) {
                            $.stickyhide(st1.id);
                            $.sticky(result + " <?= L::alert_records_delete; ?>", {autoclose: 3000, position: "top-right", type: "st-success", speed: "fast"});
                            form.remove();
                            $('#loading').fadeOut('slow');
                            if ($('#show_hide').val() == '1')
                                addnewForm(0);
                        }
                    });
                }
            });
            return false;
        });
        $('#addtable' + i).find('.con').click(function() {
            var editid = $(this).closest('.myform').find('.edit_id').val();
            var url = "<?= url::router('admincountriesads') ?>?id=" + editid;
            document.location.href = url;
            return false;
        });

        $('#addtable' + i).closest('form').on('submit', function() {
            var form = $(this);
            var save = $(this).find('.save');
            var editid = $(this).find('.edit_id');
            $('#loading').fadeIn('slow');

            data = {
                adnetwork_title: $(this).find('.adnetwork_title').val(),
                code: base64.encode($(this).find('.code').val()),
                status: $(this).find('.status').val(),
                disc: $(this).find('.disc').val(),
                order: $(this).find('.order').val(),
                id: ($(this).find('.edit_id').length > 0 ? $(this).find('.edit_id').val() : null)
            };
            // encode and slashes
            // $.each(data, function (k, v) {
            //   data[k] = base64.encode(v);
            // });

            $.ajax({
                type: 'POST',
                data:{'encodedData':encodePostData(data)},
                url: "<?= url::itself()->fulluri(array('save' => 1)) ?>",
                success: function(result) {

                    obj = JSON.parse(result);
                    if (obj.save_code === 1) {
                        if (window.jobsQueue > 0) {
                            window.jobsQueue--;
                            window.savedRecord++;
                        }
                        if (window.jobsQueue == -1)
                            $.sticky(obj.save_txt, {autoclose: 3000, position: "top-right", type: "st-success", speed: "fast"});
                        if (obj.lsid) {
                            editid.val(obj.lsid);
                            form.find('.con').fadeIn(300);
                            form.find('.del').fadeIn(300);
                            form.find('.dtcon').html(obj.count_allcons);
                            if (!chknewForm() && $('#show_hide').val() != 1)
                                addnewForm(0);
                        }
                        save.attr('disabled', true);
                    }
                    else {
                        if (window.jobsQueue == -1)
                            $.sticky("<?= addslashes(L::global_error);?>! " + obj.save_txt, {autoclose: 3000, position: "top-right", type: "st-error", speed: "fast"});
                    }
                    $('#loading').fadeOut('slow');

                    if (window.jobsQueue == 0) {
                        $.sticky((window.savedRecord || "") + " <?= L::alert_records_save; ?> ", {autoclose: 3000, position: "top-right", type: "st-success", speed: "fast"});
                        window.jobsQueue = -1;
                    }

                }
            });
            return false;
        });
        if (i == 10) {
            $('#addtable' + (i)).find('.add').fadeOut();
        }
        if ($('#show_hide').val() == '1') {
            $('.add').hide();
            $('.order').attr('disabled', true);
        } else if ($('#show_hide').val() == '2') {
            $('.order').closest('tr').hide();

        }
        $('#addtable' + (i - 1)).find('.add').fadeOut();
        $('#addtable' + i).find('.save').attr('disabled', true);
        active_savebtn();
        return i;

    }

</script>
<?php
get_footer();
?>
