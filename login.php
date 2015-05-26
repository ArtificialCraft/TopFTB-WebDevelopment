<?php

session_start();

if(!isset($_POST['un']) || !isset($_POST['pw'])){
	die('Possible hacking attempt!');
}else{
	include('util.php');
	$connection = getDB()->getConnection();
	$username = mysqli_real_escape_string($connection, trim($_POST['un']));
	$password = mysqli_real_escape_string($connection, trim($_POST['pw']));
	if(empty($username) || empty($password)){
		$_SESSION['error'] = "Please enter your username <b>and</b> password!";
	}else{
		$db = getDB();
		
		$db->select(array('id', 'username', 'password'));
		$db->from('#__UserDatabase');
		$db->where(array(array('username', $username)));
		$db->exec();
		if($db->count() == 1){
			$res = $db->getSingleRow();
			if(myencrypt($password, $username) == $res['password']){
				$_SESSION['un'] = $res['username'];
				$_SESSION['id'] = $res['id'];
				$_SESSION['loggedin'] = true;
				redirect("manage");
			}else{
				$_SESSION['error'] =  'Incorrect username or password!';
			}
		}else if($db->count() > 1){
			$_SESSION['error'] = 'Incorrect username or password! Please file a report!';
		}else{
			$_SESSION['error'] = 'Incorrect username or password!';	
		}
	}
	if(isset($_SESSION['error'])){
		$_SESSION['type'] = "login";
	}
	redirect("");
}

?>