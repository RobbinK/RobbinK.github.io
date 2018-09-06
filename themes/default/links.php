<?php
get_header();
?>
<div id="content">
    <div class="single_box_outer_most_game">
        <div class="box3_wrap">
            <div class="box3_header">Partners</div>
            <div class="page_container">
                <ul class="links">
                    <?php
                    $ab_result = ab_partners_links(null, _ab_linkspage_links);
                    while ($link = $ab_result->the_link()):
                        ?>
                        <li><a href="<?= $link['url'] ?>" title="<?= $link['title'] ?>"><?= $link['title'] ?></a></li>
                        <?php
                    endwhile;
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php
get_footer();
?>
