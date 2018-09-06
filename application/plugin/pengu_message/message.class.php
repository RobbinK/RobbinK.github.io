<?php

/*
 * araye message ha ebteda be in sorat sakhte mishavad
 * ################################ Figure 1#############################
 * $_session= array (  $SessionPreName => 
 *                                  array( 'warning'=> 
 *                                              array( 0=>array(  'body'=>'this is msg text'  , 'priority'=>1  , 'live'=>false)
 *                                          'error'=>
 *                                              array(...................)
 *                  )
 * ################################# Figure 2############################
 * va dar zamani baraye namayesh ersal mishavad be shekl zir az noo sakhte mishavad
 *   $productMsgArray = array (   'warning'=>array('this is msg text1' , 'this is msg text2')
 *                                'error' =>array(................)
 *                            )
 * 
 */
define('ALERT_TYPE_MAINTENANCE', 'maintenance');
define('ALERT_TYPE_SUCCESS', 'success');
define('ALERT_TYPE_INFO', 'info');
define('ALERT_TYPE_ERROR', 'error');
define('ALERT_TYPE_WARNING', 'warning');
define('ALERT_OP_WITHCLASS', 'withclass');
define('ALERT_OP_ARRAY', 'array');
define('ALERT_OP_HTMLTAG', 'htmltag');
define('ALERT_OP_SEPRATOR', 'seprator');
define('ALERT_OP_PADDING', 'padding');


if (!headers_sent())
    @session_start();

class pengu_message
{

    private $SessionPreName = 'alerts';
    private $SessionName;
    public $MsgType;
    public $MsgId = null;
    public $MsgPriority = 0;
    public $Msglive = false;
    public $vars;

    function __construct()
    {
        $this->SessionName = $this->SessionPreName;
    }

    public function cmp_method($a, $b)
    {
        $cmp = strnatcasecmp($a['priority'], $b['priority']);
        return ($cmp != 0) ? $cmp : 0;
    }

    private function SetSessionName()
    {
        $this->SessionName = $this->SessionPreName . ($this->MsgId ? '_' . $this->MsgId : null);
    }

    #-------------------------------------
    // Set Message Id
    #-------------------------------------

    public function setId($id)
    {
        if (!empty($id)) {
            $this->MsgId = $id;
            $this->SetSessionName();
        }
    }

    #-------------------------------------
    // Set Message Type
    #-------------------------------------

    public function setType($messageType)
    {
        if (!empty($messageType)) {
            $this->MsgType = $messageType;
        }
    }

    #-------------------------------------
    // check Isset Messege

    public function IsMessage()
    {
        if (!empty($this->MsgType))
            return !empty($_SESSION[$this->SessionName][$this->MsgType]);
        return !empty($_SESSION[$this->SessionName]);
    }

    #-------------------------------------
    // Set Message
    #-------------------------------------

    public function setMessage($_MsgBody)
    {

        $priority = 1;
        if ($this->MsgPriority)
            $priority = $this->MsgPriority;
        else if (isset($_SESSION[$this->SessionName][$this->MsgType])) {

            $last = end($_SESSION[$this->SessionName][$this->MsgType]);
            if (isset($last['priority']))
                $priority = intval($last['priority']) + 1;
        }

        if (!@arrayUtil::array_search($_SESSION[$this->SessionName][$this->MsgType], 'body', $_MsgBody))
            $_SESSION[$this->SessionName][$this->MsgType][] = array('body' => $_MsgBody, 'priority' => $priority, 'live' => $this->Msglive);
        uasort($_SESSION[$this->SessionName][$this->MsgType], array($this, 'cmp_method'));
    }

    #-------------------------------------
    // Get Message
    #-------------------------------------

