<?php
    include('../database/config.php');
    include('jobSeeker1.php');

    $sql = "SELECT * FROM jobPost WHERE endDate >= CURDATE()";
    $result = $con->query($sql);
    
    $jobs = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $jobs[] = $row;
        }
    }
    
    $con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal</title>
    <link rel="stylesheet" href="../css/view_job.css">
</head>
<body>
    <!-- Search Bar -->
    <div class="search-bar-container">
        <div class="search-bar">
            <img src="../images/Search.png" alt="Searvh Icon" class="search-icon" />
            <input type="text" class="search-input" placeholder="Enter job title or location" />
            <button class="search-btn">Search</button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="rectangle">
            <?php
            // Check if there are any jobs to display
            if (count($jobs) > 0) {
                foreach ($jobs as $job) {
            ?>

        <div class="job-card" data-id="<?php echo $job['jobPostID']; ?>">
            <div class="top-right-icons">
                <img src="../images/BookmarkSimple.png" alt="Bookmark" class="bookmark-icon">
                <img src="../images/more_vert.png" alt="More Options" class="more-options-icon" onclick="toggleDropdown(this)">
                <div class="dropdown-menu">
                    <div class="dropdown-item" onclick="navigateToReport('<?php echo $job['jobPostID']; ?>')">
                        <img src="../images/sms_failed.png" alt="Report Icon">
                        <span>Report Post</span>
                    </div>
                    <div class="dropdown-item" onclick="hidePost(this)">
                        <img src="../images/cancel_presentation.png" alt="Hide Icon">
                        <span>Hide Post</span>
                    </div>
                </div>
            </div>

                <div class="job-header">
                    <h3><?php echo htmlspecialchars($job['jobTitle']); ?></h3>
                </div>
                <div class="job-details">
                    <p class="salary">
                        <span class="time-label <?php echo ($job['workingHour'] === 'Day Shift') ? 'day' : 'night'; ?>">
                            <?php echo htmlspecialchars($job['workingHour']); ?>
                        </span>
                        Salary: RM <?php echo number_format($job['salary'], 2); ?> / hour
                    </p>
                    <p class="date">
                        <?php 
                            $startDate = date("d/m/Y", strtotime($job['startDate']));
                            $endDate = date("d/m/Y", strtotime($job['endDate']));
                            echo $startDate . " - " . $endDate;
                        ?>
                    </p>
                    <p class="location">
                        <img src="../images/MapPin.png" alt="Location Icon" class="map-pin"> <?php echo htmlspecialchars($job['location']); ?>
                    </p>

                    <div class="job-details-buttons">
                        <button class="view-details-btn" onclick="location.href='manageJob.php?jobPostID=<?php echo $job['jobPostID']; ?>'">View Details</button>

                    </div>
                </div>
            </div>

            <?php
                }
            } else {
                echo "<p>No jobs found.</p>";
            }
            ?>
        </div>
    </div>

    <script>
    function toggleDropdown(element) {
        const dropdownMenu = element.nextElementSibling;
        dropdownMenu.classList.toggle('show');
    }

    function navigateToReport(jobPostID) {
        window.location.href = `report_form.php?jobPostID=${jobPostID}`;
    }

    function hidePost(element) {
        const jobCard = element.closest('.job-card');
        const jobContainer = jobCard.parentElement;

        const placeholder = document.createElement('div');
        placeholder.classList.add('job-placeholder');
        placeholder.dataset.hiddenJobCardId = jobCard.dataset.id;

        placeholder.style.height = `${jobCard.offsetHeight}px`;
        placeholder.style.width = `${jobCard.offsetWidth}px`;
        placeholder.style.margin = window.getComputedStyle(jobCard).margin;

        const modal = document.createElement('div');
        modal.classList.add('hide-post-modal');
        modal.innerHTML = `
            <span class="hide-post-text">Hiding posts helps us find the suitable jobs for you</span>
            <button class="undo-btn" onclick="showPost('${jobCard.dataset.id}')">Undo</button>
        `;
        placeholder.appendChild(modal);

        jobCard.style.display = 'none';
        jobContainer.insertBefore(placeholder, jobCard);
    }

    function showPost(hiddenJobCardId) {
        const placeholder = document.querySelector(`.job-placeholder[data-hidden-job-card-id='${hiddenJobCardId}']`);

        if (placeholder) {
            const jobCard = document.querySelector(`.job-card[data-id='${hiddenJobCardId}']`);
            if (jobCard) {
                jobCard.style.display = 'block';
            }
            placeholder.remove();
        }
    }

    document.addEventListener('click', function (event) {
        const dropdowns = document.querySelectorAll('.dropdown-menu');
        dropdowns.forEach((dropdown) => {
            if (!dropdown.contains(event.target) && !event.target.matches('.more-options-icon')) {
                dropdown.classList.remove('show');
            }
        });
    });
    </script>
</body>
</html>
