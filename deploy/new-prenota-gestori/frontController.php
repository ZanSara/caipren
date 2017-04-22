<?php 

// Front Controller is the main controller of the page, the first being loaded
// and the one loading all others. Now a little overkill, having only one page, 
// but possibly useful later.

spl_autoload_register(function($controllerName) {
	if(file_exists("controller/" . $controllerName . ".php")) {
		include "controller/" . $controllerName . ".php";
	}
});

class FrontController {

	const DEFAULT_CONTROLLER = "CalendarController";
	const DEFAULT_ACTION = "baseAction";
	const BASE_PATH = "caipren\/deploy\/new-prenota-gestori\/";//"prenota-gestori\/";

	private $controller = self::DEFAULT_CONTROLLER;
	private $action = self::DEFAULT_ACTION;
	private $params = array();

    // Constructor of FrontController
	public function __construct() {
		$this->parseUrl();
	}

    // Routing process.
    // FrontController parses the URL in order to load the correct subcontroller.
	private function parseUrl() {
	
	    // ASK THESE!!
		$path = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");
		$path = preg_replace("/^" . $this::BASE_PATH . "(.*)/", "$1", $path);
		
		echo $path;

		if(strlen($path) > 0) {
			$components = explode("/", $path, 3);
			
			// to avoid a null field here
			if(!isset($components[2])) {
						$components[2] = "";
					}
			
			// Assess which subcontroller I requested and save it.
			if(isset($components[0])) {
			    $subController = ucfirst(strtolower($components[0]) . "Controller");
			    
				if(class_exists($subController)) {
					$this->controller = $subController;
				} else {
					$components[2] = $components[2] . $components[0];  // ??
				}
			}
			
			// Assess which action from that subcontroller I requested and save it. 
			if(isset($components[1])) {
				if(class_exists( $subController) ) {
					
					if(method_exists(new $subController(array()), $components[1] . "Action")) {
						$this->action = strtolower($components[1]) . "Action";		
					} else {
						if(!isset($components[2])) {
							$components[2] = "";
						}
						$components[2] = $components[2] . $components[1];
					}
				} else {
					$components[2] = $components[2] . $components[1];
				}
				
			}
			
			// Read the parameters, if any, I get from the URL and save them.
			if(isset($components[2])) {
				$paramArray = explode("/", $components[2]);

				foreach ($paramArray as $param) {
					$this->params[] = $param;
				}
			}
		}
	}

    // Create the subcontroller, give him parameters and then run the selected action.
	public function run() {
		$controller = new $this->controller($this->params);
		$action = $this->action;
		$controller->$action();
	}
}
?>
