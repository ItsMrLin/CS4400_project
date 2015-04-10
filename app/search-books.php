<?php require_once("../resources/templates/header.php"); ?>

<div class="ui tall stacked segment">
    <h1>Search Books</h1>

    <form class="ui form" action="search-books.php" method="post">
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
    <div class="ui left labeled icon button">
        <i class="left arrow icon"></i>
        Back
    </div>
    <div class="ui primary button">
        <i class="search icon"></i>
        Search
    </div>
    <div class="ui button">
        Close
    </div>
</div>

<?php require_once("../resources/templates/footer.php"); ?>
