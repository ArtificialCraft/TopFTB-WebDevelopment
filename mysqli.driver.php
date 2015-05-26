<?php
class mysqliDriver {
	
	private static $instance = array();
	
	private $connection;
	private $sql = array();
	private $query = array();
	private $execs = 0;
	private $prefix = false;
	private $_SQL = '';
	private $_i = 0;
	
	private function __construct(array $dbConf = array('host' => '', 'user' => '', 'pass' => '', 'database' => '', 'prefix' => false)){
		$host = $dbConf['host'];
		$user = $dbConf['user'];
		$pass = $dbConf['pass'];
		$db = $dbConf['database'];
		$this->prefix = $dbConf['prefix'];
		if(false === ($this->connection = @mysqli_connect($host, $user, $pass))){
			trigger_error('Failed to connect to MySQL server: ' . mysqli_errno($this->connection) .': '. mysqli_connect_error().'!', E_USER_WARNING);
		}
		
		if(false !== $db){
			$this->selectDb($db);
		}
	}
	
	public static function Obtain(array $dbConf = array('host' => '', 'user' => '', 'pass' => '', 'database' => '', 'prefix' => false), $instance = 0) {
		if(!isset(self::$instance[$instance])) {
			self::$instance[$instance] = new mysqliDriver($dbConf);
		}
		
		return self::$instance[$instance];
	}
	
	public function connect($host, $user, $pass, $db = false){
		if(false === ($this->connection = @mysqli_connect($host, $user, $pass))){
			trigger_error('Failed to connect to MySQL server: '.mysqli_errno($this->connection) .': '. mysqli_error($this->connection).'!', E_USER_WARNING);
		}
		
		if(false !== $db){
			$this->selectDb($db);
		}
	}
	
	public function getConnection(){
		return $this->connection;
	}
	
	public function selectDb($db){
		if(false === @mysqli_select_db($this->connection, $db)){
			trigger_error('Failed to select MySQL database: '.mysqli_errno($this->connection) .': '. mysqli_error($this->connection).'!', E_USER_WARNING);
		}
	}
	
	public function buildQuery($string, $add = false, $finalize = false, $debug = DEBUGSQL){
		$last = count($this->sql);
		
		//Might want to not do this like so due too possible security risk....
		//$string = str_replace('#__', $this->prefix, $string);
		
		if(false === $add){
			$this->sql[]['sql'] = $string;
		}elseif(is_int($add)){
			if(isset($this->sql[$add]) && is_array($this->sql[$add])) {
				if(true !== $this->sql[$add]['finalize']){
					$this->sql[$add]['sql'] .= $string;
					$last = $add;
				}else{
					if($debug === true){
						trigger_error('This SQL syntax is finalized!', E_USER_WARNING);
					}
				}
			}else{
				trigger_error($add. ' is not a valid SQL query!', E_USER_WARNING);
			}
		}else{
			$last = count($this->sql) - 1;
			if(isset($this->sql[$last]) && is_array($this->sql[$last])) {
				if(true !== $this->sql[$last]['finalize']){
					$this->sql[$last]['sql'] .= $string;
				}else{
					if($debug === true){
						trigger_error('This SQL syntax is finalized!', E_USER_WARNING);
					}
				}
			}else{
				trigger_error($last. ' is not a valid SQL query!', E_USER_WARNING);
			}
		}
	
		if(true === $finalize){
			$this->sql[$last]['finalize'] = true;
		}elseif(false === $finalize && !isset($this->sql[$last]['finalize'])){
			$this->sql[$last]['finalize'] = false;
		}
		
		if(!isset($this->sql[$last]['executed'])) {
			$this->sql[$last]['executed'] = false;
		}
		if($debug === true){
			if(!$this->sql[$last]['finalize'])
				trigger_error($this->sql[$last]['sql'], E_USER_NOTICE);
			else
				trigger_error('<pre>Finalized: ' . $this->sql[$last]['sql'] . '</pre>', E_USER_NOTICE);
		}
		return $this;
	}
	
	public function start() {
		if(false === mysqli_query($this->connection, "START TRANSACTION"))
			trigger_error('Failed to create query: '.mysqli_errno($this->connection) .': '. mysqli_error($this->connection).'!', E_USER_WARNING);
	}
	
	public function commit() {
		if(false === mysqli_query($this->connection, "COMMIT"))
			trigger_error('Failed to create query: '.mysqli_errno($this->connection) .': '. mysqli_error($this->connection).'!', E_USER_WARNING);
	}
	
	public function rollback() {
		if(false === mysqli_query($this->connection, "ROLLBACK"))
			trigger_error('Failed to create query: '.mysqli_errno($this->connection) .': '. mysqli_error($this->connection).'!', E_USER_WARNING);
	}
	
