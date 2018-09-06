<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Users_controller.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class UsersController extends InterfaceController {

    protected $_model = null;

    function __construct() {
        parent::__construct();
        $this->MapViewThemesFolder('/themes');
        $this->MapViewFolder(null);
    }

    function forget() {
        if (isset($_GET['abviewfile']))
            $this->MapViewFileName($_GET['abviewfile']);
        else
            $this->MapViewFileName('users_forget.php');

        if (isset($_GET['abnocontroller']))
            return;

        if (isset($_GET['confirm'])) {
            $model = new Admin;
            if ($model->confirmForget($_GET['confirm']))
                psuccess('Your password has been changed successfuly.<br>login to your account')->Id('OnConfirmAlert');
            else
                warning('Something is wrong!')->Id('OnConfirmAlert');
        }
        if (isset($_POST['submit'])) {
            $model = new Admin;
            $res = $model->forget($_POST['email']);
            if ($res === LOGIN_FORGET_SENT) {
                psuccess("your password was sent to your email.")->Id('OnForgetAlert')->priority(1);
                psuccess("Please check your Junk/Spam folder if you didn't get password recovery email in your inbox.")->Id('OnForgetAlert')->priority(2);
            } else if ($res === LOGIN_FORGET_ERROR_OCCURS)
                perror("your email is not valid!")->Id('OnForgetAlert');
            else
                warning("error!")->Id('OnForgetAlert');
            ref(url::itself()->fulluri(array('forget' => 1)))->redirect();
        }
    }

    function login() {
        if (isset($_GET['abviewfile']))
            $this->MapViewFileName($_GET['abviewfile']);
        elseif (validate::_is_ajax_request() && file_exists(viewfolder_path() . '/ajax_users_login.php'))
            $this->MapViewFileName('ajax_users_login.php');
        else
            $this->MapViewFileName('users_login.php');

        if (isset($_GET['abnocontroller']))
            return;

        if (isset($_GET['confirm'])) {
            $model = new Member;
            if ($model->confirm($_GET['confirm']))
                psuccess('Your account has been confirmed successfuly.');
            else
                perror('Your account was not confiremed because somting was worng!');
        }
        if (isset($_GET['at']) || isset($_GET['logintop'])) {
            $model = new Member;
            if (isset($_POST['fld_username'])) {
                if (strtolower($_POST['fld_username']) != 'username') {
                    if (!validate::_is_ajax_request())
                        $model->setLoginPage(ab_router('homepage'));
                    $checklogin = $model->dologin($_POST['fld_username'], $_POST['fld_pwd'], @convert::to_bool($_POST['remember_me']) ? true : false);
                    if (validate::_is_ajax_request())
                        die((string) $checklogin);
                    if ($checklogin === LOGIN_USER_NOTACTIVE)
                        perror("Your account is deactive!")->Id(@$_GET['alertid']);
                    if ($checklogin === LOGIN_USER_WRONG)
                        perror("Username or password is not valid!")->Id(@$_GET['alertid']);
                }
                ref(url::referrer()->fulluri(array('confirm' => null)))->redirect();
            }
        } else {
            $model = new Member;
            $model->setLoginPage(ab_router('userdashboard'));
            $model->CheckLogin();
        }
    }

    public function dashboard() {
        if (isset($_GET['abviewfile']))
            $this->MapViewFileName($_GET['abviewfile']);
        else
            $this->MapViewFileName('users_dashboard.php');

        if (isset($_GET['abnocontroller']))
            return;
        $this->islogin();
    }

    public function profile() {
        if (isset($_GET['abviewfile']))
            $this->MapViewFileName($_GET['abviewfile']);
        else
            $this->MapViewFileName('users_profile.php');

        if (isset($_GET['abnocontroller']))
            return;

        $this->islogin();
        $avatarMaxFileSize = setting::get_data('members_max_avatar_filesize', 'val');
        $this->view->avatarMaxFileSize = $avatarMaxFileSize;
        $model = new Member;
        //--Upload Avatar 
        if (isset($_GET['uploadfile']) || isset($_FILES['uploadfile'])) {
            $this->view->disable();
            pengu_user_load_class('ab_interface_upload', $upload);
            $upload->addToIndex('uploadfile');
            $upload->setValidExtentions(array('jpg', 'png', 'gif', 'jpeg'));
            $new_file_name = 'avatar_' . lib::rand(15);
            if ($file = $upload->upload(ab_tmp_dir, $new_file_name))
                echo json_encode(array('success' => true, 'file' => $file));
            else
                echo json_encode(array('success' => false, 'msg' => $upload->getErrorMsg()));
            return;
            exit;
        }
        if (isset($_GET['getkey'])) {
            $this->view->disable();
            pengu_user_load_class('ab_interface_upload', $instance);
            echo $instance->proccess_getkey();
            exit;
        }
        if (isset($_GET['progresskey'])) {
            pengu_user_load_class('ab_interface_upload', $instance);
            echo $instance->proccess_st($_GET['progresskey']);
            exit;
        }
        if (isset($_GET['showimage'])) {
            $w = 65;
            $h = 65;
            if (isset($_GET['size'])) {
                if ($_GET['size'] == 'auto') {
                    $w = null;
                    $h = null;
                } else {
                    list ($w, $h) = explode('x', $_GET['size']);
                    $w = !intval($w) ? null : intval($w);
                    $h = !intval($h) ? null : intval($h);
                }
            }

            function showimage($src, $w = null, $h = null) {
                $ins = pengu_image::resize($src, $w, $h);
                $ins->ReCreate();
                $newsource = $ins->getImagePath();
                $imginfo = getimagesize($newsource);
                header("Content-type: {$imginfo['mime']}");
                readfile($newsource);
                exit;
            }

            $path = ab_image_path($_GET['showimage']);
            if (empty($path) || !file_exists($path))
                $path = content_path() . '/images/no-img.jpg';
            showimage($path, $w, $h, false);
            exit;
        }
        //--End Upload 

        if (validate::_is_ajax_request()) {
            $this->view->disable();
            //-- deleting avatar
            if (isset($_GET['del_avatar']) && isset($_POST['id'])) {
                $records = $model->select()->where(array('id' => decrypt($_POST['id'])))->exec();
                $icon_file = trim($records->current['avatar']);
                @unlink(ab_upload_dir . '/' . $icon_file);
                $model->update(array('avatar' => ''))->where(array('id' => decrypt($_POST['id'])))->exec();
                if ($icon_file == '')
                    echo 'Avatar not found.';
                else
                    echo 'Avatar is Deleted.';
                exit;
            }
            //-- Saving
            if (isset($_GET['save'])) {
                $this->getPOST($_POST);
                $savearray = array(
                    'email' => $_POST['email'],
                    'name' => $_POST['name'],
                    'avatar' => @$_POST['avatar'],
                );
                $save_error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_alert'] = '';

                /*
                 * 
                 * Some Validation
                 * 
                 */

                if (path::get_file_size(ab_tmp_dir . '/' . $savearray['avatar'], true) / 1024 > $avatarMaxFileSize) {
                    $save_error = 1;
                    $json_out['save_code'] = 0;
                    $json_out['save_alert'] = perror("Your avatar is too big!")->alert()->getResult();
                }

                if ($save_error == 0) {
                    if (file_exists(ab_tmp_dir . '/' . $savearray['avatar'])) {
                        @copy(ab_tmp_dir . '/' . $savearray['avatar'], ab_upload_dir . '/' . $savearray['avatar']);
                        @unlink(ab_tmp_dir . '/' . $savearray['avatar']);
                    }

                    function savedata($savearray, $json_out) {
                        $model = new Member();
                        if (false !== $model->update($savearray)->where(array('id' => Member::data('id')))->exec()) {
                            $json_out['save_code'] = 1;
                            $json_out['save_alert'] = psuccess("Record updated.")->alert()->getResult();
                        } else {
                            $json_out['save_code'] = 0;
                            $json_out['save_alert'] = warning("Error in saving data.")->alert()->getResult();
                        }
                        return $json_out;
                    }

                    if (!empty($_POST['password'])) {
                        if (md5($_POST['old_password']) == Member::data('password')) {
                            $result = $model->ChangePass($_POST['password'], $_POST['old_password'], Member::data('username'));
                            if ($result > 0) {
                                $json_out = savedata($savearray, $json_out);
                            } else {
                                $json_out['save_code'] = 0;
                                $json_out['save_alert'] = perror("New Password didn`t save.")->alert()->getResult();
                            }
                        } else {
                            $json_out['save_code'] = 0;
                            $json_out['save_alert'] = perror("Old Password is Invalid.")->alert()->getResult();
                        }
                    } else {
                        $json_out = savedata($savearray, $json_out);
                    }
                }
                echo json_encode($json_out);
                exit;
            }
        }
        //get
        $this->view->user = $model->select()->where(array('id' => Member::data('id')))->exec()->current();
    }

    public function signup() {
        if (isset($_GET['abviewfile']))
            $this->MapViewFileName($_GET['abviewfile']);
        elseif (validate::_is_ajax_request() && file_exists(viewfolder_path() . '/ajax_users_signup.php'))
            $this->MapViewFileName('ajax_users_signup.php');
        else
            $this->MapViewFileName('users_signup.php');

        if (isset($_GET['abnocontroller']))
            return;

        if (!convert::to_bool(setting::get_data('membership_system', 'val'))) {
            echo "<div style='position:absolute;top:43%;left:39%;text-align:center;'>";
            echo "<b>Membership system  is closed , try later . </b>";
            echo "<br><br><a href='" . ab_router('homepage') . "'>back to HomePage<a>";
            echo "</div>";
            exit;
        }
        if (validate::_is_ajax_request()) {
            if (isset($_GET['check_captcha'])) {
                if ($_GET['captcha'] == $_SESSION['captcha'])
                    echo 'true';
                else
                    echo 'false';
                exit;
            }
            if (isset($_GET['save'])) {
                $this->getPOST($_POST);

                //check captcha
                if ((empty($_SESSION['captcha']) || $_POST['captcha'] != $_SESSION['captcha']) && setting::get_data('members_captcha_system', 'val') == 'enable') {
                    echo json_encode(array('res' => 0, 'er' => warning("Captcha code was expired!")->alert()->getResult()));
                    exit;
                }
                $_SESSION['captcha'] = null;

                $name = $_POST['name'];
                $uname = $_POST['uname'];
                $pname = $_POST['pname'];
                $email = $_POST['email'];
                $confirm_code = lib::rand(15);

                $approve = 0;
                $approval_mode = Setting::get_data('membership_approval_system', 'val');
                if ($approval_mode == 'auto' || $approval_mode === null)
                    $approve = 1;

                $user = new Member();
                $result = $user->save($uname, $pname, $email, $name, $confirm_code, $approve);

                if ($result === true) {
                    if ($approval_mode == 'auto' || $approval_mode === null)
                        $out = array('res' => 1, 'er' => psuccess('<B><font color="Green"> User registration was done successfully. </font></b>')->alert()->getResult());
                    else if ($approval_mode == 'email') {
                        if ($this->send_mail_by_tpl($email, 'Confirm Your Registation', array(
                                    'name' => $name,
                                    'username' => $uname,
                                    'email' => $email,
                                    'domain' => HOST_NAME,
                                    'login_link' => HOST_URL . url::router('userlogin')->fulluri(),
                                    'confirm_link' => HOST_URL . url::router('userlogin')->fulluri(array('confirm' => $confirm_code)),
                                    'confirm_code' => $confirm_code
                                        ), 'membership_approval_email'))
                            $out = array('res' => 1, 'er' => psuccess('<B><font color="Green"> User registration was done successfully!<br> confirmation email was sent to your mail box please confirm your email by clicking on the confirmation link we have sent to you. </font></b>')->alert()->getResult());
                    } elseif ($approval_mode == 'admin')
                        $out = array('res' => 1, 'er' => psuccess('<B><font color="Green"> User registration was done successfully!<br> please wait for admin approval. </font></b>')->alert()->getResult());
                }
                if ($result === 0)   #user is duplicated 
                    $out = array('res' => 0, 'er' => warning("This username was taken already!")->alert()->getResult());
                if ($result === -1)   #email is duplicated 
                    $out = array('res' => 0, 'er' => warning("This email was taken already!")->alert()->getResult());
                echo json_encode($out);
                exit;
            }
        }
    }

    public function addtofavorit() {
        if (!@is_numeric(input::get('gid')))
            exit;
        $gid = input::get('gid');
        $model = new Member;
        $checklogin = $model->CheckLogin();

        if ($checklogin != LOGIN_USER_LOGINED) {
            $url = ab_router('useraddtofavorit', array(), 'gid=' . $gid);
            $referrer = base64::encode($url);
            $loginform = ab_router('userlogin');
            $redirector = 'function go(){' . ref(url::link($loginform)->fulluri(array('ref' => $referrer)))->locate() . '}go();';
            if (validate::_is_ajax_request()) {
                if (isset($_GET['json']))
                    echo json_encode(array('result' => false, 'script' => $redirector));
                else
                    echo $redirector;
                exit;
            } else
                ref(url::link($loginform)->fulluri(array('ref' => $referrer)))->redirect();
        }

        $model = new Favorite;
        $added = $model->addtofavorit(Member::data('id'), $gid);
        if (validate::_is_ajax_request()) {
            if ($added === true) {
                if (isset($_GET['json']))
                    echo json_encode(array('result' => true, 'script' => "$('#adfav_msg_text').html('<div class=\\'green bold center\\'>Added to favorites.</div>');"));
                else
                    echo "$('#adfav_msg_text').html('<div class=\\'green bold center\\'>Added to favorites.</div>');";
            }
            else
            if ($added === -1) {
                if (isset($_GET['json']))
                    echo json_encode(array('result' => false, 'script' => "$('#adfav_msg_text').html('<div class=\\'red bold center\\'>Removed from favorites!</div>');"));
                else
                    echo "$('#adfav_msg_text').html('<div class=\\'red bold center\\'>Removed from favorites!</div>');";
            }
        } else {
            if ($added === true)
                psuccess("Added to favorites.");
            else if ($added === 0)
                perror("Already in favorites!!");
            ref(ab_router('userfavorites'))->redirect();
        }
    }

    public function logout() {
        $model = new Member;
        $model->logout();
        ref(ab_router('homepage'))->redirect();
    }

    function contact() {
        if (isset($_GET['abviewfile']))
            $this->MapViewFileName($_GET['abviewfile']);
        else
            $this->MapViewFileName('contactform.php');

        if (isset($_GET['abnocontroller']))
            return;
        //--End Upload 
        if (validate::_is_ajax_request()) {
            $this->view->disable();
            if (isset($_GET['check_captcha'])) {
                if ($_GET['captcha'] == $_SESSION['captcha'])
                    echo 'true';
                else
                    echo 'false';
                exit;
            }
            //-- Saving
            if (isset($_GET['save'])) {
                $this->getPOST($_POST);

                $savearray = array(
                    'name' => $_POST['name'],
                    'email' => $_POST['email'],
                    'website' => lib::get_domain($_POST['website']),
                    'type' => $_POST['type'],
                    'comment' => $_POST['comment'],
                    'ip' => agent::remote_info_ip(),
                    'country' => agent::remote_info_country_code(),
                    'time' => time(),
                    'status' => 0
                );
                $save_error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_alert'] = '';


                //check captcha
                if (empty($_SESSION['captcha']) || $_POST['captcha'] != $_SESSION['captcha']) {
                    $save_error = 1;
                    $json_out['save_code'] = 0;
                    $json_out['save_alert'] = warning("Captcha code was expired!")->alert()->getResult();
                }
                $_SESSION['captcha'] = null;

                if ($save_error == 0) {
                    $model = new Comment();
                    if (false !== $model->insert($savearray)->exec()) {
                        $json_out['save_code'] = 1;
                        $json_out['save_alert'] = psuccess("Your Message was sent successfully.")->alert()->getResult();
                    } else {
                        $json_out['save_code'] = 0;
                        $json_out['save_alert'] = warning("Error in sending data.")->alert()->getResult();
                    }
                }
                echo json_encode($json_out);
            }
            exit;
        }
    }

    function users_submission() {
        $this->islogin();
        if (isset($_GET['abviewfile']))
            $this->MapViewFileName($_GET['abviewfile']);
        else
            $this->MapViewFileName('users_submission.php');

        if (isset($_GET['abnocontroller']))
            return;
        //--Upload Flash File
        if (isset($_GET['up_game_file']) || isset($_FILES['up_game_file'])) {
            $this->view->disable();
            pengu_user_load_class('ab_interface_upload', $upload);
            $upload->addToIndex('up_game_file');
            $upload->setValidExtentions(array('swf', 'dcr', 'unity3d'));
            $new_file_name = 'feedfilesubmited_' . lib::rand(15);
            if ($file = $upload->upload(ab_tmp_dir, $new_file_name))
                echo json_encode(array('success' => true, 'file' => $file));
            else
                echo json_encode(array('success' => false, 'msg' => $upload->getErrorMsg()));
            return;
            exit;
        }

        //--Upload Thumbs
        foreach (array('game_img') as $v) {
            if (isset($_GET['up_img_' . $v]) || isset($_FILES['up_img_' . $v])) {
                $this->view->disable();
                pengu_user_load_class('ab_interface_upload', $upload);
                $upload->addToIndex('up_img_' . $v);
                $upload->setValidExtentions(array('jpg', 'png', 'gif', 'jpeg'));
                $new_file_name = 'feedimagesubmited_' . lib::rand(15);
                if ($file = $upload->upload(ab_tmp_dir, $new_file_name))
                    echo json_encode(array('success' => true, 'file' => $file));
                else
                    echo json_encode(array('success' => false, 'msg' => $upload->getErrorMsg()));
                return;
                exit;
            }
        }

        if (isset($_GET['getkey'])) {
            $this->view->disable();
            pengu_user_load_class('ab_interface_upload', $instance);
            echo $instance->proccess_getkey();
            exit;
        }
        if (isset($_GET['progresskey'])) {
            pengu_user_load_class('ab_interface_upload', $instance);
            echo $instance->proccess_st($_GET['progresskey']);
            exit;
        }
        if (isset($_GET['showimage'])) {
            $this->preview_img(ab_image_path($_GET['showimage']));
            exit;
        }

        if (validate::_is_ajax_request()) {

            if (isset($_GET['check_captcha'])) {
                if ($_GET['captcha'] == $_SESSION['captcha'])
                    echo 'true';
                else
                    echo 'false';
                exit;
            }

            //-- Saving
            if (isset($_GET['save'])) {
                $this->getPOST($_POST);

                $savearray = array(
                    'game_name' => @$_POST['game_name'],
                    'game_categories' => @$_POST['game_categories'],
                    'game_description' => @$_POST['game_description'],
                    'game_instruction' => @$_POST['game_instruction'],
                    'game_controls' => @$_POST['game_controls'],
                    'game_tags' => @$_POST['game_tags'],
                    'game_img' => @$_POST['game_img'],
                    'game_file' => @$_POST['game_file'],
                    'game_width' => @$_POST['width'],
                    'game_height' => @$_POST['height'],
                    'user_id' => @Member::data('id'),
                    'status' => 0
                );

                $save_error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_txt'] = '';


                if (abversion_more_or_equal('1.4.6')) {
                    //check captcha
                    if (empty($_SESSION['captcha']) || @$_POST['captcha'] != @$_SESSION['captcha']) {
                        $save_error = 1;
                        $json_out['save_code'] = 0;
                        $json_out['save_txt'] = warning("Captcha code was expired!")->alert()->getResult();
                    }
                    $_SESSION['captcha'] = null;
                }

                if ($save_error == 0) {
                    //  MOve from temp
                    if (!empty($savearray['game_img']) && file_exists(ab_tmp_dir . '/' . $savearray['game_img'])) {
                        $filename = convert::filesafe($savearray['game_name']);
                        $ext = path::get_extension($savearray['game_img']);
                        $newfilename = $filename . '.' . $ext;
                        $i = 1;
                        while (file_exists(ab_submission_images_dir . '/' . $newfilename)) {
                            $newfilename = $filename . '_' . $i . '.' . $ext;
                            $i++;
                        }
                        @copy(ab_tmp_dir . '/' . $savearray['game_img'], ab_submission_images_dir . '/' . $newfilename);
                        @unlink(ab_tmp_dir . '/' . $savearray['game_img']);
                        $savearray['game_img'] = $newfilename;
                    }

                    if (!empty($savearray['game_file']) && file_exists(ab_tmp_dir . '/' . $savearray['game_file'])) {
                        $filename = convert::filesafe($savearray['game_name']);
                        $ext = path::get_extension($savearray['game_file']);
                        $newfilename = $filename . '.' . $ext;
                        $i = 1;
                        while (file_exists(ab_submission_files_dir . '/' . $newfilename)) {
                            $newfilename = $filename . '_' . $i . '.' . $ext;
                            $i++;
                        }
                        @copy(ab_tmp_dir . '/' . $savearray['game_file'], ab_submission_files_dir . '/' . $newfilename);
                        @unlink(ab_tmp_dir . '/' . $savearray['game_file']);
                        $savearray['game_file'] = $newfilename;
                    }
                    //
                    $model = new Game_submited();
                    if ($model->insert(array_merge($savearray, array('addtime' => time())))->exec()) {
                        $json_out['save_code'] = 1;
                        $json_out['save_txt'] = psuccess('<B><font color="Green"> Your game was submitted successfully! </font></b>')->alert()->getResult();
                    } else {
                        $json_out['save_code'] = 0;
                        $json_out['save_txt'] = psuccess('<B><font color="Green"> Error in submitting game! </font></b>')->alert()->getResult();
                    }
                }
                echo json_encode($json_out);
                exit;
            }
        }
        $model = new Category;
        $this->view->cats = $model->select('title')->where(array('is_active' => 1))->exec();
    }

}
