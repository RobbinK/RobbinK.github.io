<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: pengu_comment.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:58
##########################################################
 */

define('PENGU_COMMENT_POST_EDITID', 11);
define('PENGU_COMMENT_POST_REPLYTO', 22);
define('PENGU_COMMENT_SESSION_USERID', 2);
define('PENGU_COMMENT_POST_NAME', 3);
define('PENGU_COMMENT_POST_EMAIL', 4);
define('PENGU_COMMENT_POST_WEBSITE', 5);
define('PENGU_COMMENT_POST_COUNTRY', 6);
define('PENGU_COMMENT_POST_COMMENT', 7);
define('PENGU_COMMENT_POST_TIME', 8);

class pengu_comment {

    public $user_authority = true;
    public $approve_status_value = 1;
    public $auto_approve = true;
    public $show_editbutton = true;
    public $get_website = true;
    public $items_per_page = 10;
    public $reply_level = 1;
    public $avatar = '50x50'; //default avatar size is 50x50
    private $user_id;
    private $user_avatar;
    public $avatars_folder;
    private $_comment_group;
    private static $_model;
    private $bbcode_parser;
    private $cookie_expire = 2592000;
    private $button_edit = true;
    private $button_delete = true;
    private $default_post = array(
        PENGU_COMMENT_POST_NAME => 'name',
        PENGU_COMMENT_POST_EMAIL => 'email',
        PENGU_COMMENT_POST_WEBSITE => 'website',
        PENGU_COMMENT_POST_COUNTRY => 'country',
        PENGU_COMMENT_POST_COMMENT => 'comment',
    );

    function __construct($comment_group = null) {
        if ($comment_group)
            $this->_comment_group = $comment_group;
        if (!self::$_model)
            self::$_model = new Model();
        $this->set_data_table();
        $this->jbbcode_init();

        $this->avatars_folder = content_url() . '/upload';
        event::register_onLoadView(array('pengu_comment', 'loadcssjs'), 9);
    }

    function set_data_table($data_table = 'comment') {
        self::$_model->settable($data_table);
    }

    private function jbbcode_init() {
        require_once 'jbbcode-1.2.0/Parser.php';
        require_once 'BbeditorCodeDefinitionSet.php';

        $this->bbcode_parser = new jbbcode_parser();
        $this->bbcode_parser->addCodeDefinitionSet(new DefaultCodeDefinitionSet());
        $this->bbcode_parser->addCodeDefinitionSet(new BbeditorCodeDefinitionSet());
    }

    function set_authority_model($name) {
        if (!empty($name)) {
            $obj = new $name;
            $this->user_id = call_user_func(array($obj, 'getUserFoundId'));
            $this->user_avatar = eval("return {$name}::data('avatar');");
        }
    }

    function loadcssjs(&$ViewContent) {
        //==load csses 
        $cssFils = array();
        $css_common = null;
        if (file_exists(template_path() . '/css/comment.css'))
            $css_common = template_url() . '/css/comment.css';
//        else
//            $css_common = plugin_url() . '/pengu_comment/css/common.css';

        if ($css_common && !css::loaded($css_common))
            $cssFils[] = $css_common;

        if (!css::loadedAlert())
            css::loadAlert();

        $css_bbeditor = plugin_url() . '/pengu_comment/bbeditor/minified/themes/default.min.css';
        if (!css::loaded($css_bbeditor))
            $cssFils[] = $css_bbeditor;

        if (!empty($cssFils))
            css::load($cssFils);

        //==load jses
        $jsFils = array();
        $js_smoothscroll = plugin_path() . '/pengu_comment/js/jquery.smooth-scroll.min.js';
        if (!js::loaded($js_smoothscroll))
            $jsFils[] = $js_smoothscroll;

        $js_comment = plugin_path() . '/pengu_comment/js/comment.js';
        if (!js::loaded($js_comment))
            $jsFils[] = $js_comment;

        $js_bbeditor = plugin_path() . '/pengu_comment/bbeditor/minified/jquery.sceditor.bbcode.min.js';
        if (!js::loaded($js_bbeditor))
            $jsFils[] = $js_bbeditor;

        $js_url = static_path() . '/js/ba_urltools/jquery.ba-bbq.min.js';
        if (!js::loaded($js_url))
            $jsFils[] = $js_url;

        $js_base64 = static_path() . '/js/base64.lib.js';
        if (!js::loaded($js_base64))
            $jsFils[] = $js_base64;

        $jsContent = null;
        ob_start();
        if (!js::loadedJquery())
            js::loadJquery(true);


        if (!js::loadedJquery_migrate())
            js::loadjquery_migrate(true);

        if (!empty($jsFils))
            js::load($jsFils, array(JS_FORCELOAD => true, JS_MINIFY => false));
        $jsContent = ob_get_clean();
        if (preg_match("/<\/body>/i", $ViewContent)) {
            $ViewContent = preg_replace("/<\/body>/i", "{$jsContent}</body>", $ViewContent);
        }
    }

