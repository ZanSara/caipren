<?php

abstract class Model {
	protected $mysqli;

	public function __construct() {
	       
	    // Connect to DB
        $username = "6786_utentesql";
        $password = "databasecai";
        $hostname = "localhost";
        $dbname = "6786_prenotazioni";
	    
		$this->mysqli = new mysqli($hostname, $username, $password, $dbname);
		if($this->mysqli->connect_error != NULL) {
			die("Errore durante la connessione al server."); //. $mysqli->connect_error);
		}
		
		// In case I need some further operations to happen at construction time
		$this->customConstructor();
	}
}
?>
