<?php

$search_value = $_POST['search'];
$conn = mysqli_connect("localhost","root","","chatroom") or die("connection failed");
$sql = "select * from `user_data` where name like '%{$search_value}%'";
$result = mysqli_query($conn,$sql) or die("SQL Query Failed.");
if(mysqli_num_rows($result)>0){
  $output = '<table border = "1" width="100%" cellspacing = "10px">
  <tr>
  
  </tr>';
  while($row = mysqli_fetch_assoc($result)){
      $output .= "<tr><td>{$row['roomname']}</td></tr>";
  }
  $output .= "</table>";
  mysqli_close($conn);
  echo $output;
}
else{
   echo"<h2>no record found</h2>";

}
?>