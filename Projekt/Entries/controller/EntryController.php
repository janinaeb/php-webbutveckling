<?php

class EntryController {
	private $entryDAL;
	private $entryView;
	private $username;
	private $sessionHandler;
	private $entryList;
	private $allEntriesList;
	private $commentList;
	
	public function __construct(EntryDAL $entryDAL,
								EntryView $entryView,
								Session $sessionHandler) {
		$this->entryDAL = $entryDAL;
		$this->entryView = $entryView;
		$this->sessionHandler = $sessionHandler;
		$this->username = $this->sessionHandler->getUsername();
		$this->entryList = $this->entryDAL->getEntries($this->username);
		$this->allEntriesList = $this->entryDAL->getAllEntries();
	}
								
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
					return $this->entryView->getEntryHTML($this->entryList);
				}
			}
			return $this->entryView->getEntryForm();
		} else {
			if ($this->entryView->isCommenting()) {
				if ($this->entryView->isSavingComment()) {
					$index = $this->entryView->getEntryID();
					$entry = $this->allEntriesList->getEntryByID($index);
					$comment = new Comment($this->entryView->getComment(),
											$this->username);
					$entry->addComment($comment);
					$navigator = new Navigator();
					$navigator->redirect();
				}
			}

			if ($this->entryView->isViewingOwnEntries()) {
				return $this->entryView->getEntryHTML($this->entryList);
			} else {

				return $this->entryView->getEntryHTML($this->allEntriesList);
			}
		}
	}
	private function tryAddEntry($entry) {
		if ($entry->getHeadline() == "") {
			$this->sessionHandler->setMessage(Message::headlineMissing);
			return false;
		} else if ($entry->getContent() == "") {
			$this->sessionHandler->setMessage(Message::contentMissing);
			return false;
		}
		$this->sessionHandler->setMessage(Message::entrySuccess);
		return true;
	}
}
