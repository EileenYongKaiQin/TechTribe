<?php
include('../database/config.php');

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request method.');
}

// Get the post ID from the form
if (!isset($_POST['postID'])) {
    die('Post ID is missing.');
}
$postID = $_POST['postID'];

// Get the input values from the form
$skills = $_POST['skillCategory'];
$skillDetails = $_POST['skillDetails'];
$state = $_POST['state'];
$district = $_POST['district'];
$jobPreferences = $_POST['jobPreferences'];

// Construct the availability JSON
$availableTime = [];
foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day) {
    $start = $_POST["{$day}Start"] ?? '';
    $end = $_POST["{$day}End"] ?? '';
    $availableTime[$day] = [$start, $end];
}
$availableTime_json = json_encode($availableTime);

// Prepare the SQL query for updating the post
$query = "UPDATE wallPost 
          SET skillCategory = ?, skillDetails = ?, availableTime = ?, state = ?, district = ?, jobPreferences = ?
          WHERE postID = ?";
$stmt = $con->prepare($query);
if (!$stmt) {
    die('Failed to prepare statement: ' . $con->error);
}

// Bind the parameters
$stmt->bind_param("sssssss", $skills, $skillDetails, $availableTime_json, $state, $district, $jobPreferences, $postID);

// Execute the query
if ($stmt->execute()) {
    echo '<script>
            alert("Post updated successfully!");
            window.location.href = "my_posts.php";
          </script>';
} else {
    echo '<script>
            alert("Failed to update the post. Please try again.");
            window.history.back();
          </script>';
}

// Close the statement and connection
$stmt->close();
$con->close();
?>