    public function getMessage()
    {
        //--------------------------------------------------------
        // ba tavajoh be noe darkhast message ha ro entekhab  
        // va dar motegayer $messagesArray mirizad momken ast 
        // entekhab manot be yek goroh bashad ya momken ast
        //  chandin goroh entekhab shode bashad
        if ($this->MsgType) {
            if (!empty($_SESSION[$this->SessionName][$this->MsgType]))
                $messagesArray[$this->MsgType] = $_SESSION[$this->SessionName][$this->MsgType];   // exmp : noe darkhast alert()->warninng();
        } else {
            if (!empty($_SESSION[$this->SessionName]))
                $messagesArray = @$_SESSION[$this->SessionName];  // exmp : noe darkhast  alert();
        }

        if (!isset($messagesArray))
            return false;

        //  if (!arrayUtil::array_filter_recursive($messagesArray))
        //    return false;
        //-------------------------------
        // exam : CleanUp(null,null) or
        // exam : CleanUp('warning',11)
        $this->CleanUp($this->MsgType, $this->MsgId);
        //-----------------------------
        // araye ro filter mikone va 
        // dobare misaze 
        $productMsgArray = array();

        foreach ($messagesArray as $type => $messages) {
            foreach ($messages as $msg)
                $productMsgArray[$type][] = $msg['body'];
        }
        //------------------------------
        if ($this->getp('array')) { // khoroji be sorate araye bashad
            if (!empty($productMsgArray))
                return $productMsgArray;
            return false;
        } else
            return $this->displayMessage($productMsgArray);
    }

    #-------------------------------------
    // display message
    #-------------------------------------

    private function displayMessage($_MessagesArray)
    {
        $return = array();

        $close = null;
        if (!validate::_is_ajax_request() && class_exists('js')) {
            $js = plugin_path() . '/pengu_message/js/common.js';
            if (!js::loaded($js))
                js::load($js, array(JS_MINIFY => true));
            $close = "<a href='#' onclick='pengu_message_close(this);return false;' class='pengu_message_close'><img alt='close'  title='Close this notification' src='" . plugin_url() . "/pengu_message/images/cross_grey_small.png'></a>";
        }

        foreach ($_MessagesArray as $type => $messages) {
            //----- Create Message ------//

            $msghtmlbody = join($this->getp('seprator'), $messages);
            $cssclass = null;
            if ($this->getp('withclass'))
                $cssclass = " class='" . $this->getp($type) . "'";
            if ($this->getp('htmltag')) {

                if ($this->getp('padding') > 0)
                    $return[] = "<div style='padding:" . intval($this->getp('padding')) . "px'><div{$cssclass} style='position:relative;'>{$close}" . $msghtmlbody . "</div></div>";
                else
                    $return[] = "<div{$cssclass}>" . $msghtmlbody . "</div>";
            } else
                $return[] = $msghtmlbody;

            //---------------------------
        }
        if (!$this->getp('htmltag'))
            return join($this->getp('seprator'), $return); // chon tag <div> nadarand bara inke az ham joda beshan
        return join("\n", $return);
    }

    #-------------------------------------
    // Cleen Message
    #-------------------------------------

    public function CleanUp()
    {
        //-----------------------------------------------
        // arraye session be shekl figure 1 mibashad
        // in method message haye live ro clear namikonad
        $sesN = $this->SessionName;

        if (!isset($_SESSION[$sesN]))
            return false;

        if ($this->MsgType) {
            //----------------------------
            //** For : CleanUp('warning');

            if (!isset($_SESSION[$sesN][$this->MsgType]))
                return false;
            $unsetError = false;
            foreach ($_SESSION[$sesN][$this->MsgType] as $key => $msg) {
                if (!$msg['live']) {
                    unset($_SESSION[$sesN][$this->MsgType][$key]);
                } else
                    $unsetError = true;
            }
            if (!$unsetError)
                unset($_SESSION[$sesN][$this->MsgType]);
            //----------------------------    
        } else {
            //----------------------------
            //** For : CleanUp();
            $unsetError = false;
            foreach ($_SESSION[$sesN] as $type => $messeges) { // foreach on msg types
                foreach ($messeges as $key => $msg) {
                    if (!$msg['live']) {
                        unset($_SESSION[$sesN][$type][$key]);
                    } else
                        $unsetError = true;
                }
            }
            if (!$unsetError)
                unset($_SESSION[$sesN]);

            //----------------------------
        }
    }

