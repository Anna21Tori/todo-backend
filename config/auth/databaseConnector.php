<?php
class DatabaseConnector{
		
	private $dbConnection = null;
		
	public function __construct(){

		try {
            		$this->dbConnection = new PDO("mysql:host=".DB_HOST.";port=".DB_PORT.";charset=utf8;dbname=".DB_NAME, DB_USER, DB_PASSWORD);
        	} catch (PDOException $e) {
            		exit("Failed connect to DB: ".$e->getMessage());
        	}


	}
		
	public function getConnection(){
		return $this->dbConnection;
	}
}

?>
