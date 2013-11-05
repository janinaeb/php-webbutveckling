<?php

class UserDAL extends DAL {
	/**
	 * @param User object
	 */
	public function addUser(User $user) {
		$sql = "INSERT INTO UserList(Username, Password) VALUES (?, ?)";
		
		$prep = $this->connection->prepare($sql);
        if ($prep == false) {
            throw new Exception("prepare of [$sql] failed " . $this->connection->error);
        }
		$username = $user->getUsername();
		$password = $user->getPassword();
        $exec = $prep->bind_param("ss", $username, $password);
		if ($exec == false) {
			throw new Exception("Bind param of [$sql] failed " . $prep->error);
		}
        $exec = $prep->execute();
        if ($exec == false) {
        	throw new Exception("execute of [$sql] failed " . $prep->error);
        }
		
	}
	
	/**
	 * @return Array of users
	 */
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

	    while ($prep->fetch()) {
	        $return[] = User::createUser($username, $password);
	    }
        return $return;
	}
}