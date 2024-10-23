<?php
session_start();
// Check if 'name' is not set or empty, and proceed accordingly

if (!isset($_SESSION['status'])) {
    header("location: index.php");
}
$user = $_GET['name'];
$room = $_GET['room'];

// Function to generate the modakfl for entering the user's name


// Function to generate the chat page
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ($room) ?> - Chat Room</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="claim.css">

</head>

<body>
    <section class="msger">
        <header class="msger-header">
            <div class="msger-header-title">
                <i class="fas fa-comment-alt"></i> <?php echo ($room) ?> <span id="username"><i class="fas fa-user">
                        <?php echo ($_SESSION['status']) ?> </i>
                    <?php
                    include("dbconn.php");
                    $sql = "SELECT * FROM `user_data` WHERE roomname = '$room'";
                    $res = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($res)) {
                        $admin = $row['name'];
                        echo $admin . "<br>";
                    }
                    ?>
                </span>
            </div>
            <div>
                <span id="user-status"><i class="fas fa-users"></i></span>
                <span id="typingStatus"></span>
            </div>

            <div class="msger-header-options">
                <button type="button" id="share" class="btn btn-light btn-sm" data-bs-toggle="modal"
                    data-bs-target="#shareModal">
                    <i class="fas fa-share-alt"></i>
                </button>
                <a href="logout.php?user=<?php echo ($user) ?>&&room=<?php echo ($room) ?>" id="logout"
                    class="btn btn-danger btn-sm">LogOut</a>
                <a href="#" id="disable-chats" class="btn btn-danger btn-sm">Disable Chats</a>
            </div>
        </header>

        <main class="msger-chat" id="chat">
            <div id="scrollBottom"></div>

        </main>

        <form class="msger-inputarea" action="home_action.php" id="dataForm" enctype="multipart/form-data">
            <input type="text" class="msger-input text-dark" placeholder="Type your message..." id="chat_msg"
                autocomplete="off">
            <button type="submit" id="submit-btn" class="msger-send-btn"><i class="fas fa-paper-plane"></i></button>
        </form>
    </section>



    <!-- Modal for sharing the chat room link -->
    <div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shareModalLabel">Share This Chat Room</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="shareLink"></p>
                </div>
                <div class="modal-footer">
                    <button id="copyLinkButton" class="btn btn-primary">Copy Link</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/10.32.0/js/jquery.fileupload.min.js">
    </script>
    <script>
        // chat-notifications.js

        $(document).ready(function () {
            let user = "<?php echo ($user) ?>";
            let roomname = "<?php echo ($room) ?>";
            let lastMessage = ''; // Store the last message to track changes

            function toggleMenu() {
                var menu = document.getElementById("menu");
                menu.classList.toggle("active");
            }

            // Disabling chats
            $("#disable-chats").click(function (e) {
                e.preventDefault();
                var admin = $('#username').text().trim();
                var currentText = $(this).text();

                if (user !== admin) {
                    alert("You can't disable chats. Contact the admin: " + admin);
                } else {
                    if (currentText === "Enable Chats") {
                        $(this).text("Disable Chats");
                    } else {
                        $(this).text("Enable Chats");
                    }

                    $.ajax({
                        type: "POST",
                        url: "disable-chats.php",
                        data: {
                            action: roomname,
                            user: admin
                        },
                        success: function (response) {
                            if (response == 1) {
                                console.log("Chat status updated successfully.");
                            } else {
                                console.log("Failed to update chat status.");
                            }
                        }
                    });
                }
            });

            function loadActions() {
                $.ajax({
                    url: "load-room-actions.php",
                    type: "POST",
                    data: {
                        roomname: roomname,
                        user: user
                    },
                    success: function (response) {
                        if (response == 1) {
                            $("#chat_msg").attr("disabled", true);
                            $("#btn").attr("disabled", true);
                            $("#chat_msg").attr("placeholder", "Chat is Disabled by Admin");
                        } else if (response == 0) {
                            $("#chat_msg").attr("disabled", false);
                            $("#btn").attr("disabled", false);
                            $("#chat_msg").attr("placeholder", "Type your message...");
                        }
                    }
                });
            }

            setInterval(loadActions, 1000);

            function scrollToBottom() {
                let chatContainer = document.getElementById("chat");
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }

            function loadChats() {
                $.ajax({
                    url: "chat-load.php",
                    type: "POST",
                    data: {
                        roomname: roomname,
                        user: user
                    },
                    success: function (data) {
                        $("#chat").html(data);
                        scrollToBottom();

                        let newMessage = $("#chat").find("div.msg:last").text().trim();
                        if (newMessage !== lastMessage) {
                            lastMessage = newMessage;
                            sendNotification("New Message", "You have received a new message in " +
                                lastMessage);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("An error occurred while loading chats: " + error);
                    }
                });
            }

            loadChats();
            setInterval(loadChats, 3000); // Refresh every 3 seconds

            $("#submit-btn").on("click", function (e) {
                e.preventDefault();
                let chat_msg = $("#chat_msg").val().trim();
                if (chat_msg !== "") {
                    $.ajax({
                        url: "home_action.php",
                        type: "POST",
                        data: {
                            name: user,
                            chatmsg: chat_msg,
                            roomname: roomname
                        },
                        success: function (data) {
                            loadChats();
                            $("#dataForm").trigger("reset");
                            console.log(data);
                        },
                    });
                }
            });

            $(document).on("dblclick", ".msg.right-msg", function () {
                let chatId = $(this).data("chat_id");
                $.ajax({
                    url: "delete-chat.php",
                    type: "POST",
                    data: {
                        id: chatId
                    },
                    success: function (data) {
                        console.log(data);
                        if (data == 1) {
                            $(this).closest("div.msg").fadeOut(1000);
                            $("#msg")
                                .html("Deleted successfully")
                                .slideDown()
                                .fadeOut(2000);
                        } else {
                            $("#error-msg")
                                .html("Can't delete record")
                                .slideDown()
                                .fadeOut(3000);
                        }
                    }.bind(this),
                });
            });

            function shareLink() {
                let currentUrl = new URL(window.location.href);
                currentUrl.searchParams.delete("name");
                $("#shareLink").text(currentUrl.toString());
            }

            $("#share").on("click", shareLink);

            $("#copyLinkButton").on("click", function () {
                const linkToCopy = $("#shareLink").text();
                navigator.clipboard.writeText(linkToCopy).then(() => {
                    const originalText = $(this).text();
                    $(this).text("Link copied!");
                    setTimeout(() => $(this).text(originalText), 2000);
                }).catch(err => {
                    console.error("Could not copy text: ", err);
                });
            });

            function loadStatus() {
                $.ajax({
                    url: "user-status.php",
                    type: "POST",
                    data: {
                        roomname: roomname
                    },
                    success: function (data) {
                        $("#user-status").html(data);
                    },
                });
            }

            loadStatus();
            setInterval(loadStatus, 100);

            let typingTimer;
            let typingInterval = 100;
            $("#chat_msg").on("keypress", function () {
                clearTimeout(typingTimer);
                updateTypingStatus(1);

                typingTimer = setTimeout(function () {
                    updateTypingStatus(0);
                }, typingInterval);
            });

            function updateTypingStatus(isTyping) {
                if (typeof user !== "undefined" && typeof roomname !== "undefined") {
                    $.ajax({
                        type: "POST",
                        url: "update_typing_status.php",
                        data: {
                            user: user,
                            room: roomname,
                            is_typing: isTyping
                        },
                        success: function (response) {
                            console.log("Typing status updated:", response);
                        },
                        error: function (xhr, status, error) {
                            console.error("An error occurred while updating typing status:", error);
                        }
                    });
                } else {
                    console.error("User or room name is not defined.");
                }
            }

            function checkTypingStatus() {
                $.ajax({
                    type: "POST",
                    url: "get_typing_status.php",
                    data: {
                        room: roomname
                    },
                    success: function (response) {
                        $("#typingStatus").html(response);
                    }
                });
            }

            setInterval(checkTypingStatus, 500);

            // Browser Notification API
            function sendNotification(title, message) {
                if (Notification.permission === "granted") {
                    let notification = new Notification(title, {
                        body: message,
                        icon: "chat-icon.png"
                    });
                } else if (Notification.permission !== "denied") {
                    Notification.requestPermission().then(permission => {
                        if (permission === "granted") {
                            let notification = new Notification(title, {
                                body: message,
                                icon: "chat-icon.png"
                            });
                        }
                    });
                }
            }

            if (Notification.permission !== "granted") {
                Notification.requestPermission();
            }
        });
    </script>
    <script>
        // Time (in milliseconds) before logging out the user
        const timeoutDuration = 1 * 60 * 1000; // 5 minutes

        // Function to handle automatic logout
        function autoLogout() {
            // Send AJAX request to log out the user
            $.ajax({
                url: 'logout.php', // Endpoint that handles the logout
                method: 'GET',
                success: function (response) {
                    // Redirect the user to the login page or show a message
                    alert('You have been logged out due to inactivity.');
                    window.location.href = 'index.php'; // Redirect to login page
                }
            });
        }

        let inactivityTimeout;

        // Reset inactivity timer on any user interaction
        function resetInactivityTimeout() {
            clearTimeout(inactivityTimeout); // Clear any existing timeout
            inactivityTimeout = setTimeout(autoLogout, timeoutDuration); // Set a new timeout
        }

        // Listen for user activity: mouse movement, key presses, etc.
        window.onload = resetInactivityTimeout;
        document.onmousemove = resetInactivityTimeout;
        document.onkeypress = resetInactivityTimeout;
        document.onclick = resetInactivityTimeout;
        document.onscroll = resetInactivityTimeout;

        // Detect when the user switches tabs (optional)
        document.addEventListener('visibilitychange', function () {
            if (document.hidden) {
                resetInactivityTimeout(); // Reset the inactivity timer when tab is hidden
            }
        });
    </script>

</body>

</html>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>