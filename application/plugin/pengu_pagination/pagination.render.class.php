<?php

/**
 * pagination class
 *
 * A basic class for creating easy pagination lists
 *
 * @version 	0.1
 * @author 		Christian Weber <christian@cw-internetdienste.de>
 * @link		http://www.cw-internetdienste.de
 *
 * freely distributable under the MIT Licence
 *
 */
class pagination {

// display options
    private $tag_wrapper = 'ul';
    private $tag_item = 'li';
    private $activeclass = 'active';
    private $activelink = false;
    private $attributes;
// jumper options (first / last page link)
    private $jumpers = true;
    private $jumper_first_text = '<<';
    private $jumper_first_title = 'First Page';
    private $jumper_last_text = '>>';
    private $jumper_last_title = 'Last Page';
//  all page link
    public $showall = true;
    public $showall_text = 'All';
// steps options (prev / next page link)
    private $steps = true;
    private $steps_back_text = '<';
    private $steps_back_title = 'Previous Page';
    private $steps_next_text = '>';
    private $steps_next_title = 'Next Page';
// link options
    public $additionalQs=array();
    private $qkey = 'page';
    private $router;
    private $link_text = '##ID##';
    private $link_title = 'Go to page no ##ID##';
// internal options
    private $itemcount = 1;
    private $items_per_page = 10;
    private $maxpages = 10;
    private $currentpage = 1;
    private $totalpages;
    /**
     * __construct function.
     *
     * @access public
     * @param mixed $itemcount
     * @param int $currentpage (default: 1)
     * @param array $options (default: array())
     * @return int
     */
    public function __construct($itemcount, $currentpage = 1, $options = array()) {
        if (!$itemcount || !is_numeric($itemcount)) {
            return false;
        }
        $this->itemcount = (int) (($itemcount >= 1) ? $itemcount : 1);

        if (is_array($options) && count($options) > 0) {
            foreach ($options as $var => $val) {
                if (property_exists('pagination', $var)) {
                    $this->$var = $val;
                }
            }
        }

        if (!$currentpage || !is_numeric($currentpage)) {
            return false;
        }
        $this->currentpage = (int) (($currentpage >= 1) ? $currentpage : 1);

        $this->itself_url = url::link($_SERVER['REQUEST_URI'])->url_nonqry();
        $this->itself_url_filtered = $this->filter_url($this->itself_url);
        $this->itself_qstring_array = $this->qstoarr($_SERVER['QUERY_STRING']);
    }

    /**
     * set_page function.
     *
     * @access public
     * @param mixed $id
     * @return int
     */
    public function set_page($id) {
        if (!$id || !is_numeric($id) || $id <= 0) {
            return false;
        }
        $this->currentpage = (int) $id;
        return true;
    }

    /**
     * get_page function.
     *
     * @access public
     * @return int
     */
    public function get_page() {
        return (int) $this->currentpage;
    }

    /**
     * set_itemcount function.
     *
     * @access public
     * @param mixed $amount
     * @return int
     */
    public function set_itemcount($amount) {
        if (!$amount || !is_numeric($amount) || $amount <= 0) {
            return false;
        }
        $this->itemcount = (int) $amount;
        return true;
    }

    /**
     * get_itemcount function.
     *
     * @access public
     * @return int
     */
    public function get_itemcount() {
        return (int) $this->itemcount;
    }

    /**
     * set_items_per_page function.
     *
     * @access public
     * @param mixed $amount
     * @return int
     */
    public function set_items_per_page($amount) {
        if (!$amount || (!(is_numeric($amount) && $amount > 0) && $amount != 'all')) {
            return false;
        }
        $this->items_per_page = strtolower($amount);
        return true;
    }

    /**
     * get_items_per_page function.
     *
     * @access public
     * @return int
     */
    public function get_items_per_page() {
        return $this->items_per_page;
    }

    /**
     * set_jumpers function.
     *
     * @access public
     * @param mixed $bool
     * @return int
     */
    public function set_jumpers($bool) {
        $this->jumpers = (bool) ($bool) ? true : false;
    }

    /**
     * set_wrapper_tag function.
     *
     * @access public
     * @param mixed $tag
     * @return int
     */
    public function set_wrapper_tag($tag) {
        if (!$tag || !is_string($tag) || empty($tag) || trim($tag) === '') {
            return false;
        }
        $this->tag_wrapper = (string) str_replace(array('<', '>'), '', $tag);
        return true;
    }

    /**
     * get_wrapper_tag function.
     *
     * @access public
     * @return int
     */
    public function get_wrapper_tag() {
        return (string) $this->tag_wrapper;
    }

    /**
     * set_item_tag function.
     *
     * @access public
     * @param mixed $tag
     * @return int
     */
    public function set_item_tag($tag) {
        if (!$tag || !is_string($tag) || empty($tag) || trim($tag) === '') {
            return false;
        }
        $this->tag_item = (string) str_replace(array('<', '>'), '', $tag);
        return true;
    }

    /**
     * get_item_tag function.
     *
     * @access public
     * @return int
     */
    public function get_item_tag() {
        return (string) $this->tag_item;
    }

