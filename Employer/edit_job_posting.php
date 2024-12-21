<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job Posting</title>
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/jobPosting.css">
    <style>
        .required {
            color: red;
        }
    </style>
</head>

<body>

    <?php
    include('../database/config.php');
    include('employer1.php'); 

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
        $venue = mysqli_real_escape_string($con, $_POST['venue']);
        $language = mysqli_real_escape_string($con, $_POST['language']);
        $race = mysqli_real_escape_string($con, $_POST['race']);
        $workingTimeStart = mysqli_real_escape_string($con, $_POST['workingTimeStart']);
        $workingTimeEnd = mysqli_real_escape_string($con, $_POST['workingTimeEnd']);
        
        if (
            empty($jobTitle) || empty($location) || empty($salary) || empty($jobDescription) ||
            empty($workingHour) || empty($startDate) || empty($endDate) || empty($venue) ||
            empty($workingTimeStart) || empty($workingTimeEnd)
        ) {
            $errorMessage = "All required fields must be filled!";
        } else {
            $sql = "UPDATE jobPost 
                    SET jobTitle = '$jobTitle', 
                        location = '$location', 
                        salary = $salary, 
                        jobDescription = '$jobDescription', 
                        jobRequirement = '$jobRequirement', 
                        workingHour = '$workingHour', 
                        startDate = '$startDate', 
                        endDate = '$endDate',
                        venue = '$venue',
                        language = '$language',
                        race = '$race',
                        workingTimeStart = '$workingTimeStart',
                        workingTimeEnd = '$workingTimeEnd' 
                    WHERE jobPostID = '$jobPostID'";

            if (mysqli_query($con, $sql)) {
                $successMessage = "Job posting updated successfully!";
            } else {
                $errorMessage = "Error updating record: " . mysqli_error($con);
            }
        }
    }

    if (!empty($jobPostID)) {
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
                    <label for="jobTitle">Job Title: <span class="required">*</span></label>
                    <input type="text" class="form-control" id="jobTitle" name="jobTitle"
                        required value="<?php echo htmlspecialchars($job['jobTitle'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="venue">Venue: <span class="required">*</span></label>
                    <input type="text" class="form-control" id="venue" name="venue"
                        required value="<?php echo htmlspecialchars($job['venue'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="location">Location: <span class="required">*</span></label>
                    <select class="form-control" id="location" name="location" required>
                        <option value="" disabled>Select State</option>
                        <?php
                        $locations = ['Johor', 'Malaka', 'Negeri Sembilan', 'Selangor', 'Kuala Lumpur', 'Pahang', 'Perak', 
                                    'Kelantan', 'Terengganu', 'Penang', 'Kedah', 'Perlis', 'Sabah', 'Sarawak'];
                        foreach ($locations as $location) {
                            $selected = ($job['location'] === $location) ? 'selected' : '';
                            echo "<option value='$location' $selected>$location</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="salary">Salary per hour (RM): <span class="required">*</span></label>
                    <input type="number" class="form-control" id="salary" name="salary" step="0.01"
                        required value="<?php echo htmlspecialchars($job['salary'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="startDate">Start Date: <span class="required">*</span></label>
                    <input type="date" class="form-control" id="startDate" name="startDate"
                        min="<?php echo date('Y-m-d', strtotime('+1 days')); ?>" required 
                        value="<?php echo htmlspecialchars($job['startDate'] ?? ''); ?>" onchange="updateEndDate();">
                </div>

                <div class="form-group">
                    <label for="endDate">End Date: <span class="required">*</span></label>
                    <input type="date" class="form-control" id="endDate" name="endDate" required 
                        min="<?php echo htmlspecialchars($job['startDate'] ?? date('Y-m-d', strtotime('+3 days'))); ?>"
                        value="<?php echo htmlspecialchars($job['endDate'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="workingHour">Working Hour: <span class="required">*</span></label>
                    <select class="form-control" id="workingHour" name="workingHour" required>
                        <option value="Day Shift" <?php echo ($job['workingHour'] === 'Day Shift') ? 'selected' : ''; ?>>Day Shift</option>
                        <option value="Night Shift" <?php echo ($job['workingHour'] === 'Night Shift') ? 'selected' : ''; ?>>Night Shift</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="workingTimeStart">Working Time: <span class="required">*</span></label>
                    <div class="d-flex align-items-center">
                        <input type="time" class="form-control" id="workingTimeStart" name="workingTimeStart"
                            required value="<?php echo htmlspecialchars($job['workingTimeStart'] ?? ''); ?>"
                            onchange="updateEndTimeRange();">
                        <span class="mx-2">to</span>
                        <input type="time" class="form-control" id="workingTimeEnd" name="workingTimeEnd"
                            required value="<?php echo htmlspecialchars($job['workingTimeEnd'] ?? ''); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="race">Race: <span class="required">*</span></label>
                    <select class="form-control" id="race" name="race" required>
                        <option value="Any" <?php echo ($job['race'] === 'Any') ? 'selected' : ''; ?>>Any</option>
                        <option value="Malay" <?php echo ($job['race'] === 'Malay') ? 'selected' : ''; ?>>Malay</option>
                        <option value="Chinese" <?php echo ($job['race'] === 'Chinese') ? 'selected' : ''; ?>>Chinese</option>
                        <option value="Indian" <?php echo ($job['race'] === 'Indian') ? 'selected' : ''; ?>>Indian</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="language">Language:</label>
                    <input type="text" class="form-control" id="language" name="language" placeholder="e.g. Malay, English"
                        value="<?php echo htmlspecialchars($job['language'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="jobDescription">Job Description: <span class="required">*</span></label>
                    <textarea class="form-control" id="jobDescription" name="jobDescription" rows="4" required><?php echo htmlspecialchars($job['jobDescription'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="jobRequirement">Requirements:</label>
                    <textarea class="form-control" id="jobRequirement" name="jobRequirement" rows="3"><?php echo htmlspecialchars($job['jobRequirements'] ?? ''); ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
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

        // When the start date changes, dynamically set the minimum selectable date of the end date.
        function updateEndDate() {
        const startDate = document.getElementById('startDate').value;
        const endDateInput = document.getElementById('endDate');

        if (startDate) {
            const start = new Date(startDate);
            const minEndDate = new Date(start);
            minEndDate.setDate(minEndDate.getDate());

            // Set end date's minimum value
            const year = minEndDate.getFullYear();
            const month = String(minEndDate.getMonth() + 1).padStart(2, '0');
            const day = String(minEndDate.getDate()).padStart(2, '0');
            endDateInput.min = `${year}-${month}-${day}`;
        }
    }

    // Limit workingTime to a maximum of 12 hours
    function validateWorkingHours() {
        const startTimeStr = document.getElementById('workingTimeStart').value;
        const endTimeStr = document.getElementById('workingTimeEnd').value;

        if (!startTimeStr || !endTimeStr) {
            return true;
        }

        const [startHours, startMinutes] = startTimeStr.split(':').map(num => parseInt(num));
        const [endHours, endMinutes] = endTimeStr.split(':').map(num => parseInt(num));

        const startTime = new Date();
        startTime.setHours(startHours, startMinutes, 0, 0);

        const endTime = new Date();
        endTime.setHours(endHours, endMinutes, 0, 0);

        if (endTime <= startTime) {
            alert('Working time cannot exceed 12 hours.');
            return false;
        }

        const diffMilliseconds = endTime - startTime;
        const diffHours = diffMilliseconds / (1000 * 60 * 60);

        if (diffHours > 12) {
            alert('Working time cannot exceed 12 hours.');
            return false;
        }

        return true;
    }

    // Attach the validate function to the form's submit event
    document.querySelector('form').onsubmit = function(event) {
        if (!validateWorkingHours()) {
            event.preventDefault(); // Prevent the form submission
        }
    };

    // When the working time is updated, we will check the time limit
    function updateEndTimeRange() {
        const startTimeStr = document.getElementById('workingTimeStart').value;
        const endTimeInput = document.getElementById('workingTimeEnd');

        if (!startTimeStr) return;

        const [startHours, startMinutes] = startTimeStr.split(':');
        const startTime = new Date();
        startTime.setHours(parseInt(startHours), parseInt(startMinutes));

        const maxEndTime = new Date(startTime.getTime());
        maxEndTime.setHours(startTime.getHours() + 12); // Set max end time to 12 hours after start time

        const maxEndHours = String(maxEndTime.getHours()).padStart(2, '0');
        const maxEndMinutes = String(maxEndTime.getMinutes()).padStart(2, '0');
        const maxEndTimeStr = `${maxEndHours}:${maxEndMinutes}`;

        endTimeInput.max = maxEndTimeStr;

        if (endTimeInput.value > maxEndTimeStr) {
            endTimeInput.value = maxEndTimeStr;
        }
    }
    </script>
</body>
</html>

<?php
mysqli_close($con);
?>