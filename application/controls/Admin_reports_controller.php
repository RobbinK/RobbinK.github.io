<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Admin_reports_controller.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class Admin_reportsController extends AdministrationController {

    protected $_model = null;
    private $tmp_dir, $tmp_url, $upload_dir, $upload_url;

    function __construct() {
        parent::__construct();
        $this->MapViewFile_groupFolder('vg_admin_reports');
    }

    function ab_stats() {
        $this->islogin();
        $model = new AbsStat();

        $site = lib::get_domain(setting::get_data('arcadebooster_publisher_site', 'val'));
        $this->view->domain = $site;

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
                $aColumns = array('date', 'imps', 'clicks', 'earning');
                $model->select(implode(',', $aColumns));

                if (!empty($_GET['datef']) && !empty($_GET['datee']))
                    $model->getreport(input::get('datef'), input::get('datee'), $site);
                else
                    $model->getreport(pengu_date(PENGU_DATE_GREGORIAN)->beginOfMonth()->toString('Y-m-d'), date('Y-m-d'), $site);

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

                            if (strpos($aColumns[$f], 'earning'))
                                $v =  toPrice($v);
                        }
                    }
                }

                $jdt->setData($myrows);
                echo $jdt->renderOutput();
                exit;
            }
        }
    }

    function traffic_report() {
        $this->islogin();
        $model = new Visit_daily();
        $today = date('Y-m-d');

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

                $aColumns = array('date', 'tier1_visit', 'tier2_visit', 'tier3_visit', '(tier1_visit+tier2_visit+tier3_visit) total_visit', '(tier1_pageview+tier2_pageview+tier3_pageview) total_pageview', 'pageview_avg', 'bounce_rate', 'game_hits');
                $model->select(implode(',', $aColumns))->where(array("date>='$date1'", "date<='$date2'"));
                $sql = "
select " . implode(',', $aColumns) . "
from( 
   select 
       date,
       tier1_visit,
       tier2_visit,
       tier3_visit,
       tier1_pageview,
       tier2_pageview,
       tier3_pageview,
       pageview_avg,
       bounce_rate,
       game_hits
   from abs_visit_daily
 union 
    /* Today Data */
  select
        '{$today}' as date,
       (select count(*) from abs_visit where tier=1 and page_view<100 and date='{$today}'),
       (select count(*) from abs_visit where tier=2 and page_view<100 and date='{$today}'),
       (select count(*) from abs_visit where tier=3 and page_view<100 and date='{$today}'),              
       (select ifnull(sum(page_view),0) from abs_visit where tier=1 and page_view<100 and date='{$today}'),
       (select ifnull(sum(page_view),0) from abs_visit where tier=2 and page_view<100 and date='{$today}'),
       (select ifnull(sum(page_view),0) from abs_visit where tier=3 and page_view<100 and date='{$today}'),       
       (select ifnull(avg(page_view),0) from abs_visit where page_view<100 and date='{$today}'),  
       ((select count(*) from abs_visit  where  page_view=1 and date='{$today}')/ (select count(*) from abs_visit where date='{$today}')),
       (select sum(game_today_hits) from abs_games)
 ) R
   where   R.date>='{$date1}' and R.date<='{$date2}'";
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
                        if ($aColumns[$f] == 'tier1_visit') {
                            $v = number_format($v) . "<span class=\"pull-right label label-success \">" . round(@($v / $row[4]) * 100, 1) . '%</span>';
                        }
                        if ($aColumns[$f] == 'tier2_visit') {
                            $v = number_format($v) . "<span class=\"pull-right label label-warning \">" . round(@($v / $row[4]) * 100, 1) . '%</span>';
                        }
                        if ($aColumns[$f] == 'tier3_visit') {
                            $v = number_format($v) . "<span class=\"pull-right label label-important \">" . round(@($v / $row[4]) * 100, 1) . '%</span>';
                        }
                        if (is_numeric($v)) {
                            if (false !== strpos($v, '.'))
                                $v = round($v, 2);
                            else
                                $v = number_format($v);
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

    function geo_report() {
        $this->islogin();
        $model = new Visit_countries_daily();
        $today = date('Y-m-d');

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

                $aColumns = array('country_code', 'sum(visit) as visit', 'sum(pageview) as pageview', 'sum(pageview)/sum(visit)', 'sum(bounce_rate*visit) / sum(visit)');


                $sql = "
                select " . implode(',', $aColumns) . "
                from(  
                     select  
                        date , country_code ,   visit   ,pageview ,   pageview_avg ,  bounce_rate  
                     from
                       abs_visit_countries_daily 
                    union  
                    /* Today Data */
                     select 
                      '{$today}' as date,
                        V.country_code
                        ,count(*) as visit
                        ,ifnull(sum(V.page_view),0) as  view  
                        ,ifnull(avg(V.page_view),0) as view_avg  
                        ,(select count(*) from abs_visit   where  country_code=V.country_code and  page_view=1 and `date`='{$today}')/count(*)
                     from abs_visit V where V.`date`='{$today}' and V.page_view<100  group by   V.country_code
                   ) R
               where  R.date>='{$date1}' and R.date<='{$date2}'  group by R.country_code  ";

                $model->query($sql);
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
                        if ($f == 4)
                            $v = ($v * 100) . '%';
                    }
                }

                $jdt->setData($myrows);
                echo $jdt->renderOutput();
                exit;
            }
        }
    }

}