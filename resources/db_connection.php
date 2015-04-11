<?php

$config = require("config.php");

return new mysqli($config['hostname'], $config['username'], $config['password'], $config['dbname'], $config['port']);