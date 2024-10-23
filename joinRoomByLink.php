<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Chat Room</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
    body {
        background-color: #f8f9fa;
    }

    .card {
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .card-title {
        color: #007bff;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }
    </style>
</head>

<body>
    <?php
    if (!isset($_GET['room'])) {
        echo '<div class="container mt-5"><div class="alert alert-danger">Error: Room name is missing.</div></div>';
        exit;
    }
    $room = htmlspecialchars($_GET['room']);
    ?>

    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Join Chat Room</h2>
                <form id="join-form">
                    <div class="mb-3">
                        <label for="staticRoom" class="form-label">Room Name:</label>
                        <input type="text" readonly class="form-control-plaintext" id="staticRoom"
                            value="<?php echo $room; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="user" class="form-label">Your Name:</label>
                        <input type="text" id="user" class="form-control" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" id="sub-btn" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Join Chat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function() {
        $("#join-form").on("submit", function(e) {
            e.preventDefault();
            let user = $("#user").val().trim();
            let room = $("#staticRoom").val();

            if (user === "") {
                alert("Please enter your name.");
                return;
            }

            $.ajax({
                type: "POST",
                url: "joinroom.php",
                data: {
                    roomname: room,
                    user: user
                },
                success: function(response) {
                    let currentUrl = new URL(window.location.href);
                    let gotoURL =
                        `${currentUrl.origin}/chatroom/claim.php?room=${encodeURIComponent(room)}&name=${encodeURIComponent(user)}`;
                    window.location.assign(gotoURL);
                },
                error: function() {
                    alert("An error occurred. Please try again.");
                }
            });
        });
    });
    </script>
</body>

</html>