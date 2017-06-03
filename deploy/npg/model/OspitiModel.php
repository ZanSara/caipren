<?php
	include("Model.php");

	class OspitiModel extends Model {
	
	    private $year, $giornoInizio, $giornoFine;
	    
	    // Sets some date variables reused later in the class.
	    public function customConstructor(){
            $this->year = date('Y');
	    }
	    
	    
	    // Some utility getters
	    public function getYear(){
	        return $this->year;
	    }
	    
	    public function setInizio($dataInizio){
	        $this->giornoInizio = self::correct_date($dataInizio, $this->year);
	    }
	    public function setFine($dataFine){
	        $this->giornoFine = self::correct_date($dataFine, $this->year);
	    }
	    
	    
	    
	    public function getNumPren(){
            $query = "  SELECT  COUNT(*) 
                        FROM    Pernottamenti
                        WHERE 
                                stagione = {$this->year}
                            AND 
                            NOT (
                                giorno_inizio+durata-1 <  {$this->giornoInizio}
                            OR 
                                giorno_inizio > {$this->giornoFine} )";
            $result = $this->mysqli->query($query);
	        if(!$result) {
		        throw new Exception("Errore inatteso durante il caricamento del conto dei pernottamenti."); // . $this->mysqli->error);
	        }
	        return $result->fetch_array()[0];
	    }
	    
	    
	    public function getNumGest(){
	        $query = "  SELECT  COUNT(*) 
                        FROM    Pernottamenti
                        WHERE
                                stagione = {$this->year}
                            AND 
                                gestione = 1 
                            AND 
                            NOT (
                                giorno_inizio+durata-1 <  {$this->giornoInizio}
                            OR 
                                giorno_inizio > {$this->giornoFine})";
            $result = $this->mysqli->query($query);
	        if(!$result) {
		        throw new Exception("Errore inatteso durante il caricamento del conto delle gestioni."); // . $this->mysqli->error);
	        }
	        return $result->fetch_array()[0];
	    }
	    
	    
	    public function getData(){
	        $query = "  SELECT  * 
                        FROM    Pernottamenti
                        WHERE
                                stagione = {$this->year}
                            AND 
                            NOT (
                                giorno_inizio+durata-1 <  {$this->giornoInizio}
                            OR 
                                giorno_inizio > {$this->giornoFine})
                        ORDER BY 
                                giorno_inizio";
            $result = $this->mysqli->query($query);
	        if(!$result) {
		        throw new Exception("Errore inatteso durante il caricamento dei dati degli ospiti."); // . $this->mysqli->error);
	        }
	        return $result->fetch_all(MYSQLI_ASSOC) ;
	    }
	    
        
    }
?>
