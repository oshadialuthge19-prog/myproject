<?php

session_start();

include "Includes/db.php";

if (
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'mentor'
) {
    header("Location: login.php");
    exit();
}

$mentor_id = $_SESSION['user_id'];

$message = "";
$messageType = "";


/* ===============================
   LOAD SETTINGS
================================ */

$settings = $conn->prepare(
    "SELECT *
     FROM mentor_settings
     WHERE mentor_id=?"
);

$settings->bind_param(
    "i",
    $mentor_id
);

$settings->execute();

$result = $settings->get_result();

$data = $result->fetch_assoc();


/* ===============================
   CREATE DEFAULT SETTINGS
================================ */

if (!$data) {

    $insert = $conn->prepare(
        "INSERT INTO mentor_settings
        (mentor_id)
        VALUES(?)"
    );

    $insert->bind_param(
        "i",
        $mentor_id
    );

    $insert->execute();

    $settings->execute();

    $result = $settings->get_result();

    $data = $result->fetch_assoc();

}


/* ===============================
   SAVE SETTINGS
================================ */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

/* ===============================
   CHANGE PASSWORD
================================ */

$current_password =
trim($_POST['current_password'] ?? '');

$new_password =
trim($_POST['new_password'] ?? '');

$confirm_password =
trim($_POST['confirm_password'] ?? '');


if (
    !empty($current_password) ||
    !empty($new_password) ||
    !empty($confirm_password)
) {

    if (
        empty($current_password) ||
        empty($new_password) ||
        empty($confirm_password)
    ) {

        $message =
        "Please fill all password fields.";

        $messageType =
        "danger";

    }

    elseif (
        $new_password !== $confirm_password
    ) {

        $message =
        "New passwords do not match.";

        $messageType =
        "danger";

    }

    else {

        $user = $conn->prepare(

            "SELECT usersPwd
             FROM users
             WHERE usersId=?"

        );

        $user->bind_param(
            "i",
            $mentor_id
        );

        $user->execute();

        $passwordResult =
        $user->get_result();

        $account =
        $passwordResult->fetch_assoc();


        if (
            password_verify(
                $current_password,
                $account['usersPwd']
            )
        ) {

            $hashedPassword =
            password_hash(
                $new_password,
                PASSWORD_DEFAULT
            );

            $updatePassword =
            $conn->prepare(

                "UPDATE users
                 SET usersPwd=?
                 WHERE usersId=?"

            );

            $updatePassword->bind_param(

                "si",

                $hashedPassword,

                $mentor_id

            );

            $updatePassword->execute();

            $message =
            "Password changed successfully.";

            $messageType =
            "success";

        }

        else {

            $message =
            "Current password is incorrect.";

            $messageType =
            "danger";

        }

    }

}

    $notify_appointments =
        isset($_POST['notify_appointments']) ? 1 : 0;

    $notify_chat =
        isset($_POST['notify_chat']) ? 1 : 0;

    $notify_gpa =
        isset($_POST['notify_gpa']) ? 1 : 0;

    $notify_system =
        isset($_POST['notify_system']) ? 1 : 0;

        $email_appointments =
    isset($_POST['email_appointments']) ? 1 : 0;

$email_chat =
    isset($_POST['email_chat']) ? 1 : 0;

$email_gpa =
    isset($_POST['email_gpa']) ? 1 : 0;

$email_system =
    isset($_POST['email_system']) ? 1 : 0;

    $show_online_status =
        isset($_POST['show_online_status']) ? 1 : 0;

    $show_last_seen =
        isset($_POST['show_last_seen']) ? 1 : 0;


    $theme =
        $_POST['theme'] ?? "light";

    $session_duration =
        intval($_POST['session_duration']);

    $break_time =
        intval($_POST['break_time']);

    $max_daily_sessions =
        intval($_POST['max_daily_sessions']);


    $update = $conn->prepare(

"UPDATE mentor_settings SET

notify_appointments=?,
notify_chat=?,
notify_gpa=?,
notify_system=?,

email_appointments=?,
email_chat=?,
email_gpa=?,
email_system=?,

show_online_status=?,
show_last_seen=?,

theme=?,

session_duration=?,
break_time=?,
max_daily_sessions=?

WHERE mentor_id=?"

);


    $update->bind_param(

"iiiiiiiiiisiiii",

$notify_appointments,
$notify_chat,
$notify_gpa,
$notify_system,

$email_appointments,
$email_chat,
$email_gpa,
$email_system,

$show_online_status,
$show_last_seen,

$theme,

$session_duration,
$break_time,
$max_daily_sessions,

$mentor_id

);


    if($update->execute()){

        $message =
        "Settings updated successfully.";

        $messageType =
        "success";

    }else{

        $message =
        "Unable to update settings.";

        $messageType =
        "danger";

    }


    $settings->execute();

    $result =
    $settings->get_result();

    $data =
    $result->fetch_assoc();

}

