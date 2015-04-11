<?php
    if (count($this->errors)) {
?>
<div class="ui error message">
    <div class="header">
        There was a problem processing your request.
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