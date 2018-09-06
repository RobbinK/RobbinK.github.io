<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: mysqli.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */


include("mysqli_trace.class.php");
define('TMPPATH', 'tmp');

class pengu_mysqli
{

    static private $instance;
    public $handle;
    private $result;
    private $host_name;
    private $host_username;
    private $host_password;
    private $host_db;
    private $host_persist;
    private $executemode;
    public $sql;
    private $sqlplaces;
    private $lastsql;
    private $lastinsid;
    private $lastmysqlerror;
    private $execTime;
    private $result_type;
    private $_tableAlias;
    private $return_array_type = MYSQLI_ASSOC; //MYSQLI_ASSOC ,MYSQLI_BOTH , MYSQLI_NUM
    private $db_caches_path;
    private $db_logs_path;
    private $cache;
    public  $caching;
    private $grabfromcache;
    private $multiquery;
    private $affectToDb = true;
    private $reconnect_try_limit = 10;
    private $lastping;
    #====

    public function __construct($config)
    {
        $this->sql_RenewPlaces();
        $this->set_config($config);
        pengu_mysqli_trace::$logsavepath = $this->db_logs_path;
        # yek nemone koli az in class baraye karbar
        # tavasot  self::$instance ersal mishavad
        //self :: $instance = $this; cancle singlton
    }

    #====

    private function set_config($config)
    {
        # get config  from user
        # az methode linkto($config) / __construct($config)
        # be inja mirese
        $this->host_name = $config['host'];
        $this->host_db = $config['db'];
        $this->host_username = $config['user'];
        $this->host_password = $config['pass'];
        $this->host_persist = $config['persist'];

        if (!isset($config['db_encoding']))
            $this->db_encoding = "utf8";
        elseif ($config['db_encoding'])
            $this->db_encoding = $config['db_encoding'];

        if (isset($config['affecting']) && is_bool($config['affecting']))
            $this->affectToDb = $config['affecting'];
        $this->db_caches_path = $config['cachesPath'];

        $this->db_logs_path = $config['logsPath'];
        if (DEVELOP && !file_exists($this->db_logs_path))
            rmkdir($this->db_logs_path);
    }

    #====

    public function renew()
    {
        $this->sql_RenewPlaces();
    }

    #====

    public function settable($tablename, $alias = null)
    {
        if (!empty($tablename))
            $this->_table = $tablename;
        if (!empty($tablename))
            $this->_tableAlias = $alias;
        return $this;
    }

    public function gettable($quoted = false)
    {
        if ($quoted && strpos($this->_table, '(') === false)
            return "`{$this->_table}`";
        return $this->_table;
    }

    #====#

    public function alias($alias)
    {
        if (!empty($alias) && is_string($alias))
            $this->_tableAlias = $alias;
        return $this;
    }

    #====   

    public function setrestype($restype)
    {
        if (in_array($restype, array(MYSQLI_ASSOC, MYSQLI_BOTH, MYSQLI_NUM)))
            $this->return_array_type = $restype;
        return $this;
    }

    #====   

    private function connect()
    {
        if (empty($this->host_name) || empty($this->host_db)) {
            $this->handle = null;
            return false;
        }
        if ($this->host_persist)
            $this->handle = @mysqli_connect((strpos($this->host_name, 'p:') ? $this->host_name : 'p:' . $this->host_name), $this->host_username, $this->host_password, $this->host_db);
        else
            $this->handle = @mysqli_connect($this->host_name, $this->host_username, $this->host_password, $this->host_db);

        if (!@mysqli_ping($this->handle)) {
            $this->handle = null;
            return false;
        }
        if (isset($this->db_encoding))
            if (!@mysqli_set_charset($this->handle, $this->db_encoding)) {
                printf("Error loading character set {$this->db_encoding}: %s\n", error);
                exit;
            }
        return true;
    }

    #====
    # inja olgoye singelton piyade sazi shode
//    public static function link($config) {
//        if (!(self :: $instance instanceof mysql)) {
//            new pengu_mysqli($config);
//            # va be donbale on dar
//            # __construct moteghayere
//            # $instance=$this mishavad
//        }
//        return self :: $instance;
//    }
//    
    #====
    //alias connect() function

    public function link()
    {
        return $this->connect();
    }

    #====

