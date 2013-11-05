<?php

class EntryView {
	/**
	 * @var string
	 */
	private static $headlinePost = "headline";
	/**
	 * @var string
	 */
	private static $contentPost = "textContent";
	/**
	 * @var string
	 */
	private static $saveEntryButton = "saveEntry";
	/**
	 * @var string
	 */
	private static $addEntryGet = "create";
	/**
	 * @var string
	 */
	private static $ownEntriesGet = "ownEntries";
	/**
	 * @var string
	 */
	private static $editEntryGet = "edit";
	/**
	 * @var string
	 */
	private static $deleteEntryGet = "delete";
	/**
	 * @var string
	 */
	private static $commentEntryGet = "comment";
	/**
	 * @var string
	 */
	private static $commentContentPost = "commentContent";
	/**
	 * @var string
	 */
	private static $commentButton = "comment";
	/**
	 * @var string
	 */
	private static $exitButton = "abort";
	/**
	 * @var string
	 */
	private static $deleteButton = "delete";
	/**
	 * @var string
	 */
	private static $editCommentGet = "editComment";
	/**
	 * @var string
	 */
	private static $entryCommentGet = "c";
	/**
	 * @var string
	 */
	private static $deleteCommentGet = "deleteComment";
	
	/**
	 * @var Session object
	 */
	private $sessionHandler;
	
	/**
	 * @param Session object
	 */
	public function __construct(Session $sessionHandler) {
		$this->sessionHandler = $sessionHandler;
	}
	
