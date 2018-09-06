<?php

/**
 * baraye global kardan moteghayer ha estefade mishe
 * liste moteghayer ha ro besorate araye vared mikonim
 *  example:
 * <pre><Code>
 * eval(globals_st(array('varible1','variable2')));
 * </pre></code>
 */
function globals_st($args)
{
    $vars = array();
    foreach ($args as $k => $v)
        if (is_string($k))
            $vars[] = "$" . $k;

    if (count($vars) > 0)
        return "global " . join(",", $vars) . ";";
    else
        return null;
}

/**
 * baraye unset kardan moteghayer ha estefade mishe
 * liste moteghayer ha ro besorate araye vared mikonim
 * example:
 * <pre><Code>
 * eval(unextract(array('varible1','variable2')));
 * </pre></code>
 */
function unextract(array $VaribleNames)
{
    $newarray = array();
    foreach ($VaribleNames as $k => $v)
        if (is_string($k))
            $newarray[] = "$" . $k;
    return ('unset(' . join(',', $newarray) . ');');
}

/**
 * convert object to array
 * example:
 * <pre><Code>
 * $std=new stdclass();
 * $std->var1=123;
 * objectToArray($std)
 * </pre></code>
 * @return array
 */
function objectToArray($data)
{
    if (is_array($data) || is_object($data)) {
        $result = array();
        foreach ($data as $key => $value) {
            $result[$key] = objectToArray($value);
        }
        return $result;
    }
    return $data;
}

/**
 * Check If is md5
 * @return bool
 */
function _is_Md5($md5)
{
    return !empty($md5) && preg_match('/^[a-f0-9]{32}$/', $md5);
}

function rmkdir($path, $mode = 0777)
{
    $path = trim($path);
    $path = rtrim(preg_replace(array("/\\\{2,}/", "/\/{2,}/"), "/", $path), "/");
    if (substr($path, 1, 1) == ":") {
        //win
        $s = substr($path, 0, 2);
        $path = substr($path, 3);
    } else {
        $s = null;
        $path = ltrim($path, './');
    }

    $dirs = explode('/', $path);
    $count = count($dirs);

    $oldmask = umask(0);
    for ($i = 0; $i < $count; ++$i) {
        $s .= '/' . $dirs[$i];
        if (@file_exists($s) || !@mkdir($s, $mode)) {
            continue;
        }
        @chmod($s, $mode);
    }
    umask($oldmask);
    return true;
}

function permup($file)
{
    /* change perm */
    $stat = stat(dirname($file));
    $perms = $stat['mode'] & 0000666;
    @chmod($file, $perms);
}

function rrmdir($dir)
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir . "/" . $object) == "dir")
                    rrmdir($dir . "/" . $object);
                else
                    unlink($dir . "/" . $object);
            }
        }
        reset($objects);
        rmdir($dir);
    }
}

/**
 * Get Server Name
 */