    function set_fiels($array_fields) {
        if (is_array($array_fields)) {
            foreach ($this->default_post as $k => $v)
                if (isset($array_fields[$k]))
                    $this->default_post[$k] = $array_fields[$k];
        }
    }

    private function __($key) {
        return isset($this->default_post[$key]) ? $this->default_post[$key] : null;
    }

    function set_comment_group($type) {
        $this->_comment_group = $type;
    }

    function postback() {
        global $dback;
        if (isset($_POST)) {
            $dback = $_POST;
        }
    }

    function save($_data, $user_id = null, $user_avatar = null) {
        $data = array();
        $error = false;
        if (!empty($user_id)) {
            $this->user_id = $user_id;
        }
        if (!empty($user_avatar)) {
            $this->user_avatar = $user_avatar;
        }

        if ($this->user_authority == true && empty($this->user_id)) {
            perror("You have to login your account to post your comment !")->Id($this->_comment_group);
            $error = 1;
        }

        //==captcha
        if (!isset($_POST['captcha']) || $_POST['captcha'] != $_SESSION['captcha']) {
            pwarning('The Captcha isn\'t correnct !')->Id($this->_comment_group);
            $error = 1;
        }

        if (isset($_POST['comment_form_editid'])) {
            $userid = self::$_model->select()->where(array('id' => $_POST['comment_form_editid']))->exec()->current()->user_id;
            if (!$this->show_editbutton || $userid != $this->user_id) {
                pwarning('You don\'t have permission  to edit this post !')->Id($this->_comment_group);
                $error = 1;
            }
        }

        if ($error) {
            $this->postback();
            return false;
        }

        // change some smiley code 
        $_data['comment'] = strtr($_data['comment'], array('<3' => '&#3&'));
        $_data['comment'] = nl2br(strip_tags($_data['comment']));
        $_data['comment'] = strtr($_data['comment'], array('&#3&' => '<3'));

        foreach ($_data as $k => $v) {
            if (in_array($k, $this->default_post) && $k != 'comment_form_editid' && $k != 'comment_form_replyto')
                $data[$k] = $v;
            elseif ($k == 'comment_form_editid')
                $edit = array('id' => $v);
            elseif ($k == 'comment_form_replyto')
                $data['parent_id'] = $v;
        }
        $this->savecookie($data);


        $result = null;
        if (isset($edit)) {
            $data = array_merge($data, array('time' => time()));
            $result = self::$_model->update($data)->where($edit)->exec();
            if (!$result)
                pwarning('Your comment didn\'t save.')->Id($this->_comment_group);
            else
                psuccess('Your comment saved.')->Id($this->_comment_group);
        } else {
            $data = array_merge($data, array(
                'group' => $this->_comment_group,
                'ip' => @agent::remote_info_ip(),
                'country' => @agent::remote_info_country(),
                'user_id' => $this->user_id,
                'time' => time(),
            ));

            if ($this->avatar)
                $data['user_avatar'] = $this->user_avatar;

            if ($this->auto_approve)
                $data['status'] = $this->approve_status_value;
            else
                $data['status'] = 0;

            $result = self::$_model->insert($data)->exec();
            if (!$result)
                pwarning('Your data not saved.')->Id($this->_comment_group)->priority(1);
            else {
                psuccess('Your comment sent.')->Id($this->_comment_group)->priority(1);
                if (!$this->auto_approve)
                    psuccess("Your comment will be shown after admin approval.")->Id($this->_comment_group)->priority(2);
            }
        }
        if ($result)
            ref(url::itself()->fulluri(array('cedit' => null, 'creplyto' => null)))->redirect();
        else
            ref(url::itself())->redirect();
    }

