<?php

	try{
		require_once('bootstrap.php');
	}catch(ErrorException $ex){
		echo('Unable to load file.');
	}

	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
	header("Access-Control-Max-Age: 3600");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	
	$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$uri = explode( '/', $uri );
	if ($uri[1] !== 'api' && $uri[2] !== 'task') {
    		header("HTTP/1.1 404 Not Found");
    		exit();
	}


	$taskId = null;
	if (isset($uri[3])) {
    		$taskId = (int) $uri[3];
	}

	$requestMethod = $_SERVER["REQUEST_METHOD"];


	$controller = new TaskController($dbConnection, $requestMethod, $taskId);
	$controller->processRequest();

?>
