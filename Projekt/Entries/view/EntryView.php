<?php

class EntryView {
	private static $headlinePost = "headline";
	private static $contentPost = "textContent";
	private static $saveEntryButton = "saveEntry";
	private static $addEntryGet = "create";
	private static $ownEntriesGet = "ownEntries";
	private static $editEntryGet = "edit";
	private static $deleteEntryGet = "delete";
	private static $commentEntryGet = "comment";
	private static $commentContentPost = "commentContent";
	private static $commentButton = "comment";
	private static $exitCommentButton = "exitComment";

	private $sessionHandler;
	
	public function __construct(Session $sessionHandler) {
		$this->sessionHandler = $sessionHandler;
	}
	
	public function getEntryHTML(EntryList $entryList) {
		$entryHTML = "";
		$entries = $entryList->getEntries();
		if ($this->isViewingOwnEntries()) {
			$header = "<h2>Mina inlägg</h2>";
		} else {
			$header = "<h2>Alla inlägg</h2>";
		}
		$usernameHTML = ""; 
		for ($i = count($entries) - 1; $i >= 0; $i--) {
			if (!$this->isViewingOwnEntries()) {
				$usernameHTML = "<p class='user'>Skapat av: " 
								. $entries[$i]->getUsername() . "</p>";
			}
			$commentsHTML = "";
			$comments = $entries[$i]->getComments();
			for ($j = count($comments) - 1; $j >= 0; $j--) {
				$commentsHTML .= "<div class='comment'>
								<p>" . $comments[$j]->getUsername() . ": 
								" . $comments[$j]->getContent() . "</p>
								</div>";
			}
			$changeEntryLinks = "";
			if ($this->sessionHandler->isSameUser($entries[$i]->getUsername())) {
				$changeEntryLinks = "<a href='?" . self::$editEntryGet . "=$i'>Ändra</a>
									<a href='?" . self::$deleteEntryGet . "=$i'>Ta bort</a>";
			}
			if (isset($_GET[self::$commentEntryGet]) && 
				$_GET[self::$commentEntryGet] == $i) {
				$commentsHTML .= $this->getCommentForm();
			}
			$commentLink = "";
			if (!$this->isViewingOwnEntries()) {
				$commentLink = "<a href='?" . self::$commentEntryGet . "=$i#$i'>Kommentera</a>";
			}
			$entryHTML .= "<div class='entry' id='$i'>
								<div class='entryLinks'>
									$commentLink
									$changeEntryLinks
								</div>
								<h2>". $entries[$i]->getHeadline()."</h2>
								<p>". $entries[$i]->getContent()."</p>
								$usernameHTML
								<h3>Kommentarer (" . count($comments) . ")</h3>
								$commentsHTML
							</div>";
		}
		return "<div id='content'>$header $entryHTML</div>";
	}
	public function isCommenting() {
		return isset($_GET[self::$commentEntryGet]);
	}
	public function isSavingComment() {
		return isset($_POST[self::$commentButton]);
	}
	public function getEntryID() {
		return intval($_GET[self::$commentEntryGet]);
	}
	public function getComment() {
		return $_POST[self::$commentContentPost];
	}
	private function getCommentForm() {
		return "<form method='post'>
					<textarea rows='3' cols='30' maxlength='100' name='"
					. self::$commentContentPost . "'></textarea>
					<input type='submit' name='" . self::$commentButton . 
					"' value='Kommentera' />
					<a class='cancel' href='?'><input type='button' name='" . self::$exitCommentButton . 
					"' value='Avbryt' /></a>
				</form>";
	}
	public function getEntryForm() {
		return "<div id='content'>
			<form method='post' enctype='multipart/form-data'>
				<h2>Skapa nytt inlägg</h2>
				" . $this->sessionHandler->getMessage() . "
				<label for='headline' >Rubrik :</label>
				<input type='text' name='" . self::$headlinePost . 
				"' id='headline' value='' maxlength='40' />
				<br/>
				<textarea rows='5' cols='40' maxlength='500' name='" 
				. self::$contentPost . "'></textarea>
				<input type='submit' name='" . self::$saveEntryButton . 
				"'  value='Skapa' />
			</form>
		</div>";
	}
	public function isAddingEntry() {
		return isset($_GET[self::$addEntryGet]);
	}
	public function isViewingOwnEntries() {
		return isset($_GET[self::$ownEntriesGet]);
	}
	public function isSavingEntry() {
		return isset($_POST[self::$saveEntryButton]);
	}
	public function getHeadline() {
		return $_POST[self::$headlinePost];
	}
	public function getContent() {
		return $_POST[self::$contentPost];
	}
	public function getMenuHTML() {
		return "
		<div id='menu'>
			<ul>
				<li><a href='?'>Alla inlägg</a></li>
				<li><a href='?" . self::$ownEntriesGet . 
					"'>Mina inlägg</a></li>
				<li><a href='?" . self::$addEntryGet . 
					"'>Nytt inlägg</a></li>
				<li><a href='?" . LoginView::$logoutString . 
					"'>Logga ut</a></li>
			</ul>
			
		</div>";
	}
}
