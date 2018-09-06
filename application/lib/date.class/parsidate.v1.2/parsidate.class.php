<?php

/*
 *  Persian Jalali Class (ParsiDate)
 *  disc:  کلاس کار با تاریخ جلالی
 * ------------------------------------------------
 * @package    ParsiDate
 * @author     Hamed Pakdaman <iraitc@gmail.com> <website: www.rafa-co.ir>
 * @developer  Saeed Moghadam <phpro.ir@gmail.com> <website: www.phpro.ir>
 * @copyright  2013 Hamed Pakdaman
 * @license    http://opensource.org/licenses/mit-license.php The MIT License
 * @link       www.parsidate.ir 
 * @see        ParsiDate
 * @version    1.2 
 * --------------------------------------------------
 * disc:  در سایت اصلی موجود است ParsiDate تمامی اطلاعات فنی کار با کلاس
 * WebSite:  www.parsidate.ir   ,  www.parsidate.rafa-co.ir  
 *
 * Notify: ها به آدرس زیر رجوع فرمایید Timezone برای مشاهده لیست 
 * http://www.php.net/manual/en/timezones.php
 * 
 */

define('PARSIDATE_JALALI_PARAMS', true);
define('PARSIDATE_GREGORIAN_PARAMS', false);

class ParsiDate {

    //----------------------------
    // private member variables
    //----------------------------
    var $_TimeStamp;
    private $_TimeZone;
    private $_datePart;
    private $_dateArray;
    private $_format;
    private $_ParsiDigit = false;

    /*
     * ========================================================
     *  method __constructor
     *
     *  @param string $format     Y/m/d فرمت پیشفرض نمایش تاریخ در خروجی . برای مثال 
     *  @param string $dateTime   ایجاد شیء برای تاریخی مشخص  
     *                           (ex. "now" : زمان حال را برای شیء ایجاد شده مشخص میکند )
     * 
     *  @param string $TimeZone   جهت تنظیم مشکل اختلاف ساعت در سرور ها استفاده میشود
     * ===========================================================
     */

    function __construct($format = 'Y/m/d H:i:s', $dateTime = "now", $TimeZone = 'Asia/Tehran') {
        $this->_format = $format;
        $this->setTimeZone($TimeZone);
        if (!$dateTime || $dateTime == 'now') {
            if (function_exists('date_default_timezone_set ') && date_default_timezone_set($TimeZone))
                $dateTime = time();
            elseif (@ini_set('date.timezone', $TimeZone))
                $dateTime = time();
            else
                $dateTime = $this->getLocalTime($this->_TimeZone, time());
        }
        $this->setDateTime($dateTime);
    }

    /*
     * ========================================================
     *  private method setTimeZone() :   
     *  Disc: جهت تنظیم مشکل اختلاف ساعت در سرور ها استفاده میشود
     * 
     *  @param string/integer  $TimeZone  
     *  disc:                   مقدار عددی جهت تنظیم اختلاف ساعت . مثال برای  تنظیم ساعت به وقت ایران 3.5
     *  disc:                   Asia/Tehran یا Asia/Dubai  همچنین میتوان از نام کشور نیز استفاده کرد. مثلا 
     * ===========================================================
     */

    public function setTimeZone($TimeZone = 'Asia/Tehran') {
        $this->_TimeZone = $TimeZone;
        return $this;
    }

    /*
     * ========================================================
     *  Public method setDate() :  
     * 
     *  Disc:                        برای تغییر هر یک از مقادیر سال ، ماه ، روز ، در شیء استفاده میشود
     *  Disc:                        درصورتی که قصد تغییر هریک را دارید مقداری عددی صحیح در پارامتر آن وارد نمایید
     *  Disc:                        استفاده نمایید null در غیر اینصورت  در قسمت پارامتر از عبارت
     * 
     *  @param  int(year)  $year   مقدار سال شیء را تغییر میدهد            
     *  @param  int(month) $month  مقدار ماه شیء را تغییر میدهد
     *  @param  int(day)   $day    مقدار روز شیء را تغییر میدهد         
     * 
     *  @param  bool       مشخص میکند که پارامتر های ورودی تابع از چه نوع است.
     *             $Parameters_Type==PARSIDATE_JALALI_PARAMS    به این معنی است که پارامتر های ورودی متد از نوع جلالی میباشد      
     *             $Parameters_Type==PARSIDATE_GREGORIAN_PARAMS به این معنی است که پارامتر های ورودی متد از نوع میلادی میباشد      
     * ===========================================================
     */

    function setDate($year = null, $month = null, $day = null, $Parameters_Type = PARSIDATE_JALALI_PARAMS) {
        $hour = $this->datePart('hours');
        $min = $this->datePart('minutes');
        $sec = $this->datePart('seconds');

        if ($Parameters_Type == PARSIDATE_JALALI_PARAMS) {
            $Jyear = $year === NULL ? $this->datePart('year') : $year;
            $Jmonth = $month === NULL ? $this->datePart('mon') : $month;
            $Jday = $day === NULL ? $this->datePart('mday') : $day;
            list($gyear, $gmonth, $gday) = ParsiDate::to_gregorian($Jyear, $Jmonth, $Jday);
        } else {
            $gyear = $year === NULL ? date('Y', $this->_TimeStamp) : $year;
            $gmonth = $month === NULL ? date('m', $this->_TimeStamp) : $month;
            $gday = $day === NULL ? date('d', $this->_TimeStamp) : $day;
        }
        $this->setDateTime(mktime($hour, $min, $sec, $gmonth, $gday, $gyear));
        return $this;
    }

