<?php
	class LoginView extends View {
		public function show() {
		
			$this->title = "Login - CAI Sovico";
			$this->scripts = array();
			
            include("templates/loginTemplate.php");
		}
		
	}
?>
