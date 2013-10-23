<?php

class UserDAL {
	private $connection;
	
	public function __construct() {
		$this->connection = new mysqli("127.0.0.1", "root", "", "PHP-project");
		if (mysqli_connect_errno()) {
		    echo "Connect failed: %s\n" . mysqli_connect_error();
	 	   exit();
		}
	}
	
	public function getUsers() {
		$sql = "SELECT Username, Password FROM UserList";
        $prep = $this->connection->prepare($sql);
        if ($prep == false) {
            throw new Exception("prepare of [$sql] failed " . $this->connection->error);
        }
        
        $exec = $prep->execute();
        if ($exec == false) {
        	throw new Exception("execute of [$sql] failed " . $prep->error);
        }

        $exec = $prep->bind_result($username, $password);
        if ($exec == false) {
        	throw new Exception("execute of [$sql] failed " . $prep->error);
        }

        $return = array();

	    while ($stmt->fetch()) {
	        $return[] = $username . $password;
	    }
		var_dump($return);
        return $return;
	}
}