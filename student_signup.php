<?php

include "Includes/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $studentid = trim($_POST['studentid']);
    $email = trim($_POST['email']);
    $course = trim($_POST['course']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $role = "student";

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format!");
    }

    // Password length
    if (strlen($password) < 8) {
        die("Password must be at least 8 characters!");
    }

    // Confirm password
    if ($password != $confirm_password) {
        die("Passwords do not match!");
    }

    // Check duplicate email
    $checkEmail = $conn->prepare("SELECT * FROM users WHERE usersEmail=?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();

    $result = $checkEmail->get_result();

    if ($result->num_rows > 0) {
        die("Email already exists!");
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert securely
    $stmt = $conn->prepare("INSERT INTO users 
    (usersName, usersEmail, usersPwd, role, student_id, course)
    VALUES (?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "ssssss",
        $name,
        $email,
        $hashedPassword,
        $role,
        $studentid,
        $course
    );

    if ($stmt->execute()) {

        header("Location: login.php");
        exit();

    } else {

        echo "Signup failed!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Signup</title>

    <!-- Main website styles -->
    <link rel="stylesheet" href="index.css">

    <!-- Signup page styles -->
    <link rel="stylesheet" href="student_signup.css">

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Font Awesome -->
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    

<?php include "Includes/header.php"; ?>

<!-- Signup Section -->
<div class="signup-container">

    <div class="signup-box">

        <form action="student_signup.php" method="POST">

            <h2>Student Signup</h2>

            <div class="input-box">
                <span class="icon"><i class="bx bx-user"></i></span>
                <input type="text" name="name" required>
                <label>Full Name</label>
            </div>

            <div class="input-box">
                <span class="icon"><i class='bx bxs-id-card'></i></span>
                <input type="text" name="studentid" required>
                <label>Student ID</label>
            </div>

            <div class="input-box">
                <span class="icon"><i class="bx bx-envelope"></i></span>
                <input type="email" name="email" required>
                <label>Email</label>
            </div>

            <!-- <div class="input-box">
                <span class="icon"><i class='bx bx-book-bookmark'></i></span>
                <input type="text" name="course" required>
                <label>Course</label>
            </div> -->

            <div class="input-box">

    <span class="icon">
        <i class='bx bx-book-bookmark'></i>
    </span>

    <select name="course" required>

        <option value="" disabled selected hidden>
            Select Course
        </option>

        <option value="Software Engineering">
            Software Engineering
        </option>

        <option value="Cyber Security">
            Cyber Security
        </option>

        <option value="Data Science">
            Data Science
        </option>

        <option value="Business Management">
            Business Management
        </option>

        <option value="Graphic Design">
            Graphic Design
        </option>

    </select>


</div>

            <div class="input-box">
                <span class="icon"><i class="bx bx-lock-alt"></i></span>
                <input type="password" name="password" required>
                <label>Password</label>
            </div>

            <div class="input-box">
                <span class="icon"><i class="bx bx-lock-alt"></i></span>
                <input type="password" name="confirm_password" required>
                <label>Confirm Password</label>
            </div>

            <button type="submit" class="btn">
                Signup
            </button>

            <div class="login-link">
                <p>
                    Already have an account?
                    <a href="login.php">Login</a>
                </p>
            </div>

        </form>

    </div>

</div>

<?php include "Includes/footer.php"; ?>

</body>
</html>