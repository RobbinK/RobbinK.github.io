<div class="install_feed" style="display:none" ><?= json_encode($data); ?></div>
<table id="user" class="table table-bordered table-striped" style="width:100%">
    <tbody> 
        <tr>         
            <td width="10%"><?= L::forms_game_name; ?></td>
            <td width="50%"><?= $data['name'] ?></td> 
        </tr>  
        <tr>         
            <td><?= L::forms_game_description; ?></td>
            <td><?= @str::summarize($data['full_disc'], 250) ?></td>     
        </tr> 
        <tr>         
            <td><?= L::forms_game_thumb; ?></td>
            <td> 
                <?php
                if (!empty($data['thumbnail'])) {
                    ?> 
                    <img src="<?= $data['thumbnail'] ?>" rel="clbox" >   
                    <?php
                }
                ?>             
            </td>    
        </tr>
        <tr>         
            <td><?= L::forms_game_file; ?></td> 
            <td>
                <span class="help-inline">
                    <?php
                    if (!empty($data['file'])) {
                        ?>
                        <div style="position: relative; height: 60px; display: block;" >
                            <img src="<?= static_url() ?>/images/icons/attachments/attachment_up.png"> 
                            <a class="btn btn-mini btn-abs" onclick="feed_showswf(this);
                                    return false;"  style="position: absolute; top: 23px; margin-left: 51px;margin-right:51px;" href="<?= $data['file'] ?>"><?= L::forms_preview; ?></a>   
                        </div>
                        <?php
                    }
                    ?>
                </span> 
            </td>          
        </tr> 
        <tr>         
            <td><?= L::forms_categories; ?></td>
            <td><?= @$data['genres'] ?></td>          
        </tr> 
        <tr>         
            <td><?= L::forms_dimension; ?></td>
            <td><span class="text-info"><?= join('x', array($data['width'], $data['height'])) ?> <?= L::forms_size_px; ?>
                </span></td>          
        </tr>  
        <tr>         
            <td><?= L::forms_other_info; ?></td> 
            <td> 
                <?= convert::to_bool($data['has_ads']) ? '<span class="text-warning"><?=L::forms_ingame_ads;?></span>  <br/>' : '<?= addslashes(L::forms_no_more_info);?>' ?>   
            </td>          
        </tr> 
    </tbody>
</table>

<div id="gedit-modal" class="modal container  hide fade"  tabindex="-1">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3><?= L::forms_edit_submitted; ?></h3>
    </div>
    <div class="modal-body"> 
    </div>
    <div class="modal-footer"> 
        <input class="btn btn-success" type="button" value="<?= addslashes(L::global_save);?>" style="width: 120px" onclick="$('#expressform').submit();">
        <button type="button" data-dismiss="modal" class="btn"><?= L::global_close; ?></button>
    </div>
</div>
<script type="text/javascript">
    var loading_config = {
        'indicatorZIndex': 990,
        'overlayZIndex': 990
    };


    function reg_feed_install() {
        $('.install,.installedit,.installqueue').unbind('click').click(function() {

            var data=$(this).closest('.modal').find('.modal-body .install_feed').html();
            var install_data;
            eval('install_data=' + data+ ';');
            if (!install_data) {
                console.log('No installation data were found!');
                return false;
            }

            window.editopen = false;
            if ($(this).hasClass('install')) {
                install_data['active'] = 1;
            } else if ($(this).hasClass('installedit')) {
                window.editopen = true;
                install_data['active'] = -1;
            } else if ($(this).hasClass('installqueue')) {
                install_data['active'] = 0;
            }

            var st1 = $.sticky(window.loadingIMG+' &nbsp; <?= addslashes(L::alert_installing_game);?>', {autoclose: false, position: "top-right", type: 'st-info', speed: "fast"});
            $.ajax({
                type: 'POST',
                dataType: 'json',
                data: install_data,
                url: '<?= url::router('adminopenfeed') ?>?install',
                success: function (obj) {
                    $.stickyhide(st1.id);
                    $.sticky(obj.msg, {autoclose: 2000, position: "top-right", type: obj.type, speed: "fast"});
                    $('#feed-modal').modal('hide');
                    if (obj.type == 'st-success') {
                        /* callback */
                        if (typeof window.feedJobs_afterinstall != 'undefined' && window.feedJobs_afterinstall.length > 0)
                        {
                            for (var i = 0; i < window.feedJobs_afterinstall.length; i++)
                                eval(window.feedJobs_afterinstall[i]);
                            window.feedJobs_afterinstall = [];

                        }
                        if (window.editopen)
                            feed_editgame(obj.insid);
                    }

                }
            });
            $(this).closest('.btn-group').removeClass('open');
            return false;
        });
    }

    function feed_showswf(t) {
        s = $(t).attr('href');
        $.colorbox({
            href: window.myself_url + '?showswf=' + encodeURIComponent(s),
            maxWidth: '98%',
            maxHeight: '98%',
            opacity: '0.2',
            loop: false,
            fixed: true
        });
        return false;
    }

    function feed_editgame(id) {
        window.EditGameDG = function (id) {
            var $modal = $('#gedit-modal');
            $('body').modalmanager('loading');
            setTimeout(function () {
                $modal.find('.modal-body').load('<?= url::router('editgame') ?>', function () {
                    $modal.find('.modal-body').prepend('<div class="alert alert-info">\n\
                <a data-dismiss="alert" class="close">×</a>\n\
<?= L::alert_submit_msg_line1; ?><br>\n\
<?= L::alert_submit_msg_line2; ?>\n\
                </div>');
                    $modal.modal({width: '70%', height: 370});
                    __bodyLoad();
                    __editform(id);

                });
            }, 100);
        }



<?php $ext = (DEVELOP ? '?' . lib::rand(5) : null) ?>
        var files = [];
        files.push(window.template_url + "/lib/validation/jquery.validate.min.js<?= $ext ?>");
        files.push(window.template_url + "/lib/bootstrap_tagsinput/bootstrap-tagsinput.min.js<?= $ext ?>");
        files.push(window.template_url + "/lib/simple_ajax_uploader/SimpleAjaxUploader.min.js<?= $ext ?>");
        files.push(window.static_url + "/js/multiple-select/jquery.multiple.select.js<?= $ext ?>");
        files.push(window.static_url + "/js/jQuery.unserializeForm/jQuery.unserializeForm.min.js<?= $ext ?>");
        files.push(window.template_url + "/vg_admin_games/editgame.js<?= $ext ?>");
        if (files.length > 0) {
            yepnope({
                load: files,
                complete: function () {
                    window.EditGameDG(id);
                    return true;
                }
            });
            return false;
        }
        window.EditGameDG(id);
    }

    reg_feed_install();
</script> 