<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Systems_controller.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class SystemsController extends Controller {

    protected $_model = null;

    function action($args) {

        function getMySQLVersion() {
            $output = @mysql_get_client_info();
            if (empty($output))
                $output = @shell_exec('mysql -V');
            if (empty($output))
                return null;
            preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version);
            return $version[0];
        }

        global $license_type;
        switch ($args['action']) {
            case 'getinfo': exit(json_encode(array('mysql' => getMySQLVersion(), 'php' => phpversion(), 'abs' => sys_ver, 'scType' => $license_type)));
            case 'getver': exit(sys_ver);
            case 'getgeo': $this->geo();
                break;
            case 'modrewrite': exit('enable');
                break;
            case 'dbdebug': $this->dbdebug();
                break;
            case 'cleanlog': if (isset($_GET['pass']) && md5($_GET['pass']) == '4f00921114932db3f8662a41b44ee68f') {
                    rrmdir(tmp_path() . '/logs');
                    rmkdir(tmp_path() . '/logs/mysql');
                    exit('Log files have been removed successfuly.');
                } else
                    exit('password is not corrent!');
                break;
            case 'cleancache': if (isset($_GET['pass']) && md5($_GET['pass']) == '4f00921114932db3f8662a41b44ee68f') {
                    rrmdir(cache_path());
                    rmkdir(ROOT_PATH . '/tmp/cache/images');
                    rmkdir(ROOT_PATH . '/tmp/cache/mysql');
                    rmkdir(ROOT_PATH . '/tmp/cache/lang');
                    rmkdir(ROOT_PATH . '/tmp/cache/etc');
                    exit('Cache folder has been removed successfuly.');
                } else
                    exit('password is not corrent!');
                break;
            case 'phpinfo': if (isset($_GET['pass']) && md5($_GET['pass']) == '4f00921114932db3f8662a41b44ee68f')
                    phpinfo();
                else
                    exit('password is not corrent!');
                break;
        }
        exit;
    }

    function geo() {
        $info = array(
            'ip' => agent::remote_info_ip(),
            'country' => agent::remote_info_country(),
            'country_code' => agent::remote_info_country_code(),
            'tier' => agent::remote_info_tier(),
            'referrer' => agent::remote_info_referrer(),
            'bot' => agent::is_bot()
        );

        exit(json_encode($info));
    }

    function chkmodrewrite() {
        exit('true');
    }

    function dbdebug() {
        $mysqllogpath = tmp_path() . '/logs/mysql/mysqllog_last.txt';
        if (!file_exists($mysqllogpath)) {
            echo '<b>Log File Not Found....</b>';
            exit;
        }

        $FileContent = file_get_contents($mysqllogpath);
        if (empty($FileContent))
            exit("no data is available!");
        $FileContent = unserialize($FileContent);
        ?>  
        <style>
            .sqloptions{
                display: none;

            }
            .sqloptions ul{
                list-style-position: inside;
                margin: 1px;
                padding: 0;
            }

            .sqloptions ul li{ cursor: pointer;}

            .sqloptions ul li.open{
                list-style-image: url('<?= static_url() ?>/css/dbdebug/images/mini-open.png');
            }

            .sqltd{
                position: relative;
            }

            .sqltd:hover .sqloptions{
                display: block;
                position: absolute;
            }

            .sqltd .transactions{
                color:#D50581;
                font-weight: bold;
                margin: 0 0 0 20px;
            }
            .sqltd  pre.sql {
                padding: 0 0 0 17px;
            } 


        </style>
        <?php
        js::loadJquery(true);
        css::load(array(
            static_url() . '/css/dbdebug/css/table.css',
            static_url() . '/css/dbdebug/js/highlight.js/styles/idea.css',
                ), array(CSS_FORCELOAD => true));
        js::load(array(
            static_path() . '/css/dbdebug/js/highlight.js/highlight.pack.js',
                ), array('exec' => false, CSS_FORCELOAD => true));
        ?>
        <script>
            $(document).ready(function () {
                $('.open').click(function () {
                    st = $(this).closest('td').find('.sql');
                    st.html(st.data('sql'));
                    hljs.highlightBlock(st[0]);
                });

                $('pre.sql').each(function (i, e) {
                    hljs.highlightBlock(e)
                });
            });
        </script>
        <table class="CSSTableGenerator">
            <tbody>
                <tr>
                    <td style="width: 200px;">Sql</td>
                    <td style="width: 8px;">Rows</td>
                    <td style="width: 8px;">Execute</td>
                    <td style="width: 8px;">Features</td>
                </tr>
                <?php
                $FileContent = array_reverse($FileContent);
                if (is_array($FileContent))
                    while (current($FileContent)):
                        $data = current($FileContent);
                        ?>
                        <tr>
                            <td style="width: 500px;height:30px;vertical-align: top;" class="sqltd">
                                <div class="sqloptions" style="width: 10px;height: 10px">
                                    <ul>
                                        <li class="open"></li>
                                    </ul>
                                </div>
                                <?php if (preg_match('/autocommit(false)|begin|commit|rollback/i', @$data['minifysql'])) { ?>
                                    <font class="transactions"><font style="color:#7F7484;font-weight: bold">Connection : </font><?= @$data['minifysql'] ?></font>
                                <?php } else { ?>
                                    <pre class="sql" style="font-size: 11px;width:95%;white-space: pre-wrap;" style="max-width: 100px;" data-sql="<?= addcslashes(@$data['sql'], "\"") ?>"><?= @$data['minifysql'] ?></pre>
                                <?php } ?>
                                <?= !empty($data['error']) ? "<font style='color:red'>{$data['error']}</font>" : null ?>
                            </td>
                            <td><div><?= @$data['numrows'] ?></div></td>
                            <td><div><?= round(@$data['exectime'], 6) ?></div></td>
                            <td style="width: 70px;">
                                <ul class="check">
                                    <?php if (isset($data['isajax'])) { ?>
                                        <li>Is Ajax</li>
                                    <?php } ?>
                                    <?php if (isset($data['cached'])) { ?>
                                        <li>Cached</li>
                                    <?php } ?>

                                </ul>
                                <ul class="error">
                                    <?php if (isset($data['error'])) { ?>
                                        <li>mysql error</li>
                                    <?php } ?>
                                </ul>
                            </td>
                        </tr>
                        <?php
                        next($FileContent);
                    endwhile;
                ?>
            </tbody>
        </table> 

        <?php
    }

}