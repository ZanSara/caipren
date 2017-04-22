<?php
	include("Model.php");

	class CalendarModel extends Model {
	
	    private $year, $firstday, $lastday, $today, $last_prenid, $error_flag, $error_msg;
	    
	    
	    // Sets some date variables reused later in the class.
	    public function customConstructor(){

	        $this->year = date('Y');
	        
	        // Correct the strtotime output for leap years
            if($this->year % 4 == 0){
                $this->firstday = date('z', strtotime('01-06-'.$this->year)-1); //   ONLY dd-mm-yyyy OR mm/dd/yyyy are recognized correctly
                $this->lastday = date('z', strtotime('1-10-'.$this->year)-1);
            }else{
                $this->firstday = date('z', strtotime('01-06-'.$this->year));
                $this->lastday = date('z', strtotime('1-10-'.$this->year));
              }
            
            $this->today = date('z') - $this->firstday;
            $last_prenid = 0;
	    }
	    
	    
	    // Some utility getters
	    public function getYear(){
	        return $this->year;
	    }
	    public function getFirstDay(){
	        return $this->firstday;
	    }
	    public function getLastDay(){
	        return $this->lastday;
	    }
	    public function getToday(){
	        return $this->today;
	    }
	    public function getLastPrenid(){
	        return $this->last_prenid;
	    }
	    public function getErrorFlag(){
	        return $this->error_flag;
	    }
	    public function getErrorMsg(){
	        return $this->error_msg;
	    }
	    
	    // Error setter, in case
	    public function setError($error_msg){
	        $this->error_flag = true;
	        $this->error_msg = $error_msg;
	    }
	    public function resetError(){
	        $this->error_flag = false;
	        $this->error_msg = "[Nessun errore]";
	    }
	    
	    
	    // Output a list of user-friendly date that fits the first column of the table
	    public function buildDates(){

            $dateColumn = array();
            
            for($absday = $this->firstday; $absday<$this->lastday; $absday++){
                $absdate = DateTime::createFromFormat('z', $absday);
               
                $day = $absdate->format('j');
                $weekday = $absdate->format('N');
                $month = $absdate->format('n');

                switch ($month):
                    case '6':
                        $monthname = 'Giu';
                        break;
                    case '7':
                        $monthname = 'Lug';
                        break;
                    case '8':
                        $monthname = 'Ago';
                        break;
                    case '9':
                        $monthname = 'Set';
                        break;
                    default:
                        $monthname = 'ERR';
                endswitch;
                
                switch ($weekday):
                    case '1':
                        $weekdayname = 'L';
                        break;
                    case '2':
                    case '3':
                        $weekdayname = 'M';
                        break;
                    case '4':
                        $weekdayname = 'G';
                        break;
                    case '5':
                        $weekdayname = 'V';
                        break;
                    case '6':
                        $weekdayname = 'S';
                        break;
                    case '7':
                        $weekdayname = 'D';
                        break;
                    default:
                        $weekdayname = '?';
                endswitch;
                
                $dateColumn[] = array($absday, $day, $monthname, $month, $weekdayname);
            }
            return $dateColumn;
        }
             
        
        // Output the gestori list for the second column of the table.
        // Builds an entry for each day, so Template does not need to process anything.
        public function getGestori(){   
        
            $listaGestori = array();
            $gestori = array();
            
            for($absday = $this->firstday; $absday<$this->lastday; $absday++){
                $query = "  SELECT  * 
                            FROM    Pernottamenti 
                            WHERE 
                                    stagione = {$this->year}
                                AND
                                    gestione = 1 
                                AND 
                                    giorno_inizio= {$absday}";
                $result = $this->mysqli->query($query);
		        if(!$result) {
			        throw new Exception("Errore inatteso durante il caricamento dei dati della gestione del giorno ".$absday-$this->firstday."."); // . $this->mysqli->error);
		        }
		        
		        while ($row = $result->fetch_array()) {
                    $gestori[] = $row;        // This appends the NEW gestione to $gestori, which is usually NOT empty!
                }
            
                $gestore = array();
                $gestore[] = sizeOf($gestori); // to let Template know how many gestori I found for that day.
                if (sizeOf($gestori) != 0){
                    $gestore[] = $gestori[0]['id']; 
                    $gestore[] = $gestori[0]['nome'];
                }
                
                // Remove from $gestori all gestori that are on their last day
                foreach($gestori as $g){
                    if ($g["giorno_inizio"]+ $g["durata"]-1 <= $absday){ 
                        $key = array_search($g, $gestori);
                        unset($gestori[$key]);
                        $gestori = array_values($gestori);
                    }
                }
                
                $listaGestori[] = $gestore;
            }
            return $listaGestori;
        }
        
        
        
        // Output the list of booking required to fill the table.
        // Builds an entry for each day, so Template does not need to process anything.
        public function getBookings(){
            
            $bookingsList = array();
            $bag = array();
            
            for($absday = $this->firstday; $absday<$this->lastday; $absday++){
                $query = "  SELECT  * 

                            FROM    Pernottamenti AS p 
                                INNER JOIN 
                                    Colori AS c 
                                ON  
                                    p.colore = c.ID
                            WHERE 
                                    stagione = {$this->year}
                                AND

                                    gestione = 0
                                AND 
                                    giorno_inizio= {$absday}";
                $result = $this->mysqli->query($query);
		        if(!$result) {
			        throw new Exception("Errore inatteso durante il caricamento dei dati della gestione del giorno ".$absday-$this->firstday."."); // . $this->mysqli->error);
		        }
		        
                while ($row = $result->fetch_array()) {
                    $bag[] = $row; // This appends the NEW bookings to $bag, which is usually NOT empty!
                }
                
                $pren = array();
                // For each booking in the bag and for each bed they booked, set ID and color for that cell.
                foreach($bag as $booking){
                    for($p = 0; $p<$booking['posti']; $p++){
                        $pren[] = array( $booking['id'], $booking['colore']);
                    }
                    
                    if ($booking['giorno_inizio']+ $booking['durata']-1 <= $absday){
                        $key = array_search($pren, $bag);  // need to look for it, because the index is not normalized...
                        unset($bag[$key]);  // pop from array
                        $bag = array_values($bag); // fix indices
                    }
                }
                $bookingsList[] = $pren;   
            }
            return $bookingsList;
        }
        
        
        
        
    
    
    
    
    
    
        // *************** RESERVATIONS MANAGEMENT *******************************

        // Perform a reservation into DB
        public function makeReservation(){
        
            echo "Making reservation...";

            $validData = self::validate();

            // Retrieve last color used
            $query = "  SELECT ID 
                        FROM Colori 
                        WHERE last IN (
                                        SELECT MAX(last) 
                                        FROM Colori
                                      )";
            $result1 = $this->mysqli->query($query);
	        if(!$result1) {
		        throw new Exception("Errore inatteso durante il caricamento dei dati relativi all'ultimo colore."); // . $this->mysqli->error);
	        }
	        $last_color = $result1->fetch_array()[0];
	        
	        // Retrieve count colors
	        $query = "  SELECT COUNT(*) 
	                    FROM Colori";
            $result2 = $this->mysqli->query($query);
	        if(!$result2) {
		        throw new Exception("Errore inatteso durante il caricamento dei dati relativi ai colori."); // . $this->mysqli->error);
	        }
            $ncolor = $result2->fetch_array()[0];
            
            // Decide what's the next one to get
            if ($last_color >= $ncolor-1 ){
                $new_color = 0;
            }else{
                $new_color = $last_color+1;
            }
            
	       
	       // Actually write reservation in DB
	       
	       // Fix this
	       $values = "(NULL, '".
	                    $validData['nome']."', '".
                        $validData['telefono']."', '".
                        $validData['provincia']."', '".
                        $validData['arrivo']."', '".
                        $this->year."', '".
                        $validData['durata']."', '".
                        $validData['posti']."', '".
                        $validData['note']."', '".
                        $validData['gestione']."', '".
                        $validData['resp']."', '".
                        $new_color."')";
                        
	       $query = "  INSERT INTO 
	                        `Pernottamenti` (
	                            `id`, 
	                            `nome`, 
	                            `tel`, 
	                            `provincia`, 
	                            `giorno_inizio`, 
	                            `stagione`, 
	                            `durata`, 
	                            `posti`, 
	                            `note`, 
	                            `gestione`, 
	                            `responsabile`, 
	                            `colore`)
                            VALUES 
                                {$values}";
            $main_result = $this->mysqli->query($query);
            
            if (!$main_result) {
               throw new Exception("Errore interno durante la prenotazione:<br>la prenotazione NON è stata effettuata.<br>Avverti il webmaster.");//.$result);
               
            } else {
                
                // FIX ME
                // $prenid = mysqli_insert_id($dbhandle);
                
                // Update Last Color: the field 'last' should be bigger for the last used color
                $query = "  SELECT MAX(last) 
                            FROM Colori";
                $result3 = $this->mysqli->query($query);
	            if(!$result3) {
		            throw new Exception("Errore inatteso durante il caricamento dell'ultimo colore.<br>la prenotazione È stata comunque effettuata.<br>Avverti il webmaster."); // . $this->mysqli->error);
	            }
                $max_last = $result3->fetch_array()[0] + 1;
                
                
                $query = "  UPDATE Colori 
                            SET last = {$max_last}
                            WHERE ID = {$new_color}";
                            
                $result4 = $this->mysqli->query($query);
	            if(!$result4) {
		            throw new Exception("Errore inatteso durante l'aggiornamento del colore.<br>la prenotazione È stata comunque effettuata.<br>Avverti il webmaster."); // . $this->mysqli->error);
	            }
            }
            
            
            echo "Run everything!";
        }



        // Update a reservation into DB
        public function updateReservation($prenid){

            $validData = self::validate();
            
            // Update reservation in DB
           $query = "  UPDATE 
                            `Pernottamenti`
                        SET
                            `nome` = '{$validData['nome']}',
                            `tel` = '{$validData['telefono']}',
                            `provincia` = '{$validData['provincia']}',
                            `giorno_inizio` = '{$validData['arrivo']}',
                            `durata` = '{$validData['durata']}',
                            `posti` = '{$validData['posti']}',
                            `note` = '{$validData['note']}',
                            `gestione` = '{$validData['gestione']}',
                            `responsabile` = '{$validData['resp']}'
                        WHERE 
                            `ID` = '{$prenid}'";
                            
            $result = $this->mysqli->query($query);
            if(!$result) {
                throw new Exception("Errore interno:<br>L'aggiornamento NON è stato effettuato.<br>Avverti il webmaster."); // . $this->mysqli->error);
            }
            
        }


        // Delete reservation into DB
        public function deleteReservation($prenid){
        
            $query = "  DELETE FROM 
                            Pernottamenti 
                        WHERE 
                            ID = {$prenid}";
                        
            $result = $this->mysqli->query($query);
            if(!$result) {
	            throw new Exception("Errore interno al server.<br>La prenotazione NON è stata cancellata.<br>Avverti il webmaster."); // . $this->mysqli->error);
            }
        }





    // *************** SERVER-SIDE DATA VALIDATION ****************************
    // Throws errors only if js validation failed to spot bad data.

        public function validate() {
        
            // FIX THI FUNCTION!!
             if ($_POST['arrivo']== '')  throw new Exception("Data di arrivo non valida");
            $replaced = str_replace("/", "-", $_POST['arrivo']);
            if (!(substr($replaced, -4)== $this->year)){
                $replaced .= "-".$this->year;
            }
            $absdate = date('z', strtotime($replaced)-1);
            
            $gestione = 0;
            if (isset($_POST['gestione'])) {
               $gestione = 1;
            }
            
            return array(
                'nome' => $_POST['nome'],
                'telefono' => $_POST['telefono'],
                'provincia' => $_POST['provincia'],
                'arrivo' => $absdate,
                'durata' => $_POST['durata'],
                'posti' => $_POST['posti'],
                'note' => $_POST['note'],
                'gestione' => $gestione,
                'resp' => $_POST['responsabile'],
            );
        
        
            /*
            if ($_POST['nome']== '')  throw new Exception("Nome del cliente non valido");
            if ($_POST['telefono']== '')  throw new Exception("Numero di telefono non valido");
            if ((int)($_POST['durata'])<= 0 or (int)($_POST['durata'])>= 122) throw new Exception("Durata della prenotazione non valida");
            
            if ($_POST['arrivo']== '')  throw new Exception("Data di arrivo non valida");
            $replaced = str_replace("/", "-", $_POST['arrivo']);
            if (!(substr($replaced, -4)== $year)){
                $replaced .= "-".$year;
            }
            $absdate = mysqli_real_escape_string($dbhandle, date('z', strtotime($replaced)-1));

	        $gestione = 0;
            if (isset($_POST['gestione'])) {
               $gestione = 1;
            }
            if (!$gestione && ( (int)($_POST['posti'])<= 0 or (int)($_POST['posti'])> 16) ) throw new Exception("Numero di posti prenotati non valido!");
            
            if ($_POST['responsabile']== '') throw new Exception("Nome del responsabile non valido!");

            return array(
                'nome' => mysqli_real_escape_string($dbhandle, $_POST['nome']),
                'telefono' => mysqli_real_escape_string($dbhandle, $_POST['telefono']),
                'provincia' => mysqli_real_escape_string($dbhandle, $_POST['provincia']),
                'arrivo' => (int)mysqli_real_escape_string($dbhandle, $absdate),
                'durata' => (int)mysqli_real_escape_string($dbhandle, $_POST['durata']),
                'posti' => mysqli_real_escape_string($dbhandle, $_POST['posti']),
                'note' => mysqli_real_escape_string($dbhandle, $_POST['note']),
                'gestione' => $gestione,
                'resp' => mysqli_real_escape_string($dbhandle, $_POST['responsabile']),
            );
            */
            
            //checkAssertions($validData);
        }

    // *************** DB ASSERTIONS ****************************************

        public function checkAssertions($data){
            // Notice: here I assume that no booking will have ID = 0, because 0 means basically "do not check"
            
            $dayslist = array();
            for($giorno=$data['arrivo']; $giorno < ($data['arrivo']+$data['durata']); $giorno++ ){
                if(!$data['gestione']){
                    $what2Do = "SUM(posti)";
                }else{
                    $what2Do = "COUNT(*)";
                }
                $string = "SELECT ".$what2Do." FROM Pernottamenti
                            WHERE stagione = ".$year." AND ( giorno_inizio <= ".$giorno." AND (giorno_inizio + durata) > ".$giorno.")
                            AND gestione = ".$data['gestione']." AND id <> ".$prenid;
                $result = mysqli_fetch_array(mysqli_query($dbhandle, $string));
                if(!$data['gestione']){
                    if($result[0] + $data['posti'] > 16){
                        $dayslist[] = DateTime::createFromFormat('z', $giorno);
                    }            
                }else{
                    if($result[0] > 0){
                        $dayslist[] = DateTime::createFromFormat('z', $giorno);
                    }
                }
            }

            if(count($dayslist) > 0){
                $errorstring = "";
                foreach ($dayslist as $day){
                    $errorstring = $errorstring.'<br><br>'.(string)$day->format('d-m-Y');
                }
                mysqli_close($dbhandle);
                if (!$data['gestione']){
                    throw new Exception("Impossibile prenotare!<br>Il Rifugio è già pieno nelle date:".$errorstring."<br><br>NON è stata registrata nessuna modifica: ripetere l'operazione.");
                }else{
                    throw new Exception("Attenzione!<br>C'è già un gestore in queste date: ".$errorstring."<br><br>NON è stata registrata nessuna modifica: ripetere l'operazione.");
                }
            }
       
        }
        
    }
?>
