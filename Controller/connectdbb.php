<?php 
class connectDB {
    public $servername = "localhost";
    public $username = "root";
    public $passsword = ""; //=V~MBEM]14-|j]#d
    public $dbName = "email_challenge";
    public $db ;

    public function databaseConn() {
        $this->db =  new mysqli("localhost","root","");
    

        if($this->db->connect_error) {
            die("Failed to connect database");
        }
           
        if(mysqli_select_db($this->db, $this->dbName)){
            return $this->db;
        }
        else {
            // create with parameter binding 
            $sql = "CREATE DATABASE " . $this->dbName;
            if ($this->db->query($sql)) {                
                $createTable = "CREATE TABLE USERS(id INTEGER PRIMARY KEY AUTO_INCREMENT,email VARCHAR(35) ,otp INTEGER,isverify BOOLEAN ,isactivate BOOLEAN)";
                mysqli_select_db($this->db, $this->dbName);
                if ($this->db->query($createTable) === TRUE) {
                    return $this->db;
                } else {
                    die($this->db->error);//echo "Failed to create table " . $this->db->error;
                }
            } else {
                die($this->db->error);//echo "Error creating database: " . $this->db->error;
            }
        }
    }

function __destruct() { 
    $this->db->close(); 
 }
} 

?>
