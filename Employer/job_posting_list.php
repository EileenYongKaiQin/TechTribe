<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Job Posting</title>
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            color: white; 
            background-color: #333; 
        }

        table {
            color: white;
            width: 100%;
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

        .action-buttons {
            display: inline-flex;
            gap: 10px;
        }

        .action-buttons .btn {
            width: auto;
        }
    </style>
</head>
<body>

    <?php
        include('../database/config.php');
        include('employer.php');

        
        // Fetch all job postings
        $sql = "SELECT jobPostID, jobTitle, workingHour, location, salary, startDate, endDate, createTime FROM jobPost";

        $result = mysqli_query($con, $sql);


        if (mysqli_num_rows($result) > 0) {
            echo '<div class="container mt-5">';
            echo '<h2>Job Postings List</h2>';
            echo '<table class="table table-bordered">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Job Title</th>';
            echo '<th>Working Hour</th>';
            echo '<th>Location</th>';
            echo '<th>Salary per hour(RM)</th>';
            echo '<th>Start Date</th>';
            echo '<th>End Date</th>';
            echo '<th>Created Date</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['jobTitle']) . '</td>'; // Use 'jobTitle'
                echo '<td>' . htmlspecialchars($row['workingHour']) . '</td>'; // Use 'workingHour'
                echo '<td>' . htmlspecialchars($row['location']) . '</td>'; // Use 'location'
                echo '<td>' . number_format($row['salary'], 2) . '</td>'; // Use 'salary'
                echo '<td>' . htmlspecialchars($row['startDate']) . '</td>'; // Use 'startDate'
                echo '<td>' . htmlspecialchars($row['endDate']) . '</td>'; // Use 'endDate'
                echo '<td>' . htmlspecialchars($row['createTime']) . // Use 'createTime'
                     '<div class="action-buttons" style="float: right;">
                        <a href="edit_job_posting.php?jobPostID=' . $row['jobPostID'] . '" class="btn btn-success btn-sm">Edit</a>
                        <a href="confirm_delete.php?jobPostID=' . $row['jobPostID'] . '" class="btn btn-danger btn-sm">Delete</a>
                     </div></td>';
                echo '</tr>';
            }
            

            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        } else {
            echo '<div class="container mt-5"><p>No job postings found.</p></div>';
        }
    ?>

    <div class="container mt-3">
        <a href="job_posting_form.php" class="btn btn-primary btn-block">Create New Job Posting</a>
    </div>

    <?php include('../footer/footer.php'); ?>

</body>
</html>

<?php
mysqli_close($con);
?>
