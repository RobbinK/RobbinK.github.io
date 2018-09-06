<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: step3.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */

if (!preg_match('/install$/', dirname(__FILE__)))
    exit('/install folder was not found!');


##############################################
require_once ('../path.php');
require_once (ROOT_PATH . '/core/_jp.php');

class installation extends pengu_db {

    function __construct() {
        global $ConnectOptions;
        parent::__construct($ConnectOptions);
        $this->ping();
    }

    function create_structure() {
        set_time_limit(15 * 60);
        @ini_set('memory_limit', '1024M');
        $struct = file_get_contents(ROOT_PATH . "/install/files/structure.sql");
        $queries = explode(';', $struct);
        $executed = 0;
        foreach ($queries as $query) {
            $this->query($query)->exec();
            if ($this->errorno() == 0)
                $executed++;
        }
        return $executed;
    }

    function create_default_data() {
        sleep(2);
        set_time_limit(15 * 60);
        @ini_set('memory_limit', '1024M');
        $this->unlink();
        $this->link();
        $this->query("SET GLOBAL max_allowed_packet=200*1024*1024;")->exec();
        $default_tables = array('abs_ads', 'abs_blocks', 'abs_links', 'abs_members_group', 'abs_pages', 'abs_settings', 'abs_zones');

        $executed = 0;
        foreach ($default_tables as $f) {
            $this->settable($f);
            $exec = @$this->query("SHOW TABLES LIKE '{$f}';")->exec();
            if ($exec && $exec->found() && $this->select()->getcount() == 0) {
                $queries = file_get_contents(ROOT_PATH . "/install/files/defaultdata/{$f}.sql");
                $this->free_result();
                $this->multiquery($queries)->exec();
                if ($this->errorno() == 0) {
                    $executed++;
                }
            }
        }
        return $executed;
    }

    function create_iprange_data() {
        set_time_limit(20 * 60);
        @ini_set('memory_limit', '2048M');
        $this->unlink();
        $this->link();
        $this->query("SET GLOBAL max_allowed_packet=1048*1024*1024;")->exec(); // 1G 
        $error = 0;
        $t = 'abs_iprange';
        $this->settable($t);
        if ($this->query("SHOW TABLES LIKE '{$t}'")->exec()->found() && $this->select()->getcount() == 0) {
            $data = file_get_contents(ROOT_PATH . "/install/files/defaultdata/{$t}.sql");
            $queries = explode(';', $data);

            while ($q = current($queries)) {
                $this->free_result();
                if (trim($q) != '') {
                    $this->multiquery($q . ';')->exec();
                    if ($this->errorno() != 0) {
                        $error++;
                        if (DEVELOP) {
                            perror($this->lasterror() . '<br><small>sql : <b>' . $this->lastsql() . '<b></small>');
                        }
                    }
                }
                next($queries);
            }
            if (!$error)
                return true;
        }
        return false;
    }

    function create_sample_data() {
        set_time_limit(15 * 60);
        $this->unlink();
        $this->link();
        @ini_set('memory_limit', '1024M');
        if ($this->ping()) {
            $sample_tables = array('abs_games', 'abs_categories');

            $executed = 0;
            foreach ($sample_tables as $f) {
                $this->settable($f);
                if ($this->query("SHOW TABLES LIKE '{$f}'")->exec()->found() && $this->select()->getcount() == 0) {
                    $queries = file_get_contents(ROOT_PATH . "/install/files/sampledata/{$f}.sql");
                    $this->free_result();
                    $this->multiquery($queries)->exec();
                    if ($this->errorno() == 0) {
                        if ($f == 'abs_games') {
                            @path::RecursiveCopy(ROOT_PATH . "/install/files/sampledata/games/images", ROOT_PATH . "/content/upload/games/images", 0666);
                            @path::RecursiveCopy(ROOT_PATH . "/install/files/sampledata/games/files", ROOT_PATH . "/content/upload/games/files", 0666);
                        }
                        $executed++;
                    }
                }
            }
            return $executed;
        }
    }

    function installManually() {
        pinfo("To install the script manually, you can use the links below:")->priority(4);
        pinfo("<a href='" . ROOT_URL . "/install/files/defaultdata/abs_iprange.sql'>Download ArcadeBooster iprange table</a>")->priority(6);
        pinfo("<a href='" . ROOT_URL . "/install/files/fulldb.sql'>Download ArcadeBooster structure</a>")->priority(5);
        pinfo("<a href='" . ROOT_URL . "/install/files/readme.txt'>Installation Help</a>")->priority(7);
    }

}

