<?php
get_header();
?> 
<div class="clear"></div>
<div class="single_box_outer_most_game"> 
    <div class="box3_wrap_1">
        <div class="box3_header_1"><?=L::forms_favorite_games;?></div>
        <div class="clear"></div>

        <div style="margin-top:20px;">
            <?php
            echo alert();
            
            $ab_result = ab_user_favorite_games(null, null, 30);
            while ($game = $ab_result->the_game()) :
                $url = ab_game_url($game);
                ?>
                <div class="box_item_most ">
                    <div class="box_images_most r5">
                        <a title="<?= $game->name ?>" href="<?= $url ?>"> 
                            <img height="65" width="65" alt="<?= $game->name ?>" class="thumb" src="<?= ab_game_thumb($game) ?>"/> 
                            <img height="65" width="65" alt="<?= $game->name ?>" class="newThumb" src="<?= ab_template_images() ?>/clear.gif"/></a>
                    </div>
                    <div class="box_title_most">
                        <a title="<?= $game->name ?>" href="<?= $url ?>"><?= str::summarize($game->name, 30); ?></a>
                    </div>    
                    <div class="box_lead_most">
                        <?= str::summarize($game->description, 100); ?>
                        <div class="jrating">
                            <div class="reteBox" data-average="<?= $game->rate ?>" data-id="4"></div>
                        </div>
                    </div>

                </div>
            <?php endwhile; ?>
            <div class="clear"></div>
            <div class="pagination">
                <?= ab_pagination($ab_result) ?>
                <div class="clear"></div>
            </div>
            <br />

            <div class="pagination">
                <ul>
                    <?php ab_pagination($ab_result); ?>
                </ul>
            </div> 
        </div>



        <div class="clear"></div>

    </div>
    <div class="box3_wrap_2">
        <div class="box3_header_2">
            <?=L::forms_dashboard;?>
        </div>
        <div class="clear"></div>
        <img src="<?= ab_image_url(Member::data('avatar')) ?>" class="images_profile"/>
        <i><?=L::forms_welcome;?></i> <b><?= Member::data('name'); ?></b>
        <br/>
        <div style="padding-left:50px; margin-top:10px;">
            <a href="<?= ab_router('userprofile') ?>"><?=L::menu_profile;?></a>
            <a href="<?= ab_router('userlogout') ?>"><?=L::menu_logout;?></a> 
        </div>
    </div>
</div>
<?php get_footer() ?>