?>







<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0"
>

<title>Mentor Settings</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
rel="stylesheet">

<link
rel="stylesheet"
href="Includes/mentor_header.css">

<link
rel="stylesheet"
href="Includes/footer.css">

<link
rel="stylesheet"
href="mentor_settings.css">

</head>

<body>

<?php include "Includes/mentor_header.php"; ?>

<section class="settings-section">

<div class="container">
<form method="POST">

<div class="settings-heading">

<?php if(!empty($message)){ ?>

<div
class="alert alert-<?php echo $messageType; ?>
alert-dismissible fade show"
role="alert">

<?php echo htmlspecialchars($message); ?>

<button
type="button"
class="btn-close"
data-bs-dismiss="alert">
</button>

</div>

<?php } ?>

<span class="settings-badge">
Mentor Account
</span>

<h1>Settings</h1>

<p>
Manage your account preferences and mentoring options.
</p>

</div>

<div class="settings-card">

    <div class="card-title-area">

        <div class="title-icon">

            <i class='bx bx-lock-alt'></i>

        </div>

        <div>

            <h2>Security</h2>

            <p>
                Update your account password securely.
            </p>

        </div>

    </div>


    <div class="setting-row">

        <div>

            <h5>Password</h5>

            <p>
                Click below to change your password.
            </p>

        </div>

        <button
            class="btn btn-primary"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#passwordCollapse">

            Change Password

        </button>

    </div>


    <div
        class="collapse mt-4"
        id="passwordCollapse">

        <div class="row g-3">

            <div class="col-md-4">

                <label class="form-label">

                    Current Password

                </label>

                <input
                    type="password"
                    class="form-control"
                    name="current_password">

            </div>


            <div class="col-md-4">

                <label class="form-label">

                    New Password

                </label>

                <input
                    type="password"
                    class="form-control"
                    name="new_password">

            </div>


            <div class="col-md-4">

                <label class="form-label">

                    Confirm Password

                </label>

                <input
                    type="password"
                    class="form-control"
                    name="confirm_password">

            </div>

        </div>

    </div>

</div>


<div class="settings-card">

<div class="card-title-area">

<div class="title-icon">

<i class='bx bx-bell'></i>

</div>

<div>

<h2>Notification Preferences</h2>

<p>

Choose which notifications you receive.

</p>

</div>

</div>


<div class="form-check form-switch">

<input
class="form-check-input"
type="checkbox"
name="notify_appointments"
<?php if($data['notify_appointments']) echo "checked"; ?>>

<label class="form-check-label">

Appointment Notifications

</label>

</div>


<div class="form-check form-switch">

<input
class="form-check-input"
type="checkbox"
name="notify_chat"
<?php if($data['notify_chat']) echo "checked"; ?>>

<label class="form-check-label">

Chat Notifications

</label>

</div>


<div class="form-check form-switch">

<input
class="form-check-input"
type="checkbox"
name="notify_gpa"
<?php if($data['notify_gpa']) echo "checked"; ?>>

<label class="form-check-label">

GPA Notifications

</label>

</div>


<div class="form-check form-switch">

<input
class="form-check-input"
type="checkbox"
name="notify_system"
<?php if($data['notify_system']) echo "checked"; ?>>

<label class="form-check-label">

System Notifications

</label>

</div>

</div>


