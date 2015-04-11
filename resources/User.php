<?php

class User {
    protected $username;
    protected $password;

    function User($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }

    public function login() {
        $mysqli = require("db_connection.php");
        $query = "SELECT * FROM User WHERE Username='$this->username' AND Password='$this->password'";
        $result = $mysqli->query($query);
        if ($result->num_rows == 1) {
            session_start();
            $_SESSION['username'] = $this->username;
            return true;
        } else {
            return false;
        }
    }
}