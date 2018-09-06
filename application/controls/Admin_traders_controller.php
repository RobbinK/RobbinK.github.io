<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Admin_traders_controller.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class Admin_tradersController extends AdministrationController {

    protected $_model = null;

    function __construct() {
        parent::__construct();
        $this->MapViewFile_groupFolder('vg_admin_traders');
    }

    function request_comments() {
        $this->islogin();
        $model = new Comment();
        $pk = 'id';
        if (validate::_is_ajax_request()) {
            if (isset($_GET['editcm'])) {
                $this->request_comments_reply();
                return;
            }

            if (isset($_GET['save'])) {
                $this->getPOST($_POST);
                $savearray = array(
                    'response' => @$_POST['response'],
                );

                $save_error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_txt'] = '';

                if ($save_error == 0) {
                    $model->update($savearray)->where(array('id' => $_POST['id']))->exec();
                    if ($this->send_mail($_POST['email'], "RE: Traffic trade with  " . Lib::get_domain(HOST_URL), $_POST['response'])) {
                        $json_out['save_code'] = 1;
                        $json_out['save_txt'] = L::alert_message_sent_to . "  {$_POST['email']}";
                    } else {
                        $json_out['save_code'] = 0;
                        $json_out['save_txt'] = L::alert_message_not_sent;
                    }
                }
                echo json_encode($json_out);
                exit;
            }

            $this->view->disable();
            //-- deleting
            if (isset($_GET['del'])) {
                $id = intval(@$_POST['id']);
                $delC = $model->delete()->where(array($pk => $id))->exec();
                if ($delC)
                    echo "{$delC} " . L::alert_records_delete;
                exit;
            }
            //-- multi delete
            if (isset($_GET['mdel'])) {
                $ides = join(',', $_POST['id']);
                $delC = $model->delete()->where($pk . " in ({$ides})")->exec();
                if ($delC)
                    echo "{$delC} " . L::alert_records_delete;
                exit;
            }
            //-- Data Table
            if (isset($_GET['dt'])) {
                pengu_user_load_class('ab_bbcode', $bbcodeI);
                $aColumns = array(
                    'id', 'name', 'email', "DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m-%d %H:%i:%s')", 'comment', 'status', 'response');
                $model->select(implode(',', $aColumns))->where(array('type' => 1));
                pengu_user_load_class('ab_jdatatable', $jdt, $model);
                $jdt->setColumns($aColumns);
                $jdt->preparePagin();
                $jdt->prepareOrdering();
                $jdt->prepareFiltering();
                $jdt->process();
                $myrows = $jdt->getData();

                foreach ($myrows as &$row) {
                    $iconshow = '';
                    foreach ($row as $f => &$v) {
                        if ($aColumns[$f] == 'comment') {
                            $v = str::summarize($v, 150);
                            $v = $bbcodeI->bbcode_decode($v);
                        }


                        if ($aColumns[$f] == 'status') {
                            if ($v == 0)
                                $v = "<span class='text-error'>" . L::forms_unread . "</span>";
                            elseif ($v == 1)
                                $v = "<span class='text-info'>" . L::forms_viewed . "</span>";
                        }

                        if ($aColumns[$f] == 'response' && !empty($v))
                            $v = "<i class='splashy-check'></i>";
                    }
                    $row[] = "<input type='hidden' class='row_id' value='{$row[0]}' />" .
                            "<a href='#' title='" . L::global_remove . "' class='sepV_a'><i class='icon-trash del'></i></a>" .
                            "<a href='#' title='Observe' class='sepV_a'><i class='icon-eye-open observe'></i></a>" . $iconshow;
                }


                $jdt->setData($myrows);
                echo $jdt->renderOutput();
                exit;
            }
        }
    }

    function request_comments_reply() {
        $this->islogin();
        $this->MapViewFileName('request_comments_reply.php');
        $model = new Comment();
        $pk = 'id';

        $model->update(array('status' => 1))->where(array('id' => $_GET['id'], 'status' => 0))->exec();
        $aColumns = array(
            'id',
            'name',
            'email',
            'website',
            "DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m-%d') as date",
            'comment',
            'ip',
            'time',
            'response',
            'country'
        );
        $data = $model->select(implode(',', $aColumns))
                        ->where(array($pk => $_GET['id']))
                        ->exec()->current;

        pengu_user_load_class('ab_bbcode', $instance);
        $data['comment'] = $instance->bbcode_decode($data['comment']);
        $data['comment'] = nl2br($data['comment']);
        $this->set($data);
    }

    function trader_history() {
        $this->islogin();
        $model = new Trader_history();
        $today = date('Y-m-d');

        if (!isset($_GET['tid']) || !$trader = Trader::getData($_GET['tid'])) {
            $this->notfound();
            return;
        }
        $this->view->trader_title = $trader->title;

        /* date */
        $dateobj = new pengu_date(PENGU_DATE_GREGORIAN);
        $format = 'Y-m-d';
        $this->view->date = array(
            'today' => $dateobj->toString($format),
            'yesterday' => $dateobj->setTimeStamp(time())->add('d', -1)->toString($format),
            'month' => $dateobj->setTimeStamp(time())->beginOfMonth()->toString($format),
            'lmonth_f' => $dateobj->setTimeStamp(time())->beginOfLastMonth()->toString($format),
            'lmonth_e' => $dateobj->setTimeStamp(time())->endOfLastMonth()->toString($format),
            'year_f' => $dateobj->setTimeStamp(time())->beginOfYear()->toString($format),
        );

        if (validate::_is_ajax_request()) {
            $this->view->disable();

            /* data table */
            if (isset($_GET['dt'])) {
                $date1 = input::get('datef');
                $date2 = input::get('datee');
                if (empty($date1) || empty($date2)) {
                    $date1 = pengu_date(PENGU_DATE_GREGORIAN)->beginOfMonth()->toString('Y-m-d');
                    $date2 = date('Y-m-d');
                }

                $aColumns = array('date', 'tier1_in', 'tier2_in', 'tier3_in', 'tier1_in+tier2_in+tier3_in', 'tier1_out', 'tier2_out', 'tier3_out', 'tier1_out+tier2_out+tier3_out', '`convert`', 'pageview_avg', 'bounce_rate');

                $sql = "
                select " . join(',', $aColumns) . "   
                from 
                (
                 select
                 date,tier1_in,tier2_in,tier3_in,  tier1_out,tier2_out,tier3_out,  `convert`, pageview_avg , bounce_rate
                 from `abs_trade_history` where   `tid`={$_GET['tid']}
                union
                 select 
                 '{$today}' as date,
                 tier1_in_today,tier2_in_today,tier3_in_today  ,tier1_out_today,tier2_out_today,tier3_out_today  ,convert_today ,  
                 (select ifnull(avg(page_view),0) from abs_visit where date='{$today}' and trader_id=abs_traders.id),  
                 ((select count(*) from abs_visit  where  page_view=1 and date='$today' and trader_id=abs_traders.id)/ (select count(*) from abs_visit  where  date='{$today}' and trader_id=abs_traders.id))
                 from abs_traders
                 where   `id`={$_GET['tid']}
                ) R where  R.date>='{$date1}' and R.date<='{$date2}'";
                $model->query($sql);

                pengu_user_load_class('ab_jdatatable', $jdt, $model);
                $jdt->setColumns($aColumns);
                $jdt->preparePagin();
                $jdt->prepareOrdering();
                $jdt->prepareFiltering();
                $jdt->process();

                $myrows = $jdt->getData();

                foreach ($myrows as &$row) {
                    foreach ($row as $f => &$v) {
                        if (is_numeric($v)) {
                            if (false !== strpos($v, '.'))
                                $v = round($v, 2);
                            else
                                $v = number_format($v);
                        }

                        if ($aColumns[$f] == '`convert`') {
                            $v = $v . "<span class=\"pull-right label  label-success \">" . ($row[4] == 0 ? 0 : round(@($v / $row[4]), 1)) . '%</span>';
                        }

                        if ($aColumns[$f] == 'bounce_rate') {
                            $v = ($v * 100) . '%';
                        }
                    }
                }

                $jdt->setData($myrows);
                echo $jdt->renderOutput();
                exit;
            }
        }
    }

    function trader_geo_report() {
        $this->islogin();
        $model = new Trader_geo();

        if (!isset($_GET['tid']) || !$trader = Trader::getData($_GET['tid'])) {
            $this->notfound();
            return;
        }
        $this->view->trader_title = $trader->title;

        if (validate::_is_ajax_request()) {
            $this->view->disable();

            /* data table */
            if (isset($_GET['dt'])) {

                $SUM = $model->select("ifnull(sum(in_today),0),ifnull(sum(out_today),0),ifnull(sum(in_total),0),ifnull(sum(out_total),0)")->exec()->current();

                $aColumns = array('country_code', 'in_today', 'out_today', 'in_total', 'out_total');

                $model->select(join(',', $aColumns))
                        ->where(array("tid" => $_GET['tid']));
                pengu_user_load_class('ab_jdatatable', $jdt, $model);
                $jdt->setColumns($aColumns);
                $jdt->preparePagin();
                $jdt->prepareOrdering();
                $jdt->prepareFiltering();
                $jdt->process();

                $myrows = $jdt->getData();

                foreach ($myrows as &$row) {
                    if (strlen($row[0]) == 2)
                        $row[0] = '<i   class="flag-' . $row[0] . '" style="border: 0"></i>&nbsp;&nbsp;' . str::summarize(agent::country($row[0], 'country'), 25);
                    else
                        $row[0] = 'Unknown';

                    foreach ($row as $f => &$v) {
                        if (is_numeric($v)) {
                            if (false !== strpos($v, '.'))
                                $v = round($v, 2);
                            else
                                $v = number_format($v);
                        }
                    }
                }

                $jdt->setData($myrows);
                echo $jdt->renderOutput();
                exit;
            }
        }
    }

    function traders() {
        global $modelTU;
        $this->islogin();
        $model = new Trader;
        $modelTU = new Tender_url;
        $pk = 'id';

        if (validate::_is_ajax_request()) {
            $this->view->disable();

            function speedStyle($speed) {
                switch ($speed) {
                    case 1:return '<span><i class="splashy-quanitity_capsule_1"></i></span>';
                        break;
                    case 2:return '<span><i class="splashy-quantity_capsule_2"></i></span>';
                        break;
                    case 3:return'<span><i class="splashy-quantity_capsule_3"></i></span>';
                        break;
                    case 4:return '<span><i class="splashy-quantity_capsule_4"></i></span>';
                        break;
                    case 5:return '<span><i class="splashy-quantity_capsule_5"></i></span>';
                        break;
                }
                return false;
            }

            function runningStyle($st) {
                if (convert::to_bool($st)) {
                    return "<span class='text-success'>" . L::global_running . "</span>";
                } else {
                    return "<span class='text-error'>" . L::global_paused . "</span>";
                }
            }

            /*  change */
            if (isset($_GET['change'])) {
                if (isset($_POST['speed'])) {
                    if ($model->update(array('speed=speed' . ($_POST['speed'] == 'up' ? '+1' : '-1')))->where(array(($_POST['speed'] == 'up' ? 'speed<5' : 'speed>1'), $pk => intval(@$_POST['id'])))->exec())
                        echo speedStyle($model->select('speed')->where(array($pk => intval(@$_POST['id'])))->exec()->current()->speed);
                    else
                        echo -1;
                }
                if (isset($_POST['status'])) {
                    if ($model->update(array('status' => $_POST['status']))->where(array($pk => intval(@$_POST['id'])))->exec())
                        echo json_encode(array('st' => $_POST['status'], 'ss' => runningStyle($_POST['status'])));
                    else
                        echo -1;
                }
                exit;
            }

            /*  getdata */
            if (isset($_GET['edit'])) {
                $found = $model->select()->where(array($pk => intval(@$_POST['id'])))->exec();
                if ($found->numrows() > 0) {
                    $rtu = $modelTU->select()->where(array('tid' => intval(@$_POST['id'])))->exec()->allrows();
                    echo json_encode(array_merge($found->current, array('sites' => $rtu)));
                }
                exit;
            }

            /*  deleting */
            if (isset($_GET['del'])) {
                $id = intval(@$_POST['id']);
                $delC = $model->delete()->where(array($pk => $id))->exec();
                if ($delC)
                    echo "{$delC} " . L::alert_records_delete;
                exit;
            }


            /*  multi delete */
            if (isset($_GET['mdel'])) {
                $ides = join(',', $_POST['id']);
                $delC = $model->delete()->where($pk . " in ({$ides})")->exec();
                if ($delC)
                    echo "{$delC} " . L::alert_records_delete;
                exit;
            }

            /* delete site */
            if (isset($_GET['delsite'])) {
                $id = intval(@$_POST['id']);
                $delC = $modelTU->delete()->where(array('id' => $id))->exec();
                if ($delC)
                    echo json_encode(array('save_code' => 1, 'save_text' => L::alert_site_removed));
                else
                    echo json_encode(array('save_code' => 0, 'save_text' => L::alert_err_in_saving_data));
                exit;
            }


            /*  Saving */
            if (isset($_GET['save'])) {
                $this->getPOST($_POST);
                $savearray = array(
                    'title' => @$_POST['title'],
                    'description' => convert::seoText(@$_POST['description']),
                    'trader_email' => @$_POST['trader_email'],
                    'trade_ratio' => @$_POST['trade_ratio'],
                    'daily_cap' => @$_POST['daily_cap'],
                    'forced_hits' => @$_POST['forced_hits'],
                    'tier1_credits=ifnull(tier1_credits,0)+' . intval($_POST['tier_1_credit']),
                    'tier2_credits=ifnull(tier2_credits,0)+' . intval($_POST['tier_2_credit']),
                    'tier3_credits=ifnull(tier3_credits,0)+' . intval($_POST['tier_3_credit']),
                    'send_to_homepage' => @$_POST['send_to_homepage'],
                    'speed' => @$_POST['speed'],
                    'status' => @$_POST['status'],
                );
                $save_error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_txt'] = '';

                if (empty($_POST['title'])) {
                    $json_out['save_txt'] .= '<li>' . L::alert_fill_trader_title . '</li>';
                    $save_error = 1;
                }
                if ($save_error == 1)
                    $json_out['save_txt'] = '<ul class="list_d">' . $json_out['save_txt'] . '</ul>';

                if ($save_error == 0) {


                    function updUT($data, $tid) {
                        global $modelTU;
                        foreach ($data as $site) {
                            if (is_numeric($site['id'])) {
                                if ($modelTU->update(array(
                                            'site_url' => @lib::get_domain($site['site_url']),
                                            'type' => @$site['type'],
                                            'status' => @convert::to_bool($site['status'])
                                                ), true, true)->where(array('id' => $site['id']))->exec() !== false)
                                    continue;
                            }
                            if (is_numeric($tid) && !empty($site['site_url'])) {
                                $modelTU->insert(array(
                                    'tid' => $tid,
                                    'site_url' => @lib::get_domain($site['site_url']),
                                    'type' => @$site['type'],
                                    'status' => @convert::to_bool($site['status'])
                                ))->exec();
                            }
                        }
                    }

                    if (empty($_POST[$pk])) {
                        if ($model->insert($savearray)->exec()) {
                            if (isset($_POST['sites']))
                                updUT($_POST['sites'], $model->lastinsid());

                            $json_out['save_code'] = 1;
                            $json_out['save_txt'] = L::alert_record_save;
                        } else {
                            $json_out['save_code'] = 0;
                            $json_out['save_txt'] = L::alert_err_in_saving_data;
                        }
                    } else {
                        if (false !== $model->update($savearray)->where(array($pk => $_POST[$pk]))->exec()) {
                            if (isset($_POST['sites']))
                                updUT($_POST['sites'], $_POST['id']);
                            $json_out['save_code'] = 1;
                            $json_out['save_txt'] = L::alert_record_update;
                        } else {
                            $json_out['save_code'] = 0;
                            $json_out['save_txt'] = L::alert_err_in_saving_data;
                        }
                    }
                }
                if ($json_out['save_code'] == 1 && !empty($_POST[$pk])) {
                    $tire_data = $model->select('tier1_credits,tier2_credits,tier3_credits')->where(array($pk => $_POST[$pk]))->exec()->current;
                    $json_out = array_merge($json_out, $tire_data);
                }
                echo json_encode($json_out);
                exit;
            }

            /*  Data Table */
            if (isset($_GET['dt'])) {
                $aColumns = array(
                    $pk,
                    'title',
                    'tier1_out_today+tier2_out_today+tier3_out_today', // out today
                    'tier1_in_today+tier2_in_today+tier3_in_today', // in today
                    '(tier1_in_today+tier2_in_today+tier3_in_today) * 100 / (tier1_out_today+tier2_out_today+tier3_out_today) ',
                    'tier1_out_overall+tier2_out_overall+tier3_out_overall', // out overall
                    'tier1_in_overall+tier2_in_overall+tier3_in_overall', // in overall
                    'convert_overall',
                    'tier1_credits+tier2_credits+tier3_credits', // credit
                    'trade_ratio',
                    'speed',
                    'if(status=1,"active","inactive")'
                );
                $model->select(implode(',', $aColumns));
                pengu_user_load_class('ab_jdatatable', $jdt, $model);
                $jdt->setColumns($aColumns);
                $jdt->preparePagin();
                $jdt->prepareOrdering();
                $jdt->prepareFiltering();
                $jdt->process();
                $myrows = $jdt->getData();

                function in_today_ratio($v, $in, $out) {
                    if ($out == 0 && $in > 0) {
                        $c = 'label-success';
                        $v = 999;
                    } elseif ($v > 90)
                        $c = 'label-success';
                    elseif ($v >= 70 && $v <= 90)
                        $c = 'label-warning';
                    elseif ($v < 70)
                        $c = 'label-important';
                    return "<span class=\"pull-right label {$c}\">" . round($v) . '%</span>';
                }

                foreach ($myrows as &$row) {
                    foreach ($row as $f => &$v) {
                        if ($aColumns[$f] == 'title')
                            $v = str::summarize($v, 60);

                        if (is_numeric($v)) {
                            if (false !== strpos($v, '.'))
                                $v = round($v, 1);
                            else
                                $v = number_format($v);
                        }

                        /* in today */
                        if ($f == 3) {
                            $v.= in_today_ratio($row[4], $row[3], $row[2]);
                        }

                        /* speed */
                        if ($aColumns[$f] == 'speed') {
                            $v = speedStyle($v);
                            $v.="<div class='pull-right speedBtnGroup'><i class='splashy-arrow_state_grey_collapsed up'></i><i class='splashy-arrow_state_grey_expanded down'></i></div>";
                        }

                        /* convert */
                        if ($aColumns[$f] == 'convert_overall') {
                            $v = $v . "<span class=\"pull-right label  label-warning \">" . ($row[6] == 0 ? 0 : round(@($v / $row[6]), 1)) . '%</span>';
                        }

                        /* active */
                        if ($f == 11) {
                            if (convert::to_bool($v))
                                $st = 'pause';
                            else
                                $st = 'play';
                            $v = runningStyle($v);
                            $v.= "<div  class='pull-right RunBtnGroup'><i class='icon-{$st} {$st}'></i></a>";
                        }
                    }
                    $row[] = "<input type='hidden' class='row_id' value='{$row[0]}' />" .
                            "<a href='#' title='" . L::global_edit . "' class='sepV_a'><i class='icon-pencil edit'></i></a>" .
                            "<a href='" . url::router('admin-poolitradergeo')->fulluri(array('tid' => $row[0])) . "' title='Trader Country Report' class='sepV_a'><i class='icon-globe country'></i></a>" .
                            "<a href='" . url::router('admin-poolihistory')->fulluri(array('tid' => $row[0])) . "' title='Trader daily history' class='sepV_a'><i class='icon-calendar history'></i></a>" .
                            "<a href='#' title='" . L::global_remove . "' class='sepV_a'><i class='icon-trash del'></i></a>";
                }

                $jdt->setData($myrows);
                echo $jdt->renderOutput();
                exit;
            }
        }
    }

    /* some other functions */

    function interface_trader_detector() {
        $this->view->disable();
        if (isset($_GET['getcount'])) {
            $game = new Game();
            echo $game->where(array('game_is_active' => 1))->getcount();
        }
        else
            echo 'detected';
    }

    function interface_generate_plugs() {
        $this->view->disable();
        $step=3000;
        $url_sep = 'url';
        $end = 'end';
        $s = "{*";
        $e = "*}";
        header('Content-Type: text/html; charset=utf-8');
        $model = new Game();
        $model->getGames(null, null, 'game_adddate desc');
        if (isset($_GET['step']))
            $step=intval($_GET['step']);
        if (isset($_GET['start']) && is_numeric($_GET['start']))
            $model->limit($_GET['start'],$step);
        $model->exec_and_get_result();

        $recieve_page = setting::get_data('trade_recive_page', 'val');
        $domain = HOST_URL;

        while ($game = $model->the_game()) :

            /* make url */
            if ($recieve_page == 'play') {
                if (!empty($game->category_seotitle))
                    $url = url::router('playgame', array(
                                'category_seo' => @$game->category_seotitle,
                                'category_id' => $game->category_id,
                                'game_id' => $game->id,
                                'game_seo' => $game->seotitle
                            ))->fulluri();
                else
                    $url = url::router('playgame2', array(
                                'game_id' => $game->id,
                                'game_seo' => $game->seotitle
                            ))->fulluri();
            }
            else {
                if (!empty($game->category_seotitle))
                    $url = url::router('pregame', array(
                                'category_seo' => @$game->category_seotitle,
                                'category_id' => $game->category_id,
                                'game_id' => $game->id,
                                'game_seo' => $game->seotitle
                        ))->fulluri();
                else
                    $url = url::router('pregame2', array(
                                'game_id' => $game->id,
                                'game_seo' => $game->seotitle
                            ))->fulluri();
            }

            echo "{$game->name}{$s}{$url_sep}{$e}{$domain}{$url}{$s}{$end}{$e}";
        endwhile;
    }

    function plug_checksys($domain) {
        /* ABS */
        $url =  $domain. '/trade/trtradedetect.html?';
        $found = @file_get_contents($url);
        if ($found == 'detected')
            return array('ArcadeBoosterTradeScript', intval(file_get_contents($url . '&getcount')));

        /* ATS */
        $url =  $domain . '/ats-plug-helper.php?';
        $found = @file_get_contents($url);
        if ($found && preg_match('/found/i', $found))
            return array('ArcadeTradeScript', intval(file_get_contents($url . '&getnum=1')));

        return false;
    }

    /* some other functions */

    function plug_grab($system, $domain, $numgames, $tid, $tDomainId) {
        $games = array();
        $modelG = new Game;
        $resG = $modelG->select('gid,game_name,seo_title')->exec();
        if (!$resG->numrows())
            return -1;
        while ($resG->fetch())
            $games[$resG->current()->seo_title] = $resG->current;

        /* ATS */
        $foundlist = array();
        if ($system == 'ATS') {
            $step = 3000;
            for ($i = 0; $i < ceil($numgames / $step); $i++) {
                $start = ($i * $step);
                $url =  $domain . "/ats-plug-helper.php?s={$start}";
                $data = @file_get_contents($url);
                if (!empty($data) && !preg_match('/notFound/i', $data)) {
                    $explode = explode('{%sep2%}', $data);
                    foreach ($explode as $game) {
                        @list($name, $url) = explode('{%sep%}', $game);
                        if (!empty($name) && !empty($url) && validate::_is_URL($url)) {
                            if (isset($games[convert::seoText($name)]))
                                $games[convert::seoText($name)] ['url'] = $url; //added
                        }
                    }
                }
            }
        }
        if ($system == 'ABS') {
            $step = 3000;
            for ($i = 0; $i < ceil($numgames / $step); $i++) {
                $start = ($i * $step);
                $url =  $domain . "/trade/generateplugs.html?start={$start}&step={$step}";
                $data = @file_get_contents($url);
                if (!empty($data) && !preg_match('/notFound/i', $data)) {
                    $explode = explode('{*end*}', $data);
                    foreach ($explode as $game) {
                        @list($name, $url) = explode('{*url*}', $game);
                        if (!empty($name) && !empty($url) && validate::_is_URL($url)) {
                            if (isset($games[convert::seoText($name)]))
                                $games[convert::seoText($name)] ['url'] = $url; //added
                        }
                    }
                }
            }
        }

        $success = 0;
        $modelP = new Trade_plug;
        foreach ($games as $k => $g) {
            if (isset($g['url'])) {
                $res = $modelP->insert(array(
                            'gid' => $g['gid'],
                            'tid' => $tid,
                            'tdid' => $tDomainId,
                            'url' => $g['url'],
                            'status' => 1,
                        ))->exec();

                if ($res)
                    $success++;
            }
        }
        return $success;
    }

    function plugs() {
        $this->islogin();
        $model = new Trade_plug;
        $pk = 'id';

        if (!isset($_GET['tid']) || !$trader = Trader::getData($_GET['tid'])) {
            $this->notfound();
            return;
        }

        $this->view->trader_title = $trader->title;


        if (!isset($_GET['u']) || !is_numeric($_GET['u'])) {
            $this->notfound();
            return;
        }

        $modelU = new Tender_url;
        $domain = $modelU->select('id,site_url')->where(array('id' => intval($_GET['u']), 'tid' => intval($_GET['tid'])))->exec();
        if (!$domain->numrows()) {
            $this->notfound();
            return;
        }
        $domain = $domain->current();
        $site_addr= rtrim(get_redirected_url( 'http://'.$domain->site_url),'/');
        $this->view->trader_domain = $domain->site_url;

        if (validate::_is_ajax_request()) {
            $this->view->disable();

            /* grab plugs */
            if (isset($_GET['grab'])) {
                $ret = $this->plug_grab($_POST['sys'], $site_addr, $_POST['numgames'], $_GET['tid'], $_GET['u']);
                switch ($ret) {
                    case -1 : $out = array('grabbed' => 0, 'msg' => L::alert_no_games_in_db);
                        break;
                    case 0 : $out = array('grabbed' => 0, 'msg' => L::alert_no_plugs);
                        break;
                    default: $out = array('grabbed' => 1, 'msg' => $ret . ' ' . L::alert_plugs_grabbed);
                        break;
                }
                echo json_encode($out);
                exit;
            }

            /*  getdata */
            if (isset($_GET['edit'])) {
                $found = $model->select()->where(array('id' => intval(@$_POST['id'])))->exec();
                if ($found->numrows() > 0)
                    echo json_encode($found->current);
                exit;
            }


            /*  deleting */
            if (isset($_GET['del'])) {
                $id
                        = intval(@$_POST['id']);
                $delC = $model->delete()->where(array($pk => $id))->exec();
                if ($delC)
                    echo "{$delC} " . L::alert_records_delete;
                exit;
            }
            /*  multi delete */
            if (isset($_GET['mdel'])) {
                $ides = join(',', $_POST['id']);
                $delC = $model->delete()->where(array($pk . " in ({$ides})"))->exec();
                if ($delC)
                    echo "{$delC} " . L::alert_records_delete;
                exit;
            }


            /* Saving */
            if (isset($_GET[
                            'save'])) {
                $save_error = 0;
                $json_out['save_code'] = 0;

                $json_out['save_txt'] = '';
                $this->getPOST($_POST);

                $savearray = array(
                    'gid' => @$_POST['gid'],
                    'tid' => $trader->id,
                    'tdid' => $domain->id,
                    'url' => @$_POST['url'],
                    'status' => @convert::to_bool($_POST['status']),
                );

                if (!validate::_is_URL($savearray['url'])) {
                    $json_out['save_txt'
                            ] .= '<li>' . L::alert_invalid_url_format . '</li>';
                    $save_error = 1;
                }

                if ($save_error == 1)
                    $json_out['save_txt'] = '<ul class="list_d">' . $json_out['save_txt'] . '</ul>';


                if ($save_error == 0) {
                    if (empty($_POST['id'])) {
                        if ($model->insert($savearray)->exec()) {
                            $json_out['save_code'] = 1;
                            $json_out['save_txt'] = L::alert_record_save;
                        } else {
                            $json_out['save_code'] = 0;
                            $json_out['save_txt'] = L::alert_err_in_saving_data;
                        }
                    } else {
                        if (false !== $model->update($savearray)->where(array('id' => $_POST['id']))->exec()) {
                            $json_out['save_code'] = 1;
                            $json_out['save_txt'] = L::alert_record_update;
                        } else {
                            $json_out['save_code'] = 0;
                            $json_out['save_txt'] = L::alert_err_in_saving_data;
                        }
                    }
                }

                echo json_encode($json_out);
                exit;
            }

            /* Data Table */
            if (isset($_GET['dt'])) {
                $aColumns = array('P.id', 'G.game_img', 'G.game_name', 'url', 'if(P.status=0,"Disabled","Enabled")');

                $model->alias('P')->select(implode(',', $aColumns))->innerjoin('abs_games', 'G')->on('P.gid=G.gid')->where(array('P.tid' => $trader->id, 'tdid' => $domain->id));
                pengu_user_load_class('ab_jdatatable', $jdt, $model);
                $jdt->setColumns($aColumns);
                $jdt->preparePagin();
                $jdt->prepareOrdering();
                $jdt->prepareFiltering();
                $jdt->process();
                $myrows = $jdt->getData();

                foreach ($myrows as &$row) {
                    foreach ($row as $f => &$v) {
                        if ($aColumns[$f] == 'G.game_img') {
                            /*  game image */
                            $img = '';
                            if (!empty($row[1]) && file_exists(ab_game_images_dir . '/' . $row[1]))
                                $img = $row[1];
                            $row[1] = "<div class='thumbnail'><img  src=\"" . ab_game_create_img($img, 50, null) . "\" data-fullimage=\"" .
                                    ab_game_get_image_url($img) . "\"  rel=\"clbox\" ></div>";
                        }
                        if (validate::_is_URL($v))
                            $v = "<a href='" . $v . "' target='_blank' class='external_link'>" . str::summarize($v, 70, true, '/') . "</a>";

                        if ($aColumns[$f] == 'G.game_name') {
                            $v = $v;
                        }
                        if ($f == 4)
                            $v = ( convert::to_bool($v) ) ? "<span class='text-success'>" . L::global_enable . "</span>" : "<span class='text-error'>" . L::global_disable . "</span>";
                    }
                    $row[] = "<input type='hidden' class='row_id' value='{$row [0]}' />" .
                            "<a href='#' title='" . L::global_edit . "' class='sepV_a'><i class='icon-pencil edit'></i></a>" .
                            "<a href='#' title='" . L::global_remove . "' class='sepV_a'><i class='icon-trash del'></i></a>";
                }

                $jdt->setData($myrows);
                echo $jdt->renderOutput();
                exit;
            }
        }

        $foundsys = $this->plug_checksys($site_addr);
        if (!empty($foundsys)) {
            list($system, $gamesfound) = $foundsys;
            if ($system == 'ArcadeTradeScript') {
                $this->view->sysdetected = true;
                $this->view->sysname = 'ATS';
                $this->view->systitle = 'ArcadeTradeScript';
                $this->view->sysnumgames = $gamesfound;
            }
            if ($system == 'ArcadeBoosterTradeScript') {
                $this->view->sysdetected = true;
                $this->view->sysname = 'ABS';
                $this->view->systitle = 'ArcadeBoosterTradeScript';
                $this->view->sysnumgames = $gamesfound;
            }
        }
    }

}