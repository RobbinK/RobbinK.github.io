<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: booter.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */


define('MYSQL_LOG_BULK', 1000);
include_once ("sqlcach.class.php");

$ConnectOptions = array(
    'host' => CONFIG_DB_HOST,
    'db' => CONFIG_DB_NAME,
    'user' => CONFIG_DB_USER,
    'pass' => CONFIG_DB_PASSWORD,
    'persist' => false,
    'cachesPath' => ROOT_PATH . '/tmp/cache/mysql',
    'logsPath' => ROOT_PATH . '/tmp/logs/mysql'
);

if (isset($_SESSION['DB_AFFECTING']))
    $ConnectOptions['affecting'] = $_SESSION['DB_AFFECTING'];
elseif (defined('CONFIG_DB_AFFECTING'))
    $ConnectOptions['affecting'] = CONFIG_DB_AFFECTING;

if (!defined('CONFIG_DB_CONNECTOR'))
    define('CONFIG_DB_CONNECTOR', 'mysqli');

//<<<<<<<<<<<<<<<<< sql Injection Safe >>>>>>>>>>>>>>>>>>>>>
global $injectionkeywords;
$injectionkeywords = array('drop', 'delete', 'insert', 'table', 'update', 'database', '--');
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
switch (1) {
    case CONFIG_DB_CONNECTOR == 'pdo' && class_exists('PDO') : $connector = 'pdo';
        break;
    case CONFIG_DB_CONNECTOR == 'mysqli' && function_exists('mysqli_connect') : $connector = 'mysqli';
        break;
    case CONFIG_DB_CONNECTOR == 'mysql' && function_exists('mysql_connect') : $connector = 'mysql';
        break;
    default: class_exists('PDO') ? $connector = 'pdo' : (function_exists('mysqli_connect') ? $connector = 'mysqli' : $connector = 'mysql');
}

if ($connector == 'pdo') {
    define('DB_CONNECTION_TYPE', 'pdo');
    include_once 'pdo/pdo.class.php';

    class pengu_db extends pengu_PDO {

        function __construct($config) {
            parent::__construct($config);
        }

    }

} elseif ($connector == 'mysqli') {
    define('DB_CONNECTION_TYPE', 'mysqli');
    include_once 'mysqli/mysqli.class.php';

    class pengu_db extends pengu_mysqli {

        function __construct($config) {
            parent::__construct($config);
        }

    }

} else if ($connector == 'mysql') {
    define('DB_CONNECTION_TYPE', 'mysql');
    include_once 'mysql/mysql.class.php';

    class pengu_db extends pengu_mysql {

        function __construct($config) {
            parent::__construct($config);
        }

    }

}
