<?php
    include('../database/config.php');
    include('job_seeker_header.php');

    $sql = "SELECT * FROM jobPost WHERE endDate >= CURDATE()";
    $result = $con->query($sql);
    
    $jobs = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ratingSQL = "SELECT r.rating, r.feedback, js.fullName
                          FROM jobRating r
                          JOIN jobHistory h ON r.historyID = h.historyID
                          JOIN jobSeeker js ON r.userID = js.userID
                          WHERE h.jobPostID = ?";
            $stmt = $con->prepare($ratingSQL);
            $stmt->bind_param("s", $row['jobPostID']);
            $stmt->execute();
            $ratingResult = $stmt->get_result();
            $rating = $ratingResult->fetch_all(MYSQLI_ASSOC);

            $row['rating'] = $rating;
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

        .job-header {
            width: 100%; 
            overflow: hidden; 
        }

        .job-title {
            white-space: nowrap; 
            overflow: hidden; 
            text-overflow: ellipsis;
            display: block; 
            max-width: 310px; 
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
            justify-content: flex-start;
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
            border-top: 1px solid #dcdcdc; 
            margin: 0px 0; 
        }

        .search-input {
            border: none;
            outline: none; 
        }

        
        .search-btn {
            background-color:rgb(211, 211, 211);
        }
        .clear-icon {
            margin-top: 30px;
            margin-right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 25px;
            color: #767F8C;
            display: none;
        }

        .search-bar:hover {
            background-color: #F7F7F7;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .search-btn:hover {
            background-color: #AAE1DE;
            color: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease; 
            cursor: pointer;
        }

        .clear-icon:hover {
            color: #AAE1DE;

            transition: all 0.2s ease;
        }

        .search-input:focus + .clear-icon,
        .search-input:not(:placeholder-shown) + .clear-icon {
            display: inline; 
        }
        

        .filter-btn:hover {
            background-color: #AAE1DE; /* Hover background color */
            color: #FFFFFF; /* Hover text color */
            border-color: #84C5C2; /* Hover border color */
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); /* Add subtle shadow on hover */
            border: none; 
        }

        .filter-btn:active {
            background-color: #84C5C2; /* Active state background */
        
            transform: translateY(0); /* Reset lift effect */
        }

        /* Filter Panel (Hidden by Default) */
        .filter-panel {
            position: fixed;
            top: 0;
            right: 0;
            width: 335px;
            height: 100%;
            background: #F7F7F7;
            border-left: 1.5px solid #F2F2F2;
            border-radius: 10px 0 0 10px;
            padding: 20px;
            box-shadow: -4px 0px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            transform: translateX(100%); /* Initially hidden */
            transition: transform 0.3s ease-in-out;
        }

        .filter-panel.visible {
            transform: translateX(0); /* Slide into view */
        }

        .hidden {
            display: none;
        }

        .filter-content h3 {
            font-size: 18px;
            margin-top: 20px;
            margin-bottom: 15px;
            color: #333333;
        }

        .filter-content input[type="number"] {
            width: 48%;
            padding: 8px;
            margin-right: 4%;
            border: 1px solid #CCCCCC;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .filter-content input[type="number"]:last-child {
            margin-right: 0;
        }

        /* Salary Range Section */
        .salary-range-section {
            position: relative;
            margin-bottom: 20px;
        }

        .salary-range-label {
            font-family: Arial, sans-serif;
            font-style: normal;
            font-weight: 600;
            font-size: 18px;
            line-height: 14px;
            color: #303030;
            margin-bottom: 18px;
            display: block;
        }

        .salary-input-container {
            display: flex;
            gap: 12px; /* Space between inputs */
        }

        .salary-input {
            box-sizing: border-box;
            width: 132px;
            height: 32px;
            background: #FFFFFF;
            border: 1px solid #DDE2E4;
            border-radius: 6px;
            padding: 4px 12px;
            font-family: 'Inter', sans-serif;
            font-style: normal;
            font-weight: 400;
            font-size: 14px;
            line-height: 24px;
            color: #303030;
        }

        .salary-input::placeholder {
            color: #A0A0A0;
        }

        .checkbox-container label {
            display: block;
            margin-bottom: 6px;
            color: #767F8C;
        }

        .apply-filter-btn {
            background: #AAE1DE;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            margin-top: 20px;
            width: 100%;
            text-align: center;
        }

        .apply-filter-btn:hover {
            background: #84C5C2;
        }

        .rating-comment {
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 15px;
            display: block;
        }

        .user-rating {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .anonymous-user-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
            flex-shrink: 0;
            align-self: flex-start;
       
        }

        .rating-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: bold;
            margin: 0;
            color: #333;
        }

        .rating-star {
            margin: 5px 0;
            font-size: 20px;
        }

        .feedback {
            margin-top: 5px;
            color: #666;
        }

        .filled-star, .empty-star{
            width: 20px;
            height: 20px;
        }

        .no-rating-container {
            padding: 10px 0;
            font-size: 14px;
            color: #777;
            text-align: left;
            margin-left: 20px;
        }
        
        .rating-comment:empty {
            display: none;
        }
        

        /* Responsive Design */
        @media (max-width: 600px) {
            .user-rating {
                flex-direction: column;
                align-items: flex-start;
            }

            .anonymous-user-icon {
                margin-bottom: 10px;
            }
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
            <input type="text" name="jobTitle" class="search-input" placeholder="Enter job title" />
            <span class="clear-icon" onclick="clearSearch('jobTitle')">×</span>
            <span class="divider"></span>
            <img src="../images/location.png" alt="Location Icon" class="search-icon" />
            <input type="text" name="location" class="search-input" placeholder="Enter location" />
            <span class="clear-icon" onclick="clearSearch('location')">×</span>
            <button type="submit" class="search-btn">Search</button>
        </div>
        <button class="filter-btn" id="filterBtn">Filter <span class="dropdown-icon">▼</span></button>
        </div>

<!-- Filter Panel -->
<div class="filter-panel" id="filterPanel">
    <div class="filter-content">
    <div class="salary-range-section">
    <label class="salary-range-label" for="salary-range">Salary Range</label>
    <div class="salary-input-container">
        <input type="number" id="minSalary" class="salary-input" placeholder="Min">
        <input type="number" id="maxSalary" class="salary-input" placeholder="Max">
    </div>
</div>

        <hr>
        <h3>Location</h3>
        <div class="checkbox-container">
            <label><input type="checkbox" value="Johor"> Johor</label>
            <label><input type="checkbox" value="Melaka"> Melaka</label>
            <label><input type="checkbox" value="Negeri Sembilan"> Negeri Sembilan</label>
            <label><input type="checkbox" value="Selangor"> Selangor</label>
            <label><input type="checkbox" value="Kuala Lumpur"> Kuala Lumpur</label>
            <label><input type="checkbox" value="Pahang"> Pahang</label>
            <label><input type="checkbox" value="Perak"> Perak</label>
            <label><input type="checkbox" value="Kelantan"> Kelantan</label>
            <label><input type="checkbox" value="Terengganu"> Terengganu</label>
            <label><input type="checkbox" value="Penang"> Penang</label>
            <label><input type="checkbox" value="Kedah"> Kedah</label>
            <label><input type="checkbox" value="Perlis"> Perlis</label>
            <label><input type="checkbox" value="Sabah"> Sabah</label>
            <label><input type="checkbox" value="Sarawak"> Sarawak</label>

        </div>
        <hr>
        <h3>Working Hour</h3>
        <div class="checkbox-container">
            <label><input type="checkbox" value="Day Shift"> Day Shift</label>
            <label><input type="checkbox" value="Night Shift"> Night Shift</label>
        </div>
        <button class="apply-filter-btn" onclick="applyFilters()">Apply Filters</button>
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
                    <h3 class="job-title"><?php echo htmlspecialchars($job['jobTitle']); ?></h3>
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
 
                    <div class="rating-comment">
                        <?php if (!empty($job['rating'])):  ?>
                            <?php foreach ($job['rating'] as $rating): ?>
                                <div class="user-rating">
                                    <img src="../images/anonymous.png" alt="Anonymous User" class="anonymous-user-icon">
                                    <div class="rating-content">
                                        <p class="user-name">
                                            <?php
                                                $name = htmlspecialchars($rating['fullName']);
                                                $formattedName = substr($name, 0, 1).str_repeat('*', strlen($name) - 1);
                                                echo $formattedName;
                                            ?>
                                        </p>
                                        <div class="rating-stars">
                                            <?php
                                             for($i = 1; $i <= 5; $i++) {
                                                if ($i <= $rating['rating']) {
                                                    echo '<img src="../images/rating-star-after.png" alt="Filled Star" class="filled-star">';
                                                } else {
                                                    echo '<img src="../images/rating-star-before.png" alt="Empty Star" class="empty-star">';;
                                                }
                                            }
                                            ?>
                                        </div>
                                        <?php if(!empty($rating['feedback'])): ?>
                                            <p class="feedback">
                                                <?php echo htmlspecialchars($rating['feedback']); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="salary-line"></div>
                                <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="no-rating-container"><p>No ratings yet.</p></div>
                                <?php endif; ?>
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
    <script src="../js/view_jobs.js" data-user-id="<?php echo $userID; ?>"></script>
</body>
</html>
