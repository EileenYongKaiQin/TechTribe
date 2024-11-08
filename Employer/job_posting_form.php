<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlexMatch - Create Job Posting</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/job_posting.css">
</head>
<body>
    <?php 
        include('../database/config.php');
        include('employer.php');
    ?>

    <!-- Main Container -->
    <div class="content" id="content">
        <form id="jobPostingForm" action="#" method="post">
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
                <input type="text" class="form-control" id="location" name="location" required>
            </div>

            <div class="form-group">
                <label for="salary">Salary:</label>
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

            <button type="submit" class="button">Create Job Posting</button>
        </form>

        <!-- Success Message -->
        <div id="successMessage" class="alert alert-success mt-3" style="display: none;">
            Job posting created successfully!
        </div>

        <!-- Decorative Image -->
        <div class="text-center mt-5">
            <img src="../images/partTimeJob.jpg" alt="Job Search" class="img-fluid">
        </div>
    </div>

    <?php include('../footer/footer.php'); ?>

</body>
</html>
