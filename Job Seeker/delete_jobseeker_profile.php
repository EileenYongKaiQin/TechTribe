<?php
session_start();
include('../database/config.php');

if (!isset($_SESSION['userID'])) {
    header('Location: ../login.html');
    exit();
}

$userID = $_SESSION['userID'];

$sql = mysqli_query($con, "SELECT * FROM jobseeker WHERE userID='$userID'");
if (!$sql || mysqli_num_rows($sql) === 0) {
    echo "<script>alert('Profile not found. Nothing to delete.'); window.location.href='jobseeker_dashboard.php';</script>";
    exit();
}

mysqli_begin_transaction($con);

try {
    $deleteJobseeker = "DELETE FROM jobseeker WHERE userID = '$userID'";
    mysqli_query($con, $deleteJobseeker);

    $deleteLogin = "DELETE FROM login WHERE userID = '$userID'";
    mysqli_query($con, $deleteLogin);

    mysqli_commit($con);

    session_destroy();

    echo "<script>alert('Your account and associated data have been deleted successfully.'); window.location.href='../login.html';</script>";
} catch (Exception $e) {
    mysqli_rollback($con);

    echo "<script>alert('Failed to delete your account. Please try again later.'); window.location.href='jobseeker_dashboard.php';</script>";
}
?>
