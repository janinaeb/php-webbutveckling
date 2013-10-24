<?php

class Menu {
	public function getMenuHTML() {
		return "
		<div id='menu'>
			<ul>
				<li><a href='?'>Startsida</a></li>
				<li><a href='?'>Nytt inlägg</a></li>
				<li><a href='?'>Mina inlägg</a></li>
			</ul>
			
		</div>";
	}
	public function getContent() {
		return "
		<div id='content'>
			<h2>Blogg</h2>
		</div>";
	}
}
