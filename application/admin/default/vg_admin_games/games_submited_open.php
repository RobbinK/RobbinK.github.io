<input type="hidden" id="id" value="<?= $game->id ?>">
<input type="hidden" id="email" value="<?= @$game->email ?>">
<div class="row-fluid"> 
    <table id="user" class="table table-bordered table-striped" style="width:100%">
        <tbody> 
            <tr>         
                <td width="10%"><?=L::forms_game_name;?></td>
                <td width="50%"><?= $game->game_name ?></td> 
            </tr>
            <tr>         
                <td><?=L::forms_user;?></td>
                <td><?= $game->name ?></td>
            </tr>  
            <tr>         
                <td><?=L::forms_description;?></td>
                <td><?= $game->game_description ?></td>     
            </tr>
            <tr>         
                <td><?=L::forms_game_instruction;?></td>
                <td><?= $game->game_instruction ?></td>     
            </tr>
            <tr>         
                <td><?=L::forms_game_controls;?></td>
                <td><?= $game->game_controls ?></td>     
            </tr>
            <tr>         
                <td><?=L::forms_games_tags;?></td>
                <td><?= $game->game_tags ?></td>      
            </tr>
            <tr>         
                <td><?=L::forms_game_thumb;?></td>
                <td>
                    <?php
                    $imgsrc = ab_submission_images_dir . '/' . $game->game_img;
                    if (!empty($game->game_img) && file_exists($imgsrc)) {
                        list($w, $h) = @getimagesize($imgsrc);
                        $filesize = path::get_file_size($imgsrc);
                        ?>
                        <div style="position: relative; height: 60px; display: block;" >
                            <img src="<?= url::itself()->url_nonqry(array('showimage' => urldecode($game->game_img))) ?>" rel="clbox" > 
                            <span style="top: 0px; position: absolute; margin:0 10px;"><?= str::summarize($game->game_img, 25, true, '') ?></span> 
                            <span class='help-block' style="left: 70px;bottom: 0px;margin: 0;position: absolute;"><span class="muted"><?=L::forms_dimension;?> = <?= "{$w}x{$h}" ?> &nbsp&nbsp&nbsp <?=L::forms_file_size;?> = <?= $filesize ?></span></span>
                        </div>
                        <?php
                    } elseif (!empty($game->game_img))
                        echo "<span class='help-inline'><?=L::alert_file_not_found;?></span>";
                    
                    ?>
                </td>    
            </tr>
            <tr>         
                <td>Game File</td> 
                <td><?php
                    $imgsrc = ab_submission_files_dir . '/' . $game->game_file;
                    if (!empty($game->game_file) && file_exists($imgsrc)) {
                        list($w, $h) = @getimagesize($imgsrc);
                        $filesize = path::get_file_size($imgsrc);
                        ?>
                        <div style="position: relative; height: 60px; display: block;" >
                            <img src="<?= static_url() ?>/images/icons/attachments/attachment_up.png">
                            <span style="top: 0px; position: absolute; margin:0 10pxs;"><?= $game->game_file ?></span>
                            <div class="btn-group" style="position: absolute; top: 23px; margin:0 10px;">
                                <a href="javascript:void(0);" data-toggle="dropdown" class="btn btn-inverse btn-mini dropdown-toggle"><?=L::global_action;?> <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a class="showswf" href="<?= $game->game_file ?>"><i class="icon-eye-open"></i> <?=L::forms_preview;?></a></li> 
                                </ul>
                                <span class='help-block' style="margin: 0;position: absolute;"><span class="muted"><?=L::forms_dimension;?> = <?= "{$w}x{$h}" ?> &nbsp&nbsp&nbsp <?=L::forms_file_size;?> = <?= $filesize ?></span></span>
                            </div>
                        </div>
                        <?php
                    } elseif (!empty($game->game_file))
                        echo "<span class='help-inline'><?=L::alert_file_not_found;?></span>";
                   
                    ?>



                </td>          
            </tr>
            <tr>         
                <td><?=L::forms_categories;?></td>
                <td><?= $game->game_categories ?></td>          
            </tr>
            <tr>         
                <td>Add Time</td>
                <td><?= $game->addtime ? date('Y-m-d H:i', $game->addtime) : '-' ?></td>      
            </tr> 

        </tbody>
    </table> 
</div>