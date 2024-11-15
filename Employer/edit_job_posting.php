<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job Posting</title>
    <link rel="shortcut icon" href="../images/FlexMatch Logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/job_posting.css">
</head>
<body>

<?php
include('../database/config.php');
include('employer.php'); 

$errorMessage = '';
$successMessage = '';
$jobPostingId = isset($_GET['job_posting_id']) ? intval($_GET['job_posting_id']) : 0;
$job = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jobPostingId = intval($_POST['job_posting_id']);
    $job_title = mysqli_real_escape_string($con, $_POST['jobTitle']);
    $location = mysqli_real_escape_string($con, $_POST['location']);
    $salary = floatval($_POST['salary']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $requirements = mysqli_real_escape_string($con, $_POST['requirements']);
    $working_hour = mysqli_real_escape_string($con, $_POST['working_hour']);
    $start_date = mysqli_real_escape_string($con, $_POST['startDate']);
    $end_date = mysqli_real_escape_string($con, $_POST['endDate']);
    
    if (empty($job_title) || empty($location) || empty($salary) || empty($description) || empty($working_hour) || empty($start_date) || empty($end_date)) {
        $errorMessage = "All fields marked with * are required.";
    } else {
        $sql = "UPDATE jobPost 
                SET job_title = '$job_title', 
                    location = '$location', 
                    salary = $salary, 
                    description = '$description', 
                    requirements = '$requirements', 
                    working_hour = '$working_hour', 
                    start_date = '$start_date', 
                    end_date = '$end_date' 
                WHERE job_posting_id = $jobPostingId";

        if (mysqli_query($con, $sql)) {
            $successMessage = "Job posting updated successfully!";
        } else {
            $errorMessage = "Error updating record: " . mysqli_error($con);
        }
    }
}

if ($jobPostingId > 0) {
    // Retrieve existing job data
    $sql = "SELECT * FROM jobPost WHERE job_posting_id = $jobPostingId";
    $result = mysqli_query($con, $sql);
    $job = mysqli_fetch_assoc($result);

    if (!$job) {
        $errorMessage = "Job posting not found.";
    }
}
?>

<div class="container mt-5">
    <h2>Edit Job Posting</h2>

    <?php if ($successMessage): ?>
        <div class="alert alert-success">
            <?php echo $successMessage; ?>
        </div>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
        <div class="alert alert-danger">
            <?php echo $errorMessage; ?>
        </div>
    <?php elseif ($job): ?>
        <!-- Edit Job Posting Form -->
        <form action="edit_job_posting.php" method="post">
            <input type="hidden" name="job_posting_id" value="<?php echo $jobPostingId; ?>">

            <div class="form-group">
                <label for="jobTitle">Job Title:</label>
                <input type="text" class="form-control" id="jobTitle" name="jobTitle" value="<?php echo htmlspecialchars($job['job_title'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="location">Location:</label>
                <select class="form-control" id="location" name="location" required>
                    <option value="" disabled>Select State</option>
                    <option value="Johor" <?php echo ($job['location'] == 'Johor') ? 'selected' : ''; ?>>Johor</option>
                    <option value="Kedah" <?php echo ($job['location'] == 'Kedah') ? 'selected' : ''; ?>>Kedah</option>
                    <option value="Kedah" <?php echo ($job['location'] == 'Kelantan') ? 'selected' : ''; ?>>Kelantan</option>
                    <option value="Kedah" <?php echo ($job['location'] == 'Kuala Lumpur') ? 'selected' : ''; ?>>Kuala Lumpur</option>
                    <option value="Kedah" <?php echo ($job['location'] == 'Labuan') ? 'selected' : ''; ?>>Labuan</option>
                    <option value="Kedah" <?php echo ($job['location'] == 'Malacca') ? 'selected' : ''; ?>>Malacca</option>
                    <option value="Kedah" <?php echo ($job['location'] == 'Negeri Sembilan') ? 'selected' : ''; ?>>Negeri Sembilan</option>
                    <option value="Kedah" <?php echo ($job['location'] == 'Pahang') ? 'selected' : ''; ?>>Pahang</option>
                    <option value="Kedah" <?php echo ($job['location'] == 'Penang') ? 'selected' : ''; ?>>Penang</option>
                    <option value="Kedah" <?php echo ($job['location'] == 'Perak') ? 'selected' : ''; ?>>Perak</option>
                    <option value="Kedah" <?php echo ($job['location'] == 'Perlis') ? 'selected' : ''; ?>>Perlis</option>
                    <option value="Kedah" <?php echo ($job['location'] == 'Putrajaya') ? 'selected' : ''; ?>>Putrajaya</option>
                    <option value="Kedah" <?php echo ($job['location'] == 'Sabah') ? 'selected' : ''; ?>>Sabah</option>
                    <option value="Kedah" <?php echo ($job['location'] == 'Sarawak') ? 'selected' : ''; ?>>Sarawak</option>
                    <option value="Kedah" <?php echo ($job['location'] == 'Selangor') ? 'selected' : ''; ?>>Selangor</option>
                    <option value="Kedah" <?php echo ($job['location'] == 'Terengganu') ? 'selected' : ''; ?>>Terengganu</option>
                </select>
            </div>

            <div class="form-group">
                <label for="salary">Salary per hour (RM):</label>
                <input type="number" class="form-control" id="salary" name="salary" value="<?php echo number_format($job['salary'] ?? 0, 2); ?>" required>
            </div>

            <div class="form-group">
                <label for="startDate">Start Date:</label>
                <input type="date" class="form-control" id="startDate" name="startDate" value="<?php echo htmlspecialchars($job['start_date'] ?? ''); ?>" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
            </div>

            <div class="form-group">
                <label for="endDate">End Date:</label>
                <input type="date" class="form-control" id="endDate" name="endDate" value="<?php echo htmlspecialchars($job['end_date'] ?? ''); ?>" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
            </div>

            <div class="form-group">
                <label for="working_hour">Working Hour:</label>
                <select class="form-control" id="working_hour" name="working_hour" required>
                    <option value="Day Shift" <?php echo ($job['working_hour'] == 'Day Shift') ? 'selected' : ''; ?>>Day Shift</option>
                    <option value="Night Shift" <?php echo ($job['working_hour'] == 'Night Shift') ? 'selected' : ''; ?>>Night Shift</option>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Job Description:</label>
                <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($job['description'] ?? ''); ?></textarea>
            </div>

            <div class="form-group">
                <label for="requirements">Requirements:</label>
                <textarea class="form-control" id="requirements" name="requirements" rows="3"><?php echo htmlspecialchars($job['requirements'] ?? ''); ?></textarea>
            </div>

            <div class="d-flex justify-content-center mt-4">
                <a href="job_posting_list.php" class="btn btn-primary mx-2">Back</a>
                <button type="submit" class="btn btn-success mx-2">Save Changes</button>
            </div>
        </form>
    <?php else: ?>
        <div class="alert alert-info">Please select a valid job posting to edit.</div>
    <?php endif; ?>
</div>

<?php
mysqli_close($con);
?>

</body>
</html>