    public function ping()
    {
        static $ping;
        if (!isset($ping))
            $ping = true;
        if ((time() - $this->lastping) < 5)
            return $ping;

        if (is_null($this->handle)) {
            if (!$this->connect()) {
                $this->lastping = time();
                $ping = false;
                return false;
            }
        }
        $ping = @mysqli_ping($this->handle);
        $this->lastping = time();
        return $ping;
    }

    #====

    private function islink()
    {
        return (!!$this->handle);
    }

    #====

    public static function safe($value, $strict = false)
    {
        if ($strict && method_exists('input', 'safe'))
            return input::safe($value, true, false);
        else
            if (method_exists('input', 'sqlescape'))
                return input::sqlescape($value);
        global $pengu_dbhandle;
        return mysqli_real_escape_string($pengu_dbhandle, $value);
    }

    #==== 

    public static function marge($firstAtt, &$condition, $endAtt)
    {
        if (!empty($condition))
            $condition = ' ' . trim(' ' . $firstAtt . ' ' . $condition . ' ' . $endAtt . ' ') . ' ';
    }

    #====

    public static function sqlToCount($sql, $field = '*')
    {
        if ($field != "'0'")
            $field = "count({$field}) as `count`";
        $sql = (preg_replace("#select\s([\s\S]*?\s*)\sfrom#i", "select {$field} from", $sql));
        $sql = preg_replace('#order\s*by\s*.*#i', '', $sql);
        $sql = preg_replace('#limit\s*\d+.*#i', '', $sql);
        return $sql;
    }

    public static function sqlToSum($sql, $field)
    {
        $sql = (preg_replace("#select\s([\s\S]*?\s*)\sfrom#i", "select sum({$field}) as `sum` from", $sql));
        $sql = preg_replace('#order\s*by\s*.*#i', '', $sql);
        $sql = preg_replace('#limit\s*\d+.*#i', '', $sql);
        return $sql;
    }

    public static function sqlToMax($sql, $field)
    {
        return (preg_replace("#select\s([\s\S]*?\s*)\sfrom#i", "select max({$field}) as `max` from", $sql));
        $sql = preg_replace('#order\s*by\s*.*#i', '', $sql);
        $sql = preg_replace('#limit\s*\d+.*#i', '', $sql);
        return $sql;
    }

    public static function sqlToMin($sql, $field)
    {
        return (preg_replace("#select\s([\s\S]*?\s*)\sfrom#i", "select min({$field}) as `min` from", $sql));
        $sql = preg_replace('#order\s*by\s*.*#i', '', $sql);
        $sql = preg_replace('#limit\s*\d+.*#i', '', $sql);
        return $sql;
    }

    #====

    public function limit($start, $lenght = -1)
    {
        if (!empty($this->sql)) {
            $this->sql = preg_replace('#limit\s*\d+\s*,?\s*\d*#i', '', $this->sql);
            if ($this->executemode != 'noneresult') {
                //filter
                if (is_string($start)) {
                    $start = preg_replace('/limit/i', null, $start);
                    $start = self::safe($start);
                    $start = trim($start);
                }

                if ($lenght > -1) {
                    if (is_numeric($start))
                        $this->sql_setplace('Qlimit', " limit {$start},$lenght");
                } else {
                    if (is_numeric($start))
                        $this->sql_setplace('Qlimit', " limit {$start}");
                }
            }
        }
        return $this;
    }

    #====

    public function orderby($str, $replace = true)
    {
        if (!empty($this->sql)) {
            $str = trim($str);
            if (!empty($str)) {
                if (!$replace) {
                    preg_match('#order\s*by\s*(.*)#i', $this->sql_getplace('Qorderby'), $match);
                    if (isset($match[1]) && !empty($match[1]))
                        $str = ltrim($match[1], ',') . ',' . $str;
                }
                $this->sql_setplace('Qorderby', " order by {$str}");
            }
            return $this;
        }
        return false;
    }

    #====

    public function groupby($str, $replace = true)
    {
        if (!empty($this->sql)) {
            $str = trim($str);
            if (!empty($str)) {
                if (!$replace) {
                    preg_match('#group*by\s*(.*)#i', $this->sql_getplace('Qgroupby'), $match);
                    if (isset($match[1]) && !empty($match[1]))
                        $str = ltrim($match[1], ',') . ',' . $str;
                }
                $this->sql_setplace('Qgroupby', " group by {$str}");
            }
            return $this;
        }
        return false;
    }

    #====

