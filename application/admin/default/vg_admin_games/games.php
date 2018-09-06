<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: games.php
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
abs_admin_inc(l_tagsinput);
abs_admin_inc_js(template_path() . '/lib/simple_ajax_uploader/SimpleAjaxUploader.min.js');
abs_admin_inc_js(template_path() . '/lib/pshowlimit/pshowlimit.jquery.js');
abs_admin_inc_css(template_url() . '/lib/pshowlimit/style.css');
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
                    <?= L::sidebar_games_mng; ?>
                </li>
            </ul>
        </div>
    </nav>
    <!-- /Navigation Menu -->

    <!-- Add Game -->
    <div id="form_div" class="tab-content" style="visibility:visible ">
    <h3 class="heading" id="div_title"></h3>


    <form id="myform" method="post" class="form_validation_reg" novalidate="novalidate">
    <input type="hidden" name="gid" id="gid" class="edit_id"/>
    <dl class="dl-horizontal">
    <dt><label><?= L::forms_game_name; ?></label></dt>
    <dd>
        <div><input type="text" name="game_name" id="game_name" required></div>
    </dd>

    <dt><label><?= L::forms_game_categories; ?></label></dt>
    <dd>
        <div>
            <select name="game_categories" id="game_categories" class="hidden" style="width: 300px" multiple="multiple">
                <?php
                if (isset($categoriesaout))
                    while (current($categoriesaout)) : extract(current($categoriesaout));
                        echo "<option value={$cid}>{$title}</option>";
                        next($categoriesaout);
                    endwhile;
                ?>
            </select>
        </div>
    </dd>

    <dt><label><?= L::forms_game_description; ?></label></dt>
    <dd>
        <div><textarea name="game_description" id="game_description" class="input-xxlarge auto_expand"></textarea></div>
    </dd>

    <dt><label><?= L::forms_game_instruction; ?></label></dt>
    <dd>
        <div><textarea name="game_instruction" id="game_instruction" class="input-xxlarge auto_expand"></textarea></div>
    </dd>

    <dt><label><?= L::forms_game_controls; ?></label></dt>
    <dd>
        <div><textarea name="game_controls" id="game_controls" class="input-xxlarge auto_expand"></textarea></div>
    </dd>

    <dt><label><?= L::forms_games_tags; ?></label></dt>
    <dd>
        <div>
            <input id="game_tags" name="game_tags" type="text" class="input-xxlarge"/>
            <span class="help-inline"><?= L::forms_press_enter; ?></span>
        </div>
    </dd>
    <?php if (setting::get_data('meta_description_source', 'val') == 'new'): ?>
        <dt><label><?= L::forms_meta_description; ?></label></dt>
        <dd>
            <div>
                <textarea name="game_meta_description" id="game_meta_description"
                          class="input-xxlarge auto_expand meta_box"></textarea>

                <div class="limit-hint">
                    <span></span>
                </div>
            </div>
        </dd>
    <?php endif; ?>

    <dt><label><?= L::forms_meta_keywords; ?></label></dt>
    <dd>
        <div>
            <textarea name="game_keywords" id="game_keywords" maxlength="500"
                      class="input-xxlarge auto_expand meta_box"></textarea>

            <div class="limit-hint">
                <span></span>
            </div>
        </div>
    </dd>

    <dt><label><?= L::forms_ribbon_type; ?></label></dt>
    <dd>
        <div>
            <select name="ribbon_type" id="ribbon_type" class="input-medium">
                <option value=""></option>
                <option value="new"><?= L::forms_new; ?></option>
                <option value="hot"><?= L::forms_hot; ?></option>
                <option value="featured"><?= L::forms_featured; ?></option>
            </select>
        </div>
    </dd>
    <div id="ribbon_expiration_wrapper">
        <dt><label><?= L::forms_expires_after; ?></label></dt>
        <dd>
            <div>
                <input type="text" id="ribbon_expiration" name="ribbon_expiration" class="input-mini" data-default="1"/>
                <span class="help-inline"><?= L::global_days ?></span>
            </div>
        </dd>
    </div>

    <div class="formSep"></div>

    <!-- <Upload Game Image> -->
    <dt><label><?= L::forms_image_source; ?></label></dt>
    <dd>
        <div>
            <select name="game_image_source" id="game_image_source" class="input-xlarge">
                <option value="0"><?= L::forms_upload_game_image; ?></option>
                <option value="1"><?= L::forms_grab_remote_image; ?></option>
            </select>
        </div>
    </dd>

    <dt><label><?= L::forms_game_thumb; ?></label></dt>
    <dd id="game_img_wrapper">
        <input type="hidden" name="game_img" id="game_img"/>

        <div class="manual">
            <input type="button" id="upload_game_img" class="btn btn-large clearfix"
                   value="<?= addslashes(L::forms_select_file); ?>"/>
            <span class="help-inline"><i>PNG, JPG, GIF (<?= L::forms_max_file_size; ?> : 200 <?= L::global_kilo_byte; ?>
                    )</i></span>
        </div>

        <div class="grab">
            <div class="input-append" style="margin-bottom: 0px;">
                <input type="url" name="grab_game_img" id="grab_game_img" data-default="http://">

                <div class="btn-group">
                    <a class="btn" onclick="grabbing_game_img();
                                            return false;"><?= L::forms_grab; ?></a>
                    <button class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="javascript:void(0);" onclick="$('#grab_game_img').val($('#grab_game_img').data('default') || '');
                                                return false;"><i class="icon-ban-circle"></i><?= L::forms_clean; ?></a>
                        </li>
                    </ul>
                </div>
            </div>
            <span class="help-inline"></span>
        </div>

        <div id="game_img_attachment" style="margin-bottom:15px;">
            <div id="errormsg-game_img" class="clearfix uploaderror label label-important "></div>
            <div id="pic-progress-wrap-game_img" class="progress-wrap" style="margin-bottom:10px;"></div>
            <div id="filebox-game_img" class="clear" style="position: relative;padding-top:0px;"></div>
        </div>
    </dd>

    <dt><label><?= L::forms_featured_image; ?></label></dt>
    <dd id="featured_img_wraper">
        <input type="hidden" name="featured_img" id="featured_img"/>

        <div class="manual">
            <input type="button" id="upload_featured_img" class="btn btn-large clearfix"
                   value="<?= addslashes(L::forms_select_file); ?>"/>
            <span class="help-inline"><i>PNG, JPG, GIF (<?= L::forms_max_file_size; ?> : 200 <?= L::global_kilo_byte; ?>
                    )</i></span>
        </div>

        <div class="grab">
            <div class="input-append" style="margin-bottom: 0px;">
                <input type="url" name="grab_featured_img" id="grab_featured_img" data-default="http://">

                <div class="btn-group">
                    <a class="btn" onclick="grabbing_featured_img();
                                            return false;"><?= L::forms_grab; ?></a>
                    <button class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="javascript:void(0);" onclick="$('#grab_featured_img').val($('#grab_featured_img').data('default') || '');
                                                return false;"><i class="icon-ban-circle"></i><?= L::forms_clean; ?></a>
                        </li>
                    </ul>
                </div>
            </div>
            <span class="help-inline"></span>
        </div>

        <div id="featured_img_attachment" style="margin-bottom:15px;">
            <div id="errormsg-featured_img" class="clearfix uploaderror label label-important "></div>
            <div id="pic-progress-wrap-featured_img" class="progress-wrap" style="margin-bottom:10px;"></div>
            <div id="filebox-featured_img" class="clear" style="position: relative;padding-top:0px;"></div>
        </div>
    </dd>
    <!-- </Upload Game Image> -->


    <div class="formSep"></div>
    <!-- <Upload slide image> -->
    <dt></dt>
    <dd>
        <label class="uni-checkbox">
            <input type="checkbox" name="game_show_slide" id="game_show_slide" value="1">
            <?= L::forms_show_as_slide; ?>
        </label>
    </dd>
    <div id="game_slide_image_wrapper">
        <dt><label><?= L::forms_slide_show_image; ?></label></dt>
        <dd>
            <input type="hidden" name="game_slide_image" id="game_slide_image">

            <div class="manual">
                <input type="button" id="upload_game_slide_image" class="btn btn-large clearfix"
                       value="<?= addslashes(L::forms_select_file); ?>"/>
                <span class="help-inline"><i>PNG, JPG, GIF</i></span>
            </div>
            <div id="game_slide_image_attachment" style="margin-bottom:15px;">
                <div id="errormsg-game_slide_image" class="clearfix uploaderror label label-important "></div>
                <div id="pic-progress-wrap-game_slide_image" class="progress-wrap" style="margin-bottom:10px;"></div>
                <div id="filebox-game_slide_image" class="clear" style="position: relative;padding-top:0px;"></div>
            </div>
        </dd>
    </div>
    <!-- </Upload slide image> -->
    <div class="formSep"></div>


    <!-- <Upload Game File> -->
    <dt><label> <?= L::forms_game_file_source; ?></label></dt>
    <dd>
        <div>
            <select name="game_file_source" id="game_file_source" class="input-xxlarge">
                <option value="0"><?= L::forms_upload_game_file; ?></option>
                <option value="1"><?= L::forms_grab_remote_file; ?></option>
                <option value="3"><?= L::forms_remote_game_file; ?></option>
                <option value="2"><?= L::forms_remote_iframe_link; ?></option>
                <option value="4"><?= L::forms_embedded_code; ?></option>
            </select>
        </div>
    </dd>

    <dt><label><?= L::forms_game_file; ?></label></dt>
    <dd id="game_file_wraper">
        <input type="hidden" name="game_file" id="game_file">

        <div class="manual">
            <input type="button" id="upload_game_file" class="btn btn-large clearfix"
                   value="<?= addslashes(L::forms_select_file); ?>"/>
            <span class="help-inline"><i>SWF, DCR, UNITY3D (<?= L::forms_max_file_size; ?> :
                    50 <?= L::global_mega_byte; ?>)</i></span>
        </div>

        <div class="grab">
            <div class="input-append" style="margin-bottom: 0px;">
                <input type="url" name="grab_game_file" id="grab_game_file" data-default="http://">

                <div class="btn-group">
                    <a class="btn" onclick="grabbing_game_file();
                                            return false;"><?= L::forms_grab; ?></a>
                    <button class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="javascript:void(0);" onclick="$('#grab_game_file').val($('#grab_game_file').data('default') || '');
                                                return false;"><i class="icon-ban-circle"></i> <?= L::forms_clean; ?>
                            </a></li>
                    </ul>
                </div>
            </div>
            <em></em>
            <span class="help-inline"></span>
        </div>

        <div class="link">
            <div class="input-append" style="margin-bottom: 0px;">
                <input type="url" name="link_game_file" id="link_game_file" data-default="http://">

                <div class="btn-group">
                    <button class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="#link_game_file" class="showswf"><i
                                    class="icon-eye-open"></i> <?= L::forms_preview; ?></a></li>
                        <li><a href="javascript:void(0);" onclick="$('#link_game_file').val($('#link_game_file').data('default') || '');
                                                return false;"><i class="icon-ban-circle"></i> <?= L::forms_clean; ?>
                            </a></li>
                    </ul>
                </div>
            </div>
            <span class="help-inline"></span>
        </div>


        <div class="iframe">
            <div class="input-append" style="margin-bottom: 0px;">
                <input type="url" name="iframe_game_file" id="iframe_game_file" data-default="http://">

                <div class="btn-group">
                    <button class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="#iframe_game_file" class="showswf"><i
                                    class="icon-eye-open"></i> <?= L::forms_preview; ?></a></li>
                        <li><a href="javascript:void(0);" onclick="$('#iframe_game_file').val($('#iframe_game_file').data('default') || '');
                                                return false;"><i class="icon-ban-circle"></i> <?= L::forms_clean; ?>
                            </a></li>
                    </ul>
                </div>
            </div>
            <span class="help-inline"></span>
        </div>

        <div class="embedded">
            <div class="input-append" style="margin-bottom: 0px;">
                <textarea name="embedded_game_file" id="embedded_game_file" class="span5 auto_expand"></textarea>
            </div>
            <span class="help-inline"></span>
        </div>

        <div id="game_file_attachment" style="margin-bottom:15px;">
            <div id="errormsg-game_file" class="clearfix uploaderror label label-important "></div>
            <div id="pic-progress-wrap-game_file" class="progress-wrap" style="margin-bottom:10px;"></div>
            <div id="filebox-game_file" class="clear"
                 style="position: relative;height:60px;padding-top:0px;padding-bottom:10px;"></div>
        </div>
    </dd>
    <!-- </Upload Game File> -->

    <div class="formSep"></div>

    <?= convert::to_bool(setting::get_data('getdimension_after_uploading', 'val')) ? "<dt></dt><dd><label class='text-success'><?=L::forms_game_dimension_hint;?></label></dd>" : null; ?>
    <dt><label> <?= L::forms_game_width; ?> </label></dt>
    <dd>
        <div>
            <input type="text" name="game_width" id="game_width" data-default="0" style="width:50px" required>
            <span class='help-inline'><?= L::forms_size_px; ?></span>
        </div>
        <em></em>
    </dd>

    <dt><label><?= L::forms_game_height; ?></label></dt>
    <dd>
        <div>
            <input type="text" name="game_height" id="game_height" data-default="0" style="width:50px" required>
            <span class='help-inline'><?= L::forms_size_px; ?></span>
        </div>
        <em></em>
    </dd>


    <dt><label><?= L::forms_featured; ?></label></dt>
    <dd>
        <div>
            <select name="game_is_featured" id="game_is_featured" class="input-mini">
                <option value="0"><?= L::global_state_no; ?></option>
                <option value="1"><?= L::global_state_yes; ?></option>
            </select>
        </div>
    </dd>

    <dt><label><?= L::global_status; ?></label></dt>
    <dd>
        <div>
            <select name="game_is_active" id="game_is_active" class="input-medium">
                <option value="1"><?= L::global_active; ?></option>
                <option value="0"><?= L::forms_move_to_queue_list; ?></option>
                <option value="-1"><?= L::global_inactive; ?></option>
            </select>
        </div>
    </dd>
    <dt></dt>
    <dd>
        <div>
            <input class="btn btn-success" type="submit" value="<?= addslashes(L::global_save); ?>"
                   style="width: 120px"/>
            &nbsp;&nbsp;
            <input class="btn" type="button" name="reset" value="<?= addslashes(L::forms_add_new_game); ?>" onclick="reset_form();
                                    trigger_selectuploaders();
                                    return false;"/>
            &nbsp;&nbsp;
            <input class="btn" type="button" name="close" value="<?= addslashes(L::global_cancel); ?>" onclick="close_from();
                                    return false;" style="width: 80px"/>
        </div>
    </dd>

    </dl>

    </form>
    <div class="formSep"></div>
    </div>
    <!-- /Add Game -->

    <!-- Game List -->
    <h3 class="heading"><?= L::forms_games_list; ?>
        <button class="pull-right btn btn-info  bt_add_new" onclick=""><?= L::forms_add_new_game; ?></button>
        <button class="pull-right btn btn-info  bt_cancel" style='display: none'
                onclick=""><?= L::global_cancel; ?></button>
    </h3>


    <?php if (isset($_GET['cat'])) : ?>
        <span class="label label-info sepH_c" style=" padding: 3px 5px; ">
            <font style="font:15px 'PT sans';"><?= Category::getCategoriesTitle($_GET['cat']) ?>
                <a href="<?=url::itself()->fulluri(array('cat'=>null));?>" class="ext_disabled"><i class="splashy-tag_remove" style=" margin-top: 2px; "></i></a>
            </font>
        </span>
    <?php endif; ?>

    <table id="dt_e" class="table table-striped table-bordered dTableR">
        <thead>
        <tr>
            <th>id</th>
            <th>game_rating</th>
            <th>game_is_featured</th>
            <th>featured_img</th>
            <th><?= L::global_image; ?></th>
            <th><?= L::forms_game_name; ?></th>
            <th><?= L::forms_game_categories; ?></th>
            <th><?= L::forms_played_today; ?></th>
            <th><?= L::forms_total_plays; ?></th>
            <th><?= L::forms_last_played; ?></th>
            <th><?= L::global_type; ?></th>
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
    <!-- /Game List -->


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
    var debug = false;
    var fValidation;
    var oTable;
    var loading_config = {
        'indicatorZIndex': 990,
        'overlayZIndex': 990
    };
    $(document).ready(function () {
        <?php
        if (setting::get_data('meta_description_source', 'val') == 'new'):
            $lenght = setting::get_data('meta_description_length', 'val');
            if ($lenght <= 0)
                $lenght = 175;
            ?>
        new pShowLimit($('#game_meta_description'), {maxWords: 5, maxChars: <?= $lenght ?>});
        <?php endif; ?>
        new pShowLimit($('#game_keywords'), {});

        reg_xhr_setup();
        reg_uploaders_game_img();
        reg_uploaders_featured_img();
        reg_uploaders_slideshow_img();
        reg_uploaders_game_file();
        reg_showswf_colorbox();
        reg_tagsinput();
        $('#form_div').fadeOut(0);
        $("select[multiple='multiple']").multipleSelect({selectAllText: '<?= addslashes(L::global_select_all); ?>'});
        //label
        $('#ribbon_expiration').spinner({
            min: 1
        });
        $('#ribbon_type').change(function () {
            if ($('#ribbon_type').val())
                $('#ribbon_expiration_wrapper').fadeIn();
            else
                $('#ribbon_expiration_wrapper').fadeOut();
        });
        $('#ribbon_type').trigger('change');
        // Image Source
        $('#game_image_source').on('change', function () {
            if (debug)
                console.log('trigger:  game_image_source (val:' + this.value + ')\n');
            $('#game_img_wrapper').find('.manual,.grab').each(function () {
                $(this).fadeOut(300);
            });
            $('#featured_img_wraper').find('.manual,.grab').each(function () {
                $(this).fadeOut(300);
            });
            if ($(this).val() == 1)
                $('#game_img_wrapper .grab,#featured_img_wraper .grab').delay(300).fadeIn(300);
            else
                $('#game_img_wrapper .manual,#featured_img_wraper .manual').delay(300).fadeIn(300);
        });
        // slideshow
        $('#game_show_slide:checkbox').change(function () {
            if ($(this).is(':checked'))
                $('#game_slide_image_wrapper').fadeIn(300);
            else
                $('#game_slide_image_wrapper').fadeOut(300);
        });
        // File Source
        $('#game_file_source').on('change', function () {
            if (debug)
                console.log('trigger:  game_file_source (val:' + this.value + ')\n');
            $('#game_file_wraper').find('.manual,.grab,.iframe,.link,.embedded').each(function () {
                $(this).fadeOut(300);
            });
            switch ($(this).val()) {
                case '1':
                    $('#game_file_wraper .grab').delay(300).fadeIn(300);
                    $('#game_file_attachment').delay(300).fadeIn(300);
                    break;
                case '0':
                    $('#game_file_wraper .manual').delay(300).fadeIn(300);
                    $('#game_file_attachment').delay(300).fadeIn(300);
                    break;
                case '2':
                    $('#game_file_wraper .iframe').delay(300).fadeIn(300);
                    $('#game_file_attachment').delay(300).fadeOut(300);
                    break;
                case '3':
                    $('#game_file_wraper .link').delay(300).fadeIn(300);
                    $('#game_file_attachment').delay(300).fadeOut(300);
                    break;
                case '4':
                    $('#game_file_wraper .embedded').delay(300).fadeIn(300);
                    $('#game_file_attachment').delay(300).fadeOut(300);
                    break;
            }

        });
        trigger_selectuploaders();
        oTable = $('#dt_e').dataTable({
            bInfo: true,
            bLengthChange: true,
            sPaginationType: "bootstrap_full", /*full_numbers , two_button */
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
                {bVisible: false, aTargets: [0]},
                {bVisible: false, aTargets: [1]},
                {bVisible: false, aTargets: [2]},
                {bVisible: false, aTargets: [3]},
                {aTargets: [4], sWidth: '60px'},
                {aTargets: [5]},
                {aTargets: [6], sWidth: '200px'},
                {aTargets: [7], sWidth: '60px'},
                {aTargets: [8], sWidth: '60px'},
                {aTargets: [9], sWidth: '60px'},
                {aTargets: [10], sWidth: '40px', sClass: 'center'},
                {aTargets: [11], sWidth: '40px'},
                {bSortable: false, aTargets: [12], sWidth: '50px'}
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
                reg_dt_edit();
                reg_dt_row_click();
                reg_colorbox('auto');
            }
        });
        $("div.toolbar").html('<div class="sepH_a" id="toolbar_inside">\n\
<button class="btn btn-mini sepV_a sall"><li class="icon-th-list"></li> <?= L::global_select_all; ?></button>\n\
<button class="btn btn-mini sepV_a dall" style="display:none"><li class="icon-ban-circle"></li><?= L::global_deselect_all; ?></button>\n\
<button class="btn btn-mini sepV_a btn-danger mdel" style="display:none"><li class="icon-trash"></li><?= L::global_delete_selected; ?></button>\n\
</div>');
        reg_select_all();
        reg_deselect_all();
        reg_multidelete();
        <?php
        if (isset($_GET['new']))
            echo 'setTimeout(function(){$(\'.bt_add_new\') . trigger(\'click\')},1000);';
        ?>
    });
    function reg_xhr_setup() {
        $.xhrPool = [];
        $.xhrPool.abortAll = function () {
            $(this).each(function (idx, jqXHR) {
                jqXHR.abort();
            });
            $.xhrPool.length = 0
        };
        $.ajaxSetup({
            beforeSend: function (jqXHR) {
                $.xhrPool.push(jqXHR);
            },
            complete: function (jqXHR) {
                var index = $.inArray(jqXHR, $.xhrPool);
                if (index > -1) {
                    $.xhrPool.splice(index, 1);
                }
            }
        });

        $.ajaxSetup({
            error: function (x, e) {
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
        $('.dataTables_processing').css({visibility: 'hidden'});
    }

    function is_edit_st() {
        if (parseInt($('.form_validation_reg .edit_id').val()) > 0)
            return true;
        return false;
    }

    function reset_form() {
        $('.form_validation_reg').find('input:text, input[type=url], input[type=hidden], input:password, input:file, select, textarea').val('');
        $('.form_validation_reg').find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
        //tinymce
        if (typeof (tinyMCE) != 'undefined') {
            $('textarea.tinymce').each(function () {
                tinyMCE.get($(this).attr('id')).setContent('');
                tinyMCE.DOM.setStyle(tinyMCE.DOM.get($(this).attr('id') + '_ifr'), 'height', 120 + 'px');
            });
        }
        //select
        $('.form_validation_reg').find('select').each(function () {
            $(this).find('option:first').attr('selected', 'true');
            $(this).trigger('change');
        });
        //default
        $('.form_validation_reg').find('input').each(function () {
            if ($(this).attr('data-default')) {
                $(this).val($(this).data('default'));
            }
        });

        //date
        $('.form_validation_reg').find('input').each(function () {
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

        if (typeof $.fn.multipleSelect != 'undefined') {
            $("select[multiple='multiple']").multipleSelect("uncheckAll");
        }

        //filebox
        close_uploader_box('#filebox-game_img');
        close_uploader_box('#filebox-featured_img');
        close_uploader_box('#filebox-game_file');
        close_uploader_box('#filebox-game_slide_image');
        $('#upload_game_img').val('<?= addslashes(L::forms_select_file); ?>');
        $('#upload_featured_img').val('<?= addslashes(L::forms_select_file); ?>');
        $('#upload_game_file').val('<?= addslashes(L::forms_select_file); ?>');
        $('#upload-game_slide_image').val('<?= addslashes(L::forms_select_file); ?>');
        $('#game_url_parameters_box').fadeOut();

        $('.uploaderror').html('');
        fValidation.resetForm();
        $('.form_validation_reg div').removeClass("f_error");
        $('#div_title').html('<?= addslashes(L::forms_add_new_game); ?>');

        $('.auto_expand').each(function () {
            $(this).css({'height': $(this).data('default-height'), 'min-height': $(this).data('default-height')});
        });
        $('#game_tags').tagsinput('removeAll');
    }


    $('.bt_add_new').click(function () {
        reset_form();
        open_form();
        trigger_selectuploaders();
        $('#ribbon_type').trigger('change');
    });
    $('.bt_cancel').click(function () {
        close_from();
    });
    function trigger_selectuploaders() {
        $('#game_file_source').trigger('change');
        $('#game_image_source').trigger('change');
        $('#game_show_slide:checkbox').trigger('change');
    }
    function open_form() {
        $('.bt_add_new').hide();
        $('.bt_cancel').show();
        $(window).scrollTop(0);
        $('#form_div').slideDown(200);
    }

    function close_from() {
        abortAllAjax();
        $('.bt_add_new').show();
        $('.bt_cancel').hide();
        reset_form();
        $('#form_div').slideUp(200);
    }
    // Validation Options
    window.callbackjob = 0;
    jQuery.validator.addMethod("url", function (value, element) {
        return this.optional(element) || /^(https?:\/\/)?((localhost|[a-z0-9\-]+(\.[a-z0-9\-]+)+)(:[0-9]+)?(\/.*)?)?$/.test(value);
    }, "<?= addslashes(L::alert_invalid_link); ?>");
    fValidation = $("#myform").validate({
        debug: false,
        onfocusout: false,
        highlight: function (element) {
            if ($(element).closest('dd').find('em').length)
                $(element).closest('dd').find('em').closest('div,dd').addClass("f_error");
            else
                $(element).closest('div').addClass("f_error");
        },
        unhighlight: function (element) {
            if ($(element).closest('dd').find('em').length)
                $(element).closest('dd').find('em').closest('div,dd').removeClass("f_error");
            else
                $(element).closest('div').removeClass("f_error");
        },
        errorPlacement: function (error, element) {
            if ($(element).closest('dd').find('em').length)
                error.appendTo($(element).closest('dd').find('em'));
            else
                error.insertAfter(element);
        },
        submitHandler: function (form) {
            window.submitmyform = function () {
                if (window.callbackjob > 0)
                    return false;
                $('#myform').showLoading(loading_config);
                data = $.deparam($('#myform').serialize());
                //tinymce
                if (typeof (tinyMCE) != 'undefined') {
                    $('textarea.tinymce').each(function () {
                        $tinyval = tinyMCE.get($(this).attr('id')).getContent();
                        eval("$.extend(data || {}, {" + $(this).attr('name') + ":$tinyval});");
                    });
                }

                if (typeof $.fn.multipleSelect != 'undefined') {
                    $("select[multiple='multiple']").each(function () {
                        $multis = $(this).multipleSelect("getSelects");
                        eval("$.extend(data || {}, {" + $(this).attr('name') + ":$multis});");
                    });
                }

                // encode and slashes
                // $.each(data, function (k, v) {
                //   data[k] = base64.encode(v);
                // });

                $.ajax({
                    type: 'POST',
                    data: {'encodedData': encodePostData(data)},
                    url: "<?= url::itself()->url_nonqry(array('save' => 1)) ?>",
                    success: function (result) {
                        $('#myform').hideLoading();
                        obj = JSON.parse(result);
                        if (obj.save_code === 1) {
                            $.sticky(obj.save_txt, {
                                autoclose: 5000,
                                position: "top-right",
                                type: "st-success",
                                speed: "fast"
                            });
                            if (!is_edit_st()) {
                                oTable.fnReloadAjax();
                                reset_form();
                            }
                            else {
                                oTable.fnStandingRedraw();
                                open_uploader_imagebox('#filebox-game_img', 'game_img', obj.game_img);
                                open_uploader_imagebox('#filebox-featured_img', 'featured_img', obj.featured_img);
                                open_uploader_imagebox('#filebox-game_slide_image', 'game_slide_image', obj.game_slide_image);
                                open_uploader_filebox_game_file('#filebox-game_file', 'game_file', obj.game_file, function () {
                                    if (typeof (obj.game_url_parameters) != 'undefined' && obj.game_url_parameters != '') {
                                        $('#game_url_parameters').val(obj.game_url_parameters);
                                        $('#game_url_parameters_box').fadeIn();
                                    }
                                });
                                $('#game_img').val(obj.game_img);
                                $('#featured_img').val(obj.featured_img);
                                $('#game_slide_image').val(obj.game_slide_image);
                                $('#game_file').val(obj.game_file);
                            }
                            return true;
                        }
                        else {
                            $.sticky("<?= addslashes(L::global_error); ?>! " + obj.save_txt, {
                                autoclose: 5000,
                                position: "top-right",
                                type: "st-error",
                                speed: "fast"
                            });
                            return false;
                        }
                    }
                });
            };
            if ($('#game_image_source').val() == 1) {
                grabbing_game_img('window.submitmyform');
                grabbing_featured_img('window.submitmyform');
            }
            if ($('#game_file_source').val() == 1) {
                grabbing_game_file('window.submitmyform');
            }
            window.submitmyform();
        }
    });
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
            $('.toolbar .mdel').fadeIn(300);
            $('.toolbar .dall').fadeIn(300);
        } else {
            $('.toolbar .mdel').fadeOut(300);
            $('.toolbar .dall').fadeOut(300);
        }
    }

    function reg_multidelete() {
        $('.toolbar .mdel').click(function () {
            var ids = [];
            $('#dt_e .row_selected').each(function () {
                id = $(this).find('input.row_id').val();
                ids.push(id);
            });
            smoke.confirm('<?= addslashes(L::alert_del_warning); ?>', function (e) {
                if (e) {
                    st1 = $.sticky('<?= addslashes(L::alert_deleting_records); ?>', {
                        autoclose: false,
                        position: "top-right",
                        type: "st-info",
                        speed: "fast"
                    });
                    $.ajax({
                        type: 'POST',
                        data: {id: ids},
                        url: "<?= url::itself()->url_nonqry(array('mdel' => 1)) ?>",
                        success: function (result) {
                            $.stickyhide(st1.id);
                            $.sticky(result, {
                                autoclose: 5000,
                                position: "top-right",
                                type: "st-success",
                                speed: "fast"
                            });
                            oTable.fnStandingRedraw();
                        }
                    });
                }
            }, {});
        });
    }

    function reg_dt_row_click() {
        $('#dt_e tbody tr').click(function () {
            $(this).toggleClass('row_selected');
            dt_selection_stats();
        });
    }

    function reg_dt_delete() {
        $('.del').click(function () {
            var did = $(this).closest('td').find('.row_id').val();
            smoke.confirm('<?= addslashes(L::alert_del_warning); ?>', function (e) {
                if (e) {
                    st1 = $.sticky('<?= addslashes(L::alert_deleting_records); ?>', {
                        autoclose: false,
                        position: "top-right",
                        type: "st-info",
                        speed: "fast"
                    });
                    $.ajax({
                        type: 'POST',
                        data: {id: did},
                        url: "<?= url::itself()->url_nonqry(array('del' => 1)) ?>",
                        success: function (result) {
                            $.stickyhide(st1.id);
                            $.sticky(result, {
                                autoclose: 5000,
                                position: "top-right",
                                type: "st-success",
                                speed: "fast"
                            });
                            oTable.fnStandingRedraw();
                        }
                    });
                }
            }, {});
        });
    }

    function reg_dt_edit() {
        $('.edit').click(function () {
            var eid = $(this).closest('td').find('.row_id').val();
            reset_form();
            $('#div_title').html('<?= addslashes(L::forms_edit_game); ?>');
            open_form();
            $('#myform').showLoading(loading_config);
            $.ajax({
                type: 'POST',
                data: {'id': eid},
                url: "<?= url::itself()->url_nonqry(array('edit' => 1)) ?>",
                success: function (result) {
                    $('#myform').hideLoading();
                    data = JSON.parse(result);
                    $('#myform').unserializeForm($.param(data));
                    if (typeof (tinyMCE) != 'undefined') {
                        $.each(data, function (k, v) {
                            if ($('textarea.tinymce[name=' + k + ']').length) {
                                id = $('textarea.tinymce[name=' + k + ']').attr('id');
                                tinyMCE.get(id).setContent(v);
                            }
                        });
                    }
                    if (typeof $.fn.multipleSelect != 'undefined') {
                        $("select[multiple='multiple']").each(function () {
                            if (data[$(this).attr('name')].length > 0)
                                $(this).multipleSelect("setSelects", data[$(this).attr('name')]);
                        });
                    }

                    if (typeof (data.game_tags) != 'undefined') {
                        for (i = 0; i < data.game_tags.length; i++)
                            $("#game_tags").tagsinput('add', data.game_tags[i]);
                    }

                    trigger_selectuploaders();
                    $('#ribbon_type').trigger('change');
                    setTimeout(function () {
                        if (typeof (data.game_img) != 'undefined' && data.game_img != '') {
                            $('#game_img').val(data.game_img);
                            open_uploader_imagebox('#filebox-game_img', 'game_img', data.game_img);
                        }

                        if (typeof (data.featured_img) != 'undefined' && data.featured_img != '') {
                            $('#featured_img').val(data.featured_img);
                            open_uploader_imagebox('#filebox-featured_img', 'featured_img', data.featured_img);
                        }

                        if (typeof (data.game_slide_image) != 'undefined' && data.game_slide_image != '') {
                            $('#game_slide_image').val(data.game_slide_image);
                            open_uploader_imagebox('#filebox-game_slide_image', 'game_slide_image', data.game_slide_image);
                        }

                        if ((data.game_file_source == 0 || data.game_file_source == 1) && typeof (data.game_file) != 'undefined' && data.game_file != '') {
                            $('#game_file').val(data.game_file);
                            open_uploader_filebox_game_file('#filebox-game_file', 'game_file', data.game_file, function () {
                                if (typeof (data.game_url_parameters) != 'undefined' && data.game_url_parameters != '') {
                                    $('#game_url_parameters').val(data.game_url_parameters);
                                    $('#game_url_parameters_box').fadeIn();
                                }
                            });
                        }

                    }, 200);
                }
            });
        });
    }


    function reg_uploaders_game_img() {

        var btn = document.getElementById('upload_game_img'),
            wrap = document.getElementById('pic-progress-wrap-game_img'),
            filebox = document.getElementById('filebox-game_img'),
            errBox = document.getElementById('errormsg-game_img');
        var uploader = new ss.SimpleUpload({
            button: btn,
            url: '<?= url::itself()->url_nonqry() ?>',
            progressUrl: '<?= url::itself()->url_nonqry() ?>',
            name: 'up_game_img',
            multiple: false,
            maxUploads: 2,
            maxSize: 200 * 1024,
            allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
            accept: 'image/*',
            debug: false,
            hoverClass: 'btn-hover',
            focusClass: 'active',
            disabledClass: 'disabled',
            responseType: 'json',
            onChange: function () {
                this.setData({
                    gid: $('#myform .edit_id').val(),
                    gamename: encodeURIComponent($('#myform #game_name').val())
                });
            },
            onExtError: function (filename, extension) {
                alert('<?= addslashes(L::alert_invalid_image_format); ?>');
            },
            onSizeError: function (filename, fileSize) {
                alert('<?= addslashes(L::alert_invalid_file_size); ?>. (<?= L::forms_max_file_size; ?> : 200 <?= L::global_kilo_byte ?>)');
            },
            onSubmit: function (filename, ext) {
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
                btn.value = '<?= addslashes(L::forms_select_another_file); ?>';
            },
            onComplete: function (file, response) {
                if (response.success === true) {
                    open_uploader_imagebox('#filebox-game_img', 'game_img', response.file);
                    $('#game_img').val(response.file);
                } else {
                    errBox.innerHTML = response.msg;
                    $('#game_img').val('');
                }
            }
        });
    }

    function reg_uploaders_featured_img() {

        var btn = document.getElementById('upload_featured_img'),
            wrap = document.getElementById('pic-progress-wrap-featured_img'),
            filebox = document.getElementById('filebox-featured_img'),
            errBox = document.getElementById('errormsg-featured_img');
        var uploader = new ss.SimpleUpload({
            button: btn,
            url: '<?= url::itself()->url_nonqry() ?>',
            progressUrl: '<?= url::itself()->url_nonqry() ?>',
            name: 'up_featured_img',
            multiple: false,
            maxUploads: 2,
            maxSize: 200 * 1024,
            allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
            accept: 'image/*',
            debug: false,
            hoverClass: 'btn-hover',
            focusClass: 'active',
            disabledClass: 'disabled',
            responseType: 'json',
            onChange: function () {
                this.setData({
                    gid: $('#myform .edit_id').val(),
                    gamename: encodeURIComponent($('#myform #game_name').val())
                });
            },
            onExtError: function (filename, extension) {
                alert('<?= addslashes(L::alert_invalid_image_format); ?>');
            },
            onSizeError: function (filename, fileSize) {
                alert('<?= addslashes(L::alert_invalid_file_size); ?>. (<?= L::forms_max_file_size; ?> : 200 <?= L::global_kilo_byte ?>)');
            },
            onSubmit: function (filename, ext) {
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
                btn.value = '<?= addslashes(L::forms_select_another_file); ?>';
            },
            onComplete: function (file, response) {
                if (response.success === true) {
                    open_uploader_imagebox('#filebox-featured_img', 'featured_img', response.file);
                    $('#featured_img').val(response.file);
                } else {
                    errBox.innerHTML = response.msg;
                    $('#featured_img').val('');
                }
            }
        });
    }

    function reg_uploaders_slideshow_img() {
        var btn = document.getElementById('upload_game_slide_image'),
            wrap = document.getElementById('pic-progress-wrap-game_slide_image'),
            filebox = document.getElementById('filebox-game_slide_image'),
            errBox = document.getElementById('errormsg-game_slide_image');
        var uploader = new ss.SimpleUpload({
            button: btn,
            url: '<?= url::itself()->url_nonqry() ?>',
            progressUrl: '<?= url::itself()->url_nonqry() ?>',
            name: 'up_game_slide_image',
            multiple: false,
            maxUploads: 1,
            allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
            accept: 'image/*',
            debug: false,
            hoverClass: 'btn-hover',
            focusClass: 'active',
            disabledClass: 'disabled',
            responseType: 'json',
            onChange: function () {
                this.setData({
                    gid: $('#myform .edit_id').val(),
                    gamename: encodeURIComponent($('#myform #game_name').val())
                });
            },
            onExtError: function (filename, extension) {
                alert('<?= addslashes(L::alert_invalid_image_format); ?>');
            },
            onSubmit: function (filename, ext) {
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
                btn.value = '<?= addslashes(L::forms_select_another_file); ?>';
            },
            onComplete: function (file, response) {
                if (response.success === true) {
                    open_uploader_imagebox('#filebox-game_slide_image', 'game_slide_image', response.file);
                    $('#game_slide_image').val(response.file);
                } else {
                    errBox.innerHTML = response.msg;
                    $('#game_slide_image').val('');
                }
            }
        });
    }

    function reg_uploaders_game_file() {

        var btn = document.getElementById('upload_game_file'),
            wrap = document.getElementById('pic-progress-wrap-game_file'),
            filebox = document.getElementById('filebox-game_file'),
            errBox = document.getElementById('errormsg-game_file');
        var uploader = new ss.SimpleUpload({
            button: btn,
            url: '<?= url::itself()->url_nonqry() ?>',
            progressUrl: '<?= url::itself()->url_nonqry() ?>',
            name: 'up_game_file',
            multiple: false,
            maxUploads: 2,
            maxSize: 1024 * 1024 * 50,
            allowedExtensions: ['swf', 'dcr', 'unity3d'],
            /*accept: 'flash/*',*/
            debug: false,
            hoverClass: 'btn-hover',
            focusClass: 'active',
            disabledClass: 'disabled',
            responseType: 'json',
            onChange: function () {
                this.setData({
                    gid: $('#myform .edit_id').val(),
                    gamename: encodeURIComponent($('#myform #game_name').val())
                });
            },
            onExtError: function (filename, extension) {
                alert('<?= addslashes(L::alert_invalid_image_format); ?>');
            },
            onSizeError: function (filename, fileSize) {
                alert('<?= addslashes(L::alert_invalid_file_size); ?>. (<?= L::forms_max_file_size; ?> : 50 <?= L::global_mega_byte ?>)');
            },
            onSubmit: function (filename, ext) {
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
                btn.value = '<?= addslashes(L::forms_select_another_file); ?>';
            },
            onComplete: function (file, response) {
                if (response.success === true) {
                    open_uploader_filebox_game_file('#filebox-game_file', 'game_file', response.file);
                    $('#game_file').val(response.file);
                    if (window.get_auto_game_dimension && response.width > 0)
                        $('#game_width').val(response.width);
                    if (window.get_auto_game_dimension && response.height > 0)
                        $('#game_height').val(response.height);
                } else {
                    errBox.innerHTML = response.msg;
                    $('#game_file').val('');
                    if (window.get_auto_game_dimension) {
                        $('#game_width').val('0');
                        $('#game_height').val('0');
                    }
                }
            }
        });
    }
    function close_uploader_box(jQid) {
        if (debug)
            console.log('close_uploader_box\n');
        if ($(jQid).hasClass('noimg')) {
            $(jQid).find('img.thumbnail').siblings().remove();
            $(jQid).find('img.thumbnail').show();
        } else if ($(jQid).length) {
            $(jQid).html('').fadeOut();
        }

    }

    function open_uploader_imagebox(jQid, dbField, filename) {
        if (debug)
            console.log('open_uploader_imagebox\n');
        if (!$(jQid).length)
            return false;
        if (filename == '' || typeof (filename) == undefined || filename == null) {
            close_uploader_box(jQid);
            return false;
        }
        if (!$(jQid).parent().find('.shoimageloading').length) {
            $(jQid).before("<img src='<?= static_url() ?>/images/loading/loading-9.gif' class='shoimageloading'/>");
        }

        $(jQid).fadeOut(300, function () {
            html = '<img src="<?= url::itself()->url_nonqry() ?>?showimage=' + encodeURIComponent(filename) + '&size=70xnull" rel="clbox" >';
            html += '<span style="top: 0px; position: absolute; margin:0 10px;">' + summarize(filename, 25, true, '') + '</span>';
            html += '<a style="position: absolute; top: 23px; margin:0 10px;cursor:pointer;" onclick="delete_file(\'' + dbField + '\',\'' + filename + '\');return false;" class="btn-danger btn-mini deleteicon"><?= L::global_remove; ?></a>';
            if ($(jQid).hasClass('noimg')) {
                $(jQid).find('img.thumbnail').hide();
                $(jQid).find('img.thumbnail').siblings().remove();
                $(jQid).append(html);
                $(jQid).find('img:not(.thumbnail)').imagesLoaded(function () {
                    reg_colorbox('auto');
                    $(jQid).fadeIn(300);
                    $(jQid).parent().find('.shoimageloading').remove();
                });
            }
            else {
                $(jQid).html(html);
                $(jQid).find('img:not(.thumbnail)').imagesLoaded(function () {
                    reg_colorbox('auto');
                    $(jQid).fadeIn(300);
                    $(jQid).parent().find('.shoimageloading').remove();
                });
            }
        });
    }
    function open_uploader_filebox_game_file(jQid, dbField, filename, doneFunc) {
        if (!$(jQid).length)
            return false;
        if (filename == '' || typeof (filename) == undefined || filename == null) {
            $(jQid).html('').fadeOut();
            return false;
        }

        $(jQid).fadeOut(300, function () {
            act_btn = '<div style="margin:0 10px;cursor:pointer;" class="btn-group">';
            act_btn += ' <a class="btn btn-primary btn-mini dropdown-toggle"  data-toggle="dropdown" href="javascript:void(0);"  ><?= L::global_action; ?> <span class="caret"></span></a>';
            act_btn += ' <ul class="dropdown-menu">';
            act_btn += '  <li><a href="javascript:void(0);" class="showextparameters"><i class="icon-briefcase"></i> Set extra parameters</a></li>';
            act_btn += '  <li><a href="' + filename + '" class="showswf"><i class="icon-eye-open"></i> <?= L::forms_preview; ?></a></li>';
            act_btn += '  <li><a href="javascript:void(0);" onclick="delete_file(\'' + dbField + '\',\'' + filename + '\');return false;"><i class="icon-trash"></i> <?= L::global_remove; ?></a></li>';
            act_btn += ' </ul>';
            act_btn += '</div>';

            html = ' <img src="<?= static_url() ?>/images/icons/attachments/attachment_up.png" class="pull-left"/>';
            html += '<div class="pull-left">';
            html += ' <span style="margin:0 10px 3px;display:block">' + summarize(filename, 25, true, '');
            html += '  <span class="label label-default" id="game_url_parameters_box" style="display: none;">?';
            html += '   <input type="text" id="game_url_parameters"  name="game_url_parameters" class="input-medium" placeholder="parameters.." style="margin: 0;"/>';
            html += '   <span class="close remove_ext_paramas" title="Remove parameters" style="margin: 5px 0 0 2px">X</span>';
            html += '  </span>';
            html += ' </span>';
            html += act_btn;
            html += '</div>';
            $(jQid).html(html);
            $(jQid).fadeIn(300);
            $(".pop_over").popover({trigger: 'hover'});
            reg_showswf_colorbox();
            $('.showextparameters').click(function () {
                $('#game_url_parameters_box').fadeIn();
                $('.showextparameters').closest('.btn-group').removeClass('open');
                return false;
            });
            $('.remove_ext_paramas').click(function () {
                $('#game_url_parameters_box').fadeOut(function () {
                    $('#game_url_parameters').val('');
                });
                return false;
            });
            if (typeof doneFunc != 'undefined')
                doneFunc();
        });
    }

    function delete_file(db_field, filename) {
        var did = $('#myform .edit_id').val();
        smoke.confirm('<?= addslashes(L::alert_del_file_warning); ?>', function (e) {
            if (e) {
                st1 = $.sticky('<?= addslashes(L::alert_deleting_file); ?>', {
                    autoclose: false,
                    position: "top-right",
                    type: "st-info",
                    speed: "fast"
                });
                $.ajax({
                    type: 'POST',
                    'data': {'id': did, 'db_field': db_field, 'filename': filename},
                    url: "<?= url::itself()->url_nonqry(array('del_file' => 1)) ?>",
                    success: function (result) {
                        $.stickyhide(st1.id);
                        obj = JSON.parse(result);
                        if (obj.delete_code === 1)
                            $.sticky(obj.delete_txt, {
                                autoclose: 5000,
                                position: "top-right",
                                type: "st-success",
                                speed: "fast"
                            });
                        else
                            $.sticky(obj.delete_txt, {
                                autoclose: 5000,
                                position: "top-right",
                                type: "st-error",
                                speed: "fast"
                            });
                        pid = $('#' + db_field).parent().find("div[id^='filebox']").attr('id');
                        $('#' + db_field).val('');
                        close_uploader_box('#' + pid);
                    }
                });
            }
        }, {});
    }

    function reg_colorbox(size) {
        size = size || 'auto';
        $('.dataTable img[rel=clbox]').unbind('click').click(function (e) {
            e.stopPropagation();
            $.colorbox({
                href: $(this).data('fullimage'),
                photo: true,
                maxWidth: '90%',
                maxHeight: '90%',
                opacity: '0.2',
                loop: false,
                fixed: true
            });
        });
        $('#myform img[rel=clbox]').unbind('click').click(function (e) {
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
        $('a.showswf').unbind('click').click(function () {
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

    function grabbing_game_file(callback) {
        var did = $('#myform .edit_id').val();
        var gamename = $('#myform #game_name').val();
        if (($('#grab_game_file').val() == $('#grab_game_file').data('default')) || ($('#grab_game_file').val() == ''))
            return false;
        if (typeof (callback) != 'undefined')
            window.callbackjob++;
        var file_addr = $('#grab_game_file').val();
        st1 = $.sticky('<?= addslashes(L::alert_grabbing_file); ?>', {
            autoclose: false,
            position: "top-right",
            type: "st-info",
            speed: "fast"
        });
        if (!$('#grab_game_file').closest('.grab').find('.shoimageloading').length) {
            $('#grab_game_file').closest('.grab').append("<img src='<?= static_url() ?>/images/loading/loading-9.gif' class='shoimageloading'/>");
        }
        // encode and slashes
        data = {'id': did, 'from': file_addr, 'gamename': gamename};
        $.each(data, function (k, v) {
            data[k] = encodeURIComponent(v);
        });
        $.ajax({
            type: 'POST',
            data: data,
            url: "<?= url::itself()->url_nonqry(array('act_grab_game_file' => 1)) ?>",
            success: function (result) {
                $('#grab_game_file').closest('.grab').find('.shoimageloading').remove();
                $.stickyhide(st1.id);
                obj = JSON.parse(result);
                if (obj.grab_code === 1) {
                    $.sticky(obj.grab_txt, {autoclose: 5000, position: "top-right", type: "st-success", speed: "fast"});
                    if (typeof (callback) == 'undefined') {
                        open_uploader_filebox_game_file('#filebox-game_file', 'game_file', obj.file);
                    }
                    $('#game_file').val(obj.file);
                    if (window.get_auto_game_dimension && obj.width > 0)
                        $('#game_width').val(obj.width);
                    if (window.get_auto_game_dimension && obj.height > 0)
                        $('#game_height').val(obj.height);
                }
                else {
                    $.sticky(obj.grab_txt, {autoclose: 5000, position: "top-right", type: "st-error", speed: "fast"});
                    $('#filebox-game_file').html('').fadeOut();
                    $('#game_file').val('');
                    if (window.get_auto_game_dimension) {
                        $('#game_width').val('0');
                        $('#game_height').val('0');
                    }
                }
                $('#grab_game_file').val($('#grab_game_file').data('default') || '');
                if (typeof (callback) != 'undefined') {
                    if (debug)
                        console.log('job upload game file is completed.');
                    window.callbackjob--;
                    eval(callback + '();');
                }

            }

        });
        return true;
    }

    function grabbing_game_img(callback) {
        var did = $('#myform .edit_id').val();
        var gamename = $('#myform #game_name').val();
        if (($('#grab_game_img').val() == $('#grab_game_img').data('default')) || ($('#grab_game_img').val() == ''))
            return false;
        if (typeof (callback) != 'undefined')
            window.callbackjob++;
        var file_addr = $('#grab_game_img').val();
        st2 = $.sticky('<?= addslashes(L::alert_grabbing_file); ?>', {
            autoclose: false,
            position: "top-right",
            type: "st-info",
            speed: "fast"
        });
        if (!$('#grab_game_img').closest('.grab').find('.shoimageloading').length) {
            $('#grab_game_img').closest('.grab').append("<img src='<?= static_url() ?>/images/loading/loading-9.gif' class='shoimageloading'/>");
        }
        // encode and slashes
        data = {'id': did, 'from': file_addr, 'gamename': gamename};
        $.each(data, function (k, v) {
            data[k] = encodeURIComponent(v);
        });
        $.ajax({
            type: 'POST',
            data: data,
            url: "<?= url::itself()->url_nonqry(array('act_grab_game_img' => 1)) ?>",
            success: function (result) {
                $('#grab_game_img').closest('.grab').find('.shoimageloading').remove();
                $.stickyhide(st2.id);
                obj = JSON.parse(result);
                if (obj.grab_code === 1) {
                    $.sticky(obj.grab_txt, {autoclose: 5000, position: "top-right", type: "st-success", speed: "fast"});
                    if (typeof (callback) == 'undefined') {
                        open_uploader_imagebox('#filebox-game_img', 'game_img', obj.file);
                    }
                    $('#game_img').val(obj.file);
                }
                else {
                    $.sticky(obj.grab_txt, {autoclose: 5000, position: "top-right", type: "st-error", speed: "fast"});
                    $('#filebox-game_img').html('').fadeOut();
                    $('#game_img').val('');
                }
                $('#grab_game_img').val($('#grab_game_img').data('default') || '');
                if (typeof (callback) != 'undefined') {
                    if (debug)
                        console.log('job upload game file is completed.');
                    window.callbackjob--;
                    eval(callback + '();');
                }
            }
        });
    }

    function grabbing_featured_img(callback) {
        var did = $('#myform .edit_id').val();
        var gamename = $('#myform #game_name').val();
        if (($('#grab_featured_img').val() == $('#grab_featured_img').data('default')) || ($('#grab_featured_img').val() == ''))
            return false;
        if (typeof (callback) != 'undefined')
            window.callbackjob++;
        var file_addr = $('#grab_featured_img').val();
        st3 = $.sticky('<?= addslashes(L::alert_grabbing_file); ?>', {
            autoclose: false,
            position: "top-right",
            type: "st-info",
            speed: "fast"
        });
        if (!$('#grab_featured_img').closest('.grab').find('.shoimageloading').length) {
            $('#grab_featured_img').closest('.grab').append("<img src='<?= static_url() ?>/images/loading/loading-9.gif' class='shoimageloading'/>");
        }
        // encode and slashes
        data = {'id': did, 'from': file_addr, 'gamename': gamename};
        $.each(data, function (k, v) {
            data[k] = encodeURIComponent(v);
        });
        $.ajax({
            type: 'POST',
            data: data,
            url: "<?= url::itself()->url_nonqry(array('act_grab_featured_img' => 1)) ?>",
            success: function (result) {
                $('#grab_featured_img').closest('.grab').find('.shoimageloading').remove();
                $.stickyhide(st3.id);
                obj = JSON.parse(result);
                if (obj.grab_code === 1) {
                    $.sticky(obj.grab_txt, {autoclose: 5000, position: "top-right", type: "st-success", speed: "fast"});
                    if (typeof (callback) == 'undefined') {
                        open_uploader_imagebox('#filebox-featured_img', 'featured_img', obj.file);
                    }
                    $('#featured_img').val(obj.file);
                }
                else {
                    $.sticky(obj.grab_txt, {autoclose: 5000, position: "top-right", type: "st-error", speed: "fast"});
                    $('#filebox-featured_img').html('').fadeOut();
                    $('#featured_img').val('');
                }
                $('#grab_featured_img').val($('#grab_featured_img').data('default') || '');
                if (typeof (callback) != 'undefined') {
                    if (debug)
                        console.log('job upload game file is completed.');
                    window.callbackjob--;
                    eval(callback + '();');
                }
            }
        });
    }

    function reg_tagsinput() {
        $('#game_tags').tagsinput({
            confirmKeys: [13],
            typeahead: {
                source: function (query) {
                    return $.getJSON("<?= url::itself()->url_nonqry() ?>?gettags=" + query);
                }
            }
        });

    }
    </script>
<?php
get_footer();
?>