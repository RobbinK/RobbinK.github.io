<?php
get_header();
?>  
<div id="content">  
    <!-- Box Start --> 
    <div class="box3_wrap">
        <div class="box3_header">Most Popular Games</div>
        <div class="box_container">
            <?php
            $ab_result = ab_popular_games(15, null, null);
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

        </div>
    </div><!--box3_wrap-->
    <div class="clear"></div>
</div>

<div class="box_gray">
    <?php
    $ab_cat_result = ab_featured_categories();
    while ($cat = $ab_cat_result->the_category()) :
        ?>
        <div class="box_cat_game">
            <div class="box_image_title">
                <img src="<?= ab_category_image($cat) ?>" title="<?= $cat['title'] ?>" width="311" height="86"/> 
                <div class="lable_action_game">
                    <?= $cat['title'] ?>
                </div>
            </div>
            <?php
            $ab_result = ab_new_games(3, $cat['seo_title'], null);
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
                        <a title="<?= $game->name ?>" href="<?= $url ?>"> <?= str::summarize($game->name, 20); ?></a>
                    </div>    
                    <div class="box_lead_most">
                        <?= str::summarize($game->description, 100); ?>
                        <div class="jrating">
                            <div class="reteBox" data-average="<?= $game->rate ?>" data-id="4"></div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div><!--box_cat_game-->
    <?php endwhile; ?>
    <div class="clear"></div>
    <div class="about_us">
        <div class="title_about">
            <?= ab_block_title('block-1') ?>
        </div><!--title_about-->
        <div class="lead_about">
            <?= ab_block_content('block-1') ?>
        </div><!--lead_about-->
    </div><!--about_us-->
</div><!--box_gray-->
<?php get_footer(); ?>