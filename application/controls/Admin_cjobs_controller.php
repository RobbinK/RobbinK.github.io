<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Admin_cjobs_controller.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:56
##########################################################
 */


class Admin_cjobsController extends Controller
{

    protected $_model = null;
    private $yesterday;

    function __construct()
    {
        parent::__construct();
        $this->view->disable();
        $this->yesterday = date("Y-m-d", strtotime('-1 days'));
    }

    function log_start()
    {
        microtimer::start();
        if (!file_exists(tmp_path() . '/logs/cronjobs/'))
            rmkdir(tmp_path() . '/logs/cronjobs/');


        $logfile = tmp_path() . '/logs/cronjobs/cron_manageablerun_' . date('Y') . '.log';
        echo "check the cron log history <a href='" . tmp_url() . '/logs/cronjobs/cron_manageablerun_' . date('Y') . ".log'>here</a><br>";
        global $fhandel;
        if (!$fhandel = fopen($logfile, 'a')) {
            $fhandel = false;
            return false;
        }
        $this->log_insert('--------');
        $this->log_insert('Request for starting cron at ' . date('Y-m-d H:i:s'));
    }

    function log_insert($content)
    {
        global $fhandel;
        if (!$fhandel)
            return false;
        fwrite($fhandel, $content . PHP_EOL);
    }

    function log_end()
    {
        global $fhandel;
        if (!$fhandel)
            return false;
        $this->log_insert('Finish: ' . date('Y-m-d H:i:s') . '   Duration : ' . round(microtimer::stop(), 5));
        fclose($fhandel);
        $fhandel = false;
    }

    function manageable_run()
    {
        $this->log_start();
        if (agent::get_client_ip() != $_SERVER['SERVER_ADDR'] && strpos(Setting::get_data('myserver_ips', 'val'), agent::get_client_ip()) === false) {
            $this->log_insert('Access denied! (agent:' . agent::get_client_ip() . ')');
            die('Access denied! (your ip address : ' . agent::get_client_ip() . ')');
        }
        set_time_limit(20 * 60);
        $this->activating_queue_games();
        $this->visit_daily();
        $this->visit_countries_daily();
        $this->trader_history();
        if (date('H:i') >= '00:00' && date('H:i') <= '02:00') {
            $this->reset();
        } else {
            $this->log_insert('Time was not correct for reset data (time:' . date('H:i') . ')');
        }
        $this->clean_uploadtmp_folder();
        $this->log_end();
        echo "done!";
    }

    function clean_uploadtmp_folder()
    {

        function rrexpiredir($dir)
        {
            if (is_dir($dir)) {
                $objects = scandir($dir);
                foreach ($objects as $object) {
                    if ($object != "." && $object != "..") {
                        if (filetype($dir . "/" . $object) == "dir")
                            rrexpiredir($dir . "/" . $object);
                        else
                            if ((filemtime($dir . "/" . $object) + 3600) < time()) {
                                @unlink($dir . "/" . $object);
                            }
                    }
                    reset($objects);
                    @rmdir($dir);
                }
            }
        }

        rrexpiredir(content_path() . '/upload/tmp');
    }

    function activating_queue_games()
    {
        $limit = intval(Setting::get_data('daily_game_installation', 'val'));
        if ($limit <= 0)
            return;

        $model = new Game();
        if ($res = $model->update(array(
            'game_is_active' => 1,
            'game_adddate' => time(),
            'game_upddate' => time()
        ))->where(array('game_is_active' => 0))->limit($limit)->exec()
        )
            $this->log_insert($res . ' game(s) were activated in queue list.');

        $model = new MobileGame();
        if ($res = $model->update(array(
            'game_is_active' => 1,
            'game_adddate' => time(),
            'game_upddate' => time()
        ))->where(array('game_is_active' => 0))->limit($limit)->exec()
        )
            $this->log_insert($res . ' game(s) were activated in queue list.');
    }

