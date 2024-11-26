<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
    <title>FlexMatch</title>
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

        .menu-item, .logout {
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

        /* Hover effect */
        .menu-item:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
            cursor: pointer;
        }

        /* Hover effect for both menu items and logout */
        .menu-item:hover,
        .logout:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
            color: #FFFFFF;
        }

        .logout {
            margin-top: auto;
            margin-bottom: 80px;
            text-align: center;
        }

        .logout-text {
            color: #FFFFFF;
            font-size: 14px;
            font-family: 'Arial', sans-serif;
            font-weight: 400;
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
            position: sticky;
            z-index: 1000;
            left: 180px;
            top: 0;
            width: calc(100% - 180px)
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

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-right: 20px; 
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

        .logout-button {
            font-size: 14px;
            color: #000000;
            border: none;
            background: none;
            cursor: pointer;
            margin-left: 80px;
            transition: color 0.3s ease; 
        }

        /* Hover effect */
        .logout-button:hover {
            color: #AAE1DE;
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

        /* Hidden submenu styles */
        .submenu {
            display: none;
            flex-direction: column;
            gap: 10px;
            padding-left: 20px;
        }

        .submenu-item {
            padding: 10px;
            cursor: pointer;
            color: #FFFFFF;
            font-size: 14px;
            font-family: 'Cabin', sans-serif;
            font-weight: 400;
            border-radius: 8px; 
        }

        .submenu-item:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo-section">
            <img src="../images/FlexMatchLogo.png" alt="FlexMatch Logo" class="logo-image">
            <h1 class="logo-text">FlexMatch</h1>
        </div>
        <nav class="menu">
            <div class="menu-item">
                <img src="../images/add_circle.png" alt="Apply Job Icon" class="menu-icon">
                <span onclick="location.href='job_posting_form.php'">Apply Job</span>
            </div>
            <div class="menu-item">
                <img src="../images/note_alt.png" alt="Application Icon" class="menu-icon">
                <span>My Application</span>
            </div>
            <div class="menu-item" onclick="toggleSubmenu()">
                <img src="../images/contacts.png" alt="Job Seeker Wall Icon" class="menu-icon">
                <span>Job Seeker Wall</span>
            </div>
            <div class="submenu">
                <div class="submenu-item" onclick="location.href='job_seeker_wall.php'">View Posts</div>
                <div class="submenu-item" onclick="location.href='my_posts.php'">My Posts</div>
            </div>
        </nav>
        <div class="logout" onclick="location.href='login.php'">
            <img src="../images/vector.png" alt="Logout Icon" class="menu-icon">
            <span class="logout-text">Logout</span>
        </div>
    </div>        
    <header class="header">
        <div class="header-right">
            <img src="../images/Notification.png" alt="Notification Icon" class="notification-icon">
            <img src="../images/Chat.png" alt="Chat Icon" class="notification-icon">
            <img src="../images/employer.png" alt="User Image" class="profile-image">
            <div class="user-info">
                <span class="user-name">User</span>
                <span class="user-role">Job Seeker</span>
            </div>
        </div>
        <span class="logout-button" onclick="location.href='login.php'">Log Out</span>
    </header>

    </div>    
    <script>
        // Function to toggle the submenu visibility
        function toggleSubmenu() {
            const submenu = document.querySelector('.submenu');
            submenu.style.display = submenu.style.display === 'flex' ? 'none' : 'flex';
        }
    </script>
</body>
</html>