    public function CleanUpStrict()
    {
        //---------------------------------------------        
        // baraye message hai ke live hastan az in method
        // estefade mishe
        // arraye session be shekl figure 1 mibashad
        $sesN = $this->SessionName;

        if (!isset($_SESSION[$sesN]))
            return false;

        if ($this->MsgType) {
            //----------------------------
            //** For : CleanUp('warning');

            if (!isset($_SESSION[$sesN][$this->MsgType]))
                return false;
            else
                unset($_SESSION[$sesN][$this->MsgType]);
            //----------------------------            
        } else {
            //----------------------------
            //** For : CleanUp();

            foreach ($_SESSION[$sesN] as $type => $messeges) // foreach on msg types 
                unset($_SESSION[$sesN][$type]);
            //----------------------------
        }
    }

    private function getp($name)
    {
        if (isset($this->vars[$name]))
            return $this->vars[$name];
        else if (MsgOptions::get($name))
            return MsgOptions::get($name);
        else {
            $css = MsgOptions::get('css');
            if (isset($css[$name]))
                return $css[$name];
        }
    }

}

class MsgOptions
{

    private static $ops = array(
        'css' => array(
            'info' => 'al-info',
            'success' => 'al-success',
            'warning' => 'al-warning',
            'error' => 'al-error',
            'maintenance' => 'al-maintenance'
        ),
        ALERT_OP_HTMLTAG => true,
        ALERT_OP_WITHCLASS => true,
        ALERT_OP_ARRAY => false,
        ALERT_OP_SEPRATOR => '<br/>',
        ALERT_OP_PADDING => 7
    );

    static function set($vars, $values = null)
    {
        $data = array();
        if (is_array($vars) && is_array($values))
            $data = array_combine($vars, $values);
        else
            if (is_array($vars) && $values === null)
                $data = $vars;
            else
                if ($vars !== null && $values !== null)
                    $data = array($vars => $values);

        foreach ($data as $k => $v) {
            if (isset(self::$ops[$k]))
                self::$ops[$k] = $v;
        }
    }

    static function get($name)
    {
        if (isset(self::$ops[$name])) {
            return self::$ops[$name];
        }
    }

}

#--------------------------
// MSG Axulary Classes
#--------------------------

class SetMsgAuxClass
{

    private $instance;
    private $MsgBody;
    private $forceShow;

    function __construct($_MsgType, $_MsgBody)
    {
        $this->instance = new pengu_message;
        $this->instance->MsgType = $_MsgType;
        $this->MsgBody = $_MsgBody;
    }

    public function Id($_MsgId)
    {
        $this->instance->setId($_MsgId);
        return $this;
    }

    public function priority($PriorityNumber)
    {
        $this->instance->MsgPriority = $PriorityNumber;
        return $this;
    }

    public function live()
    {
        $this->instance->Msglive = true;
        return $this;
    }

    public function alert()
    {
        //-----------------------------------------
        // Force Alert / bad az set kardane message
        $this->forceShow = true;
        $this->setmsg();
        $obj = new ShowMsgAuxCLass();
        $obj->instance->MsgType = $this->instance->MsgType;
        $obj->instance->setId($this->instance->MsgId);
        return $obj;
    }

    private function setmsg()
    {
        $this->instance->setMessage($this->MsgBody);
    }

    function __destruct()
    {
        if (!$this->forceShow) //ghablan set shode ast
            $this->setmsg();
    }

}

class ShowMsgAuxCLass
{

    public $instance;

    function __construct()
    {
        $this->instance = new pengu_message;
    }

    function showerror()
    {
        $args = func_get_args();
        return $this->__call(__FUNCTION__, $args);
    }

