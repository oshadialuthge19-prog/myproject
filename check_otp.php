<?php
session_start();
require_once "Includes/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $otp = trim($_POST['otp']);

    if (!isset($_SESSION['reset_email'])) {
        header("Location: forgot_pwd.php");
        exit();
    }

    $email = $_SESSION['reset_email'];

    $stmt = $conn->prepare("
        SELECT otp, otp_expiry
        FROM users
        WHERE usersEmail = ?
    ");

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $user = $result->fetch_assoc();

        if (
            $user['otp'] == $otp &&
            strtotime($user['otp_expiry']) > time()
        ) {

            $_SESSION['otp_verified'] = true;
             $clear = $conn->prepare("
    UPDATE users
    SET otp = NULL, otp_expiry = NULL
    WHERE usersEmail = ?
");
$clear->bind_param("s", $email);
$clear->execute();
            header("Location: reset_pwd.php");
            exit();

        } else {

            $_SESSION['otp_error'] = "Invalid or expired OTP.";
header("Location: verify_otp.php");
exit();

        }

    } else {

        echo "User not found.";

    }

}
?>