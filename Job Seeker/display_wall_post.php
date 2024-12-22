<?php
include('../database/config.php');

// Get search criteria from URL parameters
$keyword = isset($_GET['keyword']) ? $con->real_escape_string($_GET['keyword']) : '';
// Filters
$skillCategories = isset($_GET['skillCategory']) ? $_GET['skillCategory'] : [];
$locations = isset($_GET['location']) ? $_GET['location'] : [];
$availableTimes = isset($_GET['availableTime']) ? $_GET['availableTime'] : [];


// Set the current page number (default is 1)
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$postsPerPage = 10; // Display 10 posts per page
$offset = ($page - 1) * $postsPerPage;

// Base SQL query
$sql = "SELECT wp.*, js.fullName, js.contactNo, js.email
        FROM wallPost wp
        INNER JOIN jobSeeker js ON wp.userID = js.userID
        WHERE 1=1";

// Add filter for keyword search
if (!empty($keyword)) {
    $sql .= " AND (wp.skillCategory LIKE '%$keyword%' 
                 OR wp.skillDetails LIKE '%$keyword%' 
                 OR wp.jobPreferences LIKE '%$keyword%')";
}
// Add filter for skill categories
if (!empty($skillCategories)) {
    $skills = implode("','", array_map([$con, 'real_escape_string'], $skillCategories));
    $sql .= " AND wp.skillCategory IN ('$skills')";
}

// Add filter for location
if (!empty($locations)) {
    $locs = implode("','", array_map([$con, 'real_escape_string'], $locations));
    $sql .= " AND wp.state IN ('$locs')";
}

// Add filter for available time
if (!empty($availableTimes)) {
    foreach ($availableTimes as $day) {
        $escapedDay = $con->real_escape_string($day);

        // Check if the day exists and the times are not empty
        $sql .= " AND JSON_UNQUOTE(JSON_EXTRACT(wp.availableTime, '$.\"$escapedDay\"[0]')) != '' 
                  AND JSON_UNQUOTE(JSON_EXTRACT(wp.availableTime, '$.\"$escapedDay\"[1]')) != ''";
    }
}

// Add ordering and pagination
$sql .= " ORDER BY wp.createdAt DESC LIMIT $postsPerPage OFFSET $offset";

$result = $con->query($sql);

