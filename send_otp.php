<?php
session_start();
require_once __DIR__ . "/Includes/db.php";
require_once __DIR__ . "/Includes/mail_config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);

    // Check if email exists
    $stmt = $conn->prepare("SELECT usersId FROM users WHERE usersEmail = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);

        // OTP expires in 10 minutes
        $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

        // Save OTP
        $update = $conn->prepare("
            UPDATE users
            SET otp = ?, otp_expiry = ?
            WHERE usersEmail = ?
        ");

        $update->bind_param("sss", $otp, $expiry, $email);
        $update->execute();

        // Send email
        if (sendOTP($email, $otp)) {

            $_SESSION['reset_email'] = $email;

            header("Location: verify_otp.php");
            exit();

        } else {

            echo "Failed to send OTP.";

        }

    } else {

        echo "Email not found.";

    }

}
?>