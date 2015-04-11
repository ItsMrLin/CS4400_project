<?php
include_once("../resources/Error.php");
include_once("../resources/Validator.php");
include_once("../resources/User.php");
$validator = new Validator();

if (count($_POST) > 0) {
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $user = new User($username, $password);
        if ($user->login()) {
            header("Location:search-books.php");
        } else {
            $validator->add(new Error("top", "Your username or password is incorrect."));
            $validator->add(new Error("l_username", ""));
            $validator->add(new Error("l_password", ""));
        }
    } else if (isset($_POST['register'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($password != $confirmPassword) $validator->add(new Error("r_password", "Passwords do not match."));
        if (empty($password)) $validator->add(new Error("r_password", "Password cannot be left blank."));
        if (empty($username)) $validator->add(new Error("r_username", "Username cannot be left blank."));

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
        <form class="column" action="login-register.php" method="post">
            <h1><i class="lock icon"></i>Login</h1>

            <div class="ui form segment">
                <div class="field <?php $validator->validate("l_username"); ?>">
                    <label>Username</label>

                    <div class="ui left icon input">
                        <input type="text" name="username" placeholder="Username">
                        <i class="user icon"></i>
                    </div>
                </div>
                <div class="field <?php $validator->validate("l_password"); ?>">
                    <label>Password</label>

                    <div class="ui left icon input">
                        <input type="password" name="password" placeholder="Password">
                        <i class="lock icon"></i>
                    </div>
                </div>
                <button class="ui blue submit button" type="submit" name="login">Login</button>
            </div>
        </form>
        <div class="ui vertical divider">
            Or
        </div>
        <form class="column" action="login-register.php" method="post">
            <h1><i class="write icon"></i>Register</h1>

            <div class="ui form segment">
                <div class="field <?php $validator->validate("r_username"); ?>">
                    <label>Username</label>

                    <div class="ui left icon input">
                        <input type="text" name="username" placeholder="Username">
                        <i class="user icon"></i>
                    </div>
                </div>
                <div class="field <?php $validator->validate("r_password"); ?>">
                    <label>Password</label>

                    <div class="ui left icon input">
                        <input type="password" name="password" placeholder="Password">
                        <i class="lock icon"></i>
                    </div>
                </div>
                <div class="field <?php $validator->validate("r_password"); ?>">
                    <label>Confirm Password</label>

                    <div class="ui left icon input">
                        <input type="password" name="confirm_password" placeholder="Confirm Password">
                        <i class="lock icon"></i>
                    </div>
                </div>
                <button class="ui green submit button" type="submit" name="register">Register</button>
            </div>
        </form>
    </div>
</div>
<?php require_once("../resources/templates/footer.php"); ?>
