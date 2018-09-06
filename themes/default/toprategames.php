<?php get_header(); ?>    
<div id="content">
    <div class="single_box_outer_most_game">

        <!-- Box Start --> 
        <div class="box3_wrap">
            <div class="box3_header"><?= ab_page_heading() ?></div>
            <div class="box_container">
                <div style="float:left; width:352px;">
                    <!--ads 300x250-->
                </div>
                <?php
                $ab_result = ab_top_rated_games(null, @$current_category->seo_title, 30);
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
                        <a title="<?= $game->name ?>" href="<?= $url ?>"><?=str::summarize($game->name, 30); ?></a>
                    </div>    
                    <div class="box_lead_most">
                        <?= str::summarize($game->description, 100); ?>
                        <div class="jrating">
                            <div class="reteBox" data-average="<?=$game->rate?>" data-id="4"></div>
                        </div>
                    </div>

                </div>
                <?php endwhile;
                ?>

            <div class="clear"></div>
            <div class="pagination">
                <?= ab_pagination($ab_result) ?>
                <div class="clear"></div>
            </div>

            </div>
        </div>
        <!-- Box End -->
    </div>
</div>
<?php get_footer(); ?>