<div class="settings-card">

    <div class="card-title-area">

        <div class="title-icon">
            <i class='bx bx-envelope'></i>
        </div>

        <div>

            <h2>Email Preferences</h2>

            <p>
                Choose which emails you would like to receive.
            </p>

        </div>

    </div>

    <div class="form-check form-switch">

        <input
            class="form-check-input"
            type="checkbox"
            name="email_appointments"
            <?php if($data['email_appointments']) echo "checked"; ?>>

        <label class="form-check-label">

            Appointment Emails

        </label>

    </div>

    <div class="form-check form-switch">

        <input
            class="form-check-input"
            type="checkbox"
            name="email_chat"
            <?php if($data['email_chat']) echo "checked"; ?>>

        <label class="form-check-label">

            Chat Emails

        </label>

    </div>

    <div class="form-check form-switch">

        <input
            class="form-check-input"
            type="checkbox"
            name="email_gpa"
            <?php if($data['email_gpa']) echo "checked"; ?>>

        <label class="form-check-label">

            GPA Emails

        </label>

    </div>

    <div class="form-check form-switch">

        <input
            class="form-check-input"
            type="checkbox"
            name="email_system"
            <?php if($data['email_system']) echo "checked"; ?>>

        <label class="form-check-label">

            System Emails

        </label>

    </div>

</div>

<div class="settings-card">

    <div class="card-title-area">

        <div class="title-icon">
            <i class='bx bx-message-rounded-dots'></i>
        </div>

        <div>
            <h2>Chat Settings</h2>
            <p>Configure your chat preferences.</p>
        </div>

    </div>

    <div class="form-check form-switch">

        <input
            class="form-check-input"
            type="checkbox"
            name="show_online_status"
            id="onlineStatus"
            <?php if($data['show_online_status']) echo "checked"; ?>

        >

        <label
            class="form-check-label"
            for="onlineStatus">

            Show Online Status

        </label>

    </div>

    <div class="form-check form-switch">

        <input
            class="form-check-input"
            type="checkbox"
            name="show_last_seen"
            id="lastSeen"
            <?php if($data['show_last_seen']) echo "checked"; ?>

        >

        <label
            class="form-check-label"
            for="lastSeen">

            Show Last Seen

        </label>

    </div>

</div>



<div class="settings-card">

    <div class="card-title-area">

        <div class="title-icon">
            <i class='bx bx-palette'></i>
        </div>

        <div>

            <h2>Appearance</h2>

            <p>
                Customize your dashboard appearance.
            </p>

        </div>

    </div>

    <div class="mb-3">

        <label class="form-label">

            Theme

        </label>

        <select
            class="form-select"
            name="theme">

            <option
                value="light"
                <?php if($data['theme']=="light") echo "selected"; ?>
            >
                Light
            </option>

            <option
                value="dark"
                <?php if($data['theme']=="dark") echo "selected"; ?>
            >
                Dark
            </option>

            <option
                value="system"
                <?php if($data['theme']=="system") echo "selected"; ?>
            >
                System Default
            </option>

        </select>

    </div>

</div>

<div class="settings-card">

<div class="card-title-area">

<div class="title-icon">

<i class='bx bx-calendar'></i>

</div>

<div>

<h2>

Appointment Preferences

</h2>

<p>

Configure your mentoring sessions.

</p>

</div>

</div>


<div class="row">


<div class="col-md-4">

<label class="form-label">

Session Duration

</label>

<select
class="form-select"
name="session_duration">

<option
value="30"
<?php if($data['session_duration']==30) echo "selected"; ?>
>

30 Minutes

</option>

<option
value="60"
<?php if($data['session_duration']==60) echo "selected"; ?>
>

60 Minutes

</option>

<option
value="90"
<?php if($data['session_duration']==90) echo "selected"; ?>
>

90 Minutes

</option>

</select>

</div>


<div class="col-md-4">

<label class="form-label">

Break Time

</label>

<select
class="form-select"
name="break_time">

<option
value="0"
<?php if($data['break_time']==0) echo "selected"; ?>
>

0 Minutes

</option>

<option
value="15"
<?php if($data['break_time']==15) echo "selected"; ?>
>

15 Minutes

</option>

<option
value="30"
<?php if($data['break_time']==30) echo "selected"; ?>
>

30 Minutes

</option>

</select>

</div>


<div class="col-md-4">

<label class="form-label">

Maximum Daily Sessions

</label>

<input
type="number"
class="form-control"
name="max_daily_sessions"
value="<?php echo htmlspecialchars($data['max_daily_sessions']); ?>">

</div>

</div>

</div>



<div class="save-area">

<button
type="submit"
class="btn btn-success btn-lg">

<i class='bx bx-save'></i>

Save Changes

</button>
</div>

</form>
</div>

</section>

<?php include "Includes/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>