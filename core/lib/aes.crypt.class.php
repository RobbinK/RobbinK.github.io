<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: aes.crypt.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */


class mcrypt
{

    private $dobase64 = false;
    private static $instance;
    private $Securekey = 'ASDFGHJKL!@#$%^&*()';
    private $block;

    function __construct()
    {
        $this->block = mcrypt_get_block_size('des', 'ecb');
    }

    static function extention_is_enabled()
    {
        return (function_exists('mcrypt_encrypt') && function_exists('mcrypt_decrypt'));
    }

    public function Securekey($keyText)
    {
        if (!empty($keyText))
            $this->Securekey = $keyText;
        return $this;
    }

    public function do_base64()
    {
        if (method_exists('base64', 'encode'))
            $this->dobase64 = true;
        return $this;
    }

    private static function _is_Serialized($str)
    {
        return ($str == serialize(false) || @unserialize($str) !== false);
    }

    function encrypt($data)
    {
        if (!is_string($data))
            $data = serialize($data);
        // PKCS7 Padding
        $pad = $this->block - (strlen($data) % $this->block);
        $data .= str_repeat(chr($pad), $pad);

        $encrypted_string = mcrypt_encrypt(MCRYPT_DES, $this->Securekey, utf8_encode($data), MCRYPT_MODE_ECB);
        if ($this->dobase64)
            $encrypted_string = base64::encode($encrypted_string);
        return $encrypted_string;
    }

    function decrypt($decrypted_string)
    {
        if (class_exists('base64') && base64::isBase64($decrypted_string))
            $decrypted_string = base64::decode($decrypted_string);
        $data = mcrypt_decrypt(MCRYPT_DES, $this->Securekey, $decrypted_string, MCRYPT_MODE_ECB);
        // PKCS7 Padding
        $pad = ord($data[($len = strlen($data)) - 1]);
        $data = substr($data, 0, $len - $pad);

        if (self::_is_Serialized($data))
            $data = unserialize($data);
        return $data;
    }

}

class crypt
{

    private $dobase64 = false;
    private static $instance;
    private $Securekey = 'ASDFGHJKL!@#$%^&*()';

    private function init()
    {
        if (!self::$instance instanceof Crypt_AES) {
            include(ROOT_PATH . '/core/lib/seclib/Crypt/AES.php');
            self::$instance = new Crypt_AES(CRYPT_AES_MODE_CBC);
        }
        self::$instance->setKey($this->Securekey);
    }

    public function Securekey($keyText)
    {
        if (!empty($keyText))
            $this->Securekey = $keyText;
        return $this;
    }

    public function do_base64()
    {
        if (method_exists('base64', 'encode'))
            $this->dobase64 = true;
        return $this;
    }

    private static function _is_Serialized($str)
    {
        return ($str == serialize(false) || @unserialize($str) !== false);
    }

    public function encrypt($data)
    {
        $this->init();
        if (!is_string($data))
            $data = serialize($data);
        $data = self::$instance->encrypt($data);
        if ($this->dobase64)
            $data = base64::encode($data);
        return $data;
    }

    public function decrypt($decrypted_string)
    {
        $this->init();
        if (class_exists('base64') && base64::isBase64($decrypted_string))
            $decrypted_string = base64::decode($decrypted_string);
        $data = @self::$instance->decrypt($decrypted_string);
        if (self::_is_Serialized($data))
            $data = unserialize($data);
        return $data;
    }

}

function __pengu_crypt_Instance($class)
{
    static $instanse;
    if (!isset($instanse[$class])) {
        $instanse[$class] = (mcrypt::extention_is_enabled() && $class == 'mcrypt' ? new mcrypt : new crypt);
    }
    return $instanse[$class];
}

if (!function_exists('encrypt')) {

    function encrypt($data, $key = DefaultEncryptKey, $base64 = true, $class = 'mcrypt')
    {
        __pengu_crypt_Instance($class)->Securekey($key);
        if ($base64)
            __pengu_crypt_Instance($class)->do_base64();
        return __pengu_crypt_Instance($class)->encrypt($data);
    }

}

if (!function_exists('decrypt')) {

    function decrypt($decrypted_string, $key = DefaultEncryptKey, $class = 'mcrypt')
    {
        __pengu_crypt_Instance($class)->Securekey($key);
        return @__pengu_crypt_Instance($class)->decrypt($decrypted_string);
    }

}