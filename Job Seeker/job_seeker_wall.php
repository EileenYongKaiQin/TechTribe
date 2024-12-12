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
        include('jobSeeker1.php');  // Include header and side menu here
    ?>

    <div class="main-content">
        <h1>Job Seeker Wall</h1>
        <!-- Search Bar -->
        <div class="search-bar-container">
                <form method="GET" action="job_seeker_wall.php" class="search-bar">
                    <img src="../images/Search.png" alt="Search Icon" class="search-icon" />
                    <input type="text" name="keyword" class="search-input" placeholder="Search post" value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>" />
                    <button type="submit" class="search-btn">Search</button>
                    <?php if (!empty($_GET['keyword'])): ?>
                        <button 
                            type="button" 
                            class="clear-btn" 
                            onclick="window.location.href='job_seeker_wall.php';">
                            ✖
                        </button>
                    <?php endif; ?>
                </form>
                <button class="filter-btn">Filter <span class="dropdown-icon">▼</span></button>
        </div>

        <!-- Display posts -->
        <div class="job-seeker-wall">
            <?php include 'display_post.php'; ?>
        </div>
    </div>

    <!-- Create Post Button Section -->
    <div class="button-section">
        <button onclick="window.location.href='create_wall_post.php'" class="create-post-btn">Create Post</button>
    </div>

</body>
</html>

