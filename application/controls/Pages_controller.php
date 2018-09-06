<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Pages_controller.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class PagesController extends InterfaceController {

    protected $_model = null;

    function __construct() {
        global $router;
        parent::__construct();
    }

    function show($args) {
        $this->MapViewFileName('page.php'); 
        if (isset($args['page_seo']))
            $page = ab_page(input::safe($args['page_seo']));
        elseif (isset($args['page_id']))
            $page = ab_page_byid(input::safe($args['page_id']));
        
        if (isset($page->page_access) && $page->page_access == 2) {
            warning('you have to login to access this page!');
            $this->islogin();
        }
        if ($page) {
            $page->page_content = htmlspecialchars_decode($page->page_content);
            foreach ($page as $k => $v)
                $this->set($k, $v);
        }
        else
            $this->page404();
    }

    function links() {
        $this->MapViewFileName('links.php');
    }

}