<?php
get_header();
?>
<div id="content">
    <div class="single_box_outer_most_game">

        <!-- Box Start --> 
        <div class="box3_wrap">
            <div class="box3_header"> <a href="<?=ab_router('homepage')?>"><?=L::alert_back_to_home;?></a></div>
            <div class="box_container" style="width:100%">
                <div style="margin: 40px;">
                    <br/>
                    <font class="heading404"><?=L::alert_page_not_found;?></font>
                    <p style="margin-left: 30px; font-size: 14px"><?=L::alert_worng_url_msg;?></p>
                </div>
            </div>
        </div>
        <!-- Box End -->
    </div>
</div>
<?php get_footer(); ?>
