<?php
require "dbconn.php"; // Database connection file
session_start(); // Start session

if (isset($_POST['submit'])) {
    // Fetch data from the form
    $user = $_POST['user'];
    $room = $_POST['roomname'];
    $password = !empty($_POST['password']) ? $_POST['password'] : null; // Handle public room password
    $time = date('Y-m-d H:i:s', time());
    $max = $_POST['max_users'];
    // Store user and room information in session
    $_SESSION['user'] = $user;
    $_SESSION['room'] = $room;
    $_SESSION['status'] = "online";

    // Check if the room already exists in the database
    $sqlCheck = "SELECT * FROM user_data WHERE roomname = '$room'";
    $resultCheck = mysqli_query($conn, $sqlCheck);

    if (mysqli_num_rows($resultCheck) > 0) {
        echo "<script>alert('This room has been owned by someone.');</script>"; // Room already exists
    } else {
        // Insert room and user data into the database
        $sqlInsert = "INSERT INTO user_data (name, roomname, password, max_users, users_joined, disableChats, date) 
                      VALUES ('$user', '$room', '$password', '$max', 1, 0, '$time')";
        $sqlStatus = "INSERT INTO user_room_data (name, roomjoined, status, joining_date) 
                      VALUES ('$user', '$room', 'online', '$time')";

        // Execute queries
        if (mysqli_query($conn, $sqlInsert) && mysqli_query($conn, $sqlStatus)) {
            header("location: claim.php?room=$room&name=$user"); // Redirect to claim page
        } else {
            echo "Error: " . mysqli_error($conn); // Display error
        }
    }
}
?>

