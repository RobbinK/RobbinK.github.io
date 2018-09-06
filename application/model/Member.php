<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Member.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class Member extends login {

    protected $_table = "abs_members";
    protected $_name = "absuserlogin";
    public $_cache_time = CacheExpireTime;
    private $resmem;
    protected $LoginOptionField = array(
        'PK_Field' => 'id',
        'username_Field' => 'username',
        'password_Field' => 'password',
        'name_Field' => 'name',
        'email_Field' => 'email',
        'active_Filed' => array('status' => 1),
        'confirm_Field' => 'confirm'
    );

    function __construct() {
        parent::__construct();
        $this->RegisterEvent_OnUserLogin('userlogin');
        $this->RegisterEvent_OnUserLogout('userlogout');
    }

    public function have_members() {
        if (is_array($this->resmem) && current($this->resmem))
            return true;
        else
            return false;
    }

    public function the_member() {
        if (!is_array($this->resmem) || empty($this->resmem))
            return false;
        $current = current($this->resmem);
        next($this->resmem);
        return $current;
    }

    public function Allmembers($limit = null) {
        $this->select()->where(array('status' => 1))->orderby('regdate desc');
        if (UseCache && intval($this->_cache_time))
            $this->cacheable(60);

        if ($limit !== null)
            $this->limit($limit);

        $this->resmem = $this->exec()->allrows();
        return $this->resmem;
    }

    protected function userlogin($userid) {
        $this->update(array(
            'login' => array('login+1'),
            'lastlogin' => time(),
            'ip_info_range' => agent::remote_info_ip(),
            'ip_info_country' => agent::remote_info_country_code()
        ))->where(array('id' => $userid))->limit(1)->exec();
    }

    function save($uname, $pname, $email, $name, $confirm = null, $approve = 1) {
        $found = $this->select()->where(array($this->LoginOptionField['username_Field'] => $uname, $this->LoginOptionField['email_Field'] => $email), 'or')->exec();
        if ($found->numrows() == 0) {
            if ($this->insert(
                            array(
                                'name' => $name,
                                'group' => 2,
                                'username' => $uname,
                                'password' => md5($pname),
                                'email' => $email,
                                'regdate' => date('Y-m-d'),
                                'status' => $approve,
                                'confirm' => $confirm
                            )
                    )->exec()
            )
                return true;
        }
        else {
            if ($found->current[$this->LoginOptionField['username_Field']] == $uname)
                return 0; // Username is already exists
            if ($found->current[$this->LoginOptionField['email_Field']] == $email)
                return -1; //Email is already exists
        }
    }

    function confirm($confirmCode) {
        if (empty($confirmCode) || strlen($confirmCode) != 15 || $confirmCode == 'confirmed')
            return false;
        $found = $this->select('id')->where(array('status' => 0, 'confirm' => $confirmCode))->limit(1)->exec();
        if ($found->found()) {
            $foundid = $found->current()->id;
            $upd = array(
                'status' => 1,
                'confirm' => 'confirmed'
            );
            $res = $this->update($upd)->where(array('id' => $foundid))->exec();
            if ($res)
                return true;
        }
        return false;
    }

}