    private function savecookie($data) {
        $cookie = array(
            $this->__(PENGU_COMMENT_POST_EMAIL),
            $this->__(PENGU_COMMENT_POST_NAME),
            $this->__(PENGU_COMMENT_POST_WEBSITE)
        );
        $domain = (lib::get_domain(HOST_URL) != 'localhost') ? lib::get_domain(HOST_URL) : null;
        foreach ($data as $k => $v)
            if (in_array($k, $cookie))
                setcookie('pengu_comment_' . $k, $v, time() + $this->cookie_expire, '/', $domain);
    }

    private function getcookie($cookie_name) {
        if (isset($_COOKIE['pengu_comment_' . $cookie_name]))
            return $_COOKIE['pengu_comment_' . $cookie_name];
    }

    private function bbcode_decode($inputText) {
        $this->bbcode_parser->parse($inputText);
        return $this->bbcode_parser->getAsHTML();
    }

    private function bbcode_tosmiley($inputText) {
        $path = plugin_url() . '/pengu_comment/bbeditor/emoticons/';
        $tr = array(
            ':alien:' => "<img src=\"{$path}alien.png\">",
            ':angel:' => "<img src=\"{$path}angel.png\">",
            ':angry:' => "<img src=\"{$path}angry.png\">",
            ':blink:' => "<img src=\"{$path}blink.png\">",
            ':blush:' => "<img src=\"{$path}blush.png\">",
            ':cheerful:' => "<img src=\"{$path}cheerful.png\">",
            '8-)' => "<img src=\"{$path}cool.png\">",
            ':\'(' => "<img src=\"{$path}cwy.png\">",
            ':devil:' => "<img src=\"{$path}devil.png\">",
            ':dizzy:' => "<img src=\"{$path}dizzy.png\">",
            ':ermm:' => "<img src=\"{$path}ermm.png\">",
            ':getlost:' => "<img src=\"{$path}getlost.png\">",
            ':D' => "<img src=\"{$path}grin.png\">",
            ':happy:' => "<img src=\"{$path}happy.png\">",
            '<3' => "<img src=\"{$path}heart.png\">",
            ':kissing:' => "<img src=\"{$path}kissing.png\">",
            ':ninja:' => "<img src=\"{$path}ninja.png\">",
            ':pinch:' => "<img src=\"{$path}pinch.png\">",
            ':pouty:' => "<img src=\"{$path}pouty.png\">",
            ':(' => "<img src=\"{$path}sad.png\">",
            ':O' => "<img src=\"{$path}shocked.png\">",
            ':sick:' => "<img src=\"{$path}sick.png\">",
            ':sideways:' => "<img src=\"{$path}sideways.png\">",
            ':silly:' => "<img src=\"{$path}silly.png\">",
            ':sleeping:' => "<img src=\"{$path}sleeping.png\">",
            ':)' => "<img src=\"{$path}smile.png\">",
            ':P' => "<img src=\"{$path}tongue.png\">",
            ':unsure:' => "<img src=\"{$path}unsure.png\">",
            ':woot:' => "<img src=\"{$path}w00t.png\">",
            ':wassat:' => "<img src=\"{$path}wassat.png\">",
            ';)' => "<img src=\"{$path}wink.png\">",
        );
        return strtr($inputText, $tr);
    }

