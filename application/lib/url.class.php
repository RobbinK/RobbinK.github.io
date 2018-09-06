<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: url.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class url {

    private $url;
    private $query;
    private $domain = null;

    private static function ltrimstr($s1, $s2) {
        if (!empty($s1)) {
            return $s2 . ltrim($s1, $s2);
        }
    }

    public static function selfdomain() {
        if (isset($_SERVER['HTTP_X_FORWARDED_HOST']) && $host = $_SERVER['HTTP_X_FORWARDED_HOST']) {
            $elements = explode(',', $host);
            $host = trim(end($elements));
        } else {
            if (!isset($_SERVER['HTTP_HOST']) || !$host = $_SERVER['HTTP_HOST']) {
                if (!isset($_SERVER['SERVER_NAME']) || !$host = $_SERVER['SERVER_NAME']) {
                    $host = !empty($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '';
                }
            }
        }
        $host = preg_replace('/:\d+$/', '', $host); 

        $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
        $protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos($_SERVER["SERVER_PROTOCOL"], '/')) . $s;  // return http or https
        $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":" . $_SERVER["SERVER_PORT"]);
        return $protocol . "://" . $host . $port;
    }

    private static function get_referrer_domain() {
        $ref = getenv('HTTP_REFERER');
        if (!$ref)
            return;
        if ($ref != strip_tags($ref))
            return;
        return $ref;
    }

    private static function modify_url($url, $mod) {

        $query = null;

        if (strpos($url, '?') !== false) {
            // if 'hello?test' or '?test' bashe qeury barabare test mishe
            $query = substr($url, strpos($url, '?') + 1);
            $query = explode("&", $query);
        }


        // modify/delete data
        if (is_array($query))
            foreach ($query as $q) {
                @list($key, $value) = explode("=", $q);
                if (array_key_exists($key, $mod)) {
                    if ($mod[$key] !== null && $mod[$key] !== false) {
                        $url = preg_replace('/' . $key . '=' . $value . '/i', $key . '=' . $mod[$key], $url);
                    } else {
                        $url = preg_replace('/&?' . $key . '=' . $value . '/i', null, $url); //delete
                    }
                }
            }
        // add new data
        foreach ($mod as $key => $value) {
            if (($value !== null && $mod[$key] !== false) && !preg_match('/' . $key . '=/i', $url)) {
                $url .= '&' . $key . '=' . $value;
            }
        }
        return $url;
    }

    #############################################################
    #############################################################

    public static function itself() {
        $obj = new url;
        if (!isset($_SERVER['REQUEST_URI'])) {
            $obj->url = $_SERVER['SCRIPT_URL'];
            $obj->query = $_SERVER['QUERY_STRING'];
        } else {
            @list($obj->url, $obj->query) = explode('?', $_SERVER['REQUEST_URI']);
        }
        $obj->domain = self::selfdomain();
        return $obj;
    }

    public static function referrer() {
        $ref = self::get_referrer_domain();
        return self::link($ref);
    }

    public static function link($url) {
        $obj = new url;
        @list($obj->url, $obj->query) = explode('?', $url);
        return $obj;
    }

    public static function router($routerName, $params = array()) {
        global $router;
        if (!$router)
            $router = new Router;
        $url = $router->generate($routerName, $params);

        $obj = new url;
        @list($obj->url, $obj->query) = explode('?', $url);
        return $obj;
    }

    public function url_nonqry($array = null) {
        if (empty($this->url))
            return false;

        $url = $this->url;
        $query = null;
        #-----------------------------
        # add query string
        if (is_array($array))
            $query = self::modify_url($query, $array);
        #-----------------------------

        return $this->domain . $url . self::ltrimstr($query, '?');
    }

    public function qry_nonurl($array = null) {
        if (empty($this->url))
            return false;

        $url = null;
        $query = self::ltrimstr($this->query, '?');
        #-----------------------------
        # add or remove other parametrs
        if (is_array($array))
            $query = self::modify_url($query, $array);
        #-----------------------------
        return self::ltrimstr($query, '?');
    }

    public function fulluri($array = null) {
        if (empty($this->url))
            return false;

        $url = $this->url;
        $query = self::ltrimstr($this->query, '?');
        #-----------------------------
        # add or remove other parametrs
        if (is_array($array))
            $query = self::modify_url($query, $array);
        #-----------------------------
        return $this->domain . $url . self::ltrimstr($query, '?');
    }

    public function __toString() {
        return (string) $this->fulluri();
    }

}

