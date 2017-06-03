<?php
	class DatiView extends View {
		public function show() {
		
			$this->title = "Dati Service - CAI Sovico";
			$this->scripts = array();
			
            echo parent::__get("JSON");
		}
		
	}
?>
