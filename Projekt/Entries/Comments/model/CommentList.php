<?php

class CommentList {
	private $comments;

	public function __construct() {
		$this->comments = array();
	}
	public function add(Comment $comment) {
		$this->comments[] = $comment;
	}
	public function delete(Comment $comment) {
		$i = array_search($comment, $this->comments, true);
		unset($this->comments[$i]);
	}
	public function getComments() {
		return $this->comments;
	}
}