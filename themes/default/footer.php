<div class="footer_wrap">
    <ul>
        <div class="title_footer"><?= L::forms_site_pages; ?></div>
        <?php ab_show_pages() ?>
        <li><a href="<?= ab_router('allgames') ?>"><?= L::forms_all_games; ?></a></li>
    </ul>  
    <ul>
        <div class="title_footer"><?= L::forms_partners_links; ?></div>
        <?php ab_show_links(20) ?> 
    </ul>  
    <div class="clear"></div>
    <?= show_social_links() ?>
    <div class="copyright">
        Copyright &copy; <?= date('Y') ?> :: All Rights Reserved
        <a href="<?= ab_router('homepage') ?>"><?= ab_site_name() ?></a> 
    </div>
    <div id="poweredby"> 
        <!--{SponsorLink}-->
    </div>
</div> 
</div>
</div>
</body></html> 