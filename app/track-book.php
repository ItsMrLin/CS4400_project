<?php
include_once("../resources/templates/base.php");

$user = new User('', '');
$username = $user->getUsername();
$validator = new Validator();
$validator->constraint("ISBN", "post", "required", "ISBN cannot be left blank.");

$form = new Form("trackBook", "track-book.php", "post");
$form->setValidator($validator);

$resultData;
$form->onSubmit(function ($props) use ($form, $validator, &$resultData) {
    if ($validator->valid()) {
        $isbn = $props['ISBN'];
        $mysqli = require("../resources/db_connection.php");
        $query = "SELECT f.FloorID, s.ShelfId, s.AisleID, sub.Name
                  FROM Book AS b
                  INNER JOIN Shelf AS s
                  ON b.ShelfID=s.ShelfID
                  INNER JOIN Floor as f
                  ON s.FloorID=f.FloorID
                  INNER JOIN Subject AS sub
                  ON sub.FloorID=f.FloorID
                  WHERE b.ISBN=$isbn";
        $results = $mysqli->query($query);
        $resultData = $results->fetch_array(MYSQLI_ASSOC);
    }
});

$form->onRetrieve(function () {

});
?>
<?php
require("../resources/templates/header.php");
$validator->showAllErrors();
?>
<div class="ui tall stacked segment">
    <h1>Track Book Location</h1>
    <?php
    $form->contents(function () use ($form) {
        $form->input("ISBN", "ISBN", "text", "");
        $form->submitButton("Locate", "primary");
    });
    if ($form->submitted() && $validator->valid()) {
        $form->divider();?>
        <h4>Floor Number</h4>
        <p><?php echo $resultData['FloorID'] ?></p>
        <h4>Shelf Number</h4>
        <p><?php echo $resultData['ShelfId'] ?></p>
        <h4>Aisle Number</h4>
        <p><?php echo $resultData['AisleID'] ?></p>
        <h4>Subject</h4>
        <p><?php echo $resultData['Name'] ?></p>
    <?php
    } else if ($form->submitted()) {
        ?>
        <h4>Nothing found.</h4>
    <?php
    }
    ?>
</div>
<?php require_once("../resources/templates/footer.php"); ?>
