<?php
	class CalendarView extends View {
		public function show() {
			$this->title = "Prenotazioni - CAI Sovico";
			$this->scripts = array();
			
            include("templates/headTemplate.php");
			include("templates/bannerTemplate.php");
			
			include("templates/errorAlert.php");
			include("templates/bookingIDAlert.php");
			include("templates/aboutModal.php");
			include("templates/advancedModal.php");
			include("templates/findModal.php");
			include("templates/newBookingModal.php");
			
		    include("templates/calendarTemplate.php");
		    include("templates/footTemplate.php");
		}
	}
?>
