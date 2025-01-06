<?php
include('../database/config.php'); // Include your database configuration file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $result = $con->query("SELECT MAX(postID) AS maxID FROM wallpost");
    $row = $result->fetch_assoc();
    $lastID = $row['maxID'];

    // Generate the next ID (assuming the format is POST001)
    $nextID = 'WP' . str_pad((int)substr($lastID, 2) + 1, 4, '0', STR_PAD_LEFT);
    

    $userID = $_POST['user_id'];

    // Retrieve form data
    $skillCategory = $_POST['skills'];
    $skillDetails = $_POST['skillDetails'];
    $availability = json_encode([
        'Monday' => [$_POST['mondayStart'], $_POST['mondayEnd']],
        'Tuesday' => [$_POST['tuesdayStart'], $_POST['tuesdayEnd']],
        'Wednesday' => [$_POST['wednesdayStart'], $_POST['wednesdayEnd']],
        'Thursday' => [$_POST['thursdayStart'], $_POST['thursdayEnd']],
        'Friday' => [$_POST['fridayStart'], $_POST['fridayEnd']],
        'Saturday' => [$_POST['saturdayStart'], $_POST['saturdayEnd']],
        'Sunday' => [$_POST['sundayStart'], $_POST['sundayEnd']]
    ]);
    $state = $_POST['state'];
    $district = $_POST['district'];
    $jobPreferences = $_POST['jobPreferences'];

    // Prepare the SQL statement
    $stmt = $con->prepare("INSERT INTO wallpost (postID, skillCategory, skillDetails, availableTime, state, district, jobPreferences, userID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $con->error);
    }

    // Bind parameters to the SQL statement
    $stmt->bind_param("ssssssss", $nextID, $skillCategory, $skillDetails, $availability, $state, $district, $jobPreferences, $userID);


    // Execute the statement and check for errors
    if ($stmt->execute()) {
        echo '<script>
            alert("Post published successfully!");
            window.location.href = "job_seeker_wall.php";
        </script>';
      // Redirect to the Job Seeker Wall page after successful insertion

    } else {
        echo '<script>
            alert("Failed to publish the post. Please try again.");
            window.history.back();
          </script>';
    }

    // Close the statement and connection
    $stmt->close();
    $con->close();
}
?>

