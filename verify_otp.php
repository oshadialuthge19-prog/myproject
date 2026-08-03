<?php
session_start();

if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_pwd.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">

    <div class="card shadow-lg border-0 rounded-4 p-4" style="max-width:450px; width:100%;">

        <div class="text-center mb-4">
            <i class="fa-solid fa-shield-halved fa-3x text-primary mb-3"></i>
            <h2 class="fw-bold">Verify OTP</h2>
            <p class="text-muted">
                Enter the 6-digit code sent to your email.
            </p>
        </div>

            <?php
if (isset($_SESSION['otp_error'])) {
?>
    <div class="alert alert-danger">
        <?php
        echo htmlspecialchars($_SESSION['otp_error']);
        unset($_SESSION['otp_error']);
        ?>
    </div>
<?php
}
?>

        <form action="check_otp.php" method="POST">

            <div class="mb-3">
                <label class="form-label">OTP Code</label>

                <input
                    type="text"
                    name="otp"
                    maxlength="6"
                    class="form-control text-center fs-4"
                    placeholder="123456"
                    required>
            </div>

            <button class="btn btn-primary w-100">
                <i class="fa-solid fa-check me-2"></i>
                Verify OTP
            </button>

        </form>

    </div>

</div>

</body>
</html>