    public function having($condparams, $opration = null, $replace = true, $safe = false)
    {
        $conditions = $this->condition($condparams, $opration, $safe);
        if (!empty($conditions)) {
            if (!$this->sql_getplace('Qtype'))
                $this->select();
            if (!$replace) {
                $oldQconds = $this->sql_getplace('Qhaving');
                $oldQconds = str_replace(array('having', 'HAVING'), null, $oldQconds);
                $oldQconds = trim($oldQconds);
                self::marge(null, $oldQconds, 'and');
                $this->sql_setplace('Qhaving', 'having ' . $oldQconds . $conditions);
            } else
                $this->sql_setplace('Qhaving', 'having' . $conditions);
        }
        return $this;
    }

    #====

    public function orderbyRand($str)
    {
        if (!empty($this->sql)) {
            $str = trim($str);
            if (!empty($str))
                $this->sql_setplace('Qorderby', " order by rand()");
            return $this;
        }
        return false;
    }

    #====

    public function getcount()
    {
        $return = false;
        if ($this->sql) {
            $SQL = self::sqlToCount($this->sql, '*');
            $res = @mysqli_query($this->handle, $SQL);

            if (!$res || mysqli_num_rows($res) != 1) {
                $SQL = self::sqlToCount($this->sql, "'0'");
                $res = @mysqli_query($this->handle, $SQL);
                if (!$res) {
                    $SQL = $this->sql;
                    $res = @mysqli_query($this->handle, $SQL);
                    $return = $res ? @mysqli_num_rows($res) : false;
                } else
                    $return = @mysqli_num_rows($res);
            } else {
                $row = @mysqli_fetch_assoc($res);
                $return = intval(@$row['count']);
            }
            $error = @mysqli_error($this->handle);
            $this->sql_log("<font style='color:gray'>/* getcount() => return : {$return} */</font> &nbsp;&nbsp; " . $SQL, $error);
            @mysqli_free_result($res);
        }
        return $return;
    }

    #====

    public function getsum($field)
    {
        $return = false;
        if ($this->sql) {
            $SQL = self::sqlToSum($this->sql, $field);
            $res = @mysqli_query($this->handle, $SQL);
            if ($res && mysqli_num_rows($res) == 1) {
                $row = @mysqli_fetch_assoc($res);
                $return = (int)$row['sum'];
            }
            @mysqli_free_result($res);
            $error = @mysqli_error($this->handle);
            $this->sql_log("<font style='color:gray'>/* getsum() => return : {$return} */</font> &nbsp;&nbsp; " . $SQL, $error);
        }
        return $return;
    }

    #====

    public function getmin($field)
    {
        $return = false;
        if ($this->sql) {
            $SQL = self::sqlToMin($this->sql, $field);
            $res = @mysqli_query($this->handle, $SQL);
            if ($res && mysqli_num_rows($res) == 1) {
                $row = @mysqli_fetch_assoc($res);
                $return = (int)$row['min'];
            }
            @mysqli_free_result($res);
            $error = @mysqli_error($this->handle);
            $this->sql_log("<font style='color:gray'>/* getmin() => return : {$return} */</font> &nbsp;&nbsp; " . $SQL, $error);
        }
        return $return;
    }

    #====

    public function getmax($field)
    {
        $return = false;
        if ($this->sql) {
            $SQL = self::sqlToMax($this->sql, $field);
            $res = @mysqli_query($this->handle, $SQL);
            if ($res && mysqli_num_rows($res) == 1) {
                $row = @mysqli_fetch_assoc($res);
                $return = (int)$row['max'];
            }
            @mysqli_free_result($res);
            $error = @mysqli_error($this->handle);
            $this->sql_log("<font style='color:gray'>/* getmax() => return : {$return} */</font> &nbsp;&nbsp; " . $SQL, $error);
        }
        return $return;
    }

    #====

    public function begin()
    {
        if ((!isset($this->handle->transactionStatus) || $this->handle->transactionStatus != 'began')   && mysqli_autocommit($this->handle, false)) {
            $this->handle->transactionStatus = 'began';
            $this->executemode = 'execcommant';
            $this->lastsql = 'autocommit(false)';
            $this->sql_trace();
            return $this;
        }
        return false;
    }

    public function commit()
    {
        if ((isset($this->handle->transactionStatus) && $this->handle->transactionStatus == 'began') && mysqli_commit($this->handle)) {
            $this->handle->transactionStatus = 'commited';
            $this->executemode = 'execcommant';
            $this->lastsql = 'COMMIT';
            $this->sql_trace();
            return $this;
        }
        return false;
    }