    function showposts() {
        self::$_model->select();
        if (!empty($this->_comment_group))
            self::$_model->where(array('group' => $this->_comment_group, 'status' => $this->approve_status_value));
        else
            self::$_model->where(array('status' => $this->approve_status_value));

        $allrows = self::$_model->exec()->allrows();

        if (count($allrows) == 0)
            return;

        foreach ($allrows as &$row) {
            $row['time_str'] = pengu_date::ago($row['time']);
            $row['comment_bbdecode'] = $this->bbcode_decode($row['comment']);
            $row['comment_bbdecode'] = $this->bbcode_tosmiley($row['comment_bbdecode']);
            $row['comment'] = preg_replace('#\<br\s*\/\>#i', '', $row['comment']);
        }
        $allrows = array_merge(array(array('id' => 0, 'parent_id' => null)), $allrows);
        $tree = new tree;
        $tree->set_parentKey('parent_id');
        $dattree = $tree->mapToTree($allrows, 0);

        if ($this->items_per_page) {
            $pg = new pengu_pagination();
            $pg->current_page = isset($_GET['page']) ? $_GET['page'] : -1 /* -1 is last page */;
            $pg->items_per_page = intval($this->items_per_page);
            $pg->total_rows = count($dattree[0]['children']);
            $limit = $pg->limit(false);
            $dattree[0]['children'] = array_slice($dattree[0]['children'], $limit[0], $limit[1]);
        }




        $avatar = '';
        if ($this->avatar) {
            list($av_w, $av_h) = is_array($this->avatar) ? $this->avatar : explode('x', $this->avatar);
            $whstyle = "width:{$av_w}px;height:{$av_h}px;";
            $default_avatar = plugin_url() . "/pengu_comment/images/user.png";
            $avatar = "       <div style='{$whstyle}' id='cavatar' class='cavatar'>
                 <img src='[user_avatar]' onerror=\"this.src='{$default_avatar}';\" alt='Avatar' style='{$whstyle}' />
                </div>";
        }

        $format = " 
            <div id='cm_[id]' class='cm'>
                <span id='cid' style='display:none'>[id]</span>
                <span id='cemail' style='display:none'>[email]</span>
                <span id='cwebsite' style='display:none'>[website]</span>
                <p id='ccomment'  style='display:none'>[comment]</p>
                
                {$avatar}
                
                <b id='cname'>[name]</b> <br>
                <p>[comment_bbdecode]</p>
                <div class='ctools'> 
                    <div class='cleft'>
                       <span class='time'>[time_str]</span>
                    </div>
                    <div class='cright'>
                       <#reply#>
                       <#edit#>
                    </div>
                </div> 
           </div>
            ";

        $out = "<div class='comment_posts_container'>";
        $out.=self::showblocks($dattree, $tree->children_field, $format);
        if ($this->items_per_page)
            $out.= $pg->render();
        $out.= "</div>";
        echo $out;
    }

    public function showblocks($tree, $children_field, $format, $child = false) {
        static $space;
        $is_child = false;
        if (!$child) {
            $tree = $tree[0];
            $tree = $tree['children'];
            $out = "\n" . "<ul class='comment'>"; //first  Tag 
        } else {
            $is_child = true;
            $out = "\n" . $space . "<ul>"; //children tags 
        }

        $child = null;
        foreach ($tree as $t) {
            //-- Replace key By Format 
            //--  []
            $arr = array_filter($t, create_function('$v', 'return !is_array($v);'));
            $keys = array_keys($arr);
            $vals = array_values($arr);
            array_walk($keys, create_function('&$v,&$k', '$v="[".$v."]";'));
            $arr = array_combine($keys, $vals);
            //--  <##>  
            $arr['<#edit#>'] = null;
            if ($this->user_authority && $this->show_editbutton && !empty($this->user_id) && $arr['[user_id]'] == $this->user_id)
                $arr['<#edit#>'] = "<a href='#' class='edit' >Edit</a>";

            $arr['<#reply#>'] = null;
            if ($this->reply_level) {
                if ($this->reply_level == 1 && !$is_child)
                    $arr['<#reply#>'] = " <a href='#' class='replyLink'>add a reply</a>";
                elseif ($this->reply_level == 2)
                    $arr['<#reply#>'] = " <a href='#' class='replyLink'>add a reply</a>";
            }

            if (empty($arr['[user_avatar]']))
                $arr['[user_avatar]'] = 'empty';
            else
                $arr['[user_avatar]'] = path::rightSlashes($this->avatars_folder) . $arr['[user_avatar]'];

            $child.="\n" . $space . '<li>';
            $child.= strtr($format, $arr);

            if (isset($t[$children_field])) {
                $space.="\t";
                $child.= self::showblocks($t[$children_field], $children_field, $format, true);
            }
            $child.= '</li>';
        }
        $out .=$child . "\n" . $space . '</ul>';
        $space = substr($space, 0, strlen($space) - 1);
        return $out;
    }

