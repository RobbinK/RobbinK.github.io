<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: ab_ui_visit_ulib.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */


function _visitor_init() {
    _visitor_impression();
//    $cn = 'ab_fbd695a651716a3126c7e2e768041b7b'; //ab_cookie_isenabled  
//    if (isset($_COOKIE[$cn]) && $_COOKIE[$cn] == 1) {
//        _visitor_impression();
//    } elseif (isset($_SESSION[$cn]) && $_SESSION[$cn] == 0) { //ab_cookie_isenabled =0
//        _visitor_impression();
//    } else {
//        // load cookie detector
//        event::register_onLoadView('visitor_detect_cookie_init', 9);
//    }
}

//function _visitor_detect_cookie_init(&$ViewContent) {
//    ob_start();
//    if (!js::loadedJquery())
//        js::load(lib_path() . '/agent/lib/detectcookie/jx/jx.min.js', array(JS_FORCELOAD => 1));
//    js::load(lib_path() . '/agent/lib/detectcookie/detect_cookie_jx.js', array(JS_FORCELOAD => 1));
//    $html = ob_get_clean();
//
//    if (preg_match("/<\/body>/i", $ViewContent))
//        $ViewContent = preg_replace("/<\/body>/i", "{$html}</body>", $ViewContent);
//    else if (preg_match("/<\/head>/i", $ViewContent))
//        $ViewContent = preg_replace("/<\/head>/i", "{$html}</head>", $ViewContent);
//    else if (preg_match("/<body>/i", $ViewContent))
//        $ViewContent = preg_replace("/<body>/i", "<body>{$html}", $ViewContent);
//}
//function _visitor_detect_cookie() {
//    if (isset($_GET['detected_cookie'])) {
//        $cn = 'ab_fbd695a651716a3126c7e2e768041b7b'; //ab_cookie_isenabled
//        if ($_GET['detected_cookie'] == 1) {
//            setcookie($cn, '1', time() + 7 * 24 * 60 * 60, '/');
//            $_COOKIE[$cn] = 1;
//        } else {
//            $_SESSION[$cn] = 0; /* cookie is not enable */
//        }
//        //for first impression
//        _visitor_impression();
//    }
//}
//function _visitor_cookie_is_enable() {
//    $cn = 'ab_fbd695a651716a3126c7e2e768041b7b'; //ab_cookie_isenabled
//    if ((isset($_COOKIE[$cn]) && $_COOKIE[$cn] == 1))
//        return true;
//
//    elseif (isset($_SESSION[$cn]) && $_SESSION[$cn] == 0)
//        return false;
//    return null;
//}

function _visitor_append_data($name, $val, $expire = 86400) {
    /* Save site to  Cookie Or session */
    $ldata = _visitor_get_data($name);
    $data = array();
    if (!empty($ldata)) {
        $data = explode('|', $ldata);
        if (!empty($data))
            foreach ($data as $k => $v) {
                if (strcasecmp($val, $v) == 0)
                    unset($data[$k]);
            }
    }
    array_push($data, $val);
    _visitor_save_data($name, join('|', $data), $expire);
}

function _visitor_save_data($name, $val, $expire = 86400) {
    $hashname = 'ab_' . md5($name);
//    if (_visitor_cookie_is_enable()) {
    $data = encrypt($val);
    setcookie($hashname, $data, time() + $expire, '/');
    $_COOKIE[$hashname] = $data;
//    }
//    else {
//    $_SESSION[$name] = $val;
//    }
}

function _visitor_get_data($name) {
    $hashname = 'ab_' . md5($name);
    if (isset($_COOKIE[$hashname]))
        return @decrypt($_COOKIE[$hashname]);
//    else
//    if (isset($_SESSION[$name]))
//        return $_SESSION[$name];
}

