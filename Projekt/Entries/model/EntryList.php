<?php

class EntryList {
	private $entryList;
	
	public function __construct() {
		$this->entryList = array();
	}
	public function add(Entry $entry) {
		$this->entryList[] = $entry;
	}
	public function delete(Entry $entry) {
		$i = array_search($entry, $this->entryList, true);
		unset($this->entryList[$i]);
	}
	public function getEntries() {
		return $this->entryList;
	}
	public function getEntryByID($index) {
		return $this->entryList[$index];
	}
}
