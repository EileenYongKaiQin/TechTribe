<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlexMatch</title>
    <link rel="stylesheet" href="css/manageJob.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/manageJob.js" defer></script>   
</head>
<body>

    <!-- Header -->
    <header>
        <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
        <h1>FlexMatch</h1>
    </header>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <button class="close-btn" onclick="toggleSidebar()">×</button>
        <ul>
            <li>+ Create Job</li>
            <li>Job Application</li>
            <li>Job-Seeker Wall</li>
        </ul>
    </nav>

     <!-- Main content -->
     <div class="content" id="content">
        <h2>APPLY JOB!</h2>
    
    <div class="jobDescription">
      <p>Job title: </p>
      <p>Company name: </p>
      <p>Location: </p>
      <p>Salary: </p>
      <p>Job Description: </p>
      <p>Requirements: </p>
      <p>Responsibilities: </p>
    </div>
    <button id="applyButton">Apply</button>
  </div>

  <?php
        echo '<footer>
            <p>&copy; 2024 Copyright: <b>FlexMatch</b>. All rights reserved.</p>
        </footer>'
    ?>

    <!-- JavaScript for Sidebar Toggle -->
    <script>
        function toggleSidebar() {
            document.body.classList.toggle('sidebar-visible');
        }
    </script>
</body>
</html>
