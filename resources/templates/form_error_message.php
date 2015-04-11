<?php
    if (count($errors)) {
?>
<div class="ui error message">
    <i class="close icon"></i>
    <div class="header">
        There was a problem processing your request.
    </div>
    <ul class="list">
        <?php
        foreach ($errors as $error) {
            echo "<li>" . $error->getMessage() . "</li>";
        }
        ?>
    </ul>
</div>
<?php } ?>