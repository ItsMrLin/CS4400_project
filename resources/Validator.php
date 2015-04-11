<?php
include_once("Error.php");

class Validator
{
    protected $errors = array();

    function add(Error &$error)
    {
        $this->errors[$error->getTitle()] = $error;
    }

    function validate($err)
    {
        if (isset($this->errors[$err])) {
            echo "error";
        }
    }

    function showAllErrors()
    {
        $errors = $this->errors;
        require("templates/form_error_message.php");

//        if (isset($this->errors[$er])) {
//            $error_message = require_once("templates/form_error_message.php");
//            echo $error_message;
//        }
    }
}