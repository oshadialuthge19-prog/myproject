<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

function sendOTP($email, $otp)
{
    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;

        // CHANGE THESE
        $mail->Username   = 'oshadialuthge19@gmail.com';
        $mail->Password   = 'lnhbakqkhqiovtmt';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('oshadialuthge19@gmail.com', 'Smart Mentoring System');

        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Password Reset OTP';

        $mail->Body = "
        <h2>Smart Mentoring System</h2>

        <p>Your OTP is:</p>

        <h1>$otp</h1>

        <p>This code will expire in 10 minutes.</p>
        ";

        $mail->send();

        return true;

    } catch (Exception $e) {

        return false;

    }

}