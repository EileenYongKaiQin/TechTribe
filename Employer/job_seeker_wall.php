<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job-Seeker Wall</title>
    <link rel="stylesheet" href="../css/job_seeker_wall.css">
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
</head>
<body>

    <?php 
        include('../database/config.php');
        include('employerNew.php');  // Include header and side menu here
    ?>

    <div class="main-content">
        <h1>Job Seeker Wall</h1>
        <!-- Search and Filter Bar -->
        <div class="search-filter-bar">
            <input type="text" placeholder="Enter Job title">
            <input type="text" placeholder="Enter location">
            <button>Search</button>
            <button>Filter</button>
        </div>

        <!-- Display posts -->
        <div class="job-seeker-wall">
            <?php include 'display_wall_post.php'; ?>
        </div>
    </div>

</body>
</html>
