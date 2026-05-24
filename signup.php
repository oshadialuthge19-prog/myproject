
<?php
include "Includes/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $role = "student";

    // ✅ 1. CHECK IF EMAIL EXISTS (PUT HERE)
    $check = "SELECT * FROM users WHERE usersEmail='$email'";
    $result = mysqli_query($conn, $check);

    if (mysqli_num_rows($result) > 0) {

 // ❌ Email already exists
    $_SESSION['error'] = "Email already exists!";
        header("Location: login.php");
        exit();

    } else {

        // ✅ 2. INSERT USER (PUT HERE)
        $sql = "INSERT INTO users (usersName, usersEmail, usersPwd, role)
                VALUES ('$name', '$email', '$password', '$role')";

        if (mysqli_query($conn, $sql)) {

            // ✅ 3. REDIRECT AFTER SUCCESS (PUT HERE)
            $_SESSION['success'] = "Signup successful!";
            header("Location: login.php");
            exit();

        } else {
             $_SESSION['error'] = "Something went wrong!";
            header("Location: login.php");
            exit();
        }
    }
}
?>
