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
            font-family: 'Arial', sans-serif;
            font-weight: 500;
            background-color: #F0FDFF;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: row;
        }

        .container {
            margin-left: 250px;
            margin-right: auto;
            padding: 40px;
            max-width: 1200px;
            text-align: center;
        }

        h2 {
            text-align: center;
            font-size: 32px;
            font-weight: 700;
            color: #333333;
            margin-bottom: 20px;
            margin-top: -80px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #FFFFFF;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        thead {
            background-color: #F7F6FE;
        }

        thead th {
            text-align: left;
            font-size: 16px;
            font-weight: 600;
            color: #000000;
            padding: 16px;
            border-bottom: 2px solid #EAEAEA;
        }

        tbody tr {
            border-bottom: 1px solid #EAEAEA;
            transition: background 0.3s ease;
        }

        tbody tr:hover {
            background: rgba(45, 140, 255, 0.1);
        }

        tbody td {
            font-size: 14px;
            font-weight: 400;
            color: #4D4D4D;
            padding: 16px;
            text-align: left;
        }

        tbody td:last-child {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            gap: 16px;
        }

        /* Action Buttons Container */
        .action-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .action-buttons .btn {
            display: flex;
            justify-content: center;
            align-items: center;
            border: none;
            background: transparent;
            cursor: pointer;
            padding: 0;
            transition: transform 0.3s ease;
        }

        .action-buttons .btn:hover {
            transform: translateY(-3px);
        }

        .action-buttons .icon {
            width: 24px;
            height: 24px;
            object-fit: contain;
            display: inline-block;
            vertical-align: middle;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .action-buttons .btn:hover .icon {
            transform: translateY(-3px);
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>
    <?php
        include('../database/config.php');
        include('employer1.php');

        $userID = $_SESSION['userID']; // Ensure userID is stored in session upon login

        // Fetch job postings specific to the logged-in user
        $sql = "SELECT jobPostID, jobTitle, workingHour, location, salary, startDate, endDate, venue, language, race, workingTimeStart, workingTimeEnd, createTime 
                FROM jobPost 
                WHERE userID = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $userID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            echo '<div class="container mt-5">';
            echo '<h2>Job Postings List</h2>';
            echo '<table class="table table-bordered">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Job Title</th>';
            echo '<th>Working Hour</th>';
            echo '<th>Location</th>';
            echo '<th>Salary per hour (RM)</th>';
            echo '<th>Start Date</th>';
            echo '<th>End Date</th>';
            echo '<th>Venue</th>';
            echo '<th>Language</th>';
            echo '<th>Race</th>';
            echo '<th>Working Time</th>';
            echo '<th>Action</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
    
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['jobTitle']) . '</td>';
                echo '<td>' . htmlspecialchars($row['workingHour']) . '</td>';
                echo '<td>' . htmlspecialchars($row['location']) . '</td>';
                echo '<td>' . number_format($row['salary'], 2) . '</td>';
                echo '<td>' . htmlspecialchars($row['startDate']) . '</td>';
                echo '<td>' . htmlspecialchars($row['endDate']) . '</td>';
                echo '<td>' . htmlspecialchars($row['venue']) . '</td>';
                echo '<td>' . htmlspecialchars($row['language']) . '</td>';
                echo '<td>' . htmlspecialchars($row['race']) . '</td>';
                echo '<td>' . date("h:i A", strtotime($row['workingTimeStart'])) . ' - ' . date("h:i A", strtotime($row['workingTimeEnd'])) . '</td>';
                echo '<td>
                        <div class="action-buttons">
                            <a href="edit_job_posting.php?jobPostID=' . $row['jobPostID'] . '" class="btn btn-edit">
                                <img src="../images/edit.png" alt="Edit" class="icon">
                            </a>
                            <a href="confirm_delete.php?jobPostID=' . $row['jobPostID'] . '" class="btn btn-delete">
                                <img src="../images/trash.png" alt="Delete" class="icon">
                            </a>
                        </div>
                    </td>';
                echo '</tr>';
            }
    
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        } else {
            echo '<div class="content"><p>No job postings found.</p></div>';
        }

        mysqli_stmt_close($stmt);
        mysqli_close($con);
    ?>
</body>
</html>
