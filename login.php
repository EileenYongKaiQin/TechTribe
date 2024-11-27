<?php
// Include the database configuration file
include 'database/config.php';

// Start the session
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Hash the entered password using MD5
    // Prepare the SQL statement
    $stmt = $con->prepare("SELECT * FROM login WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Store user details in the session
        $_SESSION['userID'] = $user['userID'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Update last login timestamp
        $updateQuery = $con->prepare("UPDATE login SET lastLogin = NOW() WHERE userID = ?");
        $updateQuery->bind_param("s", $user['userID']);
        $updateQuery->execute();

        // Redirect based on role
        if ($user['role'] === 'admin') {
            header('Location: Admin/admin_dashboard.php');
        } elseif ($user['role'] === 'employer') {
            header('Location: Employer/employer_dashboard.php');
        } else {
            header('Location: Job Seeker/jobseeker_dashboard.php');
        }
        exit();
    } else {
        // Invalid credentials
        echo "<script>alert('Incorrect username or password. Please try again.'); window.location.href = 'login.html';</script>";
    }
}
?>
