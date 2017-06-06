<?php
	include("Model.php");

	class LoginModel extends Model {
	
        public function login($username, $password){
            echo $username." ".$password;
        }
    }
?>
