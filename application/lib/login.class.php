<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: login.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


define('LOGIN_USER_LOGINED', 1);
define('LOGIN_USER_NOTACTIVE', -1);
define('LOGIN_USER_WRONG', 0);
define('LOGIN_PASSWORD_CHANGED', 1);
define('LOGIN_PASSWORD_WRONG', -1);
define('LOGIN_PASSWROD_ERROR_OCCURS', 0);
define('LOGIN_FORGET_SENT', 1);
define('LOGIN_FORGET_ERROR_OCCURS', 0);

class login extends Model {

    public $data;
    public $dataArray;
    private $hashMethod;
    private $DataRegisterName;
    private $OnSessionLoginEventMethod;
    private $OnUserLoginEventMethod;
    private $OnUserLoginFaildEventMethod;
    private $OnUserLogoutEventMethod;
    private $OnSessionLogoutEventMethod;
    private $CookieexpireTime = 604800; // 1 week
    protected $CookieAccessPath = '/';
    protected $CookieAccessDomain;
    private $LogoutPage = null;
    private $LoginPage = null;
    protected $LoginOptionField = array(
        'PK_Field' => 'id',
        'username_Field' => 'username',
        'password_Field' => 'password',
        'name_Field' => 'name',
        'email_Field' => 'email',
        'active_Filed' => 'active'
    );

    function __construct() {
        parent::__construct();
        $this->GetDataRegisterName();
        $this->setHashMethod('md5');
        if (!$this->CookieAccessDomain)
            $this->CookieAccessDomain = (lib::get_domain(HOST_URL) != 'localhost') ? lib::get_domain(HOST_URL) : null;
        //---------------------------------
        $this->data = new stdClass();
        if (!$this->LoadSessionToData()) // agar session ijad shode bashe login hast
            $this->checkCookie();   // dar gheire in sorat cookie ro bar rasi mikone  
    }

    protected function setHashMethod($mothod) {
        if (in_array($mothod, array('md5', 'sha1')))
            $this->hashMethod = $mothod;
    }

    private function Hash($text_to_hash) {
        switch ($this->hashMethod) {
            case 'md5': $text_to_hash = trim(md5($text_to_hash));
                break;
            case 'sha1': $text_to_hash = trim(sha1($text_to_hash));
                break;
        }
        return $text_to_hash;
    }

    private function GetDataRegisterName() {
        if (@$this->_name)
            $childname = $this->_name;
        else
            $childname = @get_class();
        if (!$childname)
            $childname = __CLASS__;
        $this->DataRegisterName = $childname . '_login';
    }

    public function CheckLogin() {
        if ($this->getUserFound()) {
            //////////////////////// Login Event
            if (!empty($this->OnSessionLoginEventMethod))
                call_user_func(array($this, $this->OnSessionLoginEventMethod), $this->getUserFoundId());


            $this->LoginRedirectPage();
            return true;
        }

        //////////////////////// logout Event
        if (!empty($this->OnSessionLogoutEventMethod))
            call_user_func(array($this, $this->OnSessionLogoutEventMethod));

        $QSTR = null;
        if (!empty($this->LogoutPage)) {
            $selfURL = str_replace(url::selfdomain(), null, url::itself()->url_nonqry());
            $logoutURL = str_replace(url::selfdomain(), null, url::link($this->LogoutPage)->url_nonqry());
            if ($selfURL !== $logoutURL) {
                $ref = base64::encode(url::itself()->fulluri());
                $QSTR = url::link($this->LogoutPage)->qry_nonurl(array('ref' => $ref));
            }
        }

        $this->LogoutRedirectPage($QSTR);
        return false;
    }

    protected function loginQuery($condition) {
        return $this->select()->where($condition)->limit(1)->exec();
    }

