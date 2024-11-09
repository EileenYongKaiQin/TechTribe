<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job-Seeker Wall</title>
    <link rel="stylesheet" href="../css/job_seeker_wall.css">
</head>
<body>

    <?php 
        include('../database/config.php');
        include('jobSeeker.php');
    ?>

    <!-- Main content -->
    <div class="content" id="content">
        <h1>Job-Seeker Wall</h1>
        <?php include 'display_post.php'; ?>
    </div>
       
    <div class="button-section">
        <button onclick="window.location.href='create_wall_post.php'" class="create-post-btn">+ Create Post</button>
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
