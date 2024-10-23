<?php
include("dbconn.php");
$user = $_POST['user'];
$roomname = $_POST['roomname'];
$sql = "select * from `user_data` where roomname = '$roomname'";
$res = mysqli_query($conn,$sql);
$num = mysqli_num_rows($res);
if ($num == 1) {
    while($row= mysqli_fetch_assoc($res)){
        $disableChats = $row['disableChats'];
        $admin = $row['name'];
    }
    echo $disableChats;
    // if($user==$admin){
    //     echo 2;
    // }
} else {
    echo "there is some error";
}



?>