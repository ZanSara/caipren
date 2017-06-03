<?php
include_once("config.php");

abstract class Model {
	protected $mysqli;

	public function __construct($dbhost=DB_HOSTNAME, $dbuser=DB_USERNAME, $dbpwd=DB_PASSWORD, $dbname = DB_DBNAME) {
		$this->mysqli = new mysqli($dbhost, $dbuser, $dbpwd, $dbname);
		if($this->mysqli->connect_error != NULL) {
			die("Errore durante la connessione al server. ");// . $mysqli->connect_error);
		}
		$this->mysqli->set_charset("utf8");
		
		// In case I need some further operations to happen at construction time
		$this->customConstructor();
		
	}

	public function __destruct() {
		$this->mysqli->close();
	}
	
	
	// *************** Utilities **********************************************
	
	// Correct the strtotime output for leap years
	// Input: date MUST STRICTLY BE in DD-MM-YYYY format
	// Output: unless specified otherwhise, date in "z" format: "day number", db-friendly
	public function correct_date($date, $year, $format="z"){
        if($year % 4 == 0){
            return date($format, strtotime($date)-1); //   ONLY dd-mm-yyyy OR mm/dd/yyyy are recognized correctly
        }else{
            return date($format, strtotime($date));
        }
	}
}
?>
