<?php
// logout.php

session_start();

// Destroy the session
session_unset();
session_destroy();

// Optionally, update the user's status in the database to "offline"
require 'dbconn.php';
$user = $_GET['user'];
$updateStatus = "UPDATE user_room_data SET status = 'offline' WHERE name = '$user'";
mysqli_query($conn, $updateStatus);

// Return success response
echo json_encode(['status' => 'logged_out']);
?>