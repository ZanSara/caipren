<?php
	class LogoutView extends View {
		public function show() {
		    header("Location: /caipren/#".(string)date('j-m', strtotime('yesterday')) );
            die();
		}
		
	}
?>
