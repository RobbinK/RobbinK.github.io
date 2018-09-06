<?PHP
class db {
var $host;
var $user;
var $pass;
var $database;
var $connection_id;
var $result;
var $record_row;
function db($host, $user, $pass, $database){
$this->host = $host;
$this->user = $user;
$this->pass = $pass;
$this->database = $database;
$this->syncsql();
}

function syncsql(){
$this->connection_id = new mysqli($this->host, $this->user, $this->pass, $this->database);
mysqli_set_charset($this->connection_id,"utf8");
if (!$this->connection_id){
$this->fatal_error("Error connecting to database.");
return FALSE;
}

// if (!mysql_select_db($this->database, $this->connection_id)){
// $this->fatal_error("Error finding database!");
// return FALSE;
// }
return TRUE;
}

function query($the_query){	  
$this->result = $this->connection_id->query($the_query);
if (!$this->result){
$this->fatal_error("MySQL Query Error: $the_query");
}
return $this->result;
}

function fetch_rows($result=""){
if ($result == ""){
$result = $this->result;
}
	  
$array = array();
      
for ($i = 0, $d = @mysqli_num_rows($result); $i < $d; $i++){
$array[] = mysqli_fetch_assoc($result);
}
return $array;
}

function escape($var){
	return mysqli_real_escape_string($this->connection_id,$var);
}

function fetch_row($result=""){    	
if ($result == ""){
$result = $this->result;
}
$this->record_row = mysqli_fetch_assoc($result);
return $this->record_row;
}

function quick_fetch($the_query){
$result = $this->query($the_query);  
return $this->fetch_row($result);
}

function quick_count($table, $where=""){
$result = $this->quick_fetch("SELECT COUNT(*) AS count FROM $table $where");  
return $result['count'];
}

function do_delete($table, $where=""){
if(!$where){
$query = "TRUNCATE TABLE $table";
}else{
$query = "DELETE FROM $table WHERE $where";
}
$return = $this->query($query);
return $return;
}

function do_insert($table, $arr){
	//prepare this
$result = $this->stick_instert($arr);
$query = "INSERT IGNORE INTO $table ({$result['FIELD_NAMES']}) VALUES({$result['FIELD_VALUES']})";
$return = $this->query($query);
return mysqli_insert_id($this->connection_id);
}

function stick_instert($data){
$field_names  = "";
$field_values = "";
foreach ($data as $k => $v){	
$field_names  .= "$k,";
if (is_numeric($v) and intval($v) == $v){
$field_values .= $v.",";
} else {
$v = mysqli_real_escape_string($this->connection_id,$v);
$field_values .= "'$v',";
}
}
$field_n  = preg_replace("/,$/", "", $field_names );
$field_v = preg_replace("/,$/", "", $field_values);
return array( 'FIELD_NAMES'  => $field_n, 'FIELD_VALUES' => $field_v, );
}

function do_update($table, $arr, $where=""){
$result = $this->stick_updatte($arr);
$query = "UPDATE $table SET $result";
if ($where){
$query .= " WHERE ".$where;
}
$return = $this->query($query);
return $return;
}

function stick_updatte($data){
$return_string = "";
foreach ($data as $k => $v){		  
if (is_numeric($v)){
$return_string .= $k . "='".$v."',";
}else{
$v = mysqli_real_escape_string($this->connection_id,$v);
$return_string .= $k . "='".$v."',";
}
}
$return_s = preg_replace( "/,$/" , "" , $return_string);
return $return_s;
}

function get_num_rows($result=""){
if ($result == ""){
$result = $this->result;
}
return mysqli_num_rows($result);
}

function fatal_error($error_msg=""){  	
$the_error .= "\n\n\nSQL error: ".$this->_get_error_string()."\n";
$the_error .= "SQL error code: ".$this->_get_error_number()."\n";
$the_error .= "Date: ".date("l dS of F Y h:i:s A");
$out = "<html><head><title>MySQL Error</title></head><body>&nbsp;<br /><br /><blockquote><b>There has been an error with your database.</b><br />Try refreshing or checking the code.<br /><br /><b>Error Returned</b><br /><form name='mysql'><textarea rows=\"15\" cols=\"60\">".htmlspecialchars($error_msg)."".$the_error."</textarea></form></blockquote></body></html>";
print $out;
exit();
}

function _get_error_string(){
return mysqli_error($this->connection_id);
}

function _get_error_number(){
return mysqli_errno($this->connection_id);
}

function close(){
if ($this->connection_id){
return @mysqli_close($this->connection_id);
}
}
}
?>