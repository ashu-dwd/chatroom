<?php
   require "dbconn.php";
   $sql = "select * from `user_data`";
   $result = mysqli_query($conn,$sql);
   if (mysqli_num_rows($result)>0) {
    while ($row= mysqli_fetch_assoc($result)) {
        echo '<li>
                    <a href="#">
                      <img class="contacts-list-img" src="https://bootdey.com/img/Content/user_1.jpg">
        
                      <div class="contacts-list-info">
                            <span class="contacts-list-name">'
                              .$row["roomname"].
                              '<small class="contacts-list-date pull-right">2/28/2015</small>
                            </span>
                      </div>
                      <!-- /.contacts-list-info -->
                    </a>
                  </li>';
    }
   } else {
    echo "no rooms found";
   }
   


?>