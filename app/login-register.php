<?php
include_once("../resources/Error.php");
include_once("../resources/Form.php");
include_once("../resources/Validator.php");
include_once("../resources/User.php");
include_once("../resources/Map.php");

$loginValidator = new Validator();
$loginValidator->constraint("l_username", "post", "required", "Username cannot be left blank.");
$loginValidator->constraint("l_password", "post", "required", "Password cannot be left blank.");

$loginForm = new Form("login-register.php", "post");
$loginForm->setValidator($loginValidator);

$loginForm->onSubmit(function ($form) use ($loginValidator) {
    $user = new User($form['l_username'], $form['l_password']);
    if ($user->login()) {
        header("Location:search-books.php");
    } else {
        $loginValidator->add(new Error("top", "Your username or password is incorrect."));
    }
});

$registerValidator = new Validator();
$registerValidator->constraint("r_username", "post", "required", "Username cannot be left blank.");
$registerValidator->constraint("r_password", "post", "required", "Password cannot be left blank.");
$registerValidator->constraint("r_confirm_password", "post", "required", "You need to confirm your password.");

$registerForm = new Form("login-register.php", "post");
$registerForm->setValidator($registerValidator);

$registerForm->onSubmit(function ($form, $mysqli) use ($registerValidator) {
    if ($form['r_password'] != $form['r_confirm_password']) {
        $registerValidator->add(new Error("r_password", "Passwords do not match."));
        $registerValidator->add(new Error("r_confirm_password", ""));
    }

    $username = $form['r_username'];
    $password = $form['r_password'];

    $result = $mysqli->query("SELECT * FROM User WHERE Username='$username'");
    if ($result->num_rows > 0) {
        $registerValidator->add(new Error("r_username", "Username <strong>$username</strong> is already taken."));
    } else {
        $mysqli->query("INSERT INTO User VALUES ('$username', '$password')");
        header("Location:profile.php");
    }
});

if (count($_POST) > 0) {
    if (isset($_POST['login'])) {
        $loginForm->submit();
    } else if (isset($_POST['register'])) {
        $registerForm->submit();
    }
}
?>
<?php require_once("../resources/templates/header.php"); ?>
<?php
$registerValidator->showAllErrors();
$loginValidator->showAllErrors();
?>
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
