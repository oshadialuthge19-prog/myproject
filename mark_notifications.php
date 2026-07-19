<?php

session_start();

include "Includes/db.php";

$user_id = $_SESSION['user_id'];

$update = $conn->prepare(

"UPDATE system_notifications

SET is_read = 1

WHERE user_id = ?"

);

$update->bind_param(
    "i",
    $user_id
);

$update->execute();

/* Return to previous page if possible */
if (isset($_SERVER['HTTP_REFERER'])) {

    header("Location: " . $_SERVER['HTTP_REFERER']);

} else {

    header("Location: student_dashbord.php");

}

exit();

?>