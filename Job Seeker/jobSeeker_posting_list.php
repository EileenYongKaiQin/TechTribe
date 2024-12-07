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
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
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
            font-weight: bold;   */
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

        /* Time Label (Day/Night) */
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

        /* Search Bar */
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
            height: 65%;
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
            height: 20px;
            margin-left: 20px;
        }
    </style>
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
                // Loop through each job and display the job details dynamically
                foreach ($jobs as $job) {
            ?>

            <div class="job-card">
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

                    <!-- Wrap buttons in a container for horizontal alignment -->
                    <div class="job-details-buttons">
                        <button class="view-details-btn" onclick="location.href='manageJob.php?jobPostID=<?php echo $job['jobPostID']; ?>'">View Details</button>

                    </div>
                </div>
            </div>

            <?php
                }
            } else {
                // If no jobs are found, display a message
                echo "<p>No jobs found.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
