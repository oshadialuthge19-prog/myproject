
<?php
session_start();

include "Includes/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

// fetch real mentors from database
$mentors =
$conn->query(
"SELECT usersId, usersName
FROM users
WHERE role='mentor'"
);

?>
 

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="student_dashbord.css">
  <link rel="stylesheet" href="student_header.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <title>Document</title>
</head>
<body>

<?php include "Includes/student_header.php"; ?>

<!-- hero section -->

<section class="hero-banner">

    <div class="welcome-text">
        <h1>
            Welcome, <?php echo $_SESSION['name']; ?> 👋
        </h1>
    </div>

    <div class="quote-box">
        <p id="daily-quote">
            "Believe you can and you're halfway there."
        </p>
    </div>

</section>

        
  <script>

// Daily Quote

const quotes = [

"Success is the sum of small efforts repeated daily.",

"Believe in yourself and all that you are.",

"Every day is a new opportunity to grow.",

"Your future is created by what you do today.",

"Small progress is still progress."

];

const today = new Date().getDate();

document.getElementById("daily-quote").innerText =
quotes[today % quotes.length];

// GPA Calculator


</script>
</body>
</html>


<?php include "Includes/footer.php"; ?>