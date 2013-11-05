<?php

class Message {
	// Login & logout
	const usernameMissing = "Användarnamn saknas";
	const credentialsWrong = "Felaktigt användarnamn/lösenord";
	const passwordMissing = "Lösenord saknas";
	const loginSuccess = "Inloggningen lyckades";
	const loginSuccessWithSave = "Inloggningen lyckades,
								 och vi kommer ihåg dig nästa gång";
	const loginFromCookie = "Inloggning lyckades via cookies";
	const faultyCookie = "Felaktig information i kaka!";
	const logoutSuccess = "Du har loggat ut";
	
	// Register
	const usernameTooShort = "Användarnamnet har för få tecken. Minst 3 tecken";
	const usernameTooLong = "Användarnamnet har för många tecken. Max 9 tecken";
	const usernameFaulty = "Användarnamnet innehåller ogiltiga tecken";
	const passwordTooShort = "Lösenorden har för få tecken. Minst 6 tecken";
	const passwordTooLong = "Lösenordet har för många tecken. Max 16 tecken";
	const passwordNoMatch = "Lösenord matchar inte";
	const usernameTaken = "Användarnamnet är redan upptaget";
	const registerSuccess = "Registrering av ny användare lyckades";
	
	// Entries
	const headlineMissing = "Rubrik saknas";
	const contentMissing = "Inlägg för kort";
	const entryFaulty = "Inlägget innehåller ogiltiga tecken";
	const entrySuccess = "Inlägget har sparats";

	// Comments
	const commentMissing = "Kommentar saknas";
	const commentFaulty = "Kommentaren innehåller ogiltiga tecken";

	const errorMessage = "Ett fel inträffade";
}
