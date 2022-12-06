<?php

require_once  dirname(getcwd(),1).'/Model/dbOperation.php';

class unSubscribe {
    public $email; 
   function __construct() {
    if(isset($_POST['email']) && !empty($_POST['email'])) {
        if(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)) {
             $this->email = $_POST['email'];
             $dbOperation = new dbOperations();
             $response = $dbOperation->unsubscribeUser($this->email);
             
             if($response == "1") {
                echo "User Unsubscribed successfully";
            }
            else {
                 echo "Failed to unsubscribe the user";
             }
        }
   }
}

function __destruct() {
     unset($email);
}
}
new unSubscribe();
?>