    private function authenticat($username, $password, $LoadDataToSession = true) {

        $condition_array =
                array(
                    $this->_('username_Field') => $username,
                    $this->_('password_Field') => $password
        );

        $UserFound = $this->loginQuery($condition_array);

        if (@$UserFound->numrows() == 1) {
            //  User Is Login
            //=================================Check Acount Active
            if ($this->_('active_Filed')) {
                $activ = $this->_('active_Filed');
                if (is_array($activ)) {
                    if (isset($activ[1])) {
                        if (!call_user_func(array($this, $activ[1]), $UserFound->current()->{$activ[0]}))
                            return 0;
                    }
                    else {
                        $key = key($activ);
                        if ($UserFound->current()->{$key} != $activ[$key])
                            return 0;
                    }
                } else {
                    if (!convert::to_bool($UserFound->current()->{$activ}))
                        return 0;
                }
            }
            //====================================================
            $this->fillData($UserFound->current);  //->current is Array
            //----
            if ($LoadDataToSession)
                $this->LoadDataToSession();
            return 1;
        }
        return false;
    }

    #======================================================

    private function LoadDataToSession() {
        $_SESSION[$this->DataRegisterName] = $this->getData();
    }

    private function LoadSessionToData() {
        if (isset($_SESSION[$this->DataRegisterName])) {
            $this->fillData($_SESSION[$this->DataRegisterName]);
            return true;
        }
        return false;
    }

    #======================================================

    private function fillData($UserData) {
        if (is_array($UserData)) {
            $this->dataArray = $UserData;
            foreach ($this->dataArray as $k => $v)
                $this->data->{$k} = $v;
        }
    }

    private function setData($offset, $value) {
        if (isset($this->dataArray[$offset])) {
            $this->dataArray[$offset] = $value;
            $this->data->{$offset} = $value;
        }
    }

    private function getData($offset = null) {
        if (!$offset)
            return $this->dataArray;
        if (isset($this->dataArray[$offset]))
            return $this->dataArray[$offset];
    }

    public static function data($Offset = null) {
        if (function_exists('get_called_class')) {
            $classname = get_called_class();
            $instance = new $classname;
            if (isset($instance->dataArray[$Offset])) {
                if (is_numeric($instance->dataArray[$Offset]))
                    return $instance->dataArray[$Offset];
                else if (validate::_is_boolean_Type($instance->dataArray[$Offset]))
                    return convert::to_bool($instance->dataArray[$Offset]);
                else if (isset($instance->dataArray[$Offset]))
                    return $instance->dataArray[$Offset];
            }
            elseif (!$Offset)
                return $instance->dataArray;
        }
        else
            throw new Exception("This function is require php 5.3.0 +");
    }

    public static function isLogin() {
        if (function_exists('get_called_class')) {
            $classname = get_called_class();
            $instance = new $classname;
            if ($instance->getUserFoundId())
                return true;
        }
        else
            throw new Exception("This function is require php 5.3.0 +");
        return false;
    }

    #======================================================

    public function dologin($username, $password, $remember = false) {
        $username = input::safe($username);
        $password = $this->Hash($password);
        $auth = $this->authenticat($username, $password, $LoadDataToSession = true);
        if ($auth === 1) {
            // user login
            if ($remember)
                $this->set_Cookie($username, $password, $this->CookieexpireTime);
            //////////////////////// User Login Event
            if (!empty($this->OnUserLoginEventMethod))
                call_user_func(array($this, $this->OnUserLoginEventMethod), $this->getUserFoundId());

            $this->LoginRedirectPage();
            return LOGIN_USER_LOGINED;
        } else if ($auth === 0) {
            if (!empty($this->OnUserLoginAcountNotActiveEventMethod))
                call_user_func(array($this, $this->OnUserLoginFaildEventMethod));
            //account is not acitve
            $this->DestroyVariable();
            $this->LogoutRedirectPage();
            return LOGIN_USER_NOTACTIVE;
        } else {
            //////////////////////// Login Faild Event
            if (!empty($this->OnUserLoginFaildEventMethod))
                call_user_func(array($this, $this->OnUserLoginFaildEventMethod));
            $this->DestroyVariable();
            $this->LogoutRedirectPage();

            //user or pass is wrong
            return LOGIN_USER_WRONG;
        }
    }

    public function logout() {
        #-------------------------------------
        # User Logout Event
        if (!empty($this->OnUserLogoutEventMethod))
            call_user_func(array($this, $this->OnUserLogoutEventMethod), $this->getUserFoundId());
        #-------------------------------------    
        $this->DestroyVariable();
        $this->LogoutRedirectPage();
    }

    private function DestroyVariable() {
        unset($_SESSION[$this->DataRegisterName]);
        setcookie($this->DataRegisterName, "", time() - $this->CookieexpireTime, $this->CookieAccessPath, $this->CookieAccessDomain);
    }

