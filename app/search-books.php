<?php
include_once("../resources/templates/base.php");

$user = new User('', '');
$username = $user->getUsername();
$validator = new Validator();
$form = new Form("searchForm", "search-books.php", "post");
$form->setValidator($validator);
$form->onSubmit(function ($props) use ($form, $validator) {
    if (empty($props['Author']) && empty($props['ISBN']) && empty($props['Title'])) {
        $validator->add(new Error("top", "You must specify at least one search criteria before searching."));
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
        $title = $_POST['Title'];
        $author = $_POST['Author'];

        $mysqli = require("../resources/db_connection.php");
        $query = "SELECT b.Title, b.Edition, b.ISBN FROM Book AS b
                 INNER JOIN Author AS a
                 ON a.ISBN=b.ISBN
                 WHERE (
                  b.ISBN LIKE '%$isbn%' AND
                  b.Title LIKE '%$title%' AND
                  a.Author LIKE '%$author%'
                 ) AND b.IsReserved=0
                    GROUP BY b.ISBN";
        $results = $mysqli->query($query);
        ?>

        <?php if ($results->num_rows > 0) { ?>
            <table class="ui table">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Edition</th>
                    <th>ISBN</th>
                    <th>Copies</th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($row = $results->fetch_array(MYSQLI_ASSOC)) {
                    ?>
                    <tr>
                        <td><?php echo $row['Title']; ?></td>
                        <td><?php echo $row['Edition']; ?></td>
                        <td><?php echo $row['ISBN']; ?></td>
                        <td>
                        <?php
                            $targetIsbn = $row['ISBN'];
                            $copyCountQuery = "SELECT b.ISBN, b.Title, b.Edition, COUNT(bc.CopyID) AS 'Available'
                                FROM Book AS b
                                INNER JOIN BookCopy AS bc
                                ON b.ISBN=bc.ISBN AND bc.IsCheckedOut=0 AND bc.IsDamaged=0 AND (bc.IsOnHold=0 OR (bc.IsOnHold=1 AND bc.FutureRequester='$username'))
                                WHERE b.ISBN='$targetIsbn'
                                GROUP BY 'Available'
                            ";

                            $copyCountResult = $mysqli->query($copyCountQuery);
                            $copyRow = $copyCountResult->fetch_array(MYSQLI_ASSOC);
                            if (empty($copyRow["Available"])) {
                                echo 0;
                            } else {
                                echo $copyRow["Available"];
                            }
                        ?>
                        </td>
                        <td><a href="book-hold.php?isbn=<?php echo $targetIsbn; ?>" class="ui button">Hold</a></td>
                        <td><a href="book-checkout.php?isbn=<?php echo $targetIsbn; ?>" class="ui button">Check Out</a></td>
                    </tr>
                <?php
                }
                ?>
                </tbody>
            </table>
            <a href="search-books.php" class="ui button">Back</a>

        <?php } else { ?>

        <div class="ui warning message">
            <div class="header">
                Sorry, we couldn't find anything. <a href="search-books.php">Try again?</a>
            </div>
        </div>

    <?php } } else { ?>
        <div>
            <h1 style="display:inline-block;">Search Books</h1>
            <a href="return-books.php" style="display:inline-block;">Return Books</a>
        </div>   
        <?php
        $form->contents(function () use ($form) {
            $form->input("ISBN", "ISBN", "text", "");
            $form->input("Title", "Title", "text", "");
            $form->input("Author", "Author", "text", "");
            $form->link("Back", "login-register.php", "ui left icon button");
            $form->submitButton("Search", "primary right floated");
        });
        ?>
    <?php } ?>
</div>
<?php require_once("../resources/templates/footer.php"); ?>