<?php
// Join Room Functionality
if (isset($_POST['joinSubmit'])) {
    require "dbconn.php";
    $room = $_POST['joinroomname'];
    $user = $_POST['joinuser'];
    $pass = !empty($_POST['joinpassword']) ? $_POST['joinpassword'] : null;
    $time = date('Y-m-d H:i:s', time());

    // Check the room's max users and current users joined
    $user_check = "SELECT max_users, users_joined FROM `user_data` WHERE roomname='$room'";
    $user_res = mysqli_query($conn, $user_check);
    $num = mysqli_fetch_assoc($user_res);

    if ($num && $num['users_joined'] < $num['max_users']) {
        // Check room and password match
        $sql = "SELECT * FROM user_data WHERE roomname = '$room' AND password = '$pass'";
        $res = mysqli_query($conn, $sql);

        if (mysqli_num_rows($res) == 1) {
            $_SESSION['status'] = "online";

            // Update users_joined in the room
            $sql_update = "UPDATE user_data SET users_joined = users_joined + 1 WHERE roomname = '$room'";

            if (mysqli_query($conn, $sql_update)) {
                // Insert into user_room_data
                $sqlStatus = "INSERT INTO user_room_data (name, roomjoined, status, joining_date) VALUES ('$user', '$room', 'online', '$time')";

                if (mysqli_query($conn, $sqlStatus)) {
                    header("location: claim.php?room=$room&name=$user"); // Redirect after joining room
                } else {
                    echo "Error while joining room.";
                }
            } else {
                echo "Error updating user count: " . mysqli_error($conn);
            }

        } else {
            echo "<script>alert('Incorrect room password.');</script>"; // Incorrect password
        }
    } else {
        echo "<script>alert('Room Full. Please join another room.');</script>"; // Room full
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create or Join Room</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url("https://images.pexels.com/photos/1097456/pexels-photo-1097456.jpeg");
            background-size: cover;
            background-attachment: fixed;
            color: #333;
            font-family: 'Arial', sans-serif;
        }

        .container {
            margin-top: 50px;
        }

        .card {
            border-radius: 1rem;
            background-color: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .form-control,
        .btn {
            border-radius: 0.5rem;
        }

        .btn-primary {
            background-color: #4a90e2;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #3a7bd5;
            transform: scale(1.05);
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: scale(1.05);
        }

        .form-check-input:checked {
            background-color: #4a90e2;
            border-color: #4a90e2;
        }

        .modal-content {
            border-radius: 1rem;
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }

        .modal-header {
            border-bottom: none;
        }

        .modal-footer {
            border-top: none;
        }

        h2,
        h5 {
            color: #4a90e2;
        }

        .animate__animated {
            animation-duration: 0.8s;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Create Room Card -->
                <div class="card shadow-sm animate__animated animate__fadeInDown">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Create a New Room</h2>
                        <form action="" method="post">
                            <div class="mb-3">
                                <label for="roomname" class="form-label">Room Name:</label>
                                <input type="text" class="form-control" name="roomname" id="roomname" required>
                            </div>
                            <div class="mb-3">
                                <label for="user" class="form-label">Your Name:</label>
                                <input type="text" class="form-control" name="user" id="user" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Room Type:</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                        id="publicRoom" value="Public" required>
                                    <label class="form-check-label" for="publicRoom">Public Room</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                        id="privateRoom" value="Private">
                                    <label class="form-check-label" for="privateRoom">Private Room</label>
                                </div>
                            </div>
                            <div class="mb-3" id="pass" hidden>
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" class="form-control" name="password" id="password">
                            </div>
                            <div class="mb-3">
                                <label for="usernum" class="form-label">Maximum Users:</label>
                                <input type="number" class="form-control" pattern="[0-9]" name="max_users"
                                    id="max_users" required>

                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100" name="submit">Create
                                Room</button>
                        </form>
                    </div>
                </div>

                <br>

                <!-- Join Room Button -->
                <button type="button" class="btn btn-secondary btn-lg w-100 animate__animated animate__fadeInUp"
                    data-bs-toggle="modal" data-bs-target="#joinRoomModal">Join Room</button>
            </div>
        </div>
    </div>

    <!-- Modal for Joining Room -->
    <div class="modal fade" id="joinRoomModal" tabindex="-1" aria-labelledby="joinRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="joinRoomModalLabel">Join a Room</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="" method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="joinroomname" class="form-label">Room Name:</label>
                            <input type="text" class="form-control" name="joinroomname" id="joinroomname" required>
                        </div>
                        <div class="mb-3">
                            <label for="joinuser" class="form-label">Your Name:</label>
                            <input type="text" class="form-control" name="joinuser" id="joinuser" required>
                        </div>
                        <div class="mb-3" id="joinpass">
                            <label for="joinpassword" class="form-label">Password:</label>
                            <input type="password" class="form-control" disabled name="joinpassword" id="joinpassword">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="joinSubmit">Join Room</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            // Show/hide password field based on room type selection
            $("#privateRoom").on("click", function () {
                $("#pass").removeClass("animate__fadeOutUp").addClass(
                    "animate__animated animate__fadeInDown").removeAttr("hidden");
                $("#password").attr("required", true);
            });
            $("#publicRoom").on("click", function () {
                $("#pass").removeClass("animate__fadeInDown").addClass(
                    "animate__animated animate__fadeOutUp");
                setTimeout(function () {
                    $("#pass").attr("hidden", true);
                }, 800);
                $("#password").removeAttr("required");
            });

            // Add animation to form inputs
            $("input").focus(function () {
                $(this).addClass("animate__animated animate__pulse");
            }).blur(function () {
                $(this).removeClass("animate__animated animate__pulse");
            });

            // Add animation to buttons
            $(".btn").hover(
                function () {
                    $(this).addClass("animate__animated animate__pulse");
                },
                function () {
                    $(this).removeClass("animate__animated animate__pulse");
                }
            );
            // Check room name availability on keyup
            $('#roomname').keyup(function (e) {
                var roomname = $('#roomname').val();
                //alert(roomname);
                $.ajax({
                    type: "post",
                    url: "roomType.php",
                    data: {
                        roomname: roomname
                    },
                    success: function (response) {
                        console.log(response)
                        if (response === 2) {
                            $('#roomname').addClass(".border border-3 border-success")
                                .removeClass(".border border-3 border-danger");
                        } else {
                            $('#roomname').removeClass(".border border-3 border-success")
                                .addClass(".border border-3 border-danger");
                        }
                    }
                });
            });
            $('#joinroomname').keyup(function (e) {
                var roomname = $('#joinroomname').val();
                //alert(roomname);
                $.ajax({
                    type: "post",
                    url: "roomType.php",
                    data: {
                        roomname: roomname
                    },
                    success: function (response) {
                        console.log(response)
                        if (response !== 2) {
                            $('#roomname').addClass(".border border-3 border-success")
                                .removeClass(".border border-3 border-danger");
                            if (response === 1) {
                                $("joinpassword").removeAttr("disabled");
                            } else {
                                $("joinpassword").attr('disabled', true);
                            }
                        } else {
                            $('#roomname').removeClass(".border border-3 border-success")
                                .addClass(".border border-3 border-danger");
                        }
                    }
                });
            });

        });
    </script>
</body>

</html>