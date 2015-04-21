<?php
include_once("../resources/gotoPage.php");
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
    <div class="ui menu">
        <a href="index.php" class="header item">
            Library Management System
        </a>
        <div class="right menu">
            <?php if ($user->loggedIn()) { ?>
                <div class="ui dropdown item">
                    Welcome, <?php  echo (new User('', ''))->getUsername(); ?>
                    <i class="dropdown icon"></i>
                    <div class="menu">
                        <a class="item" href="?editProfile">
                            <i class="user icon"></i>
                            Edit Profile
                        </a>
                        <a class="item" href="?logout">
                            <i class="user sign out icon"></i>
                            Sign Out
                        </a>
                    </div>
                </div>
            <?php }?>
        </div>
    </div>