<?php

if (!isset($_SESSION['user_id'])) {
    return;
}

$mentor_id = $_SESSION['user_id'];


/* ===============================
   FETCH MENTOR PROFILE IMAGE
================================ */

$profileQuery = $conn->prepare(
    "SELECT profile_picture
     FROM mentor_profiles
     WHERE mentor_id = ?"
);

$profileQuery->bind_param(
    "i",
    $mentor_id
);

$profileQuery->execute();

$profileResult = $profileQuery->get_result();

$mentorProfile = $profileResult->fetch_assoc();


/* ===============================
   FETCH NOTIFICATIONS
================================ */

$notificationQuery = $conn->prepare(
    "SELECT *
     FROM mentor_notifications
     WHERE mentor_id = ?
     AND is_read = 0
     ORDER BY created_at DESC"
);

$notificationQuery->bind_param(
    "i",
    $mentor_id
);

$notificationQuery->execute();

$notifications =
$notificationQuery->get_result();

$unreadCount =
$notifications->num_rows;

?>


<nav class="navbar">

    <div class="navbar_container">


        <!-- LOGO -->

        <a href="index.php" id="navbar_logo">

            <img
                src="Assets/logoo.png"
                alt="Logo"
                class="logo"
            >

            Smart Mentoring System

        </a>


        <!-- MOBILE MENU -->

        <div
            class="navbar_toggle"
            id="mobile-menu"
        >

            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>

        </div>


        <!-- NAVIGATION -->

        <ul class="navbar_menu">

            <li class="navbar_item">

                <a
                    href="mentor_dashbord.php"
                    class="navbar_links"
                >
                    Dashboard
                </a>

            </li>


            <li class="navbar_item">

                <a
                    href="mentor_appointment.php"
                    class="navbar_links"
                >
                    Appointments
                </a>

            </li>

        </ul>


        <!-- RIGHT PROFILE AREA -->

        <div class="profile-menu">


            <!-- NOTIFICATION -->

            <div class="notification-menu">

                <div
                    class="notification-icon"
                    onclick="toggleNotifications()"
                >

                    <i class='bx bx-bell'></i>


                    <?php if ($unreadCount > 0) { ?>

                        <span class="notification-count">

                            <?php
                            echo $unreadCount;
                            ?>

                        </span>

                    <?php } ?>

                </div>


                <!-- NOTIFICATION DROPDOWN -->

                <div
                    class="notification-dropdown"
                    id="notificationDropdown"
                >

                    <h3>
                        Notifications
                    </h3>


                    <?php

                    if (
                        $notifications->num_rows > 0
                    ) {

                        while (
                            $note =
                            $notifications->fetch_assoc()
                        ) {

                    ?>

                            <div class="notification-item">

                                <p>

                                    <?php
                                    echo htmlspecialchars(
                                        $note['message']
                                    );
                                    ?>

                                </p>

                                <small>

                                    <?php
                                    echo htmlspecialchars(
                                        $note['created_at']
                                    );
                                    ?>

                                </small>

                            </div>

                    <?php

                        }

                    } else {

                        echo "<p>No notifications</p>";
                    }

                    ?>


                    <a
                        href="mark_mentor_notifications.php"
                        class="mark-read-btn"
                    >

                        Mark all as read

                    </a>

                </div>

            </div>


            <!-- PROFILE ICON -->

            <div
                class="profile-icon"
                onclick="toggleMenu()"
            >

                <?php if (
                    !empty(
                        $mentorProfile['profile_picture']
                    )
                ) { ?>

                    <img
                        src="<?php
                        echo htmlspecialchars(
                            $mentorProfile['profile_picture']
                        );
                        ?>"
                        class="nav-profile-img"
                        alt="Mentor Profile"
                    >

                <?php } else { ?>

                    <i class='bx bx-user'></i>

                <?php } ?>

            </div>


            <!-- PROFILE DROPDOWN -->

            <div
                class="sub-menu-wrap"
                id="subMenu"
            >

                <div class="sub-menu">


                    <a
                        href="mentor_profile.php"
                        class="sub-menu-link"
                    >

                        <i class='bx bx-user'></i>

                        <p>My Profile</p>

                    </a>


                    <a
                        href="#"
                        class="sub-menu-link"
                    >

                        <i class='bx bx-bell'></i>

                        <p>Notifications</p>

                    </a>


                    <a
                        href="mentor_messages.php"
                        class="sub-menu-link"
                    >

                        <i class='bx bx-message-detail'></i>

                        <p>Messages</p>

                    </a>


                    <a
                        href="#"
                        class="sub-menu-link"
                    >

                        <i class='bx bx-cog'></i>

                        <p>Settings</p>

                    </a>


                    <a
                        href="logout.php"
                        class="sub-menu-link"
                    >

                        <i class='bx bx-log-out'></i>

                        <p>Logout</p>

                    </a>


                </div>

            </div>


        </div>

    </div>

</nav>


<script>


function toggleNotifications() {

    const notificationDropdown =
    document.getElementById("notificationDropdown");

    notificationDropdown.classList.toggle(
        "open-notifications"
    );

}

function toggleMenu() {

    const subMenu =
    document.getElementById("subMenu");

    subMenu.classList.toggle(
        "open-menu"
    );

}

</script>