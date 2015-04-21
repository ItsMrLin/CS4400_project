<?php

class User
{
    protected $username = "";
    protected $password = "";

    function User($username, $password)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!empty($username) && !empty($password)) {
            $this->username = $username;
            $this->password = $password;
        } else {
            $this->username = isset($_SESSION['username']) ? $_SESSION['username'] : "";
            $this->password = isset($_SESSION['password']) ? $_SESSION['password'] : "";
        }
    }

    public function isStaff()
    {
        $mysqli = require("db_connection.php");
        $staffQuery = "SELECT * FROM Staff WHERE Username='$this->username'";
        $result = $mysqli->query($staffQuery);

        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function login()
    {
        if (empty($this->username)) return false;

        $mysqli = require("db_connection.php");
        $query = "SELECT * FROM User WHERE Username='$this->username' AND Password='$this->password'";
        $result = $mysqli->query($query);

        if ($result->num_rows > 0) {
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
        header("Location:login-register.php");
        exit();
    }

    public function loggedIn()
    {
        return (!empty($this->username) && !empty($this->password)) ? true : false;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }
}