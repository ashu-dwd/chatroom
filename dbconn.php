<?php
   $host = "localhost";
   $userdb = "root";
   $pass = "";
   $db = "chatroom";
   $conn =  new mysqli($host,$userdb,$pass,$db);
   if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  } else {
      //echo "Database connected successfully";
  }
   


?>