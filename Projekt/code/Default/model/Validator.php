<?php

class Validator {
	/**
	 * @param string input value
	 * @return Boolean true if param contains tags
	 */
	public static function containsTags($string) {
		$cleanString = strip_tags($string);
		if ($cleanString != $string) {
			return true;
		}
		return false;
	}
}