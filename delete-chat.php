<?php
    $id = $_POST['id'];
    $conn = mysqli_connect("localhost","root","","chatroom") or die("connection failed");
    $sql = "delete from `chats` where chat_id = '$id'";
    $result = mysqli_query($conn,$sql) or die("SQL Query Failed.");
    if ($result) {
        echo 1;
    } else {
        echo 0;
    }
    
    mysqli_close($conn);
?>