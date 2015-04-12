<?php
include_once("../resources/Error.php");
include_once("../resources/Form.php");
include_once("../resources/Validator.php");
include_once("../resources/User.php");
include_once("../resources/Map.php");
$validator = new Validator();

$userMap = new Map();
$userMap->link('l_username', 'Username');
$userMap->link('l_password', 'Password');
$userMap->link('r_username', 'Username');
$userMap->link('r_password', 'Password');

$loginForm = new Form("login-register.php", "post");
$loginForm->setValidator($validator);
$loginForm->setMap($userMap);
$loginForm->onSubmit(function ($form, $mysqli) {
    $q  = "SELECT Username, Password FROM User WHERE Username='".$form['l_username']."'";
    $q .= "AND Password='".$form['l_password']."'";

//    $result = $mysqli->query($q);
    

});

$registerForm = new Form("login-register.php", "post");
$registerForm->setValidator($validator);
$registerForm->setMap($userMap);

if (count($_POST) > 0) {
    if (isset($_POST['login'])) {
        $username = $_POST['l_username'];
        $password = $_POST['l_password'];

        $user = new User($username, $password);
        if ($user->login()) {
            header("Location:search-books.php");
        } else {
            $validator->add(new Error("top", "Your username or password is incorrect."));
            $validator->add(new Error("l_username", ""));
            $validator->add(new Error("l_password", ""));
        }
    } else if (isset($_POST['register'])) {
        $username = $_POST['r_username'];
        $password = $_POST['r_password'];
        $confirmPassword = $_POST['r_confirm_password'];

        if ($password != $confirmPassword) $validator->add(new Error("r_password", "Passwords do not match."));
        $validator->constraint("r_password", "post", "required", "Password is required.");
        $validator->constraint("r_confirm_password", "post", "required", "You need to confirm your password.");
        $validator->constraint("r_username", "post", "required", "Username is required.");

        if ($password == $confirmPassword && $password != "") {
            $mysqli = require("../resources/db_connection.php");
            if ($mysqli->connect_error) {
                die($mysqli->connect_error);
            } else {
                $result = $mysqli->query("SELECT * FROM User WHERE Username='$username'");
                if ($result->num_rows > 0) {
                    $validator->add(new Error("r_username", "The username <b>$username</b> has already been taken."));
                } else {
                    $mysqli->query("INSERT INTO User VALUES ('$username', '$password')");
                    $user = new User($username, $password);
                    if ($user->login()) {
                        header("Location:profile.php");
                        exit();
                    } else {
                        $validator->add(new Error("error", "There was a problem during registration."));
                    }
                }
                $mysqli->close();
            }
        }
    }
}
?>
<?php require_once("../resources/templates/header.php"); ?>
<?php $validator->showAllErrors(); ?>
<div class="ui tall stacked segment">
    <div class="ui two column relaxed fitted stackable grid">
        <div class="column">
            <?php
            $loginForm->begin();
            $loginForm->html('<h1><i class="lock icon"></i>Login</h1>');
            $loginForm->input("l_username", "Username", "text");
            $loginForm->input("l_password", "Password", "password");
            $loginForm->button("login", "Login", "submit", "ui blue submit button");
            $loginForm->end();
            ?>
        </div>
        <div class="ui vertical divider">
            Or
        </div>
        <div class="column">
            <?php
            $registerForm->begin();
            $registerForm->html('<h1><i class="write icon"></i>Register</h1>');
            $registerForm->input("r_username", "Username", "text");
            $registerForm->input("r_password", "Password", "password");
            $registerForm->input("r_confirm_password", "Confirm password", "password");
            $registerForm->button("register", "Register", "submit", "ui green submit button");
            $registerForm->end();
            ?>
        </div>
    </div>
</div>
<?php require_once("../resources/templates/footer.php"); ?>
