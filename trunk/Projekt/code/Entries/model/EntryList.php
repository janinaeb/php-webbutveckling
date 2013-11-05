<?php

class EntryList {
	/**
	 * @var array with entries
	 */
	private $entryList;
	
	/**
	 * Inits entrylist array
	 */
	public function __construct() {
		$this->entryList = array();
	}
	
	/**
	 * @param Entry object
	 */
	public function add(Entry $entry) {
		$this->entryList[] = $entry;
	}
	
	/**
	 * @param Entry object
	 */
	public function delete(Entry $entry) {
		$i = array_search($entry, $this->entryList, true);
		unset($this->entryList[$i]);
	}
	
	/**
	 * @return EntryList object
	 */
	public function getEntries() {
		return $this->entryList;
	}
	
	/**
	 * @param int index
	 * @param Entry object
	 * exception thrown if index does not exist
	 */
	public function getEntryByID($index) {
		if (count($this->entryList) > $index) {
			return $this->entryList[$index];
		}
		throw new Exception("Index outside entryList");
	}
}