    /*
     * ========================================================
     *  Public method mktime() : php در mktime مشابه تابع
     *  Disc:  درون شیء میشود TimeStamp است که جایگزین  TimeStamp  نتیجه کار این متد مقدار 
     * 
     *  @param bool 
     *              $Parameters_Type=PARSIDATE_GREGORIAN_PARAMS     مشخص میکند که پارامتر های ورودی متد از نوع میلادی است
     *              $Parameters_Type=PARSIDATE_JALALI_PARAMS        مشخص میکند که پارامتر های ورودی متد از نوع جلالی است
     * ========================================================    
     */

    function mktime($hour, $min, $sec, $month, $day, $year, $Parameters_Type = PARSIDATE_GREGORIAN_PARAMS) {
        $hour = $this->datePart('hours');
        $min = $this->datePart('minutes');
        $sec = $this->datePart('seconds');
        if ($Parameters_Type == PARSIDATE_JALALI_PARAMS)
            list($year, $month, $day) = ParsiDate::to_gregorian($year, $month, $day);
        $this->setDateTime(mktime($hour, $min, $sec, $month, $day, $year));
        return $this;
    }

    /*
     * ========================================================
     *  Public method setDateTime() :  درون شیء را تغییر میدهد timestamp مقدار 
     *  @param (unix timestamp/string/object)  $DateTime     
     *  Disc:            مقدار ورودی متد میتواند از 3 نوع باشد
     *          1- unix timestamp = از نوع عددی صحیح timestamp مقدار 
     *          2- string = تبدیل میشود timestamp به مقدار  strtotime() وارد شود توسط تابع string اکر مقدار
     *          3- object = را میتوان وارد نمود ParsiDate شیء از کلاس 
     * ===========================================================
     */

    function setDateTime($DateTime) {
        $this->setTimeStamp($this->parseDate($DateTime));
        return $this;
    }

    /*
     * ========================================================
     *  Public method setTimeStamp :  درون شیء را تغییر میدهد Timestamp مقدار 
     *  @param (unix timestamp)  $timeStamp       
     * ===========================================================
     */

    function setTimeStamp($timeStamp) {
        if (is_numeric($timeStamp)) {
            $oldST = $this->getTimeStamp();
            $this->_TimeStamp = $timeStamp;
            if ($this->getTimeStamp() != $oldST) {
                $this->_dateArray = ParsiDate::to_jalali(date('Y', $this->getTimeStamp()), date('m', $this->getTimeStamp()), date('d', $this->getTimeStamp()));
                $this->ParsiGetdate();
            }
        }
        return $this;
    }

    /*
     * ========================================================
     *  Public method getTimeStamp :   درون شیء را برمیگرداند  Timestamp مقدار 
     *  @return (unix timestamp)   درون شیء TimeStamp خروجی مقدار
     * ===========================================================
     */

    function getTimeStamp() {
        return $this->_TimeStamp;
    }

    private function parseDate($dateTime) {
        $ts = 0; // timestamp of parsed $_timestamp
        switch ($dateTime) {
            case is_string($dateTime):
                $ts = strtotime($dateTime);
                break;
            case is_numeric($dateTime):
                $ts = $dateTime;
                break;
            case is_object($dateTime):
                if (get_class($dateTime) == "ParsiDate") {
                    $ts = $dateTime->getTimeStamp();
                }
        }
        return $ts;
    }

    /*
     * ========================================================
     *  Public method datePart : آرایه انجمنی برمیگرداند "php" در "getdate()" ماننده متد
     *  $Part :   برای تعیین عنصری مشخص از آرایه برای خروجی استفاده میگردد
     * 
     *  @param  string $Part = 'seconds'  نمایش ثانیه. رنج مقادیر خروجی اعداد بین  0-59 
     *  @param  string $Part = 'minutes'  نمایش دقیقه. رنج مقادیر خروجی اعداد بین  0-59
     *  @param  string $Part = 'hours'    نمایش ساعت. رنج مقادیر خروجی اعداد بین  0-23
     *  @param  string $Part = 'mday'     شماره روز ماه.رنج مقادیر خروجی اعداد بین 00-31
     *  @param  string $Part = 'mon'      شماره ماه سال. رنج مقادیر خروجی اعداد بین  1-12
     *  @param  string $Part = 'year'     عدد 4 رقمی نشان دهنده سال.  مثال  1391
     *  @param  string $Part = 'yday'     شمارش تعداد روزها در سال تا این تاریخ.  رنج مقادیر خروجی اعداد بین 1-366 
     *  @param  string $Part = 'wday'     شماره روز هفته . رنج مقادیر خروجی اعداد بین 0-6
     *  @param  string $Part = 'weekday'  روز هفته . مثال  شنبه - یکشنبه - دوشنبه
     *  @param  string $Part = 'month'    نام ماه . مثال  فروردین - اردیبهشت - خرداد    
     *  @param  string $Part = '0'        در خروجی "unixtimestamp" نمایش
     *  @return  
     *             array :  باشد خروجی تابع آرایه انجمنی از همه عناصر می باشد null برابر $part در صورتی که
     *             string/integer : را برمیگرداند $part مقدار عنصر مشخص شده در پارامتر
     * ===========================================================
     */