	public function execQuery($query_id = false, $return = false){
		$query_id = (false == $query_id) ? (((count($this->sql) - 1) > 0) ? (count($this->sql) - 1) : 0) : $query_id;
		if(false === ($this->query[$query_id] = mysqli_query($this->connection, $this->sql[$query_id]['sql']))){
			echo $this->sql[$query_id]['sql'];
			trigger_error('Failed to create query: '.mysqli_errno($this->connection) .': '. mysqli_error($this->connection).'!', E_USER_WARNING);
			if($return)
				return false;
		}else{
			$this->sql[$query_id]['executed'] = true;
			$this->execs++;
			
			if($return)
				return true;
		}
		return $this;
	}
	
	public function exec($query_id = false, $return = false){
		return $this->execQuery($query_id, $return);
	}
	
	public function countResult($query_id = false){
		$query_id = (false == $query_id) ? (count($this->query) - 1) : $query_id;
		
		if(!isset($this->query[$query_id]) || false === $this->query[$query_id])
			return 0;
		
		return @mysqli_num_rows($this->query[$query_id]);
	}
	
	public function count($query_id = false) {
		return $this->countResult($query_id);
	}
	
	public function affectedRows($query_id = false){
		return (int) mysqli_affected_rows($this->connection);
	}
	
	public function getResult($query_id = false){
		$query_id = (false == $query_id) ? (count($this->query) - 1) : $query_id;
		
		if(!isset($this->query[$query_id]) || false === $this->query[$query_id])
			return array();
		
		$result = array();
		$i = 0;
		while(false !== ($row = @mysqli_fetch_assoc($this->query[$query_id]))){
			if(!is_array($row))
				break;
			
			$i++;
			foreach($row as $field => $value){
				$result[$i][$field] = $value;
			}
		}
		return $result;
	}
	
	public function get($query_id = false) {
		return $this->getResult($query_id);
	}
	
	public function getSingleRow($query_id = false){
		$query_id = (false == $query_id) ? (count($this->query) - 1) : $query_id;
		
		if(!isset($this->query[$query_id]) || false === $this->query[$query_id])
			return array();
		
		$result = array();
		$i = 0;
		$row = @mysqli_fetch_assoc($this->query[$query_id]);
		
		return $row;
	}
	
	public function getLastID() {
		return mysql_insert_id($this->connection);
	}
	
	public function countQueries(){
		return count($this->query);
	}
	
	public function countSQL(){
		return count($this->sql);
	}
	
	public function close(){
		mysqli_close($this->connection);
	}
	
	public function getStats() {
		return (string) $this->execs;
	}
	
	public function select($fields) {
		$sql = "SELECT ";
		if(is_array($fields)) {
			$i = 0;
			foreach($fields as $field) {
				if($i > 0)
					$sql .= ", ";
				
				if(is_array($field))
					$sql .= $field[0] . " AS " . $field[1];
				else
					$sql .= $field;
				
				$i++;
			}
		}else
			$sql .= $fields;
		
		$this->buildQuery($sql . " \n", false, false, false);
		
		return $this;
	}
	
	public function from($table) {
		$table = str_replace('#__', $this->prefix, $table);
		$this->buildQuery("FROM " . $table . " \n", true, false, false);
		
		return $this;
	}
	
	public function where(array $fields, $recall = false) {
		if(!$recall) {
			$this->_SQL = "WHERE ";
			$this->_i = 0;
		}
		
		foreach($fields as $field) {
			if(isset($field['type'])) {
				$type = ($field['type'] == 'OR') ? 'OR' : (($field['type'] == 'NONE') ? '' : 'AND');
				if($this->_i > 0)
					$this->_SQL .= " " . $type . " ";
				
				if(isset($field['group']) && is_array($field['group'])) {
					$this->_SQL .= "\n (\n";
						$this->where($field['group'], true);
					$this->_SQL .= "\n) \n";
				}elseif(isset($field['kind']) && $field['kind'] == 'int')
					$this->_SQL .= $field['data'][0] . "=" . $field['data'][1] . "";
				elseif(isset($field['kind']) && $field['kind'] == 'null')
					$this->_SQL .= $field['data'][0] . " IS NULL";
				elseif(isset($field['kind']) && $field['kind'] == 'notnull')
					$this->_SQL .= $field['data'][0] . " IS NOT NULL";
				else{
					$this->_SQL .= $field['data'][0] . "='" . $field['data'][1] . "'";
				}
			}else{
				if($this->_i > 0)
					$this->_SQL .= " AND ";
				
				if(isset($field['group']) && is_array($field['group'])) {
					$this->_SQL .= "\n (\n";
						$this->where($field['group'], true);
					$this->_SQL .= "\n) \n";
				}elseif(isset($field['kind']) && $field['kind'] == 'int')
					$this->_SQL .= $field['data'][0] . "=" . $field['data'][1] . "";
				elseif(isset($field['kind']) && $field['kind'] == 'null')
					$this->_SQL .= $field['data'][0] . " IS NULL";
				elseif(isset($field['kind']) && $field['kind'] == 'notnull')
					$this->_SQL .= $field['data'][0] . " IS NOT NULL";
				else 
					$this->_SQL .= $field[0] . "='" . $field[1] . "'";
			}
			
			$this->_i++;
		}
		
		if(!$recall)
			$this->buildQuery($this->_SQL . " \n", true, false, false);
		
		return $this;
	}
	
