<?php
include_once("../resources/templates/base.php");
$user = new User('', '');
if (!$user->isStaff()) {
    gotoPage("index.php");
}

require("../resources/templates/header.php");
?>


<div class="ui tall stacked segment">
    <h1>Staff View</h1>

    <div class="ui list centered">
        <div class="item"><a href="report-damage.php">Report Damage/Lost Book</a></div>
        <div class="item"><a href="popular-book-report.php">Popular Book Report</a></div>
        <div class="item"><a href="popular-subject-report.php">Popular Subject Report</a></div>
        <div class="item"><a href="frequent-user-report.php">Frequent User Report</a></div>
        <div class="item"><a href="damaged-book-report.php">Damaged Books Report</a></div>
    </div>
</div>