<?php
session_start(); // Start the session

// Include the database configuration file
include '../database/config.php';
include '../notification/notification.php';

// Check if the user is logged in
if (!isset($_SESSION['userID'])) {
    // Redirect to login page if not logged in
    header('Location: ../login.html');
    exit();
}

// Fetch the logged-in admin's full name from the admin table
$userID = $_SESSION['userID'];
$query = $con->prepare("
    SELECT admin.fullName 
    FROM admin 
    INNER JOIN login ON admin.userID = login.userID 
    WHERE admin.userID = ?
");
$query->bind_param("s", $userID);
$query->execute();
$result = $query->get_result();

if ($result && $result->num_rows > 0) {
    $admin = $result->fetch_assoc();
    $fullName = $admin['fullName']; // Get the full name
} else {
    $fullName = "Admin"; // Default fallback if user not found
}

$noti = fetchNotifications($userID);
// Check if notifications were fetched correctly
if ($noti !== false) {
    $notiArray = $noti['notifications']->fetch_all(MYSQLI_ASSOC);
    $notiCount = $noti['unreadCount'];
} else {
    // Handle the case when fetching notifications fails
    $notiArray = [];
    $notiCount = 0;
}
?>

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
        }

        .logo-image {
            width: 70px;
        }

        .logo-text {
            font-weight: bold;
            font-size: 20px;
            color: #FFFFFF;
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
            margin-bottom: 50px;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 14px;
            color: #FFFFFF;
            display: flex;
            align-items: center;
            gap: 10px;
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

        /* Notifications */
        .notification-container {
            position: absolute;
            width: 362px;
            height: auto;
            top: 60px;
            right: 300px;
            background: #F4F4F4;
            border-radius: 8px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            padding: 10px;
            display: none;
        }

        .notification-item1 {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            padding: 10px;
            margin: 5px 0;
            border-radius: 8px;
        }

        .notification-item2 {
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            padding: 15px;
            margin: 5px 0;
            background: rgba(217, 217, 217, 0.8);
            border-radius: 8px;
            position: relative;
        }

        .notification-text {
            font-weight: 600;
            font-size: 12px;
            color: #000000;
            line-height: 1.5;
        }

        .notification-time {
            font-size: 8px;
            color: rgba(0, 0, 0, 0.5);
            position: absolute;
            bottom: 5px; 
            right: 10px;
        }

        .notification-count {
            position: absolute;
            top: 20px;
            right: 300px;
            background-color: red;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            color: white;
            font-size: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .notification-type-icon {
            width: 20px;
            height: 20px;
            margin-right: 10px; /* Add some spacing between the icon and text */
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
                <img src="../images/content_paste_search.png" alt="Review Report Icon" class="menu-icon">
                <span onclick="location.href='reviewReport.php'">Report</span>
            </div>
            <div class="menu-item">
                <img src="../images/reported_account.png" alt="Review Report Icon" class="menu-icon">
                <span onclick="location.href='accountIssueList.php'">User Account</span>
            </div>
        </nav>
        <div class="logout" onclick="location.href='../login.html'">
            <img src="../images/vector.png" alt="Logout Icon" class="menu-icon">
            <span class="logout-text">Logout</span>
        </div>
    </div>        
    <header class="header">
        <div class="header-right">
                <div class="notification-item1">
                <div class="notification-count" style="<?php echo ($notiCount == 0) ? 'display:none;' : ''; ?>">
                    <?php echo $notiCount; ?>
                </div>
                    <img src="../images/Notification.png" alt="Notification Icon" class="notification-icon">
                </div>
        <div class="notification-container">
                <?php foreach($notiArray as $notification): ?>
                <div class="notification-item2" style="<?php echo ($notification['isRead'] == 1) ? 'background-color: #e0e0e0;' : ''; ?>">
                    <div class="notification-type-icon">
                        <?php
                        if ($notification['notificationType'] == 'status-change') {
                            echo '<img src="../images/status-change-icon.png" alt="Status Change Icon" class="icon">';
                        } elseif ($notification['notificationType'] == 'warning') {
                            echo '<img src="../images/warning-icon.png" alt="Warning Icon" class="icon">';
                        }
                        ?>
                    </div>
                    <div class="notification-text"><?php echo $notification['notificationText']; ?></div>
                    <div class="notification-time" data-timestamp="<?php echo $notification['createdAt']; ?>"></div>
                </div>
                <?php endforeach; ?>
        </div>
            <img src="../images/Admin.png" alt="User Image" class="profile-image">
            <div class="user-info">
                <span class="user-name"><?php echo htmlspecialchars($fullName); ?></span>
                <span class="user-role">Admin</span>
            </div>
        </div>
        <span class="logout-button" onclick="location.href='../login.html'">Log Out</span>
    </header>
    <section class="content">
    </section>

    </div>    
    <script>
        // Function to toggle the submenu visibility
        function toggleSubmenu() {
            const submenu = document.querySelector('.submenu');
            submenu.style.display = submenu.style.display === 'flex' ? 'none' : 'flex';
        }

        document.querySelector('.notification-icon').addEventListener('click', function() {
            const notificationContainer = document.querySelector('.notification-container');
            const notificationCount = document.querySelector('.notification-count');

            // Fetch the userID from PHP and send the POST request to mark notifications as read
            fetch('../notification/markNotificationsAsRead.php', {
                method: 'POST',
                body: JSON.stringify({ userID: '<?php echo $userID; ?>' }), // Make sure userID is available from PHP
                headers: { 'Content-Type': 'application/json' },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hide the notification count (set to 0 and hide it)
                    notificationCount.textContent = '0';
                    notificationCount.style.display = 'none'; // Hide the notification count

                    // Toggle visibility of the notification container
                    notificationContainer.style.display = (notificationContainer.style.display === 'none' || notificationContainer.style.display === '') ? 'block' : 'none';
                } else {
                    console.error("Failed to mark notifications as read");
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
        });

        function time_ago(timestamp) {
            const time_ago = new Date(timestamp);
            const current_time = new Date();
            const time_difference = current_time - time_ago;
            const seconds = time_difference / 1000;
            const minutes = Math.round(seconds / 60);
            const hours = Math.round(seconds / 3600);
            const days = Math.round(seconds / 86400);
            const weeks = Math.round(seconds / 604800);
            const months = Math.round(seconds / 2629440);
            const years = Math.round(seconds / 31553280);

            if (seconds <= 60) {
                return "Just Now";
            } else if (minutes <= 60) {
                return minutes === 1 ? "one minute ago" : `${minutes} minutes ago`;
            } else if (hours <= 24) {
                return hours === 1 ? "an hour ago" : `${hours} hours ago`;
            } else if (days <= 7) {
                return days === 1 ? "yesterday" : `${days} days ago`;
            } else if (weeks <= 4.3) {
                return weeks === 1 ? "one week ago" : `${weeks} weeks ago`;
            } else if (months <= 12) {
                return months === 1 ? "one month ago" : `${months} months ago`;
            } else {
                return years === 1 ? "one year ago" : `${years} years ago`;
            }
        }

        // Function to update the notification time dynamically
        function updateNotificationTimes() {
            const notificationTimes = document.querySelectorAll('.notification-time');

            notificationTimes.forEach(function(notiTime) {
                const timestamp = notiTime.getAttribute('data-timestamp');
                const timeAgo = time_ago(timestamp);
                notiTime.textContent = timeAgo;
            });
        }

        // Update times every minute
        setInterval(updateNotificationTimes, 60000); // Update every minute

        // Initial update of notification times
        updateNotificationTimes();

        

    </script>
</body>
</html>