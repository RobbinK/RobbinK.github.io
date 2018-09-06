<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: ws_uclass.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:58
##########################################################
 */


define('ws_url', master_url . '/ws.html');

class wsUclass {

    private function _ws($url, $remote_func_name, $params) {
        static $chk_connection;
        if (!isset($chk_connection) && !$this->is_connectted())
            return false;
        $chk_connection = true;

        require_once (lib_path() . '/nusoap_0.9.5/nusoap.php');
        $client = new nusoap_client($url);
        $err = $client->getError();

        if ($err) {
            $err = '<h2>Constructor error</h2><pre>' . $err . '</pre>';
            $res = false;
        } else {
            $client->soap_defencoding = 'UTF-8';
            $result = $client->call($remote_func_name, $params);

            if ($client->fault) {
                $err = $client->getError();
                $res = false;
            } else {
                $err = $client->getError();
                if ($err) {
                    $res = false;
                }
                else
                    $res = $result;
            }
        }
        return $res;
    }

    function is_connectted() {
        $faultcode = null;
        $faultstring = null;
        $connected = @fsockopen(master_domain, 80, $faultcode, $faultstring, 7);
        if ($connected) {
            fclose($connected);
            return true;
        }
        return false;
    }

    function get_from_main_by_ws($remote_func_name, array $params = array()) {
        $params[] = ab_user_id;
        $params[] = ab_user_country_code;
        if (function_exists('lang'))
            $params[] = lang();
        $data = $this->_ws(ws_url, $remote_func_name, $params);
        return $data;
    }

    function get_from_feed_by_ws($remote_func_name, array $params = array()) {
        $params[] = ab_user_id;
        $params[] = ab_user_country_code;
        if (function_exists('lang'))
            $params[] = lang();
        $data = $this->_ws(ws_url, $remote_func_name, $params);
        return $data;
    }

    function get_from_theme_by_ws($remote_func_name, array $params = array()) {
        $params[] = ab_user_id;
        $params[] = ab_user_country_code;
        if (function_exists('lang'))
            $params[] = lang();
        $data = $this->_ws(ws_url, $remote_func_name, $params);
        return $data;
    }

}