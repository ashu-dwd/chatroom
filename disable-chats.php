<?php
include("dbconn.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the action (room name) and user from the POST request
    $action = $_POST['action'];
    $user = $_POST['user'];

    // Ensure that action is not empty
    if (!empty($action)) {
        // First, get the current value of `disableChats` and admin's name
        $check_sql = "SELECT `disableChats`, `name` FROM `user_data` WHERE `roomname` = '$action'";
        $result = mysqli_query($conn, $check_sql);

        if ($result && mysqli_num_rows($result) > 0) {
            // Fetch the current value and admin's name
            $row = mysqli_fetch_assoc($result);
            $current_value = $row['disableChats'];
            $admin = $row['name'];

            // Check if the user is the admin
                // Toggle the value: if it's 1, set it to 0; if it's 0, set it to 1
                $new_value = ($current_value == '1') ? '0' : '1';

                // Prepare the SQL query to update the disableChats field
                $sql = "UPDATE `user_data` SET `disableChats` = '$new_value' WHERE `roomname` = '$action'";
                //echo $sql;
                // Execute the query
                $res = mysqli_query($conn, $sql);
                
                // Check if the query was successful
                if ($res) {
                    echo 1; // Success
                } else {
                    echo 0; // Update failed
                
            } 
        } else {
            echo 2; // Room not found
        }
    } else {
        echo 3; // Invalid input
    }
}
?>