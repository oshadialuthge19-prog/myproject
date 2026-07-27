<?php
session_start();
include "Includes/db.php";

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "User not logged in."
    ]);
    exit;
}

$sender_id = $_SESSION['user_id'];

$receiver_id = intval($_POST['receiver_id'] ?? 0);
$message = trim($_POST['message'] ?? "");

if ($receiver_id == 0 || $message == "") {
    echo json_encode([
        "success" => false,
        "message" => "Missing data."
    ]);
    exit;
}

$stmt = $conn->prepare("
    INSERT INTO messages
    (sender_id, receiver_id, message)
    VALUES (?, ?, ?)
");

$stmt->bind_param(
    "iis",
    $sender_id,
    $receiver_id,
    $message
);

if ($stmt->execute()) {

    echo json_encode([
        "success" => true
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => $conn->error
    ]);

}