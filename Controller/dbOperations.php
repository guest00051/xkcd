<?php
require_once 'connectdb.php';
require_once  __DIR__.'/mailHandler.php';
class dbOperations
{
    public $dbObj;
    public $dbCon;

    public function __construct()
    {

        $this->dbObj = new connectDB();
        $this->dbCon = $this->dbObj->databaseConn();
        
    }


    //check existing user -- 
    public function userExists($email)
    {
        $userEmail = trim($email);
        $userEmail = htmlspecialchars($userEmail, ENT_QUOTES);
        $userEmail = mysqli_real_escape_string($this->dbCon, $userEmail);
        $queryStr = 'SELECT * FROM USERS WHERE email=?';
        $query = $this->dbCon->prepare($queryStr);
        $query->bind_param('s', $userEmail);
        $query->execute();
        $result = $query->get_result();
        $array = $result->fetch_assoc();
        
        if ($result->num_rows > 0) {
            if ($array['isactivate'] == 1 && $array['isverify'] == 1) {
                return "1";
            } 
            // else if ($array['isverify'] == 0) {
                // regenerate OTP
                // $res = $this->regenerateOTP($userEmail);
                // if($res == '1'){                   return '2';  //regenerate the otp and mail     }
                // else if ($res == '2')  {     return 'Failed to send mail'; }
                // else return 'Failed to generate otp';
                
            // }
            else if ($array['isactivate'] == 0) {
                return "3";
            } else {
                return "Something went wrong (DB)";
            }

        } else {
            return "-1";
        }

    }

    //create user with email and otp 
    public function createUser($email, $otp)
    {
        $userEmail = trim($email);
        $userEmail = htmlspecialchars($userEmail, ENT_QUOTES);
        $userEmail = mysqli_real_escape_string($this->dbCon, $userEmail);
        $queryStr = "INSERT INTO USERS(email,otp,isverify,isactivate) VALUES (?,?,?,?);";
        $query = $this->dbCon->prepare($queryStr);
        $bool = 0;
        $query->bind_param('siii', $userEmail, $otp, $bool, $bool);
        $query->execute();
       
        if ($query->affected_rows > 0) {
            return '1';
        } else {
            return '0';
        }

    }

    //Generate otp
    public function verifyOTP($email, $otp)
    {
        $userEmail = trim($email);
        $userEmail = htmlspecialchars($userEmail, ENT_QUOTES);
        $userEmail = mysqli_real_escape_string($this->dbCon, $userEmail);
        $queryStr = 'SELECT * FROM USERS WHERE email=? and otp=?';
        $query = $this->dbCon->prepare($queryStr);
        $query->bind_param('si', $userEmail, $otp);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            $isSub = $this->subscribeUser($email);
            if ($isSub == '1') {
                return '1';
            } else {
                return '0';
            }
        } else {
            return "0";
        }

    }

    //Regenerate otp
    public function regenerateOTP($email)
    {
        $userEmail = trim($email);
        $userEmail = htmlspecialchars($userEmail, ENT_QUOTES);
        $userEmail = mysqli_real_escape_string($this->dbCon, $userEmail);
        $queryStr = 'UPDATE USERS set otp = ? WHERE email=?';
        $query = $this->dbCon->prepare($queryStr);
        $bool = 1;
        $regenOTP = rand(111111,999999);
        $query->bind_param('is', $regenOTP, $userEmail);
        $query->execute();
        
        if ($query->affected_rows > 0) {
            $mail = new mailHandler();
            if($mail->resendOTP($userEmail,$regenOTP) == true)
                  {
                    return '1'.$userEmail.$regenOTP;
                  }
                else {  return '2';
                    //    echo "Failed to send mail";   
                    }
        } else {
            $mail = new mailHandler();
            $mail->resendOTP($userEmail, $regenOTP);
            return '0';
        }
    }

    //User subscribe
    public function subscribeUser($email)
    {
        $userEmail = trim($email);
        $userEmail = htmlspecialchars($userEmail, ENT_QUOTES);
        $userEmail = mysqli_real_escape_string($this->dbCon, $userEmail);
        $queryStr = 'UPDATE USERS set otp = ?,isverify=?,isactivate=?  WHERE email=?';
        $query = $this->dbCon->prepare($queryStr);
        $bool = 1;
        $resetOTP = -111;
        $query->bind_param('iiis', $resetOTP, $bool, $bool, $userEmail);
        $query->execute();
        
        if ($query->affected_rows > 0) {
            return '1';
        } else {
            return '0';
        }
    }

    //User unsubscribe
    public function unsubscribeUser($userEmail)
    {
        $userEmail = trim($userEmail);
        $userEmail = htmlspecialchars($userEmail, ENT_QUOTES);
        $userEmail = mysqli_real_escape_string($this->dbCon, $userEmail);
        $queryStr = 'UPDATE USERS set isactivate=?  WHERE email=?';
        $query = $this->dbCon->prepare($queryStr);
        $bool = 0;
        $query->bind_param('is', $bool, $userEmail);
        $query->execute();
        if ($query->affected_rows > 0) {
            return '1';
        } else {
            return '0';
        }
    }
}


?>