<?php

session_start();

include "Includes/db.php";

if(!isset($_SESSION['role']) ||
$_SESSION['role'] != 'mentor'){

    header("Location: login.php");
    exit();
}

// fetch mentor data
$mentor_id = $_SESSION['user_id'];

$profile = $conn->prepare(

"SELECT * FROM mentor_profiles
WHERE mentor_id=?"

);

$profile->bind_param(
"i",
$mentor_id
);

$profile->execute();

$profileResult =
$profile->get_result();

$data =
$profileResult->fetch_assoc();

// fetch mentor profile
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $full_name = $_POST['full_name'];

    $email = $_POST['email'];

    $qualification =
    $_POST['qualification'];

    $specialization =
    $_POST['specialization'];

    $experience =
    $_POST['experience'];

    $department =
    $_POST['department'];

    $university =
    $_POST['university'];

    $skills =
    $_POST['skills'];

    $contact_no =
    $_POST['contact_no'];

    $address =
    $_POST['address'];

    $bio =
    $_POST['bio'];

    $profile_picture =
$data['profile_picture'] ?? "";

// image upload

if(!empty($_FILES['profile_picture']['name'])){

    $file_name =
    time() . "_" .
    $_FILES['profile_picture']['name'];

    $target =
    "Uploads/" . $file_name;

    move_uploaded_file(

        $_FILES['profile_picture']['tmp_name'],

        $target
    );

    $profile_picture = $target;
}

    // check profile

    $check = $conn->prepare(

    "SELECT * FROM mentor_profiles
    WHERE mentor_id=?"

    );

    $check->bind_param(
    "i",
    $mentor_id
    );

    $check->execute();

    $result =
    $check->get_result();

    // UPDATE

    if($result->num_rows > 0){

        $update = $conn->prepare(

        "UPDATE mentor_profiles SET

        full_name=?,
        email=?,
        qualification=?,
        specialization=?,
        experience=?,
        department=?,
        university=?,
        skills=?,
        contact_no=?,
        address=?,
        bio=?,
        profile_picture=?

        WHERE mentor_id=?"

        );

        $update->bind_param(

        "ssssssssssssi",

        $full_name,
        $email,
        $qualification,
        $specialization,
        $experience,
        $department,
        $university,
        $skills,
        $contact_no,
        $address,
        $bio,
        $profile_picture,
        $mentor_id

        );

        $update->execute();
        header("Location: mentor_profile.php");
        exit();

    }else{

        // INSERT

        $insert = $conn->prepare(

        "INSERT INTO mentor_profiles

        (
        mentor_id,
        full_name,
        email,
        qualification,
        specialization,
        experience,
        department,
        university,
        skills,
        contact_no,
        address,
        bio,
        profile_picture
        )

        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"

        );

        $insert->bind_param(

        "issssssssssss",

        $mentor_id,
        $full_name,
        $email,
        $qualification,
        $specialization,
        $experience,
        $department,
        $university,
        $skills,
        $contact_no,
        $address,
        $bio,
        $profile_picture

        );

        $insert->execute();
        header("Location: mentor_profile.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="mentor_dashbord.css">
<link rel="stylesheet" href="mentor_profile.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<title>Mentor Profile</title>

</head>

<body>
    <?php include "Includes/mentor_header.php"; ?>

<section class="mentor-profile-section">

<div class="mentor-profile-container">

<h1>Mentor Profile</h1>

<form method="POST" enctype="multipart/form-data">

<div class="profile-grid">

<!-- LEFT CARD -->

<div class="profile-card">

<h2>Personal Information</h2>

<div class="profile-image">

<?php if(!empty($data['profile_picture'])){ ?>

<img
src="<?php echo $data['profile_picture']; ?>">

<?php } ?>

</div>

<input
type="file"
name="profile_picture">

<input
type="text"
name="full_name"
placeholder="Full Name"

value="<?php
echo $data['full_name'] ?? '';
?>"

required>

<input
type="email"
name="email"
placeholder="Email"

value="<?php
echo $data['email'] ?? '';
?>"

required>

<!-- Sri Lankan Phone -->

<div class="phone-group">

<span>+94</span>

<input
type="text"
name="contact_no"
placeholder="75 983 0983"

value="<?php
echo $data['contact_no'] ?? '';
?>"

required>

</div>

<textarea
name="address"
placeholder="Address"><?php
echo $data['address'] ?? '';
?></textarea>

</div>

<!-- RIGHT CARD -->

<div class="profile-card">

<h2>Professional Information</h2>

<select name="department" required>

<option value="">
Select Department
</option>

<option value="Software Engineering"
<?php
if(($data['department'] ?? '') ==
'Software Engineering'){
echo 'selected';
}
?>>
Software Engineering
</option>

<option value="Computer Science"
<?php
if(($data['department'] ?? '') ==
'Computer Science'){
echo 'selected';
}
?>>
Computer Science
</option>

<option value="Information Technology"
<?php
if(($data['department'] ?? '') ==
'Information Technology'){
echo 'selected';
}
?>>
Information Technology
</option>

<option value="Cyber Security"
<?php
if(($data['department'] ?? '') ==
'Cyber Security'){
echo 'selected';
}
?>>
Cyber Security
</option>

<option value="Data Science"
<?php
if(($data['department'] ?? '') ==
'Data Science'){
echo 'selected';
}
?>>
Data Science
</option>

<option value="Business Management"
<?php
if(($data['department'] ?? '') ==
'Business Management'){
echo 'selected';
}
?>>
Business Management
</option>

</select>

<input
type="text"
name="qualification"
placeholder="Qualification"

value="<?php
echo $data['qualification'] ?? '';
?>">

<input
type="text"
name="specialization"
placeholder="Specialization"

value="<?php
echo $data['specialization'] ?? '';
?>">

<input
type="text"
name="experience"
placeholder="Years of Experience"

value="<?php
echo $data['experience'] ?? '';
?>">

<input
type="text"
name="university"
placeholder="University"

value="<?php
echo $data['university'] ?? '';
?>">

<textarea
name="skills"
placeholder="Skills"><?php
echo $data['skills'] ?? '';
?></textarea>

<textarea
name="bio"
placeholder="About Mentor"><?php
echo $data['bio'] ?? '';
?></textarea>

</div>

</div>

<button
type="submit"
class="save-btn">

Save Profile

</button>

</form>

</div>

</section>

<script>

let subMenu =
document.getElementById("subMenu");

function toggleMenu(){

    subMenu.classList.toggle(
    "open-menu"
    );

}

</script>

</body>
</html>