<?php

class DAL {
	protected $connection;
	
	public function __construct() {
		$this->connection = new mysqli("localhost", "root", "root", "project");
		if (mysqli_connect_errno()) {
		    echo "Connect failed: %s\n" . mysqli_connect_error();
	 	   exit();
		}
	}
}
