<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "flexmatch_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }
    echo "Connected successfully";
    
    $sql = "INSERT INTO job_application_status (status) VALUES ('Pending')";
    $conn->query($sql);
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlexMatch</title>
    <link rel="stylesheet" href="../css/manageJob.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/manageJob.js" defer></script>   
</head>
<body>
    <?php 
        include('../database/config.php');
        include('jobSeeker.php'); 
    ?>
     <!-- Main content -->
     <div class="content" id="content">
        <h2>APPLY JOB!</h2>
    
    <div class="jobDescription">
      <p>Job title: </p>
      <p>Location: </p>
      <p>Salary: </p>
      <p>Job Description: </p>
      <p>Requirements: </p>
      <p>Responsibilities: </p>
    </div>
    <button id="applyButton">Apply</button>
  </div>

  <?php include('../footer/footer.php'); ?>

    <!-- JavaScript for Sidebar Toggle -->
    <script>
        function toggleSidebar() {
            document.body.classList.toggle('sidebar-visible');
        }
    </script>
</body>
</html>