    function datePart($Part = null, $dateTime = "") {
        if ($dateTime)
            $this->setDateTime($dateTime);
        if ($Part == "timestamp")
            $Part = 0;
        if (isset($this->_datePart[$Part]))
            return $this->_datePart[$Part];
        return $this->_datePart;
    }

    /*
     * ===========================================================
     * Public method  Add()	کاهش/افزایش هریک از مقادیر سال / ماه/ روز / ساعت / دقیقه / ثانیه در درون کلاس 
     * 
     * @param string  $part   = ('months' یا  'month' یا 'm' یا 'mon')       تغییرات برای ماه اعمال شود
     * @param string  $part   = ('day'    یا  'days'  یا 'd' یا 'mday' )     تغییرات برای روز اعمال شود
     * @param string  $part   = ('year'   یا  'year'  یا 'y'   )             تغییرات برای سال اعمال شود    
     * @param string  $part   = ('hours'  یا  'hour'  یا 'g'   )             تغییرات برای ساعت اعمال شود
     * @param string  $part   = ('minutes' یا 'minute' یا 'i'  )             تغییرات برای دقیقه اعمال گردد
     * @param string  $part   = ('seconds' یا 'second' یا 's'  )             تغییرات برای ثانیه اعمال شود
     * 
     * @param integer $adjustValue   میزان تغییر . برای افزایش از اعداد + بدون علامت و برای کاهش از اعداد - با علامت استفاده شود. مثلا 1 ,-1
     * 
     * ===========================================================
     */

    public function Add($part, $adjustValue, $dateTime = "") {
        if ($dateTime)
            $this->setDateTime($dateTime);
        if (!is_int($adjustValue))
            $adjustValue = 0;

        $year = date('Y', $this->getTimeStamp());
        $month = date('m', $this->getTimeStamp());
        $day = date('d', $this->getTimeStamp());
        $hour = date('G', $this->getTimeStamp());
        $min = date('i', $this->getTimeStamp());
        $sec = date('s', $this->getTimeStamp());

        switch (strtolower($part)) {
            case "months":
            case "month": // month
            case "m":
            case "mon":
                $this->setDateTime(mktime($hour, $min, $sec, $month + $adjustValue, $day, $year));
                break;
            case "day": // day
            case "days":
            case "d":
            case "mday":
                $this->setDateTime(mktime($hour, $min, $sec, $month, $day + $adjustValue, $year));
                break;
            case "year": // year
            case "years":
            case "y":
            case "Y":
                $this->setDateTime(mktime($hour, $min, $sec, $month, $day, $year + $adjustValue));
                break;
            case "hours": // hour
            case "hour":
            case "g": case "G": case "H": case "h":
                $this->setDateTime(mktime($hour + $adjustValue, $min, $sec, $month, $day, $year));
                break;
            case "minutes": // minute
            case "minute":
            case "i":
                $this->setDateTime(mktime($hour, $min + $adjustValue, $sec, $month, $day, $year));
                break;
            case "seconds": // seconds
            case "second":
            case "s":
                $this->setDateTime(mktime($hour, $min, $sec + $adjustValue, $month, $day, $year));
                break;
        }
        return $this;
    }

    private function ParsiGetdate() {
        $this->_datePart = array(
            'seconds' => $this->ParsiFormat("s"),
            'minutes' => $this->ParsiFormat("i"),
            'hours' => $this->ParsiFormat("G"),
            'mday' => $this->ParsiFormat("d"),
            'wday' => $this->ParsiFormat("w"),
            'mon' => $this->ParsiFormat("m"),
            'year' => $this->ParsiFormat("Y"),
            'yday' => $this->ParsiFormat("z"),
            'weekday' => $this->ParsiFormat("l"),
            'month' => $this->ParsiFormat("F"),
            0 => $this->ParsiFormat("U"),
        );
    }

    /*
     * ===========================================================
     * Public method  showDate()  متد نمایش تاریخ به فرمت های مختلف	
     * @param string  $format   
     *                   'A'     = بعد از ظهر , قبل ازظهر  
     *                   'a'     = ب.ظ   , ق.ظ
     *                   'd'     = شماره روز ماه.رنج مقادیر خروجی اعداد بین 00-31
     *                   'D'     = روز هفته . مثال  ش - ی - د - س - چ - پ - ج   
     *                   'F'     = نام ماه . مثال  فروردین - اردیبهشت - خرداد    
     *                   'g'     = نمایش ساعت . رنج مقادیر خروجی اعداد بین 1-12 
     *                   'G'     = نمایش ساعت. رنج مقادیر خروجی اعداد بین  0-23
     *                   'H'     = نمایش ساعت . رنج مقادیر خروجی اعداد بین 00-23
     *                   'h'     = نمایش ساعت. رنج مقادیر خروجی اعداد بین 01-12
     *                   'i'     = نمایش دقیقه. رنج مقادیر خروجی اعداد بین  0-59
     *                   's'     = نمایش ثانیه. رنج مقادیر خروجی اعداد بین  0-59
     *                   'j'     = شماره روز ماه به صورت عدد صحیح. رنج مقادیر خروجی اعداد بین  1-31
     *                   'l'     = روز هفته . مثال  شنبه - یکشنبه - دوشنبه
     *                   'm'     = شماره ماه سال. رنج مقادیر خروجی اعداد بین  1-12
     *                   'M'     = نام ماه . مثال  فرو - ارد - خرد    
     *                   'n'     = شماره ماه سال به صورت عدد صحیح . رنج مقادیر خروجی اعداد بین  1-12
     *                   'L'     = درصورتی که سال کبیسه باشد 1 در غیر اینصورت 0 برمیگرداند
     *                   'S'     = "ام"
     *                   't'     = شماره آخرین روز ماه را برمیگرداند . رنج مقادیر خروجی اعداد بین 29-30-31
     *                   'U'     = unix timestamp
     *                   'w'     = شماره روز هفته . رنج مقادیر خروجی اعداد بین 0-6
     *                   'W'     = شمارش تعداد هفته ها تا این تاریخ
     *                   'y'     = عدد 2 رقمی نشان دهنده سال . مثال 91
     *                   'Y'     = عدد 4 رقمی نشان دهنده سال.  مثال  1391
     *                   'z'     = شمارش تعداد روزها در سال تا این تاریخ.  رنج مقادیر خروجی اعداد بین 1-366 
     * ===========================================================
     */

