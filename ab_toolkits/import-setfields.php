<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: import-setfields.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */

include 'init.php';

$dbinfo = import_get_session('source_con');
$Tsource = array();
if (is_array($dbinfo)) {
    $db = new db(array_merge($dbinfo, array(
                'persist' => false,
                'cachesPath' => ROOT_PATH . '/tmp/cache/mysql',
                'logsPath' => ROOT_PATH . '/tmp/logs/mysql'
    )));
    $Tdestinations = array(
        'abs_games' => array(
            'gid',
            'game_name',
            'seo_title',
            'game_categories',
            'game_description',
            'game_instruction',
            'game_keywords',
            'game_tags',
            'game_img',
            'featured_img',
            'game_file',
            'game_width',
            'game_height',
            'game_adddate',
            'game_rating',
            'game_votes',
            'game_is_featured',
            'game_is_active'
        ),
        'abs_categories' => array(
            'cid',
            'title',
            'seo_title',
            'meta_description',
            'meta_keywords',
        ),
        'abs_members' => array(
            'id',
            'username',
            'password',
            'email',
            'name',
            'avatar',
            'status'
        ),
        'abs_links' => array(
            'id',
            'partner_title',
            'partner_url',
            'status'
        ),
        'abs_games_tags' => array(
            'id',
            'name',
            'seo_name',
        )
    );
    if ($db->ping()) {
        $tables = import_get_session('tables');
        foreach ($tables as $k=>$v) {
            if (!empty($v)){
                $Tsource[$k] = getFields($db, $v);
            }
        }
    }
}
if (isset($_POST['submit'])) {
    $tables = import_set_session('fields', $_POST['fields']);
    header('location: import-finish.php');
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
    .box1{
        width: 400px;
        height: 400px;
        overflow-y: scroll;
        overflow-x: hidden;
        border: solid 1px #E2D8D8;
        border-radius: 6px;
        background-color: #FFF;
        margin-bottom: 13px;
    }
    .box1 table{
        margin-bottom: 20px;
    }
    .tablename{
        border-radius: 10px 10px 0 0;
        width: 361px;
        display: block;
        height: 20px; 
        background-color: #474D55;
        color: #FFF;
        padding: 6px 10px;
        font: 15px arial bold;
    }
</style>
<div class="heading breadCrumb">
    <?php
    include "import-breadcrump.php"
    ?> 
</div>

<div>

    <fieldset>
        <?= alert('import-setfields') ?>
        Match each field in destination table with the same field in source table:<br>

        <form action="<?= url::itself()->url_nonqry() ?>" method="post">

            <div class="box1">
                <?php
                foreach ($Tsource as $table => $fields):
                    ?>
                    <span class="tablename">Table name : <?= $table ?></span>
                    <table>
                        <tr>
                            <th>Destination Fields</th>
                            <th style="width:20px"></th>
                            <th>Source Fields</th>
                        </tr> 
                        <?php
                        if (isset($Tdestinations[$table]))
                            foreach ($Tdestinations[$table] as $Tfield) :
                                ?>
                                <tr>
                                    <td style="text-align: right">
                                        <select disabled="disabled" class="input-medium">
                                            <option><?= $Tfield ?></option>
                                        </select>
                                    </td>
                                    <td>
                                        <span class="arrow"><=</span>
                                    </td>
                                    <td> 
                                        <select class="input-medium" name="fields[<?= $table ?>][<?= $Tfield ?>]">
                                            <option></option>
                                            <?php
                                            if ($fields)
                                                foreach ($fields as $field) {
                                                    echo "<option>{$field}</option>";
                                                }
                                            ?>
                                        </select>
                                    </td>
                                </tr>                 
                            <?php endforeach; ?>
                    </table>  
                <?php endforeach; ?>
            </div>
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