<?php
	class TaskController{
		private $dbConnection = null;
		private $requestMethod;
        private $taskId;
        private $done;

		private $taskGateway;

 		public function __construct($dbConnection, $requestMethod, $taskId, $done){
        		$this->dbConnection = $dbConnection;
        		$this->requestMethod = $requestMethod;
                $this->taskId = $taskId;
                $this->done = $done;

        		$this->taskGateway = new TaskGateway($this->dbConnection);
    		}

		public function processRequest(){
        		switch ($this->requestMethod) {
            			case 'GET':
                			if ($this->taskId) {
                    				$response = $this->getTask($this->taskId);
                			} else {
                    				$response = $this->getAllTasks();
                			};
                			break;
            			case 'POST':
                			$response = $this->createTaskFromRequest();
                			break;
                        case 'PUT':
                            if(!$this->done){
                                $response = $this->updateTaskFromRequest($this->taskId);
                            }else{
                                $response = $this->changeStatusFromPendingToDone($this->taskId);
                            }
                			break;
            			case 'DELETE':
                			$response = $this->deleteTask($this->taskId);
                			break;
            			default:
                			$response = $this->notFoundResponse();
               				break;
        		}
        		header($response['status_code_header']);
        		if ($response['body']) {
            			echo $response['body'];
        		}
    		}

    		private function getAllTasks(){
        		$result = $this->taskGateway->findAll();
        		$response['status_code_header'] = 'HTTP/1.1 200 OK';
        		$response['body'] = json_encode($result);
        		return $response;
    		}

		private function getTask($id){
        		$result = $this->taskGateway->find($id);
        		if (!$result) {
            			return $this->notFoundResponse();
        		}
        		$response['status_code_header'] = 'HTTP/1.1 200 OK';
        		$response['body'] = json_encode($result);
        		return $response;
    }
      
    private function createTaskFromRequest(){
        $input = (array) json_decode(file_get_contents("php://input"), TRUE);
        if (!$this->validateTask($input)) {
            return $this->unprocessableEntityResponse();
        }
        $result = $this->taskGateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode($result);
        return $response;
    }
      
    private function updateTaskFromRequest($id){
        $result = $this->taskGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents("php://input"), TRUE);
        if (!$this->validateTask($input)) {
            return $this->unprocessableEntityResponse();
        }
        $result = $this->taskGateway->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function changeStatusFromPendingToDone($id){
        $result = $this->taskGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $result = $this->taskGateway->doneTask($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function deleteTask($id){
        $result = $this->taskGateway->find($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $this->taskGateway->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function validateTask($input){
        if (!isset($input['title'])) {
            return false;
        }
        if (!isset($input['date'])) {
            return false;
        }
        if (!isset($input['status'])) {
            return false;
        }
        return true;
    }

    private function unprocessableEntityResponse(){
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    private function notFoundResponse(){
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }

	}




?>
