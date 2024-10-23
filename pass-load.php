<?php
require "dbconn.php"; // Include the database connection file
// Start the session and store user and room in session variables


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch data from POST request
    $user = $_POST['user'];
    $room = $_POST['roomname'];
    $password = $_POST['password'];

    session_start();
    $_SESSION['user'] = $user;
    $_SESSION['room'] = $room;
    // Handle empty password for public rooms
    if (empty($password)) {
        $password = NULL;  // Set password as NULL for public rooms
    }

    // Query to check if the room already exists in the database
    $sqlCheck = "SELECT * FROM user_data WHERE roomname = '$room'";
    $resultCheck = mysqli_query($conn, $sqlCheck);

    if (mysqli_num_rows($resultCheck) > 0) {
        // Room already exists, return "0"
        echo "0";
    } else {
        // Insert new room data into the database
        $sqlInsert = "INSERT INTO user_data (name, roomname, password) VALUES ('$user', '$room', '$password')";
        if (mysqli_query($conn, $sqlInsert)) {
            // Redirect to home page after successful insertion
            header("location: home.php");
        } else {
            // Show error message if query fails
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>