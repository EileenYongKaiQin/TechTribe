<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Posts</title>
    <link rel="stylesheet" href="../css/my_post.css">
</head>
<body>
    <?php 
        include('../database/config.php');
        include('jobSeeker1.php');  // Include header and side menu here
    ?>

    <div class="main-content">
        <h1>My Posts</h1>

        <!-- Display posts -->
        <div class="job-seeker-wall">
            <?php include 'display_my_post.php'; ?>
        </div>
    </div>

</body>
</html>