	/**
	 * @param EntryList object
	 * @return string HTML
	 */
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
				$commentLinks = "";
				if ($comments[$j]->getUsername() ==
					$this->sessionHandler->getUsername() &&
					!$this->isViewingOwnEntries()) {
					$commentLinks = "<div class='commentLinks'>
										<a href='?" . 
										self::$editCommentGet . "=$i&"
										 . self::$entryCommentGet . "=$j'>Redigera</a>
										<a href='?" . 
										self::$deleteCommentGet ."=$i&"
										. self::$entryCommentGet . "=$j'>Ta bort</a>
									</div>";
				}
				$commentsHTML .= "<div class='comment'>
									$commentLinks
									<p>" . $comments[$j]->getUsername() . ": 
									" . $comments[$j]->getContent() . "</p>
								</div>";
			}
			if (isset($_GET[self::$commentEntryGet]) && 
				$_GET[self::$commentEntryGet] == $i) {
				$commentsHTML .= $this->getCommentForm(null);
			}
			if (!$this->isViewingOwnEntries()) {
				$links = "<a href='?" . self::$commentEntryGet . "=$i#$i'>Kommentera</a>";
			} else {
				if ($this->sessionHandler->isSameUser($entries[$i]->getUsername())) {
					$links = "<a href='?" . self::$editEntryGet . "=$i'>Ändra</a>
										<a href='?" . self::$deleteEntryGet . "=$i'>Ta bort</a>";
				}
			}
			$entryHTML .= "<div class='entry' id='$i'>
								<div class='entryLinks'>
									$links
								</div>
								<div class='entryContent'>
									<h2>". $entries[$i]->getHeadline()."</h2>
									<p>". $entries[$i]->getContent()."</p>

									$usernameHTML
									<h3>Kommentarer (" . count($comments) . ")</h3>
									$commentsHTML
								</div>
							</div>";
		}
		return "$header <p>" . $this->sessionHandler->getMessage() .  "</p> $entryHTML";
	}
	/**
	 * @return boolean true if get is set
	 */
	public function isCommenting() {
		return isset($_GET[self::$commentEntryGet]);
	}
	/**
	 * @return boolean
	 */
	public function isSavingComment() {
		return isset($_POST[self::$commentButton]);
	}
	/**
	 * @return int id in get
	 */
	public function getID() {
		if (isset($_GET[self::$commentEntryGet])) {
			return intval($_GET[self::$commentEntryGet]);
		} else if (isset($_GET[self::$editEntryGet])) {
			return intval($_GET[self::$editEntryGet]);
		} else if (isset($_GET[self::$deleteEntryGet])) {
			return intval($_GET[self::$deleteEntryGet]);
		} else if (isset($_GET[self::$editCommentGet])) {
			return intval($_GET[self::$editCommentGet]);
		} else if (isset($_GET[self::$deleteCommentGet])) {
			return intval($_GET[self::$deleteCommentGet]);
		} 
	}
	/**
	 * @return int id in get
	 */
	public function getCommentID() {
		return $_GET[self::$entryCommentGet];
	}
	/**
	 * @return string from post
	 */
	public function getComment() {
		return $_POST[self::$commentContentPost];
	}
	/**
	 * @param Comment object
	 * @return string HTML
	 */
	public function getCommentForm($comment) {
		if ($comment == null) {
			$commentContent = "";
		} else {
			$commentContent = $comment->getContent();
		}
		return "<form method='post'>
					<p>" . $this->sessionHandler->getMessage() . "</p>
					<textarea rows='3' cols='30' maxlength='100' name='"
					. self::$commentContentPost . "'>$commentContent</textarea><div class='margin'>
					<input type='submit' class='btn' name='" . self::$commentButton . 
					"' value='Kommentera' />
					<a class='cancel' href='?'><input class='btn' type='button' name='"
					 . self::$exitButton . 
					"' value='Avbryt' /></a></div>
				</form>";
	}
	/**
	 * @param Entry object
	 * @return String HTML
	 */
	public function getEntryForm($entry) {
		if ($entry == null) {
			$headline = "";
			$content = "";
		} else {
			$headline = $entry->getHeadline();
			$content = $entry->getContent();
		}
		return "<form method='post' enctype='multipart/form-data'>
				<h2>Skapa nytt inlägg</h2>
				<p>" . $this->sessionHandler->getMessage() . "</p>
				<label for='headline' >Rubrik :</label>
				<input type='text' name='" . self::$headlinePost . 
				"' id='headline' maxlength='40' value='$headline' />
				<br/>
				<textarea rows='5' cols='40' maxlength='500' name='" 
				. self::$contentPost . "'>$content</textarea><div class='margin'>
				<input type='submit' class='btn' name='" . self::$saveEntryButton . 
				"'  value='Spara'/> <a class='cancel' href='?'>
				<input type='button' class='btn' name='" . self::$exitButton . 
					"' value='Avbryt' /></a></div>
			</form>";
	}
	/**
	 * @param object Comment/Entry
	 * @return String HTML
	 */
	public function getDeleteForm($input) {

		if (is_a($input, "Comment")) {
			$string = "kommentaren";
		} else {
			$string = "inlägget";
		}
		return "<form method='post'>
					<p>Är du säker på att du vill ta bort $string?</p>
					<input type='submit' name='" . self::$deleteButton . 
					"' class='btn' value='Ta bort' /> <a class='cancel' href='?'>
					<input class='btn' type='button' name='" . self::$exitButton . 
					"' value='Avbryt' /></a>
				</form>";
	}
	/**
	 * @return boolean true if get is set
	 */
	public function isAddingEntry() {
		return isset($_GET[self::$addEntryGet]);
	}
	/**
	 * @return boolean true if get is set
	 */
	public function isEditingEntry() {
		return isset($_GET[self::$editEntryGet]);
	}
	/**
	 * @return boolean true if get is set
	 */
	public function isDeletingEntry() {
		return isset($_GET[self::$deleteEntryGet]);
	}
	/**
	 * @return boolean true if post is set
	 */
	public function isPressingDelete() {
		return isset($_POST[self::$deleteButton]);
	}
	/**
	 * @return boolean true if get is set
	 */
	public function isEditingComment() {
		return isset($_GET[self::$editCommentGet]);
	}
	/**
	 * @return boolean true if get is set
	 */
	public function isDeletingComment() {
		return isset($_GET[self::$deleteCommentGet]);
	}
	/**
	 * @return boolean true if get is set
	 */
	public function isViewingOwnEntries() {
		return isset($_GET[self::$ownEntriesGet]);
	}
	/**
	 * @return boolean true if post is set
	 */
	public function isSavingEntry() {
		return isset($_POST[self::$saveEntryButton]);
	}
	/**
	 * @return string headline from post
	 */
	public function getHeadline() {
		return $_POST[self::$headlinePost];
	}
	/**
	 * @return string content from post
	 */
	public function getContent() {
		return $_POST[self::$contentPost];
	}
	/**
	 * @return string HTML menu choices
	 */
	public function getMenuHTML() {
		return "
		<div class='navbar navbar-inverse'>
			<ul class='nav navbar-nav'>
				<li class=''><a href='?'>Alla inlägg</a></li>
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
