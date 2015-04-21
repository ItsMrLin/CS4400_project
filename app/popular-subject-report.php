<?php
include_once("../resources/templates/base.php");
$user = new User('', '');
if (!$user->isStaff()) {
    gotoPage("index.php");
}

$validator = new Validator();
$form = new Form("popularSubjectReportForm", "popular-subject-report.php", "post");
$form->setValidator($validator);
$form->onSubmit(function ($f, $mysqli) {
});

require("../resources/templates/header.php");
?>

<div>
    <h1>
        Popular Book Report
    </h1>
</div>   
<?php

if ($form->submitted()) {

    $month = $_POST["Month"];

    $mysqli = require("../resources/db_connection.php");

    $query = "SELECT SubName, COUNT(*) AS BookCount FROM
            (SELECT Issue.ISBN as ISBN, Issue.CopyID as cid, Book.SubName FROM Issue
            INNER JOIN BookCopy
            ON (Month(Issue.IssueDate) = $month AND Issue.ISBN = BookCopy.ISBN)
            INNER JOIN Book
            on (BookCopy.ISBN = Book.ISBN)
            GROUP BY ISBN, cid) AS T
            GROUP BY SubName
            ORDER BY BookCount desc
            LIMIT 3;";

    $results = $mysqli->query($query);

    if ($results->num_rows > 0) {?>
        <table class="ui table">
            <thead>
                <tr>
                    <th>Subject Name</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>

            <?php
            while ($row = $results->fetch_array(MYSQLI_ASSOC)) {
                // print_r($row);
                ?>
                    <tr>
                        <td><?php echo $row['SubName']; ?></td>
                        <td><?php echo $row['BookCount']; ?></td>
                    </tr>
                <?php

            }
            ?>
            </tbody>
        </table>
        <a href="popular-subject-report.php" class="ui button">Back</a>
    <?php
    } else {?>
        <div class="ui warning message">
            <div class="header">
                We don't have any book checked out for this month. <a href="popular-subject-report.php">Choose another month.</a>
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

