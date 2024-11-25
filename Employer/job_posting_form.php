<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Job Posting</title>
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/jobPosting.css">

</head>
<body>

<?php
include('../database/config.php');
include('employerNew.php');

$formSubmitted = false;
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jobTitle = isset($_POST['jobTitle']) ? mysqli_real_escape_string($con, $_POST['jobTitle']) : '';
    $location = isset($_POST['location']) ? mysqli_real_escape_string($con, $_POST['location']) : '';
    $salary = isset($_POST['salary']) && is_numeric($_POST['salary']) ? floatval($_POST['salary']) : 0;
    $jobDescription = isset($_POST['description']) ? mysqli_real_escape_string($con, $_POST['description']) : '';
    $jobRequirement = isset($_POST['requirements']) ? mysqli_real_escape_string($con, $_POST['requirements']) : '';
    $workingHour = isset($_POST['working_hour']) ? mysqli_real_escape_string($con, $_POST['working_hour']) : '';
    $startDate = isset($_POST['startDate']) ? mysqli_real_escape_string($con, $_POST['startDate']) : '';
    $endDate = isset($_POST['endDate']) ? mysqli_real_escape_string($con, $_POST['endDate']) : '';

    // Example employer ID for testing
    $userID = 'EP001';

    if (empty($jobTitle) || empty($location) || empty($salary) || empty($jobDescription) || empty($workingHour) || empty($startDate) || empty($endDate)) {
        $errorMessage = "All required fields must be filled!";
    } else {
        // Generate the next jobPostID
        $sql_find_id = "SELECT MAX(CAST(SUBSTRING(jobPostID, 3) AS UNSIGNED)) AS maxID FROM jobPost";
        $result = mysqli_query($con, $sql_find_id);
        $row = mysqli_fetch_assoc($result);
        $maxID = $row['maxID'] ?? 0;
        $nextJobPostID = 'JP' . str_pad($maxID + 1, 3, '0', STR_PAD_LEFT);

        // Insert the job posting
        $sql_insert = "INSERT INTO jobPost (jobPostID, jobTitle, location, salary, startDate, endDate, workingHour, jobDescription, jobRequirement, userID)
                       VALUES ('$nextJobPostID', '$jobTitle', '$location', $salary, '$startDate', '$endDate', '$workingHour', '$jobDescription', '$jobRequirement', '$userID')";

        if (mysqli_query($con, $sql_insert)) {
            $formSubmitted = true;
        } else {
            $errorMessage = "Error: " . mysqli_error($con);
        }
    }
}
?>

<div class="content">
    <h2>Create Job Posting</h2>

    <!-- Success Message -->
    <?php if ($formSubmitted): ?>
        <div class="alert alert-success">
            <strong>Success!</strong> Job posting created successfully.
        </div>
        <form action="job_posting_list.php" method="post">
            <button type="submit" class="btn btn-primary btn-block">View Posted Job List</button>
        </form>
    <?php elseif ($errorMessage): ?>
        <div class="alert alert-danger">
            <strong>Error!</strong> <?php echo $errorMessage; ?>
        </div>
    <?php else: ?>
        <!-- Job Posting Form -->
        <form id="jobPostingForm" action="job_posting_form.php" method="post">
            <div class="form-group">
                <label for="jobTitle">Job Title:</label>
                <input type="text" class="form-control" id="jobTitle" name="jobTitle" required>
            </div>

            <div class="form-group">
                <label for="location">Location:</label>
                <select class="form-control" id="location" name="location" required>
                    <option value="" disabled selected>Select State</option>
                    <option value="Johor">Johor</option>
                    <option value="Kedah">Kedah</option>
                    <option value="Kelantan">Kelantan</option>
                    <option value="Kuala Lumpur">Kuala Lumpur</option>
                    <option value="Labuan">Labuan</option>
                    <option value="Malacca">Malacca</option>
                    <option value="Negeri Sembilan">Negeri Sembilan</option>
                    <option value="Pahang">Pahang</option>
                    <option value="Penang">Penang</option>
                    <option value="Perak">Perak</option>
                    <option value="Perlis">Perlis</option>
                    <option value="Putrajaya">Putrajaya</option>
                    <option value="Sabah">Sabah</option>
                    <option value="Sarawak">Sarawak</option>
                    <option value="Selangor">Selangor</option>
                    <option value="Terengganu">Terengganu</option>
                </select>
            </div>

            <div class="form-group">
                <label for="salary">Salary per hour (RM):</label>
                <input type="number" class="form-control" id="salary" name="salary" required>
            </div>

            <div class="form-group">
                <label for="startDate">Start Date:</label>
                <input type="date" class="form-control" id="startDate" name="startDate" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
            </div>

            <div class="form-group">
                <label for="endDate">End Date:</label>
                <input type="date" class="form-control" id="endDate" name="endDate" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
            </div>

            <div class="form-group">
                <label for="working_hour">Working Hour:</label>
                <select class="form-control" id="working_hour" name="working_hour" required>
                    <option value="Day Shift">Day Shift</option>
                    <option value="Night Shift">Night Shift</option>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Job Description:</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="requirements">Requirements:</label>
                <textarea class="form-control" id="requirements" name="requirements" rows="3"></textarea>
            </div>
            <button type="submit">Submit</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
mysqli_close($con);
?>
