<?php
include_once("Error.php");

class Validator
{
    protected $errors = array();
    protected $enabled = false;

    function add(Error &$error)
    {
        $this->errors[$error->getTitle()] = $error;
    }

    function validate($err)
    {
        if (!$this->enabled) return "";

        if (!empty($this->errors[$err])) {
            return "error";
        }
        return "";
    }

    function constraint($name, $method, $constraint, $customMsg)
    {
        switch ($method) {
            case "post":
                $expression = isset($_POST[$name]) ? $_POST[$name] : '';
                break;
            case "get":
                $expression = isset($_GET[$name]) ? $_GET[$name] : '';
                break;
            default:
                $expression = isset($_POST[$name]) ? $_POST[$name] : '';
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
        if (!$this->enabled) return;
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

    function enabled($enabled)
    {
        $this->enabled = $enabled;
    }
}