    public function rollback()
    {
        if ((isset($this->handle->transactionStatus) && $this->handle->transactionStatus == 'began') && mysqli_commit($this->handle)) {
            $this->handle->transactionStatus = 'rollbacked';
            $this->executemode = 'execcommant';
            $this->lastsql = 'ROLLBACK';
            $this->sql_trace();
            return $this;
        }
        return false;
    }

    #====

    public static function condition($params, $opration = null, $safe = false)
    {
        $conditions = null;
        if (is_array($params)) {
            if (empty($opration))
                $opration = 'and';
            foreach ($params as $key => $val) {
                $prekey = null;
                if (strpos($key, '.')) { // Ex: C.id
                    $prekey = substr($key, 0, strpos($key, '.') + 1);
                    $key = substr($key, strpos($key, '.') + 1);
                }

                if (is_array($val)) {
                    #-------------------------------------
                    # amalgar dar araye moshakhas mishavad
                    # 'foo'=>array('blah','<>');
                    #-------------------------------------
                    $opr = isset($val[1]) ? $val[1] : '=';
                    $conditions[] = $prekey . "`" . $key . "`" . $opr . ((is_numeric($val[0]) && !is_string($val[0])) ? $val[0] : "'" . self::safe($val[0], $safe) . "'");
                } else
                    if (is_string($key) && (is_string($val) || is_numeric($val))) {
                        $key = trim($key);
                        $val = self::safe($val, $safe);
                        $conditions[] = $prekey . "`" . $key . "`=" . ((is_numeric($val) && !is_string($val)) ? $val : "'" . $val . "'");
                    } else
                        if (!is_string($key) && is_string($val) && !empty($val)) {
                            if (preg_match("/and|or/i", $val))
                                $val = '(' . $val . ')';
                            $conditions[] = $val;
                        }
            }
            $conditions = @join(" " . $opration . " ", $conditions);
        } else
            if (is_string($params)) {
                if (!empty($opration))  //i.e condition('id',2);
                    $conditions = self::condition(array($params => $opration));
                else
                    $conditions = $params;
            }
        return $conditions;
    }

    #====

    public function query($sql)
    {
        $this->sql = $sql;
        $this->multiquery = false;
        return $this;
    }

    public function multiquery($multisql)
    {
        $this->multiquery = true;
        $this->sql = $multisql;
        return $this;
    }

    public function more_result()
    {
        if ($this->multiquery && @mysqli_more_results($this->handle))
            return true;
        return false;
    }

    public function next_result()
    {
        if ($this->multiquery) {
            $this->lastinsid = null;
            $this->lastmysqlerror = null;
            mysqli_next_result($this->handle);
            $result = mysqli_store_result($this->handle);
            if (is_bool($result)) {
                $this->result_type = 'wor'; //without result  
                if ($this->sql_trace())
                    return false;
                if ($insid = @mysqli_insert_id($this->handle))
                    $this->lastinsid = $insid;
                return $this->affectedrows(); // update , insert , delete 
            } else { //else if (is_resource($result)) {
                $asMysqliResult = true;
                $this->result = $result;
                $this->result_type = 'wr'; //with result  
                $obj = new pengu_mysqli_result($this->result, $this->return_array_type, $this->lastsql);
                $this->free_result();
                return $obj;
            }
        }
    }

    #====

    public function select($field = '*')
    {
        $this->sql_RenewPlaces();
        $this->sql_setplace('Qtype', 'select');
        $this->sql_setplace('Qchoice', $field);
        $this->sql_setplace('Qfrom', 'from');
        $this->sql_setplace('Qtable', $this->gettable(true));
        $this->sql_setplace('QtableAlias', $this->_tableAlias);
        return $this;
    }

    #====

    public function join($table, $alias = null, $joinType = null)
    {
        $oldjoin = $this->sql_getplace('Qjoin');
        self::marge(null, $oldjoin, "  ");

        $this->sql_setplace('Qjoin', $oldjoin . "{$joinType} join `" . $table . "` {$alias}");
        return $this;
    }

    public function on($cond)
    {
        $oldjoin = $this->sql_getplace('Qjoin');
        if (!empty($oldjoin)) {
            $this->sql_setplace('Qjoin', $oldjoin . " on (" . $cond . ")");
            return $this;
        }
        return false;
    }

    #====

