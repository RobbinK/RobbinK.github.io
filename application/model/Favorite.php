<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Favorite.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class Favorite extends Model {

    protected $_table = "abs_favorite";
    private $resGame = array();

    ###########################################
    ##<<<<<<<<<<<<< Other Function ############

    public function have_games() {

        if (current($this->resGame))
            return true;
        else
            return false;
    }

    public function the_game() {
        $current = current($this->resGame);
        next($this->resGame);
        return $current;
    }

    ############################################

    public function getFavouritesGameId($userId) {
        $result = $this->select('game_id')->where(array('user_id' => $userId))->exec();
        $data = array();
        while ($result->fetch())
            $data[] = $result->current()->game_id;
        return $data;
    }

    public function in_myfave($userId, $gameId) {
        $result = $this->select('game_id')->where(array('user_id' => $userId, 'game_id' => $gameId))->exec();
        if ($result->numrows() > 0)
            return true;
        return false;
    }

    public function addtofavorit($userId, $gameId) {
        $nm = $this->select()->where(array('user_id' => $userId, 'game_id' => $gameId))->numrows();
        if ($nm === 0) {
            if ($this->insert(array('user_id' => $userId, 'game_id' => $gameId))->exec())
                return true;
        }
        else if ($nm > 0) {
            if ($this->delete()->where(array('user_id' => $userId, 'game_id' => $gameId))->exec())
                return -1;
            return 0;
        }
        return false;
    }

}