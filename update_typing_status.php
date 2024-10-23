<?php
include("dbconn.php");

// Fetch data from POST request
$user = $_POST['user'];
$room = $_POST['room'];
if ($_POST['is_typing']) {
    $is_typing = $_POST['is_typing']; 
$sql = "UPDATE `user_room_data` SET `is_typing` = '1' WHERE `name` = '$user' AND `roomjoined` = '$room'";
$res = mysqli_query($conn,$sql);
if ($res) {
    echo 1;
} else {
    echo 0;
}
} else {
    $sql = "UPDATE `user_room_data` SET `is_typing` = '0' WHERE `name` = '$user' AND `roomjoined` = '$room'";
$result = mysqli_query($conn,$sql);
if ($result) {
}
}
// Close the statement and connection
$conn->close();
?>