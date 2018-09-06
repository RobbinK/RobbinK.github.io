<style type="text/css">
<?php
echo '/* multiple-select.css */ ';
include (static_path() . '/js/multiple-select/multiple-select.css');
echo '/* bootstrap-tagsinput.css */ ';
include (template_path() . '/lib/bootstrap_tagsinput/bootstrap-tagsinput.css');
echo '/* style.css */ ';
include(template_path() . '/lib/pshowlimit/style.css');
?>
</style>
<script type="text/javascript">
<?php
echo '/* pshowlimit.jquery.js */ ';
include(template_path() . '/lib/pshowlimit/pshowlimit.jquery.js');
?>
    window.forms_select_file = '<?= addslashes(L::forms_select_file); ?>';
    window.alert_invalid_image_format = '<?= addslashes(L::alert_invalid_image_format); ?>';
    window.forms_select_another_file = '<?= addslashes(L::forms_select_another_file); ?>';
    window.global_remove = '<?= addslashes(L::global_remove); ?>';
    window.global_action = '<?= addslashes(L::global_action); ?>';
    window.alert_del_file_warning = '<?= addslashes(L::alert_del_file_warning); ?>';
    window.alert_deleting_file = '<?= addslashes(L::alert_deleting_file); ?>';
    window.alert_grabbing_file = '<?= addslashes(L::alert_grabbing_file); ?>';
    window.alert_invalid_link = '<?= addslashes(L::alert_invalid_link); ?>';
    window.global_select_all = '<?= addslashes(L::global_select_all); ?>';
