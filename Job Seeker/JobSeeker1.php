<?php
// Detect the current file name
$currentPage = basename($_SERVER['PHP_SELF']);
$submenuVisible = ($currentPage == 'job_seeker_wall.php' || $currentPage == 'my_posts.php') ? 'flex' : 'none';
session_start(); // Start the session

// Include the database configuration file
include '../database/config.php';
include '../notification/notification.php';

// Check if the user is logged in
if (!isset($_SESSION['userID'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}

// Fetch the logged-in user's full name from the jobSeeker table
$userID = $_SESSION['userID'];
$query = $con->prepare("
    SELECT jobSeeker.fullName, jobSeeker.profilePic 
    FROM jobSeeker 
    INNER JOIN login ON jobSeeker.userID = login.userID 
    WHERE jobSeeker.userID = ?
");
$query->bind_param("s", $userID);
$query->execute();
$result = $query->get_result();

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $fullName = $user['fullName']; // Get the full name
    $profilePic = $user['profilePic'];
} else {
    $fullName = "Guest"; // Default fallback if user not found
    $profilePic = null;
}

$noti = fetchNotificationsWithReport($userID);
// Check if notifications were fetched correctly
if ($noti !== false) {
    $notiArray = $noti['notifications']->fetch_all(MYSQLI_ASSOC);
    $notiCount = $noti['unreadCount'];
} else {
    // Handle the case when fetching notifications fails
    $notiArray = [];
    $notiCount = 0;
}

$profilePicPath = "../images/JobSeeker.png";
if (!empty($profilePic)) {
    $customProfilePicPath = "../uploads/profile_pictures/" . htmlspecialchars($profilePic);
    if (file_exists($customProfilePicPath)) {
        $profilePicPath = $customProfilePicPath;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/job_seeker_layout.css">
    <style>
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
            cursor: pointer;
        }

        .notification-icon {
            width: 32px;
            height: 32px;
            cursor: pointer;
            position: relative;
            left: 10px; /* Adjust this value to move it to the right */
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
            right: 330px;
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
    <title>FlexMatch</title>
    
</head>
<body>
    <div class="sidebar">
        <div class="logo-section">
            <img src="../images/FlexMatchLogo.png" alt="FlexMatch Logo" class="logo-image">
            <div class="title">
                <h1 class="logo-text">FlexMatch</h1>        
            </div>
        </div>
        <nav class="menu">
            <div class="menu-item <?php echo $currentPage == 'jobseeker_dashboard.php' ? 'active' : ''; ?>">
                <img src="../images/dashboardIcon.png" alt="Dashboard Icon" class="menu-icon">
                <span onclick="location.href='jobseeker_dashboard.php'">Dashboard</span>
            </div>
            <div class="menu-item <?php echo $currentPage == 'jobSeeker_posting_list.php' ? 'active' : ''; ?>">
                <img src="../images/add_circle.png" alt="Apply Job Icon" class="menu-icon">
                <span onclick="location.href='jobSeeker_posting_list.php'">Apply Job</span>
            </div>

            <div class="menu-item <?php echo $currentPage == 'my_application.php' ? 'active' : ''; ?>">
                <img src="../images/note_alt.png" alt="Application Icon" class="menu-icon">
                <span onclick="location.href='my_application.php'">My Application</span>
            </div>
            <div class="menu-item <?php echo $currentPage == 'saved_job.php' ? 'active' : ''; ?>">
                <img src="../images/save.png" alt="Saved Job Icon" class="menu-icon">
                <span onclick="location.href='saved_job.php'">Saved Job</span>
            </div>
            <div class="menu-item" onclick="toggleSubmenu()">
                <img src="../images/contacts.png" alt="Job Seeker Wall Icon" class="menu-icon">
                <span>Job Seeker Wall</span>
            </div>
            <div class="submenu" style="display: <?php echo $submenuVisible; ?>;">
                <div class="submenu-item <?php echo $currentPage == 'job_seeker_wall.php' ? 'active' : ''; ?>" onclick="location.href='job_seeker_wall.php'">View Posts</div>
                <div class="submenu-item <?php echo $currentPage == 'my_posts.php' ? 'active' : ''; ?>" onclick="location.href='my_posts.php'">My Posts</div>
            </div>
            <div class="menu-item <?php echo $currentPage == 'job_history.php'? 'active' : ''; ?>">
                <img src="../images/history.png" alt="Job History Icon" class="menu-icon">
                <span onclick="location.href='job_history.php'">Job History</span>
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
                        <img src="../images/Notification.png" alt="Notification Icon" class="notification-icon"> &nbsp;
                        <img src="../images/Chat.png" alt="Chat Icon" class="notification-icon" onclick="location.href='../Job Seeker/jobSeeker_employer_list.php'">
                    </div>
                    
            <div class="notification-container">
                    <?php foreach($notiArray as $notification): ?>
                        <?php
                        $reportID = $notification['reportID'] ?? null; // Directly use the reportID if available
                        $notificationType = $notification['notificationType'] ?? null; // Get notificationType
                        $destination = '#';

                        // Determine the navigation link based on the notification type
                        if ($notificationType === 'status-change') {
                            $destination = $reportID ? 'viewReportStatus.php?reportID=' . urlencode($reportID) : '#';
                        } elseif ($notificationType === 'warning') {
                            $destination = $reportID ? 'viewWarningComment.php?reportID=' . urlencode($reportID) : '#';
                        }
                        ?>
                    <div class="notification-item2" style="<?php echo ($notification['isRead'] == 1) ? 'background-color: #e0e0e0;' : ''; ?>" onclick="window.location.href='<?php echo $destination; ?>';">
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
            
            <a href="view_selfprofile_jobseeker.php"><img src="<?php echo $profilePicPath; ?>" alt="User Image" class="profile-image"></a>
            <a href="view_selfprofile_jobseeker.php">
            <div class="user-info">
                <span class="user-name"><?php echo htmlspecialchars($fullName); ?></span>
                <span class="user-role">Job Seeker</span>
            </div>
            </a>
        </div>
        <span class="logout-button" onclick="location.href='../login.html'">Log Out</span>
    </header>
    <section class="content">
    </section>

    </div>    
    <script>


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
        // Function to toggle the submenu visibility
        function toggleSubmenu() {
            const submenu = document.querySelector('.submenu');
            submenu.style.display = submenu.style.display === 'flex' ? 'none' : 'flex';
        }

        // Update times every minute
        setInterval(updateNotificationTimes, 60000); // Update every minute

        // Initial update of notification times
        updateNotificationTimes();

    </script>
</body>
</html>