$ins = new installation;
if (isset($_GET['install'])) {
    if (!$ins->create_structure() || !$ins->create_default_data()) {
        perror("Failed to create tables!");
        ref(url::itself()->url_nonqry(array('notinstalled' => 1)))->redirect();
    }

    if (!empty($_POST['sitename'])) {
        $ins->settable('abs_settings');
        if ($ins->where(array('key' => 'site_name'))->getcount() > 0)
            $ins->update(array('val' => $_POST['sitename']))->where(array('key' => 'site_name'))->exec();
        else
            $ins->insert(array('cat' => 'main', 'key' => 'site_name', 'val' => $_POST['sitename']))->exec();
    }


    $ins->settable('abs_members');
    if (!$ins->insert(array(
                'name' => $_POST['adminuser'],
                'username' => $_POST['adminuser'],
                'email' => $_POST['adminemail'],
                'password' => !empty($_POST['adminpass']) ? md5($_POST['adminpass']) : null,
                'group' => 1,
                'regdate' => date('Y-m-d'),
                'status' => 1
            ))->exec()) {
        perror("Failed to create administrator account!");
    }
    if (!$ins->create_iprange_data()) {

        perror("Failed to insert GEO data!");
    }
    if (isset($_POST['sampledata']) && convert::to_bool($_POST['sampledata']))
        $ins->create_sample_data();

    if (isAlert(null, ALERT_TYPE_ERROR))
        ref(url::itself()->url_nonqry(array('notinstalled' => 1)))->redirect();
    else
        ref(url::itself()->url_nonqry(array('completed' => 1)))->redirect();
}

function showmessage() {
    echo '<div class="alert">';
    echo alert()->options(array(ALERT_OP_HTMLTAG => true)) . "\n";
    echo '</div>';
}

include 'header.php';
?> 

<?php if (isset($_GET['completed'])) : CleanUp(); ?>
    <h4 style="color: #009900">Installation was completed..</h4> 
    <p>
        <a  style="color: #003366" href="<?= ROOT_URL ?>/ab_toolkits" target="_blank">ArcadeBooster Toolkits</a>
        <br/> 
        <a  style="color: #003366" href="<?= ROOT_URL ?>/admin" target="_blank">Admin Dashboard</a>
        <br/> 
        <a  style="color: #003366" href="<?= ROOT_URL ?>"  target="_blank">Website HomePage</a>
    </p> 

    <br>
    <div class="hint" style="font-size: 11px;padding: 7px;color: #F30;">
        Delete <b>install</b> folder for security reasons!
    </div>
    <?php
elseif (isset($_GET['notinstalled'])) :
    $ins->installManually()
    ?> 
    <p><?= showmessage() ?></p>    
<?php else : ?>
    <p>
    <form id='installform' method="post" action="<?= url::itself()->url_nonqry() ?>?install">
        <table>
            <tr>
                <td><label>Site Name :</label></td>
                <td><input type="text" id="sitename"  name="sitename" size="20"></td> 
            </tr>
            <tr>
                <td><label>Admin Email :</label></td>
                <td><input type="text" id="adminemail"  name="adminemail" size="20"></td> 
            </tr>
            <tr>
                <td><label>Admin Username :</label></td>
                <td><input type="text" id="adminuser"  name="adminuser" size="20"></td> 
            </tr>
            <tr>
                <td><label>Admin Password :</label></td>
                <td><input type="text" id="adminpass"  name="adminpass" size="20"></td> 
            </tr>
            <tr>
                <td><label>Install sample data</label></td>
                <td><input type="checkbox" name="sampledata" value="1" checked="true"></td> 
            </tr>
            <tr>
                <td colspan="2"> 
                    <br>
                    <a class="button-silver" onclick="install();"  ><span>Install</span></a> 
            </tr>

        </table>
    </form>
    </p>  
    <script type="text/javascript">
        function chkf(f) {
            if (document.getElementById(f).value.length > 0)
                return true;
            return false;
        }
        function install() {
            if (!chkf('sitename') || !chkf('adminemail') || !chkf('adminuser') || !chkf('adminpass')) {
                alert("Please fill all field completely!");
                return false;
            }
            this.innerHTML = '<span>Installing...</span>';
            this.onclick = function () {
                return false;
            };
            this.className = 'disabled button-silver';
            document.getElementById('installform').submit();
            return false;
        }
    </script>
<?php endif; ?>

<?php include 'footer.php'; ?>