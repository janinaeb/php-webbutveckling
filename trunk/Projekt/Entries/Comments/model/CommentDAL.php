<?php

class CommentDAL extends DAL {
	
	public function addComment(Comment $comment, $entry) {
		$headline = $entry->getHeadline();
		$commentUsername = $comment->getUsername();
		$entryUsername = $entry->getUsername();
		$content = $comment->getContent();
		$sql = "INSERT INTO Comments(UserID, EntryID, Comment) 
				VALUES ((SELECT UserID FROM UserList WHERE Username = ?),
				(SELECT EntryID FROM BlogEntries WHERE Headline = ? AND UserID =
				(SELECT UserID FROM UserList WHERE Username = ?)), ?)";
		$prep = $this->connection->prepare($sql);
        if ($prep == false) {
            throw new Exception("comments prepare of [$sql] failed " . $this->connection->error);
        }
		
        $exec = $prep->bind_param("ssss", $commentUsername, $headline, $entryUsername, $content);
		if ($exec == false) {
			throw new Exception("comments bind param of [$sql] failed " . $prep->error);
		}
        $exec = $prep->execute();
        if ($exec == false) {
        	throw new Exception("comments execute of [$sql] failed " . $prep->error);
        }
	}
	
	public function getCommentsFromEntry($entry) {
		$headline = $entry->getHeadline();
		$username = $entry->getUsername();
		$sql = "SELECT Comments.Comment, UserList.Username FROM Comments 
				INNER JOIN UserList ON Comments.UserID = UserList.UserID 
				WHERE EntryID = 
				(SELECT EntryID FROM BlogEntries WHERE Headline = ? AND UserID = 
				(SELECT UserID FROM UserList WHERE Username = ?))";
        
        $prep = $this->connection->prepare($sql);
        if ($prep == false) {
            throw new Exception("comments prepare of [$sql] failed " . $this->connection->error);
        }

        $exec = $prep->bind_param("ss", $headline, $username);
		if ($exec == false) {
			throw new Exception("comments bind param of [$sql] failed " . $prep->error);
		}
        
        $exec = $prep->execute();
        if ($exec == false) {
        	throw new Exception("comments execute of [$sql] failed " . $prep->error);
        }

        $exec = $prep->bind_result($content, $commentUsername);
        if ($exec == false) {
        	throw new Exception("comments execute of [$sql] failed " . $prep->error);
        }

        $commentList = new CommentList();

	    while ($prep->fetch()) {
	        $commentList->add(new Comment($content, $commentUsername));
	    }
        return $commentList;
	}
}