    /**
     * set_active_class function.
     *
     * @access public
     * @param mixed $class
     * @return int
     */
    public function set_active_class($class) {
        if (!$class || !is_string($class) || empty($class) || trim($class) == '') {
            return false;
        }
        $this->activeclass = (string) $class;
        return true;
    }

    /**
     * get_active_class function.
     *
     * @access public
     * @return int
     */
    public function get_active_class() {
        return (string) $this->activelcass;
    }

    /**
     * set_attributes function.
     *
     * @access public
     * @param array $attributes (default: array())
     * @return int
     */
    public function set_attributes($attributes = array()) {
        if (!is_array($attributes)) {
            return false;
        }
        $this->attributes = $attributes;
        return true;
    }

    /**
     * get_attributes function.
     *
     * @access public
     * @return int
     */
    public function get_attributes() {
        return $this->attributes;
    }

    private function filter_url($url) {
        if ($qpos = strpos($url, '?'))
            $url = substr($url, 0, $qpos);
        $host = $_SERVER['HTTP_HOST'];
        $url = preg_replace('#' . preg_quote($host) . '#i', '', $url);
        $url = preg_replace('/www\./i', '', $url);
        $url = preg_replace('/http:\/\//i', '', $url);
        $url = preg_replace('/https:\/\//i', '', $url);
        return $url;
    }

    private function qstoarr($qs) {
        $qs = trim($qs, ' ?&');
        if (($qpos = strpos($qs, '?')) !== false)
            $qs = substr($qs, $qpos + 1);
        $array = explode('&', $qs);
        $ret = array();
        foreach ($array as $s) {
            if (!empty($s)) {
                @list($k, $v) = explode('=', $s);
                $ret[$k] = $v;
            }
        }
        return (array) $ret;
    }

    public function set_url($url) {
        $this->router = $url;
        if (!empty($url)) {//url
            $this->router_qstring_array = $this->qstoarr(url::link($this->router)->qry_nonurl());
            $this->router_url_filtered = $this->filter_url($this->router);
        }
    }

    public function set_url_qfield($query_filed_name) {
        if (!empty($query_filed_name))
            $this->qkey = $query_filed_name;
    }

    public function set_router($router) {
        $this->router = $router;
    }

    public function set_router_param($query_filed_name) {
        if (!empty($query_filed_name))
            $this->qkey = $query_filed_name;
    }

    private function render_url($page, array $otherParams = array()) {
        $itself_url_filtered = isset($this->itself_url_filtered) ? $this->itself_url_filtered : null;
        $router_url_filtered = isset($this->router_url_filtered) ? $this->router_url_filtered : null;
        $itself_qstring_array = isset($this->itself_qstring_array) ? $this->itself_qstring_array : array();
        $router_qstring_array = isset($this->router_qstring_array) ? $this->router_qstring_array : array();

        if (!empty($otherParams))
            $itself_qstring_array = array_merge($itself_qstring_array, $otherParams);
        /* url */
        if (is_string($this->router)) {
            $qs = array_merge($itself_qstring_array, $router_qstring_array, array($this->qkey => $page));
            /* Same as Itself */
            $pattern = preg_quote($router_url_filtered);
            if (preg_match("#^{$pattern}$#i", $itself_url_filtered))
                return url::link($this->itself_url)->url_nonqry($qs);
            /* another url */
            else
                return url::link($this->router)->fulluri(array_merge(array($this->qkey => $page), $otherParams));
        }
        /*  Router */
        else
        if (is_array($this->router) && !empty($this->router[0])) {
            $params = array($this->qkey => $page);
            if (isset($this->router[1]))
                $params = array_merge($params, $this->router[1]);

            global $router;
            $url = $router->generate($this->router[0], $params);

            $route = $router->getRoute($this->router[0]);
            if (preg_match("@^" . $route->getRegex() . "*$@i", @$itself_url_filtered))
                return url::link($url)->fulluri(@$itself_qstring_array);
            else
                return url::link($url)->fulluri($otherParams);
        }
        /* Empty Router */
        else
        if (empty($this->router)) {
            $qs = array_merge($itself_qstring_array, array($this->qkey => $page), $otherParams);
            return url::link($this->itself_url)->url_nonqry($qs);
        }
    }

    private function match_url($pattern_url, $url) {
        
    }

