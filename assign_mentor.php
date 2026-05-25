<?php

session_start();

include "Includes/db.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $student_id = $_SESSION['user_id'];

    $mentor_id = $_POST['mentor_id'];

    // check if student already selected mentor

    $check = $conn->prepare(
    "SELECT * FROM mentor_assignments
    WHERE student_id=?"
    );

    $check->bind_param("i", $student_id);

    $check->execute();

    $result = $check->get_result();

    if($result->num_rows > 0){

        echo "You already selected a mentor.";

        exit();
    }

    // insert mentor assignment

    $stmt = $conn->prepare(
    "INSERT INTO mentor_assignments
    (student_id, mentor_id)
    VALUES (?, ?)"
    );

    $stmt->bind_param(
    "ii",
    $student_id,
    $mentor_id
    );

    if($stmt->execute()){

        header("Location: student_dashbord.php");
        exit();

    }else{

        echo "Something went wrong.";
    }
}
?>