<?php

class Entry {
	/**
	 * @var string headline
	 */
	private $headline;
	
	/**
	 * @var string content
	 */
	private $content;
	
	/**
	 * @var string username
	 */
	private $username;
	
	/**
	 * @var CommentList object
	 */
	private $commentList;
	
	/**
	 * @var CommentDAL object
	 */
	private $commentDAL;
	
	/**
	 * @param string headline
	 * @param string content
	 * @param string username
	 */
	public function __construct($headline, 
								$content, 
								$username) {
		$this->headline = $headline;
		$this->content = $content;
		$this->username = $username;
		$this->commentDAL = new CommentDAL();
		$this->commentList = $this->commentDAL->getCommentsFromEntry($this);
	}
	
	/**
	 * @return string headline
	 */
	public function getHeadline() {
		return $this->headline;
	}
	
	/**
	 * @return string content
	 */
	public function getContent() {
		return $this->content;
	}
	
	/**
	 * @return string username
	 */
	public function getUsername() {
		return $this->username;
	}
	
	/**
	 * @return Comment Array
	 */
	public function getComments() {
		return $this->commentList->getComments();
	}
	
	/**
	 * @return CommentList object
	 */
	public function getCommentList() {
		return $this->commentList;
	}
	
	/**
	 * @param Comment object
	 */
	public function addComment(Comment $comment) {
		$this->commentDAL->addComment($comment, $this);
		$this->commentList->add($comment);
	}
}
