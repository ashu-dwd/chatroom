<?php
$roomname = $_POST['roomname'];
$user = $_POST['user'];
session_start();

// Establish the database connection
$conn = new mysqli("localhost", "root", "", "chatroom");

// Check if the connection was successful
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Query to get chats for the specific room, ordered by time
$sql = "SELECT * FROM chats WHERE roomname = '$roomname' ORDER BY `msg time`";
$result = mysqli_query($conn, $sql);

// Check if there are any results
if ($result && mysqli_num_rows($result) > 0) {
    // Fetch all the rows from the result
    while ($row = mysqli_fetch_assoc($result)) {
        $msgBy = $row['msg_by'];
        $chat = $row['chat'];
        $time = $row['msg time']; // Ensure column name is correct
        // Determine if the message is from the current user or another user
        $messageClass = ($msgBy == $user) ? 'msg right-msg' : 'msg left-msg';
        date_default_timezone_set('Asia/Kolkata');
        $current_time = date('Y-m-d h:i:s'); 
        // Output the message with the appropriate class
        echo '<div class="'.$messageClass.'" data-chat_id="'.$row['chat_id'].'">
                <div class="msg-img" style="background-image: url(https://img.icons8.com/?size=100&id=21441&format=png&color=000000)"></div>
                <div class="msg-bubble">
                    <div class="msg-info">
                        <div class="msg-info-name">'.$msgBy.'</div>
                        <div class="msg-info-time">'.$time.' </div>
                    </div>
                    <div class="msg-text">'.$chat.'</div>
                </div>
              </div>';
    }
} else {
    // Handle case where no messages are found or query fails
    if (!$result) {
        echo "Error: " . $conn->error; // Display the query error message
    } else {
        echo "No chats found for this room.";
    }
}

// Close the database connection
$conn->close();
?>