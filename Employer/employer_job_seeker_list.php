<?php
include '../database/config.php';
include 'employerNew.php'; // Include session_start and user verification


// Fetch job seekers from the database
$sql = "SELECT userID, fullName FROM jobseeker WHERE accountStatus = 'Active'"; // Only select active job seekers
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
    <title>Select a Job Seeker to Chat</title>
    <style>
     
        .container {
            margin-left: auto; /* Adjust margin to move the entire content to the right */
            margin-right: auto; /* Ensure the right margin is set to auto for proper alignment */
        }
        h1 {
            text-align: center; /* Center the heading */
            margin-bottom: 20px; /* Add some space below the heading */
        }
        table {
            width: 700px;
            border-collapse: collapse;
            margin-top: 20px;
            margin-left: auto;
            margin-right: auto;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .chat-button {
            padding: 5px 10px;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none; /* Remove underline from links */
        }
        .chat-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Select a Job Seeker to Chat</h1>
        <table>
            <tr>
                <th>Name</th>
                <th>Action</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['fullName']) . "</td>";
                    echo "<td><a href='employer_chat.php?jobSeekerID=" . urlencode($row['userID']) . "' class='chat-button'>Chat</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No job seekers found.</td></tr>";
            }
            ?>
        </table>
    </div>

    <script src="../js/employer_chat.php"></script>
</body>
</html>
