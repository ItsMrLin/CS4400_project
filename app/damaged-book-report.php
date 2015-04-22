<?php
include_once("../resources/templates/base.php");
$user = new User('', '');
if (!$user->isStaff()) {
    gotoPage("index.php");
}

$validator = new Validator();
$form = new Form("damagedBookReportForm", "damaged-book-report.php", "post");
$form->setValidator($validator);
$form->onSubmit(function ($f, $mysqli) {
});

require("../resources/templates/header.php");

$mysqli = require("../resources/db_connection.php");
?>

<div>
    <h1>
        Damaged Book Report
    </h1>
</div>   
<?php

if ($form->submitted()) {
    $month = $_POST["Month"];
    $subname1 = $_POST["Subject1"];
    $subname2 = $_POST["Subject2"];
    $subname3 = $_POST["Subject3"];

    $query = "SELECT Subname, COUNT(*) AS DamageCount FROM
        (SELECT Issue.ISBN as ISBN, Issue.CopyID as cid, Book.SubName FROM Issue
        INNER JOIN BookCopy
        ON (Month(Issue.IssueDate) = $month AND Issue.ISBN = BookCopy.ISBN AND Issue.CopyID = BookCopy.CopyID AND BookCopy.IsDamaged = TRUE)
        INNER JOIN Book
        on (BookCopy.ISBN = Book.ISBN)
        GROUP BY ISBN, cid) AS T
        WHERE Subname IN ('$subname1', '$subname2', '$subname3')
        GROUP BY Subname;";

    $results = $mysqli->query($query);

    if ($results->num_rows > 0) {?>
        <table class="ui table">
            <thead>
                <tr>
                    <th>Subject Name</th>
                    <th>Damage Count</th>
                </tr>
            </thead>
            <tbody>

            <?php
            while ($row = $results->fetch_array(MYSQLI_ASSOC)) {
                ?>
                    <tr>
                        <td><?php echo $row['Subname']; ?></td>
                        <td><?php echo $row['DamageCount']; ?></td>
                    </tr>
                <?php

            }
            ?>
            </tbody>
        </table>
        <a href="damaged-book-report.php" class="ui button">Back</a>
    <?php
    } else {?>
        <div class="ui warning message">
            <div class="header">
                We don't have any book in those categories damaged in this month. <a href="damaged-book-report.php">Choose another month and other categories.</a>
            </div>
        </div>

    <?php   
    }
} else {
    $form->contents(function () use ($form, $mysqli) {
        $subjectListQuery = "SELECT DISTINCT Name FROM Subject;";
        $results = $mysqli->query($subjectListQuery);
        $subjectList = array();
        $subjectList[""] = "";
        while ($rows = $results->fetch_array(MYSQLI_ASSOC)) {
            $subjectList[$rows["Name"]] = $rows["Name"];
        }

        $myArray = array("1" => "Jan", "2"=>"Feb", "3"=>"Mar", "4"=>"Apr", "5"=>"May", "6"=>"Jun", "7"=>"Jul", "8"=>"Aug", "9"=>"Sep", "10"=>"Oct", "11"=>"Nov", "12"=>"Dec");
        $form->select("Month", "Month" ,$myArray, "");
        $form->select("Subject1", "Subject 1" ,$subjectList, "");
        $form->select("Subject2", "Subject 2" ,$subjectList, "");
        $form->select("Subject3", "Subject 3" ,$subjectList, "");

    ?>
        <a href="staff-nav.php" class="ui button">Back</a>
    <?php
        $form->submitButton("View Report", "primary right floated");
    });   
}
?>

