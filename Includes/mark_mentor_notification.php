<?php

session_start();

include "Includes/db.php";

$mentor_id =
$_SESSION['user_id'];

$update = $conn->prepare(

"UPDATE mentor_notifications

SET is_read=1

WHERE mentor_id=?"

);

$update->bind_param(
"i",
$mentor_id
);

$update->execute();

header(
"Location: mentor_dashbord.php"
);

?>