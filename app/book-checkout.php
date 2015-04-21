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
        WHERE ISBN = $targetIsbn AND IsCheckedOut = 0 AND IsDamaged = 0 AND IsOnHold = 1 AND FutureRequester = '$username'
        LIMIT 1;";
    // $results = $mysqli->query("SELECT * FROM BookCopy");
    $results = $mysqli->query($locateCopyQueryWithHold);
    $userHasHold = true;
    
    if ($results->num_rows=0) {
        // if the user has no hold on the book, check if there is a book available
        $locateCopyQueryWithoutHold = "SELECT CopyID FROM BookCopy
            WHERE ISBN = $targetIsbn AND IsCheckedOut = 0 AND IsDamaged = 0 AND IsOnHold = 0
            LIMIT 1;";

        $results = $mysqli->query($locateCopyQueryWithHold);
        $userHasHold = false;
    }
    
    if ($bookCopyRow = $results->fetch_array(MYSQLI_ASSOC)) {
        $copyId = $bookCopyRow["CopyID"];
        // update bookCopy
        $updateBookCopyQuery = "UPDATE BookCopy
            SET IsOnHold = 0, IsCheckedOut = 1, FutureRequester = NULL
            WHERE ISBN = $targetIsbn AND CopyID = $copyId";
        $createIssue = "INSERT INTO Issue
            (Username, IssueDate, ISBN, CopyID)
            VALUES
            ('$username', CURDATE(), '$targetIsbn', $copyId)";
        $mysqli->query($updateBookCopyQuery);
        $mysqli->query($createIssue);
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