    function trader_history()
    {
        /* it should be run at first time . */
        /* Trader History */
        $modelV = new Visit();
        $modelTH = new Trader_history();
        $modelT = new Trader();
        $tdata = $modelT->select('id,tier1_in_today,tier2_in_today,tier3_in_today,tier1_out_today,tier2_out_today,tier3_out_today,convert_today')->exec();

        while ($tdata->fetch()) {
            $t = $tdata->current();
            $t->view_avg = 0;
            $t->bounce_rate = 0;
            /* get trader's visitors info */
            $resV = $modelV->select("
                     ifnull(avg(page_view),0) as view_avg,
                     (select count(*) from abs_visit   where  page_view=1 and date='{$this->yesterday}' and trader_id={$t->id})/count(*) as bounce
                    ")->where(array('date' => $this->yesterday, 'page_view<100', 'trader_id' => $t->id))->exec();

            if ($resV->numrows()) {
                $t->view_avg = $resV->current['view_avg'] ? $resV->current['view_avg'] : 0;
                $t->bounce_rate = $resV->current['bounce'] ? $resV->current['bounce'] : 0;
            }


            if (!$modelTH->where(array('date' => $this->yesterday, 'tid' => $t->id))->getcount()) {
                $modelTH->insert(array(
                    'tid' => $t->id,
                    'date' => $this->yesterday,
                    'tier1_in' => $t->tier1_in_today,
                    'tier2_in' => $t->tier2_in_today,
                    'tier3_in' => $t->tier3_in_today,
                    'tier1_out' => $t->tier1_out_today,
                    'tier2_out' => $t->tier2_out_today,
                    'tier3_out' => $t->tier3_out_today,
                    'convert' => $t->convert_today,
                    'pageview_avg' => $t->view_avg,
                    'bounce_rate' => $t->bounce_rate,
                ))->exec();
            }
            unset($resV);
            unset($t);
        }
    }

    function visit_countries_daily()
    {
        /* it should be run at first time . */
        /* visit & view */
        $model = new Visit;
        $res = $model->alias('V')->select("
                     V.country_code
                     ,count(*) as visit
                     ,sum(V.page_view) as  view 
                     /*,count(*)/ (select count(*) from abs_visit and date='{$this->yesterday}' ) as tier_percent*/
                     ,avg(V.page_view) as view_avg  
                     ,(select count(*) from abs_visit   where  country_code=V.country_code and  page_view=1 and date='{$this->yesterday}')/count(*) as bounce  
                    ")->where(array('date' => $this->yesterday, 'page_view<100'))->groupby('V.country_code')->exec();

        $model = new Visit_countries_daily();
        while ($res->fetch()) {
            if (!$model->where(array('date' => $this->yesterday, 'country_code' => $res->current()->country_code))->getcount())
                $model->insert(array(
                    'date' => $this->yesterday,
                    'country_code' => $res->current()->country_code,
                    'visit' => $res->current()->visit,
                    'pageview' => $res->current()->view,
                    'pageview_avg' => $res->current()->view_avg,
                    'bounce_rate' => $res->current()->bounce,
                ))->exec();
        }
    }

    function visit_daily()
    {
        /* it should be run at first time . */
        /* Daily Visit report */

        /* game_hits */
        $model = new Game();
        $game_hits = $model->sum('game_today_hits')->exec()->current()->sum;


        /* visit & view */
        $model = new Visit;
        $res = $model->alias('V')->select("
                     V.tier
                     ,count(*) as visit
                     ,sum(V.page_view) as  view 
                     /*,count(*)/ (select count(*) from abs_visit and date='{$this->yesterday}' ) as tier_percent*/
                     /*,avg(V.page_view) as view_avg*/ 
                     /*,(select count(*) from abs_visit   where  tier=V.tier and  page_view=1 and date='{$this->yesterday}' )/count(*) as bounce */
                    ")->where(array('date' => $this->yesterday, 'page_view<100'))->groupby('V.tier')->exec();
        $dt = array();
        while ($res->fetch()) {
            $dt[$res->current()->tier] = array(
                'visit' => $res->current()->visit,
                'view' => $res->current()->view
            );
        }

        /* bounce and view(avg) */
        $view_avg = 0;
        $bounce_rate = 0;
        $res = $model->select("
                     ifnull(avg(page_view),0) as view_avg,
                     (select count(*) from abs_visit   where  page_view=1 and date='{$this->yesterday}' )/count(*) as bounce
                    ")->where(array('date' => $this->yesterday, 'page_view<100'))->exec();
        if ($res->numrows()) {
            $view_avg = $res->current['view_avg'];
            $bounce_rate = $res->current['bounce'];
        }


        $model = new Visit_daily;
        if (!$model->where(array('date' => $this->yesterday))->getcount())
            $model->insert(array(
                'date' => $this->yesterday,
                'tier1_visit' => isset($dt[1]['visit']) ? $dt[1]['visit'] : 0,
                'tier2_visit' => isset($dt[2]['visit']) ? $dt[2]['visit'] : 0,
                'tier3_visit' => isset($dt[3]['visit']) ? $dt[3]['visit'] : 0,
                'tier1_pageview' => isset($dt[1]['view']) ? $dt[1]['view'] : 0,
                'tier2_pageview' => isset($dt[2]['view']) ? $dt[2]['view'] : 0,
                'tier3_pageview' => isset($dt[3]['view']) ? $dt[3]['view'] : 0,
                'pageview_avg' => $view_avg,
                'bounce_rate' => $bounce_rate,
                'game_hits' => $game_hits
            ))->exec();
    }

    function reset()
    {
        /* it should be run at 2nd time . */
        /* Trader Stats Reset */
        $model = new Trader;
        $model->update(array(
            'tier1_in_today' => 0,
            'tier2_in_today' => 0,
            'tier3_in_today' => 0,
            'tier1_out_today' => 0,
            'tier2_out_today' => 0,
            'tier3_out_today' => 0,
            'convert_today' => 0,
        ))->exec();

        /* Trader Geo Reset */
        $model = new Trader_geo();
        $model->update(array('in_today' => 0, 'out_today' => 0))->exec();

        /* Trader Geo Reset */
        $model = new Game();
        $model->update(array('game_today_hits' => 0))->exec();

        /* delete yesterday records from abs_visit  */
        $model = new Visit;
        $res = $model->delete()->where("`date`='{$this->yesterday}'")->exec();
    }

    function __destruct()
    {
        parent::__destruct();
        $this->log_end();
    }

}