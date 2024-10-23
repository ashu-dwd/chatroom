<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Room</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="claim.css">
</head>

<body>
    <section class="msger">
        <header class="msger-header">
            <div class="msger-header-title">
                <i class="fas fa-comment-alt"></i> Chat Room Name
            </div>
            <div id="user-status"></div>
            <div id="typingStatus"></div>
            <div class="msger-header-options">
                <button type="button" id="share" data-bs-toggle="modal" data-bs-target="#shareModal">
                    <img src="https://img.icons8.com/?size=30&id=fe3NTBhh8IKg&format=png&color=000000" alt="Share"
                        style="width:30px; height:30px;">
                </button>
                <span id="username"><i class="fas fa-user"></i> Username</span>
                <a href="#" id="logout">LogOut</a>
            </div>
        </header>

        <main class="msger-chat" id="chat">
            <!-- Chat messages will be dynamically inserted here -->
             pata hai pura server shud down ho gya aur phir sahi se delete nhi hua aur jab kiya to pata chal ki database ki sari table delete ho gai hai
        </main>

        <form class="msger-inputarea" id="dataForm">
            <input type="text" class="msger-input" placeholder="Enter your message..." id="chat_msg">
            <button type="submit" id="btn" class="msger-send-btn">Send</button>
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
    <!-- Your custom JavaScript file -->
    <script src="chat.js"></script>
</body>

</html>