    public function showDate($format = null) {
        if (!$format)
            $format = $this->_format;
        $dateArray = ParsiDate::to_jalali(date('Y', $this->getTimeStamp()), date('m', $this->getTimeStamp()), date('d', $this->getTimeStamp()));
        return $this->ParsiDigits($this->ParsiFormat($format, $dateArray));
    }

    private function ParsiFormat($formats) {
        list($year, $month, $day) = $this->_dateArray;
        $i = 0;
        $lastchar = null;
        $ret = null;
        while (($ch = substr($formats, $i, 1)) !== false) {
            //--unformat chars
            if ($ch == '\\') {
                $ret .= substr($formats, $i + 1, 1);
                $i+=2;
                continue;
            }
            //--

            switch ($ch) {
                case 'A':
                    if (date('A', $this->getTimeStamp()) == 'PM')
                        $ret.='بعد از ظهر';
                    else
                        $ret.='قبل از ظهر';
                    break;
                case 'a':
                    if (date('A', $this->getTimeStamp()) == 'PM')
                        $ret.="ب.ظ";
                    else
                        $ret.="ق.ظ";
                    break;
                case 'd':
                    $ret.= sprintf('%02d', $day);           // day
                    break;
                case 'D':
                    $ret.= $this->ParsiWeekDay(date('w', $this->getTimeStamp()), true);         // Persian Shorted day of week ex:  ش
                    break;
                case 'F':
                    $ret.= $this->ParsiMonth($month);                           // Persian Month ex: فروردین
                    break;
                case 'g':
                    $ret.= date('g', $this->getTimeStamp());                        // 12-hour format of an hour without leading zeros(1 through 12)
                    break;
                case 'G':
                    $ret.= date('G', $this->getTimeStamp());                        // 24-hour format of an hour without leading zeros(0 through 23)
                    break;
                case "h":
                    $ret.= date("h", $this->getTimeStamp());                        // 12-hour format of an hour with leading zeros(01 through 12)
                    break;
                case "H":
                    $ret.= date("H", $this->getTimeStamp());                         // 24-hour format of an hour with leading zeros(00 through 23)
                    break;
                case "i":
                    $ret.= date("i", $this->getTimeStamp());                        // Minutes with leading zeros(00 to 59)
                    break;
                case "j":
                    $ret.= intval($day);                                        // intval(day)
                    break;
                case "l":
                    $ret.= $this->ParsiWeekDay(date('w', $this->getTimeStamp()), false);        // Persian  day of week ex:  شنبه 
                    break;
                case "m":
                    $ret.= sprintf('%02d', $month);                              // month
                    break;
                case "M":
                    $ret.= $this->ParsiMonth($month, true);                     // Persian  Shorted Month ex : فرو , ارد
                    break;
                case "n":
                    $ret.= intval($month);                                      // intval(month)
                    break;
                case "L":
                    $ret.=$this->ParsiIsLeaps($year) ? 1 : 0;                   // year is Leap (Kabiseh)
                    break;
                case "s":
                    $ret.=date("s", $this->getTimeStamp());                         // Seconds, with leading zeros	00 through 59
                    break;
                case "S":
                    $ret.='ام';
                    break;
                case "t":
                    $Isleap = $this->ParsiIsLeaps($year) ? 1 : 0;
                    if ($month <= 6)
                        $DaysInMonth = 31;
                    else if ($month > 6 && $month < 12)
                        $DaysInMonth = 30;
                    else if ($month == 12)
                        $DaysInMonth = $Isleap ? 30 : 29;
                    $ret.=$DaysInMonth;                        // last day of month
                    break;
                case "U" :
                    $ret.=$this->getTimeStamp();  // Unix TimeStamp
                    break;
                case "w":
                    $ParsiDaysNumber = array(6 => 0, 0 => 1, 1 => 2, 2 => 3, 3 => 4, 4 => 5, 5 => 6);
                    $ret.= $ParsiDaysNumber[date("w", $this->getTimeStamp())];      // day of week
                    break;
                case "W":
                    $ret.=ceil($this->dayOFYear($month, $day) / 7);                   // number of weeks  
                    break;
                case "y":
                    $ret.=substr($year, 2);                                         // short year ex : 1391  =>  91
                    break;
                case "Y":
                    $ret.=$year;                                                    // Full Year ex : 1391
                    break;
                case "z":
                    $ret.=$this->dayOFYear($month, $day);                           // the day of the year ex: 280  or 365
                    break;

                default : $ret .= $ch;
            }
            $i++;
        }
        return ($ret);
    }

