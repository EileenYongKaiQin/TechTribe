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

if (isset($_POST['confirm_delete_profile'])) {
    mysqli_begin_transaction($con);

    try {
        $deleteJobseeker = "DELETE FROM jobseeker WHERE userID = '$userID'";
        mysqli_query($con, $deleteJobseeker);

        $deleteLogin = "DELETE FROM login WHERE userID = '$userID'";
        mysqli_query($con, $deleteLogin);

        mysqli_commit($con);
        session_destroy();
        
        echo "<script>
            window.location.href = '../login.html?status=delete_success';
        </script>";
        exit();
    } catch (Exception $e) {
        mysqli_rollback($con);
        echo "<script>
            window.location.href = 'view_profile.php?status=delete_failed';
        </script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Profile</title>
    <style>
        body {
            font-family: sans-serif;
            font-family: "Poppins", sans-serif;
            background: #F0FDFF;
        }
        .popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1000;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .popup .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            transition: opacity 100ms ease-in-out 200ms;
        }
        .popup .popup-content {
            position: relative;
            width: 95%;
            max-width: 600px;
            background: #ffffff;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0px 2px 2px 5px rgba(0,0,0,0.05);
        }
        .popup .popup-content h3 {
            margin: 20px auto;
            font-size: 25px;
            color: #111111;
            text-align: center;
        }
        .popup .popup-content p {
            font-size: 15px;
            color: #222222;
            text-align: center;
            margin-bottom: 30px;
        }
        .popup .popup-content .controls {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .popup .popup-content .controls button {
            padding: 10px 20px;
            border: none;
            outline: none;
            font-size: 15px;
            border-radius: 20px;
            cursor: pointer;
            min-width: 100px;
        }
        .popup .popup-content .controls .delete-btn {
            background: #dc3545;
            color: #ffffff;
        }
        .popup .popup-content .controls .cancel-btn {
            background: #6c757d;
            color: #ffffff;
        }
        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 30px;
            font-size: 20px;
            color: #c0c5cb;
            background-color: transparent;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="popup">
        <div class="overlay"></div>
        <div class="popup-content">
            <button class="close-btn">✖</button>
            <h3>Delete Profile</h3>
            <p>Are you sure you want to delete your profile? This action cannot be undone.</p>
            <div class="controls">
                <form method="POST">
                    <button type="submit" name="confirm_delete_profile" class="delete-btn">Delete</button>
                </form>
                <button class="cancel-btn" onclick="window.location.href='view_selfprofile_jobseeker.php'">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const closeBtn = document.querySelector('.close-btn');
            const cancelBtn = document.querySelector('.cancel-btn');
            const overlay = document.querySelector('.overlay');

            const redirectToProfile = () => {
                window.location.href = 'view_selfprofile_jobseeker.php';
            };

            closeBtn.addEventListener('click', redirectToProfile);
            cancelBtn.addEventListener('click', redirectToProfile);
            overlay.addEventListener('click', redirectToProfile);
        });
    </script>
</body>
</html>
