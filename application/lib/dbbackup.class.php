<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: dbbackup.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class pengu_dbbackup {

    public $tables = array();
    public $drop_tables = true;
    public $struct_only = false;
    public $comments = true;
    public $backup_dir = '';
    public $fname_format = 'Y_m_d__H_i_s';
    public $error = null;
    public $nl = "\r\n";
    private $pengu_db;

    function __construct($option = array()) {
        $option = array_merge(array(
            'host' => CONFIG_DB_HOST,
            'db' => CONFIG_DB_NAME,
            'user' => CONFIG_DB_USER,
            'pass' => CONFIG_DB_PASSWORD,
            'persist' => false,
            'cachesPath' => ROOT_PATH . '/tmp/cache/mysql',
            'logsPath' => ROOT_PATH . '/tmp/logs/mysql'), $option
        );
        $this->host = $option['host'];
        $this->db = $option['host'];
        $this->pengu_db = new pengu_db($option);
        if (!$this->pengu_db->link()) {
            exit('Could not establish a connection to database!');
        }
    }

    public function execute($fname = null, $compress = false) {
        set_time_limit(0);
        if (!($sql = $this->retrieve())) {
            return false;
        }


        if (!empty($fname)) {
            $fdir = rightchar('/', dirname($fname));
            if (!file_exists($fdir))
                mkdir($fdir);
            $fname = path::get_filename($fname);
        } else {
            $fdir = rightchar('/', $this->backup_dir);
            if (!file_exists($fdir))
                mkdir($fdir);
            $fname = date($this->fname_format) . ($compress && function_exists('gzopen') ? '.sql.gz' : '.sql');
        }
        $s = new _pengu_dbbackup_catch_data($fdir, $fname, $sql, $compress);
        return $s;
    }

    private function getTables() {
        $value = array();
        if (!($result = $this->pengu_db->query('SHOW TABLES')->exec()))
            return false;
        while ($result->fetch()) {
            $t0 = array_values($result->current);
            if (!empty($t0[0])) {
                if (empty($this->tables) || in_array($t0[0], $this->tables)) {
                    $value[] = $t0[0];
                }
            }
        }
        if (!sizeof($value)) {
            $this->error = 'No tables found in database.';
            return false;
        }
        return $value;
    }

    public function dumpTables($table) {
        $value = '';
        $this->pengu_db->query('LOCK TABLES `' . $table . '` WRITE')->exec();
        if ($this->comments) {
            $value .= '# ' . $this->nl;
            $value .= '# Table structure for table `' . $table . '`' . $this->nl;
            $value .= '#' . $this->nl . $this->nl;
        }
        if ($this->drop_tables) {
            $value .= 'DROP TABLE IF EXISTS `' . $table . '`;' . $this->nl;
        }
        if (!($result = $this->pengu_db->query('SHOW CREATE TABLE `' . $table . '`')->exec())) {
            return false;
        }

        $value .= str_replace("\n", $this->nl, $result->current['Create Table']) . ';';
        $value .= $this->nl . $this->nl;
        if (!$this->struct_only) {
            if ($this->comments) {
                $value .= '#' . $this->nl;
                $value .= '# Dumping data for table `' . $table . '`' . $this->nl;
                $value .= '#' . $this->nl . $this->nl;
            }
            $value .= $this->getInserts($table);
        }
        $value .= $this->nl . $this->nl;
        $this->pengu_db->query('UNLOCK TABLES')->exec();
        return $value;
    }

    function getInserts($table) {
        $this->pengu_db->settable($table);
        if (!($result = $this->pengu_db->select()->exec())) {
            return false;
        }

        $fields = array();
        $fres = $this->pengu_db->query("SHOW COLUMNS FROM `{$table}`;")->exec()->allrows();
        foreach ($fres as $info)
            $fields[] = "`" . $info['Field'] . "`";

        $sql = null;
        $rows = array();
        while ($result->fetch()) {
            $current = $result->current;
            foreach ($current as &$v) {
                if (!is_numeric($v))
                    $v = '\'' . str_replace(array("\r\n", "\n"), '\n', addslashes($v)) . '\'';
            }
            $rows[] = '(' . join(',', $current) . ')';
        }

        $step = 1000;
        $ret = null;
        for ($i = 0; $i < ceil(count($rows) / $step); $i++) {
            $start = ($i * $step);
            $srows = array_slice($rows, $start, $step);
            $ret.='INSERT INTO `' . $table . '`(' . join(',', $fields) . ') VALUES' . $this->nl . join(',' . $this->nl, $srows) . ';' . $this->nl;
        }
        return $ret;
    }

    private function retrieve() {
        $value = null;
        if ($this->comments) {
            $value .= '#' . $this->nl;
            $value .= '# MySQL database dump' . $this->nl;
            $value .= '# Created by Penguin Framework , ver. ' . sys_ver . $this->nl;
            $value .= '#' . $this->nl;
            $value .= '# Host: ' . $this->host . $this->nl;
            $value .= '# Generated: ' . date('M j, Y') . ' at ' . date('H:i') . $this->nl;
            $value .= '# PHP version: ' . phpversion() . $this->nl;
            if (!empty($this->database)) {
                $value .= '#' . $this->nl;
                $value .= '# Database: `' . $this->db . '`' . $this->nl;
            }
            $value .= '#' . $this->nl . $this->nl . $this->nl;
        }
        if (!($tables = $this->getTables())) {
            return false;
        }
        foreach ($tables as $table) {
            if (!($table_dump = $this->dumpTables($table))) {
                $this->error = $this->lasterror();
                return false;
            }
            $value .= $table_dump;
        }
        return $value;
    }

}

class _pengu_dbbackup_catch_data {

    private $path;
    private $fname;
    private $compress;
    private $sql;

    function __construct($path, $fname, $sql, $compress = false) {
        $this->path = $path;
        $this->fname = $fname;
        $this->sql = $sql;
        $this->compress = $compress;
    }

    public function saveToFile() {
        $filesrc = rightchar('/', $this->path) . $this->fname;
        if ($this->compress && function_exists('gzopen')) {
            if ($zf = @gzopen($filesrc . '.sql.gz', 'w9')) {
                $filesrc.='.sql.gz';
                gzwrite($zf, $this->sql);
                gzclose($zf);
                return $filesrc;
            } else
                $this->error = 'Can\'t create the zip file.';
        }
        if ($f = fopen($filesrc . '.sql', 'w')) {
            $filesrc.='.sql';
            fwrite($f, $this->sql);
            fclose($f);
            return $filesrc;
        } else
            $this->error = 'Can\'t create the output file.';
        return false;
    }

    public function sql() {
        return $this->sql;
    }

    public function downloadFile() {
        header('Content-disposition: filename=' . $this->fname . '.sql');
        header('Content-type: application/octetstream');
        header('Pragma: no-cache');
        header('Expires: 0');
        echo ($this->compress ? gzencode($this->sql) : $this->sql);
        return true;
    }

}
