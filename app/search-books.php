<?php
include_once("../resources/Form.php");
include_once("../resources/Error.php");
include_once("../resources/Validator.php");
include_once("../resources/User.php");
$user = new User('', '');
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
                 )";
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
                        <td><!--@todo fixme--></td>
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
        <h1>Search Books</h1>
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
