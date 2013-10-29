<?php

class EntryDAL extends DAL {
	
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
	
	public function getAllEntries() {
		$sql = "SELECT BlogEntries.Headline, BlogEntries.Content, UserList.Username FROM UserList INNER JOIN BlogEntries ON UserList.UserID=BlogEntries.UserID";
        
        $prep = $this->connection->prepare($sql);
        if ($prep == false) {
            throw new Exception("prepare of [$sql] failed " . $this->connection->error);
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
	
	public function getEntries($username) {
		$sql = "SELECT Headline, Content FROM BlogEntries WHERE UserID = (SELECT UserID FROM UserList WHERE Username = ?)";
        
        $prep = $this->connection->prepare($sql);
        if ($prep == false) {
            throw new Exception("prepare of [$sql] failed " . $this->connection->error);
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
