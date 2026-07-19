<?php

$current_student =
$_SESSION['user_id'];

$navProfile = $conn->prepare(

"SELECT profile_picture
FROM student_profiles
WHERE student_id=?"

);

$navProfile->bind_param(
"i",
$current_student
);

$navProfile->execute();

$navResult =
$navProfile->get_result();

$navData =
$navResult->fetch_assoc();

?>

<!-- Navigation bar -->
<nav class="navbar">
    <div class="navbar_container">

    <!-- Left Side -->
    <div class="navbar_left">

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
                <a href="student_dashbord.php" class="navbar_links">Dashboard</a>
            </li>

            <!-- <li class="navbar_item">
                <a href="#" class="navbar_links">Resources</a>
            </li> -->

            <li class="navbar_item">
                <a href="book_appointment.php" class="navbar_links">Appointments</a>
            </li>

            <!-- <li class="navbar_item">
                <a href="#" class="navbar_links">Contact</a>
            </li> -->

            <!-- <li class="navbar_btn">
                <a href="login.php" class="button">Login</a>
            </li> -->

            <li class="navbar_item">
    <a href="chat.php" class="navbar_links">
        <i class='bx bx-message-rounded-dots'></i>
        Chat
    </a>
</li>

        </ul>
        </div>

        <!-- Right Side -->
            <div class="navbar_right">

        <?php

             $notificationQuery = $conn->prepare(
              "SELECT *
               FROM system_notifications
            WHERE user_id=?
            AND is_read=0
            ORDER BY created_at DESC"
            );
                 $notificationQuery->bind_param(
                 "i",
                $_SESSION['user_id']
            );

                $notificationQuery->execute();

                $notifications = $notificationQuery->get_result();

                $unreadCount = $notifications->num_rows;

                ?>
            

<div class="profile-menu">
       <div class="notification-menu">

    <div class="notification-icon" onclick="toggleNotifications()">
        <i class='bx bx-bell'></i>
        <?php if($unreadCount > 0){ ?>
        <span class="notification-count">
         <?php echo $unreadCount; ?></span>

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

          <a
            href="<?php echo htmlspecialchars($note['notification_link']); ?>"
             class="notification-item"
              >

    <p>
        <?php echo htmlspecialchars($note['message']); ?>
    </p>

    <small>
        <?php echo htmlspecialchars($note['created_at']); ?>
    </small>

</a>

        <?php

            }

        }else{

            echo "<p>No notifications</p>";
        }

        ?>

        <a href="mark_notifications.php"
        class="mark-read-btn">

        Mark all as read

        </a>

    </div>

</div>

    <div class="profile-icon" onclick="toggleMenu()">

        <?php if(!empty($navData['profile_picture'])){ ?>

            <img
            src="<?php echo $navData['profile_picture']; ?>"
            class="nav-profile-img">

        <?php } else { ?>

            <i class='bx bx-user'></i>

        <?php } ?>

    </div>

    <div class="sub-menu-wrap" id="subMenu">

        <div class="sub-menu">

            <a href="student_profile.php" class="sub-menu-link">
                <i class='bx bx-user'></i>
                <p>My Profile</p>
            </a>

        

            <a href="student_messages.php" class="sub-menu-link">
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

</div> <!-- profile-menu -->

</div> <!-- navbar_right -->

</div> <!-- navbar_container -->

</nav>

<script>

function toggleNotifications(){

    document
    .getElementById("notificationDropdown")
    .classList.toggle("open-notifications");

}

let subMenu =
document.getElementById("subMenu");

function toggleMenu(){

    subMenu.classList.toggle("open-menu");

}

</script>