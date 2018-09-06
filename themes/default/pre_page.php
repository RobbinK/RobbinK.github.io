<?php get_header(); ?> 
<div id="content">
    <!--Box Start-->
    <div class="single_box_outer_most_game">
        <div class="single_box_inner_most_game"> 
            <div class="single_box_title"><?= ab_page_heading() ?></div>
            <div class="box_container">
                <div class="game_area">
                    <div class="left">
                        <div class="thumb_wrap">
                            <div class="box_images_most r5">
                                <a title="<?= $current_game->name ?>" href="<?= $current_game->play_url ?>" target="<?= $current_game->opening_mode ?>"> 
                                    <img height="65" width="65" alt="<?= $current_game->name ?>" class="thumb" src="<?= ab_game_thumb($current_game) ?>"/> 
                                    <img height="65" width="65" alt="<?= $current_game->name ?>" class="newThumb" src="<?= ab_template_images() ?>/clear.gif"/>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="gamedesc"> 
                        <div class="single_box_title_most">
                            <?= $current_game->name ?>
                        </div> <br />
                        <br />
                        <?= $current_game->description; ?>
                        <br />
                        <div class="tags">
                            <?= ab_show_tags($current_game); ?>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="play_button">
                        <a href="<?= $current_game->play_url ?>" target="<?= $current_game->opening_mode ?>"> <img  alt="Play <?= $current_game->name ?>" src="<?= ab_template_images() ?>/playbtn.png"/> </a> 
                    </div>

                    <div style="margin-top:70px">
                        <?= ab_show_ad('468x60-small-rectangle') ?>
                    </div>
                </div> 
            </div>
        </div>
        <!-- Box End -->


        <div class="ads_right">
            <div class="ads_right_title">
                <?= L::forms_advertisement; ?>
            </div><!--ads_right_title-->
            <div class="clear"></div>
            <div class="box_ads">
                <?= ab_show_ad('300x250-medium-rectangle') ?>
                <!--Ads 300x250 -->
            </div><!--box_ads-->
        </div>
        <!-- Box Start -->
        <div class="left" > 
            <div class="box3_wrap">
                <div class="box3_header"><?= ab_category_heading_populargames() ?></div>
                <div class="box_container">
                    <?php
                    $ab_result = ab_popular_games(21, @$current_category->seo_title, null);
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
            </div>
        </div>
    </div>
</div>
<!-- Box End -->
<?php get_footer(); ?>
