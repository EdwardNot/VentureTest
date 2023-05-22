<?php
    class Email {
        // DB information
        private $conn;
        private $table = 'emails';

        // Email properties from table
        public $id;
        public $full_email;
        public $email_domain;
        public $email_body;

        // cunstructor get reference to database entity to work with
        public function __construct($db){
            $this->conn = $db;
        }

        // GET Emails
        public function read(){
            // Emails come to user ordered firstly by domain, secondly by body (account name)
            //! Idealy it is unsafe to just let everyone get emails without authentication, or let anyone use such function at all, but it is part of task.
            $query = 'SELECT full_email, email_domain, email_body
            FROM ' . $this->table . '
            ORDER BY email_domain, email_body';

            // Checking if constructor worked normally
            if (!$this->conn){
                echo 'CONN ERROR';
                return null;
            }
            
            // preparing and executing statement
            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            // return result
            return $stmt;
            
        }

        public function create() {
            // We separate information of email on full email, domain and body, to sort it after
            $query = 'INSERT INTO ' . 
                $this->table . ' 
                SET
                    full_email = :full_email,
                    email_domain = :email_domain,
                    email_body = :email_body;';

            // Checking if constructor worked normally
            if (!$this->conn){
                echo 'CONN ERROR';
                return;
            }

            // Preparing statement
            $stmt = $this->conn->prepare($query);

            // Preprocess text for query (it is important if emails contain '+' or other ASCII symbols allowed in emails, but which can ruin query)
            $this->full_email = htmlspecialchars(strip_tags($this->full_email));
            $this->email_domain = htmlspecialchars(strip_tags($this->email_domain));
            $this->email_body = htmlspecialchars(strip_tags($this->email_body));
            
            // Binding actual values to query
            $stmt->bindParam(':full_email', $this->full_email);
            $stmt->bindParam(':email_domain', $this->email_domain);
            $stmt->bindParam(':email_body', $this->email_body);

            if ($stmt->execute()){
                return true;
            } else {

                printf("ERROR: %s.\n", $stmt->error);

                return false;
            }
        }
    }