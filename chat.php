<?php

session_start();
include "Includes/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];

$user_id = $_SESSION['user_id'];

if ($role == "student") {

    $users = $conn->query(

        "SELECT usersId, usersName

         FROM users

         WHERE role='mentor'

         ORDER BY usersName"

    );

} else {

    $users = $conn->query(

        "SELECT usersId, usersName

         FROM users

         WHERE role='student'

         ORDER BY usersName"

    );

}



?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Chat</title>
<link rel="stylesheet" href="Includes/student_header.css">
<link rel="stylesheet" href="Includes/mentor_header.css">

<link rel="stylesheet" href="chat.css">

<link rel="stylesheet" href="Includes/footer.css">

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>

<body>
    <?php
    if ($role == "student") {
    include "Includes/student_header.php";
} else {
    include "Includes/mentor_header.php";
}
?>

<section class="chat-hero">

    <div class="chat-hero-content">

        <span class="chat-page-label">
            Communication
        </span>

        <h1>
            Mentor & Student Chat
        </h1>

        <p>
            Stay connected with your mentor or students through real-time conversations.
        </p>

    </div>

    <div class="chat-hero-icon">

        <i class='bx bx-message-rounded-dots'></i>

    </div>

</section>

<div class="chat-container">

    <!-- LEFT SIDE -->

    <div class="chat-sidebar">

        <div class="chat-sidebar-header">

            <h2>Conversations</h2>

        </div>

        <div id="conversation-list">

<?php while($user = $users->fetch_assoc()) { ?>

<div class="conversation-card"
     onclick="openChat(
        <?= $user['usersId']; ?>,
        '<?= htmlspecialchars($user['usersName'], ENT_QUOTES); ?>'
     )">

    <div class="conversation-avatar">
        <i class='bx bx-user'></i>
    </div>

    <div class="conversation-info">

        <h4><?= htmlspecialchars($user['usersName']); ?></h4>

        <small>Click to chat</small>

    </div>

</div>

<?php } ?>

</div>

    </div>

    <!-- RIGHT SIDE -->

    <div class="chat-window">

        <div class="chat-header">

            <h2>Select a conversation</h2>

        </div>

        <div id="chat-messages">

            <div class="empty-chat">

                <i class='bx bx-message-rounded-dots'></i>

                <h3>No Conversation Selected</h3>

                <p>Select a mentor or student to start chatting.</p>

            </div>

        </div>

        <div class="chat-input">

            <input
                type="text"
                placeholder="Type a message..."
                disabled
            >

            <button onclick="sendMessage()" disabled>

                <i class='bx bx-send'></i>

            </button>

        </div>

    </div>

</div>

<?php include "Includes/footer.php"; ?>

<script>

const socket = new WebSocket("ws://localhost:8080");

socket.onopen = function(){

    console.log("✅ Connected");

    socket.send(JSON.stringify({

        type:"register",

        user_id: <?= $_SESSION['user_id']; ?>

    }));

};

socket.onmessage = function(event){

    const data = JSON.parse(event.data);

    console.log("Received:", data);

    if(data.type === "message"){

        loadMessages();

    }

};

socket.onclose = function(){

    console.log("Disconnected");

};

let currentReceiver = null;

function openChat(userId, userName) {

    currentReceiver = userId;

    document.querySelector(".chat-header h2").innerText = userName;

    document.querySelector(".chat-input input").disabled = false;
    document.querySelector(".chat-input button").disabled = false;

    loadMessages();
}

function loadMessages() {

    if (!currentReceiver) return;

    fetch("fetch_msg.php?user_id=" + currentReceiver)
        .then(response => response.text())
        .then(data => {

            document.getElementById("chat-messages").innerHTML = data;

            document.getElementById("chat-messages").scrollTop =
                document.getElementById("chat-messages").scrollHeight;

        });

}

function sendMessage(){

    if(!currentReceiver) return;

    const input = document.querySelector(".chat-input input");

    const message = input.value.trim();

    if(message === "") return;

    fetch("send_msg.php",{

        method:"POST",

        headers:{
            "Content-Type":"application/x-www-form-urlencoded"
        },

        body:
            "receiver_id="+encodeURIComponent(currentReceiver)+
            "&message="+encodeURIComponent(message)

    })
    .then(res=>res.json())
    .then(response=>{

        if(response.success){

            socket.send(JSON.stringify({

                type:"message",

                sender_id: <?= $_SESSION['user_id']; ?>,

                receiver_id: currentReceiver,

                message: message

            }));

            input.value="";

            loadMessages();

        }else{

            alert(response.message);

        }

    });

}

document.querySelector(".chat-input input").addEventListener("keypress",function(e){

    if(e.key==="Enter"){

        sendMessage();

    }

});

</script>

</body>
</html>