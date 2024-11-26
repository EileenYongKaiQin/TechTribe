<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job Posting</title>
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/jobPosting.css">
</head>

<body>

    <?php
    include('../database/config.php');
    include('employerNew.php'); 

    $errorMessage = '';
    $successMessage = '';
    $jobPostID = isset($_GET['jobPostID']) ? mysqli_real_escape_string($con, $_GET['jobPostID']) : '';
    $job = null;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $jobPostID = mysqli_real_escape_string($con, $_POST['jobPostID']);
        $jobTitle = mysqli_real_escape_string($con, $_POST['jobTitle']);
        $location = mysqli_real_escape_string($con, $_POST['location']);
        $salary = floatval($_POST['salary']);
        $jobDescription = mysqli_real_escape_string($con, $_POST['jobDescription']);
        $jobRequirement = mysqli_real_escape_string($con, $_POST['jobRequirement']);
        $workingHour = mysqli_real_escape_string($con, $_POST['workingHour']);
        $startDate = mysqli_real_escape_string($con, $_POST['startDate']);
        $endDate = mysqli_real_escape_string($con, $_POST['endDate']);
        
        if (empty($jobTitle) || empty($location) || empty($salary) || empty($jobDescription) || empty($workingHour) || empty($startDate) || empty($endDate)) {
            $errorMessage = "All fields marked with * are required.";
        } else {
            $sql = "UPDATE jobPost 
                    SET jobTitle = '$jobTitle', 
                        location = '$location', 
                        salary = $salary, 
                        jobDescription = '$jobDescription', 
                        jobRequirement = '$jobRequirement', 
                        workingHour = '$workingHour', 
                        startDate = '$startDate', 
                        endDate = '$endDate' 
                    WHERE jobPostID = '$jobPostID'";

            if (mysqli_query($con, $sql)) {
                $successMessage = "Job posting updated successfully!";
            } else {
                $errorMessage = "Error updating record: " . mysqli_error($con);
            }
        }
    }

    if (!empty($jobPostID)) {
        // Retrieve existing job data
        $sql = "SELECT * FROM jobPost WHERE jobPostID = '$jobPostID'";
        $result = mysqli_query($con, $sql);
        $job = mysqli_fetch_assoc($result);

        if (!$job) {
            $errorMessage = "Job posting not found.";
        }
    }
    ?>

    <div class="content">
        <!-- Success Message -->
        <?php if ($successMessage): ?>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    document.querySelector('.modal').style.display = 'block';
                    document.querySelector('.modal-overlay').style.display = 'block';
                });
            </script>
        <?php else: ?>

        <h2>Edit Job Posting</h2>
            <!-- Edit Job Posting Form -->
            <form action="edit_job_posting.php" method="post">
                <input type="hidden" name="jobPostID" value="<?php echo htmlspecialchars($job['jobPostID'] ?? ''); ?>">

                <div class="form-group">
                    <label for="jobTitle">Job Title:</label>
                    <input type="text" class="form-control" id="jobTitle" name="jobTitle" value="<?php echo htmlspecialchars($job['jobTitle'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="location">Location:</label>
                    <select class="form-control" id="location" name="location" required>
                        <option value="" disabled>Select State</option>
                        <option value="Johor" <?php echo ($job['location'] == 'Johor') ? 'selected' : ''; ?>>Johor</option>
                        <option value="Malacca" <?php echo ($job['location'] == 'Malacca') ? 'selected' : ''; ?>>Malacca</option>
                        <option value="Negeri Sembilan" <?php echo ($job['location'] == 'Negeri Sembilan') ? 'selected' : ''; ?>>Negeri Sembilan</option>
                        <option value="Selangor" <?php echo ($job['location'] == 'Selangor') ? 'selected' : ''; ?>>Selangor</option>
                        <option value="Pahang" <?php echo ($job['location'] == 'Pahang') ? 'selected' : ''; ?>>Pahang</option>
                        <option value="Perak" <?php echo ($job['location'] == 'Perak') ? 'selected' : ''; ?>>Perak</option>
                        <option value="Kelantan" <?php echo ($job['location'] == 'Kelantan') ? 'selected' : ''; ?>>Kelantan</option>
                        <option value="Terengganu" <?php echo ($job['location'] == 'Terengganu') ? 'selected' : ''; ?>>Terengganu</option>
                        <option value="Penang" <?php echo ($job['location'] == 'Penang') ? 'selected' : ''; ?>>Penang</option>
                        <option value="Kedah" <?php echo ($job['location'] == 'Kedah') ? 'selected' : ''; ?>>Kedah</option>
                        <option value="Perlis" <?php echo ($job['location'] == 'Perlis') ? 'selected' : ''; ?>>Perlis</option>
                        <option value="Sabah" <?php echo ($job['location'] == 'Sabah') ? 'selected' : ''; ?>>Sabah</option>
                        <option value="Sarawak" <?php echo ($job['location'] == 'Sarawak') ? 'selected' : ''; ?>>Sarawak</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="salary">Salary (RM):</label>
                    <input type="number" class="form-control" id="salary" name="salary" value="<?php echo number_format($job['salary'] ?? 0, 2); ?>" required>
                </div>

                <div class="form-group">
                    <label for="startDate">Start Date:</label>
                    <input type="date" class="form-control" id="startDate" name="startDate" value="<?php echo htmlspecialchars($job['startDate'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="endDate">End Date:</label>
                    <input type="date" class="form-control" id="endDate" name="endDate" value="<?php echo htmlspecialchars($job['endDate'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="workingHour">Working Hour:</label>
                    <select class="form-control" id="workingHour" name="workingHour" required>
                        <option value="Day Shift" <?php echo ($job['workingHour'] == 'Day Shift') ? 'selected' : ''; ?>>Day Shift</option>
                        <option value="Night Shift" <?php echo ($job['workingHour'] == 'Night Shift') ? 'selected' : ''; ?>>Night Shift</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="jobDescription">Job Description:</label>
                    <textarea class="form-control" id="jobDescription" name="jobDescription" rows="4" required><?php echo htmlspecialchars($job['jobDescription'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="jobRequirement">Job Requirement:</label>
                    <textarea class="form-control" id="jobRequirement" name="jobRequirement" rows="3"><?php echo htmlspecialchars($job['jobRequirement'] ?? ''); ?></textarea>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    <button type="submit">Save Change</button>
                </div>
            </form>

        
        <?php endif; ?>
    </div>

    <!-- Modal and Overlay -->
    <div class="modal-overlay"></div>
    <div class="modal">
        <img src="../images/check-one.png" alt="Success" class="modal-icon">
        <h2 class="modal-title">SUCCESS</h2>
        <p class="modal-message">Job posting has been successfully updated.</p>
        <button class="close-button" onclick="redirectToList()">Close</button>
    </div>

    <script>
        function redirectToList() {
            document.querySelector('.modal').style.display = 'none';
            document.querySelector('.modal-overlay').style.display = 'none';
            window.location.href = 'job_posting_list.php';
        }
    </script>

</body>
</html>

<?php
mysqli_close($con);
?>