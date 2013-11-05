<?php

class EntryDAL extends DAL {
	/**
	 * @param Entry object
	 */
	public function addEntry(Entry $entry) {
        $username = $entry->getUsername();
		$sql = "INSERT INTO BlogEntries(Headline, Content, UserID) 
				VALUES (?, ?, (SELECT UserID FROM UserList WHERE Username = ?))";
		$prep = $this->connection->prepare($sql);
        if ($prep == false) {
            throw new Exception("prepare of [$sql] failed " . $this->connection->error);
        }
		$headline = $entry->getHeadline();
		$content = $entry->getContent();
		
        $exec = $prep->bind_param("sss", $headline, $content, $username);
		if ($exec == false) {
			throw new Exception("Bind param of [$sql] failed " . $prep->error);
		}
        $exec = $prep->execute();
        if ($exec == false) {
        	throw new Exception("execute of [$sql] failed " . $prep->error);
        }
	}
	
	/**
	 * @param Entry object
	 * @param Entry object
	 */
	public function editEntry(Entry $oldEntry, Entry $newEntry) {
        $username = $oldEntry->getUsername();
        $oldHeadline = $oldEntry->getHeadline();
        $oldContent = $oldEntry->getContent();
        $newHeadline = $newEntry->getHeadline();
        $newContent = $newEntry->getContent();

        $sql = "UPDATE BlogEntries SET Headline = ?, Content = ? 
                WHERE Headline = ? AND Content = ? AND UserID = 
                (SELECT UserID FROM UserList WHERE Username = ?)";
        $prep = $this->connection->prepare($sql);
        if ($prep == false) {
            throw new Exception("prepare of [$sql] failed " . 
                                $this->connection->error);
        }
        $exec = $prep->bind_param("sssss", $newHeadline, $newContent, 
                                    $oldHeadline, $oldContent, $username);
        if ($exec == false) {
            throw new Exception("Bind param of [$sql] failed " . $prep->error);
        }
        $exec = $prep->execute();
        if ($exec == false) {
            throw new Exception("execute of [$sql] failed " . $prep->error);
        }
    }
	
	/**
	 * @param Entry object
	 */
    public function deleteEntry(Entry $entry) {
        $headline = $entry->getHeadline();
        $content = $entry->getContent();
        $username = $entry->getUsername();

        $sql = "DELETE FROM BlogEntries WHERE Headline = ? AND Content = ? 
                AND UserID = (SELECT UserID FROM UserList WHERE Username = ?)";
        $prep = $this->connection->prepare($sql);
        if ($prep == false) {
            throw new Exception("prepare of [$sql] failed " . 
                                $this->connection->error);
        }
        $exec = $prep->bind_param("sss", $headline, $content, $username);
        if ($exec == false) {
            throw new Exception("Bind param of [$sql] failed " . $prep->error);
        }
        $exec = $prep->execute();
        if ($exec == false) {
            throw new Exception("execute of [$sql] failed " . $prep->error);
        }
    }

	/**
	 * @return EntryList object
	 */
	public function getAllEntries() {
		$sql = "SELECT BlogEntries.Headline, BlogEntries.Content, 
                UserList.Username FROM UserList INNER JOIN BlogEntries 
                ON UserList.UserID=BlogEntries.UserID 
                ORDER BY BlogEntries.EntryID";
        $prep = $this->connection->prepare($sql);
        if ($prep == false) {
            throw new Exception("prepare of [$sql] failed " . 
                                $this->connection->error);
        }
        $exec = $prep->execute();
        if ($exec == false) {
        	throw new Exception("execute of [$sql] failed " . $prep->error);
        }
        $exec = $prep->bind_result($headline, $content, $username);
        if ($exec == false) {
        	throw new Exception("execute of [$sql] failed " . $prep->error);
        }
        $entryList = new EntryList();
	    while ($prep->fetch()) {
	        $entryList->add(new Entry($headline, $content, $username));
	    }
        return $entryList;
	}
	
	/**
	 * @param string username
	 * @return EntryList object
	 */
	public function getEntries($username) {
		$sql = "SELECT Headline, Content FROM BlogEntries WHERE UserID = 
                (SELECT UserID FROM UserList WHERE Username = ?)";
        $prep = $this->connection->prepare($sql);
        if ($prep == false) {
            throw new Exception("prepare of [$sql] failed " . 
                                $this->connection->error);
        }
		$exec = $prep->bind_param("s", $username);
		if ($exec == false) {
			throw new Exception("Bind param of [$sql] failed " . $prep->error);
		}
        $exec = $prep->execute();
        if ($exec == false) {
        	throw new Exception("execute of [$sql] failed " . $prep->error);
        }
        $exec = $prep->bind_result($headline, $content);
        if ($exec == false) {
        	throw new Exception("execute of [$sql] failed " . $prep->error);
        }
        $entryList = new EntryList();
	    while ($prep->fetch()) {
	        $entryList->add(new Entry($headline, $content, $username));
	    }
        return $entryList;
	} 
}
