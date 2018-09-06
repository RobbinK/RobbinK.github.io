<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: MobileTag.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class MobileTag extends Game_tag {

    private static $instance;

    private static function createInstance() {
        if (!isset(self::$instance)) {
            $classname = get_class();
            self::$instance = new $classname;
        }
    }

    public function select($fields = 'T.*') {
        $Mobile_Device_condition = null;
        if (function_exists('is_android'))
            $Mobile_Device_condition = is_android() ? ' and length(G.game_android_link)>10 ' : ' and length(G.game_ios_link)>10 ';
        return parent::select($fields)
                        ->innerjoin('abs_games_mobile', 'G')
                        ->on("concat(',',G.game_tags,',') like concat('%,',T.id,',%')" . $Mobile_Device_condition)
                        ->groupby('T.id');
    }

}

