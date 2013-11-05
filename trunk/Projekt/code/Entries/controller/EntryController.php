<?php

class EntryController {
	/**
	 * @var EntryDAL object
	 */
	private $entryDAL;
	
	/**
	 * @var EntryView object
	 */
	private $entryView;
	
	/**
	 * @var string username
	 */
	private $username;
	
	/**
	 * @var Session object
	 */
	private $sessionHandler;
	
	/**
	 * @var EntryList object
	 */
	private $entryList;
	
	/**
	 * @var EntryList object
	 */
	private $allEntriesList;
	
	/**
	 * @var CommentDAL
	 */
	private $commentDAL;
	
	/**
	 * @param EntryDAL object
	 * @param EntryView object
	 * @param Session object
	 */
	public function __construct(EntryDAL $entryDAL,
								EntryView $entryView,
								Session $sessionHandler) {
		$this->entryDAL = $entryDAL;
		$this->entryView = $entryView;
		$this->sessionHandler = $sessionHandler;
		$this->username = $this->sessionHandler->getUsername();
		$this->entryList = $this->entryDAL->getEntries($this->username);
		$this->allEntriesList = $this->entryDAL->getAllEntries();
		$this->commentDAL = new CommentDAL();
	}
								
	/**
	 * @return string HTML
	 */
	public function getEntries() {
		if ($this->entryView->isAddingEntry()) {
			if ($this->entryView->isSavingEntry()) {
				$entry = new Entry($this->entryView->getHeadline(),
									$this->entryView->getContent(),
									$this->username);
				if ($this->tryAddEntry($entry)) {
					// Entry is valid - adding to database
					$this->entryDAL->addEntry($entry);
					$this->entryList->add($entry);
					$this->allEntriesList->add($entry);
					return $this->entryView->getEntryHTML($this->allEntriesList);
				}
			}
			return $this->entryView->getEntryForm(null);
		} else {
			return $this->checkUserState();
		}
	}
	
	/**
	 * @return string HTML
	 */
	private function checkUserState() {
		try {
			$navigator = new Navigator();
			if ($this->entryView->isCommenting()) {
				if ($this->entryView->isSavingComment()) {
					$entry = $this->getUserEntry($this->allEntriesList);
					$comment = new Comment($this->entryView->getComment(),
											$this->username);
					if ($this->tryComment($comment)) {
						$entry->addComment($comment);
						$navigator->redirect();
						return "";
					}
					$this->sessionHandler->setMessage(Message::commentFaulty);
				}
			} else if ($this->entryView->isEditingEntry()) {
				$entry = $this->getUserEntry($this->entryList);
				if ($this->username == $entry->getUsername()) {
					if ($this->entryView->isSavingEntry()) {
						$newEntry = new Entry($this->entryView->getHeadline(),
											$this->entryView->getContent(),
											$this->username);
						if ($this->tryAddEntry($newEntry)) {
							$this->entryDAL->editEntry($entry, $newEntry);
							$navigator->redirect();
							return "";
						}
					}
					return $this->entryView->getEntryForm($entry);
				}
			} else if ($this->entryView->isDeletingEntry()) {
				$entry = $this->getUserEntry($this->entryList);
				if ($this->username == $entry->getUsername()) {
					if ($this->entryView->isPressingDelete()) {
						$this->entryDAL->deleteEntry($entry);
						$navigator->redirect();
						return "";
					}
					return $this->entryView->getDeleteForm($entry);
				}
			} else if ($this->entryView->isEditingComment()) {
				$entry = $this->getUserEntry($this->allEntriesList);
				$comment = $this->getComment($entry);
				if ($this->username == $comment->getUsername()) {
					if ($this->entryView->isSavingComment()) {
						$newComment = new Comment($this->entryView->getComment(),
												$this->username);
						if ($this->tryComment($newComment)) {
							$this->commentDAL->editComment($comment, 
															$newComment, 
															$entry);
							$navigator->redirect();
							return "";
						}
					}
					return $this->entryView->getCommentForm($comment);
				}
			} else if ($this->entryView->isDeletingComment()) {
				$entry = $this->getUserEntry($this->allEntriesList);
				$comment = $this->getComment($entry);
				if ($this->username == $comment->getUsername()) {
					if ($this->entryView->isPressingDelete()) {
						$this->commentDAL->deleteComment($comment, $entry);
						$navigator->redirect();
						return "";
					}
					return $this->entryView->getDeleteForm($comment);
				}
			}
			if ($this->entryView->isViewingOwnEntries()) {
				return $this->entryView->getEntryHTML($this->entryList);
			} else {
				return $this->entryView->getEntryHTML($this->allEntriesList);
			}
		} catch (Exception $e){
			$this->sessionHandler->setMessage(Message::errorMessage);
			return $this->entryView->getEntryHTML($this->allEntriesList);
		}
	}

	/**
 	* @param EntryList object
	* @return Entry object
 	*/
	private function getUserEntry(EntryList $entryList) {
		$index = $this->entryView->getID();
		$entry = $entryList->getEntryByID($index);
		return $entry;
	}
	
	/**
	 * @param Entry object
	 * @return Comment object
	 */
	private function getComment(Entry $entry) {
		$index = $this->entryView->getCommentID();
		$comments = $entry->getCommentList();
		return $comments->getCommentByID($index);
	}
	
	/**
	 * @param Comment object
	 * @return Boolean true if comment is valid
	 */
	private function tryComment(Comment $comment) {
		if ($comment->getContent() == "") {
			$this->sessionHandler->setMessage(Message::commentMissing);
			return false;
		} else if (Validator::containsTags($comment->getContent())) {
			$this->sessionHandler->setMessage(Message::commentFaulty);
			return false;
		}
		return true;
	}
	
	/**
	 * @param Entry object
	 * @return Boolean true if entry is valid
	 */
	private function tryAddEntry(Entry $entry) {
		if ($entry->getHeadline() == "") {
			$this->sessionHandler->setMessage(Message::headlineMissing);
			return false;
		} else if ($entry->getContent() == "") {
			$this->sessionHandler->setMessage(Message::contentMissing);
			return false;
		} else if (Validator::containsTags($entry->getHeadline()) ||
					Validator::containsTags($entry->getContent())) {
			$this->sessionHandler->setMessage(Message::entryFaulty);
			return false;
		}
		$this->sessionHandler->setMessage(Message::entrySuccess);
		return true;
	}
}
