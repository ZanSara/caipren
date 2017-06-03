<?php
include_once("config.php");

abstract class Model {

	protected $mysqli, $year;

	public function __construct($dbhost=DB_HOSTNAME, $dbuser=DB_USERNAME, $dbpwd=DB_PASSWORD, $dbname = DB_DBNAME) {
		$this->mysqli = new mysqli($dbhost, $dbuser, $dbpwd, $dbname);
		if($this->mysqli->connect_error != NULL) {
			die("Errore durante la connessione al server. ");// . $mysqli->connect_error);
		}
		$this->mysqli->set_charset("utf8");
		
		$this->year = date('Y');
		
		// In case I need some further operations to happen at construction time
		$this->customConstructor();
		
	}
	
	public function customConstructor(){}

	public function __destruct() {
		$this->mysqli->close();
	}
	
	
	// *************** Utilities **********************************************
	
	public function getYear(){
	    return $this->year;
	}
	
	// Correct the strtotime output for leap years
	//  Input: date MUST STRICTLY BE in DD-MM-YYYY format
	//  Output: unless specified otherwhise, date in "z" format: "day number", db-friendly
	public function correct_date($date, $year, $format="z"){
        if($year % 4 == 0){
            return date($format, strtotime($date)-1); //   ONLY dd-mm-yyyy OR mm/dd/yyyy are recognized correctly
        }else{
            return date($format, strtotime($date));
        }
	}
	
	// Convert month number into month name
    public function convert_month($monthnum){

        switch ($monthnum):
            case '6':
                return 'Giugno';
            case '7':
                return 'Luglio';
            case '8':
                return 'Agosto';
            case '9':
                return 'Settembre';
            default:
                return 'FuoriStagione';
        endswitch;
        
        return "Errore";
    } 
    
     // Convert month name into month number
    public function decode_month($monthname){

        switch ($monthname):
            case 'Giugno':
                return '06';
            case 'Luglio':
                return '07';
            case 'Agosto':
                return '08';
            case 'Settembre':
                return '09';
            default:
                return 'ERRORE';
        endswitch;
        
        return "Errore";
    } 
}
?>
