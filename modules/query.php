<?php

	require __DIR__ . '/MinecraftQuery.class.php';
	
	$Query = new MinecraftQuery('ftbac.ca');
	
	if(!$Query->error){
		print_r($Query->GetInfo());
    	print_r($Query->GetPlayers());
	}else{
		echo $Query->error;
	}

?>