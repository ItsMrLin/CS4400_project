<?php
include_once("../resources/templates/base.php");

$validator = new Validator();
$validator->constraint("Name", "post", "required", "First name is required.");
$validator->constraint("DOB", "post", "required", "DOB is required.");
$validator->constraint("Gender", "post", "required", "Gender is required.");
$validator->constraint("Email", "post", "required", "Email is required.");
$validator->constraint("Address", "post", "required", "Address is required.");
if (isset($_POST['IsFaculty'])) {
    $validator->constraint("Dept", "post", "required", "As faculty, you need to pick an Associated Department.");
} else {
    unset($_POST['Dept']);
}

$form = new Form("form", "profile.php", "post");
$form->setValidator($validator);
$form->onSubmit(function ($f, $mysqli) use ($validator) {
    if ($validator->valid()) {
        $username = (new User('',' '))->getUsername();
        $name = $f['Name'];
        $dob = $f['DOB'];
        $gender = $f['Gender'];
        $email = $f['Email'];
        $address = $f['Address'];
        $isFaculty = isset($f['IsFaculty']) ? 1 : 0;
        $dept = isset($f['Dept']) ? $f['Dept'] : 0;

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
$form->onRetrieve(function ($props, $mysqli) {
    $username = (new User('', ''))->getUsername();
    $result = $mysqli->query("SELECT * FROM StudentFaculty WHERE Username='$username'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $props['Name'] = $row['Name'];
        $props['DOB'] = $row['DOB'];
        $props['Gender'] = $row['Gender'];
        $props['Email'] = $row['Email'];
        $props['Address'] = $row['Address'];
        $props['IsFaculty'] = empty($row['IsFaculty']) ? "" : 1;
        if ($props['IsFaculty'] == 1) {
            $props['Dept'] = $row['Dept'];
        } else {
            unset($props['Dept']);
            unset($props['IsFaculty']);
        }
    }
    return $props;
});

?>
<?php require_once("../resources/templates/header.php"); ?>
<?php $validator->showAllErrors(); ?>
    <div class="ui tall stacked segment">
        <h1>Your Profile</h1>
        <?php
        $form->contents(function ($props) use ($form, $validator) {
            $form->input("Name", "Name", "text", "required");
            $form->input("DOB", "DOB", "text", "required");

            // @todo: populate gender from database instead of hardcoded:
            $form->select("Gender", "Gender", array(
                "" => "Gender",
                "1" => "Male",
                "0" => "Female"
            ), "required");
            $form->input("Email", "Email", "email", "required");
            $form->input("Address", "Address", "text", "required");
            $form->checkbox("IsFaculty", "Faculty member", "");

            // @todo: populate associated department with live data later:
            if (isset($props['IsFaculty'])) {
                $form->select("Dept", "Associated Department", array(
                    "" => "Associated Department",
                    "test" => "test",
                    "test2" => "test2"
                ), "required");
            }

            $form->link("Back", "search-books.php", "ui button");
            $form->submitButton("Save", "primary right floated");
        });
        ?>
    </div>
<?php require_once("../resources/templates/footer.php"); ?>
