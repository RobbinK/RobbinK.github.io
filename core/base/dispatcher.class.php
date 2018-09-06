<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: dispatcher.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */


class dispather
{

    protected $_target; //controller
    protected $_controllerCS; //Controler Class Name ex: GamesController
    protected $_action;
    protected $_model;
    private $params;

    function __construct($target, $params)
    {

        $this->params = $params;
        list($this->_target, $this->_action) = explode(':', $target);
        $this->correctNames($this->_target, $this->_model);
        $this->_controllerCS = $this->_target . 'Controller';

        if (isset($_GET['extime']))
            microtimer::start();
        $this->call();
    }

    private function correctNames(&$controller, &$model = null)
    {
        global $PenguInflector;
        $controller = $PenguInflector->get_plural(ucwords($controller));
        $model = $PenguInflector->get_singular(ucwords($controller));
    }

    function call()
    {
        //////// All Site Content
        //if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && @substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
        //    ob_start("ob_gzhandler");

        ob_start();

        ///<<<<<<<<<<<< Call Controller
        global $DispTarget, $DispAction, $DispModel;
        $DispTarget = $this->_target;
        $DispAction = $this->_action;
        $DispModel = $this->_model;
        direction::init();
        #################################### 
        if (file_exists(siteinfo(SITE_CONTROLS_PATH) . '/booter.php'))
            require_once(siteinfo(SITE_CONTROLS_PATH) . '/booter.php');
        #################################### 
        $objController = new $this->_controllerCS();

        #======================= maintenance Page ========#  
        if (defined('CloseSiteForMaintenance') && CloseSiteForMaintenance === true) {
            global $router;
            $route = $router->getRoute('maintenance');
            if (!empty($route)) {
                $target = $route->getTarget();
                $targetArr = explode(':', $target);
                if (method_exists($objController, $targetArr[1]))
                    $this->_action = $targetArr[1];
            }
        }
        #=================================================#   
        ///////////////////////////////
        //////// OnCallController Event
        $events = event::getEvents(EVENT_OnCallController);
        if (count($events) > 0)
            foreach ($events as $ev) {
                if (count($ev) == 2) {
                    $call = array(new $ev[0], $ev[1]);
                } else {
                    $call = $ev;
                }
                @call_user_func_array($call, array($this->_target, $this->_action));
            }
        ///>>>>>>>>>>>>>>>>>>> 
        if (method_exists($this->_controllerCS, $this->_action))
            call_user_func_array(array($objController, $this->_action), array($this->params));
        else
            die("404:Dispather");

        ////////////////////////////
        //////// OnPreLoadView Event
        $events = event::getEvents(EVENT_OnPreLoadView);
        if (count($events) > 0)
            foreach ($events as $ev) {
                if (count($ev) == 2) {
                    $call = array(new $ev[0], $ev[1]);
                } else {
                    $call = $ev;
                }
                @call_user_func($call);
            }
        ///>>>>>>>>>>>>>>>>>>>

        call_user_func(array($objController, '__LoadView'));
        if (isset($objController->view->_disable_view_loading) && $objController->view->_disable_view_loading == true)
        {
            ob_end_flush();
            exit;
        }
        unset($objController);
        $content = ob_get_clean();

        //>>>>>>>>>>>>>
        ////////////////////////////
        //////// OnLoadView Event

        $events = event::getEvents(EVENT_OnLoadView);

        if (count($events) > 0)
            foreach ($events as $ev) {
                if (count($ev) == 2) {
                    $call = array(new $ev[0], $ev[1]);
                } else {
                    $call = $ev;
                }
                @call_user_func_array($call, array(&$content));
            }
        ///>>>>>>>>>>>>>>>>>>> 
        if (isset($_GET['extime']))
            echo "\n<div style='position: fixed;
z-index: 99999999;
background: #F00;
padding: 4px;
top: 0px;
left: 0px;'><span style='color: #FFF;'>execute time : " . round(microtimer::stop(), 4) . " ms</span></div>\n";
        echo $content;
        ////////////////////////////
        //////// OnShowedView Event
        $events = event::getEvents(EVENT_OnShowedView);
        if (count($events) > 0)
            foreach ($events as $ev) {
                if (count($ev) == 2) {
                    $call = array(new $ev[0], $ev[1]);
                } else {
                    $call = $ev;
                }
                @call_user_func($call, $content);
            }
        ///>>>>>>>>>>>>>>>>>>>        
    }

    public function getTarget()
    {
        return array($this->_target, $this->_action);
    }

}

function __autoload($autoloadCalssName)
{
    global $PenguInflector;
    $dest1 = ROOT_PATH . "/application/lib/" . strtolower($autoloadCalssName) . '.class.php';
    if (file_exists($dest1))
        require_once($dest1);
    _ldmc1($autoloadCalssName);
}