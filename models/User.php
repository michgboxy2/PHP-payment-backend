<?php

class User {
    private $conn;
    private $table = 'users';

    //Create User
    public $id;
    public $firstName;
    public $lastName;
    public $email;
    public $expired_at;
    public $account_status;

    //constructor with db

    public function __construct($db){
        $this->conn = $db;
    }

    //Get Users
    public function read() {
        //Create query
        $query = 'SELECT
            id,
            firstName,
            lastName,
            email,
            account_status,
            expired_at
          FROM
            ' . $this->table. '
          ORDER BY
            id DESC';

            //Prepare statement
            $stmt = $this->conn->prepare($query);

            //Execute query
            $stmt->execute();

            return $stmt;
    }

    public function checKiFuserExist() {
        //Create query
        $query = 'SELECT
            id,
            firstName,
            lastName,
            email,
            account_status,
            expired_at
          FROM
            ' . $this->table. '
          WHERE
                email = ?
                LIMIT 0,1';

            //Prepare statement
            $stmt = $this->conn->prepare($query);

             //Bind param
            $stmt->bindParam(1, $this->email);

            //Execute query
            $stmt->execute();

            return $stmt;
    }

    public function read_single() {
                //Create query
                $query = 'SELECT
                id,
                firstName,
                lastName,
                email,
                account_status,
                expired_at
              FROM
                ' . $this->table. '
              WHERE
                email = ?
                LIMIT 0,1';

        //Prepare statement
        $stmt = $this->conn->prepare($query);

        //Bind param
        $stmt->bindParam(1, $this->email);

        //Execute query
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        //SET PROPERTIES
        $this->email = $row['email'];
        $this->firstName  = $row['firstName']; 
        $this->lastName  = $row['lastName']; 
        $this->account_status  = $row['account_status']; 
        $this->expired_at  = $row['expired_at']; 

    }


    public function create(){
        $time = strtotime("today");
        $final = date("Y-m-d", strtotime("+1 month", $time));
        //create query
        $query = 'INSERT INTO ' . $this->table . '
            SET
              firstName = :firstName,
              lastName  = :lastName,
              email     = :email,
              account_status = :account_status,
              expired_at = :expired_at';

            //Prepare statement
            $stmt = $this->conn->prepare($query);

            //clean data
            $this->firstName = htmlspecialchars(strip_tags($this->firstName));
            $this->lastName = htmlspecialchars(strip_tags($this->lastName));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->account_status = htmlspecialchars(strip_tags(1));
            $this->expired_at = htmlspecialchars(strip_tags($final));

            //Bind data
            $stmt->bindParam(':firstName', $this->firstName);
            $stmt->bindParam(':lastName', $this->lastName);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':account_status', $this->account_status);
            $stmt->bindParam(':expired_at', $this->expired_at);

            //Execute query
            if($stmt->execute()){
                return true;
            }

            //print error if someting goes  wrong
            printf("Error: $s.\n", $stmt->error);

            return false;           
    }

    public function update(){
                //create query
                $query = 'UPDATE ' . $this->table . '
                SET
                  account_status = :account_status,
                  expired_at = :expired_at
                WHERE
                  email = :email';
    
                //Prepare statement
                $stmt = $this->conn->prepare($query);
    
                //clean data
                $this->email = htmlspecialchars(strip_tags($this->email));
                $this->account_status = htmlspecialchars(strip_tags($this->account_status));
                $this->expired_at = htmlspecialchars(strip_tags($this->expired_at));
    
                //Bind data
                $stmt->bindParam(':email', $this->email);
                $stmt->bindParam(':account_status', $this->account_status);
                $stmt->bindParam(':expired_at', $this->expired_at);
    
                //Execute query
                if($stmt->execute()){
                    return true;
                }
    
                //print error if someting goes  wrong
                printf("Error: $s.\n", $stmt->error);
    
                return false;           

    }
}