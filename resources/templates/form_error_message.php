<?php
    if (count($this->errors)) {
?>
<div class="ui error message">
    <div class="header">
        Please fix these errors and try again.
    </div>
    <ul class="list">
        <?php
        foreach ($this->errors as $error) {
            echo "<li>" . $error->getMessage() . "</li>";
        }
        ?>
    </ul>
</div>
<?php } ?>