<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: AbsStat.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class AbsStat extends Model {

    protected $_table = 'abs_ab_stats';
    private static $instance;

    private static function createInstance() {
        if (!isset(self::$instance)) {
            $classname = get_class();
            self::$instance = new $classname;
        }
    }

    function getreport($from, $to, $site) {
        $this->where(array("date>='{$from}'", "date<='{$to}'", 'site' => $site));
        $this->sync($to, $site);
    }

    function sync($to, $site) {
        if (!convert::to_bool(Setting::get_data('arcadebooster_get_earning_stats', 'val')))
            return false;
        $url = "http://www.arcadebooster.com/members/pub/";
        $username = setting::get_data('arcadebooster_publisher_username', 'val');
        $password = md5(setting::get_data('arcadebooster_publisher_password', 'val'));
        $dformat = 'Y-m-d';
        $today = pengu_date(PENGU_DATE_GREGORIAN)->toString($dformat);
        $yesterday = pengu_date(PENGU_DATE_GREGORIAN)->add('d', -1)->toString($dformat);

        self::createInstance();
        $max_date = '';
        if ($resmax = self::$instance->max('date')->where(array('site' => $site))->exec())
            $max_date = $resmax->current()->max;

        if ($to > $today)
            $to = $today;

        $date1 = '';
        $date2 = '';
        if (($max_date < $to) && ($max_date < $yesterday)) {
            if (validate::_is_date($max_date))
                $date1 = pengu_date(PENGU_DATE_GREGORIAN)
                        ->setTimeStamp(strtotime($max_date))
                        ->add('d', 1)
                        ->toString($dformat);
            else
                $date1 = '0000-00-00';
            $date2 = $to;
        } elseif ($to == $today) {
            $date1 = $yesterday;
            $date2 = $today;
        }



        if (!empty($date1) && !empty($date2)) {
            set_time_limit(5 * 60);
            $data = @file_get_contents("{$url}?rqfrom=abs&from={$date1}&to={$date2}&site={$site}&u={$username}&p={$password}");
            if (!empty($data)) {
                $data = json_decode($data);
                if (!is_array($data) || empty($data))
                    return false;
                self::createInstance();
                //$end = end($data);
                foreach ($data as $row) {
                    $save = array(
                        'date' => $row->date,
                        'site' => $site,
                        'imps' => $row->imps,
                        'clicks' => $row->clicks,
                        'earning' => $row->earning,
                    );
                    if ($row->date == $today || $row->date == $yesterday) {
                        if (self::$instance->where(array('date' => $row->date, 'site' => $site))->getcount() == 1) {
                            self::$instance->update($save)->where(array('date' => $row->date, 'site' => $site))->exec();
                            continue;
                        }
                    }
                    self::$instance->insert($save)->exec();
                }
                return true;
            }
        }
    }

}
