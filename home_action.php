<?php
require "dbconn.php";
session_start();
print_r($_POST);

// Sanitize and assign variables
$msg = mysqli_real_escape_string($conn, htmlspecialchars($_POST['chatmsg']));
$name = mysqli_real_escape_string($conn, htmlspecialchars($_POST['name']));
$room = mysqli_real_escape_string($conn, htmlspecialchars($_POST['roomname']));

date_default_timezone_set('Asia/Kolkata');
$msg_time = date('Y-m-d H:i:s');


// Construct the SQL query based on whether a file was uploaded
$sql = "INSERT INTO `chats` (`chat`, `msg_by`, `roomname`, `msg time`) 
        VALUES ('$msg', '$name', '$room', '$msg_time')";

// Execute the SQL query
$result = mysqli_query($conn, $sql);

if ($result) {
    echo 1; // Success
} else {
    echo 0; // Failure
}

// Close the database connection
mysqli_close($conn);
?>