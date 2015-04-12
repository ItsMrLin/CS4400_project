<?php
include_once("../resources/Form.php");
include_once("../resources/Error.php");
include_once("../resources/Validator.php");
include_once("../resources/User.php");
$validator = new Validator();

$validator->constraint("first_name", "post", "required", "First name is required.");
$validator->constraint("last_name", "post", "required", "Last name is required.");
$validator->constraint("dob", "post", "required", "DOB is required.");
$validator->constraint("gender", "post", "required", "Gender is required.");
$validator->constraint("email", "post", "required", "Email is required.");
$validator->constraint("address", "post", "required", "Address is required.");

?>
<?php require_once("../resources/templates/header.php"); ?>
    <?php $validator->showAllErrors(); ?>
    <div class="ui tall stacked segment">
        <h1>Your Profile</h1>
        <?php
        $user = new User('','');
        $query = "SELECT * FROM StudentFaculty WHERE Username='".$user->getUsername()."'";
        $form = new Form("profile.php", "post", $query, $validator);
        $form->begin();
        $form->input("first_name", "First Name", "text");
        $form->input("last_name", "Last Name", "text");
        $form->input("dob", "DOB", "text");
        // @todo: populate gender from database instead of hardcoded:
        $form->select("gender", "Gender", array(
            "" => "Gender",
            "male" => "Male",
            "female" => "Female"
        ));
        $form->input("email", "Email", "email");
        $form->input("address", "Address", "text");
        $form->checkbox("is_faculty", "Faculty member");
        // @todo: populate associated department with live data later:
        $form->select("associated_department", "Associated Department", array(
            "" => "Associated Department",
            "test" => "test",
            "test2" => "test2"
        ));
        $form->link("Back", "search-books.php", "ui button");
        $form->button("submit", "Save", "submit", "");
        $form->end();
        ?>
    </div>
<?php require_once("../resources/templates/footer.php"); ?>