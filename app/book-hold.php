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
        // if the user does not have a hold on this book
        // locate a book copy
        $locateCopyQueryWithoutHold = "SELECT CopyID FROM BookCopy
            WHERE ISBN = '$targetIsbn' AND IsCheckedOut = 0 AND IsDamaged = 0 AND IsOnHold = 0
            LIMIT 1;";
        $results = $mysqli->query($locateCopyQueryWithoutHold);
        if ($results->num_rows > 0) {
            // if no hold available
            $bookCopyRow = $results->fetch_array(MYSQLI_ASSOC);
            $copyId = $bookCopyRow["CopyID"];
            // update book copy
            $updateBookCopyQuery = "UPDATE BookCopy
                SET IsOnHold = 1, IsCheckedOut = 0, FutureRequester = '$username'
                WHERE ISBN = $targetIsbn AND CopyID = $copyId";
            $results = $mysqli->query($updateBookCopyQuery);
            // create an issue
            $createHoldIssue = "INSERT INTO Issue
                (Username, IssueDate, ReturnDate, ISBN, CopyID)
                VALUES
                ('$username', CURDATE(), DATE_ADD(CURDATE(),INTERVAL 17 DAY), '$targetIsbn', $copyId)";
            $results = $mysqli->query($createHoldIssue);
            ?>
                <div class="ui message">
                    <div class="header">
                        The book has been successfully put on hold. Please come and check it out within 3 days.<a href="search-books.php">Find another book.</a>
                    </div>
                </div>
                <a href="search-books.php" class="ui button">Back</a>
            <?php
        } else {
            ?>
                <div class="ui message">
                    <div class="header">
                        Sorry, the book is not available for hold. <a href="search-books.php">Find another book.</a>
                    </div>
                </div>
                <a href="search-books.php" class="ui button">Back</a>
            <?php
        }
    } else {
        // If the user does have a hold on the book, do not create double hold
        ?>
            <div class="ui message">
                <div class="header">
                    You already have this book on hold. <a href="book-checkout.php?isbn=<?php echo $targetIsbn; ?>">Check this book out directly.</a>
                </div>
            </div>
            <a href="search-books.php" class="ui button">Back</a>
        <?php
    }
    
?>