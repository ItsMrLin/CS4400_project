<?php
include_once("../resources/templates/base.php");

$user = new User('', '');
$username = $user->getUsername();
$validator = new Validator();
$form = new Form("reportDamageForm", "report-damage.php", "post");
$form->setValidator($validator);
$form->onSubmit(function ($props) use ($form, $validator) {
    if (empty($props['ISBN']) || empty($props['CopyID']) || empty($props['DamageUsername'])) {
        $validator->add(new Error("top", "You must specify all fields."));
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
        $damageUsername = $_POST['DamageUsername'];

        // book return is handled at return book page
        $mysqli = require("../resources/db_connection.php");
        // mark book as damaged
        $updateBookCopyQuery = "UPDATE BookCopy
            SET IsDamaged=1
            WHERE ISBN = '$isbn' AND CopyID = $copyId";

        $results = $mysqli->query($updateBookCopyQuery);
        ?>

        <?php if ($mysqli->affected_rows > 0) {
            // charge user
            $updateUserPaneltyQuery = "UPDATE StudentFaculty
                SET Penalty = (Penalty + (SELECT Cost FROM Book WHERE ISBN = '$isbn'))
                WHERE Username = '$damageUsername'";

            $results = $mysqli->query($updateUserPaneltyQuery);

            if ($mysqli->affected_rows > 0) {
            ?>
                <div class="ui message">
                    <div class="header">
                        You have successfully filed a damage/lost report. <a href="report-damage.php">File another damage report.</a>
                    </div>
                </div>
            <?php
            } else {
            ?>
                <div class="ui warning message">
                    <div class="header">
                        Failed to update user penalty. Please contact the DB admin. <a href="report-damage.php">Try again.</a>
                    </div>
                </div>                
            <?php
            }
        } else { ?>

        <div class="ui warning message">
            <div class="header">
                Please double check your input. <a href="report-damage.php">Try again.</a>
            </div>
        </div>

    <?php } } else { ?>
        <div>
            <h1 style="display:inline-block;">Report Damage/Lost Books</h1>
        </div>   
        <?php
        $form->contents(function () use ($form) {
            $form->input("ISBN", "ISBN", "text", "");
            $form->input("CopyID", "Copy ID", "text", "");
            $form->input("DamageUsername", "Username", "text", "");
            $form->link("Back", "staff-nav.php", "ui left icon button");
            $form->submitButton("Report Damage", "primary right floated");
        });
        ?>
    <?php } ?>
</div>
<?php require_once("../resources/templates/footer.php"); ?>