    function showwarning()
    {
        $args = func_get_args();
        return $this->__call(__FUNCTION__, $args);
    }

    function showinfo()
    {
        $args = func_get_args();
        return $this->__call(__FUNCTION__, $args);
    }

    function showsuccess()
    {
        $args = func_get_args();
        return $this->__call(__FUNCTION__, $args);
    }

    function showmaintenance()
    {
        $args = func_get_args();
        return $this->__call(__FUNCTION__, $args);
    }

    public function options($options)
    {
        if (isset($options[ALERT_OP_WITHCLASS]))
            $this->instance->vars[ALERT_OP_WITHCLASS] = $options[ALERT_OP_WITHCLASS];

        if (isset($options[ALERT_OP_ARRAY]))
            $this->instance->vars[ALERT_OP_ARRAY] = $options[ALERT_OP_ARRAY];

        if (isset($options[ALERT_OP_HTMLTAG]))
            $this->instance->vars[ALERT_OP_HTMLTAG] = $options[ALERT_OP_HTMLTAG];

        if (isset($options[ALERT_OP_SEPRATOR]))
            $this->instance->vars[ALERT_OP_SEPRATOR] = $options[ALERT_OP_SEPRATOR];

        if (isset($options[ALERT_OP_PADDING]))
            $this->instance->vars[ALERT_OP_PADDING] = $options[ALERT_OP_PADDING];
        return $this;
    }

    public function getResult()
    {
        return $this->instance->getMessage();
    }

    function __call($name, $arguments)
    {
        $preg = preg_match('/show(.*)/i', $name, $match);
        if (!empty($match[1])) {
            $this->instance->setType($match[1]);
            if (isset($arguments[0]))
                $this->options($arguments[0]);
            return $this;
        }
    }

    function __toString()
    {
        return (string)$this->instance->getMessage();
    }

}

#--------------------------

function pwarning($msgBody)
{
    if (empty($msgBody))
        return false;
    return new SetMsgAuxClass('warning', $msgBody);
}

if (!function_exists('warning')) {

    function warning($msgBody)
    {
        return pwarning($msgBody);
    }

}

function perror($msgBody)
{
    if (empty($msgBody))
        return false;
    return new SetMsgAuxClass('error', $msgBody);
}

if (!function_exists('error')) {

    function error($msgBody)
    {
        return perror($msgBody);
    }

}

function pinfo($msgBody)
{
    if (empty($msgBody))
        return false;
    return new SetMsgAuxClass('info', $msgBody);
}

if (!function_exists('info')) {

    function info($msgBody)
    {
        return pinfo($msgBody);
    }

}

function psuccess($msgBody)
{
    if (empty($msgBody))
        return false;
    return new SetMsgAuxClass('success', $msgBody);
}

if (!function_exists('success')) {

    function success($msgBody)
    {
        return psuccess($msgBody);
    }

}

function pmaintenance($msgBody)
{
    if (empty($msgBody))
        return false;
    return new SetMsgAuxClass('maintenance', $msgBody);
}

if (!function_exists('maintenance')) {

    function maintenance($msgBody)
    {
        return pmaintenance($msgBody);
    }
}

function CleanUp($MessageId = null, $MessageType = null)
{
    $obj = new pengu_message;
    $obj->setId($MessageType);
    $obj->setId($MessageId);
    $obj->CleanUp();
}

function CleanUpStrict($msgId = null, $msgType = null)
{
    $obj = new pengu_message;
    $obj->setType($msgType);
    $obj->setId($msgId);
    $obj->CleanUpStrict();
}

function alert($messageId = null)
{
    $obj = new ShowMsgAuxCLass();
    $obj->instance->setId($messageId);
    return $obj;
}

function isAlert($msgId = null, $msgType = null)
{
    $obj = new pengu_message;
    $obj->setType($msgType);
    $obj->setId($msgId);
    return $obj->IsMessage();
}
