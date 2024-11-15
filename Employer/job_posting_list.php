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
        $sql = "SELECT job_posting_id, job_title, working_hour, location, salary, start_date, end_date, created_at FROM jobPost";
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
                echo '<td>' . htmlspecialchars($row['job_title']) . '</td>';
                echo '<td>' . htmlspecialchars($row['working_hour']) . '</td>';
                echo '<td>' . htmlspecialchars($row['location']) . '</td>';
                echo '<td>' . number_format($row['salary'], 2) . '</td>';
                echo '<td>' . htmlspecialchars($row['start_date']) . '</td>';
                echo '<td>' . htmlspecialchars($row['end_date']) . '</td>';
                echo '<td>' . htmlspecialchars($row['created_at']) . 
                     '<div class="action-buttons" style="float: right;">
                        <a href="edit_job_posting.php?job_posting_id=' . $row['job_posting_id'] . '" class="btn btn-success btn-sm">Edit</a>
                        <a href="confirm_delete.php?job_posting_id=' . $row['job_posting_id'] . '" class="btn btn-danger btn-sm">Delete</a>
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
