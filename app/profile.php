<?php
include_once("../resources/Error.php");
include_once("../resources/Validator.php");
include_once("../resources/User.php");
$validator = new Validator();
?>
<?php require_once("../resources/templates/header.php"); ?>
<div class="ui tall stacked segment">
    <h1>Your Profile</h1>

    <form class="ui form" action="search-books.php" method="post">
        <div class="field">
            <label>First Name</label>
            <input type="text" name="first_name"/>
        </div>
        <div class="field">
            <label>Last Name</label>
            <input type="text" name="last_name"/>
        </div>
        <div class="field">
            <label>DOB</label>
            <input type="text" name="dob"/>
        </div>
        <div class="field">
            <label>Gender</label>
            <select class="ui dropdown">
                <option value="">Gender</option>
                <option value="1">Male</option>
                <option value="0">Female</option>
            </select>
        </div>
        <div class="field">
            <label>Email</label>
            <input type="text" name="email"/>
        </div>
        <div class="inline field">
            <div class="ui checkbox">
                <input type="checkbox">
                <label>Faculty member</label>
            </div>
        </div>
        <div class="field">
            <label>Address</label>
            <input type="text" name="address"/>
        </div>
        <div class="field">
            <label>Associated Department</label>
            <select class="ui dropdown">
                <option value="">Associated Department</option>
            </select>
        </div>
    </form>
    <a href="search-books.php" class="ui left labeled icon button">
        <i class="left arrow icon"></i>
        Back
    </a>
    <button class="ui primary button right floated">
        <i class="save icon"></i>
        Save
    </button>
</div>
<?php require_once("../resources/templates/footer.php"); ?>