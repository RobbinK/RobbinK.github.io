<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Block.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class Block extends Model {

    protected $_table = "abs_blocks";
    public $_cache_time = CacheExpireTime;
    private $respage;

    public function getBlockByTitle($title, $index = null) {
        $this->select("block_title,block_content")->where(array("lower(block_title)='" . strtolower(input::safe($title)) . "'", 'status' => 1));
        if (UseCache && intval($this->_cache_time))
            $this->cacheable($this->_cache_time, 'contents');
        $data = $this->exec();
        if (!$index)
            return $data->current;
        if (isset($data->current[$index]))
            return $data->current[$index];
    }

    public function getBlockById($id, $index = null) {
        $this->select("block_title,block_content")->where(array('id' => intval($id), 'status' => 1));
        if (UseCache && intval($this->_cache_time))
            $this->cacheable($this->_cache_time, 'contents');
        $data = $this->exec();
        if (!$index)
            return $data->current;
        if (isset($data->current[$index]))
            return $data->current[$index];
    }

}

