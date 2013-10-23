<?php

class DateMaker {
	/**
	 * @var String - Ex. "Måndag"
	 */
	private static $dayOfWeek;
	
	/**
	 * @var String - number 1-31
	 */
	private static $dayNr;
	
	/**
	 * @var String - Ex. "December"
	 */
	private static $month;
	
	/**
	 * @var String - Four digits Ex. 2010
	 */
	private static $year;
	
	/**
	 * @var String - Ex. "12:22:43"
	 */
	private static $time;

	/**
	 * Set variable values by default
	 */
	public function __construct() {
		self::$dayOfWeek = $this->setWeekDay();
		self::$dayNr = $this->setDateNr();
		self::$month = $this->setMonth();
		self::$year = $this->setYear();
		self::$time = $this->setTime();
	}
	
	/**
	 * @return String weekday Ex. "Måndag"
	 */
	private function setWeekDay() {
		$dayOfWeekNr = date("N");
		switch ($dayOfWeekNr) {
			case 1:
				return "Måndag";
			case 2:
				return "Tisdag";
			case 3:
				return "Onsdag";
			case 4:
				return "Torsdag";
			case 5:
				return "Fredag";
			case 6:
				return "Lördag";
			case 7:
				return "Söndag";
		}
	}

	/**
	 * @return String day nr 1-31
	 */
	private function setDateNr() {
		return date("j");
	}
	
	
	/**
	 * @return String month Ex. "December"
	 */
	private function setMonth() {
		$monthNr = date("n");
		switch ($monthNr) {
			case 1:
				return "Januari";
			case 2:
				return "Februari";
			case 3:
				return "Mars";
			case 4:
				return "April";
			case 5:
				return "Maj";
			case 6:
				return "Juni";
			case 7:
				return "Juli";
			case 8:
				return "Augusti";
			case 9:
				return "September";
			case 10:
				return "Oktober";
			case 11:
				return "November";
			case 12:
				return "December";
		}
	}
	
	/**
	 * @return String year four digits Ex. "2010"
	 */
	private function setYear() {
		return date("Y");
	}
	
	/**
	 * @return String time formatted "10:22:43"
	 */
	private function setTime() {
		date_default_timezone_set("CET");
		return date("H\:i\:s");
	}
	
	/**
	 * @return 	String - complete date string 
	 * 			ex. "Tisdag, den 3 Maj år 2013. Klockan är [10:22:12]."
	 */
	public function getDateString() {
		$dateString = "<p>" . self::$dayOfWeek . " den " . self::$dayNr . " "
					 . self::$month . " år " . self::$year . ". Klockan är ["
					 . self::$time . "].</p>";
		
		return $dateString;
	}
}
