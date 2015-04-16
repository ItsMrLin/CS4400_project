<?php
include_once("../resources/gotoPage.php");
include_once("../resources/User.php");
$user = new User('', '');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if ($user->loggedIn()) {
    gotoPage("search-books.php");
} else {
    gotoPage("login-register.php");
}
exit;