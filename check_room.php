<?php
require "dbconn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $roomName = $_POST["roomName"];

    // Check if the room already exists in the database
    $sqlCheck = "SELECT * FROM user_data WHERE roomname = '$roomName'";
    $resultCheck = mysqli_query($conn, $sqlCheck);

    if (mysqli_num_rows($resultCheck) > 0) {
        echo "0"; // Room already exists
    } else {
        echo "1"; // Room does not exist
    }
}
?>