<?php
 if (! isset($_GET['name'],$_GET['room'])) {
      header("location: index.php");
}else{
  $user = htmlspecialchars($_GET['name']);
  $room = htmlspecialchars($_GET['room']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Full Page Chatbox</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
    /* Add this CSS for alignment */
    #chat .me {
        text-align: right;
    }

    #chat .you {
        text-align: left;
    }

    /* #dataForm {
        background-color: black;
        color: white;
    }

    .container {
        background-color: black;
        color: white;
    } */
    </style>
</head>

<body>
    <div id="container">
        <aside>
            <header>
                <input type="text" placeholder="search" id="search">
            </header>
            <div class="table-data">

            </div>
            <ul id="roombox">
                <!-- Sidebar content -->
            </ul>
        </aside>
        <main>
            <header>
                <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/1940306/chat_avatar_01.jpg" alt="Chat Avatar">
                <div>
                    <h2 id="roomname"><?php echo $room; ?></h2>
                    <h3 id="username">Your Name is: <?php echo $user; ?></h3>
                </div>
                <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/1940306/ico_star.png" alt="Star Icon">
            </header>
            <div id="content">
                <ul id="chat"></ul>
            </div>
            <footer>
                <form id="dataForm">
                    <div class="container">
                        <textarea id="chat_msg" placeholder="Type your message"></textarea>
                        <a href="#" id="btn">Send</a>
                    </div>
                </form>
            </footer>
        </main>
        <div id="error-msg"></div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script>
    $(document).ready(function() {
        let roomname = $("#roomname").text();
        let user = $("#username").text().split(":")[1].trim();

        function scrollToBottom() {
            let chatContainer = document.getElementById("chat");
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }


        // Function to load chats
        function loadChats() {
            $.ajax({
                url: 'chat-load.php',
                type: "POST",
                data: {
                    roomname: roomname,
                    user: user
                },
                success: function(data) {
                    $("#chat").html(data);
                    scrollToBottom(); // Scroll to the bottom after loading chats
                },
                error: function(xhr, status, error) {
                    console.error("An error occurred while loading chats: " + error);
                }
            });
        }

        // Load chats immediately and set an interval to refresh
        loadChats();
        setInterval(loadChats, 500);

        // Button click event handler
        $("#btn").on("click", function(e) {
            e.preventDefault();
            let name = $("#username").text().split(":")[1].trim(); // Extract only the user's name
            let chat_msg = $("#chat_msg").val();
            let roomname = $("#roomname").text();

            if (chat_msg.trim() !== "") {
                $.ajax({
                    url: "home_action.php",
                    type: "POST",
                    data: {
                        name: name,
                        chatmsg: chat_msg,
                        roomname: roomname
                    },
                    success: function(data) {
                        if (data == "1") {
                            loadChats();
                            $("#dataForm").trigger("reset");
                        } else {
                            $("#error-msg").html("Can't upload chats").slideDown().fadeOut(
                                3000);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("An error occurred: " + error);
                    }
                });
            }
        });

        // Load rooms every 500ms
        function loadRooms() {
            $.ajax({
                url: "load-rooms.php",
                type: "POST",
                success: function(data) {
                    $("#roombox").html(data);
                },
                error: function(xhr, status, error) {
                    console.error("An error occurred: " + error);
                }
            });
        }

        loadRooms();
        setInterval(loadRooms, 500);
        //live search
        $("#search").on("keyup", function() {
            let search_term = $(this).val();
            $.ajax({
                url: "live-search.php",
                type: "POST",
                data: {
                    search: search_term
                },
                success: function(data) {
                    $("#table-data").html(data);
                },
            });
        });
    });
    </script>
</body>

</html>