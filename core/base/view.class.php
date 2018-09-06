<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: view.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */


class TemplateView {

    protected $variables = array();
    protected $_loadview = true;
    protected $_loadedview = false;
    protected $_target; //controller
    protected $_action; 

    public function ViewDispathcher($target, $action) {
        $this->_target = $target;
        $this->_action = $action; 
    }

    public function setCurrentThemesFolder($folderName) {
        direction::setThemesFolder($folderName); 
    }

    public function setCurrentThemeName($themeName) {
        direction::setThemeName($themeName); 
    }

    public function setCurrentViewFolderName($foldername) {
        direction::setViewFolder($foldername); 
    }

    public function setCurrentViewFileName($fileName) {
        direction::setViewFile($fileName); 
    }

    public function setCurrentViewFile_groupFolder($foldername) {
        direction::setViewFile_groupFolder($foldername); 
    }

    function load() {
        global $router, $route;

        if (!$this->_loadview || $this->_loadedview)
            return;

        if (isset($this->variables)) {
            @eval(globals_st($this->variables));   // moarefi variables ha be onvane global jahate dast rasi dar laye haie paini
            extract($this->variables);
        }

        if (file_exists(direction::$currentViewFolderPath . '/call.php'))
            include_once (direction::$currentViewFolderPath . '/call.php');
        elseif (file_exists(direction::$temeplatePath . '/call.php'))
            include_once (direction::$temeplatePath . '/call.php');

        if (file_exists(direction::$currentViewFolderPath . '/functions.php'))
            include_once (direction::$currentViewFolderPath . '/functions.php');
        elseif (file_exists(direction::$temeplatePath . '/functions.php'))
            include_once (direction::$temeplatePath . '/functions.php');

        ob_start();

        //!!!!!!!!!!!!! load controller from template

        if (file_exists(direction::$currentViewFilePath))
            include_once (direction::$currentViewFilePath);

        $content = ob_get_clean();
        echo $content;
    }

    function set($name, $value) {   //set kardanne variable jahate namayesh dar view
        $this->variables[$name] = $value;
    }

}
