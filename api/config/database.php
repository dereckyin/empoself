<?php
// used to get mysql database connection

include_once 'conf.php';

class Database{
 
    // specify your own database credentials
    public $conn;
 
    // get the database connection
    public function getConnection(){

        $conf = new Conf();
 
        $this->conn = null;
 
        try{
            $this->conn = new PDO("mysql:host=" . $conf::$host . ";dbname=" . $conf::$db_name, $conf::$username, $conf::$password);
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;
    }
}

?>