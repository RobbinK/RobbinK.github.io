<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: agent.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class agent
{

    static $data;
    static $model;
    static $fake_ip = null;
    static $fake_referrer = null;
    private static $countries;
    private static $langcodes;
    private static $langcodes_flip;
    private static $browser;
    public static $detecting_bots = true;

    public static function init()
    {
        self::remote_info_referrer();
        self::set_data_remote_info();
    }

    public static function is_bot()
    {
        if (!isset(self::$data['bot']))
            self::set_data_remote_info();
        if (self::$data['bot'])
            return true;
        return false;
    }

    public static function remote_info_ip()
    {
        if (!isset(self::$data))
            self::set_data_remote_info();
        return @self::$data['ip'];
    }

    public static function remote_info_country_code()
    {
        if (!isset(self::$data))
            self::set_data_remote_info();
        return @self::$data['code'];
    }

    public static function remote_info_country()
    {
        if (!isset(self::$data))
            self::set_data_remote_info();
        return @self::$data['country'];
    }

    public static function remote_info_tier()
    {
        if (!isset(self::$data))
            self::set_data_remote_info();
        return @self::$data['tier'];
    }

    public static function remote_info_referrer()
    {
        if (!isset(self::$data['referrer'])) {
            $ip = self::get_client_ip();
            $cn = 'ab_' . md5('agent_referrer' . $ip);


            if (isset($_COOKIE[$cn])) {
                $data = decrypt($_COOKIE[$cn]);
                self::$data['referrer'] = $data;
                setcookie($cn, encrypt($data), time() + 15 * 60, '/');
            } else {
                $r = self::get_referrer_domain(true);
                if ($r == get_domain(HOST_URL))
                    return;
                $data_enc = encrypt($r);
                setcookie($cn, $data_enc, time() + 15 * 60, '/');
                self::$data['referrer'] = $r;
            }
        }
        return isset(self::$data['referrer']) ? self::$data['referrer'] : null;
    }

    public static function set_data_remote_info()
    {
        if (!isset(self::$data['ip'])) {
            $ip = self::get_client_ip();
            if (empty($ip))
                return false;
            $cn = 'ab_' . md5('agent_' . $ip);
            if (isset($_COOKIE[$cn])) {
                $cookieData = decrypt($_COOKIE[$cn]);
                @list($code, $country, $tier, $bot) = explode('|', $cookieData);
            } else {
                $code = null;
                $country = null;
                $tier = null;
                $bot = 0;
                $res = self::getcountry($ip);
                if (isset($res['code'])) {
                    $code = @$res['code'] ? $res['code'] : null;
                    $country = @$res['country'] ? $res['country'] : 'unknown';
                    $tier = !empty($res['tier']) ? $res['tier'] : 3;
                    if (self::$detecting_bots) {
                        $ag = self::get_user_agent();
                        if (empty($ag))
                            $bot = true;
                        else {
                            // get bots ip from db
                            $bots = ','.@file_get_contents(lib_path() . '/agent/lib/bots.db.dat'). ',';
                            if (strpos($bots, ",{$ip},"))
                                $bot = 1;
                            else if (strpos($bots, "," . substr($ip, 0, strrpos($ip, '.')) . ",") !== false)
                                $bot = 1;

                            $spiders = @include(lib_path() . '/agent/lib/spiders.db.php');
                            foreach ($spiders as $spider)
                                if (stripos($ag, $spider) !== false)
                                    $bot = 1;
                        }
                    }
                }
                $cookieData = encrypt(join('|', array(@$code, @$country, @$tier, @$bot ? 1 : 0)));
                setcookie($cn, $cookieData, time() + 10 * 256 * 24 * 60 * 60, '/');
            }

            self::$data['ip'] = @$ip;
            self::$data['code'] = @$code;
            self::$data['country'] = @$country;
            self::$data['tier'] = @$tier;
            self::$data['bot'] = @$bot;
        }
    }

    public static function country($code, $i = null)
    {
        self::loadCountries();
        if (isset(self::$countries[strtolower($code)]))
            return $i ? (isset(self::$countries[strtolower($code)][$i]) ? self::$countries[strtolower($code)][$i] : null) : self::$countries[strtolower($code)];
    }

    public static function setModel($callable)
    {
        self::$model = $callable;
    }

    private static function getcountry($ip)
    {
        if (!isset(self::$model) || !$model = call_user_func(self::$model))
            $model = new Iprange();
        $resip = array();
        $sql = "SELECT  country  FROM
	            " . $model->gettable() . " i  WHERE
	            i.ip < INET_ATON('{$ip}')
	        ORDER BY
	            i.ip DESC
	        LIMIT 0,1";
        $code = @$model->query($sql)->exec()->current;
        self::loadCountries();
        if (isset($code['country']) && isset(self::$countries[$code['country']]))
            return array_merge(array('code' => $code['country']), self::$countries[$code['country']]);
        else
            return false;
    }

    public static function lang_to_code($language)
    {
        if (!isset(self::$langcodes)) {
            global $metalanguages;
            require_once(lib_path() . '/agent/meta_langs.php');
            self::$langcodes = $metalanguages;
            unset($metalanguages);
        }
        if (isset(self::$langcodes[$language])) {
            return self::$langcodes[$language];
        }
        return false;
    }

    public static function code_to_lang($code)
    {
        if (!isset(self::$langcodes_flip)) {
            global $metalanguages;
            require_once(lib_path() . '/agent/meta_langs.php');
            self::$langcodes_flip = array_flip($metalanguages);
            unset($metalanguages);
        }
        if (isset(self::$langcodes_flip[$code])) {
            return self::$langcodes_flip[$code];
        }
        return false;
    }

    private static function loadCountries()
    {
        if (!isset(self::$countries)) {
            self::$countries = require(lib_path() . '/agent/countries.php');
        }
    }

    public static function get_client_ip()
    {
        static $ip;
        if (isset($ip))
            return $ip;
        if (self::$fake_ip) {
            $ip = self::$fake_ip;
            return $ip;
        }
        $ip = null;
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ip = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ip = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ip = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ip = $_SERVER['REMOTE_ADDR'];
        return $ip;
    }

    public static function get_referrer_domain($subdomain = true)
    {
        if (isset(self::$fake_referrer)) {
            return get_domain(self::$fake_referrer, $subdomain);
        }
        $ref = self::get_http_referrer();
        if (!$ref)
            return;
        return get_domain($ref, $subdomain);
    }

    public static function get_http_referrer()
    {
        return getenv('HTTP_REFERER');
    }

    private static function get_user_browser()
    {
        if (!self::$browser) {
            $u_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Unknown';
            $bname = 'Unknown';
            $platform = 'Unknown';
            $version = "";

            //First get the platform?
            if (preg_match('/linux/i', $u_agent)) {
                $platform = 'linux';
            } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
                $platform = 'mac';
            } elseif (preg_match('/windows|win32/i', $u_agent)) {
                $platform = 'windows';
            }
            $ub = 'unknown';
            // Next get the name of the useragent yes seperately and for good reason
            if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
                $bname = 'Internet Explorer';
                $ub = "MSIE";
            } elseif (preg_match('/Firefox/i', $u_agent)) {
                $bname = 'Mozilla Firefox';
                $ub = "Firefox";
            } elseif (preg_match('/Chrome/i', $u_agent)) {
                $bname = 'Google Chrome';
                $ub = "Chrome";
            } elseif (preg_match('/Safari/i', $u_agent)) {
                $bname = 'Apple Safari';
                $ub = "Safari";
            } elseif (preg_match('/Opera/i', $u_agent)) {
                $bname = 'Opera';
                $ub = "Opera";
            } elseif (preg_match('/Netscape/i', $u_agent)) {
                $bname = 'Netscape';
                $ub = "Netscape";
            }

            // finally get the correct version number
            $known = array('Version', $ub, 'other');
            $pattern = '#(?<browser>' . join('|', $known) .
                ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
            if (!preg_match_all($pattern, $u_agent, $matches)) {
                // we have no matching number just continue
            }

            // see how many we have
            $i = count($matches['browser']);
            if ($i != 1) {
                //we will have two since we are not using 'other' argument yet
                //see if version is before or after the name
                if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                    $version = $matches['version'][0];
                } elseif (isset($matches['version'][1])) {
                    $version = $matches['version'][1];
                }
            } else {
                $version = $matches['version'][0];
            }

            // check if we have a number
            if ($version == null || $version == "") {
                $version = "?";
            }

            self::$browser = array(
                'userAgent' => $u_agent,
                'name' => $bname,
                'version' => $version,
                'platform' => $platform,
                'pattern' => $pattern
            );
        }
        return self::$browser;
    }

    static function get_user_agent()
    {
        if (!self::$browser)
            self::get_user_browser();
        return isset(self::$browser['userAgent']) ? self::$browser['userAgent'] : null;
    }

    static function get_user_browser_name()
    {
        if (!self::$browser)
            self::get_user_browser();
        return isset(self::$browser['name']) ? self::$browser['name'] : null;
    }

    static function get_user_browser_version()
    {
        if (!self::$browser)
            self::get_user_browser();
        return isset(self::$browser['version']) ? self::$browser['version'] : null;
    }

    static function get_user_browser_platform()
    {
        if (!self::$browser)
            self::get_user_browser();
        return isset(self::$browser['platform']) ? self::$browser['platform'] : null;
    }

    static function get_user_browser_pattern()
    {
        if (!self::$browser)
            self::get_user_browser();
        return isset(self::$browser['pattern']) ? self::$browser['pattern'] : null;
    }

    static function mobile_detect()
    {
        static $detect;
        if (isset($detect))
            return $detect;
        require_once lib_path() . '/agent/lib/mobile.detect.class.php';
        $detect = new Mobile_Detect();
        return $detect;
    }

}
