<?php

class CommentList {
	/**
	 * @var CommentList object
	 */
	private $comments;

	/**
	 * Inits commentList comments
	 */
	public function __construct() {
		$this->comments = array();
	}
	
	/**
	 * @param Comment object
	 */
	public function add(Comment $comment) {
		$this->comments[] = $comment;
	}
	
	/**
	 * @param Comment object
	 */
	public function delete(Comment $comment) {
		$i = array_search($comment, $this->comments, true);
		unset($this->comments[$i]);
	}
	
	/**
	 * @param int
	 * @return Comment object
	 */
	public function getCommentByID($index) {
		if (count($this->comments) > $index) {
			return $this->comments[$index];
		}
		throw new Exception("Index outside CommentList");
	}
	
	/**
	 * @return CommentList object
	 */
	public function getComments() {
		return $this->comments;
	}
}