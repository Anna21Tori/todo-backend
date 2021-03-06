# todo-backend
REST API for to-do application

Application will work correctly if you add file globalVariable.php in config directory.
The file should contain following constans and their appropriate values (which are not public for safty): DB_USER, DB_PASSWORD, DB_HOST, DB_NAME, DB_PORT.

In order to run server You have to install php server and use following command (port can be any which is free): <br/>
  -php -S 127.0.0.1:4000 -t name-directory
  
REST API:<br/>
  - get all task (get) - /api/task<br/>
  - get one task with id (get) - /api/task/{:id}<br/>
  - insert new task (post) - /api/task<br/>
  - update task (put) - /api/task/{:id}<br/>
