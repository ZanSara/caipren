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
	    	    
	    echo "<br>POST!<br>";
	        
        try{
            if (isset($_POST['delbooking'])){
                $this->model->deleteReservation((int)$_POST['prenid']);
                
            }else{
                if (!isset($_POST['prenid'])){
                    $this->model->makeReservation();
                    //$open = 1;
                    
                }else{
                    $this->model->updateReservation((int)$_POST['prenid']);
                    //$last_prenid = (int)$_POST['prenid'];
                }
            }
        }catch (Exception $e){
            $this->model->setError("Operazione non riconosciuta");
            
            // Fix me once popup are ready
            echo $e;
        }
          
	}
}
?>