    public function delete()
    {
        $this->sql_RenewPlaces();
        $this->sql_setplace('Qtype', 'delete');
        $this->sql_setplace('Qchoice', null);
        $this->sql_setplace('Qfrom', 'from');
        $this->sql_setplace('Qtable', "`{$this->_table}`");
        $this->sql_setplace('QtableAlias', null);
        return $this;
    }

    #====

    public function update($params, $safe = false, $ignore_empty_value = false)
    {
        $sql = null;
        if (is_array($params)) {
            foreach ($params as $key => $val) {
                if ($ignore_empty_value && (is_null($val) || $val === ''))
                    continue;
                if (is_array($val))
                    $array1[] = '`' . $key . '`=' . self::safe(join(null, $val), $safe);
                else if (is_numeric($key) && is_string($val))
                    $array1[] = $val;
                else {
                    $val = self::safe($val, $safe);
                    $array1[] = '`' . trim($key) . '`=' . (validate::_is_price($val) ? convert::PriceToInt($val) : ((is_numeric($val) && !is_string($val)) ? $val : "'" . $val . "'"));
                }
            }
            $sql = join(',', $array1);
        } else
            $sql = $params;

        $this->sql_RenewPlaces();
        $this->sql_setplace('Qtype', 'update');
        $this->sql_setplace('Qtable', "`{$this->_table}`");
        $this->sql_setplace('QtableAlias', $this->_tableAlias);
        $this->sql_setplace('Qset', 'set');
        $this->sql_setplace('Qset_params', $sql);
        return $this;
    }

    #====

    public function insert($params, $safe = false)
    {
        $sql = null;
        if (is_array($params)) {
            foreach ($params as $key => $val) {
                if (is_array($val)) {
                    $keys[] = '`' . $key . '`';
                    $vals[] = self::safe(join(null, $val), $safe);
                } else if (!is_string($key) && is_string($val)) {
                    $pos = strpos($val, '=');
                    $keys[] = '`' . trim(substr($val, 0, $pos), '`') . '`';
                    $vals[] = substr($val, $pos + 1);
                } else {
                    $val = self::safe($val, $safe);
                    $keys[] = '`' . trim($key) . '`';
                    $vals[] = (validate::_is_price($val) ? convert::PriceToInt($val) : ((is_numeric($val) && !is_string($val)) ? $val : "'" . $val . "'"));
                }
            }
            $sql = ' (' . join(',', $keys) . ') values (' . join(',', $vals) . ') ';
        } else
            $sql = $params;
        $this->sql_RenewPlaces();
        $this->sql_setplace('Qtype', 'insert');
        $this->sql_setplace('Qinto', 'into');
        $this->sql_setplace('Qtable', "`{$this->_table}`");
        $this->sql_setplace('Qset_params', $sql);
        return $this;
    }

    #====

    public function where($condparams, $opration = null, $replace = true, $safe = false)
    {
        $conditions = $this->condition($condparams, $opration, $safe);
        if (!empty($conditions)) {
            if (!$this->sql_getplace('Qtype'))
                $this->select();
            $this->sql_setplace('Qwhere', 'where');
            if (!$replace) {
                $oldQconds = $this->sql_getplace('Qcond');
                self::marge(null, $oldQconds, 'and');
                $this->sql_setplace('Qcond', $oldQconds . $conditions);
            } else
                $this->sql_setplace('Qcond', $conditions);
        }
        return $this;
    }

    #====

    public function __call($name, $arguments)
    {
        if (preg_match("/findby(.*)/i", $name, $match)) {
            if (!$this->validateBeInPlaceArray(array('Qtype')))
                $this->select('*');  // ghablan dastore select() nazade
            if (!empty($match[1]))
                $this->where(array($match[1] => $arguments[0]));
            return $this;
        }

        if (preg_match("/(.*)join/i", $name, $match)) {  // innerjoin - leftjoin - rightjoin
            if (!empty($match[1])) {
                $alias = isset($arguments[1]) ? $arguments[1] : null;
                $this->join($arguments[0], $alias, $match[1]);
                return $this;
            }
        }
    }

    #====

    public function cacheable($cacheTime = 86400 /* 1 day */, $folder = null)
    {
        $this->caching = true;
        $this->cacheTime=$cacheTime;
        if (!$this->cache)
            $this->cache = new pengu_sqlcach();
        $this->cache->setPath($this->db_caches_path . leftchar('/', $folder));
        $this->cache->expireTime($this->cacheTime);
        return $this;
    }

    #====

