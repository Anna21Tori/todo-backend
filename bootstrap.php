<?php

	try{
		require_once('config/globalVariable.php');
	}catch(ErrorException $ex){
		echo('Unable to load file.');
	}

	try{
		require_once('config/auth/databaseConnector.php');
	}catch(ErrorException $ex){
		echo('Unable to load file.');
	}

	try{
		require_once('taskComponent/taskController.php');
	}catch(ErrorException $ex){
		echo('Unable to load file.');
	}

	try{
		require_once('taskComponent/taskGateway.php');
	}catch(ErrorException $ex){
		echo('Unable to load file.');
	}

	$dbConnection = (new DatabaseConnector())->getConnection();
?>