    /*
     * ========================================================
     * Public method  Year()  
     * @return  سال را بر میگرداند. مثال 1391
     * ========================================================
     */

    function Year($dateTime = "") {
        if ($dateTime)
            $this->setDateTime($dateTime);
        return $this->ParsiDigits($this->datePart("year"));
    }

    /*
     * ========================================================
     * Public method  Month()  
     * @return    شماره ماه سال. رنج مقادیر خروجی اعداد بین  1-12
     * ========================================================
     */

    function Month($dateTime = "") {
        if ($dateTime)
            $this->setDateTime($dateTime);
        return $this->ParsiDigits($this->datePart("mon"));
    }

    /*
     * ========================================================
     * Public method  Day()  
     * @return     شماره روز ماه.رنج مقادیر خروجی اعداد بین 00-31
     * ========================================================
     */

    function Day($dateTime = "") {
        if ($dateTime)
            $this->setDateTime($dateTime);
        return $this->ParsiDigits($this->datePart("mday"));
    }

    /*
     * ========================================================
     * Public method  Hours()  
     * @return     نمایش ساعت . رنج مقادیر خروجی اعداد بین 1-12 
     * ========================================================
     */

    function Hours($dateTime = "") {
        if ($dateTime)
            $this->setDateTime($dateTime);
        return $this->ParsiDigits($this->datePart("hours"));
    }

    /*
     * ========================================================
     *  Public method  Minutes() 
     * @return     نمایش دقیقه. رنج مقادیر خروجی اعداد بین  0-59
     * ========================================================
     */

    function Minutes($dateTime = "") {
        if ($dateTime)
            $this->setDateTime($dateTime);
        return $this->ParsiDigits($this->datePart("minutes"));
    }

    /*
     * ========================================================
     * Public method  Seconds() 
     * @return   نمایش ثانیه. رنج مقادیر خروجی اعداد بین  0-59
     * ========================================================
     */

    function Seconds($dateTime = "") {
        if ($dateTime)
            $this->setDateTime($dateTime);
        return $this->ParsiDigits($this->datePart("seconds"));
    }

    /*
     * =========================================================
     * public static method to_jalali() 
     * Disc :   تابع تبدل تاریخ میلادی به جلالی
     * Authors : Roozbeh Pournader and Mohammad Toosi
     * @return array  خروجی آرایه به صورت تاریخ جلالی
     * =========================================================
     */

