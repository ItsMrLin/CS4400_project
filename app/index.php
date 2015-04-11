<?php
include_once("../resources/User.php");
$user = new User('', '');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if ($user->loggedIn()) {
    header("Location:search-books.php");
} else {
    header("Location:login-register.php");
}
exit;