</script>
<div id="exform_div" class="tab-content" style="visibility:visible;">
    <div style="background: #FFFFFF; padding: 5px;"> 
        <form id="expressform" method="post" class="form_validation_reg" novalidate="novalidate">
            <input type="hidden" name="gid" id="gid" class="edit_id"  />
            <dl class="dl-horizontal">

                <dt><label><?= L::forms_game_name; ?></label></dt>
                <dd><div><input type="text" name="game_name" id="game_name"  required></div></dd>

                <dt><label><?= L::forms_game_categories; ?></label></dt>
                <dd><div>
                        <select name="game_categories" id="game_categories"  style="width:350px" class="hidden"  multiple="multiple">
                            <?php
                            if (isset($categoriesaout))
                                while (current($categoriesaout)) : extract(current($categoriesaout));
                                    echo "<option value={$cid}>{$title}</option>";
                                    next($categoriesaout);
                                endwhile;
                            ?>
                        </select>                                                                 
                    </div></dd>

                <dt><label><?= L::forms_game_description; ?></label></dt>
                <dd><div><textarea name="game_description" id="game_description" class="input-xxlarge auto_expand"></textarea></div></dd>

                <dt><label><?= L::forms_game_instruction; ?></label></dt>
                <dd><div><textarea  name="game_instruction" id="game_instruction" class="input-xxlarge auto_expand"></textarea></div></dd>

                <dt><label><?= L::forms_game_controls; ?></label></dt>
                <dd><div><textarea  name="game_controls" id="game_controls" class="input-xxlarge auto_expand"></textarea></div></dd>

                <dt><label><?= L::forms_games_tags; ?></label></dt>
                <dd>
                    <div>
                        <input id="game_tags"  name="game_tags" type="text"   class="input-xxlarge"/>
                        <span class="help-inline"><?= L::forms_press_enter; ?></span>
                    </div>
                </dd>

                <?php if (setting::get_data('meta_description_source', 'val') == 'new'): ?>
                    <dt><label><?= L::forms_meta_description; ?></label></dt>
                    <dd>
                        <div>
                            <textarea name="game_meta_description" id="game_meta_description" class="input-xxlarge auto_expand meta_box"></textarea>
                            <div class="limit-hint">
                                <span></span>
                            </div>
                        </div> 
                    </dd>
                <?php endif; ?>

                <dt><label><?= L::forms_meta_keywords; ?></label></dt>
                <dd>
                    <div>
                        <textarea  name="game_keywords" id="game_keywords" maxlength="500" class="input-xxlarge auto_expand meta_box"></textarea>
                        <div class="limit-hint">
                            <span></span>
                        </div>
                    </div> 
                </dd>

                <dt><label><?= L::forms_ribbon_type; ?></label></dt>
                <dd><div>
                        <select name="ribbon_type" id="ribbon_type" class="input-medium">
                            <option value=""></option>
                            <option value="new"><?= L::forms_new; ?></option>
                            <option value="hot"><?= L::forms_hot; ?></option>
                            <option value="featured"><?= L::forms_featured; ?></option>
                        </select>
                    </div>
                </dd>
                <div  id="ribbon_expiration_wrapper" >
                    <dt><label><?= L::forms_expires_after; ?></label></dt>
                    <dd><div>
                            <input type="text"  id="ribbon_expiration" name="ribbon_expiration" class="input-mini"  data-default="1"/>
                            <span class="help-inline"><?= L::global_days ?></span>
                        </div>
                    </dd>
                </div>

                <div class="formSep"></div>



                <!-- <Upload Game Image> --> 
                <dt><label><?= L::forms_image_source; ?></label></dt>
                <dd><div>
                        <select name="game_image_source" id="game_image_source" style="width: 200px">
                            <option value="0"><?= L::forms_upload_game_image; ?></option>
                            <option value="1"><?= L::forms_grab_remote_image; ?></option> 
                        </select>
                    </div></dd>

                <dt><label><?= L::forms_game_thumb; ?></label></dt>
                <dd id="game_img_wrapper">
                    <input type="hidden" name="game_img" id="game_img"   />
                    <div class="manual">
                        <input type="button" id="upload_game_img" class="btn btn-large clearfix" value="<?= addslashes(L::forms_select_file); ?>"/>
                        <span class="help-inline"><i>PNG, JPG, GIF (<?= L::forms_max_file_size; ?> : 200 <?= L::global_kilo_byte; ?>)</i></span>
                    </div>

                    <div class="grab">
                        <div class="input-append" style="margin-bottom: 0px;">
                            <input type="url" name="grab_game_img" id="grab_game_img"  data-default="http://">
                            <div class="btn-group">
                                <a class="btn"  onclick="__grabbing_game_img();
                                        return false;"><?= L::forms_grab; ?></a>
                                <button class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1">
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="javascript:void(0);" onclick="$('#grab_game_img').val($('#grab_game_img').data('default') || '');
                                            return false;"><i class="icon-ban-circle"></i> <?= L::forms_clean; ?></a></li> 
                                </ul>
                            </div>
                        </div>
                        <em></em>
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
                    <input type="hidden" name="featured_img" id="featured_img"   />
                    <div class="manual">
                        <input type="button" id="upload_featured_img" class="btn btn-large clearfix" value="<?= addslashes(L::forms_select_file); ?>"/>
                        <span class="help-inline"><i>PNG, JPG, GIF (<?= L::forms_max_file_size; ?> : 200 <?= L::global_kilo_byte; ?>)</i></span>
                    </div>

                    <div class="grab"> 
                        <div class="input-append" style="margin-bottom: 0px;">
                            <input type="url" name="grab_featured_img" id="grab_featured_img"  data-default="http://">
                            <div class="btn-group">
                                <a class="btn"  onclick="__grabbing_featured_img();
                                        return false;"><?= L::forms_grab; ?></a>
                                <button class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1">
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="javascript:void(0);" onclick="$('#grab_featured_img').val($('#grab_featured_img').data('default') || '');
                                            return false"><i class="icon-ban-circle"></i> <?= L::forms_clean; ?></a></li> 
                                </ul>
                            </div>
                        </div>
                        <em></em>
                        <span class="help-inline"></span> 
                    </div> 

                    <div id="featured_img_attachment"  style="margin-bottom:15px;">
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
                            <input type="button" id="upload_game_slide_image" class="btn btn-large clearfix" value="<?= addslashes(L::forms_select_file); ?>"/>
                            <span class="help-inline"><i>PNG, JPG, GIF</i></span>
                        </div>
                        <div id="game_slide_image_attachment"  style="margin-bottom:15px;">
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
                <dd><div>
                        <select name="game_file_source" id="game_file_source" style="width: 350px">
                            <option value="0"><?= L::forms_upload_game_file; ?></option>
                            <option value="1"><?= L::forms_grab_remote_file; ?></option>
                            <option value="3"><?= L::forms_remote_game_file; ?></option>
                            <option value="2"><?= L::forms_remote_iframe_link; ?></option>
                            <option value="4"><?= L::forms_embedded_code; ?></option>
                        </select>
                    </div></dd> 

                <dt><label><?= L::forms_game_file; ?></label></dt>
                <dd id="game_file_wraper">
                    <input type="hidden" name="game_file" id="game_file">
                    <div class="manual">
                        <input type="button" id="upload_game_file" class="btn btn-large clearfix" value="<?= addslashes(L::forms_select_file); ?>"/>
                        <span class="help-inline"><i>SWF, DCR, UNITY3D (<?= L::forms_max_file_size; ?> : 50 <?= L::global_mega_byte; ?>)</i></span>
                    </div>

                    <div class="grab"> 
                        <div class="input-append" style="margin-bottom: 0px;">
                            <input type="url" name="grab_game_file" id="grab_game_file"  data-default="http://">  
                            <div class="btn-group">
                                <a class="btn"  onclick="__grabbing_game_file();
                                        return false;"><?= L::forms_grab; ?></a>
                                <button class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1">
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="javascript:void(0);" onclick="$('#grab_game_file').val($('#grab_game_file').data('default') || '');
                                            return false;"><i class="icon-ban-circle"></i> <?= L::forms_clean; ?></a></li> 
                                </ul>
                            </div>
                        </div>
                        <em></em>
                        <span class="help-inline"></span> 
                    </div>  

                    <div class="iframe"> 
                        <div class="input-append" style="margin-bottom: 0px;">
                            <input type="url" name="iframe_game_file" id="iframe_game_file"  data-default="http://">
                            <div class="btn-group"> 
                                <button class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1">
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="#iframe_game_file" class="showswf"><i class="icon-eye-open"></i> <?= L::forms_preview; ?></a></li>
                                    <li><a href="javascript:void(0);" onclick="$('#iframe_game_file').val($('#iframe_game_file').data('default') || '');
                                            return false" ><i class="icon-ban-circle"></i> <?= L::forms_clean; ?></a></li>
                                </ul>
                            </div>
                        </div>
                        <span class="help-inline"></span> 
                    </div>

                    <div class="link"> 
                        <div class="input-append" style="margin-bottom: 0px;">
                            <input type="url" name="link_game_file" id="link_game_file"  data-default="http://">
                            <div class="btn-group"> 
                                <button class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1">
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="#link_game_file"  class="showswf"><i class="icon-eye-open"></i> <?= L::forms_preview; ?></a></li>
                                    <li><a href="javascript:void(0);" onclick="$('#link_game_file').val($('#link_game_file').data('default') || '');
                                            return false" ><i class="icon-ban-circle"></i> <?= L::forms_clean; ?></a></li>
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

                    <div id="game_file_attachment"  style="margin-bottom:15px;">
                        <div id="errormsg-game_file" class="clearfix uploaderror label label-important "></div>	              
                        <div id="pic-progress-wrap-game_file" class="progress-wrap" style="margin-bottom:10px;"></div>	
                        <div id="filebox-game_file" class="clear" style="position: relative;height:60px;padding-top:0px;padding-bottom:10px;"></div>
                    </div>
                </dd> 
                <!-- </Upload Game File> -->  


                <div class="formSep"></div>
                <dt><label><?= L::forms_game_width; ?></label></dt>
                <dd><div>
                        <input type="text" name="game_width" id="game_width"  data-default="0"   style="width:50px" required>
                        <span class='help-inline'><?= L::forms_size_px; ?></span>
                    </div>
                    <em></em>
                </dd>

                <dt><label><?= L::forms_game_height; ?></label></dt>
                <dd><div>
                        <input type="text" name="game_height" id="game_height"  data-default="0"  style="width:50px"  required>
                        <span class='help-inline'><?= L::forms_size_px; ?></span>
                    </div>
                    <em></em>
                </dd>



                <dt><label><?= L::forms_featured; ?></label></dt>
                <dd><div>
                        <select name="game_is_featured"  id="game_is_featured" class="input-mini">
                            <option value="0"><?= L::global_state_no; ?></option>
                            <option value="1"><?= L::global_state_yes; ?></option>
                        </select>
                    </div></dd>

                <dt><label><?= L::global_status; ?></label></dt>
                <dd><div>
                        <select name="game_is_active" id="game_is_active" class="input-medium">
                            <option value="1"><?= L::global_active; ?></option>
                            <option value="0"><?= L::forms_move_to_queue_list; ?></option>
                            <option value="-1"><?= L::global_inactive; ?></option>
                        </select>
                    </div>
                </dd> 
            </dl> 
        </form> 
    </div>
</div> 
<script type="text/javascript">
    $(function () {
<?php
if (setting::get_data('meta_description_source', 'val') == 'new'):
    $lenght = setting::get_data('meta_description_length', 'val');
    if ($lenght <= 0)
        $lenght = 175;
    ?>
            new pShowLimit($('#game_meta_description'), {maxWords: 5, maxChars: <?= $lenght ?>});
<?php endif; ?>
        new pShowLimit($('#game_keywords'), {});
    });
</script>