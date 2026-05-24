<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myweb_db"; // 👈 change to your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>