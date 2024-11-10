<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Job Posting</title>
    <link rel="shortcut icon" href="../images/FlexMatch Logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            color: white; 
            background-color: #333; 
        }

        table {
            color: white;
        }

        table th {
            color: #555; 
            background-color: white; 
        }

        table td {
            color: white; 
        }

        table, th, td {
            border: 1px solid white; 
        }

        table tbody tr:hover {
            background-color: rgba(45, 140, 255, 0.2); 
        }
    </style>
</head>
<body>

    <?php
        include('../database/config.php');
        include('employer.php');

        // SQL query to fetch all job postings
        $sql = "SELECT * FROM job_postings";
        $result = mysqli_query($con, $sql);

        // Check if there are any job postings
        if (mysqli_num_rows($result) > 0) {
            echo '<div class="container mt-5">';
            echo '<h2>Job Postings List</h2>';
            echo '<table class="table table-bordered">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Job Title</th>';
            echo '<th>Company Name</th>';
            echo '<th>Location</th>';
            echo '<th>Salary(RM)</th>';
            echo '<th>Description</th>';
            echo '<th>Created Date</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            // Loop through each job posting and display it
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $row['job_title'] . '</td>';
                echo '<td>' . $row['company_name'] . '</td>';
                echo '<td>' . $row['location'] . '</td>';
                echo '<td>' . $row['salary'] . '</td>';
                echo '<td>' . $row['description'] . '</td>';
                echo '<td>' . $row['created_at'] . '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        } else {
            echo '<div class="container mt-5"><p>No job postings found.</p></div>';
        }
    ?>

    <!-- Button to navigate to job posting form -->
    <div class="container mt-3">
        <a href="job_posting_form.php" class="btn btn-primary btn-block">Create New Job Posting</a>
    </div>

<?php include('../footer/footer.php'); ?>
    
</body>
</html>

<?php
// Close the database connection at the end of the file
mysqli_close($con);
?>
