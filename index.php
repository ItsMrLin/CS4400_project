<?php

$config = include("resources/config.php");
$conn = new mysqli($config['hostname'], $config['username'], $config['password'], $config['dbname'], $config['port']);

if ($conn->connect_error) {
    die($conn->connect_error);
}

require_once('resources/templates/header.php');
require_once('resources/templates/footer.php');