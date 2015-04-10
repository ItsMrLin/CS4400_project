<?php
if (isset($_SESSION['username'])) {
    header("Location:search-books.php");
    exit;
} else if (count($_POST) > 0) {
    if (isset($_POST['login'])) {
        echo "login is not implemented yet.";
    } else if (isset($_POST['register'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($password == $confirmPassword && $password != "") {
            $config = include("../resources/config.php");
            $mysqli = new mysqli($config['hostname'], $config['username'], $config['password'], $config['dbname'], $config['port']);
            if ($mysqli->connect_error) {
                die($mysqli->connect_error);
            } else {
                $mysqli->query("INSERT INTO User VALUES ('$username', '$password')");
                $mysqli->close();
                header("Location:search-books.php");
            }
        } else {
            echo "passwords do not match.";
        }
    }
}
?>

<?php require_once("../resources/templates/header.php"); ?>

<div class="ui tall stacked segment">
    <div class="ui two column relaxed fitted stackable grid">
        <form class="column" action="login-register.php" method="post">
            <h1><i class="lock icon"></i>Login</h1>

            <div class="ui form segment">
                <div class="field">
                    <label>Username</label>

                    <div class="ui left icon input">
                        <input type="text" name="username" placeholder="Username">
                        <i class="user icon"></i>
                    </div>
                </div>
                <div class="field">
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
                <div class="field">
                    <label>Username</label>

                    <div class="ui left icon input">
                        <input type="text" name="username" placeholder="Username">
                        <i class="user icon"></i>
                    </div>
                </div>
                <div class="field">
                    <label>Password</label>

                    <div class="ui left icon input">
                        <input type="password" name="password" placeholder="Password">
                        <i class="lock icon"></i>
                    </div>
                </div>
                <div class="field">
                    <label>Confirm Password</label>

                    <div class="ui left icon input">
                        <input type="password" name="confirm_password" placeholder="Confirm Password">
                        <i class="lock icon"></i>
                    </div>
                </div>
                <button class="ui green submit button" type="submit" name="register">Register</button>
            </div>
            <!--<div class="huge green ui labeled icon button">
                <i class="write icon"></i>
                Create an Account
            </div>-->
        </form>
    </div>
</div>

<?php require_once("../resources/templates/footer.php"); ?>
