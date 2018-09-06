<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: import-type.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */

include 'init.php';
if (isset($_SESSION['import']))
    unset($_SESSION['import']);

if (isset($_POST['submit'])) {
    if ($_POST['mt'] == 1) {
        $type = 'manual';
    } else {
        $type = $_POST['profile'];
    }
    import_set_session('type', $type);
    header('location: import-configuration.php');
    exit;
}


include_once 'header.php';
?>

<div class="heading breadCrumb">
    <?php
    include "import-breadcrump.php"
    ?> 
</div>

<div>
    <fieldset>
        <?php
        if (!version_compare(sys_ver, '1.4.4', '>')):
            echo perror("Your ArcadeBooster version should be more than 1.4.4!")->alert();
        else :
            ?>

            Select importing method: <br>
            <form action="<?= url::itself()->url_nonqry() ?>" method="post">
                <input type="radio"  name="mt"  id="mt1" value="1" /> <label for="mt1">Custom Scripts (Manual)</label><br>
                <input type="radio"  name="mt"  id="mt2" value="2" checked="checked"  /> <label for="mt2">Arcade Scripts</label> 
                <p> 
                    <select name="profile" id="profile" style="display: none;width: 150px;">
                        <option  value="avscript.php">AV Script</option>
                        <option  value="myarcadeplugin.php">MyArcadePlugin</option>
                        <option  value="onarcade.php">OnArcade</option>
                        <option  value="yas.php">YourArcadeScript</option>
                        <option  value="ats.php">ATS</option>
                        <option  value="fas.php">FreeArcadeScript</option>
                    </select>
                </p>  
                <input type="submit" name="submit" value="Next"/>
            </form>
        <?php endif; ?>
    </fieldset>
</div>    
<script type="text/javascript">
    $(function () {
        $('#mt2').change(function () {
            if ($(this).is(':checked'))
                $('#profile').fadeIn(300);
        });
        $('#mt1').change(function () {
            $('#profile').fadeOut(300);
        });
        $('#profile').fadeIn(300);
    });
</script>
<?php
include_once 'footer.php';
?>