<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: import-settables.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */

include 'init.php';

$dbinfo = import_get_session('source_con');
if (is_array($dbinfo)) {
    $db = new db(array_merge($dbinfo, array(
                'persist' => false,
                'cachesPath' => ROOT_PATH . '/tmp/cache/mysql',
                'logsPath' => ROOT_PATH . '/tmp/logs/mysql'
    )));
    if ($db->ping()) {
        $tables = getTables($db);
    }
}
if (isset($_POST['submit'])) {
    $tables = array(
        'abs_games' => $_POST['m_abs_games'],
        'abs_categories' => $_POST['m_abs_categories'],
        'abs_members' => $_POST['m_abs_members'],
        'abs_links' => $_POST['m_abs_links'],
        'abs_games_tags' => $_POST['m_abs_games_tags'],
    );
    import_set_session('tables', $tables);
    header('location: import-setfields.php');
    exit;
}
include_once 'header.php';
?>
<style>
    th{
        background-color: #E7EEFF;
        width:180px;
    }
    th{
        text-align: center;
    }
    td{
        background-color: #F5F5F5;
    }
    .arrow{
        font: 10px tahoma bold;
        color: #F60;
    }
</style>
<div class="heading breadCrumb">
    <?php
    include "import-breadcrump.php"
    ?> 
</div>

<div>

    <fieldset>
        <?= alert('import-settables') ?>
        Match each table in destination database with the same table in source database: <br>
        <form action="<?= url::itself()->url_nonqry() ?>" method="post">
            <table style="padding:16px 0 19px 0">
                <tr>
                    <th>Destination DB</th>
                    <th style="width:20px"></th>
                    <th>Source DB</th>
                </tr>
                <!---Games--->
                <tr>
                    <td style="text-align: right">
                        <select disabled="disabled" class="input-medium">
                            <option>abs_games</option>
                        </select>
                    </td>
                    <td>
                        <span class="arrow"><=</span>
                    </td>
                    <td> 
                        <select class="input-medium" name="m_abs_games">
                            <option></option>
                            <?php
                            if ($tables)
                                foreach ($tables as $table) {
                                    echo "<option>{$table}</option>";
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <!---Categories--->
                <tr>
                    <td style="text-align: right">
                        <select disabled="disabled" class="input-medium">
                            <option>abs_categories</option>
                        </select>
                    </td>
                    <td>
                        <span class="arrow"><=</span>
                    </td>
                    <td>
                        <select class="input-medium" name="m_abs_categories">
                            <option></option>
                            <?php
                            if ($tables)
                                foreach ($tables as $table) {
                                    echo "<option>{$table}</option>";
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <!---Members--->
                <tr>
                    <td style="text-align: right">
                        <select disabled="disabled" class="input-medium">
                            <option>abs_members</option>
                        </select>
                    </td>
                    <td>
                        <span class="arrow"><=</span>
                    </td>
                    <td>
                        <select class="input-medium" name="m_abs_members">
                            <option></option>
                            <?php
                            if ($tables)
                                foreach ($tables as $table) {
                                    echo "<option>{$table}</option>";
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <!---Links--->
                <tr>
                    <td style="text-align: right">
                        <select disabled="disabled" class="input-medium">
                            <option>abs_links</option>
                        </select>
                    </td>
                    <td>
                        <span class="arrow"><=</span>
                    </td>
                    <td>
                        <select class="input-medium" name="m_abs_links">
                            <option></option>
                            <?php
                            if ($tables)
                                foreach ($tables as $table) {
                                    echo "<option>{$table}</option>";
                                }
                            ?>
                        </select>
                    </td>
                </tr>                       
                <!---Tags--->
                <tr>
                    <td style="text-align: right">
                        <select disabled="disabled" class="input-medium">
                            <option>abs_games_tags</option>
                        </select>
                    </td>
                    <td>
                        <span class="arrow"><=</span>
                    </td>
                    <td>
                        <select class="input-medium" name="m_abs_games_tags">
                            <option></option>
                            <?php
                            if ($tables)
                                foreach ($tables as $table) {
                                    echo "<option>{$table}</option>";
                                }
                            ?>
                        </select>
                    </td>
                </tr>                       
            </table>  
            <input type="submit" name="submit" value="Next"/> 
        </form>
    </fieldset>
</div>    
<script type="text/javascript">
    $(function() {
    });
</script>
<?php
include_once 'footer.php';
?>