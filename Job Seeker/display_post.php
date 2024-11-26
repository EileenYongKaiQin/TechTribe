<?php
include('../database/config.php');

// Set the current page number (default is 1)
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$postsPerPage = 10;  // Display 10 posts per page
$offset = ($page - 1) * $postsPerPage;

// Query to retrieve wall posts along with user data from jobSeeker
$sql = "SELECT wp.*, js.fullName, js.contactNo 
        FROM wallPost wp
        INNER JOIN jobSeeker js ON wp.userID = js.userID
        ORDER BY wp.createdAt DESC
        LIMIT $postsPerPage OFFSET $offset";

$result = $con->query($sql);

if ($result->num_rows > 0) {
    
    
    // Display each post
    while ($row = $result->fetch_assoc()) {
        echo '<div class="post-container">';
        // Convert times to 12-hour format
        $availability = json_decode($row['availableTime'], true);
        echo '<div class="user-info">';
            // echo '<img src="'. htmlspecialchars($row['profilePic']) . '" alt="Profile Picture" class="profile-pic">';
            echo '<img src="../images/employer.png" alt="Profile Picture" class="profile-pic">';
            echo '<h3>' . htmlspecialchars($row['fullName']) . '</h3>';
        echo '</div>';

        // Display individual post
        echo '<div class="post">';
        
        // User name and publish date

        
        
        // Skill and details
        echo '<p><strong>Skills:</strong> ' . htmlspecialchars($row['skillCategory']) . '</p>';
        echo '<p><strong>Details:</strong> ' . htmlspecialchars($row['skillDetails']) . '</p>';
        
        // Display availability times
        echo '<p><strong>Available Time:</strong></p>';
        echo '<ul>';
        foreach ($availability as $day => $times) {
            if (!empty($times[0]) && !empty($times[1])) {
                // Convert 24-hour format to 12-hour format
                $start = date("g:i a", strtotime($times[0]));
                $end = date("g:i a", strtotime($times[1]));
                echo '<li>' . ucfirst($day) . ': ' . htmlspecialchars($start) . ' - ' . htmlspecialchars($end) . '</li>';
            }
        }
        echo '</ul>';

        // Location
        echo '<p><strong>Location:</strong> ' . htmlspecialchars($row['district']) . ', ' . htmlspecialchars($row['state']) . '</p>';
        
        // Contact details from jobSeeker
        echo '<p><strong>Email:</strong> ' . 'example@gmail.com' . '</p>';
        echo '<p><strong>Phone:</strong> ' . htmlspecialchars($row['contactNo']) . '</p><br>';
        
       
        echo '<p class="published-time">Published on: ' . htmlspecialchars(date("F j, Y, g:i a", strtotime($row['createdAt']))) . '</p>';
        echo '</div>'; 
        // Add Chat Button below each post
        echo '<button class="chat-btn">Chat</button>';    
        echo '</div>';
    }



    // Pagination: Display "Previous" and "Next" links
    $sqlTotalPosts = "SELECT COUNT(*) AS total FROM wallPost";
    $resultTotalPosts = $con->query($sqlTotalPosts);
    $totalPosts = $resultTotalPosts->fetch_assoc()['total'];
    $totalPages = ceil($totalPosts / $postsPerPage);

    echo '<div class="pagination">';
    if ($page > 1) {
        echo '<a href="display_post.php?page=' . ($page - 1) . '">Previous</a>';
    }
    if ($page < $totalPages) {
        echo '<a href="display_post.php?page=' . ($page + 1) . '">Next</a>';
    }
    echo '</div>';
} else {
    echo '<p>No posts available.</p>';
}

$con->close();

