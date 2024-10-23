<?php
require "dbconn.php";
$enteredRoom = $_POST['roomname'] ;
echo $enteredRoom;
print_r($_POST);
$sql = "SELECT * FROM `user_data` WHERE roomname = '$enteredRoom'";
$res = mysqli_query($conn, $sql);

if (mysqli_num_rows($res) > 0) {
    $row = mysqli_fetch_assoc($res);
    if (!empty($row['password'])) {
        echo 1;  // Private Room
    } else {
        echo 0;  // Public Room
    }
} else {
    echo 2;  // Room does not exist
}
?>