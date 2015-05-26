<?php
	if(!isset($_GET['data']) || !is_numeric($_GET['data']))
		exit('This is an invalid ad!');
	$id = $_GET['data'];
	if($id == 0){
		header('Location: http://boomcraft.net/i/topgbanner.png');//default location
		return;
	}
		
	getDB()->update('#__Ads')->buildQuery('SET clicks = clicks + 1 ', true, false, false)->where(array(array('id', $id)))->exec();
	$db = getDB()->select(array('link'))->from('#__Ads')->where(array(array('id', $id)))->exec()->getResult();
	if(!isset($db[1]['link'])){
		$_SESSION['error'] = "This ad has an invalid url link!";
		redirect('');
		return;
	}
	header('Location: ' . $db[1]['link']);
?>