<?php
session_start();
include "Includes/db.php";

if (!isset($_SESSION['user_id'])) {
    exit("Not logged in.");
}

$currentUser = $_SESSION['user_id'];
$otherUser = intval($_GET['user_id'] ?? 0);

if ($otherUser == 0) {
    exit();
}

// Mark received messages as read
$update = $conn->prepare("
    UPDATE messages
    SET is_read = 1
    WHERE sender_id = ?
      AND receiver_id = ?
");
$update->bind_param("ii", $otherUser, $currentUser);
$update->execute();

// Load conversation
$stmt = $conn->prepare("
SELECT *
FROM messages
WHERE
(sender_id = ? AND receiver_id = ?)
OR
(sender_id = ? AND receiver_id = ?)
ORDER BY created_at ASC
");

$stmt->bind_param(
    "iiii",
    $currentUser,
    $otherUser,
    $otherUser,
    $currentUser
);

$stmt->execute();

$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {

    $class = ($row['sender_id'] == $currentUser)
        ? "sent"
        : "received";

    echo "
    <div class='message $class'>
        <div class='message-text'>
            ".htmlspecialchars($row['message'])."
        </div>
        <small>".date("h:i A", strtotime($row['created_at']))."</small>
    </div>";
}
?>