    public function exec(array $params = null)
    {

        if (!empty($this->sql)) {
            $this->lastinsid = null;
            $this->lastmysqlerror = null;
            $this->lastsql = null;
            $asMysqliResult = false;
            $cached = false;

            if (DEVELOP)
                microtimer::start();
            if ($this->multiquery) {
                $this->ping();
                mysqli_multi_query($this->handle, $this->sql);
                $result = mysqli_store_result($this->handle);
                if (is_bool($result)) {
                    $this->result_type = 'wor'; //without result  
                    if ($this->sql_getplace('Qtype') == 'insert' || $insid = @mysqli_insert_id($this->handle))
                        $this->lastinsid = isset($insid) ? $insid : @mysqli_insert_id($this->handle);
                    $ret = $this->affectedrows(); // update , insert , delete 
                } else { //else if (is_resource($result)) {
                    $asMysqliResult = true;
                    $this->result = $result;
                    $this->result_type = 'wr'; //with result  
                }
            } else if ($this->sql_getplace('Qtype') == 'select' || preg_match('/^select\s/i', trim($this->sql)) || preg_match('/^show\s/i', trim($this->sql))) {
                $this->result_type = 'wr'; //with result 

                if ($this->caching) {
                    $this->cache->setKey(md5($this->sql));
                    $cached = $this->cache->isCached();
                    if (!$cached) {
                        // wr: if didn't cache
                        $this->ping();
                        $this->result = mysqli_query($this->handle, $this->sql);
                        $asMysqliResult = true;
                    } else {
                        // wr: if cached
                        $this->result = unserialize($this->cache->read());
                        $this->grabfromcache = true; // report for mysql log 
                    }
                } else {
                    // wr: if cachable = false
                    $this->ping();
                    $this->result = mysqli_query($this->handle, $this->sql);
                    $asMysqliResult = true;
                }
            } else {
                // if not "select"
                $result = false;
                if ($this->affectToDb) {
                    $this->ping();
                    $result = mysqli_query($this->handle, $this->sql);
                }
                if (is_bool($result)) {
                    $this->result_type = 'wor'; //without result  
                    if ($this->sql_getplace('Qtype') == 'insert' || $insid = @mysqli_insert_id($this->handle))
                        $this->lastinsid = isset($insid) ? $insid : @mysqli_insert_id($this->handle);
                    $ret = $this->affectedrows(); // update , insert , delete 
                } else if (is_resource($result)) {
                    $asMysqliResult = true;
                    $this->result = $result;
                    $this->result_type = 'wr'; //with result  
                }
            }
            $this->lastsql = $this->sql;
            if (DEVELOP)
                $this->execTime = microtimer::stop(); // report for mysql log

            if ($this->sql_trace())    # if error
                $ret = false;

            if (isset($ret)) {
                $this->sql_RenewPlaces();
                return $ret;
            }

            if ($this->result_type == 'wr') {
                $obj = new pengu_mysqli_result($this->result, $this->return_array_type, $this->lastsql);
                //if ($asMysqliResult)
                //  $this->free_result();
                if ($this->caching && !$cached) {
                    $this->cache->write(serialize($obj->allrows()));
                }
                $this->sql_RenewPlaces();
                return $obj;
            }
        }
    }

    public function errorno()
    {
        return mysqli_errno($this->handle);
    }

    #====

    public function lasterror()
    {
        return $this->lastmysqlerror;
    }

    #====

    public function affectedrows()
    {
        if ($this->handle)
            return @mysqli_affected_rows($this->handle);
    }

    #====

    public function numrows()
    {
        if (is_array($this->result))
            return count($this->result);
        if ($this->result)
            return @mysqli_num_rows($this->result);
        else if (!empty($this->sql))
            return $this->getcount();
        return false;
    }

    public function found()
    {
        if ($this->numrows() > 0)
            return true;
        return false;
    }

    #====

    public function min($field, $as = 'min')
    {
        $qchoice = $this->sql_getplace('Qchoice');
        if (!empty($qchoice))
            $newfields = array($qchoice, "min(`{$field}`) as `{$as}`");
        else
            $newfields = array("min(`{$field}`) as `{$as}`");
        $newfields = join(',', $newfields);
        $this->sql_setplace('Qtype', 'select');
        $this->sql_setplace('Qchoice', $newfields);
        $this->sql_setplace('Qfrom', 'from');
        $this->sql_setplace('Qtable', $this->gettable(true));
        $this->sql_setplace('QtableAlias', $this->_tableAlias);
        return $this;
    }

