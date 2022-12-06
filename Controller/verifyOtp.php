<?php
require_once  dirname(getcwd(),1).'/Model/dbOperation.php';

class verifyOtp
{

    function __construct()
    {
        $json = file_get_contents('php://input'); // Getting json multiple data 
        $data = json_decode($json, true);
        
        if (filter_var($data['Email'], FILTER_VALIDATE_EMAIL)) {
            if ((is_numeric($data['OTP']))) {
                if ((strlen((string)$data['OTP'])) == 6) {
                    $dbOperation = new dbOperations();
                    $response = $dbOperation->verifyOTP($data['Email'], $data['OTP']);
                    if ($response == '1')
                    {
                        
                        echo "Congratulation Email has been subscribed.";
                    }

                    else
                        echo "Wrong OTP";
                }
                else {
                  echo "Please enter 6 digit OTP ";
                }
            } 
            else {
               echo "Please enter numeric OTP";
            }
        } else {
            echo "Please refresh email.";
        }

    }



}
new verifyOtp();
?>