function _visitor_impression() {
    $today = pengu_date()->toString("Y-m-d");
    $ip = agent::remote_info_ip();
    $country = agent::remote_info_country();
    $country_code = agent::remote_info_country_code();
    $tier = agent::remote_info_tier();
    if (!$tier)
        $tier = 3;
    $referrer = agent::remote_info_referrer();
    $referrer_zone = lib::get_domain($referrer, false);

    //check New Visit
    $new_visitor = false;
    $modelV = new Model;
    $modelV->settable("abs_visit");
    // update if the impression already exists
    if (!agent::is_bot() && !$modelV->update(array('page_view=ifnull(page_view,0)+1', 'utime' => time()))->where(array('ip' => $ip, 'date' => $today))->exec()) {

        if (!empty($referrer)) {
            /* Find Trader's Id */
            $modelT = new Trader;
            $trader = $modelT->alias('T')
                            ->select("T.id as trader_id")
                            ->innerjoin('abs_traders_domains', 'U')
                            ->on('T.id=U.tid')
                            ->where(array(condition(array('site_url' => $referrer, 'site_url' => $referrer_zone), 'or'), 'U.type' => 1, 'U.status' => 1))->exec();

            if ($trader->numrows() > 0) {
                $trader_id = $trader->current()->trader_id;
                _visitor_save_data('visitor_rtid', $trader_id);

                /* update Trader */
                $modelT->update(array(
                    "tier{$tier}_credits=ifnull(tier{$tier}_credits,0)+ifnull(trade_ratio,1)",
                    "tier{$tier}_in_today=ifnull(tier{$tier}_in_today,0)+1",
                    "tier{$tier}_in_overall=ifnull(tier{$tier}_in_overall,0)+1"
                ))->where(array('id' => $trader_id))->exec();

                _visitor_impression_save_trader_geo($trader_id, $country_code, 'in');

                /* Save Referrer to  Cookie Or session */
                _visitor_append_data('visitor_insites', $referrer, 24 * 60 * 60);
                if (strcasecmp($referrer, $referrer_zone) != 0)
                    _visitor_append_data('visitor_insites', $referrer_zone, 24 * 60 * 60);
            }
            unset($trader); //unset object
            unset($modelT); //unset model
        }


        /* Save Visit */
        $visit = array(
            'ip' => $ip,
            'country' => $country,
            'country_code' => $country_code,
            'referrer' => $referrer,
            'tier' => $tier,
            'page_view' => 1,
            'date' => $today,
            'ctime' => time(),
            'utime' => time(),
        );
        if (isset($trader_id))
            $visit['trader_id'] = $trader_id;
        $modelV->insert($visit)->exec();
    }
    unset($modelV);
}

function _visitor_impression_save_trader_geo($tid, $country_code, $type) {
    // save to geo table report

    $model = new Trader_geo;
    $save = array();
    if ($type == 'in') {
        $save = array('in_today=ifnull(in_today,0)+1', 'in_total=ifnull(in_total,0)+1');
    } elseif ($type == 'out') {
        $save = array('out_today=ifnull(out_today,0)+1', 'out_total=ifnull(out_total,0)+1');
    }
    else
        return false;
    if (!$model->update($save)->where(array('tid' => $tid, 'country_code' => $country_code))->exec()) {
        $save = array_merge($save, array('tid' => $tid, 'country_code' => $country_code));
        $model->insert($save)->exec();
    }
    unset($model);
}

function _visitor_hitgame() {
    if (!Member::isLogin()) {
        $last = intval(_visitor_get_data('visitor_played'));
        $last++;
        _visitor_save_data('visitor_played', $last, 24 * 60 * 60);
    }
}

function _visitor_valid() {
    if (!Member::isLogin()) {
        $last = intval(_visitor_get_data('visitor_played'));
        $max = intval(setting::get_data('max_visitor_played', 'val'));
        if ($max > 0 && $last >= $max) {
            return false;
        }
    }
    return true;
}

