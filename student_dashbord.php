<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}
?>
 

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="student_dashbord.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <title>Document</title>
</head>
<body>



    <!-- Navigation bar -->
<nav class="navbar">
    <div class="navbar_container">

        <a href="index.php" id="navbar_logo">
            <img src="Assets/logoo.png" alt="Logo" class="logo">
            Smart Mentoring System
        </a>

        <div class="navbar_toggle" id="mobile-menu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>

        <ul class="navbar_menu">

            <li class="navbar_item">
                <a href="student_dashboard.php" class="navbar_links">Dashboard</a>
            </li>

            <li class="navbar_item">
                <a href="#" class="navbar_links">Resources</a>
            </li>

            <li class="navbar_item">
                <a href="#" class="navbar_links">Appointments</a>
            </li>

            <!-- <li class="navbar_item">
                <a href="#" class="navbar_links">Contact</a>
            </li> -->

            <!-- <li class="navbar_btn">
                <a href="login.php" class="button">Login</a>
            </li> -->

        </ul>

        <div class="profile-menu">

    <div class="profile-icon" onclick="toggleMenu()">
        <i class='bx bx-user'></i>
    </div>

    <div class="sub-menu-wrap" id="subMenu">
        <div class="sub-menu">

            <a href="#" class="sub-menu-link">
                <i class='bx bx-user'></i>
                <p>My Profile</p>
            </a>

            <a href="#" class="sub-menu-link">
                <i class='bx bx-bell'></i>
                <p>Notifications</p>
            </a>

            <a href="#" class="sub-menu-link">
                <i class='bx bx-message-detail'></i>
                <p>Messages</p>
            </a>

            <a href="#" class="sub-menu-link">
                <i class='bx bx-cog'></i>
                <p>Settings</p>
            </a>

            <a href="logout.php" class="sub-menu-link">
                <i class='bx bx-log-out'></i>
                <p>Logout</p>
            </a>

        </div>
    </div>

</div>

    </div>
</nav>

<!-- hero section -->

<section class="hero-banner">

    <div class="welcome-text">
        <h1>
            Welcome, <?php echo $_SESSION['student_name']; ?> 👋
        </h1>
    </div>

    <div class="quote-box">
        <p id="daily-quote">
            "Believe you can and you're halfway there."
        </p>
    </div>

</section>

<!-- GPA Calculator -->

<section class="gpa-section">

    <div class="gpa-container">

        <h2>GPA Calculator</h2>

        <div id="subjects-container">

            <div class="subject">

                <select class="grade">
                    <option value="4.0">A+</option>
                    <option value="3.7">A-</option>
                    <option value="3.3">B+</option>
                    <option value="3.0">B</option>
                    <option value="2.7">B-</option>
                    <option value="2.3">C+</option>
                    <option value="2.0">C</option>
                    <option value="1.0">D</option>
                    <option value="0">F</option>
                </select>

                <input type="number"
                class="credit"
                placeholder="Credits">

            </div>

        </div>

        <div class="gpa-buttons">

            <button onclick="addSubject()">
                Add Subject
            </button>

            <button onclick="calculateGPA()">
                Calculate GPA
            </button>

        </div>

        <h1 id="gpa-result">0.00</h1>

    </div>

   <div class="mentor-card">

    <h2>Submit GPA</h2>

    <select class="mentor-select">

        <option>Select Mentor</option>

        <option>Dr. Silva</option>

        <option>Ms. Perera</option>

        <option>Mr. Fernando</option>

    </select>

    <input type="text"
    placeholder="Semester"
    class="semester-input">

    <button>
        Submit GPA
    </button>

</div>

</select>

</div>

</section>
        
  <script>

let subMenu = document.getElementById("subMenu");

function toggleMenu(){
    subMenu.classList.toggle("open-menu");
}

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



function addSubject(){

    let container =
    document.getElementById("subjects-container");

    let subject =
    document.createElement("div");

    subject.classList.add("subject");

    subject.innerHTML = `

        <select class="grade">

            <option value="4.0">A+</option>

            <option value="3.7">A-</option>

            <option value="3.3">B+</option>

            <option value="3.0">B</option>

            <option value="2.7">B-</option>

            <option value="2.3">C+</option>

            <option value="2.0">C</option>

            <option value="1.0">D</option>

            <option value="0">F</option>

        </select>

        <input type="number"
        class="credit"
        placeholder="Credits">

    `;

    container.appendChild(subject);
}



function calculateGPA(){

    let grades =
    document.querySelectorAll(".grade");

    let credits =
    document.querySelectorAll(".credit");

    let totalPoints = 0;

    let totalCredits = 0;

    for(let i = 0; i < grades.length; i++){

        let grade =
        parseFloat(grades[i].value);

        let credit =
        parseFloat(credits[i].value);

        if(!isNaN(credit)){

            totalPoints += grade * credit;

            totalCredits += credit;
        }
    }

    let gpa =
    totalPoints / totalCredits;

    document.getElementById("gpa-result")
    .innerText = gpa.toFixed(2);
}





</script>
</body>
</html>

<?php include "Includes/footer.php"; ?>