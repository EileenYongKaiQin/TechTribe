<?php
ob_start(); // Start output buffering

include('../database/config.php');
include('employerNew.php');

$successMessage = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['jobPostID'])) {
    $jobPostID = mysqli_real_escape_string($con, $_POST['jobPostID']);

    $deleteQuery = "DELETE FROM jobPost WHERE jobPostID = '$jobPostID'";
    if (mysqli_query($con, $deleteQuery)) {
        $successMessage = true;
    } else {
        echo "Error deleting the job posting. Please try again later.";
        exit;
    }
}

if (isset($_GET['jobPostID']) && !$successMessage) {
    $jobPostID = mysqli_real_escape_string($con, $_GET['jobPostID']);
    $sql = "SELECT jobTitle FROM jobPost WHERE jobPostID = '$jobPostID'";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $job = mysqli_fetch_assoc($result);
    } else {
        echo "Job posting not found.";
        exit;
    }
} elseif (!isset($_POST['jobPostID']) && !$successMessage) {
    echo "Invalid job posting ID.";
    exit;
}

mysqli_close($con);

// End output buffering
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Deletion</title>
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Background Overlay */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }

        /* Modal Box */
        .modal-container,
        .modal {
            position: absolute;
            width: 600px;
            height: 320px;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            background: #FFFFFF;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.25);
            border-radius: 16px;
            z-index: 1000;
            text-align: center;
            padding: 10px;
        }

        .modal-title {
            font-size: 28px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 16px;
            margin-top: 20px;
        }

        .modal-message {
            font-size: 16px;
            color: #6C757D;
            margin-bottom: 16px;
        }

        .job-title {
            font-size: 18px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 32px;
        }

        /* Buttons */
        .modal-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 40px;
        }

        .cancel-button,
        .delete-button {
            background: #FF5252;
            color: #FFFFFF;
            border: none;
            padding: 10px 40px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 50px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.25);
            cursor: pointer;
        }

        .cancel-button {
            background: #2E6FE8;
        }

        .cancel-button:hover,
        .delete-button:hover {
            opacity: 0.9;
        }

        .modal-success {
            display: none;
        }
    </style>
</head>
<body>
    <?php if ($successMessage): ?>
        <!-- Background Overlay -->
        <div class="modal-overlay" style="display: block;"></div>

        <!-- Success Modal -->
        <div class="modal" style="display: block;">
            <img src="../images/check-one.png" alt="Success" class="modal-icon" style="width: 50px; margin-top: 20px;">
            <h2 class="modal-title">SUCCESS</h2>
            <p class="modal-message">Job posting has been successfully deleted.</p>
            <button class="cancel-button" onclick="redirectToList()">Close</button>
        </div>

        <script>
            function redirectToList() {
                window.location.href = 'job_posting_list.php';
            }
        </script>
    <?php else: ?>
        <!-- Background Overlay -->
        <div class="modal-overlay" style="display: block;"></div>

        <!-- Confirmation Modal -->
        <div class="modal-container">
            <h2 class="modal-title">Confirmation</h2>
            <p class="modal-message">Are you sure you want to delete the following job posting?</p>
            <p class="modal-message">This action cannot be undone.</p>
            <div class="job-title">Job Title: <?php echo htmlspecialchars($job['jobTitle']); ?></div>

            <!-- Modal Buttons -->
            <div class="modal-buttons">
                <form action="confirm_delete.php" method="POST" style="display: inline;">
                    <input type="hidden" name="jobPostID" value="<?php echo $jobPostID; ?>">
                    <button type="submit" class="delete-button">Delete</button>
                </form>
                <a href="job_posting_list.php" class="cancel-button">Cancel</a>
            </div>
        </div>
    <?php endif; ?>
</body>
</html>
