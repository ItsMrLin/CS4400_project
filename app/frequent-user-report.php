<?php
include_once("../resources/templates/base.php");
$user = new User('', '');
if (!$user->isStaff()) {
    gotoPage("index.php");
}

$validator = new Validator();
$form = new Form("frequentUserForm", "frequent-user-report.php", "post");
$form->setValidator($validator);
$form->onSubmit(function ($f, $mysqli) {
});

require("../resources/templates/header.php");
?>

<div>
    <h1>
        Frequent User Report (for users with at least 10 checkout count)
    </h1>
</div>   
<?php

if ($form->submitted()) {

    $month = $_POST["Month"];

    $mysqli = require("../resources/db_connection.php");


    $query = "SELECT Issue.Username as usr, COUNT(*) AS NumCheckout
            FROM Issue
            INNER JOIN StudentFaculty
            ON Issue.Username = StudentFaculty.Username AND Month(Issue.IssueDate) = $month AND NumCheckout >= 10
            GROUP BY usr
            ORDER BY NumCheckout desc
            LIMIT 3;";

    $results = $mysqli->query($query);

    if ($results->num_rows > 0) {?>
        <table class="ui table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Checkout Count</th>
                </tr>
            </thead>
            <tbody>

            <?php
            while ($row = $results->fetch_array(MYSQLI_ASSOC)) {
                ?>
                    <tr>
                        <td><?php echo $row['usr']; ?></td>
                        <td><?php echo $row['NumCheckout']; ?></td>
                    </tr>
                <?php

            }
            ?>
            </tbody>
        </table>
        <a href="frequent-user-report.php" class="ui button">Back</a>
    <?php
    } else {?>
        <div class="ui warning message">
            <div class="header">
                We don't have any book checked out for this month. <a href="frequent-user-report.php">Choose another month.</a>
            </div>
        </div>

    <?php   
    }
} else {
    $form->contents(function () use ($form) {
        $form->select("Month", "Month" , 
            array("1" => "Jan", "2"=>"Feb", "3"=>"Mar", "4"=>"Apr", "5"=>"May", "6"=>"Jun", "7"=>"Jul", "8"=>"Aug", "9"=>"Sep", "10"=>"Oct", "11"=>"Nov", "12"=>"Dec"),
         "");
    ?>
        <a href="staff-nav.php" class="ui button">Back</a>
    <?php
        $form->submitButton("View Report", "primary right floated");
    });   
}
?>

