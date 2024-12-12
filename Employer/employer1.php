<?php
// Detect the current file name
$currentPage = basename($_SERVER['PHP_SELF']);
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
    SELECT employer.fullName 
    FROM employer 
    INNER JOIN login ON employer.userID = login.userID 
    WHERE employer.userID = ?
");
$query->bind_param("s", $userID);
$query->execute();
$result = $query->get_result();

if ($result && $result->num_rows > 0) {
    $employer = $result->fetch_assoc();
    $fullName = $employer['fullName']; // Get the full name
} else {
    $fullName = "Employer"; // Default fallback if user not found
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
            <h1 class="logo-text">FlexMatch</h1>
        </div>
        <nav class="menu">
        <div class="menu-item <?php echo $currentPage == 'employer_dashboard.php' ? 'active' : ''; ?>">
                <img src="../images/dashboardIcon.png" alt="Dashboard Icon" class="menu-icon">
                <span onclick="location.href='employer_dashboard.php'">Dashboard</span>
            </div>
            <div class="menu-item <?php echo $currentPage == 'job_posting_form.php' ? 'active' : ''; ?>">
                <img src="../images/add_circle.png" alt="Create Job Icon" class="menu-icon">
                <span onclick="location.href='job_posting_form.php'">Create Job</span>
            </div>
            <div class="menu-item <?php echo $currentPage == 'job_posting_list.php' ? 'active' : ''; ?>">
                <img src="../images/text_snippet.png" alt="Posted Job Icon" class="menu-icon">
                <span onclick="location.href='job_posting_list.php'">Posted Job</span>
            </div>
            <div class="menu-item <?php echo $currentPage == 'response_application.php' ? 'active' : ''; ?>">
                <img src="../images/note_alt.png" alt="Application Icon" class="menu-icon">
                <span onclick="location.href='response_application.php'">Application</span>
            </div>
            <div class="menu-item <?php echo $currentPage == 'job_seeker_wall.php' ? 'active' : ''; ?>">
                <img src="../images/contacts.png" alt="Job Seeker Wall Icon" class="menu-icon">
                <span onclick="location.href='job_seeker_wall.php'">Job Seeker Wall</span>
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
            <img src="../images/Chat.png" alt="Chat Icon" class="notification-icon" onclick="location.href='../employer/employer_job_seeker_list.php'">
            <a href="view_employer_profile.php"><img src="../images/employer.png" alt="User Image" class="profile-image"></a>
                <a href="view_employer_profile.php">    
                    <div class="user-info">
                        <span class="user-name"><?php echo htmlspecialchars($fullName); ?></span>
                        <span class="user-role">Employer</span>
                    </div>
                </a>
        </div>
        <span class="logout-button" onclick="location.href='../login.html'">Log Out</span>
    </header>
    <section class="content">
    </section>

    </div>    
</body>
</html>
