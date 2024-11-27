<?php
// Include the database configuration file
include 'database/config.php';

// Start the session
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = $_POST['password']; // Do not escape the password since it will be hashed

    // Query the database for the user
    $query = "SELECT * FROM login WHERE username = '$username'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Store user details in the session
            $_SESSION['userID'] = $user['userID'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Update last login timestamp
            $updateQuery = "UPDATE login SET lastLogin = NOW() WHERE userID = '{$user['userID']}'";
            mysqli_query($con, $updateQuery);

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
            echo "<script>alert('Incorrect password. Please try again.'); window.location.href = 'login.html';</script>";
        }
    } else {
        echo "<script>alert('Username not found. Please try again.'); window.location.href = 'login.html';</script>";
    }
}
?>