    #====

    public function max($field, $as = 'max')
    {
        $qchoice = $this->sql_getplace('Qchoice');
        if (!empty($qchoice))
            $newfields = array($qchoice, "max(`{$field}`) as `{$as}`");
        else
            $newfields = array("max(`{$field}`) as `{$as}`");
        $newfields = join(',', $newfields);
        $this->sql_setplace('Qtype', 'select');
        $this->sql_setplace('Qchoice', $newfields);
        $this->sql_setplace('Qfrom', 'from');
        $this->sql_setplace('Qtable', $this->gettable(true));
        $this->sql_setplace('QtableAlias', $this->_tableAlias);
        return $this;
    }

    #====

    public function sum($field, $as = 'sum')
    {
        $qchoice = $this->sql_getplace('Qchoice');
        if (!empty($qchoice))
            $newfields = array($qchoice, "sum(`{$field}`) as `{$as}`");
        else
            $newfields = array("sum(`{$field}`) as `{$as}`");
        $newfields = join(',', $newfields);
        $this->sql_setplace('Qtype', 'select');
        $this->sql_setplace('Qchoice', $newfields);
        $this->sql_setplace('Qfrom', 'from');
        $this->sql_setplace('Qtable', $this->gettable(true));
        $this->sql_setplace('QtableAlias', $this->_tableAlias);
        return $this;
    }

    #====

    public function lastinsid()
    {
        return $this->lastinsid;
    }

    #====

    public function lastsql()
    {
        return $this->lastsql;
    }

    #====

    private function sql_RenewPlaces()
    {
        $this->multiquery = false;
        $this->caching = false;
        $this->sql = null;
        $this->sqlplaces = array(
            'Qtype' => null,
            'Qchoice' => null,
            'Qfrom' => null,
            'Qinto' => null,
            'Qtable' => null,
            'QtableAlias' => null,
            'Qjoin' => null,
            'Qset' => null,
            'Qset_params' => null,
            'Qinsert' => null,
            'Qwhere' => null,
            'Qcond' => null,
            'Qgroupby' => null,
            'Qhaving' => null,
            'Qorderby' => null,
            'Qlimit' => null
        );
    }

    private function sql_setplace($placement, $statement)
    {
        $this->sqlplaces[$placement] = $statement;
        $this->query(join(' ', array_filter($this->sqlplaces)));
    }

    private function sql_getplace($placement)
    {
        return $this->sqlplaces[$placement];
    }

    #====

    private function validateBeInPlaceArray($PlacesArray)
    {
        foreach ($PlacesArray as $place) {
            if (!array_key_exists($place, array_filter($this->sqlplaces)))
                return false;
        }
        return true;
    }

    #====

    public function sql()
    {
        $sql = $this->sql;
        $this->sql_RenewPlaces();
        return $sql;
    }

    #====

    private function sql_log($sql, $error = null, $exectime = null)
    {
        if (DEVELOP) {
            pengu_mysqli_trace::init(
                array(
                    'sql' => $sql,
                    'error' => $error,
                    'exectime' => $exectime,
                )
            );
        }
    }

    /**
     * @param $mode =lastmysqlerror  check kardane mysql_error
     * va agar error dashte bashe in method meghdare false
     * bar migardanad
     * @param $mode =success  dar sorati ye ye sql ba be
     * sorat sahih ejra gardid estefade shavad / query ejra
     * shode ro baraye mysql debuger ersal mikonad
     */
    private function sql_trace()
    {
        $sqlError = mysqli_error($this->handle);
        $this->lastmysqlerror = $sqlError;
        if (DEVELOP) {
            if ($sqlError) {
                pengu_mysqli_trace::init(
                    array(
                        'sql' => $this->lastsql,
                        'error' => $sqlError,
                        'exectime' => $this->execTime,
                    )
                );
            } else {
                if ($this->executemode == 'execcommant') {
                    # agar sql = begin | commite | rollback
                    pengu_mysqli_trace::init(
                        array(
                            'sql' => $this->lastsql,
                            'executemode' => $this->executemode,
                            'exectime' => $this->execTime,
                        )
                    );
                } else if ($this->result_type == 'wor') {
                    # agar sql = update | insert | delete bashe
                    pengu_mysqli_trace::init(
                        array(
                            'sql' => $this->lastsql,
                            'exectime' => $this->execTime,
                            'executemode' => $this->executemode,
                            'affrows' => $this->affectedrows(),
                        )
                    );
                } else if ($this->result_type == 'wr') {
                    # agar sql= select bashe   
                    pengu_mysqli_trace::init(
                        array(
                            'sql' => $this->lastsql,
                            'exectime' => $this->execTime,
                            'numrows' => $this->numrows(),
                            'cached' => $this->grabfromcache,
                        )
                    );
                }
            }
        }
        return $sqlError;
    }

