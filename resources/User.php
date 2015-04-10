<?php

class User {
    protected $username;
    protected $password;

    function User($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }

    public function login() {

    }
}