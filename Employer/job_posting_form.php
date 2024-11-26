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
        // Query the minimum missing number
            $sql_find_id = "
            SELECT MIN(t1.id + 1) AS missingID
            FROM (
                SELECT CAST(SUBSTRING(jobPostID, 3) AS UNSIGNED) AS id
                FROM jobPost
            ) t1
            WHERE NOT EXISTS (
                SELECT 1
                FROM (
                    SELECT CAST(SUBSTRING(jobPostID, 3) AS UNSIGNED) AS id
                    FROM jobPost
                ) t2
                WHERE t2.id = t1.id + 1
            )
            ";

            $result = mysqli_query($con, $sql_find_id);
            $row = mysqli_fetch_assoc($result);

            // Find the smallest missing ID or start from 1
            $missingID = $row['missingID'] ?? 1;

            // Check if all IDs are consecutive, if so, take the largest ID + 1
            $sql_max_id = "SELECT MAX(CAST(SUBSTRING(jobPostID, 3) AS UNSIGNED)) AS maxID FROM jobPost";
            $result_max = mysqli_query($con, $sql_max_id);
            $row_max = mysqli_fetch_assoc($result_max);
            $maxID = $row_max['maxID'] ?? 0;

            if ($missingID > $maxID) {
            $missingID = $maxID + 1;
            }

            $nextJobPostID = 'JP' . str_pad($missingID, 3, '0', STR_PAD_LEFT);

            echo "下一个 jobPostID: " . $nextJobPostID;


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
        <!-- Success Message -->
        <?php if ($formSubmitted): ?>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    document.querySelector('.modal').style.display = 'block';
                    document.querySelector('.modal-overlay').style.display = 'block';
                });
            </script>
        <?php else: ?>
            <h2>Create Job Posting</h2>
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
                        <option value="Malacca">Malacca</option>
                        <option value="Negeri Sembilan">Negeri Sembilan</option>
                        <option value="Selangor">Selangor</option>
                        <option value="Pahang">Pahang</option>
                        <option value="Perak">Perak</option>
                        <option value="Kelantan">Kelantan</option>
                        <option value="Terengganu">Terengganu</option>
                        <option value="Penang">Penang</option>
                        <option value="Kedah">Kedah</option>
                        <option value="Perlis">Perlis</option>
                        <option value="Sabah">Sabah</option>
                        <option value="Sarawak">Sarawak</option>
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

    <!-- Modal and Overlay -->
    <div class="modal-overlay"></div>
    <div class="modal">
        <img src="../images/check-one.png" alt="Success" class="modal-icon">
        <h2 class="modal-title">SUCCESS</h2>
        <p class="modal-message">Thank you, job posting has been successfully created.</p>
        <button class="close-button" onclick="closeModal()">Close</button>
    </div>

    <script>
        function closeModal() {
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
