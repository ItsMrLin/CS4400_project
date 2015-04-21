<?php
include_once("../resources/templates/base.php");

$loginValidator = new Validator();
$loginValidator->constraint("l_username", "post", "required", "Username cannot be left blank.");
$loginValidator->constraint("l_password", "post", "required", "Password cannot be left blank.");
$registerValidator = new Validator();
$registerValidator->constraint("r_username", "post", "required", "Username cannot be left blank.");
$registerValidator->constraint("r_password", "post", "required", "Password cannot be left blank.");
$registerValidator->constraint("r_confirm_password", "post", "required", "You need to confirm your password.");

$loginForm = new Form("loginForm", "login-register.php", "post");
$registerForm = new Form("registerForm", "login-register.php", "post");
$loginForm->setValidator($loginValidator);
$registerForm->setValidator($registerValidator);

$loginForm->onSubmit(function ($form) use ($loginValidator) {
    $user = new User($form['l_username'], $form['l_password']);
    if ($user->login()) {
        if ($user->isStaff() == true) {
            gotoPage("report-damage.php");
        } else {
            gotoPage("search-books.php");
        }
    } else {
        $loginValidator->add(new Error("top", "Your username or password is incorrect."));
    }
});

$registerForm->onSubmit(function ($form, $mysqli) use ($registerValidator) {
    $username = $form['r_username'];
    $password = $form['r_password'];

    if ($password != $form['r_confirm_password']) {
        $registerValidator->add(new Error("r_password", "Passwords do not match."));
        $registerValidator->add(new Error("r_confirm_password", ""));
    }

    if ($registerValidator->valid()) {
        $result = $mysqli->query("SELECT * FROM User WHERE Username='$username'");
        if ($result->num_rows > 0) {
            $registerValidator->add(new Error("r_username", "Username <strong>$username</strong> is already taken."));
        } else {
            $mysqli->query("INSERT INTO User VALUES ('$username', '$password')");
            (new User($username, $password))->login();
            header("Location:profile.php");
        }
    }
});
?>
<?php
require_once("../resources/templates/header.php");
$registerValidator->showAllErrors();
$loginValidator->showAllErrors();
?>
<div class="ui tall stacked segment">
    <div class="ui two column relaxed fitted stackable grid">
        <div class="column">
            <?php
            $loginForm->contents(function () use ($loginForm) {
                $loginForm->html('<h1><i class="lock icon"></i>Login</h1>');
                $loginForm->input("l_username", "Username", "text", "");
                $loginForm->input("l_password", "Password", "password", "");
                $loginForm->submitButton("Login", "primary");
            });
            ?>
        </div>
        <div class="ui vertical divider">
            Or
        </div>
        <div class="column">
            <?php

            $registerForm->contents(function () use ($registerForm) {
                $registerForm->html('<h1><i class="write icon"></i>Register</h1>');
                $registerForm->input("r_username", "Username", "text", "");
                $registerForm->input("r_password", "Password", "password", "");
                $registerForm->input("r_confirm_password", "Confirm password", "password", "");
                $registerForm->submitButton("Register", "green");
            });
            ?>
        </div>
    </div>
</div>
<?php require_once("../resources/templates/footer.php"); ?>
