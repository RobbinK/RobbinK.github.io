<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: import-paths.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */

include 'init.php';

$base_path = import_get_session('base_path');
$base_path = rightchar('/', $base_path);

$images = $base_path;
$files = $base_path;
$avatars = $base_path;

$type = import_get_session('type');
if ($type != 'manual' && file_exists('profiles/' . $type)) {
    require_once ('profiles/' . $type);
    $paths = @custom_paths();
    $images = @$paths['thumbs'];
    $files = @$paths['files'];
    $avatars = @$paths['avatars'];
}

if (isset($_POST['submit'])) {
    import_set_session('paths', array(
        'files' => $_POST['file_path'],
        'thumbs' => $_POST['thumbs_path'],
        'avatars' => @$_POST['avatar_path'],
    ));
    if ($type == 'manual')
        header('location: import-settables.php');
    else
        header('location: import-finish.php');
    exit;
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
        <?= alert('import-paths') ?>
        Set directory of of source files:<br>
        <form action="<?= url::itself()->url_nonqry() ?>" method="post">
            <table style="padding:16px 0 19px 0">
                <tr>
                    <td style="text-align: right"> Game Files </td><td><input type="text"  name="file_path" value="<?= dpost('file_path', $files); ?>" class="input-xxlarge" /></td>
                </tr>
                <tr>
                    <td style="text-align: right"> Game Images </td><td><input type="text"  name="thumbs_path" value="<?= dpost('thumbs_path', $images); ?>" class="input-xxlarge" //></td>
                </tr>
                <?php if (!empty($avatars)) : ?>
                    <tr>
                        <td style="text-align: right"> Users Avatars </td><td><input type="text"  name="avatar_path" value="<?= dpost('avatar_path', $avatars); ?>" class="input-xxlarge" //></td>
                    </tr> 
                <?php endif; ?>
                <tr>
                    <td colspan="2">   
                        <input type="submit" name="submit" value="Next"/>
                    </td> 
                </tr> 

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