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
        
        // Flag to determine if the form was successfully submitted
        $formSubmitted = false;
        $errorMessage = '';

        // Check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
            // Check if the fields are set and sanitize input
            $job_title = isset($_POST['jobTitle']) ? mysqli_real_escape_string($con, $_POST['jobTitle']) : '';
            $company_name = isset($_POST['companyName']) ? mysqli_real_escape_string($con, $_POST['companyName']) : '';
            $location = isset($_POST['location']) ? mysqli_real_escape_string($con, $_POST['location']) : '';
            $salary = isset($_POST['salary']) && is_numeric($_POST['salary']) ? floatval($_POST['salary']) : 0;
            $description = isset($_POST['description']) ? mysqli_real_escape_string($con, $_POST['description']) : '';
            $requirements = isset($_POST['requirements']) ? mysqli_real_escape_string($con, $_POST['requirements']) : '';
            $responsibilities = isset($_POST['responsibilities']) ? mysqli_real_escape_string($con, $_POST['responsibilities']) : '';
        
            // Replace with actual employer ID based on logged-in user (e.g., $_SESSION['employer_id'])
            $employer_id = 'E001'; // Placeholder: Update with actual session or dynamic value
        
            // Validate required fields
            if (empty($job_title) || empty($company_name) || empty($location) || empty($salary) || empty($description)) {
                $errorMessage = "All required fields must be filled!";
            } else {
                // SQL query to insert the job posting into the database
                $sql = "INSERT INTO job_postings (employer_id, job_title, company_name, location, salary, description, requirements, responsibilities) 
                        VALUES ('$employer_id', '$job_title', '$company_name', '$location', '$salary', '$description', '$requirements', '$responsibilities')";
        
                // Execute query and check for success
                if (mysqli_query($con, $sql)) {
                    $formSubmitted = true; // Set flag to true when form submission is successful
                } else {
                    $errorMessage = "Error: " . mysqli_error($con);
                }
            }
        }
    ?>

    <div class="container mt-5">
        <h2>Create Job Posting</h2>

        <!-- Success Message Display (Only when form is successfully submitted) -->
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
            <!-- Form to create a new job posting -->
            <form id="jobPostingForm" action="job_posting_form.php" method="post">
                <div class="form-group">
                    <label for="jobTitle">Job Title:</label>
                    <input type="text" class="form-control" id="jobTitle" name="jobTitle" required>
                </div>
        
                <div class="form-group">
                    <label for="companyName">Company Name:</label>
                    <input type="text" class="form-control" id="companyName" name="companyName" required>
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
                    <label for="salary">Salary(RM):</label>
                    <input type="number" class="form-control" id="salary" name="salary" required>
                </div>
        
                <div class="form-group">
                    <label for="description">Job Description:</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                </div>
        
                <div class="form-group">
                    <label for="requirements">Requirements:</label>
                    <textarea class="form-control" id="requirements" name="requirements" rows="3"></textarea>
                </div>
        
                <div class="form-group">
                    <label for="responsibilities">Responsibilities:</label>
                    <textarea class="form-control" id="responsibilities" name="responsibilities" rows="3"></textarea>
                </div>
        
                <button type="submit" class="btn btn-primary btn-block">Create Job Posting</button>
            </form>
        <?php endif; ?>
        
        <!-- Decorative Image -->
        <div class="text-center mt-5">
            <img src="../images/partTimeJob.jpg" alt="Job Search" class="img-fluid">
        </div>
    </div>
    
    <?php include('../footer/footer.php'); ?>
</body>
</html>

<?php
// Close the database connection at the end of the file
mysqli_close($con);
?>
