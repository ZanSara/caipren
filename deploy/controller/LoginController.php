<?php

class LoginController extends Controller {

	private $model, $view;

	public function __construct($params) {
		$this->params = $params;
	}
	

	public function baseAction() {
	    
	    $this->model = new LoginModel();
	    $this->view = new LoginView();
	    
	    // Process an eventual POST call
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		    self::processLogin();
		}
	    
	    //$params = array("JSON" => ($this->model->getDati($_GET['gestione'], $_GET['prenid'])) );
		//$this->view->addParams($params);
		$this->view->show();
	}
	
	
	public function processLogin() {
	    echo "Fake Login running!";
	}
    
}
?>
