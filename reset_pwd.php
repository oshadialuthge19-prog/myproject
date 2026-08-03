<?php
session_start();
require_once "Includes/db.php";

if (!isset($_SESSION['otp_verified']) || !isset($_SESSION['reset_email'])) {
    header("Location: forgot_pwd.php");
    exit();
}

$email = $_SESSION['reset_email'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $password = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if ($password != $confirm) {
        $error = "Passwords do not match.";
    } else {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("
            UPDATE users
            SET usersPwd = ?, otp = NULL, otp_expiry = NULL
            WHERE usersEmail = ?
        ");

        $stmt->bind_param("ss", $hashedPassword, $email);

        if ($stmt->execute()) {

            session_destroy();

            echo "<script>
                alert('Password updated successfully!');
                window.location='login.php';
            </script>";

            exit();

        } else {

            $error = "Something went wrong.";

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Reset Password</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">

<div class="card shadow-lg p-4" style="max-width:450px;width:100%;">

<h3 class="text-center mb-4">
<i class="fa-solid fa-lock text-primary"></i>
Create New Password
</h3>

<?php if(isset($error)){ ?>

<div class="alert alert-danger">
    <?php echo $error; ?>
</div>

<?php } ?>

<form method="POST">

<div class="mb-3">

<label class="form-label">
New Password
</label>

<input
type="password"
name="new_password"
class="form-control"
required>

</div>

<div class="mb-3">

<label class="form-label">
Confirm Password
</label>

<input
type="password"
name="confirm_password"
class="form-control"
required>

</div>

<button class="btn btn-primary w-100">

Reset Password

</button>

</form>

</div>

</div>

</body>
</html>