    /**
     * render function.
     *
     * @access public
     * @return int
     */
    public function render() {
        $this->calculate();
        if ($this->totalpages <= 1)
            return;
        $pages = $this->totalpages;
        if ($pages > $this->maxpages) {
            $pages = $this->maxpages;
        }
        $viewable = (int) ($this->maxpages / 2);
        $start = 1;
        $end = $pages;

        if ($this->currentpage > $viewable) {
            $start = $this->currentpage - $viewable;
            $end = $this->currentpage + $viewable - 1;
        }

        if ($this->currentpage > ($this->totalpages - $viewable)) {
            $end = $this->currentpage + ($this->totalpages - $this->currentpage);
            $start = ($this->totalpages - $this->maxpages) + 1;
        }

        if ($start < 1) {
            $start = 1;
        }
        if ($end > $this->totalpages) {
            $end = $this->totalpages;
        }

        echo '<' . $this->tag_wrapper;
        if (isset($this->attributes['wrapper']) && count($this->attributes['wrapper']) > 0) {
            foreach ($this->attributes['wrapper'] as $key => $item) {
                echo ' ' . $key . '="' . $item . '"';
            }
        }
        echo '>' . PHP_EOL;

        if ($this->jumpers === true && $this->currentpage != 1) {
            echo '<li class="pfirst"><a href="' . $this->render_url(1,$this->additionalQs) . '" title="' . $this->render_text($this->jumper_first_title, 1) . '">' . $this->render_text($this->jumper_first_text, 1) . '</a></li>' . PHP_EOL;
        }

        if ($this->steps === true && $this->currentpage > 1) {
            echo '<li class="pprev"><a href="' . $this->render_url($this->currentpage - 1,$this->additionalQs) . '" title="' . $this->render_text($this->steps_back_title, ($this->currentpage - 1)) . '">' . $this->render_text($this->steps_back_text, ($this->currentpage - 1)) . '</a></li>' . PHP_EOL;
        }

        for ($i = $start; $i <= $end; $i++) {
            $active = false;
            echo '<' . $this->tag_item;

            if (isset($this->attributes['item']) && count($this->attributes['item']) > 0) {
                foreach ($this->attributes['item'] as $key => $item) {
                    if ($key === 'class' && $this->currentpage === $i) {
                        $classexists = true;
                        $item.=' ' . $this->activeclass;
                        $active = true;
                    }
                    echo ' ' . $key . '="' . $this->render_text($item, $i) . '"';
                }
            }

            if ($this->currentpage === $i && !isset($classexists)) {
                echo ' class="' . $this->activeclass . '"';
                $active = true;
            }

            echo '>';

            if ($active == false || $this->activelink == true) {
                echo '<a href="' . $this->render_url($i,$this->additionalQs) . '" title="' . $this->render_text($this->link_title, $i) . '">';
            }

            echo $this->render_text($this->link_text, $i);

            if ($active == false || $this->activelink == true) {
                echo '</a>';
            }

            echo '</' . $this->tag_item . '>' . PHP_EOL;
        }

        if ($this->steps === true && $this->currentpage < $this->totalpages) {
            echo '<li class="pnext"><a href="' . $this->render_url($this->currentpage + 1,$this->additionalQs) . '" title="' . $this->render_text($this->steps_next_title, ($this->currentpage + 1)) . '">' . $this->render_text($this->steps_next_text, ($this->currentpage + 1)) . '</a></li>' . PHP_EOL;
        }

        if ($this->jumpers === true && $this->currentpage != $this->totalpages) {
            echo '<li class="plast"><a href="' . $this->render_url($this->totalpages,$this->additionalQs) . '" title="' . $this->render_text($this->jumper_last_title, $this->totalpages) . '">' . $this->render_text($this->jumper_last_text, $this->totalpages) . '</a></li>' . PHP_EOL;
        }

        if ($this->showall === true && $this->totalpages > 1) {
            $all_class = null;
            if ($this->get_items_per_page() === 'all')
                $all_class = ' class="' . $this->activeall . ' ' . $this->activeclass . '"';
            echo '<li ' . $all_class . '><a href="' . $this->render_url(1, array_merge(array('ipp' => 'all'),$this->additionalQs)) . '" title="' . $this->showall_text . '">' . $this->showall_text . '</a></li>' . PHP_EOL;
        }

        echo '</' . $this->tag_wrapper . '>' . PHP_EOL;
    }

    /**
     * fetch function.
     *
     * @access public
     * @return int
     */
    public function fetch() {
        ob_start();
        $this->render();
        $data = ob_get_contents();
        ob_end_clean();
        return $data;
    }

    /**
     * calculate function.
     *
     * @access private
     * @return int
     */
    private function calculate() {
        if ($this->get_items_per_page() === 'all') {
            $this->totalpages = 1;
            $this->currentpage = 1;
            return;
        }

        $pages = (int) ($this->itemcount / $this->items_per_page);
        if ($this->itemcount % $this->items_per_page !== 0) {
            $pages++;
        }
        $this->totalpages = (int) $pages;

        if ($this->currentpage < 1) {
            $this->currentpage = 1;
        }
        if ($this->currentpage > $this->totalpages) {
            $this->currentpage = $this->totalpages;
        }
    }

    /**
     * render_text function.
     *
     * @access private
     * @param mixed $txt
     * @param mixed $var
     * @param string $tag (default: '##ID##')
     * @return int
     */
    private function render_text($txt, $var, $tag = '##ID##') {
        if (!$txt || !is_string($txt) || empty($txt) || trim($txt) === '') {
            return 'ERROR';
        }
        if (!$var || empty($var) || trim($var) === '') {
            return 'ERROR';
        }
        if (!$tag || !is_string($tag) || empty($tag) || trim($tag) === '') {
            return 'ERROR';
        }

        return str_replace($tag, $var, $txt);
    }

}
