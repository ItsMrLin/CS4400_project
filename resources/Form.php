<?php
include_once("Map.php");
include_once("Validator.php");

class Form
{
    protected $action;
    protected $method;
    protected $query;
    protected $validator;
    protected $map;
    protected $submitMethod;

    function Form($action, $method)
    {
        $this->action = $action;
        $this->method = $method;

        /*if (count($_POST) && $validator->valid()) {

            foreach ($_POST as $name => $value) {
                $mapped = $map->get($name);
                if ($mapped) {

                } else {
                    echo "couldn't map ". $name;
                }
            }

        }*/

        /*if (!empty($query)) {
            $mysqli = require("db_connection.php");
            $this->result = $mysqli->query($query);
        } else {
            $this->result = false;
        }*/

    }

    function setValidator(Validator &$validator)
    {
        $this->validator = &$validator;
    }

    function setMap(Map &$map)
    {
        $this->map = &$map;
    }

    function onSubmit($method)
    {
        $this->submitMethod = $method;
    }

    function submit()
    {
        $mysqli = require("db_connection.php");
        call_user_func($this->submitMethod, $_POST, $mysqli, $this->map);
    }

    function onRead()
    {

    }

    public function begin()
    {
        echo "<form class=\"ui form\" action=\"$this->action\" method=\"$this->method\">";
    }

    public function end()
    {
        echo '</form>';

        if(count($_POST) > 0 && $this->validator->valid()) {
            $this->submit();
        }
    }

    public function save()
    {
        $mysqli = require("db_connection.php");
    }

    public function input($name, $label, $type)
    {
        $value = "";
        if ($type != "password") {
            if (!empty($_POST[$name])) {
                $value = $_POST[$name];
            }
        }

        $error = $this->validator->validate($name);

        echo "<div class=\"field $error\">";
        echo "<label>$label</label>";
        echo "<input type=\"$type\" name=\"$name\" value=\"$value\">";
        echo "</div>";
    }

    public function select($name, $label, $options)
    {
        $error = $this->validator->validate($name);
        echo "<div class=\"field $error\">";
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

    public function link($label, $href, $classes)
    {
        echo "<a href=\"$href\" class=\"$classes\">$label</a>";
    }

    public function checkbox($name, $label)
    {
        $error = $this->validator->validate($name);
        $checked = !empty($_POST[$name]) ? 'checked="true"' : "";
        echo "<div class=\"inline field $error\">";
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