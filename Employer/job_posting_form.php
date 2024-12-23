<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Job Posting</title>
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/jobPosting.css">
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
        $venue = isset($_POST['venue']) ? mysqli_real_escape_string($con, $_POST['venue']) : '';
        $language = isset($_POST['language']) ? mysqli_real_escape_string($con, $_POST['language']) : '';
        $race = isset($_POST['race']) ? mysqli_real_escape_string($con, $_POST['race']) : 'Any';
        $workingTimeStart = isset($_POST['workingTimeStart']) ? mysqli_real_escape_string($con, $_POST['workingTimeStart']) : '';
        $workingTimeEnd = isset($_POST['workingTimeEnd']) ? mysqli_real_escape_string($con, $_POST['workingTimeEnd']) : '';

        $userID = $_SESSION['userID'];

        if (empty($jobTitle) || empty($location) || empty($salary) || empty($jobDescription) || 
            empty($workingHour) || empty($startDate) || empty($endDate) || empty($venue) || 
            empty($workingTimeStart) || empty($workingTimeEnd)) {
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

            echo "next jobPostID: " . $nextJobPostID;


            $sql_insert = "INSERT INTO jobPost (jobPostID, jobTitle, location, salary, startDate, endDate, workingHour, 
                jobDescription, jobRequirement, venue, language, race, workingTimeStart, workingTimeEnd, userID)
                VALUES ('$nextJobPostID', '$jobTitle', '$location', $salary, '$startDate', '$endDate', '$workingHour', 
                '$jobDescription', '$jobRequirement','$venue', '$language', '$race', '$workingTimeStart', '$workingTimeEnd', '$userID')";



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
                <label for="jobTitle">Job Title<span class="required">*</span></label>
                    <input type="text" class="form-control" id="jobTitle" name="jobTitle" required>
                </div>

                <div class="form-group">
                <label for="venue">Venue<span class="required">*</span></label>
                    <input type="text" class="form-control" id="venue" name="venue" required>
                </div>

                <div class="form-group">
                <label for="location">Location<span class="required">*</span></label>
                    <select class="form-control" id="location" name="location" required>
                        <option value="" disabled selected>Select State</option>
                        <option value="Johor">Johor</option>
                        <option value="Malaka">Malaka</option>
                        <option value="Negeri Sembilan">Negeri Sembilan</option>
                        <option value="Selangor">Selangor</option>
                        <option value="Kuala Lumpur">Kuala Lumpur</option>
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
                <label for="salary">Salary per hour (RM)<span class="required">*</span></label>
                    <input type="number" class="form-control" id="salary" name="salary" required>
                </div>

                <div class="form-group">
                <label for="startDate">Start Date<span class="required">*</span></label>
                    <input type="date" class="form-control" id="startDate" name="startDate" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required onchange="updateEndDate()">
                </div>

                <div class="form-group">
                <label for="endDate">End Date<span class="required">*</span></label>
                    <input type="date" class="form-control" id="endDate" name="endDate" min="" required>
                </div>

                <div class="form-group">
                    <label for="working_hour">Working Hour<span class="required">*</span></label>
                    <select class="form-control" id="working_hour" name="working_hour" required>
                        <option value="Day Shift">Day Shift</option>
                        <option value="Night Shift">Night Shift</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="workingTimeStart">Working Time<span class="required">*</span></label>
                    <div class="d-flex align-items-center">
                        <input type="time" class="form-control" id="workingTimeStart" name="workingTimeStart" required onchange="updateEndTimeRange()">
                        <span class="mx-2">to</span>
                        <input type="time" class="form-control" id="workingTimeEnd" name="workingTimeEnd" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="race">Race<span class="required">*</span></label>
                    <select class="form-control" id="race" name="race">
                        <option value="" disabled selected>-- Select Race --</option>
                        <option value="Any">Any</option>
                        <option value="Malay">Malay</option>
                        <option value="Chinese">Chinese</option>
                        <option value="Indian">Indian</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="language">Language</label>
                    <input type="text" class="form-control" id="language" name="language" placeholder="e.g. Malay, English" >
                </div>

                <div class="form-group">
                    <label for="description">Job Description<span class="required">*</span></label>
                    <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label for="requirements">Requirements</label>
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

        function updateEndDate() {
    const startDate = document.getElementById('startDate').value;
    const endDateInput = document.getElementById('endDate');

    if (startDate) {
        const start = new Date(startDate);
        const minEndDate = new Date(start);
        minEndDate.setDate(minEndDate.getDate());

        const year = minEndDate.getFullYear();
        const month = String(minEndDate.getMonth() + 1).padStart(2, '0');
        const day = String(minEndDate.getDate()).padStart(2, '0');
        endDateInput.min = `${year}-${month}-${day}`;
    }
}

function updateEndTimeRange() {
    const startTimeInput = document.getElementById('workingTimeStart');
    const endTimeInput = document.getElementById('workingTimeEnd');
    
    if (!startTimeInput.value) return;

    // Convert start time to minutes since midnight
    const [startHours, startMinutes] = startTimeInput.value.split(':').map(Number);
    const startTotalMinutes = startHours * 60 + startMinutes;
    
    // Calculate max end time (12 hours = 720 minutes after start)
    let maxEndTotalMinutes = startTotalMinutes + 720;
    
    // If max end time exceeds 24 hours (1440 minutes), adjust it
    if (maxEndTotalMinutes > 1440) {
        maxEndTotalMinutes = 1440; // Set to 24:00
    }
    
    // Convert max end time back to HH:mm format
    const maxEndHours = Math.floor(maxEndTotalMinutes / 60);
    const maxEndMinutes = maxEndTotalMinutes % 60;
    const maxEndTimeStr = `${String(maxEndHours).padStart(2, '0')}:${String(maxEndMinutes).padStart(2, '0')}`;
    
    endTimeInput.max = maxEndTimeStr;

    // Check if current end time is valid
    if (endTimeInput.value) {
        const [endHours, endMinutes] = endTimeInput.value.split(':').map(Number);
        const endTotalMinutes = endHours * 60 + endMinutes;
        
        // If current end time is more than 12 hours after start time or beyond 24:00
        if (endTotalMinutes > maxEndTotalMinutes || endTotalMinutes <= startTotalMinutes) {
            endTimeInput.value = maxEndTimeStr;
        }
    }
}

// Add form validation
document.getElementById('jobPostingForm').addEventListener('submit', function (e) {
    const startTimeInput = document.getElementById('workingTimeStart');
    const endTimeInput = document.getElementById('workingTimeEnd');

    if (!startTimeInput.value || !endTimeInput.value) return;

    function convertTo24Hour(timeStr) {
        const [time, modifier] = timeStr.split(' '); 
        let [hours, minutes] = time.split(':').map(Number);

        if (modifier === 'PM' && hours !== 12) {
            hours += 12; 
        } else if (modifier === 'AM' && hours === 12) {
            hours = 0; 
        }

        return hours * 60 + minutes; 
    }

    const startTotalMinutes = convertTo24Hour(startTimeInput.value);
    const endTotalMinutes = convertTo24Hour(endTimeInput.value);

    let adjustedEndTotalMinutes = endTotalMinutes;
    if (endTotalMinutes < startTotalMinutes) {
        adjustedEndTotalMinutes += 24 * 60; 
    }

    const timeDiff = adjustedEndTotalMinutes - startTotalMinutes;

    if (timeDiff <= 0 || timeDiff > 720) {
        e.preventDefault();
        alert('Working time cannot exceed 12 hours.');
    }

});
    </script>
</body>
</html>
<?php
mysqli_close($con);
?>