if ($result->num_rows > 0) {
    // Display each post
    while ($row = $result->fetch_assoc()) {
        echo '<div class="post-container">';
        $availability = json_decode($row['availableTime'], true);

        echo '<div class="user-info-post">';
        echo '<div class="picture">';
        echo '<a href="view_jobseeker_profile.php?userID=' . $row['userID'] . '"><img src="../images/JobSeeker.png" alt="Profile Picture" class="profile-pic"></a>';
        echo '</div>';
        echo '<div class="name">';
        echo '<a href="view_jobseeker_profile.php?userID=' . $row['userID'] . '"><h3>' . htmlspecialchars($row['fullName']) . '</h3></a>';
        echo '</div>';
        echo '</div>';

        // Display post details
        echo '<div class="post">';
        echo '<p><strong>Skills:</strong> ' . htmlspecialchars($row['skillCategory']) . '</p>';
        echo '<p><strong>Details:</strong></p>';
        echo '<div class="skill-details" style="min-height: 3em;">';
        echo '<p>' . htmlspecialchars($row['skillDetails']) . '</p>';
        echo '</div>';
        echo '<hr/>';
        echo '<div class="availability" style="min-height: 10em; margin-bottom: 10px; margin-top: 5px;">';
        echo '<p><strong>Available Time:</strong></p>';
        echo '<ul>';
        foreach ($availability as $day => $times) {
            if (!empty($times[0]) && !empty($times[1])) {
                $start = date("g:i a", strtotime($times[0]));
                $end = date("g:i a", strtotime($times[1]));
                echo '<li>' . ucfirst($day) . ': ' . htmlspecialchars($start) . ' - ' . htmlspecialchars($end) . '</li>';
            }
        }
        echo '</ul>';
        echo '</div>';
        echo '<p><strong>Location:</strong> ' . htmlspecialchars($row['district']) . ', ' . htmlspecialchars($row['state']) . '</p>';
        echo '<p><strong>Job Preferences:</strong></p>';
        echo '<div class="preference" style="min-height: 3em;">';
        echo '<p>' . htmlspecialchars($row['jobPreferences']) . '</p>';
        echo '</div>';
        echo '<div class="contact-info" style="display:flex; justify-content:space-between;">';
        echo '<p><strong>Phone:</strong> ' . htmlspecialchars($row['contactNo']) . '</p>';
        echo '<p><strong>Email:</strong> ' . htmlspecialchars($row['email']) . '</p>';
        echo '</div>';
        echo '<p class="published-time" style="text-align: center; font-size: 0.9em; color: #555;">Published on: ' . htmlspecialchars(date("F j, Y, g:i a", strtotime($row['createdAt']))) . '</p>';
        echo '</div>';
        echo '<button class="chat-btn">Chat</button>';
        echo '</div>';
    }

    // Pagination logic
    $sqlTotalPosts = "SELECT COUNT(*) AS total FROM wallPost wp WHERE 1=1";
    if (!empty($keyword)) {
        $sqlTotalPosts .= " AND (wp.skillCategory LIKE '%$keyword%' 
                          OR wp.skillDetails LIKE '%$keyword%' 
                          OR wp.jobPreferences LIKE '%$keyword%')";
    }

    if (!empty($skillCategories)) {
        $sqlTotalPosts .= " AND wp.skillCategory IN ('$skills')";
    }

if (!empty($locations)) {
    $sqlTotalPosts .= " AND wp.state IN ('$locs')";
}


if (!empty($availableTimes)) {
    foreach ($availableTimes as $day) {
        $escapedDay = $con->real_escape_string($day);

        // Same filter applied to total post query
        $sqlTotalPosts .= " AND JSON_UNQUOTE(JSON_EXTRACT(wp.availableTime, '$.\"$escapedDay\"[0]')) != '' 
                            AND JSON_UNQUOTE(JSON_EXTRACT(wp.availableTime, '$.\"$escapedDay\"[1]')) != ''";
    }
}

    $resultTotalPosts = $con->query($sqlTotalPosts);
    $totalPosts = $resultTotalPosts->fetch_assoc()['total'];
    $totalPages = ceil($totalPosts / $postsPerPage);

 echo '<div class="pagination">';

// Helper function to build query string
function buildQueryString($params) {
    $query = [];
    foreach ($params as $key => $values) {
        if (is_array($values)) {
            foreach ($values as $value) {
                $query[] = urlencode($key) . '[]=' . urlencode($value);
            }
        } else {
            $query[] = urlencode($key) . '=' . urlencode($values);
        }
    }
    return implode('&', $query);
}

// Base parameters
$params = [
    'keyword' => $keyword,
    'skillCategory' => $skillCategories,
    'location' => $locations,
    'availableTime' => $availableTimes
];

// Previous button
if ($page > 1) {
    echo '<a href="job_seeker_wall.php?page=' . ($page - 1) . '&' . buildQueryString($params) . '">Previous</a>';
}

// Page numbers
for ($i = 1; $i <= $totalPages; $i++) {
    if ($i == $page) {
        echo '<a style="background-color: #2357e7; color: #fff;">' . $i . '</a>';
    } else {
        echo '<a href="job_seeker_wall.php?page=' . $i . '&' . buildQueryString($params) . '">' . $i . '</a>';
    }
}

// Next button
if ($page < $totalPages) {
    echo '<a href="job_seeker_wall.php?page=' . ($page + 1) . '&' . buildQueryString($params) . '">Next</a>';
}

echo '</div>';

} else {
    echo '<p>No posts available.</p>';
}

$con->close();