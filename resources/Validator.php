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
        if (!empty($this->errors[$err])) {
            return "error";
        }
        return "";
    }

    function constraint($name, $method, $constraint, $customMsg)
    {
        switch ($method) {
            case "post":
                $expression = $_POST[$name];
                break;
            case "get":
                $expression = $_GET[$name];
                break;
            default:
                $expression = $_POST[$name];
                break;
        }

        switch ($constraint) {
            case "required":
                $customMsg = !empty($customMsg) ? $customMsg : "$name is required.";
                if (empty($expression)) $this->add(new Error($name, $customMsg));
        }
    }

    function showAllErrors()
    {
        require("templates/form_error_message.php");
    }

    function valid()
    {
        foreach ($this->errors as $error) {
            $error = $this->validate($error->getTitle());
            if (!empty($error)) {
                return false;
            }
        }
        return true;
    }
}
