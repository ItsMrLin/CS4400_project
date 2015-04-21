<?php
include_once("../resources/templates/base.php");

$user = new User('', '');
$username = $user->getUsername();
$validator = new Validator();
$form = new Form("returnForm", "return-books.php", "post");
$form->setValidator($validator);
$form->onSubmit(function ($props) use ($form, $validator) {
    if (empty($props['ISBN']) || empty($props['CopyID'])) {
        $validator->add(new Error("top", "You must specify both ISBN and Copy ID."));
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
    <?php if ($form->submitted() && $form->isValid()) { ?>
        <h1>Search Results</h1>
        <?php
        $isbn = $_POST['ISBN'];
        $copyId = $_POST['CopyID'];

        $mysqli = require("../resources/db_connection.php");
        $updateBookCopyQuery = "UPDATE BookCopy
            SET IsCheckedOut = 0
            WHERE ISBN = '$isbn' AND CopyID = $copyId AND IsCheckedOut = 1";
        $results = $mysqli->query($updateBookCopyQuery);
        ?>

        <?php if ($mysqli->affected_rows > 0) {
            // return book
            $updateIssueQuery = "UPDATE Issue
                SET ReturnDate = CURDATE()
                WHERE Username = '$username' AND ISBN = $isbn AND CopyID = $copyId AND ReturnDate IS NULL";
            $results = $mysqli->query($updateIssueQuery);

            if ($mysqli->affected_rows > 0) {
            ?>
                <div class="ui message">
                    <div class="header">
                        You have successfully returned the book. <a href="return-books.php">Return another book?</a>
                    </div>
                </div>
            <?php
            } else {
            ?>
                <div class="ui warning message">
                    <div class="header">
                        Failed to update issue table. Please contact the DB admin. <a href="return-books.php">Return another book?</a>
                    </div>
                </div>                
            <?php
            }
        } else { ?>

        <div class="ui warning message">
            <div class="header">
                Sorry, your input does not match any of the checked out books. <a href="return-books.php">Try again?</a>
            </div>
        </div>

    <?php } } else { ?>
        <div>
            <h1 style="display:inline-block;">Return Books</h1>
            <a href="search-books.php" style="display:inline-block;">Search Books</a>
        </div>   
        <?php
        $form->contents(function () use ($form) {
            $form->input("ISBN", "ISBN", "text", "");
            $form->input("CopyID", "Copy ID", "text", "");
            $form->link("Back", "login-register.php", "ui left icon button");
            $form->submitButton("Return", "primary right floated");
        });
        ?>
    <?php } ?>
</div>
<?php require_once("../resources/templates/footer.php"); ?>
