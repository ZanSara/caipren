<?php

class CalendarController extends Controller {

	private $model, $view;

	public function __construct($params) {
		$this->params = $params;
	}

	public function baseAction() {
	    
	    $this->view = new CalendarView();
		$this->model = new CalendarModel();
		
		// Process an eventual POST call
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		    self::processBooking();
		}

        // Pass here what's needed into the View/Template rendering
		$params = array("Year" => ($this->model->getYear()),
		    "Today" => ($this->model->getToday()),
		    "FirstDay" => ($this->model->getFirstDay()),
		    "LastDay" => ($this->model->getLastDay()),
		    "Dates" => ($this->model->buildDates()),
		    "Gestori" => ($this->model->getGestori()),
		    "Bookings" => ($this->model->getBookings()),
		    "LastPrenID" => ($this->model->getLastPrenid()),
		    "ErrorFlag" => ($this->model->getErrorFlag()),
		    "ErrorMsg" => ($this->model->getErrorMsg()),
		     );
			
		$this->view->addParams($params);
		$this->view->show();
	}
	
	
	// Operates on bookings, depending on the type of incoming data
	public function processBooking() {
	    	    
	    //echo "<br>POST!<br>";
	    //print_r($_POST);
	        
        try{
            
            if ( $_POST['prenid'] == null ||  $_POST['prenid'] == "" ||  $_POST['prenid'] == 0 ){
                //echo "<br>makeReservation!<br>";
                $this->model->makeReservation();
                //$open = 1;
                
            }else{
                
                if($_POST['prenid'] < 0){
                    //echo "<br>delbookig!<br>";
                    $this->model->deleteReservation((int)$_POST['prenid']);
                    
                } else {
                    //echo "<br>updateReservation!<br>";
                    $this->model->updateReservation((int)$_POST['prenid']);
                    //$last_prenid = (int)$_POST['prenid'];
                }
            }
        }catch (Exception $e){
            $this->model->setError("Operazione non riconosciuta");
            
            // Fix me once popups are ready
            echo $e;
        }
          
	}
}
?>
