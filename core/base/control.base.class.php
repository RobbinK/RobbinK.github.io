<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: control.base.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */


class Controller {

    protected $_target; //controller
    protected $_action;
    protected $_model = 'none';
    protected $Template;
    public $view;
    private $cache;

    function __construct() {
        global $DispTarget, $DispAction, $DispModel;

        ####################################
        require_once(siteinfo('lib_path') . '/booter.php');
        require_once(siteinfo('plugin_path') . '/booter.php');
        ####################################

        $this->_target = $DispTarget;
        $this->_action = $DispAction;


        ##################### if _model dar controller set nashode bod
        if ($this->_model != null && $this->_model == 'none')
            $this->_model = $DispModel;

        #################### if _model dar controller  set shode bod
        if (isset($this->_model) && !empty($this->_model)) {
            if (is_string($this->_model))
                $this->{$this->_model} = new $this->_model;
            else if (is_array($this->_model))
                foreach ($this->_model as $model)
                    $this->{$model} = new $model;
        }

        $this->Template = new TemplateView();
        $this->Template->ViewDispathcher($DispTarget, $DispAction);
        $this->view = new TemplateViewOption();
    }

    public function set($VarName, $VarValue = null) {  // set kardane variable jahate namayesh dar view
        $this->view->_to_view($VarName, $VarValue);
    }

    public function extract($var) {  // set kardane variable jahate namayesh dar view
        $this->view->_to_view($var);
    }

    public function MapViewThemesFolder($folderName) {
        $this->Template->setCurrentThemesFolder($folderName);
    }

    public function MapViewTemeplateName($templateName) {
        $this->Template->setCurrentThemeName($templateName);
    }

    public function MapViewFolder($folderName) {
        $this->Template->setCurrentViewFolderName($folderName);
    }

    public function MapViewFileName($FileName) {
        $this->Template->setCurrentViewFileName($FileName);
    }

    public function MapViewFile_groupFolder($folderName) {
        $this->Template->setCurrentViewFile_groupFolder($folderName);
    }

    function __LoadView() {
        if (isset($this->view->_disable_view_loading) && $this->view->_disable_view_loading == true)
            return;
        if (!empty($this->view)) {
            foreach ($this->view as $key => $var)
                $this->Template->set($key, $var);
        }
        $this->Template->load();
    }

    function __destruct() {
        #################### close this  model handle
        if (is_string($this->_model)) {
            if (isset($this->{$this->_model}))
                $this->{$this->_model}->discard();
        }
        else if (is_array($this->_model)) {
            foreach ($this->_model as $model)
                if (isset($this->{$model}))
                    $this->{$model}->discard();
        }
    }

}

class TemplateViewOption {

    public function exists() {
        if (function_exists('siteinfo') && file_exists(siteinfo('viewfile_path')))
            return true;
        return false;
    }

    public function _to_view($vars, $values = null) {
        if (is_string($vars))
            $this->{$vars} = $values;
        else if (is_array($vars) && is_array($values)) {
            foreach ($vars as $key => $val)
                $this->{$val} = $values[$key];
        } else if ((is_array($vars) || is_object($vars) ) && $values === null) {
            foreach ($vars as $key => $val)
                $this->{$key} = $val;
        }
    }

    public function disable() {
        $this->_disable_view_loading = true;
    }

    public function enable() {
        $this->_disable_view_loading = false;
    }

}