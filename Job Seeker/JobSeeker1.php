<?php
// Detect the current file name
$currentPage = basename($_SERVER['PHP_SELF']);
$submenuVisible = ($currentPage == 'job_seeker_wall.php' || $currentPage == 'my_posts.php') ? 'flex' : 'none';
session_start(); // Start the session

// Include the database configuration file
include '../database/config.php';

// Check if the user is logged in
if (!isset($_SESSION['userID'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}

// Fetch the logged-in user's full name from the jobSeeker table
$userID = $_SESSION['userID'];
$query = $con->prepare("
    SELECT jobSeeker.fullName 
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
} else {
    $fullName = "Guest"; // Default fallback if user not found
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/job_seeker_layout.css">
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
        </nav>
        <div class="logout" onclick="location.href='../login.html'">
            <img src="../images/vector.png" alt="Logout Icon" class="menu-icon">
            <span class="logout-text">Logout</span>
        </div>
    </div>        
    <header class="header">
        <div class="header-right">
            <img src="../images/Notification.png" alt="Notification Icon" class="notification-icon">
            <img src="../images/Chat.png" alt="Chat Icon" class="notification-icon" onclick="location.href='../Job Seeker/jobSeeker_chat.php'">
            <a href="view_jobseeker_profile.php"><img src="../images/JobSeeker.png" alt="User Image" class="profile-image"></a>
            <a href="view_jobseeker_profile.php">
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
        // Function to toggle the submenu visibility
        function toggleSubmenu() {
            const submenu = document.querySelector('.submenu');
            submenu.style.display = submenu.style.display === 'flex' ? 'none' : 'flex';
        }
    </script>
</body>
</html>
