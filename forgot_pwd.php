
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
</head>
<body>

<form action="reset_pwd.php" method="POST">

    <input type="email"
    name="email"
    placeholder="Enter Email"
    required>

    <input type="password"
    name="new_password"
    placeholder="Enter New Password"
    required>

    <button type="submit">
        Reset Password
    </button>

</form>

</body>
</html>