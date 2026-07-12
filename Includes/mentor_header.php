<?php

session_start();
include "Includes/db.php";

$mentor_id = $_SESSION['user_id'];

$query = $conn->prepare(
"SELECT * FROM users WHERE usersId=?"
);

$query->bind_param("i", $mentor_id);

$query->execute();

$result = $query->get_result();

$mentor = $result->fetch_assoc();


$notificationQuery = $conn->prepare(
"SELECT * FROM mentor_notifications
WHERE mentor_id=?
AND is_read=0
ORDER BY created_at DESC"
);

$notificationQuery->bind_param(
"i",
$_SESSION['user_id']
);

$notificationQuery->execute();

$notifications =
$notificationQuery->get_result();

$unreadCount =
$notifications->num_rows;

?>


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
                <a href="mentor_dashbord.php" class="navbar_links">Dashboard</a>
            </li>

            <!-- <li class="navbar_item">
                <a href="#" class="navbar_links">Resources</a>
            </li> -->

            <li class="navbar_item">
                <a href="mentor_appointment.php" class="navbar_links">Appointments</a>
            </li>

            <!-- <li class="navbar_item">
                <a href="#" class="navbar_links">Contact</a>
            </li> -->

            <!-- <li class="navbar_btn">
                <a href="login.php" class="button">Login</a>
            </li> -->

        </ul>

        <div class="profile-menu">
            <div class="notification-menu">

    <div class="notification-icon"
    onclick="toggleNotifications()">

        <i class='bx bx-bell'></i>

        <?php if($unreadCount > 0){ ?>

        <span class="notification-count">

            <?php echo $unreadCount; ?>

        </span>

        <?php } ?>

    </div>

    <div class="notification-dropdown"
    id="notificationDropdown">

        <h3>Notifications</h3>

        <?php

        if($notifications->num_rows > 0){

            while($note =
            $notifications->fetch_assoc()){

        ?>

        <div class="notification-item">

            <p>

            <?php
            echo $note['message'];
            ?>

            </p>

            <small>

            <?php
            echo $note['created_at'];
            ?>

            </small>

        </div>

        <?php

            }

        }else{

            echo "<p>No notifications</p>";
        }

        ?>

        <a href="mark_mentor_notifications.php"
        class="mark-read-btn">

        Mark all as read

        </a>

    </div>

</div>

    <div class="profile-icon" onclick="toggleMenu()">
        <i class='bx bx-user'></i>
    </div>

    <div class="sub-menu-wrap" id="subMenu">
        <div class="sub-menu">

            <a href="mentor_profile.php" class="sub-menu-link">
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

<script>
    function toggleNotifications(){

    document
    .getElementById("notificationDropdown")
    .classList.toggle("open-notifications");

}

</script>