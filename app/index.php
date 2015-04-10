<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location:search-books.php");
} else {
    header("Location:login-register.php");
}
exit;