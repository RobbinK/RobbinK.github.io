<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: seosetting.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */

include 'init.php';
$customfile = ROOT_PATH . '/config/routes.custom.config.php';

$routes = array(
    'route_homepage' => array('/', 'Homepage'),
    'route_allgames_cat' => array('/games/{:category_seo}.html', 'Category page(1)'),
    'route_allgames_cat_page' => array('/games/{:category_seo}/page-{:page}.html', 'Category pages'),
    'route_pregame' => array('/view/{:category_seo}/{:game_seo}.html', 'Pre-page'),
    'route_playgame' => array('/play/{:category_seo}/{:game_seo}.html', 'Play-page'),
    'route_newgames' => array('/newgames.html', 'New games page(1)'),
    'route_newgames_page' => array('/newgames/page-{:page}.html', 'New games pages'),
    'route_populargames' => array('/populargames.html', 'Popular games page(1)'),
    'route_populargames_page' => array('/populargames/page-{:page}.html', 'Popular games pages'),
    'route_toprategames' => array('/toprategames.html', 'Top-rated games page(1)'),
    'route_toprategames_page' => array('/toprategames/page-{:page}.html', 'Top-rated games pages'),
    'route_tag' => array('/tags/{:tag_seo}.html', 'Tag page(1)'),
    'route_tag_page' => array('/tags/{:tag_seo}/page-{:page}.html', 'Tag pages'),
    'route_page' => array('/pages/{:page_seo}.html', 'Static Page'),
);
$defaultRoutes = $routes;

if (file_exists($customfile)) {
    include_once $customfile;
    foreach ($routes as $k => &$v)
        if (defined($k))
            $v[0] = eval("return {$k};");
    unset($v);
}

if (isset($_POST['submit'])) {
    if (file_exists($customfile))
        $file_content = file_get_contents($customfile);
    else
        $file_content = "<?php\n";

    foreach ($routes as $k => $v) {
        if (isset($_POST[$k])) {
            $pattern = "/define\s*\(['\"]{$k}['\"]\s*,\s*['\"]([^'\"]*)['\"]\s*\)\;/";
            if (preg_match($pattern, $file_content))
                $file_content = preg_replace($pattern, "define('{$k}', '$_POST[$k]');", $file_content);
            else
                $file_content.="\ndefine('{$k}', '{$_POST[$k]}');";
        }
    }

    $handle = fopen($customfile, 'w');
    if (fwrite($handle, $file_content))
        psuccess('The changes were done successfuly.')->Id('seosetting');
    fclose($handle);

    ref(url::itself()->url_nonqry())->redirect();
}
elseif (isset($_POST['reset'])) {
    if (file_exists($customfile))
        $file_content = file_get_contents($customfile);
    else
        $file_content = "<?php\n";

    foreach ($defaultRoutes as $k => $v) {
        $pattern = "/define\s*\(['\"]{$k}['\"]\s*,\s*['\"]([^'\"]*)['\"]\s*\)\;/";
        if (preg_match($pattern, $file_content))
            $file_content = preg_replace($pattern, "define('{$k}', '{$v[0]}');", $file_content);
        else
            $file_content.="\ndefine('{$k}', '$v[0]');";
    }

    $handle = fopen($customfile, 'w');
    if (fwrite($handle, $file_content))
        psuccess('Default settings were restored successfuly.')->Id('seosetting');
    fclose($handle);

    ref(url::itself()->url_nonqry())->redirect();
}

$filePermission = null;
if (!isLocalServer() && file_exists(ROOT_PATH . '/config/routes.custom.config.php') && path::filePermission(ROOT_PATH . '/config/routes.custom.config.php') < '0777')
    $filePermission = "/config/routes.custom.config.php file doesn't have enough permission";
if (!isLocalServer() && !file_exists(ROOT_PATH . '/config/routes.custom.config.php') && path::filePermission(ROOT_PATH . '/config') < '0777')
    $filePermission = "/config folder doesn't have enough permission";


include_once 'header.php';
?>

<style> 
    fieldset{
        height: 940px;
    }
    input[type=text]{
        padding: 3px !important;
        margin: 3px !important; 
        width:400px;
    }
    ul {
        margin: 0px;
        margin-top: 0px;
        margin-right: 0px;
        margin-bottom: 0px;
        margin-left: 0px;
        padding: 0;
    }
    ul li {
        margin: 0px;
        padding: 0px;
        list-style: none;
    } 
    .box1{
        width: 100%;
        height: 480px;
        /*overflow-y: scroll;*/
        overflow-x: hidden;
        border: solid 1px #E2D8D8;
        border-radius: 6px;
        background-color: #FCF7FF;
        margin-bottom: 13px;
        padding: 10px;
    } 
    #sidebar{
        height: 1000px !important;
    }
</style>

<div>
    <fieldset>
        <?= alert('seosetting') ?>
        Please pay attention to the rules below in order not to have any issue in your customized SEO URLs <br>
        <ul>
            <li> 1- Avoid using the same URLs </li>
            <li> 2- Don't use space or unusual SEO characters in URLs </li>
            <li> 3- Don't change the name of variables started with '{:' and ended with '}' i.e. : {:category} </li>
            <li> 4- The variables you can use in your custom urls are listed below: </li>
            <li> To have page number in your urls use {:page}  </li>
            <li> To have category name in your urls use {:category_seo}  </li>
            <li> To have category id in your urls use {:category_id} </li> 
            <li> To have game name in your urls use {:game_seo} </li>
            <li> To have game id in your urls use {:game_id} </li> 
            <li> To have tag name in your urls use {:tag_seo} </li>
            <li> To have tag id in your urls use {:tag_id} </li> 
            <li> To have page name in your urls use {:page_seo} </li>
            <li> To have page id in your urls use {:page_id} </li>

        </ul>
        <br>
        <?php
        if (!version_compare(sys_ver, '1.4.4', '>')):
            echo perror("Your ArcadeBooster version should be more than 1.4.4!")->alert();
        elseif (!empty($filePermission)):
            echo perror($filePermission)->alert();
        else :
            ?>
            <form method="post">
                <div class="box1">
                    <table>
                        <?php foreach ($routes as $k => $v): ?>
                            <tr><td> <?= @$v[1] ?> :</td><td> <input type="text" name="<?= $k ?>" value="<?= $v[0] ?>"/></td></tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <input type="submit" name="submit" value="Submit" /> &nbsp; &nbsp; &nbsp;
                <input type="submit" name="reset" value="Restore to Default" />
            </form>
        <?php endif; ?>
    </fieldset>
</div>
<?php
include_once 'footer.php';
?>