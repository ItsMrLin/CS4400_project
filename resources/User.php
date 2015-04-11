<?php

class User
{
    protected $username = "";
    protected $password = "";

    function User($username, $password)
    {
        if (!empty($username) && !empty($password)) {
            $this->username = $username;
            $this->password = $password;
        } else {
            $this->username = isset($_SESSION['username']) ? $_SESSION['username'] : "";
            $this->password = isset($_SESSION['password']) ? $_SESSION['password'] : "";
        }
    }

    public function login()
    {
        $mysqli = require("db_connection.php");
        $query = "SELECT * FROM User WHERE Username='$this->username' AND Password='$this->password'";
        $result = $mysqli->query($query);

        if ($result->num_rows == 1) {
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
        return (!empty($this->username) && !empty($this->password)) ? true : false;
    }
}