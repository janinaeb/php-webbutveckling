<?php

class Comment {
	private $commentContent;
	private $username;

	public function __construct($commentContent, $username) {
		$this->commentContent = $commentContent;
		$this->username = $username;
	}
	public function getContent() {
		return $this->commentContent;
	}
	public function getUsername() {
		return $this->username;
	}
}