    private function getbackdata($name) {
        global $dback;
        return isset($dback[$this->__($name)]) ? $dback[$this->__($name)] : null;
    }

    private function fillfunctions() {
        global $dback;
        ?>
        <ul style="display:none" id="bpostdata">
            <li id="bname"><?= $this->getbackdata(PENGU_COMMENT_POST_NAME) ?></li>
            <li id="bemail"><?= $this->getbackdata(PENGU_COMMENT_POST_EMAIL) ?></li>
            <li id="bwebsite"><?= $this->getbackdata(PENGU_COMMENT_POST_WEBSITE) ?></li>
            <li id="bcomment"><?= $this->getbackdata(PENGU_COMMENT_POST_COMMENT) ?></li>
        </ul>
        <script>
        <?php
        if (!empty($dback))
            echo 'var dback=true;';
        else
            echo 'var dback=false;';
        ?>

            function comment_form_clean() {
                $('#comment_form #comment_form_name').val('');
                $('#comment_form #comment_form_email').val('');
                $('#comment_form #comment_form_website').val('');
                comment_bbcode_html('');
            }
            function comment_form_fillfromcookie() {
                name = '<?= addslashes($this->getcookie('name')) ?>';
                email = '<?= addslashes($this->getcookie('email')) ?>';
                website = '<?= addslashes($this->getcookie('website')) ?>';
                $('#comment_form #comment_form_name').val(name);
                $('#comment_form #comment_form_email').val(email);
                $('#comment_form #comment_form_website').val(website);
                comment_bbcode_html('');
            }

            function comment_form_fillfrompost() {
                var name = $('#bpostdata #bname').html();
                var email = $('#bpostdata #bemail').html();
                var website = $('#bpostdata #bwebsite').html();
                var comment = $('#bpostdata #bcomment').html();
                $('#comment_form #comment_form_name').val(name);
                $('#comment_form #comment_form_email').val(email);
                $('#comment_form #comment_form_website').val(website);
                comment_bbcode_html(comment);
            }
        </script>
        <?php
    }

    function showform() {
        global $dback;
        $this->fillfunctions();
        ?> 
        <div class="comment_form_container">
            <div  id='declaration'>
                <?= alert($this->_comment_group) ?>
            </div>  

            <form method="post" name="comment_form" id="comment_form" action="<?= url::itself()->fulluri() ?>"> 
                <label>Name</label> 
                <input type="text" id="comment_form_name" name="<?= $this->__(PENGU_COMMENT_POST_NAME) ?>"/>  
                <label>Email   </label>
                <input type="text" id="comment_form_email" name="<?= $this->__(PENGU_COMMENT_POST_EMAIL) ?>" />  
                <?php if ($this->get_website) : ?> 
                    <label>Website  </label>
                    <input type="text"  id="comment_form_website" name="<?= $this->__(PENGU_COMMENT_POST_WEBSITE) ?>" value="http://"  data-default="http://"  />

                <?php endif; ?>

                <label>Comment   </label> 
                <textarea  id="comment_form_comment" name="<?= $this->__(PENGU_COMMENT_POST_COMMENT) ?>"  ></textarea>
                <script>
                    /*
                    $(function () {
                        bbeditor_install('textarea#comment_form_comment');
                    });
                    */
                </script> 

                <!--####### captcha #######-->
                <img src="<?= plugin_url(); ?>/cool-php-captcha-0.3.1/call.php" id="captcha" /><br/> 
                <a style="cursor: pointer" onclick="
                                document.getElementById('captcha').src = '<?= plugin_url(); ?>/cool-php-captcha-0.3.1/call.php?' + Math.random();
                                document.getElementById('captcha-form').focus();"
                   id="change-image">Not readable? Change text.</a>
                <br/>

                <!--####### END #######-->
                <span class="name-input">Enter the code you see above: <span class="req">*</span></span>
                <input type="text" name="captcha" id="captcha-form" autocomplete="off" />
                <br />   

                <input type="submit" name="submit" value="submit" id="comment_form_submit" onclick="return comment_form_submit();"/>  
            </form>   
        </div>
        <?php
    }

}
