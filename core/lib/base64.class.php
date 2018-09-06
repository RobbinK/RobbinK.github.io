<?php

/**
 * Description of base64
 *
 * @author hamed
 */
class base64
{

    private static $padchar = '!';

    public static function encode($data)
    {
        if (!is_string($data))
            $data = serialize($data);
        $data = base64_encode($data);
        $data = str_replace(array('+', '/', '='), array('-', '_', self::$padchar), $data);
        return $data;
    }

    private static function _is_Serialized($str)
    {
        return ($str == serialize(false) || @unserialize($str) !== false);
    }

    public static function decode($data)
    {
        $data = str_replace(array('-', '_', self::$padchar), array('+', '/', '='), $data);
        $data = base64_decode($data);
        if (self::_is_Serialized($data))
            $data = unserialize($data);
        return $data;
    }

    public static function isBase64($data)
    {
        if (!is_string($data))
            return false;
        if (preg_match("/^[a-zA-Z0-9\_\-\\" . self::$padchar . "]+$/", $data))
            return true;
        return false;
    }

}