    #====

    public function free_result()
    {
        if ($this->result && @mysqli_free_result($this->result))
            return true;
        return false;
    }

    public function unlink()
    {
        if ($this->handle && @mysqli_close($this->handle))
            return true;
        return false;
    }

}

#
# End Mysql Class
#

class pengu_mysqli_result
{

    private $currentrow;
    private $iterator;
    private $result_array;
    private $pointerpos;

    public function __get($name)
    {
        switch ($name) {
            case 'current':
                return $this->currentrow;
                break;
            case 'all':
                return $this->allrows();
                break;
            case 'pos':
                return $this->pointerpos;
                break;
        }
    }

    #====

    public function __construct($resource, $return_array_type, $sql = null)
    {
        ////////////// create iterator class
        $this->result_array = $this->resultToArray($resource, $return_array_type);
        $arrobj = new ArrayObject($this->result_array);
        $this->iterator = $arrobj->getIterator();
        //\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
        $this->reset();
        if (!empty($sql))
            $this->sql = $sql;
    }

    #====

    private function resultToArray($result, $return_array_type = MYSQLI_ASSOC)
    {
        if (is_array($result))
            return $result;
        $result_array = array();
        if (isset($result))
            while ($row = @mysqli_fetch_array($result, $return_array_type))
                $result_array[] = $row;
        return $result_array;
    }

    #====

    private function getcurrentrow()
    {
        if (!$this->eof()) {
            $this->currentrow = $this->iterator->current();
            return true;
        }
        return false;
    }

    #====

    public function lastsql()
    {
        if (!empty($this->sql))
            return $this->sql;
    }

    #====

    public function fetch()
    {
        if ($this->getcurrentrow()) {
            @$this->iterator->next();
            $this->pointerpos++;
            return $this->current();
        }
        return false;
    }

    #====

    public function eof()
    {
        if (!$this->iterator->valid() || $this->pointerpos >= $this->numrows())
            return true;
        return false;
    }

    #====

    public function numrows()
    {
        return $this->iterator->count();
    }

    public function found()
    {
        if ($this->numrows() > 0)
            return true;
        return false;
    }

    #====

    public function row($row_number = null)
    {
        #------------------------------------
        # braye bedast avardane recorde N om
        # estefade mishavad
        #------------------------------------
        if (is_numeric($row_number)) {
            $this->seek($row_number); # paresh be record
            return $this->current();
        } else
            return false;
    }

    #====

    public function next()
    {
        @$this->iterator->next();
        $this->pointerpos++;
        return $this->getcurrentrow();
    }

    #====

    public function current()
    {
        #---------------------
        # daryafte recorde jari 
        #---------------------
        if (!is_array($this->currentrow))
            $this->getcurrentrow();
        if (is_array($this->currentrow)) {
            return new pengu_mysqli_result_data($this->currentrow);
        }
    }

    #====

    public function allrows()
    {
        #--------------------------
        # daryafte tamami record ha
        #--------------------------
        if (is_array($this->result_array))
            return $this->result_array;
        return false; // hich resourse nabode
    }

    #====

    private function seek($position)
    {
        #------------------------
        # change internal pointer
        #------------------------

        if ($this->iterator->offsetExists($position)) {
            $this->iterator->seek($position);
            $this->pointerpos = $position;
            $this->getcurrentrow();
            return true;
        }
        return false;
    }

    public function reset()
    {
        #--------------------------
        # reset kardane internal pointer
        #-------------------------- 
        $this->iterator->rewind();
        $this->pointerpos = 0;
        $this->getcurrentrow();
        return $this;
    }

    #====
}

class pengu_mysqli_result_data
{

    function __construct($data)
    {
        foreach ($data as $k => $v) {
            $this->{$k} = $v;
        }
    }

    public function current()
    {
        return $this;
    }

    public function __get($name)
    {
        if (isset($this->{$name}))
            return $this->{$name};
    }

}

#
# End Result Class
#

function condition($params, $opration = null, $safe = false)
{
    return pengu_mysqli:: condition($params, $opration, $safe);
}
