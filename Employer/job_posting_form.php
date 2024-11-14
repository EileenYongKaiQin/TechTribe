<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Job Posting</title>
    <link rel="shortcut icon" href="../images/FlexMatch Logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/job_posting.css">
</head>
<body>

    <?php 
        include('../database/config.php');
        include('employer.php');

        $formSubmitted = false;
        $errorMessage = '';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $job_title = isset($_POST['jobTitle']) ? mysqli_real_escape_string($con, $_POST['jobTitle']) : '';
            $location = isset($_POST['location']) ? mysqli_real_escape_string($con, $_POST['location']) : '';
            $salary = isset($_POST['salary']) && is_numeric($_POST['salary']) ? floatval($_POST['salary']) : 0;
            $description = isset($_POST['description']) ? mysqli_real_escape_string($con, $_POST['description']) : '';
            $requirements = isset($_POST['requirements']) ? mysqli_real_escape_string($con, $_POST['requirements']) : '';
            $job_type = isset($_POST['jobType']) ? mysqli_real_escape_string($con, $_POST['jobType']) : '';
            $start_date = isset($_POST['startDate']) ? mysqli_real_escape_string($con, $_POST['startDate']) : '';
            $end_date = isset($_POST['endDate']) ? mysqli_real_escape_string($con, $_POST['endDate']) : '';

            // Example employer ID for testing
            $employer_id = 'E001';

            if (empty($job_title) || empty($location) || empty($salary) || empty($description) || empty($job_type) || empty($start_date) || empty($end_date)) {
                $errorMessage = "All required fields must be filled!";
            } else {
                // Store into the database
                $sql = "INSERT INTO jobPost (employer_id, job_title, location, salary, description, requirements, job_type, start_date, end_date) 
                        VALUES ('$employer_id', '$job_title', '$location', '$salary', '$description', '$requirements', '$job_type', '$start_date', '$end_date')";

                if (mysqli_query($con, $sql)) {
                    $formSubmitted = true; 
                } else {
                    $errorMessage = "Error: " . mysqli_error($con);
                }
            }
        }
    ?>

    <div class="container mt-5">
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
                    <label for="salary">Salary per hour(RM):</label>
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
                    <label for="jobType">Job Type:</label>
                    <select class="form-control" id="jobType" name="jobType" required>
                        <option value="" disabled selected>Select Job Type</option>
                        <option value="Event Work">Event Crew</option>
                        <option value="Weekend Work">Weekend Job</option>
                        <option value="Seasonal">Promoter</option>
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

                <button type="submit" class="btn btn-primary btn-block">Create Job Posting</button>
            </form>
        <?php endif; ?>

        <!-- Decorative with Image -->
        <div class="text-center mt-5">
            <img src="../images/partTimeJob.jpg" alt="Job Search" class="img-fluid">
        </div>
    </div>
    
    <?php include('../footer/footer.php'); ?>
</body>
</html>

<?php
mysqli_close($con);
?>
