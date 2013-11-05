<?php

class CommentDAL extends DAL {
	/**
	 * @param Comment object
	 * @param Entry object
	 */
	public function addComment(Comment $comment, 
								Entry $entry) {
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
								
	/**
	 * @param Comment object
	 * @param Entry object
	 */
	public function deleteComment(Comment $comment, 
									Entry $entry) {
		$username = $comment->getUsername();
		$content = $comment->getContent();
		$entryContent = $entry->getContent();
		$entryUsername = $entry->getUsername();
		$entryHeadline = $entry->getHeadline();

        $sql = "DELETE FROM Comments WHERE Comment = ? AND UserID = 
        		(SELECT UserID FROM UserList WHERE Username = ?) AND
        		EntryID = (SELECT EntryID FROM BlogEntries WHERE Headline = ?
        		AND Content = ? AND UserID = 
        		(SELECT UserID FROM UserList WHERE Username = ?))";
        $prep = $this->connection->prepare($sql);
        if ($prep == false) {
            throw new Exception("comments prepare of [$sql] failed " . 
                                $this->connection->error);
        }
        $exec = $prep->bind_param("sssss", $content,
        									$username,
        									$entryHeadline,
        									$entryContent,
        									$entryUsername);
        if ($exec == false) {
            throw new Exception("comments bind param of [$sql] failed " . $prep->error);
        }
        $exec = $prep->execute();
        if ($exec == false) {
            throw new Exception("comments execute of [$sql] failed " . $prep->error);
        }
	}

	/**
	 * @param Comment object
	 * @param Comment object
	 * @param Entry object
	 */
	public function editComment(Comment $oldComment, 
								Comment $newComment,
								Entry $entry) {
		$username = $newComment->getUsername();
		$oldContent = $oldComment->getContent();
		$newContent = $newComment->getContent();
		$entryContent = $entry->getContent();
		$entryUsername = $entry->getUsername();
		$entryHeadline = $entry->getHeadline();

        $sql = "UPDATE Comments SET Comment = ? WHERE Comment = ? AND UserID = 
                (SELECT UserID FROM UserList WHERE Username = ?) AND EntryID = 
                (SELECT EntryID FROM BlogEntries WHERE Headline = ? AND 
                Content = ? AND UserID = 
                (SELECT UserID FROM UserList WHERE Username = ?))";
        $prep = $this->connection->prepare($sql);
        if ($prep == false) {
            throw new Exception("comments prepare of [$sql] failed " . 
                                $this->connection->error);
        }
        $exec = $prep->bind_param("ssssss", $newContent, 
        									$oldContent, 
        									$username, 
        									$entryHeadline, 
        									$entryContent, 
        									$entryUsername);
        if ($exec == false) {
            throw new Exception("comments bind param of [$sql] failed " . $prep->error);
        }
        $exec = $prep->execute();
        if ($exec == false) {
            throw new Exception("comments execute of [$sql] failed " . $prep->error);
        }
	}
	
	/**
	 * @param Entry object
	 * @return CommentList object
	 */
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