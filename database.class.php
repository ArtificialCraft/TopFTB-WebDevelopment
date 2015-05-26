<?php
class DB {
	public static function Obtain(array $db = array(), $instance = 0) {
		if(!isset($db['DB']) || false === $db['DB']) {
			$driver = strtolower(Registry::getInstance()->conf->db['DB']);
		}else{
			$driver = strtolower($db['DB']);
		}
		
		if(!is_array($db) || count($db) == 0)
			$db = Registry::getInstance()->conf->db;
		
		
		$path = dirname(__FILE__).'/' . $driver . '.driver.php';
		if(file_exists($path)) {
			require_once $path;
			
			return call_user_func_array(array($driver . 'Driver', 'Obtain'), array($db, $instance));;
		}else{
			return null;
		}
	}
}
?>