    public static function to_jalali($g_year, $g_month, $g_day) {
        $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
        $gy = $g_year - 1600;
        $gm = $g_month - 1;
        $gd = $g_day - 1;
        $g_day_no = 365 * $gy + self::div($gy + 3, 4) - self::div($gy + 99, 100) + self::div($gy + 399, 400);
        for ($i = 0; $i < $gm; ++$i)
            $g_day_no += $g_days_in_month[$i];
        if ($gm > 1 && (($gy % 4 == 0 && $gy % 100 != 0) || ($gy % 400 == 0)))
            $g_day_no++; /* leap and after Feb */
        $g_day_no += $gd;
        $j_day_no = $g_day_no - 79;
        $j_np = self::div($j_day_no, 12053); /* 12053 = 365*33 + 32/4 */
        $j_day_no = $j_day_no % 12053;
        $jy = 979 + 33 * $j_np + 4 * self::div($j_day_no, 1461); /* 1461 = 365*4 + 4/4 */
        $j_day_no %= 1461;
        if ($j_day_no >= 366) {
            $jy += self::div($j_day_no - 1, 365);
            $j_day_no = ($j_day_no - 1) % 365;
        }
        for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i)
            $j_day_no -= $j_days_in_month[$i];
        $jm = $i + 1;
        $jd = $j_day_no + 1;
        return array($jy, $jm, $jd);
    }

    /*
     * =========================================================
     * public static method to_gregorian() 
     * Disc :   تابع تبدیل تاریخ جلالی به میلادی
     * Authors : Roozbeh Pournader and Mohammad Toosi
     * @return array  خروجی آرایه به صورت تاریخ میلادی
     * =========================================================
     */

    public static function to_gregorian($j_year, $j_month, $j_day) {
        $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
        $jy = $j_year - 979;
        $jm = $j_month - 1;
        $jd = $j_day - 1;
        $j_day_no = 365 * $jy + self::div($jy, 33) * 8 + self::div($jy % 33 + 3, 4);
        for ($i = 0; $i < $jm; ++$i)
            $j_day_no += $j_days_in_month[$i];
        $j_day_no += $jd;
        $g_day_no = $j_day_no + 79;
        $gy = 1600 + 400 * self::div($g_day_no, 146097); /* 146097 = 365*400 + 400/4 - 400/100 + 400/400 */
        $g_day_no = $g_day_no % 146097;
        $leap = true;
        if ($g_day_no >= 36525) { /* 36525 = 365*100 + 100/4 */
            $g_day_no--;
            $gy += 100 * self::div($g_day_no, 36524); /* 36524 = 365*100 + 100/4 - 100/100 */
            $g_day_no = $g_day_no % 36524;
            if ($g_day_no >= 365)
                $g_day_no++;
            else
                $leap = false;
        }
        $gy += 4 * self::div($g_day_no, 1461); /* 1461 = 365*4 + 4/4 */ $g_day_no %= 1461;
        if ($g_day_no >= 366) {
            $leap = false;
            $g_day_no--;
            $gy += self::div($g_day_no, 365);
            $g_day_no = $g_day_no % 365;
        }
        for ($i = 0; $g_day_no >= $g_days_in_month[$i] + ($i == 1 && $leap); $i++)
            $g_day_no -= $g_days_in_month[$i] + ($i == 1 && $leap);
        $gm = $i + 1;
        $gd = $g_day_no + 1;
        return array($gy, $gm, $gd);
    }

    private static function div($a, $b) {
        return (int) ($a / $b);
    }

    //=========================================================
    private function ParsiWeekDay($day, $Short = false) {
        switch ($day) {
            case 6:
                if ($Short)
                    return 'ش';
                else
                    return 'شنبه';
                break;
            case 0:
                if ($Short)
                    return 'ی';
                else
                    return 'يكشنبه';
                break;
            case 1:
                if ($Short)
                    return 'د';
                else
                    return 'دوشنبه';
                break;
            case 2:
                if ($Short)
                    return 'س';
                else
                    return 'سه شنبه';
                break;
            case 3:
                if ($Short)
                    return 'چ';
                else
                    return 'چهارشنبه';
                break;
            case 4:
                if ($Short)
                    return 'پ';
                else
                    return 'پنجشنبه';
                break;
            case 5:
                if ($Short)
                    return 'ج';
                else
                    return 'جمعه';
                break;
        }
    }

    private function ParsiMonth($Month, $Short = false) {
        switch ($Month) {
            case 1:
                if ($Short)
                    return "فرو";
                else
                    return "فروردین";
                break;
            case 2:
                if ($Short)
                    return "ارد";
                else
                    return "اردیبهشت";
                break;
            case 3:
                if ($Short)
                    return "خرد";
                else
                    return "خرداد";
                break;
            case 4:
                if ($Short)
                    return "تیر";
                else
                    return "تير";
                break;
            case 5:
                if ($Short)
                    return "مرد";
                else
                    return "مرداد";
                break;
            case 6:
                if ($Short)
                    return "شهر";
                else
                    return "شهریور";
                break;
            case 7:
                return "مهر";
                break;
            case 8:
                if ($Short)
                    return "آبا";
                else
                    return "آبان";
                break;
            case 9:
                return "آذر";
                break;
            case 10:
                return "دى";
                break;
            case 11:
                if ($Short)
                    return "بهم";
                else
                    return "بهمن";
                break;
            case 12:
                if ($Short)
                    return "اصف";
                else
                    return "اسفند";
                break;
        }
    }

    private function getLocalTime($TimeZone = 'Asia/Tehran', $Time = null) {
        $TZ = array(
            'Pacific/Midway' => -11,
            'America/Adak' => -10,
            'Etc/GMT10' => -10,
            'Pacific/Marquesas' => -09.5,
            'Pacific/Gambier' => -09,
            'America/Anchorage' => -09,
            'America/Ensenada' => -08,
            'Etc/GMT8' => -08,
            'America/Los_Angeles' => -08,
            'America/Denver' => -07,
            'America/Chihuahua' => -07,
            'America/Dawson_Creek' => -07,
            'America/Belize' => -06,
            'America/Cancun' => -06,
            'Chile/EasterIsland' => -06,
            'America/Chicago' => -06,
            'America/New_York' => -05,
            'America/Havana' => -05,
            'America/Bogota' => -05,
            'America/Caracas' => -04.5,
            'America/Santiago' => -04,
            'America/La_Paz' => -04,
            'Atlantic/Stanley' => -04,
            'America/Campo_Grande' => -04,
            'America/Goose_Bay' => -04,
            'America/Glace_Bay' => -04,
            'America/St_Johns' => -03.5,
            'America/Araguaina' => -03,
            'America/Montevideo' => -03,
            'America/Miquelon' => -03,
            'America/Godthab' => -03,
            'America/Argentina/Buenos_Aires' => -03,
            'America/Sao_Paulo' => -03,
            'America/Noronha' => -02,
            'Atlantic/Cape_Verde' => -01,
            'Atlantic/Azores' => -01,
            'Europe/Belfast' => 0,
            'Europe/Dublin' => 0,
            'Europe/Lisbon' => 0,
            'Europe/London' => 0,
            'Africa/Abidjan' => 0,
            'Europe/Amsterdam' => 01,
            'Europe/Belgrade' => 01,
            'Europe/Brussels' => 01,
            'Africa/Algiers' => 01,
            'Africa/Windhoek' => 01,
            'Asia/Beirut' => 02,
            'Africa/Cairo' => 02,
            'Asia/Gaza' => 02,
            'Africa/Blantyre' => 02,
            'Asia/Jerusalem' => 02,
            'Europe/Minsk' => 02,
            'Asia/Damascus' => 02,
            'Europe/Moscow' => 03,
            'Africa/Addis_Ababa' => 03,
            'Asia/Tehran' => 03.5,
            'Asia/Dubai' => 04,
            'Asia/Yerevan' => 04,
            'Asia/Kabul' => 04.5,
            'Asia/Yekaterinburg' => 05,
            'Asia/Tashkent' => 05,
            'Asia/Kolkata' => 05.5,
            'Asia/Katmandu' => 05.75,
            'Asia/Dhaka' => 06,
            'Asia/Novosibirsk' => 06,
            'Asia/Rangoon' => 06.5,
            'Asia/Bangkok' => 07,
            'Asia/Krasnoyarsk' => 07,
            'Asia/Hong_Kong' => 08,
            'Asia/Irkutsk' => 08,
            'Australia/Perth' => 08,
            'Australia/Eucla' => 08.75,
            'Asia/Tokyo' => 09,
            'Asia/Seoul' => 09,
            'Asia/Yakutsk' => 09,
            'Australia/Adelaide' => 09.5,
            'Australia/Darwin' => 09.5,
            'Australia/Brisbane' => 10,
            'Australia/Hobart' => 10,
            'Asia/Vladivostok' => 10,
            'Australia/Lord_Howe' => 10.5,
            'Etc/GMT-11' => 11,
            'Asia/Magadan' => 11,
            'Pacific/Norfolk' => 11.5,
            'Asia/Anadyr' => 12,
            'Pacific/Auckland' => 12,
            'Etc/GMT-12' => 12,
            'Pacific/Chatham' => 12.75,
            'Pacific/Tongatapu' => 13,
            'Pacific/Kiritimati' => 14,
        );
        if (!is_numeric($Time))
            $Time = time();
        $tz = 0;
        if (isset($TZ[$TimeZone]))
            $tz = $TZ[$TimeZone];
        if (is_numeric($TimeZone))
            $tz = $TimeZone;
        $Time -= date("Z", time()); // get base time
        $Time+= ($tz * 60 * 60);
        return $Time;
    }

    private function ParsiIsLeaps($yearValue) {
        return array_search((($yearValue + 2346) % 2820) % 128, array(
            5, 9, 13, 17, 21, 25, 29,
            34, 38, 42, 46, 50, 54, 58, 62,
            67, 71, 75, 79, 83, 87, 91, 95,
            100, 104, 108, 112, 116, 120, 124, 0
        ));
    }

    private function dayOFYear($month, $day) {
        return $month <= 6 ?
                ($month - 1 * 31 + $day) :
                186 + (($month - 6 - 1) * 30 + $day);
    }

    /*
     * ========================================================
     * public method englishDigits()
     * Disc: در صورت فراخوانی این تابع مقادیر عددی در خروجی به صورتی اعداد انگلیسی نمایش داده می شود
     * ========================================================
     */

    public function englishDigits() {
        $this->_ParsiDigit = false;
        return $this;
    }

    /*
     * ========================================================
     * public method persianDigits()
     * Disc: در صورت فراخوانی این تابع مقادیر عددی در خروجی به صورتی اعداد فارسی نمایش داده می شود
     * ========================================================
     */

    public function persianDigits() {
        $this->_ParsiDigit = true;
        return $this;
    }

    private function ParsiDigits($string) {
        if ($this->_ParsiDigit !== true)
            return $string;
        $parsi_digit = array('&#1776;', '&#1777;', '&#1778;', '&#1779;', '&#1780;', '&#1781;', '&#1782;', '&#1783;', '&#1784;', '&#1785;');
        $ret = null;
        $i = 0;
        while (($ch = substr($string, $i, 1)) !== false) {
            if (isset($parsi_digit[$ch]))
                $ret.=$parsi_digit[$ch];
            else
                $ret.=$ch;
            $i++;
        }
        return $ret;
    }

    function __toString() {
        return $this->showDate();
    }

    ###################################################
    #=================================================#
    //          Some Other Auxiliary method
    #=================================================# 
    ###################################################

    /*
     * ========================================================
     * public method endOfWeek()
     * Disc: ذخیره میکند "parsidate" آخرین روز هفته را در شیء  جدید از کلاس
     * @return object  میباشد "parsidate"  خروجی شیء جدید از کلاس 
     * ========================================================
     */

    function endOfWeek($dateTime = null) {
        if ($dateTime)
            $this->setDateTime($dateTime);
        $obj = new ParsiDate('Y/m/d', $this->getTimeStamp(), $this->_TimeZone);
        $obj->Add('d', (intval($obj->ParsiFormat('w')) - 6) * -1);
        return $obj;
    }

    /*
     * ========================================================
     * public method beginOfWeek()
     * Disc: ذخیره میکند "parsidate" اولین روز هفته را در شیء  جدید از کلاس
     * @return object  میباشد "parsidate"  خروجی شیء جدید از کلاس 
     * ========================================================
     */

    function beginOfWeek($dateTime = null) {
        if ($dateTime)
            $this->setDateTime($dateTime);
        $obj = new ParsiDate('Y/m/d', $this->getTimeStamp(), $this->_TimeZone);
        $obj->Add('d', intval($obj->ParsiFormat('w')) * -1);
        return $obj;
    }

    /*
     * ========================================================
     * public method beginOfMonth()
     * Disc: ذخیره میکند "parsidate" اولین روز ماه را در شیء  جدید از کلاس
     * @return object  میباشد "parsidate"  خروجی شیء جدید از کلاس 
     * ========================================================
     */

    function beginOfMonth($dateTime = null) {
        if ($dateTime)
            $this->setDateTime($dateTime);
        $obj = new ParsiDate('Y/m/d', $this->getTimeStamp(), $this->_TimeZone);
        $obj->setDate(null, null, 1, PARSIDATE_JALALI_PARAMS);
        return $obj;
    }

    /*
     * ========================================================
     * public method endOfMonth()
     * Disc: ذخیره میکند "parsidate" آخرین روز ماه را در شیء  جدید از کلاس
     * @return object  میباشد "parsidate"  خروجی شیء جدید از کلاس 
     * ========================================================
     */

    function endOfMonth($dateTime = null) {
        if ($dateTime)
            $this->setDateTime($dateTime);
        $obj = new ParsiDate('Y/m/d', $this->getTimeStamp(), $this->_TimeZone);
        $obj->setDate(null, null, $obj->ParsiFormat('t'), PARSIDATE_JALALI_PARAMS);
        return $obj;
    }

    /*
     * ========================================================
     * public method beginOfLastMonth()
     * Disc: ذخیره میکند "parsidate" اولین روز ماه گذشته را در شیء  جدید از کلاس
     * @return object  میباشد "parsidate"  خروجی شیء جدید از کلاس 
     * ========================================================
     */

    function beginOfLastMonth($dateTime = null) {
        if ($dateTime)
            $this->setDateTime($dateTime);
        $obj = new ParsiDate('Y/m/d', $this->getTimeStamp(), $this->_TimeZone);
        $obj->Add('m', -1);
        $obj->setDate(null, null, 1, PARSIDATE_JALALI_PARAMS);
        return $obj;
    }

    /*
     * ========================================================
     * public method endOfLastMonth()
     * Disc: ذخیره میکند "parsidate" آخرین روز ماه گذشته  را در شیء  جدید از کلاس
     * @return object  میباشد "parsidate"  خروجی شیء جدید از کلاس 
     * ========================================================
     */

    function endOfLastMonth($dateTime = null) {
        if ($dateTime)
            $this->setDateTime($dateTime);
        $obj = new ParsiDate('Y/m/d', $this->getTimeStamp(), $this->_TimeZone);
        $obj->Add('m', -1);
        $obj->setDate(null, null, $obj->ParsiFormat('t'), PARSIDATE_JALALI_PARAMS);
        return $obj;
    }

    /*
     * ========================================================
     * public method beginOfYear()
     * Disc: ذخیره میکند "parsidate" اولین روز سال را در شیء  جدید از کلاس
     * @return object  میباشد "parsidate"  خروجی شیء جدید از کلاس 
     * ========================================================
     */

    function beginOfYear($dateTime = null) {
        if ($dateTime)
            $this->setDateTime($dateTime);
        $obj = new ParsiDate('Y/m/d', $this->getTimeStamp(), $this->_TimeZone);
        $obj->setDate(null, 1, 1, PARSIDATE_JALALI_PARAMS);
        return $obj;
    }

    /*
     * ========================================================
     * public method endOfYear()
     * Disc: ذخیره میکند "parsidate" آخرین روز سال را در شیء  جدید از کلاس
     * @return object  میباشد "parsidate"  خروجی شیء جدید از کلاس 
     * ========================================================
     */

    function endOfYear($dateTime = null) {
        if ($dateTime)
            $this->setDateTime($dateTime);
        $obj = new ParsiDate('Y/m/d', $this->getTimeStamp(), $this->_TimeZone);
        $obj->setDate(null, 12, null, PARSIDATE_JALALI_PARAMS);
        $obj->setDate(null, null, $obj->ParsiFormat('t'), PARSIDATE_JALALI_PARAMS);
        return $obj;
    }

    /*
     * ========================================================
     * public method beginOfLastYear()
     * Disc: ذخیره میکند "parsidate" اولین روز سال گذشته را در شیء  جدید از کلاس
     * @return object  میباشد "parsidate"  خروجی شیء جدید از کلاس 
     * ========================================================
     */

    function beginOfLastYear($dateTime = null) {
        if ($dateTime)
            $this->setDateTime($dateTime);
        $obj = new ParsiDate('Y/m/d', $this->getTimeStamp(), $this->_TimeZone);
        $obj->setDate($this->datePart("year") - 1, 1, 1, PARSIDATE_JALALI_PARAMS);
        return $obj;
    }

    /*
     * ========================================================
     * public method endOfLastYear()
     * Disc: ذخیره میکند "parsidate" آخرین روز سال گذشته را در شیء  جدید از کلاس
     * @return object  میباشد "parsidate"  خروجی شیء جدید از کلاس 
     * ========================================================
     */

    function endOfLastYear($dateTime = null) {
        if ($dateTime)
            $this->setDateTime($dateTime);
        $obj = new ParsiDate('Y/m/d', $this->getTimeStamp(), $this->_TimeZone);
        $obj->setDate($this->datePart("year") - 1, 12, null, PARSIDATE_JALALI);
        $obj->setDate(null, null, $obj->ParsiFormat('t'), PARSIDATE_JALALI);
        return $obj;
    }

    /*
     * ========================================================
     * public method isKabise()
     * @return bool   برمیگرداند  false  در غیر اینصورت true در صورتی که سال کبیسه باشد مقدار 
     * ========================================================
     */

    function isKabise() {
        return $this->ParsiFormat('L') ? true : false;
    }

}

function parsidate($format = 'Y/m/d G:i:s', $dateTime = "now", $TimeZone = 'Asia/Tehran') {
    return new ParsiDate($format, $dateTime, $TimeZone);
}

