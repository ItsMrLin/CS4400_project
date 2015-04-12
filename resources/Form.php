<?php
include_once("Map.php");
include_once("Validator.php");

class Form
{
    protected $action;
    protected $method;
    protected $validator;
    protected $submitMethod;
    protected $retrieveMethod;
    protected $name;

    function Form($name, $action, $method)
    {
        $this->name = $name;
        $this->action = $action;
        $this->method = $method;
    }

    function setValidator(Validator &$validator)
    {
        $this->validator = &$validator;
        if (count($_POST) > 0) {
            if ($this->submitted()) {
                $this->validator->enabled(true);
            }
        }
    }

    function onSubmit($method)
    {
        $this->submitMethod = $method;
        if (count($_POST) > 0) {
            if ($this->submitted() && $this->validator->valid()) {
                $this->submit();
            }
        }
    }

    public function isValid()
    {
        return $this->validator->valid();
    }

    public function submit()
    {
        $this->validator->enabled(true);
        $mysqli = require("db_connection.php");
        call_user_func($this->submitMethod, $_POST, $mysqli);
        $mysqli->close();
    }

    public function contents($method)
    {
        $mysqli = require("db_connection.php");

        if (!$this->submitted()) {
            $_POST = $this->retrieve();
        }

        $this->begin();
        $method($_POST, $mysqli);
        $this->end();
        $mysqli->close();
    }

    /**
     * IMPORTANT: Make sure your onRetrieve anon function returns props!
     */
    public function onRetrieve($method)
    {
        $this->retrieveMethod = $method;
    }

    public function retrieve()
    {
        if (is_callable($this->retrieveMethod)) {
            $mysqli = require("db_connection.php");
            $props = call_user_func($this->retrieveMethod, $_POST, $mysqli);
            $mysqli->close();
            return $props;
        }
    }

    public function submitted()
    {
        return (isset($_POST[$this->name])) ? true : false;
    }

    private function begin()
    {
        echo "<form class=\"ui form\" action=\"$this->action\" method=\"$this->method\">";
    }

    private function end()
    {
        echo '</form>';
    }

    public function input($name, $label, $type, $classes)
    {
        $value = "";
        if ($type != "password") {
            if (!empty($_POST[$name])) {
                $value = $_POST[$name];
            }
        }

        $error = $this->validator->validate($name);

        echo "<div class=\"field $error $classes\">";
        echo "<label>$label</label>";
        echo "<input type=\"$type\" name=\"$name\" value=\"$value\">";
        echo "</div>";
    }

    public function select($name, $label, $options, $classes)
    {
        $error = $this->validator->validate($name);
        echo "<div class=\"field $error $classes\">";
        echo "<label>$label</label>";
        echo "<select class=\"ui dropdown\" name=\"$name\">";
        foreach ($options as $value => $label) {
            $selected = ($_POST[$name] == $value) ? 'selected="true"' : "";
            echo "<option value=\"$value\" $selected>$label</option>";
        }
        echo "</select>";
        echo "</div>";
    }

    public function button($name, $label, $type, $classes)
    {
        echo "<button type=\"$type\" name=\"$name\" class=\"ui button $classes\">$label</button>";
    }

    public function submitButton($label, $classes)
    {
        $this->button($this->name, $label, "submit", $classes);
    }

    public function link($label, $href, $classes)
    {
        echo "<a href=\"$href\" class=\"$classes\">$label</a>";
    }

    public function checkbox($name, $label, $classes)
    {
        $error = $this->validator->validate($name);
        $checked = !empty($_POST[$name]) ? 'checked="true"' : "";
        echo "<div class=\"inline field $error $classes\">";
            echo "<div class=\"ui checkbox\">";
                echo "<input type=\"checkbox\" name=\"$name\" value=\"true\" $checked>";
                echo "<label>$label</label>";
            echo "</div>";
        echo "</div>";
    }

    public function html($html)
    {
        echo $html;
    }
}