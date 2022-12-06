<?php

require_once dirname(getcwd(),1).'/Model/connectdb.php';
class mailHandler {

function sendOTP($email,$otp) {
         $subject = "OTP Verification - XKCD Challenge";       
         $message = '<html><head><style>.outer{margin:50px auto;width:600px;padding:20px 0}.inner{border-bottom:1px solid #eee}.user{font-size:1.4em;color:#00466a;text-decoration:none;font-weight:600}hr{border:none;border-top:1px solid #eee}h2{text-align:center;background:green;margin:0 auto;padding:0 10px;color:#fff;border-radius:4px}</style></head><body><div><div class="outer"><div class="inner"><p class="user">'.$email.'</p></div><p>Hi,</p><p>We received a request to verify your email address for Comics.<br>Your verification code is:</p><h2>'.$otp.'</h2><p>If you did not request this code,<br><b>Please Do not forward or give this code to anyone.</b><br><br>Thanks for visit my challenge,<br>Chirag panchal - XKCD challenge</p><hr></div></div></body></html>';         
         $header = "From:xkcdchallenge2022@gmail.com \r\n";
         $header .= "Cc:cpanchal2022@gmail.com \r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";
         $retval = mail ($email,$subject,$message,$header);
         
         if( $retval) {
            return true;
         }else {
            die("Failed to send mail");
         }
}



function resendOTP($email,$otp) {
         $subject = "OTP Reverification - XKCD Challenge";       
         $message = '<html><head><style>.outer{margin:50px auto;width:600px;padding:20px 0}.inner{border-bottom:1px solid #eee}.user{font-size:1.4em;color:#00466a;text-decoration:none;font-weight:600}hr{border:none;border-top:1px solid #eee}h2{text-align:center;background:green;margin:0 auto;padding:0 10px;color:#fff;border-radius:4px}</style></head><body><div><div class="outer"><div class="inner"><p class="user">'.$email.'</p></div><p>Hi,</p><p>We received a request to verify your email address for Comics.<br>Your verification code is:</p><h2>'.$otp.'</h2><p>If you did not request this code,<br><b>Please Do not forward or give this code to anyone.</b><br><br>Thanks for visit my challenge,<br>Chirag panchal - XKCD challenge</p><hr></div></div></body></html>';         
         $header = "From:xkcdchallenge2022@gmail.com \r\n";
         $header .= "Cc:cpanchal2022@gmail.com \r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";
         $retval = mail ($email,$subject,$message,$header);
         
         if( $retval) {
            return true;
         }else {
            die("Failed to send mail");
         }
   }
}



?>