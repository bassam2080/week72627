<?php
class Dbconnection {

    private $dbHost     = "169.254.2.167";
    private $dbUsername = "root";
    private $dbPassword = "12345";
    private $dbName     = "docker_database";

    public function connect(){
                    try{
                $conn = new PDO("mysql:host=".$this->dbHost.";dbname=".$this->dbName, $this->dbUsername, $this->dbPassword);
                $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $conn;
            }catch(PDOException $e){
                die("Failed to connect with MySQL: " . $e->getMessage());
            }
        }
    }
?>