function _visitor_outbound($gid) {
    if (!isset($gid) || !is_numeric($gid))
        return false;
    /* sort */
    $sort = array(5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2, 2, 2, 2, 1);
    shuffle($sort);
    $sort = array_unique($sort);
    $sort = join(',', $sort);
    /* last out/in site */
    $Vin = _visitor_get_data('visitor_insites');
    $Vout = _visitor_get_data('visitor_outsites');
    $x = array();
    $lastvisitedSites1 = '';
    $lastvisitedSites2 = '';
    $lastvisitedSites3 = '';
    $lastvisitedSites4 = '';
    if (!empty($Vin))
        $x = array_merge($x, explode('|', $Vin));
    if (!empty($Vout))
        $x = array_merge($x, explode('|', $Vout));
    if (!empty($x)) {
        array_walk($x, create_function('&$v', '$v=preg_quote(lib::get_domain($v));'));
        $lastvisitedSites1 = " and P.url not REGEXP '(" . join('|', $x) . ")'";
        $lastvisitedSites2 = " and site_url not REGEXP '(" . join('|', $x) . ")'";
        $lastvisitedSites3 = " site_url not REGEXP '(" . join('|', $x) . ")'";
        $lastvisitedSites4 = " url not REGEXP '(" . join('|', $x) . ")'";
    }


    $sql = "
select T.id as trader_id,  T.has_plug
from( 
      /* forced and has plugs */
      select  T.id ,T.title, T.trade_ratio, 1 as has_plug, tier1_credits , tier2_credits , tier3_credits , T.status  , 1 as priority , T.speed
      from  abs_traders T inner join abs_trade_plugs P on(T.id=P.tid)  
      where T.forced_hits>0 and P.status=1  and  P.gid={$gid} {$lastvisitedSites1}
    union  
      /* forced and send-homepage */
      select id  ,  title ,  trade_ratio , 0 as has_plug , tier1_credits , tier2_credits , tier3_credits,   status  ,  2 as priority  ,speed
      from  abs_traders   
      where  forced_hits>0 and send_to_homepage=1
    union 
      /* has plugs */
      select  T.id ,T.title, T.trade_ratio, 1 as has_plug ,T.tier1_credits , T.tier2_credits , T.tier3_credits , T.status  , 3 as priority , T.speed
      from  abs_traders T inner join abs_trade_plugs P on(T.id=P.tid)  
      where T.forced_hits=0  and  P.gid={$gid} {$lastvisitedSites1} 
    union       
      /* send-homepage */
      select id , title ,trade_ratio , 0 as has_plug, tier1_credits , tier2_credits , tier3_credits,   status  ,  4 as priority  ,speed
      from  abs_traders   
      where  forced_hits=0  and  send_to_homepage=1
 ) T
 where T.status=1
 and T.tier1_credits>=1 and T.trade_ratio>0
 and (select count(id) from abs_traders_domains  where status=1 and type=2 and tid=T.id {$lastvisitedSites2})>0
 order by T.priority , field(speed,{$sort}) , rand() limit 1";

    $model = new Model;
    $result = $model->query(trim($sql))->exec();
    if ($result->numrows()) {
        $trader_id = $result->current()->trader_id;

        $tier = agent::remote_info_tier();
        $has_plug = $result->current()->has_plug;
        if ($has_plug == 1) {
            $model = new Model;
            $model->settable('abs_trade_plugs');
            $result = $model->alias('P')
                            ->select('url as url')
                            ->where(array(
                                'tid' => $trader_id,
                                'gid' => $gid,
                                'status' => 1,
                                $lastvisitedSites4
                            ))->orderby('rand()')->exec();
            if ($result->numrows()) {
                $url = $result->current()->url;
            }
        } else {

            $model = new Model;
            $model->settable('abs_traders_domains');
            $result = $model->select('site_url')
                            ->where(array(
                                'tid' => $trader_id,
                                'type' => 2,
                                'status' => 1,
                                $lastvisitedSites3
                            ))->orderby('rand()')->exec();
            if ($result->numrows()) {
                $url = $result->current()->site_url;
            }
        }
        /* Save url to  Cookie Or session */
        _visitor_append_data('visitor_outsites', $url, 24 * 60 * 60);


        $modelT = new Trader;

        /* Get refferr  trader */
        $Rtid = _visitor_get_data('visitor_rtid');
        if (!empty($Rtid)) {
            $modelT->update(array(
                "convert_today=ifnull(convert_today,0)+1",
                "convert_overall=ifnull(convert_overall,0)+1",
            ))->where(array('id' => $Rtid))->exec();
        }

        /* update Trader */
        $upd = $modelT->update(array(
                    "tier{$tier}_credits=ifnull(tier{$tier}_credits,0)-1",
                    "tier{$tier}_out_today=ifnull(tier{$tier}_out_today,0)+1",
                    "tier{$tier}_out_overall=ifnull(tier{$tier}_out_overall,0)+1"
                ))->where(array('id' => $trader_id))->exec();

        _visitor_impression_save_trader_geo($trader_id, agent::remote_info_country_code(), 'out');

        if ($upd) {
            $url = trim($url);
            if (!preg_match('/^https?\:\/\//i', $url))
                $url = "http://" . $url;
            ref($url)->redirect();
            exit;
        }
    }
    $send_url_if_no_trader = setting::get_data('send_url_if_no_trader', 'val');
    if (!empty($send_url_if_no_trader) && validate::_is_URL($send_url_if_no_trader))
        ref($send_url_if_no_trader)->redirect();

    $ab_result = ab_select_game_byid($gid);

    if ($ab_result->have_games()) {
        $game = $ab_result->the_game();
        if (!empty($game->category_seotitle))
            $gameUrl = ab_router('playgame', array(
                'category_seo' => @$game->category_seotitle,
                'category_id' => $game->category_id,
                'game_id' => $game->id,
                'game_seo' => $game->seotitle
            ));
        else
            $gameUrl = ab_router('playgame2', array(
                'game_id' => $game->id,
                'game_seo' => $game->seotitle
            ));
        ref($gameUrl)->redirect();
    }
    // They can play game :) 
}