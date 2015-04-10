<?php require_once("../resources/templates/header.php"); ?>

<div class="ui tall stacked segment">
    <h1><i class="lock icon"></i>Login</h1>

    <div class="ui two column middle aligned relaxed fitted stackable grid">
        <div class="column">
            <div class="ui form segment">
                <div class="field">
                    <label>Username</label>
                    <div class="ui left icon input">
                        <input type="text" placeholder="Username">
                        <i class="user icon"></i>
                    </div>
                </div>
                <div class="field">
                    <label>Password</label>
                    <div class="ui left icon input">
                        <input type="password" placeholder="Password">
                        <i class="lock icon"></i>
                    </div>
                </div>
                <div class="ui blue submit button">Login</div>
            </div>
        </div>
        <div class="ui vertical divider">
            Or
        </div>
        <div class="center aligned column">
            <div class="huge green ui labeled icon button">
                <i class="write icon"></i>
                Create an Account
            </div>
        </div>
    </div>
</div>

<?php require_once("../resources/templates/footer.php"); ?>
