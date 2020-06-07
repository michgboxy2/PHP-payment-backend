<?php 
  class Database {
    // DB Params
    private $host = 'us-cdbr-east-05.cleardb.net';
    private $db_name = 'heroku_cd971134c384a0d';
    private $username = 'be801e4f2ba5b9';
    private $password = '4fbaa96d';
    private $conn;

    // DB Connect
    public function connect() {
      $this->conn = null;

      try { 
        $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch(PDOException $e) {
        echo 'Connection Error: ' . $e->getMessage();
      }

      return $this->conn;
    }
  }