<?php
// Database connection
require "dbconn.php";

if (isset($_FILES['file'])) {
    $name= $_POST['name'];
        $room = $_POST['roomname'];
        date_default_timezone_set('Asia/Kolkata');
        $msg_time = date('Y-m-d h:i:s'); 
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    // Define allowed file types
    $allowed = array('jpg', 'jpeg', 'png', 'pdf');

    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            if ($fileSize < 20000000) { // Limit file size to 20MB
                $fileNewName = uniqid('', true) . "." . $fileActualExt;
                $fileDestination = 'user-uploads/' . $fileNewName;

                // Move the uploaded file to the server's upload directory
                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    // Insert file details into the database
                    $sql = "INSERT INTO `chats` (`chat_id`, `chat`,`file`, `msg_by`, `roomname`, `msg time`) VALUES (NULL,NULL,'$fileNewName', '$name', '$room', '$msg_time')";
                    if ($conn->query($sql) === TRUE) {
                        echo "File uploaded and saved successfully!";
                    } else {
                        echo "Error: " . $conn->error;
                    }
                } else {
                    echo "There was an error uploading your file.";
                }
            } else {
                echo "Your file is too big!";
            }
        } else {
            echo "There was an error uploading your file.";
        }
    } else {
        echo "You cannot upload files of this type!";
    }
}

$conn->close();
?>