    #======================================================

    public function setLoginPage($page) {
        $this->LoginPage = $page;
        return $this;
    }

    public function setLogoutPage($page) {
        $this->LogoutPage = $page;
        return $this;
    }

    private function LoginRedirectPage() {
        if (isset($_GET['ref'])) {
            $forwarder = base64::decode($_GET['ref']);
            if (!empty($forwarder)) {

                if (!headers_sent()) {
                    ref($forwarder)->redirect();
                } else {
                    ref($forwarder)->locate();
                }
            }
        }

        if (!empty($this->LoginPage)) {
            if (!headers_sent())
                ref($this->LoginPage)->redirect();
            else {
                echo ref($this->LoginPage)->locate(true);
            }
            exit;
        }
    }

    private function LogoutRedirectPage($QSTR = null) {
        if (!empty($this->LogoutPage)) {
            if (!headers_sent())
                ref(url::link($this->LogoutPage)->url_nonqry() . $QSTR)->redirect();
            else {
                echo ref(url::link($this->LogoutPage)->url_nonqry() . $QSTR)->locate(true);
            }
            exit;
        }
    }

    #======================================================

    public function RegisterEvent_OnUserLogin($eventname) {
        $this->OnUserLoginEventMethod = $eventname;
    }

    public function RegisterEvent_OnSessionLogin($eventname) {
        $this->OnSessionLoginEventMethod = $eventname;
    }

    public function RegisterEvent_OnUserLogout($eventname) {
        $this->OnUserLogoutEventMethod = $eventname;
    }

    public function RegisterEvent_OnSessionLogout($eventname) {
        $this->OnSessionLogoutEventMethod = $eventname;
    }

    public function RegisterEvent_OnUserLoginFaild($eventname) {
        $this->OnUserLoginFaildEventMethod = $eventname;
    }

    public function RegisterEvent_OnUserLoginAcountNotActive($eventname) {
        $this->OnUserLoginAcountNotActiveEventMethod = $eventname;
    }

    #======================================================

    private function set_Cookie($username, $password, $expireTime) {
        $cookieValue =
                base64_encode(json_encode(
                        array(
                            'username' => $username,
                            'password' => $password
                        )
        ));
        if (setcookie($this->DataRegisterName, $cookieValue, time() + $expireTime, $this->CookieAccessPath, $this->CookieAccessDomain))
            return true;
    }

    private function getCookie() {
        if (isset($_COOKIE[$this->DataRegisterName]))
            return json_decode(base64_decode($_COOKIE[$this->DataRegisterName]));
        return false;
    }

    private function checkCookie() {
        $cookie = $this->getCookie();
        if ($cookie) {
            $auth = $this->authenticat($cookie->username, $cookie->password, true);
            if ($auth == 1)
                return true;
        }
        return false;
    }

    public function setCookieexpire($time) {
        if (is_numeric($time))
            $this->CookieexpireTime = $time;
        return $this;
    }

    #======================================================

    public function getUserFoundId() {
        $ID = $this->getData($this->_('PK_Field'));
        if (is_numeric($ID))
            return $ID;
        return false;
    }

    public function getUserFound() {
        $user = $this->getData($this->_('username_Field'));
        if (!empty($user))
            return $user;
        return false;
    }

    #======================================================
    /**
     * 
     * @param type $userid  pk(id)
     * @param type $newpassword   newpassword
     * @param type $oldpassword   oldpassword you can set optional
     * @return  1  password is changed
     * @return  0  previous password is wrong
     * @return  false  exist error
     */

    public function ChangePass($newpassword, $oldpassword = null, $username = null) {
        $passField = $this->_("password_Field");
        $userField = $this->_("username_Field");

        if (!$username && !($username = $this->getUserFound()))
            return LOGIN_PASSWROD_ERROR_OCCURS;
        if ($oldpassword !== null) {
            $found = $this->select()->where(array($userField => $username, $passField => $this->Hash($oldpassword)))->exec();
            if ($found->numrows() != 1)
                return LOGIN_PASSWORD_WRONG; // Invalid Old Password
        }
        $newpassword = $this->Hash($newpassword);
        if ($this->update(array($passField => $newpassword))->where(array($userField => $username))->exec()) {
            $this->setData($this->_('password_Field'), $newpassword);
            $this->LoadDataToSession();
            $u = $this->getData($this->_('username_Field'));
            $p = $this->getData($this->_('password_Field'));
            if ($this->getCookie())
                $this->set_Cookie($u, $p, $this->CookieexpireTime);

            return LOGIN_PASSWORD_CHANGED; // success
        }

        return false;
    }

