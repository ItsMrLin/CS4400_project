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

$form = new Form("profile.php", "post");
$form->setValidator($validator);
$form->onSubmit(function ($f, $mysqli) use ($validator) {
    if ($validator->valid()) {
        $user = new User('', '');
        $username = $user->getUsername();
        $name = $f['first_name'] . ' ' . $f['last_name'];
        $dob = $f['dob'];
        $gender = $f['gender'];
        $email = $f['email'];
        $address =$f['address'];
        $isFaculty = isset($f['is_faculty']) ? 1 : 0;
        echo $isFaculty;

        $q = "INSERT INTO StudentFaculty VALUES ('$username', '$name', '$dob', '$gender', 0, '$email', '$address', '$isFaculty', 0, 0.0)";
        $mysqli->query($q);
    }
});

if (count($_POST) > 0) {
    if (isset($_POST['submit'])) {
        $form->submit();
    }
}

?>
<?php require_once("../resources/templates/header.php"); ?>
    <?php $validator->showAllErrors(); ?>
    <div class="ui tall stacked segment">
        <h1>Your Profile</h1>
        <?php
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