<?php 
if(!isset($_POST['key']) || !isset($_POST['value'])){
	error_log('post not set');
}
require('util.php');
$server = getServer($_POST['key'], $_POST['value']);
if($server == null || !isset($server['id'])){
	exit();
}
if(strpos($server['rawip'],':') !== false){
	$info = explode(':',$server['rawip']);
	$ip = $info[0];
	$port = $info[1];
}else{
	$ip = $server['rawip'];
	$port = "25565";
}
try{
	require_once 'MinecraftServerPing.php';
	$Query = new MinecraftPing($ip, $port);
	$Info = $Query->Query();
	$players = 0;
	$maxplayers = 0;
	if($Info === false){
		$Query->Close();
		$Query->Connect();
		$Info = $Query->QueryOldPre17();
		$players = $Info['Players'];
		$maxplayers = $Info['MaxPlayers'];
	}else{
		$players = $Info['players']['online'];
		$maxplayers = $Info['players']['max'];
	}
	getDB()->from('#__ServerList')->update('#__ServerList')->set(array(array('players', $players), array('maxplayers', $maxplayers), array('updated', time()), array('online', 'true')))->where(array(array('id', $server['id'])))->exec();
}catch(Exception $ex){
	getDB()->from('#__ServerList')->update('#__ServerList')->set(array(array('players', '0'), array('maxplayers', '0'), array('updated', time()), array('online', 'false')))->where(array(array('id', $server['id'])))->exec();
}
?>