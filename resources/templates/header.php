<?php
include_once("../resources/User.php");
$user = new User('', '');
if ((new User('', ''))->loggedIn() == false) {
    if (strpos($_SERVER['PHP_SELF'], 'login-register') == false) {
        header("Location:login-register.php");
    }
}
if (count($_GET) > 0) {
    if (isset($_GET['logout'])) {
        $user->logout();
    } else if (isset($_GET['editProfile'])) {
        header("Location:profile.php");
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
   <!-- <div class="ui segment inverted">
        <h3>Library Management System</h3>
        <?php /*if ($user->loggedIn()) { */?>
            <div class="ui buttons">
                <a href="?editProfile" class="ui button">Edit Profile</a>
                <div class="or"></div>
                <a href="?logout" class="ui teal button">Sign out</a>
            </div>
        <?php /*} */?>
    </div>-->
    <div class="ui menu">
        <div class="header item">
            Library Management System
        </div>
        <div class="right menu">
            <?php if ($user->loggedIn()) { ?>
                <div class="header item">
                    Welcome,
                    <?php  echo (new User('', ''))->getUsername(); ?>
                </div>
                <a class="item" href="?editProfile">Edit Profile</a>
                <a class="item" href="?logout">Sign out</a>
            <?php }?>
        </div>
    </div>