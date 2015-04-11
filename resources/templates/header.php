<?php
include_once("../resources/User.php");
$user = new User('', '');
if (count($_GET) > 0) {
    if (isset($_GET['logout'])) {
        $user->logout();
    }
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>CS 4400 - Phase 3 Heavyweight</title>
    <?php
    include("scripts.php");
    include("styles.php");
    ?>
</head>
<body>

<div id="wrapper">
    <div class="ui segment inverted">
        <h3>Library Management System</h3>
        <?php if ($user->loggedIn()) { ?>
            <div class="ui buttons">
                <div class="ui button">Edit Profile</div>
                <div class="or"></div>
                <a href="?logout" class="ui teal button">Sign out</a>
            </div>
        <?php } ?>
    </div>