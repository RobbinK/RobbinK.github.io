<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: pengu.date.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


include("date.class.php");
include("datediff.lib.php");
include("parsidate.v1.2/parsidate.class.php");

define('PENGU_DATE_JALALI', 'jalalidate');
define('PENGU_DATE_GREGORIAN', 'gregoriandate');

class pengu_date {

    private $timeStamp = 0;
    private $calenderType;
    private $instance_j;
    private $instance_g;

    function __construct($CalenderType = PENGU_DATE_GREGORIAN) {
        switch ($CalenderType) {
            case PENGU_DATE_GREGORIAN:
                $this->calenderType = PENGU_DATE_GREGORIAN;
                $this->instance_g = new DateClass;
                $this->timeStamp = $this->instance_g->TimeStamp();
                break;
            case PENGU_DATE_JALALI:
                $this->calenderType = PENGU_DATE_JALALI;
                $this->instance_j = new ParsiDate("Y/m/d");
                $this->timeStamp = $this->instance_j->getTimeStamp();
                break;
            default :
                $this->calenderType = PENGU_DATE_GREGORIAN;
                $this->instance_g = new DateClass;
                $this->timeStamp = $this->instance_g->TimeStamp();
        }
        return $this;
    }

    private function padleft($str) {
        return str_pad($str, 2, '0', STR_PAD_LEFT);
    }

    private function isGregorianIns() {
        return ($this->calenderType === PENGU_DATE_GREGORIAN);
    }

    private function isJalaliIns() {
        return ($this->calenderType === PENGU_DATE_JALALI);
    }

    public function getTimeStamp() {
        return $this->timeStamp;
    }

    public function setTimeStamp($timeStamp) {
        $this->timeStamp = $timeStamp;
        return $this;
    }

    public function day($timeStamp = "") {
        if ($timeStamp)
            $this->setTimeStamp($timeStamp); // set internall time stamp

        if ($this->isJalaliIns()) {
            $this->instance_j->setTimeStamp($this->timeStamp);
            return $this->padleft($this->instance_j->Day());
        } else {
            $this->instance_g->SetDate($this->timeStamp);
            return $this->padleft($this->instance_g->Day());
        }
    }

    public function year() {
        if ($this->isJalaliIns()) {
            $this->instance_j->setTimeStamp($this->timeStamp);
            return $this->instance_j->Year();
        } else {
            $this->instance_g->SetDate($this->timeStamp);
            return $this->instance_g->Year();
        }
    }

    public function month() {
        if ($this->isJalaliIns()) {
            $this->instance_j->setTimestamp($this->timeStamp);
            return $this->padleft($this->instance_j->Month());
        } else {
            $this->instance_g->SetDate($this->timeStamp);
            return $this->padleft($this->instance_g->Month());
        }
    }

    /*
     * ***************
     * set
     * **************
     */

    public function setDateG($Year = null, $Month = null, $Day = null) {
        $this->instance_g->TimeStamp($this->timeStamp);

        $Year = $Year === NULL ? $this->instance_g->year() : $Year;
        $Month = $Month === NULL ? $this->instance_g->month() : $Month;
        $Day = $Day === NULL ? $this->instance_g->day() : $Day;
        $Hour = date('H', $this->instance_g->TimeStamp());
        $Min = date('i', $this->instance_g->TimeStamp());
        $Sec = date('s', $this->instance_g->TimeStamp());

        $this->instance_g->TimeStamp(mktime($Hour, $Min, $Sec, $Month, $Day, $Year));
        $this->setTimeStamp($this->instance_g->TimeStamp());
        return $this;
    }

    public function setDateJ($JYear = null, $JMonth = null, $JDay = null) {
        $this->instance_j->setTimeStamp($this->timeStamp);
        $this->instance_j->setDate($JYear, $JMonth, $JDay, PARSIDATE_JALALI_PARAMS);
        $this->setTimeStamp($this->instance_j->getTimeStamp());
        return $this;
    }

    /*
     * ***************
     * Add
     * **************
     */

    public function add($datePart, $adjustValue) {
        if ($this->isGregorianIns()) {
            $this->instance_g->TimeStamp($this->timeStamp);
            $TS = $this->instance_g->Add($datePart, $adjustValue)->TimeStamp();
        } else {
            $this->instance_j->setTimeStamp($this->timeStamp);
            $TS = $this->instance_j->add($datePart, $adjustValue)->getTimeStamp();
        }
        $this->setTimeStamp($TS);
        return $this;
    }

    /*
     * ***************
     * To string
     * **************
     */

    public function toString($format) {
        if ($this->isJalaliIns()) { //1371852000 
            $this->instance_j->setTimeStamp($this->timeStamp);
            return $this->instance_j->showDate($format);
        } else {
            $this->instance_g->TimeStamp($this->timeStamp);
            return $this->instance_g->ToString($format);
        }
    }

    /*
     * ***************
     * Begin Of Week
     * **************
     */

    public function beginOfWeek() {
        if ($this->isJalaliIns()) {
            $TS = $this->instance_j->beginOfWeek($this->timeStamp)->getTimeStamp();
        } else {
            $this->instance_g->TimeStamp($this->timeStamp);
            $TS = $this->instance_g->BOW()->TimeStamp();
        }
        $obj = new pengu_date($this->calenderType);
        $obj->setTimeStamp($TS);
        return $obj;
    }

    /*
     * ***************
     * End Of Week
     * **************
     */

    public function endOfWeek() {
        if ($this->isJalaliIns()) {
            $TS = $this->instance_j->endOfWeek($this->timeStamp)->getTimeStamp();
        } else {
            $this->instance_g->TimeStamp($this->timeStamp);
            $TS = $this->instance_g->EOW()->TimeStamp();
        }
        $obj = new pengu_date($this->calenderType);
        $obj->setTimeStamp($TS);
        return $obj;
    }

