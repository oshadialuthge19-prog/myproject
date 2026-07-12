<?php

session_start();

include "Includes/db.php";

$student_id =
$_SESSION['user_id'];

$update = $conn->prepare(

"UPDATE notifications

SET is_read=1

WHERE student_id=?"

);

$update->bind_param(
"i",
$student_id
);

$update->execute();

header(
"Location: student_dashbord.php"
);

exit();

?>