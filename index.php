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
	$taskId = null;
	$done = false;
	if ($uri[1] !== 'api' && $uri[2] !== 'task') {
    		header("HTTP/1.1 404 Not Found");
    		exit();
	}

	if (isset($uri[3])) {
		$taskId = (int) $uri[3];
	}

	if (isset($uri[4])) {
		if($uri[4] === 'done'){
			$done = true;
		}else{
			header("HTTP/1.1 404 Not Found");
    		exit();
		}
	}

	$requestMethod = $_SERVER["REQUEST_METHOD"];


	$controller = new TaskController($dbConnection, $requestMethod, $taskId, $done);
	$controller->processRequest();

?>