function get_host()
{
    if (isset($_SERVER['HTTP_X_FORWARDED_HOST']) && $host = $_SERVER['HTTP_X_FORWARDED_HOST']) {
        $elements = explode(',', $host);
        $host = trim(end($elements));
    } else {
        if (!isset($_SERVER['HTTP_HOST']) || !$host = $_SERVER['HTTP_HOST']) {
            if (!isset($_SERVER['SERVER_NAME']) || !$host = $_SERVER['SERVER_NAME']) {
                $host = !empty($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '';
            }
        }
    }
    $host = preg_replace('/:\d+$/', '', $host);
    return trim($host);
}

/**
 * Get domain
 */
function get_domain($url, $get_subdomain = true)
{
    if (!$url)
        return;
    if ($url != strip_tags($url))
        return;
    $domain = preg_replace("/https?:\/\//i", "", $url);
    $domain = preg_replace("/^www\./i", "", $domain);
    $domain = preg_replace("/(\/|\:).*/i", "", $domain);
    if (!$get_subdomain && preg_match("/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,7})$/i", $domain, $matches))
        $domain = $matches['domain'];
    return $domain;
}

/**
 * Get url after redirect
 */

function get_redirected_url($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, TRUE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $a = curl_exec($ch);
    if (preg_match('#Location: (.*)#', $a, $r))
       return trim($r[1]);
    return $url;
}
/**
 * Get SubDir
 */
function get_subdir()
{
    //subdir in old versions changed to UrlSubDir
    if (defined('UrlSubDir'))
        return leftchar('/', rtrim(UrlSubDir, '/'));
    if (defined('subdir'))
        return leftchar('/', rtrim(subdir, '/'));
    return FileSubDir;
}

/**
 * Is Local Server
 */
function isLocalServer()
{
    return (get_host() == 'localhost' || get_host() == '127.0.0.1');
}

/**
 * left char
 */
function leftchar($ch, $str)
{
    $trimed = ltrim($str, $ch);
    if (!empty($trimed))
        return $ch . $trimed;
}

/**
 * right char
 */
function rightchar($ch, $str)
{
    $trimed = rtrim($str, $ch);
    if (!empty($trimed))
        return $trimed . $ch;
}

/* get_called_class */
if (!function_exists('get_called_class')) {

    function get_called_class($bt = false, $l = 1)
    {
        if (!$bt)
            $bt = debug_backtrace();
        if (!isset($bt[$l]))
            throw new Exception("Cannot find called class -> stack level too deep.");
        if (!isset($bt[$l]['type'])) {
            throw new Exception('type not set');
        } else
            switch ($bt[$l]['type']) {
                case '::':
                    $lines = file($bt[$l]['file']);
                    $i = 0;
                    $callerLine = '';
                    do {
                        $i++;
                        $callerLine = $lines[$bt[$l]['line'] - $i] . $callerLine;
                    } while (stripos($callerLine, $bt[$l]['function']) === false);
                    preg_match('/([a-zA-Z0-9\_]+)::' . $bt[$l]['function'] . '/', $callerLine, $matches);
                    if (!isset($matches[1])) {
                        // must be an edge case. 
                        throw new Exception("Could not find caller class: originating method call is obscured.");
                    }
                    switch ($matches[1]) {
                        case 'self':
                        case 'parent':
                            return get_called_class($bt, $l + 1);
                        default:
                            return $matches[1];
                    }
                // won't get here. 
                case '->':
                    switch ($bt[$l]['function']) {
                        case '__get':
                            // edge case -> get class of calling object 
                            if (!is_object($bt[$l]['object']))
                                throw new Exception("Edge case fail. __get called on non object.");
                            return get_class($bt[$l]['object']);
                        default:
                            return $bt[$l]['class'];
                    }

                default:
                    throw new Exception("Unknown backtrace method type");
            }
    }

}

function pengu_enderror($title, $detail, $end = true)
{
    if (file_exists(static_path() . '/pages/page-warning.php')) {
        ob_end_clean();
        include_once static_path() . '/pages/page-warning.php';
        exit;
    } else {
        ob_end_clean();
        echo "<style>";
        echo "
        .symbol {
        font-size: 0.9em;
        font-family: Times New Roman;
        border-radius: 1em;
        padding: 0em 0.4em .1em 0.4em;
        font-weight: bolder;
        color: white;
        background-color: #4E5A56;
        }
        .icon-error { background: #e64943; font-family: Consolas; }  
        .icon-error:before { content: 'x'; } 
        .notify {
        background-color:#e3f7fc; 
        color:#555; 
        border:.1em solid;
        border-color: #8ed9f6;
        border-radius:10px;
        font-family:Tahoma,Geneva,Arial,sans-serif;
        font-size:1.1em;
        padding:10px 10px 10px 10px;
        margin:10px;
        cursor: default;
        }
        .notify p{padding-left: 32px;}
        .notify-red { background: #ffecec; border-color: #fad9d7; }";

        echo "</style>";
        echo "<div class=\"notify notify-red\"><span class=\"symbol icon-error\"></span> <b>{$title}</b><p>{$detail}</p></div>";
    }
    if ($end)
        exit;
}
