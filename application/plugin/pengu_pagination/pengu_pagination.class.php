<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: pengu_pagination.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:58
##########################################################
 */


include 'pagination.render.class.php';

class pengu_pagination {

    public $current_page = 1;
    public $items_per_page = 20;
    public $num_pages = 10;
    public static $pcnt = 0;
    public $total_rows;
    public $total_pages;
    private $limit_low;
    private $limit_high;
    private $router;
    private $additionalQs = array();
    public $access_all_item = false;

    private function checkIPP() {
        if (isset($_GET['ipp']) && !validate::_is_ajax_request()) {
            $this->set_items_per_page($_GET['ipp']);
        }
    }

    public function set_items_per_page($amount) {
        if (!$amount || (!(is_numeric($amount) && $amount > 0) && strtolower($amount) != 'all')) {
            return false;
        }
        if (strtolower($amount) == 'all' && !$this->access_all_item)
            return false;
        $this->items_per_page = strtolower($amount);
        return true;
    }

    public function merge_model(&$model) {
        $total = null;
        if ($model->caching) {
            $s = new pengu_cache(cache_path() . '/mysql', 'sql_');
            $s->setCacheKey(md5($model->sql));
            $s->expireTime(intval(@$model->cacheTime));
            if ($s->isCached())
                $total = $s->read();
            else {
                $total = $model->getcount();
                $s->write($total);
            }
        }
        else
            $total = $model->getcount();
        if ($total) {
            if (preg_match('#limit\s*(\d+\s*,?\s*\d*)\s*$#i', $model->sql, $match))
                if ($d = explode(',', $match[1]))
                    $li = isset($d[1]) ? $d[1] : $d[0];
            if (isset($li) && $li < $total)
                $total = $li;
            $this->total_rows = $total;
        }
        $this->checkIPP();
        if ($this->items_per_page != 'all') {
            $this->calc();
            $model->limit($this->limit_low, $this->limit_high);
        }
    }

    function limit($return_limit_query = true) {
        $this->checkIPP();
        if ($this->items_per_page == 'all')
            return $return_limit_query ? '' : array();

        $this->calc();
        if ($return_limit_query)
            return " LIMIT {$this->limit_low},{$this->items_per_page}";
        else
            return array($this->limit_low, $this->items_per_page);
    }

    private function calc() {
        $this->total_pages = ceil($this->total_rows / $this->items_per_page);
        if (@$this->current_page === -1)
            $this->current_page = $this->total_pages;
        else if (!isset($this->current_page) || $this->current_page <= 0)
            $this->current_page = 1;

        if ($this->total_rows < $this->items_per_page && $this->total_rows > 0)
            $this->items_per_page = $this->total_rows;

        $this->limit_low = ($this->current_page <= 0) ? 0 : ($this->current_page - 1) * $this->items_per_page;
        if ($this->limit_low > $this->total_rows) {
            //overflow
            $this->limit_low = 0;
            return false;
        }
        if ($this->limit_low + $this->items_per_page > $this->total_rows) {
            $this->limit_high = $this->total_rows - $this->limit_low;
        }
        else
            $this->limit_high = $this->items_per_page;
    }

    public function set_url($url) {
        $this->url = $url;
    }

    public function set_url_qfield($query_filed_name = 'page') {
        $this->url_qfield = $query_filed_name;
    }

    public function set_router($router, $params = null) {
        if (!empty($params) && is_array($params))
            $this->router = array($router, $params);
        else
            $this->router = array($router);
    }

    public function set_router_param($query_filed_name = 'page') {

        $this->router_param = $query_filed_name;
    }

    function render() {
        $options = array(
            'attributes' => array(
                'wrapper' => array('class' => 'paginate'),
            ),
            'items_per_page' => $this->items_per_page,
            'maxpages' => $this->num_pages);

        $pagination = new pagination($this->total_rows, ((isset($this->current_page)) ? $this->current_page : 1), $options);
        $pagination->showall = $this->access_all_item;
        $pagination->additionalQs = $this->additionalQs;

        if (isset($this->url))
            $pagination->set_url($this->url);


        if (isset($this->url_qfield))
            $pagination->set_url_qfield($this->url_qfield);


        if (isset($this->router))
            $pagination->set_router($this->router);
        if (isset($this->router_param))
            $pagination->set_router_param($this->router_param);

        ob_start();
        $pagination->render();
        return ob_get_clean();
    }

    function addQsParam($key, $val) {
        if (is_array($key) && is_array($val)) {
            $data = array_combine($key, $val);
            foreach ($data as $k => $v)
                $this->additionalQs[$k] = $v;
        } else
        if (is_array($key) && $val === null) {
            list($k, $v) = $key;
            $this->additionalQs[$k] = $v;
        } else
        if ($key !== null && $val !== null)
            $this->additionalQs[$key] = $val;
        else
            return false;
        return true;
    }

}
