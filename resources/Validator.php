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
        require("templates/form_error_message.php");
    }
}