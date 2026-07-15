
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

    $full_name      = trim($_POST['full_name'] ?? '');
$email          = trim($_POST['email'] ?? '');
$degree         = $_POST['degree'] ?? '';
$semester       = $_POST['semester'] ?? '';
$academic_year  = trim($_POST['academic_year'] ?? '');
$contact_no     = trim($_POST['contact_no'] ?? '');
$address        = trim($_POST['address'] ?? '');
$bio            = trim($_POST['bio'] ?? '');

$mentor_id      = (int)($_POST['mentor_id'] ?? 0);

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


    /* ============================
       SAVE MENTOR ASSIGNMENT
    ============================ */

    if($mentor_id > 0){

        $checkAssignment = $conn->prepare("
            SELECT id
            FROM mentor_assignments
            WHERE student_id = ?
        ");

        $checkAssignment->bind_param(
            "i",
            $student_id
        );

        $checkAssignment->execute();

        $assignmentResult =
            $checkAssignment->get_result();


        /* Student already has a mentor */

        if($assignmentResult->num_rows > 0){

            $updateAssignment = $conn->prepare("
                UPDATE mentor_assignments
                SET mentor_id = ?
                WHERE student_id = ?
            ");

            $updateAssignment->bind_param(
                "ii",
                $mentor_id,
                $student_id
            );

            $updateAssignment->execute();

        }


        /* Student does not have a mentor */

        else{

            $insertAssignment = $conn->prepare("
                INSERT INTO mentor_assignments
                (
                    student_id,
                    mentor_id
                )
                VALUES (?, ?)
            ");

            $insertAssignment->bind_param(
                "ii",
                $student_id,
                $mentor_id
            );

            $insertAssignment->execute();

        }

    }


    header("Location: student_profile.php?success=1");
    exit();

}


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
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link
href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
rel="stylesheet">

<link rel="stylesheet" href="student_profile.css">
<link rel="stylesheet" href="Includes/footer.css">
<link rel="stylesheet" href="student_dashbord.css">
<link rel="stylesheet" href="Includes/student_header.css">

</head>

<body>
    <?php include "Includes/student_header.php";?>

<main class="student-profile-page">

    <div class="profile-page-container">


        <!-- =========================
             PAGE HEADER
        ========================== -->

        <section class="profile-page-header">

            <div>

                <span class="page-eyebrow">
                    Student Account
                </span>

                <h1>
                    My Student Profile
                </h1>

                <p>
                    Manage your personal information,
                    academic details and monitor your
                    semester GPA.
                </p>

            </div>


            <div class="header-decoration">

                <i class='bx bx-user-circle'></i>

            </div>

        </section>


        <!-- SUCCESS MESSAGE -->

        <?php if(isset($_GET['success'])){ ?>

            <div
                class="alert alert-success
                alert-dismissible fade show
                profile-success-alert"
            >

                <i class='bx bx-check-circle'></i>

                Profile updated successfully.

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        <?php } ?>


        <!-- =========================
             PROFILE GRID
        ========================== -->

        <div class="profile-content-grid">


            <!-- =====================
                 STUDENT PROFILE
            ====================== -->

            <section class="student-profile-card">


                <div class="card-section-heading">

                    <div class="heading-icon">

                        <i class='bx bx-user'></i>

                    </div>

                    <div>

                        <h2>
                            Personal & Academic Information
                        </h2>

                        <p>
                            Keep your student details up to date
                        </p>

                    </div>

                </div>


                <form
                    method="POST"
                    enctype="multipart/form-data"
                    id="profileForm"
                >


                    <!-- PROFILE PHOTO -->

                    <div class="student-photo-section">


                        <div class="student-photo-wrapper">

                            <?php if(
                                !empty($data['profile_picture'])
                            ){ ?>

                                <img
                                    src="<?php
                                    echo htmlspecialchars(
                                        $data['profile_picture']
                                    );
                                    ?>"
                                    class="student-profile-image"
                                    id="profilePreview"
                                    alt="Student Profile"
                                >

                            <?php }else{ ?>

                                <div
                                    class="default-student-avatar"
                                    id="defaultAvatar"
                                >

                                    <i class='bx bx-user'></i>

                                </div>


                                <img
                                    src=""
                                    class="student-profile-image"
                                    id="profilePreview"
                                    alt="Profile Preview"
                                    style="display:none;"
                                >

                            <?php } ?>


                            <label
                                for="profile_picture"
                                class="photo-camera-button"
                            >

                                <i class='bx bx-camera'></i>

                            </label>

                        </div>


                        <div class="student-photo-info">

                            <h3>

                                <?php

                                echo htmlspecialchars(

                                    $data['full_name']
                                    ??
                                    $_SESSION['name']

                                );

                                ?>

                            </h3>


                            <span>
                                University Student
                            </span>


                            <label
                                for="profile_picture"
                                class="change-photo-button"
                            >

                                <i class='bx bx-upload'></i>

                                Change Profile Photo

                            </label>


                            <input
                                type="file"
                                id="profile_picture"
                                name="profile_picture"
                                accept="image/jpeg,image/png,image/webp"
                                hidden
                            >

                        </div>

                    </div>


                    <div class="profile-divider"></div>


                    <!-- FORM GRID -->

                    <div class="student-form-grid">


                        <!-- FULL NAME -->

                        <div class="student-form-group">

                            <label>

                                <i class='bx bx-user'></i>

                                Full Name

                            </label>


                            <input
                                type="text"
                                name="full_name"
                                placeholder="Enter your full name"
                                value="<?php
                                echo htmlspecialchars(
                                    $data['full_name'] ?? ''
                                );
                                ?>"
                                required
                            >

                        </div>


                        <!-- EMAIL -->

                        <div class="student-form-group">

                            <label>

                                <i class='bx bx-envelope'></i>

                                Email Address

                            </label>


                            <input
                                type="email"
                                name="email"
                                placeholder="student@example.com"
                                value="<?php
                                echo htmlspecialchars(
                                    $data['email'] ?? ''
                                );
                                ?>"
                                required
                            >

                        </div>


                        <!-- DEGREE -->

                        <div class="student-form-group">

                            <label>

                                <i class='bx bx-book-open'></i>

                                Degree Programme

                            </label>


                            <select
                                name="degree"
                                required
                            >

                                <option value="">
                                    Select Degree
                                </option>


                                <option
                                    value="Bachelor of Information Technology"
                                    <?= ($data['degree'] ?? '') ==
                                    "Bachelor of Information Technology"
                                    ? "selected" : "" ?>
                                >

                                    Bachelor of Information Technology

                                </option>


                                <option
                                    value="Bachelor of Business Management"
                                    <?= ($data['degree'] ?? '') ==
                                    "Bachelor of Business Management"
                                    ? "selected" : "" ?>
                                >

                                    Bachelor of Business Management

                                </option>


                                <option
                                    value="Bachelor of Software Engineering"
                                    <?= ($data['degree'] ?? '') ==
                                    "Bachelor of Software Engineering"
                                    ? "selected" : "" ?>
                                >

                                    Bachelor of Software Engineering

                                </option>

                            </select>

                        </div>


                        <!-- SEMESTER -->

                        <div class="student-form-group">

                            <label>

                                <i class='bx bx-calendar'></i>

                                Current Semester

                            </label>


                            <select
                                name="semester"
                                required
                            >

                                <option value="">
                                    Select Semester
                                </option>


                                <?php

                                for($i = 1; $i <= 8; $i++){

                                ?>

                                    <option
                                        value="Semester <?php echo $i; ?>"
                                        <?= (($data['semester'] ?? '')
                                        == "Semester $i")
                                        ? "selected"
                                        : "" ?>
                                    >

                                        Semester <?php echo $i; ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>


                        <!-- ACADEMIC YEAR -->

                        <div class="student-form-group">

                            <label>

                                <i class='bx bx-calendar-check'></i>

                                Academic Year

                            </label>


                            <input
                                type="text"
                                name="academic_year"
                                placeholder="Example: 2025 / 2026"
                                value="<?php
                                echo htmlspecialchars(
                                    $data['academic_year'] ?? ''
                                );
                                ?>"
                            >

                        </div>


                        <!-- CONTACT -->

                        <div class="student-form-group">

                            <label>

                                <i class='bx bx-phone'></i>

                                Contact Number

                            </label>


                            <input
                                type="text"
                                name="contact_no"
                                placeholder="+94 77 123 4567"
                                value="<?php
                                echo htmlspecialchars(
                                    $data['contact_no'] ?? ''
                                );
                                ?>"
                            >

                        </div>


                        <!-- ADDRESS -->

                        <div class="student-form-group full-width">

                            <label>

                                <i class='bx bx-map'></i>

                                Address

                            </label>


                            <textarea
                                name="address"
                                rows="3"
                                placeholder="Enter your residential address"
                            ><?php
                            echo htmlspecialchars(
                                $data['address'] ?? ''
                            );
                            ?></textarea>

                        </div>


                        <!-- BIO -->

                        <div class="student-form-group full-width">

                            <label>

                                <i class='bx bx-message-square-detail'></i>

                                About Me

                            </label>


                            <textarea
                                name="bio"
                                rows="4"
                                placeholder="Tell your mentor a little about yourself and your academic goals..."
                            ><?php
                            echo htmlspecialchars(
                                $data['bio'] ?? ''
                            );
                            ?></textarea>

                        </div>


                        <!-- MENTOR -->

                        <div class="student-form-group full-width">

                            <label>

                                <i class='bx bx-user-check'></i>

                                Select Your Mentor

                            </label>


                            <select
                                name="mentor_id"
                                required
                            >

                                <option value="">
                                    Choose a mentor
                                </option>


                                <?php

                                while(
                                    $mentor =
                                    $mentors->fetch_assoc()
                                ){

                                ?>

                                    <option
                                        value="<?php
                                        echo $mentor['usersId'];
                                        ?>"
                                    >

                                        <?php

                                        echo htmlspecialchars(
                                            $mentor['usersName']
                                        );

                                        ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>


                    </div>


                    <input
                        type="hidden"
                        name="gpa"
                        id="hidden-gpa"
                    >


                    <button
                        type="submit"
                        name="save_profile"
                        class="student-save-button"
                    >

                        <i class='bx bx-save'></i>

                        Save Profile Changes

                    </button>


                </form>


            </section>



            <!-- =====================
                 GPA CALCULATOR
            ====================== -->

            <section class="student-gpa-card">


                <div class="card-section-heading">

                    <div class="heading-icon">

                        <i class='bx bx-line-chart'></i>

                    </div>

                    <div>

                        <h2>
                            Academic Performance
                        </h2>

                        <p>
                            Calculate your semester GPA
                        </p>

                    </div>

                </div>


                <!-- SEMESTER -->

                <div class="gpa-semester-area">

                    <label>

                        <i class='bx bx-calendar'></i>

                        GPA Semester

                    </label>


                    <select
                        id="gpaSemester"
                    >

                        <option value="">
                            Select Semester
                        </option>


                        <?php

                        for($i = 1; $i <= 8; $i++){

                        ?>

                            <option
                                value="Semester <?php echo $i; ?>"
                            >

                                Semester <?php echo $i; ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>


                <!-- SUBJECT HEADER -->

                <div class="subject-list-heading">

                    <div>

                        <h3>
                            Subjects
                        </h3>

                        <p>
                            Add grade and credit values
                        </p>

                    </div>

                    <span>
                        GPA Scale 4.0
                    </span>

                </div>


                <!-- COLUMN LABELS -->

                <div class="subject-column-labels">

                    <span>Grade</span>

                    <span>Credits</span>

                </div>


                <!-- SUBJECTS -->

                <div id="subjects-container">

                    <div class="subject-row subject">

                        <select class="grade">

                            <option value="4.0">A+ — 4.0</option>
                            <option value="3.7">A- — 3.7</option>
                            <option value="3.3">B+ — 3.3</option>
                            <option value="3.0">B — 3.0</option>
                            <option value="2.7">B- — 2.7</option>
                            <option value="2.3">C+ — 2.3</option>
                            <option value="2.0">C — 2.0</option>
                            <option value="1.0">D — 1.0</option>
                            <option value="0">F — 0.0</option>

                        </select>


                        <input
                            type="number"
                            class="credit"
                            placeholder="Credits"
                            min="1"
                        >

                    </div>

                </div>


                <!-- ADD SUBJECT -->

                <button
                    type="button"
                    class="add-subject-button"
                    onclick="addSubject()"
                >

                    <i class='bx bx-plus'></i>

                    Add Another Subject

                </button>


                <!-- GPA RESULT -->

                <div class="professional-gpa-display">


                    <div class="gpa-result-icon">

                        <i class='bx bx-trophy'></i>

                    </div>


                    <p class="gpa-label">
                        CURRENT SEMESTER GPA
                    </p>


                    <h1 id="gpa-result">

                        0.00

                        <span>
                            / 4.00
                        </span>

                    </h1>


                    <div class="gpa-progress-track">

                        <div
                            class="gpa-progress-bar"
                            id="gpaProgressBar"
                        ></div>

                    </div>


                    <p
                        id="performance-text"
                        class="performance-message"
                    >

                        Add your subjects to calculate GPA

                    </p>


                </div>


                <!-- GPA INFORMATION -->

                <div class="gpa-info-box">

                    <i class='bx bx-info-circle'></i>

                    <p>
                        Your GPA is automatically calculated
                        based on grade points and subject credits.
                    </p>

                </div>


            </section>


        </div>


    </div>

</main>


<?php include "Includes/footer.php"; ?>


<!-- Bootstrap JavaScript -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<script>

/* =========================================
   ADD SUBJECT
========================================= */

function addSubject(){

    const container =
        document.getElementById("subjects-container");


    const row =
        document.createElement("div");


    row.className =
        "subject-row subject";


    row.innerHTML = `

        <select class="grade">

            <option value="4.0">A+ — 4.0</option>

            <option value="3.7">A- — 3.7</option>

            <option value="3.3">B+ — 3.3</option>

            <option value="3.0">B — 3.0</option>

            <option value="2.7">B- — 2.7</option>

            <option value="2.3">C+ — 2.3</option>

            <option value="2.0">C — 2.0</option>

            <option value="1.0">D — 1.0</option>

            <option value="0">F — 0.0</option>

        </select>


        <input
            type="number"
            class="credit"
            placeholder="Credits"
            min="1"
        >

    `;


    container.appendChild(row);

}



/* =========================================
   CALCULATE GPA
========================================= */

function calculateGPA(){

    const grades =
        document.querySelectorAll(".grade");


    const credits =
        document.querySelectorAll(".credit");


    let totalCredits = 0;

    let totalPoints = 0;


    grades.forEach((grade, index) => {

        const gradeValue =
            parseFloat(grade.value);


        const creditValue =
            parseFloat(credits[index].value);


        if(
            !isNaN(creditValue)
            &&
            creditValue > 0
        ){

            totalCredits += creditValue;


            totalPoints +=
                gradeValue * creditValue;

        }

    });


    let gpa = 0;


    if(totalCredits > 0){

        gpa =
            totalPoints / totalCredits;

    }


    /* DISPLAY GPA */

    const gpaResult =
        document.getElementById("gpa-result");


    gpaResult.innerHTML =

        gpa.toFixed(2)

        +

        ` <span>/ 4.00</span>`;



    /* SAVE GPA TO HIDDEN INPUT */

    const hiddenGpa =
        document.getElementById("hidden-gpa");


    if(hiddenGpa){

        hiddenGpa.value =
            gpa.toFixed(2);

    }



    /* GPA PROGRESS BAR */

    const progressBar =
        document.getElementById("gpaProgressBar");


    if(progressBar){

        const percentage =
            (gpa / 4) * 100;


        progressBar.style.width =
            percentage + "%";

    }



    /* PERFORMANCE MESSAGE */

    const performance =
        document.getElementById("performance-text");


    if(totalCredits === 0){

        performance.innerHTML =
            "Add your subjects to calculate GPA";

    }

    else if(gpa >= 3.50){

        performance.innerHTML =
            "Excellent academic performance";

    }

    else if(gpa >= 3.00){

        performance.innerHTML =
            "Good academic performance";

    }

    else if(gpa >= 2.00){

        performance.innerHTML =
            "You're making progress — keep improving";

    }

    else{

        performance.innerHTML =
            "Additional academic support may be helpful";

    }

}



/* =========================================
   AUTOMATIC GPA CALCULATION
========================================= */

document.addEventListener(
    "input",
    function(event){

        if(

            event.target.classList.contains("grade")

            ||

            event.target.classList.contains("credit")

        ){

            calculateGPA();

        }

    }
);



/* =========================================
   PROFILE IMAGE PREVIEW
========================================= */

const profileInput =
    document.getElementById("profile_picture");


const profilePreview =
    document.getElementById("profilePreview");


const defaultAvatar =
    document.getElementById("defaultAvatar");


if(profileInput){

    profileInput.addEventListener(
        "change",
        function(event){

            const file =
                event.target.files[0];


            if(file){

                profilePreview.src =
                    URL.createObjectURL(file);


                profilePreview.style.display =
                    "block";


                if(defaultAvatar){

                    defaultAvatar.style.display =
                        "none";

                }

            }

        }
    );

}



/* =========================================
   PROFILE MENU
========================================= */

function toggleMenu(){

    const subMenu =
        document.getElementById("subMenu");


    if(subMenu){

        subMenu.classList.toggle(
            "open-menu"
        );

    }

}



/* =========================================
   NOTIFICATION MENU
========================================= */

function toggleNotifications(){

    const notificationDropdown =
        document.getElementById(
            "notificationDropdown"
        );


    if(notificationDropdown){

        notificationDropdown.classList.toggle(
            "open-notifications"
        );

    }

}

</script>


</body>

</html>