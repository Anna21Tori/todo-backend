<?php
	
	class TaskGateway{

		private $dbConnection = null;
		
		public function __construct($dbConnection){
			$this->dbConnection = $dbConnection;
		}

    public function findAll(){
		$sql = "SELECT * FROM tasks;";
		try {
            	$statement = $this->dbConnection->query($sql);
            	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
            	return $result;
        } catch (PDOException $e) {
            	exit($e->getMessage());
        }
	}
    public function find($id){
        $sql = "SELECT * from tasks WHERE id = ?;";
        try {
            $statement = $this->dbConnection->prepare($sql);
            $statement->execute(array($id));
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function insert(Array $input){
        $sql = "INSERT INTO tasks (id, title, date, status) VALUES (NULL, :title, :date, :status);";
        try {
            $statement = $this->dbConnection->prepare($sql);
            $statement->execute(array(
                'title' => $input['title'],
                'date'  => $input['date'],
                'status' => $this->convertToEnum($input['status']),
            ));
            return $this->find($this->dbConnection->lastInsertId());
        } catch (PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function update($id, Array $input){
        $sql = "UPDATE tasks SET title = :title, date  = :date, status = :status WHERE id = :id;";
        try {
            $statement = $this->dbConnection->prepare($sql);
            $statement->execute(array(
                'id' => (int) $id,
                'title' => $input['title'],
                'date'  => $input['date'],
                'status' => $input['status'],
            ));
            return $this->find($id);
        } catch (PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function delete($id){
        $sql = "DELETE FROM tasks WHERE id = :id;";
        try {
            $statement = $this->dbConnection->prepare($sql);
            $statement->execute(array('id' => $id));
            return $statement->rowCount();
        } catch (PDOException $e) {
            exit($e->getMessage());
        }    
    }
	private function convertToEnum($num){
		$enum = array(
			0 => "PENDING",
			1 => "REJECT",
			2 => "DONE"
		);
		return $enum[$num];
	}
	}
?>
