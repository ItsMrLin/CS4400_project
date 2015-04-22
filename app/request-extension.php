<?php
include_once("../resources/templates/base.php");

$user = new User('', '');
$username = $user->getUsername();
$validator = new Validator();
$validator->constraint("ISBN", "post", "required", "ISBN cannot be left blank.");

$form = new Form("requestExtension", "request-extension.php", "post");
$form->setValidator($validator);

$resultData = "";

$form->onSubmit(function ($props) use ($form, $validator, &$resultData, $user) {
    if ($validator->valid()) {
        // if he checked out the book from the library:

        $isbn = $props['$ISBN'];
        $username = $user->getUsername();

        $mysqli = require("../resources/db_connection.php");
        $query = "SELECT CopyID FROM BookCopy
                  WHERE ISBN=$isbn AND FutureRequester=$username";

        $results = $mysqli->query($query);

        if ($results->num_rows > 0) {
            echo "can";
        } else {
            $validator->add(new Error('top', "You can't request an extension for this book."));
        }

        /*$isbn = $props['ISBN'];
        $mysqli = require("../resources/db_connection.php");
        $query = "SELECT b.ISBN, bc.CopyID, MIN(i.ReturnDate) AS 'ExpectedAvailableDate'
                  FROM Book AS b
                  INNER JOIN BookCopy AS bc
                  ON b.ISBN=bc.ISBN
                  INNER JOIN Issue AS i
                  ON i.ISBN=b.ISBN
                  WHERE b.ISBN=$isbn
                  GROUP BY 'ExpectedAvailableDate'
                  AND 'ExpectedAvailableDate' IS NOT NULL";

        $results = $mysqli->query($query);
        $resultData = $results->fetch_array(MYSQLI_ASSOC);

        if ($results->num_rows > 0) {

            // so someone else already extended it.
            $isbn = $resultData['ISBN'];
            $CopyID = $resultData['CopyID'];
            $ExpectedAvailableDate = $resultData['ExpectedAvailableDate'];
            $username = $user->getUsername();

            $query = "UPDATE BookCopy bc
                      SET bc.FutureRequester=$username
                      WHERE bc.ISBN=$isbn AND bc.CopyID=$CopyID";
            $result = $mysqli->query($query);
        } else {
            $validator->add(new Error("top", "A request extension for this book cannot be issued!"));
        }

        print_r($resultData);*/
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
    <h1>Request Book Extension</h1>
    <?php
    $form->contents(function () use ($form) {
        $form->input("ISBN", "ISBN", "text", "");
        $form->submitButton("Request extension", "primary");
    });
    ?>
</div>
<?php require_once("../resources/templates/footer.php"); ?>
