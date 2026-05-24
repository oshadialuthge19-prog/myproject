<?php

session_start();

include "Includes/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];

   $stmt = $conn->prepare("SELECT * FROM users WHERE usersEmail=?");
   $stmt->bind_param("s", $email);
   $stmt->execute();

   $result = $stmt->get_result();

    if ($result->num_rows == 1) {

    $user = $result->fetch_assoc();

    // Verify hashed password
    if (password_verify($password, $user['usersPwd'])) {

    if(isset($_POST['remember'])){

    setcookie(
        "email",
        $email,
        time() + (86400 * 30),
        "/"
    );

}

        $_SESSION['role'] = $user['role'];
        $_SESSION['student_name'] = $user['usersName'];
        // $_SESSION['name'] = $user['usersName'];

        if ($user['role'] == 'student') {

            header("Location: student_dashbord.php");

        } elseif ($user['role'] == 'mentor') {

            header("Location: mentor_dashbord.php");

        }

        exit();

    } else {

        $_SESSION['error'] = "Invalid password!";
        header("Location: login.php");
        exit();
    }

} else {

    $_SESSION['error'] = "Invalid email!";
    header("Location: login.php");
    exit();

} 

       
}
?>





