<?php

session_start();

include "Includes/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'mentor') {
    header("Location: login.php");
    exit();
}

$mentor_id = $_SESSION['user_id'];

// fetch appointments

$appointment_query = $conn->prepare(

"SELECT appointments.*,
users.usersName

FROM appointments

JOIN users
ON appointments.student_id = users.usersId

WHERE appointments.mentor_id=?

ORDER BY appointment_date ASC"

);

$appointment_query->bind_param(
"i",
$mentor_id
);

$appointment_query->execute();

$appointments =
$appointment_query->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="Includes/footer.css">
<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<link rel="stylesheet" href="mentor_appointments.css">

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<title>Mentor Appointments</title>

</head>

<body>

<section class="appointments-section">

<div class="appointments-container">

<h1>Student Appointments</h1>

<div class="appointments-grid">

<?php

if($appointments->num_rows > 0){

while($appointment =
$appointments->fetch_assoc()){

?>

<div class="appointment-card">

<h2>

<?php
echo $appointment['usersName'];
?>

</h2>

<p>

Date:
<?php
echo $appointment['appointment_date'];
?>

</p>

<p>

Time:
<?php
echo $appointment['appointment_time'];
?>

</p>

<p>

<?php
echo $appointment['message'];
?>

</p>

<span class="status-badge 

<?php

if($appointment['status'] == 'Approved'){

    echo 'approved';

}else if($appointment['status'] == 'Rejected'){

    echo 'rejected';

}else{

    echo 'pending';
}

?>

">

<?php
echo $appointment['status'];
?>

</span>

<div class="appointment-actions">

<form action="update_appointment.php"
method="POST">

<input type="hidden"
name="appointment_id"

value="<?php
echo $appointment['id'];
?>">

<button
type="submit"
name="status"
value="Approved"

class="approve-btn">

Approve

</button>

<button
type="submit"
name="status"
value="Rejected"

class="reject-btn">

Reject

</button>

</form>

</div>

</div>

<?php

}

}else{

echo "

<h3>
No appointments yet.
</h3>

";

}

?>

</div>

</div>

</section>

<?php include "Includes/footer.php"; ?>

<script>

document.querySelectorAll(".appointment-card")
.forEach(card => {

    card.addEventListener("mouseenter", () => {

        card.style.transform =
        "translateY(-5px)";

    });

    card.addEventListener("mouseleave", () => {

        card.style.transform =
        "translateY(0px)";

    });

});

</script>
</body>
</html>

