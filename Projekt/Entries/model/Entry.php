<?php

class Entry {
	private $headline;
	private $content;
	private $username;
	private $commentList;
	private $commentDAL;
	
	public function __construct($headline, $content, $username) {
		$this->headline = $headline;
		$this->content = $content;
		$this->username = $username;
		$this->commentDAL = new CommentDAL();
		$this->commentList = $this->commentDAL->getCommentsFromEntry($this);
	}
	public function getHeadline() {
		return $this->headline;
	}
	public function getContent() {
		return $this->content;
	}
	public function getUsername() {
		return $this->username;
	}
	public function getComments() {
		return $this->commentList->getComments();
	}
	public function addComment(Comment $comment) {
		$this->commentDAL->addComment($comment, $this);
		$this->commentList->add($comment);
	}
	// TODO: Fix edit and delete for both entries and comments!!!
}
