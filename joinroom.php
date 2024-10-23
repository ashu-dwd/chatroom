<?php

    require "dbconn.php";
    $room= $_POST['roomname'];
    $user= $_POST['user'];
    $pass= $_POST['joinpassword'];
    date_default_timezone_set('Asia/Kolkata');
    $joiningtime = date('Y-m-d H:i:s');
    //print_r($_POST);
    $sql = "select * from `user_data` where roomname = $room and password = $pass";
    $res =mysqli_query($conn,$sql);
    if (mysqli_num_rows($res == 1)) {
        session_start();
        $_SESSION['status']= "online";
        $sql_status = "INSERT INTO `user_room_data` (`id`, `name`, `roomjoined`, `status`, `joining_date`) VALUES (NULL, '$user', '$room', 'online', '$joiningtime')";
        $result = mysqli_query($conn,$sql_status);
       if($result){
        header("location: claim.php?room={$room}&&name={$user}");
       }else{
        echo "error";
       }
    } else {
        echo '<script>alert("You have entered wrong Password</script>';
    }


?>