<?php
session_start(); // Start the session

// Include the database configuration file
include '../database/config.php';

// Check if the user is logged in
if (!isset($_SESSION['userID'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}

// Fetch the logged-in employer's full name from the employer table
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
            width: 100%;
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
        a {
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <header class="header">
        <div class="header-right">
            <img src="../images/Notification.png" alt="Notification Icon" class="notification-icon">
            <img src="../images/Chat.png" alt="Chat Icon" class="notification-icon" onclick="location.href='../employer/employer_chat.php'">
            <a href="employer_dashboard.php"><img src="../images/employer.png" alt="User Image" class="profile-image"></a>
            <a href="employer_dashboard.php">
                    <div class="user-info">
                        <span class="user-name"><?php echo htmlspecialchars($fullName); ?></span>
                        <span class="user-role">Employer</span>
                    </div>
                </a>
                <span class="logout-button" onclick="location.href='../login.html'">Log Out</span>
            </div>
        </header>
        <section class="content">
        </section>
    </div>
</body>
</html>
