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
    <link rel="shortcut icon" href="../images/FlexMatchLogo.png" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="../css/view_job.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .main-content {
            display: flex;
            justify-content: center;    
            margin-top: 60px;            
            padding: 20px;
        }

        .rectangle {
            background: #FFFFFF;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.25);
            border-radius: 20px;        
            display: flex;
            flex-wrap: wrap;           
            justify-content: space-between; 
            width: 75%;                 
            max-width: 1000px;      
            padding: 30px 50px;
            
        }
        
        .job-card {
            width: 45%; 
            position: relative;                
            background: #F0FDFF;
            border: 1px solid #EFE2F8;
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px; 
            margin-bottom: 30px;         
            box-sizing: border-box;  
            display: flex;
            flex-direction: column;     
            justify-content: space-between;
        }
        
        .search-bar-container {
                    top: 105px;
        }

        .date, .salary, .location, .time {
                margin: 0;
            }

            .date, .salary, .location {
                padding-left: 50px;      
            }

            .date {
                margin-bottom: -18px;    
                font-weight: bold;   
            }

            .salary { 
                margin-top: -5px;
                margin-bottom: 25px;   
                font-weight: bold;   
                color: #333333;
            }

            .location {
                display: flex;
                align-items: center; 
                margin-bottom: 5px;  
                color: #767F8C;
            }

            .time {
                color: #767F8C;      
            }

            .salary-line {
                border-top: 1px solid #dcdcdc; /* Light grey line */
                margin: 0px 0; /* Adjust vertical spacing */
            }
    </style>
</head>
<body>
<script>
    const userID = "<?php echo $_SESSION['userID']; ?>";
</script>
    <!-- Search Bar -->
    <div class="search-bar-container">
        <div class="search-bar">
            <img src="../images/Search.png" alt="Search Icon" class="search-icon" />
            <input type="text" class="search-input" placeholder="Enter job title" />
            <span class="divider"></span>
            <img src="../images/location.png" alt="Location Icon" class="search-icon" />
            <input type="text" class="search-input" placeholder="Enter location" />
            <button class="search-btn">Search</button>
        </div>
        <button class="filter-btn">Filter <span class="dropdown-icon">▼</span></button>
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
                    <img src="../images/BookmarkSimple.png" alt="Bookmark" class="bookmark-icon" onclick="saveJob('<?php echo $job['jobPostID']; ?>', this)">
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
                    <!-- Working Hours -->
                    <p class="time">
                        <span class="time-label <?php echo ($job['workingHour'] === 'Day Shift') ? 'day' : 'night'; ?>">
                            <?php echo htmlspecialchars($job['workingHour']); ?>
                        </span>
                        <?php 
                            $workingTimeStart = date("h:i A", strtotime($job['workingTimeStart']));
                            $workingTimeEnd = date("h:i A", strtotime($job['workingTimeEnd']));
                            echo $workingTimeStart . " - " . $workingTimeEnd;
                        ?>
                    </p>

                    <!-- Date -->
                    <p class="date">
                        <?php 
                            $startDate = date("d/m/Y", strtotime($job['startDate']));
                            $endDate = date("d/m/Y", strtotime($job['endDate']));
                            echo $startDate . " - " . $endDate;
                        ?>
                    </p>

                    <!-- Venue -->
                    <p class="location">
                        <img src="../images/MapPin.png" alt="Location Icon" class="map-pin"> 
                        <?php echo htmlspecialchars($job['venue']); ?>, <?php echo htmlspecialchars($job['location']); ?>
                    </p>

                    <div class="salary-line"></div>
                    <!-- Salary -->
                    <p class="salary">
                        Salary: RM <?php echo number_format($job['salary'], 2); ?> / hour
                    </p>

                    <div class="job-details-buttons">
                        <button class="view-details-btn" onclick="location.href='manageJob.php?jobPostID=<?php echo $job['jobPostID']; ?>'">View Details</button>
                    </div>
                </div>
            </div>

            <?php
                }
            } else {
                echo "<div class='no-jobs-container'><p>No jobs found.</p></div>";
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

        // Set the initial icon state when the page loads
        document.addEventListener("DOMContentLoaded", function () {
            const jobCards = document.querySelectorAll(".job-card");

            // Collect all jobPostIDs
            const jobPostIDs = Array.from(jobCards).map(card => card.getAttribute("data-id"));

            fetch("get_saved_jobs.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ jobPostIDs, userID }), 
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const savedJobs = data.savedJobs; 
                    jobCards.forEach(card => {
                        const jobPostID = card.getAttribute("data-id");
                        const iconElement = card.querySelector(".bookmark-icon");
                        iconElement.src = savedJobs.includes(jobPostID)
                            ? "../images/Saved.png" 
                            : "../images/BookmarkSimple.png";
                    });
                } else {
                    console.error("Failed to fetch saved jobs:", data.error);
                }
            })
            .catch(error => console.error("Error:", error));
        });

        //Save or unsave function
        function saveJob(jobPostID, iconElement) {
        // Determine the current state: Saved (saved) or BookmarkSimple (unsaved)
        const isSaved = iconElement.src.includes("Saved.png");

        // Set the request URL and updated icon
        const url = isSaved ? "unsave_job.php" : "save_job.php";
        const newIcon = isSaved ? "../images/BookmarkSimple.png" : "../images/Saved.png";

        fetch(url, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ jobPostID, userID }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update icon status
                    iconElement.src = newIcon;
                } else {
                    
                }
            })
            .catch(error => {
                console.error("Error:", error);
                showNotification("Error occurred while processing the request.");
            });
    }

</script>
</body>
</html>
