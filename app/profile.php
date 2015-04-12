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
if (isset($_POST['is_faculty'])) {
    $validator->constraint("associated_department", "post", "required", "As faculty, you need to pick an Associated Department");
} else {
    unset($_POST['associated_department']);
}

$form = new Form("profile.php", "post");
$form->setValidator($validator);
$form->onSubmit(function ($f, $mysqli) use ($validator) {
    if ($validator->valid()) {
        $username = (new User('',' '))->getUsername();
        $name = $f['first_name'] . ' ' . $f['last_name'];
        $dob = $f['dob'];
        $gender = $f['gender'];
        $email = $f['email'];
        $address = $f['address'];
        $isFaculty = isset($f['is_faculty']) ? 1 : 0;
        $dept = isset($f['associated_department']) ? $f['associated_department'] : 0;

        $result = $mysqli->query("SELECT * FROM StudentFaculty WHERE Username='$username'");
        if ($result->num_rows > 0) {
             $q = "UPDATE StudentFaculty SET Name='$name', DOB='$dob', Gender='$gender', Email='$email', Address='$address', IsFaculty='$isFaculty', Dept='$dept' WHERE Username='$username'";
            $mysqli->query($q);
        } else {
            $q = "INSERT INTO StudentFaculty VALUES ('$username', '$name', '$dob', '$gender', 0, '$email', '$address', '$isFaculty', 0, 0.0)";
            $mysqli->query($q);
        }
    }
});

?>
<?php require_once("../resources/templates/header.php"); ?>
<?php $validator->showAllErrors(); ?>
    <div class="ui tall stacked segment">
        <h1>Your Profile</h1>
        <?php
        $form->contents(function ($props) use ($form, $validator) {
            $form->input("first_name", "First Name", "text", "required");
            $form->input("last_name", "Last Name", "text", "required");
            $form->input("dob", "DOB", "text", "required");

            // @todo: populate gender from database instead of hardcoded:
            $form->select("gender", "Gender", array(
                "" => "Gender",
                "1" => "Male",
                "0" => "Female"
            ), "required");
            $form->input("email", "Email", "email", "required");
            $form->input("address", "Address", "text", "required");
            $form->checkbox("is_faculty", "Faculty member", "");

            // @todo: populate associated department with live data later:
            if (isset($props['is_faculty'])) {
                $form->select("associated_department", "Associated Department", array(
                    "" => "Associated Department",
                    "test" => "test",
                    "test2" => "test2"
                ), "required");
            }

            $form->link("Back", "search-books.php", "ui button");
            $form->submitButton("Save", "primary");
        });
        ?>
    </div>
<?php require_once("../resources/templates/footer.php"); ?>