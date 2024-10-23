<?php
include("dbconn.php");

$room = $_POST['roomname'];
$sql = "SELECT * FROM `user_room_data` WHERE roomjoined = '$room' AND status = 'online'";
$res = mysqli_query($conn, $sql);

if (mysqli_num_rows($res) > 0) {
    $users = []; // Initialize an array to hold user strings

    while ($row = mysqli_fetch_assoc($res)) {
        $user = htmlspecialchars($row['name']); // Sanitize user input
        $str = "<span data-id='" . $row['id'] . "'>" . $user . " </span>";
        $users[] = $str; // Add each user span to the array
    }
    
    // Join all user spans and create output string
    $output = implode(", ", $users). " are online" ; // Use 'are' for plural

    echo $output; // Output the result
} else {
    echo "No users joined room";
}
?>