    /*
     * ***************
     * Begin Of Month
     * **************
     */

    public function beginOfMonth() {
        if ($this->isJalaliIns()) {
            $TS = $this->instance_j->beginOfMonth($this->timeStamp)->getTimeStamp();
        } else {
            $this->instance_g->TimeStamp($this->timeStamp);
            $TS = $this->instance_g->BOM()->TimeStamp();
        }
        $obj = new pengu_date($this->calenderType);
        $obj->setTimeStamp($TS);
        return $obj;
    }

    /*
     * ***************
     * end Of Month
     * **************
     */

    public function endOfMonth() {
        if ($this->isJalaliIns()) {
            $TS = $this->instance_j->endOfMonth($this->timeStamp)->getTimeStamp();
        } else {
            $this->instance_g->TimeStamp($this->timeStamp);
            $TS = $this->instance_g->EOM()->TimeStamp();
        }
        $obj = new pengu_date($this->calenderType);
        $obj->setTimeStamp($TS);
        return $obj;
    }

    /*
     * ***************
     * Begin Of Last Month
     * **************
     */

    public function beginOfLastMonth() {
        $obj = new pengu_date($this->calenderType);
        $obj->setTimeStamp($this->getTimeStamp());
        $obj->add('m', -1);
        if ($obj->isJalaliIns())
            $obj->setDateJ(null, null, 1);
        else
            $obj->setDateG(null, null, 1);
        return $obj;
    }

    /*
     * ***************
     * end Of Last Month
     * **************
     */

    public function endOfLastMonth() {
        $obj = new pengu_date($this->calenderType);
        $obj->setTimeStamp($this->getTimeStamp());
        if ($obj->isJalaliIns())
            $obj->setDateJ(null, null, 1);
        else
            $obj->setDateG(null, null, 1);
        $obj->add('d', -1);
        return $obj;
    }

    /*
     * ***************
     * Begin Of Year
     * **************
     */

    public function beginOfYear() {
        if ($this->isJalaliIns()) {
            $TS = $this->instance_j->beginOfYear($this->timeStamp)->getTimeStamp();
        } else {
            $this->instance_g->TimeStamp($this->timeStamp);
            $TS = $this->instance_g->BOY()->TimeStamp();
        }
        $obj = new pengu_date($this->calenderType);
        $obj->setTimeStamp($TS);
        return $obj;
    }

    /*
     * ***************
     * End Of Year
     * **************
     */

    public function endOfYear() {

        if ($this->isJalaliIns()) {
            $TS = $this->instance_j->endOfYear($this->timeStamp)->getTimeStamp();
        } else {
            $this->instance_g->TimeStamp($this->timeStamp);
            $TS = $this->instance_g->EOY()->TimeStamp();
        }
        $obj = new pengu_date($this->calenderType);
        $obj->setTimeStamp($TS);
        return $obj;
    }

    public static function ago($timestamp, $rtl = false, $local = null) {
        if (!$local)
            $local = array(
                'style' => array(
                    'rtl' => 'style="direction:rtl;float:left"',
                    'ltr' => 'style="direction:ltr"')
                ,
                'times' => array(
                    'single' => array('second', 'minute', 'hour', 'day', 'week', 'month', 'year', 'decade'),
                    'plural' => array('seconds', 'minutes', 'hours', 'days', 'weeks', 'months', 'years', 'decades'),
                ),
                'ago' => 'ago'
            );

        if (intval($timestamp) > 0) {
            $cur_tm = time();
            $dif = $cur_tm - $timestamp;
            $lngh = array(1, 60, 3600, 86400, 604800, 2630880, 31570560, 315705600);
            for ($v = sizeof($lngh) - 1; ($v >= 0) && (($no = $dif / $lngh[$v]) <= 1); $v--)
                ;
            if ($v < 0)
                $v = 0;
            $_tm = $cur_tm - ($dif % $lngh[$v]);
            $no = floor($no);
            if ($no <> 1)
                $x = sprintf("%d %s ", $no, $local['times']['plural'][$v]);
            else
                $x = sprintf("%d %s ", $no, $local['times']['single'][$v]);

            if ($rtl)
                return "<span><span {$local['style']['rtl']}>" . $x . ' ' . $local['ago'] . "</span></span>";
            else
                return $x . ' ' . $local['ago'];
        }
        else {
            return '-';
        }
    }

    public function diff($otherTime) {
        if (!is_numeric($otherTime)) {
            if ($this->calenderType == PENGU_DATE_JALALI) {
                if (strlen($otherTime) != 10)
                    return false;
                $j_year = substr($otherTime, 0, 4);
                $j_month = substr($otherTime, 5, 2);
                $j_day = substr($otherTime, 8, 2);
                list($y, $m, $d) = ParsiDate::to_gregorian($j_year, $j_month, $j_day);
                $time1 = mktime(0, 0, 0, date('m', $this->timeStamp), date('d', $this->timeStamp), date('Y', $this->timeStamp));
                $time2 = mktime(0, 0, 0, $m, $d, $y);
            }
            else {
                $time1 = mktime(0, 0, 0, date('m', $this->timeStamp), date('d', $this->timeStamp), date('Y', $this->timeStamp));
                $time2 = strtotime($otherTime);
                $time2 = mktime(0, 0, 0, date('m', $time2), date('d', $time2), date('Y', $time2));
            }
        }
        return _date_diff($time1, $time2);
    }

}

function pengu_date($CalenderType = PENGU_DATE_GREGORIAN) {
    return new pengu_date($CalenderType);
}