    #======================================================

    function confirmForget($confirmCode) {
        if (!$this->_('confirm_Field'))
            return false;
        if (empty($confirmCode) || $confirmCode == 'confirmed')
            return false;
        $needleFields = "`" . $this->_('PK_Field') . "`,`" . $this->_('confirm_Field') . "`";
        $found = $this->select($needleFields)->where("`" . $this->_('confirm_Field') . "` like '" . input::safe($confirmCode) . ":%'")->limit(1)->exec();
        if ($found->numrows()) {
            $foundid = $found->current()->{$this->_('PK_Field')};
            $confirmData = $found->current()->{$this->_('confirm_Field')};
            if (empty($confirmData))
                return false;
            @list($cf, $pass) = explode(':', $confirmData);
            if (empty($pass))
                return false;
            $upd = array(
                $this->_('password_Field') => $pass,
                $this->_('confirm_Field') => 'confirmed'
            );
            $res = $this->update($upd)->where(array($this->_('PK_Field') => $foundid))->exec();
            if ($res)
                return $foundid;
        }
        return false;
    }

    function forget($email, $msgtxt = null, $subject = null) {

        if (!class_exists('PHPMailer'))
            return false;

        $site = root_url();
        $DefaultConfirmUrl = url::itself()->url_nonqry(array('confirm' => '{confirm-code}'));

        $Defaultmsgtxt = <<<EOD
Hi  {{$this->LoginOptionField['name_Field']}} , <br/>
Here's your username and new password :
<br/><br/>
Username: <b>{{$this->LoginOptionField['username_Field']}}</b><br/>
Password: <b>{{$this->LoginOptionField['password_Field']}}</b><br/>
<br/>
To change your password please confirm it by clicking on the link below:<br>
Yes <a href='{$DefaultConfirmUrl}'>&gt;&gt;&gt; I Confirm &lt;&lt;&lt;</a> to change my password to <b>{{$this->LoginOptionField['password_Field']}}</b> 
EOD;
        if (empty($msgtxt))
            $msgtxt = $Defaultmsgtxt;

        $found = $this->select()->where(array($this->_('email_Field') => $email))->limit(1)->exec();
        if ($found->numrows() == 1) {
            $foundId = $found->current()->{$this->_('PK_Field')};
            $newpass = lib::rand(8);
            $Code = lib::rand(15);

            $generatedConfirm = $Code . ':' . $this->Hash($newpass);
            $res = $this->update(array($this->_('confirm_Field') => $generatedConfirm))->where(array($this->_('PK_Field') => $foundId))->exec();
            if (!$res)
                return LOGIN_FORGET_ERROR_OCCURS;

            $msgtxt = str_replace("{" . $this->_('password_Field') . "}", $newpass, $msgtxt); // replace password
            $msgtxt = str_replace("{confirm-code}", $Code, $msgtxt); // replace confirm-code
            foreach ($this->LoginOptionField as $field) {
                if (is_string($field))
                    $msgtxt = str_replace("{" . $field . "}", $found->current()->{$field}, $msgtxt);
            }


            $mail = new PHPMailer();
            if (defined('SetEmailFrom')) {
                $fromname = defined('SetEmailName') ? SetEmailName : null;
                $mail->SetFrom(SetEmailFrom, $fromname);
            }
            $mail->AddAddress($email);
            $mail->Subject = (!empty($subject) ? $subject : "Password Recovery");
            $mail->MsgHTML($msgtxt);
            if ($mail->send())
                return LOGIN_FORGET_SENT;
        }
        else
            return LOGIN_FORGET_ERROR_OCCURS;
        return false;
    }

    #======================================================

    private function getdomain() {
        return lib::get_domain(url::itself()->url_nonqry());
    }

    private function _($name) {
        if (isset($this->LoginOptionField[$name]))
            return $this->LoginOptionField[$name];
    }

}