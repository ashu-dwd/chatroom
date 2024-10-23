<?php
include("dbconn.php");

$room = $_POST['room'];

$sql = "SELECT `name` FROM `user_room_data` WHERE `roomjoined` = '$room' AND `is_typing` = 1";
$res = mysqli_query($conn, $sql);

$typingUsers = [];

if (mysqli_num_rows($res) > 0) {
    while ($row = mysqli_fetch_assoc($res)) {
        $typingUsers[] = $row['name'];
    }
}

if (!empty($typingUsers)) {
    echo implode(', ', $typingUsers) . " is typing...";
} else {
    echo "";
}
?>