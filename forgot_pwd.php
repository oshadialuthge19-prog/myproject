
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Forgot Password</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome (for icons) -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">

    <div class="card shadow-lg border-0 rounded-4 p-4" style="max-width:450px; width:100%;">

        <div class="text-center mb-4">
            <i class="fa-solid fa-key fa-3x text-primary mb-3"></i>
            <h2 class="fw-bold">Forgot Password</h2>
            <p class="text-muted">
                Enter your email address to receive a verification code.
            </p>
        </div>

        <form action="send_otp.php" method="POST">

            <div class="mb-3">
                <label class="form-label">Email Address</label>

                <div class="input-group">

                    <span class="input-group-text">
                        <i class="fa-solid fa-envelope"></i>
                    </span>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        placeholder="Enter your email"
                        required>

                </div>
            </div>


            <button type="submit" class="btn btn-primary w-100">
                <i class="fa-solid fa-rotate-right me-2"></i>
                Send Verification Code
            </button>

        </form>

        <div class="text-center mt-3">

            <a href="login.php" class="text-decoration-none">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Login
            </a>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>