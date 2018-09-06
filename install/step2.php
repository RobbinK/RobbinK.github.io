<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: step2.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */


if (!preg_match('/install$/', dirname(__FILE__)))
    exit('/install folder was not found!');


##############################################
require_once('../path.php');
require_once(ROOT_PATH . '/core/_jp.php');

function get_url_contents($URL)
{
    if ($contents = @file_get_contents($URL))
        return $contents;
    $c = curl_init();
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_URL, $URL);
    $contents = @curl_exec($c);
    curl_close($c);

    if ($contents)
        return $contents;
    else
        return false;
}

class installation extends pengu_db
{

    function __construct()
    {
        global $ConnectOptions;
        parent::__construct($ConnectOptions);
        $this->ping();
    }

    function chklicense()
    {
        $contents = @get_url_contents(ROOT_URL . '/sys/getver.html');
        if (strpos($contents, 'error(201)') !== false)
            return false;
        return true;
    }

    function chkmdrew()
    {
        if (strpos(@get_url_contents(ROOT_URL . '/sys/check/modrewrite.html'), 'true') !== false || !$this->chklicense())
            return true;
        else if (function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules()))
            return true;
        else if (@getenv('HTTP_MOD_REWRITE') == 'On')
            return true;
        else if (strpos(@shell_exec('/usr/local/apache/bin/apachectl -l'), 'mod_rewrite') !== false)
            return true;
        return false;
    }

    function checkStats()
    {
        $data = null;
        $error = 0;
        $data = '<style>fieldset{height:380px;}</style>';
        $data .= '(Checking requirements)<br/><br/>';
        $data .= "<div style='overflow-y: scroll;height: 260px;background: #FCFCFC;border: solid 1px #F7F7F7;padding: 5px;'>";
        if (version_compare(phpversion(), '5.2.17', '>'))
            $data .= "- PHP version  .... <font style='color:green'>" . phpversion() . " .. ok</font><br>\n";
        else {
            $data .= "- PHP version  .... <font style='color:#EC0000'>" . phpversion() . "</font><br>\n";
            $error = 1;
        }

        if ($this->chklicense()) {
            $data .= "- Script license  .... <font style='color:green'> is valid .. ok</font><br>\n";
        } else {
            $data .= "- Script license  .... <font style='color:#EC0000'> is  not valid </font><br>\n";
            $error = 1;
        }

        if (!function_exists('base64_decode')) {
            $data .= "- base64_decode function   .... <font style='color:#EC0000'> is desabled on server </font><br>\n";
            $error = 1;
        }

        if ($this->chkmdrew()) {
            $data .= "- mod_rewrite  .... <font style='color:green'> is enabled .. ok</font><br>\n";
        } else {
            $data .= "- mod_rewrite  .... <font style='color:#EC0000'> was not found </font><br>\n";
            $error = 1;
        }

        if (function_exists('mysqli_connect'))
            $data .= "- mysqli Extention .... <font style='color:green'> was detected .. ok</font><br>\n";
        else {
            $data .= "- mysqli Extention .... <font style='color:#EC0000'> was not found</font><br>\n";
            $error = 1;
        }
        if (function_exists('curl_init'))
            $data .= "- curl Extention .... <font style='color:green'> was detected .. ok</font><br>\n";
        else {
            $data .= "- curl Extention .... <font style='color:#EC0000'> was not found</font><br>\n";
        }

        if (extension_loaded('gd') && function_exists('gd_info'))
            $data .= "- GD Extention .... <font style='color:green'> was found .. ok</font><br>\n";
        else
            $data .= "- GD Extention .... <font style='color:orange'> was not found </font><br>\n";


        if ($this->ping())
            $data .= "- Database Status .... <font style='color:green'> is connected</font><br/>\n";
        else {
            $data .= "- Database Status .... <font style='color:#EC0000'> is not connected </font><br/>\n";
            $data .= "<div class='hint'>To connect to database edit <b>config / db.config.php</b> and enter the valid username and password</div>";
            $error = 1;
        }

        $premission = path::filePermission(ROOT_PATH . '/tmp');
        if ($premission >= '0777') {
            rmkdir(ROOT_PATH . '/tmp/etc');
            $data .= "-  <font style='color:#44C413'>/tmp/</font> folder permission was set to " . $premission . "<font style='color:green'>... ok </font><br>\n";
        } else {
            $data .= "- <font style='color:#EC0000'> /tmp/ folder doesn't have enough permission </font> <br> tmp folder path : <b>" . str::summarize(ROOT_PATH, 20, true, '/') . "/tmp</b><br>\n";
            $error = 1;
        }

        $premission = path::filePermission(ROOT_PATH . '/content');
        if ($premission >= '0777') {
            $data .= "- <font style='color:#44C413'>/content/</font> folder permission was set to " . $premission . "<font style='color:green'>... ok </font><br>\n";
        } else {
            $data .= "- <font style='color:#EC0000'> /content/ folder doesn't have enough permission </font> <br> content folder path : <b>" . str::summarize(ROOT_PATH, 20, true, '/') . "/content</b><br>\n";
            $error = 1;
        }

        if ($this->ping()) {
            $q = $this->query("SHOW TABLES LIKE 'abs_%'")->exec();
            if ($q && $q->found()) {
                $data .= "- <font style='color:#EC0000'>There are some tables with the same name in your database! <br>\n";
                $data .= "<div class='hint'>Please remove all tables with abs_ prefix and try again</div>";
                $error = 1;
            }
        }
        $data .= '</div>';
        $data .= ' <br>';

        if ($error) {
            $data .= '<a class="button-silver" href="' . url::itself()->url_nonqry() . '"><span> Check again</span></a>';
            $data .= ' &nbsp;&nbsp;&nbsp; <a href="step3.php"><span> Skip and Install it anyway</span></a>';
        } else
            $data .= '<a class="button-silver" href="step3.php"><span> Next Step</span></a>';
        return $data;
    }

}

include 'header.php';
$ins = new installation;
echo $ins->checkStats();
include 'footer.php';
?>