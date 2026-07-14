<?php

session_start();

include "Includes/db.php";

if (
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'mentor'
) {
    header("Location: login.php");
    exit();
}

$mentor_id = $_SESSION['user_id'];

$message = "";
$messageType = "";


/* ===============================
   FETCH MENTOR PROFILE
================================ */

$profile = $conn->prepare(
    "SELECT * FROM mentor_profiles
     WHERE mentor_id = ?"
);

$profile->bind_param(
    "i",
    $mentor_id
);

$profile->execute();

$profileResult = $profile->get_result();

$data = $profileResult->fetch_assoc();


/* ===============================
   SAVE PROFILE
================================ */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $qualification = trim($_POST['qualification'] ?? '');
    $specialization = trim($_POST['specialization'] ?? '');
    $experience = trim($_POST['experience'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $university = trim($_POST['university'] ?? '');
    $skills = trim($_POST['skills'] ?? '');
    $contact_no = trim($_POST['contact_no'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $bio = trim($_POST['bio'] ?? '');

    $profile_picture = $data['profile_picture'] ?? "";


    /* ===============================
       PROFILE IMAGE UPLOAD
    ================================ */

    if (
        isset($_FILES['profile_picture']) &&
        $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK
    ) {

        $allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        $fileType = mime_content_type(
            $_FILES['profile_picture']['tmp_name']
        );

        if (in_array($fileType, $allowedTypes)) {

            if (!is_dir("uploads")) {
            mkdir("uploads", 0777, true);
      }

               $extension = pathinfo(
               $_FILES['profile_picture']['name'],
               PATHINFO_EXTENSION
               );

               $fileName =
                "mentor_" .
                 $mentor_id .
                 "_" .
                  time() .
                  "." .
                 $extension;

                 $target = "uploads/" . $fileName;

            if (
                move_uploaded_file(
                    $_FILES['profile_picture']['tmp_name'],
                    $target
                )
            ) {
                $profile_picture = $target;
            }

        } else {

            $message = "Please upload JPG, PNG or WEBP images only.";
            $messageType = "danger";
        }
    }


    /* ===============================
       CHECK PROFILE
    ================================ */

    if (empty($message)) {

        $check = $conn->prepare(
            "SELECT id
             FROM mentor_profiles
             WHERE mentor_id = ?"
        );

        $check->bind_param(
            "i",
            $mentor_id
        );

        $check->execute();

        $result = $check->get_result();


        /* ===============================
           UPDATE PROFILE
        ================================ */

        if ($result->num_rows > 0) {

            $update = $conn->prepare(

                "UPDATE mentor_profiles SET

                full_name = ?,
                email = ?,
                qualification = ?,
                specialization = ?,
                experience = ?,
                department = ?,
                university = ?,
                skills = ?,
                contact_no = ?,
                address = ?,
                bio = ?,
                profile_picture = ?

                WHERE mentor_id = ?"

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

            if ($update->execute()) {

                $_SESSION['profile_message'] =
                    "Profile updated successfully.";

                header("Location: mentor_profile.php");
                exit();
            }


        /* ===============================
           INSERT PROFILE
        ================================ */

        } else {

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

            if ($insert->execute()) {

                $_SESSION['profile_message'] =
                    "Profile created successfully.";

                header("Location: mentor_profile.php");
                exit();
            }
        }
    }
}


/* ===============================
   SUCCESS MESSAGE
================================ */

if (isset($_SESSION['profile_message'])) {

    $message = $_SESSION['profile_message'];

    $messageType = "success";

    unset($_SESSION['profile_message']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Bootstrap -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Boxicons -->

    <link
        href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
        rel="stylesheet"
    >

    <!-- Mentor Header -->

    <link
        rel="stylesheet"
        href="Includes/mentor_header.css"
    >

    <link rel="stylesheet" href="Includes/footer.css">

    <!-- Mentor Profile -->

    <link
        rel="stylesheet"
        href="mentor_profile.css"
    >

    <title>Mentor Profile</title>

</head>


<body>


<?php include "Includes/mentor_header.php"; ?>


<section class="mentor-profile-section">

<div class="container">


    <!-- PAGE HEADER -->

    <div class="profile-heading text-center">

        <span class="profile-badge">
            Mentor Account
        </span>

        <h1>
            Build Your Mentor Profile
        </h1>

        <p>
            Share your academic background,
            professional experience and mentoring expertise.
        </p>

    </div>


    <!-- MESSAGE -->

    <?php if (!empty($message)) { ?>

        <div
            class="alert alert-<?php echo $messageType; ?>
            alert-dismissible fade show profile-alert"
            role="alert"
        >

            <?php echo htmlspecialchars($message); ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    <?php } ?>


    <form
        method="POST"
        enctype="multipart/form-data"
    >


    <div class="row g-4">


        <!-- =====================
             PERSONAL INFORMATION
        ====================== -->

        <div class="col-lg-5">

            <div class="profile-card h-100">

                <div class="card-title-area">

                    <div class="title-icon">

                        <i class='bx bx-user'></i>

                    </div>

                    <div>

                        <h2>
                            Personal Information
                        </h2>

                        <p>
                            Your basic contact details
                        </p>

                    </div>

                </div>


                <!-- PROFILE IMAGE -->

                <div class="profile-image-area">

                    <div class="profile-image">

                        <?php if (
                            !empty($data['profile_picture'])
                        ) { ?>

                            <img
                                src="<?php
                                echo htmlspecialchars(
                                    $data['profile_picture']
                                );
                                ?>"
                                id="profilePreview"
                                alt="Mentor Profile"
                            >

                        <?php } else { ?>

                            <div
                                class="default-profile"
                                id="defaultProfile"
                            >

                                <i class='bx bx-user'></i>

                            </div>

                            <img
                                src=""
                                id="profilePreview"
                                alt="Profile Preview"
                                style="display:none;"
                            >

                        <?php } ?>

                    </div>


                    <label
                        for="profile_picture"
                        class="upload-btn"
                    >

                        <i class='bx bx-camera'></i>

                        Change Photo

                    </label>


                    <input
                        type="file"
                        id="profile_picture"
                        name="profile_picture"
                        accept="image/jpeg,image/png,image/webp"
                        hidden
                    >

                </div>


                <!-- FULL NAME -->

                <div class="form-group">

                    <label class="form-label">

                        <i class='bx bx-user'></i>

                        Full Name

                    </label>

                    <input
                        type="text"
                        name="full_name"
                        class="form-control"
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

                <div class="form-group">

                    <label class="form-label">

                        <i class='bx bx-envelope'></i>

                        Email Address

                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        placeholder="mentor@example.com"
                        value="<?php
                        echo htmlspecialchars(
                            $data['email'] ?? ''
                        );
                        ?>"
                        required
                    >

                </div>


                <!-- PHONE -->

                <div class="form-group">

                    <label class="form-label">

                        <i class='bx bx-phone'></i>

                        Contact Number

                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            +94
                        </span>

                        <input
                            type="text"
                            name="contact_no"
                            class="form-control"
                            placeholder="75 983 0983"
                            value="<?php
                            echo htmlspecialchars(
                                $data['contact_no'] ?? ''
                            );
                            ?>"
                            required
                        >

                    </div>

                </div>


                <!-- ADDRESS -->

                <div class="form-group">

                    <label class="form-label">

                        <i class='bx bx-map'></i>

                        Address

                    </label>

                    <textarea
                        name="address"
                        class="form-control"
                        rows="4"
                        placeholder="Enter your address"
                    ><?php
                    echo htmlspecialchars(
                        $data['address'] ?? ''
                    );
                    ?></textarea>

                </div>

            </div>

        </div>


        <!-- =====================
             PROFESSIONAL INFO
        ====================== -->

        <div class="col-lg-7">

            <div class="profile-card h-100">

                <div class="card-title-area">

                    <div class="title-icon">

                        <i class='bx bx-briefcase'></i>

                    </div>

                    <div>

                        <h2>
                            Professional Information
                        </h2>

                        <p>
                            Tell students about your expertise
                        </p>

                    </div>

                </div>


                <div class="row g-3">


                    <!-- DEPARTMENT -->

                    <div class="col-md-6">

                        <label class="form-label">
                            Department
                        </label>

                        <select
                            name="department"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select Department
                            </option>

                            <?php

                            $departments = [

                                "Software Engineering",
                                "Computer Science",
                                "Information Technology",
                                "Cyber Security",
                                "Data Science",
                                "Business Management"

                            ];

                            foreach (
                                $departments as $department
                            ) {

                                $selected =
                                    (($data['department'] ?? '') ===
                                    $department)
                                    ? 'selected'
                                    : '';

                            ?>

                                <option
                                    value="<?php
                                    echo htmlspecialchars($department);
                                    ?>"
                                    <?php echo $selected; ?>
                                >

                                    <?php
                                    echo htmlspecialchars($department);
                                    ?>

                                </option>

                            <?php } ?>

                        </select>

                    </div>


                    <!-- EXPERIENCE -->

                    <div class="col-md-6">

                        <label class="form-label">
                            Years of Experience
                        </label>

                        <input
                            type="number"
                            name="experience"
                            class="form-control"
                            min="0"
                            max="60"
                            placeholder="Example: 5"
                            value="<?php
                            echo htmlspecialchars(
                                $data['experience'] ?? ''
                            );
                            ?>"
                        >

                    </div>


                    <!-- QUALIFICATION -->

                    <div class="col-md-6">

                        <label class="form-label">
                            Qualification
                        </label>

                        <input
                            type="text"
                            name="qualification"
                            class="form-control"
                            placeholder="Example: MSc Computer Science"
                            value="<?php
                            echo htmlspecialchars(
                                $data['qualification'] ?? ''
                            );
                            ?>"
                        >

                    </div>


                    <!-- SPECIALIZATION -->

                    <div class="col-md-6">

                        <label class="form-label">
                            Specialization
                        </label>

                        <input
                            type="text"
                            name="specialization"
                            class="form-control"
                            placeholder="Example: Web Development"
                            value="<?php
                            echo htmlspecialchars(
                                $data['specialization'] ?? ''
                            );
                            ?>"
                        >

                    </div>


                    <!-- UNIVERSITY -->

                    <div class="col-12">

                        <label class="form-label">
                            University / Institution
                        </label>

                        <input
                            type="text"
                            name="university"
                            class="form-control"
                            placeholder="Enter university or institution"
                            value="<?php
                            echo htmlspecialchars(
                                $data['university'] ?? ''
                            );
                            ?>"
                        >

                    </div>


                    <!-- SKILLS -->

                    <div class="col-12">

                        <label class="form-label">
                            Skills & Expertise
                        </label>

                        <textarea
                            name="skills"
                            class="form-control"
                            rows="3"
                            placeholder="PHP, JavaScript, Project Management..."
                        ><?php
                        echo htmlspecialchars(
                            $data['skills'] ?? ''
                        );
                        ?></textarea>

                    </div>


                    <!-- BIO -->

                    <div class="col-12">

                        <label class="form-label">
                            About You
                        </label>

                        <textarea
                            name="bio"
                            class="form-control"
                            rows="5"
                            placeholder="Tell students about your mentoring experience..."
                        ><?php
                        echo htmlspecialchars(
                            $data['bio'] ?? ''
                        );
                        ?></textarea>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- SAVE BUTTON -->

    <div class="save-area">

        <button
            type="submit"
            class="save-btn"
        >

            <i class='bx bx-save'></i>

            Save Mentor Profile

        </button>

    </div>


    </form>

</div>

</section>
<?php include "Includes/footer.php"; ?>

<!-- Bootstrap JS -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<script>


/* PROFILE IMAGE PREVIEW */

const profileInput =
document.getElementById("profile_picture");

const profilePreview =
document.getElementById("profilePreview");

const defaultProfile =
document.getElementById("defaultProfile");


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

            if(defaultProfile){

                defaultProfile.style.display =
                "none";

            }

        }

    }
);

</script>


</body>

</html>
