<?php

class Comment {
	/**
	 * @var String content
	 */
	private $commentContent;
	
	/**
	 * @var String username
	 */
	private $username;
	
	/**
	 * @param String content
	 * @param String username
	 */
	public function __construct($commentContent, 
								$username) {
		$this->commentContent = $commentContent;
		$this->username = $username;
	}

	/**
	 * @return String content
	 */
	public function getContent() {
		return $this->commentContent;
	}
	
	/**
	 * @return String username
	 */
	public function getUsername() {
		return $this->username;
	}
}