<?php
include 'database/config.php';

session_start();

$username = $_POST['username'];
$password = md5($_POST['password']);
$role = $_POST['role'];

if ($role === "jobseeker") {
    $prefix = "JS";
    $query = "SELECT COUNT(*) AS count FROM login WHERE role = 'jobseeker'";
} else if ($role === "employer") {
    $prefix = "EP";
    $query = "SELECT COUNT(*) AS count FROM login WHERE role = 'employer'";
} else {
    die("Invalid role selected.");
}

$result = $con->query($query);
$row = $result->fetch_assoc();
$count = (int)$row['count'] + 1;

$userID = $prefix . str_pad($count, 3, '0', STR_PAD_LEFT);

$insert_query = "INSERT INTO login (userID, username, password, role) VALUES (?, ?, ?, ?)";
$stmt = $con->prepare($insert_query);
$stmt->bind_param("ssss", $userID, $username, $password, $role);

if ($stmt->execute()) {
    $_SESSION['userID'] = $userID;
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $role;
    
    if ($role === "jobseeker") {
        header("Location: Job Seeker/create_jobseeker_profile.php");
        exit();
    } else if ($role === "employer") {
        header("Location: Employer/create_employer_profile.php");
        exit();
    }
} else {
    echo "Error: " . $stmt->error;
}
?>