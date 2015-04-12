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
?>
<?php
require("../resources/templates/header.php");
$validator->showAllErrors();
?>
<div class="ui tall stacked segment">
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

    <!--<form class="ui form" action="search-books.php" method="post">
        <div class="field">
            <label>ISBN</label>
            <input type="text" name="isbn"/>
        </div>
        <div class="field">
            <label>Title</label>
            <input type="text" name="title"/>
        </div>
        <div class="field">
            <label>Author</label>
            <input type="text" name="author"/>
        </div>
    </form>
    <a href="login-register.php" class="ui left labeled icon button">
        <i class="left arrow icon"></i>
        Back
    </a>
    <button class="ui primary button right floated">
        <i class="search icon"></i>
        Search
    </button>-->
</div>
<?php require_once("../resources/templates/footer.php"); ?>