	public function orderBy(array $fields) {
		$sql = "ORDER BY ";
		$i = 0;
		foreach($fields as $field) {
			if($i > 0)
				$sql .= ", ";
			
			$i++;
			if(is_array($field))
				$sql .= $field[0] . " " . ((isset($field[1])) ? (($field[1] == 'ASC') ? 'ASC' : 'DESC') : 'DESC');
			else
				$sql .= $field . " DESC";
		}
		
		$this->buildQuery($sql . " \n", true, false, false);
		
		return $this;
	}
	
	public function limit($limit, $start = false) {
		$lmt = (int) $limit;
		
		if(false !== $start)
			$lmt = (int) $start . ', ' . $lmt;
		
		$this->buildQuery(" LIMIT " . $lmt . " \n", true, false, false);

		return $this;
	}
	
	public function update($table) {
		$table = str_replace('#__', $this->prefix, $table);
		$this->buildQuery("UPDATE " . $table . " \n", false, false, false);
		
		return $this;
	}
	
	public function set(array $fields) {
		$sql = "SET ";
		$i = 0;
		foreach($fields as $field) {
			if($i > 0)
				$sql .= ", ";
			
			if(isset($field['kind']))
				if($field['kind'] == 'int')
					$sql .= $field['data'][0] . "=" . $field['data'][1] . "";
				else
					$sql .= $field['data'][0] . "='" . $field['data'][1] . "'";
			else
				$sql .= $field[0] . " = '" . $field[1] . "'";
			
			$i++;
		}
		
		$this->buildQuery($sql . " \n", true, false, false);
		
		return $this;
	}
	
	public function insert($table, array $data) {
		$table = str_replace('#__', $this->prefix, $table);
		$this->buildQuery("INSERT INTO " . $table . " \n", false, false, false);
		
		if(isset($data['fields']) && is_array($data['fields']) && count($data['fields']) > 0) {
			$this->buildQuery("(", true, false, false);
			
			$i = 0;
			foreach($data['fields'] as $fields) {
				if($i != 0)
					$this->buildQuery(", ", true, false, false);
				
				$i++;
				
				$this->buildQuery($fields, true, false, false);
			}
			
			$this->buildQuery(") \n", true, false, false);
		}
				
		$this->buildQuery(" VALUES \n", true, false, false);
		
		$total = count($data['values']);
		$a = 0;
		foreach($data['values'] as $values) {
			$a++;
			
			$this->buildQuery("(", true, false, false);
			$i = 0;
			foreach($values as $val) {
				if($i != 0)
					$this->buildQuery(", ", true, false, false);
				
				$i++;
				if(is_array($val) && isset($val['kind'])) {
					if($val['kind'] == 'int')
						$this->buildQuery($val['data'], true, false, false);
					else
						$this->buildQuery("'" . $val['data'] . "'", true, false, false);
				}else
					$this->buildQuery("'" . $val . "'", true, false, false);
			}
			
			if($a < $total)
				$this->buildQuery("), \n", true, false, false);
			else
				$this->buildQuery(");", true, false, false);
		}
		
		return $this;
	}
	
	public function delete($table) {
		$table = str_replace('#__', $this->prefix, $table);
		$this->buildQuery("DELETE FROM " . $table . " \n", false, false, false);
		
		return $this;
	}
	
	public function truncate($table) {
		$table = str_replace('#__', $this->prefix, $table);
		$this->buildQuery("TRUNCATE TABLE " . $table . " \n", false, false, false);
		
		return $this;
	}
	
	public function debug($switch = DEBUGSQL) {
		$this->buildQuery("", true, false, $switch);
		
		return $this;
	}
	
	public function finalize() {
		$this->buildQuery("", true, true, false);
		
		return $this;
	}
	
	public function debugFinalize($switch = DEBUGSQL) {
		$this->buildQuery("", true, true, $switch);
		
		return $this;
	}
}
?>