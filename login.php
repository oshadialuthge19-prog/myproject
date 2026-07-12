<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
     rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'
    rel='stylesheet'>
    <title>Login</title>

</head>
<body class="auth-page">

<div class="background"></div>
    <div class="container">
      <div class="content">
        <h2>
          Connect <br>
          Learn & Grow
          </h2>
        <div class="text-sci">
          <p>This is a safe and supportive space where students and mentors
            connect, learn and grow together. Whether you are here to gain guidence,
            share knowledge, track progress or achive your goals you are in the right 
            place.
          </p>
        </div>
      </div>


      <div class="login-reg-box">

       <!-- login -->
        <div class="form">
        <div class="box-login">
          <form action="login_process.php" method="POST">
            <h2>Login</h2>

            <div class="input-box">
              <span class="icon"><i class="bx bx-envelope"></i></span>
             <input type="email"
name="email"
value="<?php 
if(isset($_COOKIE['email'])){
    echo $_COOKIE['email'];
}
?>"
required>

              <label>Email</label>
            </div>

            <div class="input-box">
              <span class="icon"><i class='bx bxs-lock-alt'></i></span>
              <input type="password" name="password" required>
              <label>Password</label>
            </div>
           <div class="remember-forgot">
            <label><input type="checkbox" name="remember">Remember me</label>
            <a href="forgot_pwd.php">Forgot password?</a>
           </div>

           <!-- <div class = "role-buttons">
            <button type="button" class="role-btn active" id="studentBtn">
              Student
            </button>

            <button type="button" class="role-btn" id="mentorBtn">
              Mentor
            </button>
            </div> -->

            <div class="role-buttons">

                 <a href="student_signup.php" class="role-link">
                     Student Sign Up
                </a>

                 <a href="mentor_signup.php" class="role-link">
                     Mentor Sign Up
                </a>

            </div>

            <input type="hidden" name="role" id="roleInput" value="student">

           <button type="submit" class="btn">Login</button>
          </form>
        </div>

        <!-- register -->
         <!-- student signup -->
        <!-- <div class="box-student-register">
          <form action="student_signup.php" method="POST">
            <h2>Student Sign Up</h2>

            <div class="input-box">
              <span class="icon"><i class="bx bx-user"></i></span>
              <input type="text" name="name" required>
              <label>Name</label>
            </div>

            <div class="input-box">
              <span class="icon"><i class="bx bx-envelope"></i></span>
              <input type="email" name="email" required>
              <label>Email</label>
            </div>

            <div class="input-box">
              <span class="icon"><i class='bx bxs-lock-alt'></i></span>
              <input type="password" name="password" required>
              <label>Password</label>
            </div>

           <button type="submit" class="btn">Sign Up</button>

           <div class="login-register">
            <p>Already have an account? <a href="#" class="login-link">Login</a></p>
           </div>
          </form>
        </div> -->

        <!-- mentor signup -->
        <!-- <div class="box-mentor-register">
          <form action="mentor_signup.php" method="POST">
            <h2>Mentor Sign Up</h2>

            <div class="input-box">
              <span class="icon"><i class="bx bx-user"></i></span>
              <input type="text" name="name" required>
              <label>Name</label>
            </div>

            <div class="input-box">
              <span class="icon"><i class="bx bx-envelope"></i></span>
              <input type="email" name="email" required>
              <label>Email</label>
            </div>

            <div class="input-box">
              <span class="icon"><i class='bx bxs-lock-alt'></i></span>
              <input type="password" name="password" required>
              <label>Password</label>
            </div>

           <button type="submit" class="btn">Sign Up</button>

           <div class="login-register">
            <p>Already have an account? <a href="#" class="login-link">Login</a></p>
           </div>
          </form>

      </div> -->
    </div>

    <script src="script.js"></script>
</body>
</html>