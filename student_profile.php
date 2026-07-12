
<?php
session_start();

include "Includes/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

/* ============================
   Load Student Profile
============================ */

$profile = $conn->prepare("
SELECT *
FROM student_profiles
WHERE student_id = ?
");

$profile->bind_param("i", $student_id);
$profile->execute();

$result = $profile->get_result();
$data = $result->fetch_assoc();

/* ============================
   Load Mentors
============================ */

$mentors = $conn->query("
SELECT usersId, usersName
FROM users
WHERE role='mentor'
ORDER BY usersName ASC
");

/* ============================
   Save Student Profile
============================ */

if(isset($_POST['save_profile'])){

    $full_name      = trim($_POST['full_name']);
    $email          = trim($_POST['email']);
    $degree         = $_POST['degree'];
    $semester       = $_POST['semester'];
    $academic_year  = trim($_POST['academic_year']);
    $contact_no     = trim($_POST['contact_no']);
    $address        = trim($_POST['address']);
    $bio            = trim($_POST['bio']);

    // Keep current image by default
    $profile_picture = $data['profile_picture'] ?? "";

    /* Upload Image */

    if(!empty($_FILES['profile_picture']['name'])){

        $folder = "uploads/profile_pictures/";

        if(!is_dir($folder)){
            mkdir($folder,0777,true);
        }

        $fileName =
        time()."_".basename($_FILES["profile_picture"]["name"]);

        $target =
        $folder.$fileName;

        if(move_uploaded_file($_FILES["profile_picture"]["tmp_name"],$target)){
            $profile_picture = $target;
        }

    }

    /* Check whether profile already exists */

    $check = $conn->prepare("
    SELECT id
    FROM student_profiles
    WHERE student_id=?
    ");

    $check->bind_param("i",$student_id);
    $check->execute();

    $exists = $check->get_result();

    if($exists->num_rows > 0){

        /* Update */

        $update = $conn->prepare("
        UPDATE student_profiles
        SET

        full_name=?,
        email=?,
        degree=?,
        semester=?,
        academic_year=?,
        contact_no=?,
        address=?,
        bio=?,
        profile_picture=?

        WHERE student_id=?
        ");

        $update->bind_param(
        "sssssssssi",

        $full_name,
        $email,
        $degree,
        $semester,
        $academic_year,
        $contact_no,
        $address,
        $bio,
        $profile_picture,
        $student_id

        );

        $update->execute();

    }

    else{

        /* Insert */

        $insert = $conn->prepare("
        INSERT INTO student_profiles(

        student_id,
        full_name,
        email,
        degree,
        semester,
        academic_year,
        contact_no,
        address,
        bio,
        profile_picture

        )

        VALUES(?,?,?,?,?,?,?,?,?,?)
        ");

        $insert->bind_param(
        "isssssssss",

        $student_id,
        $full_name,
        $email,
        $degree,
        $semester,
        $academic_year,
        $contact_no,
        $address,
        $bio,
        $profile_picture

        );

        $insert->execute();

    }

    header("Location: student_profile.php?success=1");
    exit();

}

include "Includes/student_header.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Student Profile</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
rel="stylesheet">

<link rel="stylesheet" href="student_profile.css">
<link rel="stylesheet" href="student_dashbord.css">
<link rel="stylesheet" href="Includes/student_header.css">

</head>

<body>

<div class="container py-5">
    <?php if(isset($_GET['success'])){ ?>

<div class="alert alert-success alert-dismissible fade show">

Profile updated successfully.

<button
type="button"
class="btn-close"
data-bs-dismiss="alert">
</button>

</div>

<?php } ?>

<div class="row g-4">

<!-- =======================================
        LEFT CARD
======================================= -->

<div class="col-lg-6">

<div class="card profile-card shadow">

<!-- PROFILE FORM STARTS HERE -->

<form method="POST" enctype="multipart/form-data" id="profileForm">

<!-- Profile Picture -->

<div class="text-center mb-4">
<?php if(!empty($data['profile_picture'])){ ?>

<img src="<?php echo $data['profile_picture']; ?>" class="profile-img">

<?php }else{ ?>

<img src="Images/default-profile.png" class="profile-img">

<?php } ?>

<h3 class="mt-3">My Profile </h3>

</div>

<div class="mb-3">
<label class="form-label">
Profile Picture
</label>

<input
type="file"
name="profile_picture"
class="form-control">

</div>


<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

Full Name

</label>

<input
type="text"
class="form-control"
name="full_name"
value="<?php echo $data['full_name'] ?? ''; ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Email

</label>

<input
type="email"
class="form-control"
name="email"
value="<?php echo $data['email'] ?? ''; ?>"
required>

</div>

</div>


<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

Degree

</label>

<select
class="form-select"
name="degree"
required>

<option value="">Select Degree</option>

<option
value="Bachelor of Information Technology"
<?= ($data['degree'] ?? '')=="Bachelor of Information Technology" ? "selected" : "" ?>>

Bachelor of Information Technology

</option>

<option
value="Bachelor of Business Management"
<?= ($data['degree'] ?? '')=="Bachelor of Business Management" ? "selected" : "" ?>>

Bachelor of Business Management

</option>

<option
value="Bachelor of Software Engineering"
<?= ($data['degree'] ?? '')=="Bachelor of Software Engineering" ? "selected" : "" ?>>

Bachelor of Software Engineering

</option>

</select>

</div>


<div class="col-md-6 mb-3">

<label class="form-label">

Semester

</label>

<select
class="form-select"
name="semester"
required>

<option value="">Select Semester</option>

<?php
for($i=1;$i<=8;$i++){
?>

<option
value="Semester <?php echo $i;?>"
<?= (($data['semester'] ?? '')=="Semester $i") ? "selected" : "" ?>>

Semester <?php echo $i;?>

</option>

<?php } ?>

</select>

</div>

</div>


<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

Academic Year

</label>

<input
type="text"
class="form-control"
name="academic_year"
value="<?php echo $data['academic_year'] ?? ''; ?>">

</div>


<div class="col-md-6 mb-3">

<label class="form-label">

Contact Number

</label>

<input
type="text"
class="form-control"
name="contact_no"
value="<?php echo $data['contact_no'] ?? ''; ?>">

</div>

</div>


<div class="mb-3">

<label class="form-label">

Address

</label>

<textarea
class="form-control"
rows="3"
name="address"><?php echo $data['address'] ?? ''; ?></textarea>

</div>


<div class="mb-3">

<label class="form-label">

About Me

</label>

<textarea
class="form-control"
rows="3"
name="bio"><?php echo $data['bio'] ?? ''; ?></textarea>

</div>


<div class="mb-4">

<label class="form-label">

Select Mentor

</label>

<select
class="form-select"
name="mentor_id"
required>

<option value="">

Choose Mentor

</option>

<?php
while($mentor = $mentors->fetch_assoc()){
?>

<option
value="<?php echo $mentor['usersId']; ?>">

<?php echo $mentor['usersName']; ?>

</option>

<?php } ?>

</select>

</div>


<input
type="hidden"
name="gpa"
id="hidden-gpa">

<!-- <button
type="submit"
name="submit_gpa"
class="btn btn-success w-100 mb-2">

<i class='bx bx-send'></i>

Submit GPA

</button> -->

<button
type="submit"
name="save_profile"
class="btn btn-outline-primary w-100">

<i class='bx bx-save'></i>

Save Profile

</button>

</form>

</div>

</div>

<!-- ===========================
        GPA CARD
=========================== -->

<div class="col-lg-6">

    <div class="card gpa-card shadow h-100">

        <div class="card-body">

            <h3 class="text-center mb-4">

                <i class='bx bx-bar-chart-alt-2'></i>

                Semester GPA

            </h3>

            <div class="mb-3">

                <label class="form-label">

                    Semester

                </label>

                <select
                    id="gpaSemester"
                    class="form-select">

                    <option value="">Select Semester</option>

                    <?php for($i=1;$i<=8;$i++){ ?>

                    <option value="Semester <?php echo $i;?>">

                        Semester <?php echo $i;?>

                    </option>

                    <?php } ?>

                </select>

            </div>


            <!-- Subject List -->

            <div id="subjects-container">

                <div class="row subject g-2 mb-3">

                    <div class="col-7">

                        <select class="form-select grade">

                            <option value="4.0">A+</option>
                            <option value="3.7">A-</option>
                            <option value="3.3">B+</option>
                            <option value="3.0">B</option>
                            <option value="2.7">B-</option>
                            <option value="2.3">C+</option>
                            <option value="2.0">C</option>
                            <option value="1.0">D</option>
                            <option value="0">F</option>

                        </select>

                    </div>

                    <div class="col-5">

                        <input
                        type="number"
                        class="form-control credit"
                        placeholder="Credits"
                        min="1">

                    </div>

                </div>

            </div>


            <div class="d-grid mb-4">

                <button
                    type="button"
                    class="btn btn-outline-primary"
                    onclick="addSubject()">

                    <i class='bx bx-plus'></i>

                    Add Subject

                </button>

            </div>


            <!-- GPA Display -->

            <div class="gpa-display">

                <div class="stars">

                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>

                </div>

                <p class="gpa-title">

                    Current GPA

                </p>

                <h1 id="gpa-result">

                    0.00

                    <span>/ 4.00</span>

                </h1>

                <p
                id="performance-text"
                class="mt-3 fw-semibold">

                    Start adding your subjects.

                </p>

            </div>

        </div>

    </div>

</div>

</div>

</div>

<!-- ===========================
        JavaScript
=========================== -->

<script>

function addSubject(){

    let container =
    document.getElementById("subjects-container");

    let row =
    document.createElement("div");

    row.className =
    "row subject g-2 mb-3";

    row.innerHTML = `

        <div class="col-7">

            <select class="form-select grade">

                <option value="4.0">A+</option>
                <option value="3.7">A-</option>
                <option value="3.3">B+</option>
                <option value="3.0">B</option>
                <option value="2.7">B-</option>
                <option value="2.3">C+</option>
                <option value="2.0">C</option>
                <option value="1.0">D</option>
                <option value="0">F</option>

            </select>

        </div>

        <div class="col-5">

            <input
            type="number"
            class="form-control credit"
            placeholder="Credits"
            min="1">

        </div>

    `;

    container.appendChild(row);

}


function calculateGPA(){

    let grades =
    document.querySelectorAll(".grade");

    let credits =
    document.querySelectorAll(".credit");

    let totalCredits = 0;

    let totalPoints = 0;

    grades.forEach((grade,index)=>{

        let g =
        parseFloat(grade.value);

        let c =
        parseFloat(credits[index].value);

        if(!isNaN(c) && c>0){

            totalCredits += c;

            totalPoints += g*c;

        }

    });

    let gpa = 0;

    if(totalCredits>0){

        gpa = totalPoints / totalCredits;

    }

    document.getElementById("gpa-result").innerHTML =
    gpa.toFixed(2) + " <span>/ 4.00</span>";

    document.getElementById("hidden-gpa").value =
    gpa.toFixed(2);

    let performance =
    document.getElementById("performance-text");

    if(gpa >= 3.50){

        performance.innerHTML =
        "🌟 Excellent Performance";

    }

    else if(gpa >= 3.00){

        performance.innerHTML =
        "👏 Good Performance";

    }

    else if(gpa >= 2.00){

        performance.innerHTML =
        "📚 Keep Improving";

    }

    else{

        performance.innerHTML =
        "💪 Needs Improvement";

    }

}


document.addEventListener("input",function(e){

    if(

        e.target.classList.contains("grade")

        ||

        e.target.classList.contains("credit")

    ){

        calculateGPA();

    }

});


let subMenu =
document.getElementById("subMenu");

if(subMenu){

function toggleMenu(){

subMenu.classList.toggle("open-menu");

}

}

</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php include "Includes/footer.php"; ?>




