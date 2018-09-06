<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Admin_dashboards_controller.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class Admin_DashboardsController extends AdministrationController {

    protected $_model = null;

    function dashboard() {
        $this->islogin();

        /* Visit From Countries (today) */
        $model_1 = new Visit();
        $data = $model_1->select('country,country_code as code,count(*) as visit')
                        ->where(array('date' => date('Y-m-d')))
                        ->groupby('country_code')
                        ->orderby('visit desc')
                        ->limit(8)->exec()->allrows();
        $sum = 0;
        foreach ($data as $k => $v)
            $sum+=intval($v['visit']);
        foreach ($data as $k => &$v)
            $v['ratio'] = $v['visit'] / $sum;
        $this->view->v_countries_today = $data;
        unset($model_1);
        unset($k, $v);
        /* ------------------------- */
        /* Visit (daily) */
        $data = array();
        $limit = 30;
        for ($i = $limit; $i >= 1; $i--) {
            $d = pengu_date(PENGU_DATE_GREGORIAN)->add('d', -1 * $i)->toString('Y-m-d');
            $data[$d] = array('date' => $d, 'visit' => 0);
        }
        $model_1 = new Visit_daily();
        $records = $model_1->select('date,ifnull(tier1_visit,0)+ifnull(tier2_visit,0)+ifnull(tier3_visit,0) as visit')
                        ->where(array('date' => array(pengu_date(PENGU_DATE_GREGORIAN)->add('d', -1 * $limit)->toString('Y-m-d'), '>=')))
                        ->exec()->allrows();

        foreach ($records as $k => $v) {
            $data[$v['date']] = array('date' => $v['date'], 'visit' => $v['visit']);
        }

        $model_2 = new Visit();
        $data[date('Y-m-d')] = $model_2->select('date,count(*) as visit')
                        ->where(array('date' => date('Y-m-d')))
                        ->exec()->current;
        $this->view->v_daily = $data;
        unset($model_1, $model_2);
        unset($data);
        /* ------------------------- */
        /* Top Traders (today) */
        $model_1 = new Trader();
        $data = $model_1->select('title , ifnull(tier1_in_today,0)+ifnull(tier2_in_today,0)+ifnull(tier3_in_today,0)  as total_in, ifnull(tier1_out_today,0)+ifnull(tier2_out_today,0)+ifnull(tier3_out_today,0) as total_out')
                        ->limit(8)->exec()->allrows();
        $this->view->traders_today = $data;
        unset($model_1);
        unset($data);
        /* ------------------------- */
        /* ABS Earns (daily) */

        $site = lib::get_domain(setting::get_data('gamecpm_publisher_site', 'val'));
        $data = array();
        $limit = 30;
        $d1 = pengu_date(PENGU_DATE_GREGORIAN)->add('d', -1 * $limit)->toString('Y-m-d');
        $today = pengu_date(PENGU_DATE_GREGORIAN)->toString('Y-m-d');
        /* sync */

        if (!isset($_COOKIE['synced_gcpm_last_30days'])) {
            setcookie('synced_gcpm_last_30days', 1, time() + 30 * 60, '/');
            $m = new AbsStat();
            $m->sync($today, $site);
        }

        for ($i = $limit; $i >= 1; $i--) {
            $d = pengu_date(PENGU_DATE_GREGORIAN)->add('d', -1 * $i)->toString('Y-m-d');
            $data[$d] = array('date' => $d, 'earns' => 0);
        }

        $model_1 = new AbsStat();
        $records = $model_1->select('date,ifnull(earning,0) as earns')
                        ->where(array(
                            'date' => array($d1, '>='),
                            'site' => $site))
                        ->exec()->allrows();

        foreach ($records as $k => $v) {
            $data[$v['date']] = array('date' => $v['date'], 'earns' => $v['earns']);
        }

        $this->view->abs_daily = $data;
        unset($model_1, $model_2);
        unset($data);
        /* ------------------------- */

        if (!defined('SetEmailFrom') || SetEmailFrom == '')
            perror('<strong>' . L::global_warning . '!</strong> ' . L::alert_smtp_email . ' <a href="' . url::router('adminmainsetting') . '">' . L::sidebar_main_set . '</a> ')->Id('syserror');

        $this->dashboard_smallcharts();
        //$this->dashboard_marketplace();
        $this->latest_themes();
        $this->latest_news();
        $this->private_messages();
        $this->latest_revenue_sharing();
    }

    function dashboard_marketplace() {
        /* MarketPlace */
        $market_place_limit = 7;
        $this->view->link_sale_Data = Post_Log::getLimitedData(1, $market_place_limit);
        $this->view->arcades_for_sale_Data = Post_Log::getLimitedData(2, $market_place_limit);
        $this->view->domain_for_sale_Data = Post_Log::getLimitedData(3, $market_place_limit);
        $this->view->game_sponsership_Data = Post_Log::getLimitedData(4, $market_place_limit);
        $this->view->link_exchanges_Data = Post_Log::getLimitedData(5, $market_place_limit);
        $this->view->requests_Data = Post_Log::getLimitedData(6, $market_place_limit);
        $this->view->market_place_limit = $market_place_limit;
    }

    function dashboard_smallcharts() {
        $today = date('Y-m-d');
        $last6days = date('Y-m-d', strtotime('-6 days'));
        $sql = "
            select date,total_pageview, pageview_avg,game_hits ,bounce_rate
            from( 
               select 
                   date, 
                   (tier1_pageview+tier2_pageview+tier3_pageview) as total_pageview,
                   pageview_avg,
                   game_hits,
                   bounce_rate
               from abs_visit_daily
             union 
                /* Today Data */
              select
                    '{$today}' as date,           
                   (select ifnull(sum(page_view),0) from abs_visit where tier=1 and page_view<100 and date='{$today}')+
                   (select ifnull(sum(page_view),0) from abs_visit where tier=2 and page_view<100 and date='{$today}')+
                   (select ifnull(sum(page_view),0) from abs_visit where tier=3 and page_view<100 and date='{$today}'),       
                   (select ifnull(avg(page_view),0) from abs_visit where page_view<100 and date='{$today}'),  
                   (select sum(game_today_hits) from abs_games),
                   ((select count(*) from abs_visit  where  page_view=1 and date='{$today}')/ (select count(*) from abs_visit where date='{$today}'))
             ) R  where date>='{$last6days}'";
        $model = new Model();
        $data = $model->query($sql)->exec()->allrows();

        $newData = array();
        for ($i = 6; $i >= 0; $i--) {
            $d = date('Y-m-d', strtotime("-{$i} days"));

            $found = array();
            foreach ($data as $row) {
                if ($row['date'] == $d) {
                    $found = $row;
                }
            }

            $newData[] = array(
                $d,
                isset($found['total_pageview']) ? $found['total_pageview'] : 0,
                isset($found['pageview_avg']) ? $found['pageview_avg'] : 0,
                isset($found['game_hits']) ? $found['game_hits'] : 0,
                isset($found['bounce_rate']) ? $found['bounce_rate'] : 0,
            );
        }


        foreach ($newData as $row) {
            $pageview[] = $row[1];
            $pageview_avg[] = round($row[2], 1);
            $gameplays[] = $row[3];
            $bounce_rate[] = round($row[4] * 100);
        }

        $this->view->chart_pageview = $pageview;
        $this->view->chart_pageview_avg = $pageview_avg;
        $this->view->chart_gameplays = $gameplays;
        $this->view->chart_bounce_rate = $bounce_rate;
    }

    function latest_revenue_sharing() {
        if (!convert::to_bool(setting::get_data('feed_auto_downloader', 'val')))
            return false;
        $data = get_daily_ws('latest_revenue_sharing_games');
        if (is_array($data) && !empty($data))
            $this->view->limitfeed = $data;
    }

    function private_messages() {
        $data = get_daily_ws('private_messages');
        if (is_array($data) && !empty($data)) {
            foreach ($data as $msg) {
                if (isset($_COOKIE["dismissMsg{$msg['id']}"]))
                    continue;
                $hfield = "<span class='msgid' style='display:none' data-id='{$msg['id']}'></span>";
                switch ($msg['type']) {
                    case 'info': pinfo($hfield . $msg['message'])->Id('privatemessages');
                        break;
                    case 'warning': warning($hfield . $msg['message'])->id('privatemessages');
                        break;
                    case 'error': perror($hfield . $msg['message'])->Id('privatemessages');
                        break;
                    case 'success': psuccess($hfield . $msg['message'])->Id('privatemessages');
                        break;
                }
            }
        }
    }

    function latest_news() {
        if (isset($_GET['newsid'])) {
            echo @file_get_contents(master_url . '/news-' . $_GET['newsid'] . '.html');
            exit;
        }
        $data = get_daily_ws('latest_news');
        if (is_array($data) && !empty($data))
            $this->view->limitnews = $data;
    }

    function latest_themes() {
        $data = get_daily_ws('latest_themes');
        if (is_array($data) && !empty($data))
            $this->view->limittheme = $data;
    }

}