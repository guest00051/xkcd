<?php

require_once  dirname(getcwd(),1).'/Model/dbOperation.php';
require_once  __DIR__.'/mailHandler.php';

class sendMail {   
   public $email;

function __construct() {
    if(isset($_POST['Email']) && !empty($_POST['Email'])) {
        if(filter_var($_POST['Email'],FILTER_VALIDATE_EMAIL)) {
             $this->email = $_POST['Email'];
             $dbOperation = new dbOperations();
             $response = $dbOperation->userExists($this->email);
             
             if($response == "-1") {
                 
                $otp = $this->generateOTP();
                $res = $dbOperation->createUser($this->email,$otp);
                if($res == '1') {
                    $mail = new mailHandler();
                    if($mail->sendOTP($this->email,$otp))
                       {
                           echo "User created successfully";
                       }
                       
                    else {
                        echo "Failed to send mail";   
                    }
                }
                else echo "Failed to create the user";
            }
            else {
                 echo $response;  ///////////////////////////////////////////
             }
        }

        else {
            echo "Please enter valid address";
        }
    }
    else {
        echo "Please refresh page and enter email";
    }

}


  function generateOTP() {
      return rand(111111,999999);
  }
  
 
  
function __destruct() {
    unset($email);
}

}

new sendMail();
?>