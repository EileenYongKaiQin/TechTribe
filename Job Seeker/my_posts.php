<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Posts</title>
    <link rel="stylesheet" href="../css/my_post.css">
</head>

<?php 
        include('../database/config.php');
        include('jobSeeker.php');
    ?>

    <!-- Main content -->
    <div class="content" id="content">
        <h1>My Posts</h1>
        <?php include 'display_my_post.php'; ?>
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
