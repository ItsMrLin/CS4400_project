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

        $isbn = $props['ISBN'];
        $username = $user->getUsername();

        $mysqli = require("../resources/db_connection.php");
        $query = "SELECT * FROM Issue
                  WHERE ISBN=$isbn
                  AND ReturnDate>CURDATE()
                  AND Username='$username'";

        $results = $mysqli->query($query);

        if ($results->num_rows > 0) {
            // Then the guy has this book checked out already.
            $r = $results->fetch_array(MYSQLI_ASSOC);
            print_r($r);
            $extensionDate = $r['ExtensionDate'];

            // set extension limit based on user type:

            if ($user->isStaff()) {
                // then this dude is a staff member
                $extensionLimit = 5;
            } else {
                $extensionLimit = 2; // defaults to non-staff
            }

            if ($r['ExtensionCount'] <= $extensionLimit) {
                if ($extensionDate == "") {
                    echo "HERE";
                    $query = "UPDATE Issue
                              SET ExtensionDate=CURRENT_DATE(), ExtensionCount=ExtensionCount+1, ReturnDate=DATE_ADD(CURDATE(), INTERVAL 14 DAY)
                              WHERE Username='$username'
                              AND ISBN=$isbn
                              AND ReturnDate>CURRENT_DATE()";
                    $mysqli->query($query);
                } else {
                    echo "ELSEWHERE";
                    $query = "UPDATE Issue
                          SET ExtensionCount=ExtensionCount+1, ReturnDate=(DATE_ADD(ExtensionDate,INTERVAL 14 DAY))
                          WHERE Username='$username'
                          AND ISBN=$isbn
                          AND ReturnDate>CURRENT_DATE()";
                    $mysqli->query($query);
                }
            } else {
                $validator->add(new Error('1', "You can't request an extesnion for this book more than $extensionLimit times."));
            }
        } else {
            $validator->add(new Error('1', "You can't request an extension for this book at this time."));
        }
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
