<?php
// Include the database configuration file
include 'database/config.php';

// Start the session
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Hash the entered password using MD5
    
    // First check if user exists and get their role and status
    $stmt = $con->prepare("SELECT l.*, 
        CASE 
            WHEN e.accountStatus IS NOT NULL THEN e.accountStatus
            WHEN j.accountStatus IS NOT NULL THEN j.accountStatus
            ELSE NULL
        END as accountStatus,
        CASE 
            WHEN e.suspensionEndDate IS NOT NULL THEN e.suspensionEndDate
            WHEN j.suspensionEndDate IS NOT NULL THEN j.suspensionEndDate
            ELSE NULL
        END as suspensionEndDate,
        l.role,
        l.lastLogin
        FROM login l
        LEFT JOIN employer e ON l.userID = e.userID
        LEFT JOIN jobseeker j ON l.userID = j.userID
        WHERE l.username = ? AND l.password = ?");
    
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (in_array($user['accountStatus'], [
            'Suspended-Permanently',
            'Suspended-Temporary-6M',
            'Suspended-Temporary-2Y',
            'Suspended-Temporary-5Y'
        ])) {
            if ($user['accountStatus'] === 'Suspended-Permanently') {
                echo "<script>alert('Your account has been permanently suspended. Please contact admin for assistance.'); 
                      window.location.href = 'login.html';</script>";
            } else {
                $endDate = new DateTime($user['suspensionEndDate']);
                $formattedDate = $endDate->format('Y-m-d');
                echo "<script>alert('Your account is suspended until $formattedDate. Please contact admin for assistance.'); 
                      window.location.href = 'login.html';</script>";
            }
            exit();
        }

        if ($user['role'] !== 'admin') {
            $currentTime = new DateTime();
            $lastLogin = new DateTime($user['lastLogin']);
            $inactivePeriod = $currentTime->diff($lastLogin);
            
            if ($user['accountStatus'] === 'Inactive') {
                $table = ($user['role'] === 'employer') ? 'employer' : 'jobseeker';
                $updateStatus = $con->prepare("UPDATE $table SET accountStatus = 'Active' WHERE userID = ?");
                $updateStatus->bind_param("s", $user['userID']);
                $updateStatus->execute();
            }
            elseif ($inactivePeriod->m >= 3 || $inactivePeriod->y > 0) {
                $table = ($user['role'] === 'employer') ? 'employer' : 'jobseeker';
                $updateStatus = $con->prepare("UPDATE $table SET accountStatus = 'Inactive' WHERE userID = ?");
                $updateStatus->bind_param("s", $user['userID']);
                $updateStatus->execute();
            }
        }

        $_SESSION['userID'] = $user['userID'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        $updateQuery = $con->prepare("UPDATE login SET lastLogin = NOW() WHERE userID = ?");
        $updateQuery->bind_param("s", $user['userID']);
        $updateQuery->execute();

        // Redirect based on role
        switch ($user['role']) {
            case 'admin':
                header('Location: Admin/admin_dashboard.php');
                break;
            case 'employer':
                header('Location: Employer/employer_dashboard.php');
                break;
            default:
                header('Location: Job Seeker/jobseeker_dashboard.php');
                break;
        }
        exit();
    } else {
        // Invalid credentials
        echo "<script>alert('Incorrect username or password. Please try again.'); 
              window.location.href = 'login.html';</script>";
    }
}
?>