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
        WHERE ISBN = '$targetIsbn' AND IsCheckedOut = 1 AND FutureRequester IS NULL
        LIMIT 1;";
    $results = $mysqli->query($locateCopyQueryWithHold);
    if (!$results || $results->num_rows==0) {
            ?>
                <div class="ui message">
                    <div class="header">
                        Sorry. There's no book available for future hold. <a href="search-books.php">Find another book.</a>
                    </div>
                </div>
                <a href="search-books.php" class="ui button">Back</a>
            <?php
    } else {
        $bookCopyRow = $results->fetch_array(MYSQLI_ASSOC);
        $copyId = $bookCopyRow["CopyID"];
        $updateBookCopyQuery = "UPDATE BookCopy
            SET FutureRequester = '$username'
            WHERE ISBN = '$targetIsbn' AND CopyID = $copyId AND IsCheckedOut = 1";
        $results = $mysqli->query($updateBookCopyQuery);
        if ($mysqli->affected_rows > 0) {
            ?>
                <div class="ui message">
                    <div class="header">
                        You have successfully put a future hold on this book. An email will be sent to you when the book becomes available.<a href="search-books.php">Find another book.</a>
                    </div>
                </div>
                <a href="search-books.php" class="ui button">Back</a>
            <?php
        } else {
            ?>
                <div class="ui warning message">
                    <div class="header">
                        Failed to put future hold on book. Please contact the DB admin. <a href="search-books.php">Find another book.</a>
                    </div>
                </div>
                <a href="search-books.php" class="ui button">Back</a>
            <?php
        }
        
    }
    
?>