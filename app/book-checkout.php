<?php
    error_reporting(-1);
    include_once("../resources/templates/base.php");
    $user = new User('', '');
    $username = $user->getUsername();
?>
<?php
    require("../resources/templates/header.php");
?>
<?php
    $mysqli = require("../resources/db_connection.php");
    $targetIsbn = $_GET['isbn'];
    // check if copy is available
    // check if the user has a hold on this book
    $locateCopyQueryWithHold = "SELECT CopyID FROM BookCopy
        WHERE ISBN = '$targetIsbn' AND IsCheckedOut = 0 AND IsDamaged = 0 AND IsOnHold = 1 AND FutureRequester = '$username'
        LIMIT 1;";
    $results = $mysqli->query($locateCopyQueryWithHold);
    $userHasHold = true;
    
    if (!$results || $results->num_rows==0) {
        // if the user has no hold on the book, check if there is a book available
        $locateCopyQueryWithoutHold = "SELECT CopyID FROM BookCopy
            WHERE ISBN = '$targetIsbn' AND IsCheckedOut = 0 AND IsDamaged = 0 AND IsOnHold = 0
            LIMIT 1;";

        $results = $mysqli->query($locateCopyQueryWithoutHold);
        $userHasHold = false;
    }
    

    if ($bookCopyRow = $results->fetch_array(MYSQLI_ASSOC)) {
        $copyId = $bookCopyRow["CopyID"];
        // update bookCopy
        $updateBookCopyQuery = "UPDATE BookCopy
            SET IsOnHold = 0, IsCheckedOut = 1, FutureRequester = NULL
            WHERE ISBN = $targetIsbn AND CopyID = $copyId";
        $createOrUpdateIssue = "";
        if ($userHasHold) {
            // update issue if there's a hold already
            "UPDATE BookCopy
            SET IsOnHold = 1, IsCheckedOut = 0, FutureRequester = '$username'
            WHERE ISBN = $targetIsbn AND CopyID = $copyId";

            // ReturnDate > CURDATE() along with $userHasHold ensure the book is on hold, rather than presviouly checked-out and returned book 
            $createOrUpdateIssue = "UPDATE Issue
                SET IssueDate = CURDATE(), ReturnDate = DATE_ADD(CURDATE(),INTERVAL 14 DAY)
                WHERE ISBN = '$targetIsbn' AND CopyID = $copyId AND ReturnDate > CURDATE()";
        } else {
            $createOrUpdateIssue = "INSERT INTO Issue
                (Username, IssueDate, ReturnDate, ISBN, CopyID)
                VALUES
                ('$username', CURDATE(), DATE_ADD(CURDATE(),INTERVAL 14 DAY), '$targetIsbn', $copyId)";
        }
        $mysqli->query($updateBookCopyQuery);
        $mysqli->query($createOrUpdateIssue);
    ?>
            <div class="ui message">
                <div class="header">
                    The book has been successfully checked out. <a href="search-books.php">Find another book.</a>
                </div>
            </div>
    <?php
    } else {
    ?>
        <div class="ui warning message">
            <div class="header">
                Sorry, this book is currently not available. <a href="search-books.php">Find another book.</a>
            </div>
        </div>
    <?php
    }
?>