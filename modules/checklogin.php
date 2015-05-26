<?php 
if(!isset($_SESSION['un']) || !isset($_SESSION['id']) || !isset($_SESSION['loggedin']) || !$_SESSION['loggedin']){
	$_SESSION['error'] = "You must be logged in to visit this page!";
	redirect("");
	die();
}
?>