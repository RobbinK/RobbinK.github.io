<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: import-configuration.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */

include 'init.php';

$type = import_get_session('type');
if ($type != 'manual' && file_exists('profiles/' . $type)) {
    require_once ('profiles/' . $type);
    $paths = @custom_paths();
}

$error = 0;
/* Source connection */
$db = new mydb();
if (!$db->ping()) {
    $error = 1;
    warning("Cannot connect to \"destination database\" server ! <br>
    <div class='hint'>To connect to destination database edit <b>config / db.config.php</b> and enter the valid username and password</div>")->Id('import-configuration');
}

/* other connection */
if (isset($_POST['submit'])) {
    if (empty($_POST['host']) || empty($_POST['db']) || empty($_POST['user'])) {
        $error = 1;
        warning("Fill all fields!")->Id('import-configuration');
    }
    $info = array(
        'host' => $_POST['host'],
        'db' => $_POST['db'],
        'user' => $_POST['user'],
        'pass' => $_POST['pass']
    );
    $db = new db(array_merge($info, array(
                'persist' => false,
                'cachesPath' => ROOT_PATH . '/tmp/cache/mysql',
                'logsPath' => ROOT_PATH . '/tmp/logs/mysql'
    )));
    if (!$db->ping()) {
        $error = 1;
        warning("Cannot connect to the source database. Your connection information is wrong!")->Id('import-configuration');
    }

    if (isset($_POST['basepath']) && (!file_exists($_POST['basepath']) || !is_dir($_POST['basepath']))) {
        $error = 1;
        warning("Base source folder is not exist!")->Id('import-configuration');
    }

    if ($error == 0) {
        import_set_session('source_con', $info);
        if (isset($_POST['file_action']))
            import_set_session('file_action', $_POST['file_action']);
        if (isset($_POST['basepath']))
            import_set_session('base_path', $_POST['basepath']);


        if ($type != 'manual' && !@$paths['customizable']) {
            header('location: import-finish.php');
            exit;
        } elseif (isset($_POST['basepath'])) {
            header('location: import-paths.php');
            exit;
        } else {
            header('location: import-settables.php');
            exit;
        }
    }
}



include_once 'header.php';
?>

<div class="heading breadCrumb">
    <?php
    include "import-breadcrump.php"
    ?> 
</ul>
</div>

<div>

    <fieldset>
        <?= alert('import-configuration') ?>
        <form action="<?= url::itself()->url_nonqry() ?>" method="post"> 
            <table>
                <tr>
                    <td style="text-align: right"> Script source path </td>
                    <td><input type="text" name="basepath"  id="basepath" value="<?= dpost('basepath', rightchar('/', HOST_PATH)) ?>" class="input-xxlarge" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <div>
                            <input type="radio" name="file_action" id="file_action_copy" value="copy" <?= dpost('file_action') == 'copy' || dpost('file_action') == '' ? 'checked="checked"' : null ?>/>
                            Copy images and game files automatically.  <br>
                            <input type="radio" name="file_action" id="file_action_notcopy" value="notcopy" <?= dpost('file_action') == 'notcopy' ? 'checked="checked"' : null ?>/>
                            I'll copy all files manually.
                        </div>
                    </td>
                </tr>
            </table>

            <br> 
            <br> 
            Set connection information to connect to source database:<br>
            <table style="padding:16px 0 19px 0"> 
                <tr>
                    <td style="text-align: right">MySQL database Host </td><td><input type="text"  name="host" value="localhost" /></td>
                </tr>
                <tr>
                    <td style="text-align: right">MySQL database name </td><td><input type="text"  name="db" value="<?= dpost('db') ?>"/></td> 
                </tr> 
                <tr>
                    <td style="text-align: right">MySQL database Username </td><td><input type="text"  name="user" value="<?= dpost('user') ?>"/></td>
                </tr>
                <tr>
                    <td style="text-align: right">MySQL database Password </td><td><input type="text"  name="pass" value="<?= dpost('pass') ?>"/></td>
                </tr>
                <tr>
                    <td colspan="2">    <input type="submit" name="submit" value="Next"/></td> 
                </tr> 
            </table>

        </form>
    </fieldset>
</div>    
<script type="text/javascript">
    $(function() {
        $('#file_action_copy').change(function() {
            if ($(this).is(':checked'))
                $('#basepath').removeAttr('disabled');
        });
        $('#file_action_notcopy').change(function() {
            if ($(this).is(':checked'))
                $('#basepath').attr('disabled', 'disabled');
        });
        $('#file_action_notcopy').trigger('change');
    });
</script>
<?php
include_once 'footer.php';
?>