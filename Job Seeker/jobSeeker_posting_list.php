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
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .main-content {
            display: flex;
            justify-content: center;    
            margin-top: 50px;            
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

        .job-header h3 {
            font-size: 18px;
            color: #18191C;
            font-weight: bold;
            margin-bottom: 10px;      
        }

        .job-details {
            font-size: 14px;
            color: #303030;
            display: flex;
            flex-direction: column;  
            gap: 25px;                    
            margin-bottom: 10px;      
        }

        .date, .location, .salary {
            margin: 0;
        }

        .date, .location {
            padding-left: 50px;      
        }

        .date {
            margin-bottom: -18px;    
            font-weight: bold;   
        }

        .location {
            display: flex;
            align-items: center; 
            margin-bottom: 85px;  
            color: #767F8C;
        }

        .salary {
            color: #767F8C;      
        }

        .view-details-btn {
            padding: 10px 12px;
            background: #FFFFFF;
            border: 1.05121px solid #AAE1DE;
            border-radius: 4.20482px;
            color: #303030;
            font-size: 14px;
            cursor: pointer;
            width: 100%;            
            display: flex;               
            justify-content: center;     
            align-items: center;      
        }

        .apply-btn:hover, .view-details-btn:hover {
            background-color: #AAE1DE;
        }

        .view-details-btn:hover {
            background-color: #8EFAAB;
            color: #FFFFFF;              
        }

        .time-label {
            font-weight: bold;
            padding: 2px 8px;       
            border-radius: 4px;      
            margin-right: 8px; 
        }

        .time-label.day {
            background: #E7F6EA;  
            color: #4CAF50;       
        }

        .time-label.night {
            background: #F1E0FF;  
            color: #9C27B0;            
        }

        .map-pin {
            width: 18.78px;
            height: 18.9px;
            margin-right: 8px; 
            color: #767F8C;   
        }

        .search-bar-container {
            position: absolute;
            width: 969px;
            height: 66px;
            left: calc(50% - 969px / 2 + 6.5px);
            top: 85px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .search-bar {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            background-color: #F7F7F7;
            border-radius: 10px;
            box-sizing: border-box;
            padding: 0 10px;
            position: relative;
        }

        .search-input {
            flex: 1;
            height: 65%;
            border: none;
            background-color: transparent;
            font-size: 18px;
            font-family: 'Poppins', sans-serif;
            color: #808080;
            margin-right: 15px;
            padding: 0 20px;
            outline: none;
        }

        .search-btn {
            width: 95px;
            height: 60%;
            background-color: #AAE1DE;
            color: #FFFFFF;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            margin-right: 35px;
        }

        .search-btn:hover {
            background-color: #80C2B2;
        }

        .search-icon {
            width: 20px;
            height: 21px;
            margin-left: 20px;
        }

        .divider {
            height: 60%;
            width: 2px;
            background: #E0E0E0;
            margin: 0 10px;
        }

        
        .filter-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 160px;
            height: 45px;
            margin-left: 10px; 
            margin-right: -200px; 
            padding: 0 15px; 
            background: #F0FDFF;
            border: 2px solid #A8AAB0;
            border-radius: 10px;
            font-size: 16px;

            color: #A8AAB0;
            cursor: pointer;
            transition: background 0.3s;
        }

        .filter-btn .dropdown-icon {
            margin-left: 8px;
            font-size: 12px;
        }

        .filter-btn:hover {
            background: #F7F7F7;
        }
        
        .top-right-icons {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            flex-direction: column; 
            gap: 10px
        }

        .bookmark-icon, .more-options-icon {
            width: 24px;
            height: 24px;
            cursor: pointer;
        }

        .dropdown-menu {
        position: absolute;
        top: 35px; 
        right: 0;
        width: 200px;
        background: #B6E1E8;
        box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.3), 0px 1px 3px 1px rgba(0, 0, 0, 0.15);
        border-radius: 12px;
        padding: 10px;
        display: none; 
        z-index: 10;
        }

        .dropdown-menu.show {
        display: block;
        }

        .dropdown-item {
        display: flex;
        align-items: center;
        padding: 8px 10px;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s;
        gap: 10px;
        }

        .dropdown-item:hover {
        background-color: #A4D8DF; 
        }

        .dropdown-item img {
        width: 20px;
        height: 20px;
        }

        .dropdown-item span {
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        color: #FFFFFF;
        }

        .job-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        }

        .hide-post-modal {
        position: absolute;
        height: 48px;
        width: 100%;
        background: #F1F1F1;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 15px;
        box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.3), 0px 1px 3px 1px rgba(0, 0, 0, 0.15);
        z-index: 1000;
        }

        .hide-post-text {
        font-family: 'Poppins', sans-serif;
        font-size: 12px;
        font-weight: 700;
        color: #000000;
        }

        .undo-btn {
        width: 64px;
        height: 31px;
        background: #B6E1E8;
        color: #FFFFFF;
        font-family: 'Poppins', sans-serif;
        font-size: 12px;
        font-weight: 700;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.3), 0px 1px 3px 1px rgba(0, 0, 0, 0.15);
        }

        .undo-btn:hover {
        background: #80C2B2;
        }

        .more-options-icon {
            width: 24px;
            height: 24px;
            cursor: pointer;
            transition: transform 0.2s ease, background-color 0.2s ease;
        }

        .more-options-icon:hover {
            transform: scale(1.2); 
            background-color: rgba(0, 0, 0, 0.1); 
            border-radius: 50%; 
        }

        .no-jobs-container {
            display: flex; 
            justify-content: center;
            align-items: center; 
            width: 100%; 
        }

        .no-jobs-container p {
            font-size: 18px; 
            font-weight: bold; 
            color: #767F8C;
        }

    </style>
</head>
<body>
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
    </script>
</body>
</html>
