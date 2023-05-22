<?php
    class Database {
        // Database parameters
        private $host = 'database'; //'database' is container name. Docker will do all work by itself.
        private $db_name = '';
        private $username = '';
        private $password = '';
        private $conn;

        public function __construct()
        {
            // Take parameters from .env file for safety
            //! Don't use root information! It is unsafe!
            $this->db_name = $_ENV["MYSQL_DATABASE"];
            $this->username = $_ENV['MYSQL_USER'];
            $this->password = $_ENV['MYSQL_PASSWORD'];
        }

        // Connect function
        public function connect() {
            $this->conn = null;

            try{
                // using mysql pod to connect to mysql container
                $this->conn = new PDO('mysql:host=' . $this->host . ';port=3306;dbname=' . $this->db_name, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e){
                echo 'Connection Error: ' . $e->getMessage();
            }

            return $this->conn;
        }
    }