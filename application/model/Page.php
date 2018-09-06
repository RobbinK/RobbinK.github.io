<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Page.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class Page extends Model {

    protected $_table = "abs_pages";
    public $_cache_time = CacheExpireTime;
    private $respage;

    public function have_page() {
        if (is_array($this->respage) && current($this->respage))
            return true;
        else
            return false;
    }

    public function the_page() {
        if (!is_array($this->respage) || empty($this->respage))
            return false;
        $current = current($this->respage);
        next($this->respage);
        return $current;
    }

    public function PagesList() {
        $this->select("pid,page_title,seo_title")->where(array($this->_table . '.status' => 1));
        if (UseCache && intval($this->_cache_time))
            $this->cacheable($this->_cache_time, 'contents');
        $this->respage = $this->exec()->allrows();
        return $this->respage;
    }

    public function showpageById($id) {
        return $this->ShowPage(null, $id);
    }

    public function showpageBySeo($seoName) {
        return $this->ShowPage($seoName, null);
    }

    private function ShowPage($pageSeoName = null, $pageId = null) {
        $cond = array('ifnull(status,0)=1');
        if (is_numeric($pageId))
            $cond[$this->_table . '.pid'] = $pageId;
        else if (is_string($pageSeoName))
            $cond[$this->_table . '.seo_title'] = $pageSeoName;
        $this->select()->where($cond);
        if (UseCache && intval($this->_cache_time))
            $this->cacheable($this->_cache_time, 'contents');
        $res = @$this->exec()->row(0);
        if (isset($res))
            return $res;
    }

}

