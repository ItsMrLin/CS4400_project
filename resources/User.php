<?php

class User
{
    protected $username = "";
    protected $password = "";

    function User($username = "", $password = "")
    {
        if (isset($username) && isset($password)) {
            $this->username = $username;
            $this->password = $password;
        } else {
            session_start();
            $this->username = $_SESSION['username'];
            $this->password = $_SESSION['password'];
        }
    }

    public function login()
    {
        $mysqli = require("db_connection.php");
        $query = "SELECT * FROM User WHERE Username='$this->username' AND Password='$this->password'";
        $result = $mysqli->query($query);

        if ($result->num_rows == 1) {
            session_start();
            $_SESSION['username'] = $this->username;
            $_SESSION['password'] = $this->password;
            return true;
        } else {
            return false;
        }
    }

    public function logout()
    {
        $_SESSION = array();
        $this->username = "";
        $this->password = "";
        session_destroy();
    }

    public function loggedIn()
    {
        return (isset($this->username) && isset($this->password)) ? true : false;
    }
}