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
}
?>
