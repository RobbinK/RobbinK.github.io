<div id="exform_div" class="tab-content" style="visibility:visible;">
    <div style="background: #FFFFFF; padding: 5px;"> 
        <form id="expressform" method="post" action="<?= url::router('admingamecomments') ?>" class="form_validation_reg" novalidate="novalidate" onsubmit="return false">
            <input type="hidden" name="id" value="<?= $_GET['id'] ?>" class="edit_id"  />

            <?php
            global $agoLanguage;
            $table = array();
            if (isset($name))
                $table['<th>'.L::forms_user.'</th>'] = '<td>' . $name . '</td>';
            if (isset($email))
                $table['<th>'.L::forms_email.'</th>'] = '<td>' . $email . '</td>';
            if (isset($website))
                $table['<th>'.L::global_url.'</th>'] = '<td>' . $website . '</td>';
            if (isset($ip))
                $table['<th>'.L::forms_ip.'</th>'] = '<td>' . $ip . '</td>';
            if (isset($country))
                $table['<th>'.L::global_country.'</th>'] = '<td>' . @agent::country($country, 'country') . '</td>';
            if (isset($time))
                $table['<th>'.L::global_time.'</th>'] = '<td>' .pengu_date::ago($time, lang_isrtl(), $agoLanguage) . '</td>';
            ?>

            <table class="table table-striped table-striped">
                <thead>
                    <tr><?= join("\n", array_keys($table)) ?></tr>
                </thead>
                <tbody> 
                    <tr><?= join("\n", array_values($table)) ?></tr> 
                </tbody>
            </table> 

            <br><br>
            <p style="color: gray;"><?=L::forms_comment;?></p>
            <div class="clearfix"> 
                <p class='well form-inline'><?= @$comment ?></p> 
            </div>

            <p style="color: gray;"><?=L::forms_reply;?></p>
            <input type="hidden" id="email" value="<?= $email ?>" />
            <textarea name="response" id="response" class="input-xxlarge auto_expand" style="height: 60px"><?= @$response ?></textarea>

            <div>
                <input class="btn btn-success save" type="submit" value="<?= addslashes(L::forms_send);?>" style="width: 80px"/> 
                &nbsp;&nbsp;
                <input class="btn bt_cancel" type="button" name="close" value="<?= addslashes(L::global_close);?>" onclick="$.colorbox.close();
                return false;" style="width: 80px"/>
            </div>



        </form> 
    </div>
</div>

<script type="text/javascript">
            var loading_config = {
                'indicatorZIndex': 9999,
                'overlayZIndex': 9999
            };

            $(document).ready(function() {
                $('.auto_expand').autosize();
                $('.bt_cancel').click(function() {
                    $.prompt.close();
                });
                $('.save').click(function() {
                    $('#expressform').showLoading(loading_config);
                    data = {
                        comment: $('#comment').val(),
                        response: $('#response').val(),
                        email: $('#email').val(),
                        id: ($('.edit_id').length > 0 ? $('.edit_id').val() : null)
                    };
                    // encode and slashes
                    $.each(data, function(k, v) {
                        data[k] = encodeURIComponent(v);
                    }); // \<\?php foreach($_POST as &$v)  $v = rawurldecode($v);

                    $.ajax({
                        type: 'POST',
                        data: data,
                        url: "<?= url::itself()->url_nonqry(array('save' => 1)) ?>",
                        success: function(result) {
                            $('#expressform').hideLoading();
                            obj = JSON.parse(result);
                            if (obj.save_code === 1) {
                                $.sticky(obj.save_txt, {autoclose: 3000, position: "top-right", type: "st-success", speed: "fast"});
                                $.colorbox.close();
                                oTable.fnStandingRedraw();
                            }
                            else {
                                $.sticky("<?= addslashes(L::global_error);?>! " + obj.save_txt, {autoclose: 3000, position: "top-right", type: "st-error", speed: "fast"});
                            }

                        }
                    });
                    return false;
                });

            });
</script>