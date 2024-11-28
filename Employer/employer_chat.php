<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
    <title>FlexMatch - Employer Chat</title>
    <link rel="stylesheet" href="../css/chat.css"> <!-- Keep your existing CSS link if needed -->
    <style>
        /* General styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: #F0FDFF;
        }

        .sidebar {
            position: fixed;
            width: 180px;
            height: 100%;
            background: #AAE1DE;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 20px;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 30px;
            margin-top: -10px;
            position: relative;
        }

        .logo-image {
            width: 70px;
            height: auto;
        }

        .logo-text {
            font-weight: bold;
            font-size: 20px;
            color: #FFFFFF;
            margin-top: 0px;
        }

        .menu {
            width: 100%;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 15px;
            cursor: pointer;
            color: #FFFFFF;
            font-size: 14px;
            font-family: 'Cabin', sans-serif;
            font-weight: 400;
            gap: 18px;
            border-radius: 8px; 
        }

        .menu-icon {
            width: 24px;
            height: 24px;
        }

        .menu-item:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
            cursor: pointer;
        }

        .main-content {
            margin-left: 180px;
            width: calc(100% - 180px);
        }

        .header {
            background: #FFFFFF;
            padding: 10px 20px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            border-bottom: 1px solid #DBDDE0;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .notification-icon {
            width: 32px;
            height: 32px;
            cursor: pointer;
        }

        .profile-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 0.1px solid #000000;
            cursor: pointer;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        .user-name {
            font-weight: 500;
            font-size: 16px;
            line-height: 20px;
            color: #000000;
        }

        .user-role {
            font-size: 14px;
            color: #A8AAB0;
        }

        .content {
            padding: 20px;
            text-align: center;
            display: flex;
            flex-direction: column;
            gap: 20px;
            align-items: center; 
            justify-content: flex-start; 
        }

        .chat-window-title {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .chat-section {
            width: 100%;
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #DBDDE0;
            border-radius: 8px;
            padding: 10px;
            background-color: #FFFFFF;
        }

        .chat-input {
            width: calc(100% - 100px);
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #DBDDE0;
        }

        button {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            background-color: #AAE1DE;
            color: #FFFFFF;
            cursor: pointer;
        }

        button:hover {
            background-color: #009688; /* Darker shade on hover */
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="logo-section">
        <img src="../images/FlexMatchLogo.png" alt="FlexMatch Logo" class="logo-image">
        <h1 class="logo-text">FlexMatch</h1>
    </div>
    <nav class="menu">
        <div class="menu-item" onclick="location.href='job_posting_form.php'">
            <img src="../images/add_circle.png" alt="Create Job Icon" class="menu-icon">
            <span>Create Job</span>
        </div>
        <div class="menu-item" onclick="location.href='job_posting_list.php'">
            <img src="../images/text_snippet.png" alt="Posted Job Icon" class="menu-icon">
            <span>Posted Job</span>
        </div>
        <div class="menu-item">
            <img src="../images/note_alt.png" alt="Application Icon" class="menu-icon">
            <span>Application</span>
        </div>
        <div class="menu-item">
            <img src="../images/contacts.png" alt="Job Seeker Wall Icon" class="menu-icon">
            <span>Job Seeker Wall</span>
        </div>
    </nav>
    <div class="logout" onclick="location.href='../login.html'">
        <img src="../images/Vector.png" alt="Logout Icon" class="menu-icon">
        <span class="logout-text">Logout</span>
    </div>
</div>

<!-- Main content -->
<div class="main-content">
    <header class="header">
        <div class="header-right">
            <img src="../images/Notification.png" alt="Notification Icon" class="notification-icon">
            <img src="../images/Chat.png" alt="Chat Icon" class="notification-icon">
            <img src="../images/employer.png" alt="User Image" class="profile-image">
            <div class="user-info">
                <span class="user-name"><?php echo htmlspecialchars(string: $fullName); ?></span>
                <span class="user-role">Employer</span>
            </div>
        </div>
        <span class="logout-button" onclick="location.href='../login.html'">Log Out</span>
    </header>
    
    <section class="content">
        <h2 class="chat-window-title">Employer Chat</h2>
        <div class="chat-section" id="chatSection"></div>

        <form id="chatForm" onsubmit="sendMessage(event, 'employer')">
            <input type="text" id="chatInput" class="chat-input" placeholder="Type a message...">
            <button type="submit">Send</button>
        </form>
    </section>
</div>

<!-- Grey Overlay -->
<div id="overlay" class="overlay"></div>

<!-- Notification Modal -->
<div id="notificationModal" class="modal">
    <div class="modal-content">
        <p>Your message has been sent to the job seeker.</p>
        <button onclick="closeNotification()">Ok</button>
    </div>
</div>

<script src="../js/chat.js"></script>

</body>
</html>
