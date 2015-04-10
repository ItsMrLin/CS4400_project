<?php

class User {
    protected $username;
    protected $password;

    public function login($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }
}