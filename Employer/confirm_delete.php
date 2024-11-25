<?php
ob_start(); // Start output buffering

include('../database/config.php');
include('employer.php'); 

// Handle delete confirmation request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['jobPostID'])) {
    $jobPostID = mysqli_real_escape_string($con, $_POST['jobPostID']);

    $deleteQuery = "DELETE FROM jobPost WHERE jobPostID = '$jobPostID'";
    if (mysqli_query($con, $deleteQuery)) {
        header("Location: job_posting_list.php?status=success&message=Job posting deleted successfully");
        exit;
    } else {
        echo "Error deleting the job posting. Please try again later.";
    }
}

if (isset($_GET['jobPostID'])) {
    $jobPostID = mysqli_real_escape_string($con, $_GET['jobPostID']);
    $sql = "SELECT jobTitle FROM jobPost WHERE jobPostID = '$jobPostID'";
    $result = mysqli_query($con, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $job = mysqli_fetch_assoc($result);
    } else {
        echo "Job posting not found.";
        exit;
    }
} else {
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
    <link rel="shortcut icon" href="../images/FlexMatch Logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
        }
        .card {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-danger {
            background-color: #e74a3b;
            border-color: #e74a3b;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card p-4">
        <h3 class="text-center text-danger">Confirmation </h3>
        <p class="text-center">Are you sure you want to delete the following job posting?</p>
        <div class="alert alert-warning text-center">
            <strong>Job Title: </strong><?php echo htmlspecialchars($job['jobTitle']); ?>
        </div>
        <p class="text-center">This action cannot be undone.</p>

        <!-- Confirm deletion form -->
        <div class="text-center mt-4">
            <form action="confirm_delete.php" method="POST">
                <input type="hidden" name="jobPostID" value="<?php echo $jobPostID; ?>">
                <button type="submit" class="btn btn-danger btn-lg mx-3">Delete</button>
                <a href="job_posting_list.php" class="btn btn-secondary btn-lg mx-3">Cancel</a>
            </form>
        </div>
    </div>
</div>

</body>
</html>
