<?php


/* /-------------------------\ */
/* \      some functions     / */
/* /-------------------------\ */

define('DATE_TODAY', 1);
define('DATE_YESTERDAY', 2);

define('DATE_THISWEEK_START', 3);
define('DATE_THISWEEK_END', 4);

define('DATE_LASTWEEK_START', 5);
define('DATE_LASTWEEK_END', 6);

define('DATE_THISMONTH_START', 7);
define('DATE_THISMONTH_END', 8);

define('DATE_LASTMONTH_START', 9);
define('DATE_LASTMONTH_END', 10);

define('DATE_THISYEAR_START', 11);
define('DATE_THISYEAR_END', 12);

define('DATE_LASTYEAR_START', 13);
define('DATE_LASTYEAR_END', 14);

function getDatetime($time = DATE_TODAY, $format = 'Y-m-d')
{
    $dt = new pengu_date();

    switch ($time) {
        case DATE_TODAY:
            return $dt->toString($format);
            break;
        case DATE_YESTERDAY:
            return $dt->add('d', -1)->toString($format);
            break;

        case DATE_THISWEEK_START:
            return $dt->beginOfWeek()->toString($format);
            break;
        case DATE_THISWEEK_END:
            return $dt->endOfWeek()->toString($format);
            break;

        case DATE_LASTWEEK_START:
            return $dt->beginOfWeek()->add('d', -7)->toString($format);
            break;
        case DATE_LASTWEEK_END:
            return $dt->endOfWeek()->add('d', -7)->toString($format);
            break;

        case DATE_THISMONTH_START:
            return $dt->beginOfMonth()->toString($format);
            break;
        case DATE_THISMONTH_END:
            return $dt->endOfMonth()->toString($format);
            break;

        case DATE_LASTMONTH_START:
            return $dt->beginOfLastMonth()->toString($format);
            break;
        case DATE_LASTMONTH_END:
            return $dt->endOfLastMonth()->toString($format);
            break;

        case DATE_THISYEAR_START:
            return $dt->beginOfYear()->toString($format);
            break;
        case DATE_THISYEAR_END:
            return $dt->endOfYear()->toString($format);
            break;

        case DATE_LASTYEAR_START:
            return $dt->beginOfYear()->add('y', -1)->toString($format);
            break;
        case DATE_LASTYEAR_END:
            return $dt->endOfYear()->add('y', -1)->toString($format);
            break;
    }
}

function toDate($time, $format = 'Y-m-d H:i', $unValidChar = '-')
{
    return ($time > 1000000000 ? date($format, $time) : $unValidChar);
}

function toPrice($price, $numeric = false, $priceFormat = '%.2f')
{
    if ($numeric)
        return sprintf($priceFormat, $price);
    return '$' . number_format(doubleval($price), 2);
}

function toCTR($imps, $clicks)
{

    return sprintf('%.2f', ($imps > 0 ? intval($clicks) / intval($imps) : 0) * 100) . '%';
}

function toECPM($imps, $cost)
{
    return '$' . sprintf('%.3f', ($imps > 0 ? doubleval($cost) / intval($imps) : 0) * 1000);
}

function toRPM($imps, $earning)
{
    return '$' . sprintf('%.2f', ($imps > 0 ? doubleval($earning) / intval($imps) : 0) * 1000);
}
