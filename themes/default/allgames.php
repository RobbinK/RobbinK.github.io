<?php get_header(); ?>

    <div id="content">

        <div class="single_box_outer_most_game">

            <div class="left">
                <div class="box3_wrap">
                    <div class="box3_header"><?= ab_category_heading_populargames() ?></div>
                    <div class="box_container">
                        <?php
                        $ab_result = ab_popular_games(12, @$current_category->seo_title, null);
                        while ($game = $ab_result->the_game()) :
                            $url = ab_game_url($game);
                            ?>
                            <div class="box_item_most ">
                                <div class="box_images_most r5">
                                    <a title="<?= $game->name ?>" href="<?= $url ?>">
                                        <img height="65" width="65" alt="<?= $game->name ?>" class="thumb"
                                             src="<?= ab_game_thumb($game) ?>"/>
                                        <img height="65" width="65" alt="<?= $game->name ?>" class="newThumb"
                                             src="<?= ab_template_images() ?>/clear.gif"/></a>
                                </div>
                                <div class="box_title_most">
                                    <a title="<?= $game->name ?>"
                                       href="<?= $url ?>"><?php echo str::summarize($game->name, 30); ?></a>
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
                    </div>
                </div>

                <!--Description-->
                <?php if (!empty($current_category->description)) : ?>
                    <div class="box3_wrap">
                        <div class="box_container page_description">
                            <?= $current_category->description ?>
                        </div>
                    </div>
                <?php endif; ?>
                <!--/Description-->

            </div>


            <!-- Box Start -->
            <div class="box3_wrap">
                <div class="box3_header"><?= ab_category_heading_newgames() ?></div>
                <div class="box_container">
                    <?php
                    $ab_result = ab_all_games(null, @$current_category->seo_title, 66);
                    while ($game = $ab_result->the_game()) :
                    $url = ab_game_url($game);
                    ?>
                    <div class="thumb_wrap">
                        <a title="<?= $game->name ?>" href="<?= $url ?>">
                            <div class="box_images_most r5">
                                <img height="65" width="65" alt="<?= $game->name ?>" class="thumb"
                                     src="<?= ab_game_thumb($game) ?>"/>
                                <img height="65" width="65" alt="<?= $game->name ?>" class="newThumb"
                                     src="<?= ab_template_images() ?>/clear.gif"/>
                        </a>
                    </div>
                </div>
                <?php endwhile; ?>
                <div class="clear"></div>
                <div class="pagination">
                    <?= ab_pagination($ab_result) ?>
                    <div class="clear"></div>
                </div>

            </div>
        </div>
    </div>
    </div>
    <div class="clear"></div>
<?php get_footer(); ?>