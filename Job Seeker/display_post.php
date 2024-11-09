<?php
    include('../database/config.php');

    // Query to retrieve wall posts ordered by publish date (newest first)
    $result = $conn->query("SELECT * FROM wall_posts ORDER BY created_at DESC");

    if ($result->num_rows > 0) {
        echo '<div class="post-grid">';

        while ($row = $result->fetch_assoc()) {
            // Display each post
            echo '<div class="post">';
            
            // User name as the title and publish date/time
            echo '<h3>' . htmlspecialchars($row['user_name']) . '</h3>';
            echo '<p>Published on: ' . htmlspecialchars(date("F j, Y, g:i a", strtotime($row['created_at']))) . '</p>';
            
            // Display job-seeking post details
            echo '<p><strong>Skills:</strong> ' . htmlspecialchars($row['skill_category']) . '</p>';
            echo '<p><strong>Details:</strong> ' . htmlspecialchars($row['skill_details']) . '</p>';
            
            // Decode JSON availability data
            $availability = json_decode($row['availability'], true);

            // Display only filled availability times
            echo '<p><strong>Availability:</strong></p>';
            echo '<ul>';

            // Check each day in the decoded availability array
            foreach ($availability as $day => $times) {
                // Check if both start and end times are filled for that day
                if (!empty($times[0]) && !empty($times[1])) {
                    echo '<li>' . ucfirst($day) . ': ' . htmlspecialchars($times[0]) . ' - ' . htmlspecialchars($times[1]) . '</li>';
                }
            }

            echo '</ul>';
            
            // Display location
            echo '<p><strong>Location:</strong> ' . htmlspecialchars($row['district']) . ', ' . htmlspecialchars($row['state']) . '</p>';
            
            // Contact details
            echo '<p><strong>Email:</strong> ' . htmlspecialchars($row['contact_email']) . '</p>';
            echo '<p><strong>Phone:</strong> ' . htmlspecialchars($row['contact_phone']) . '</p>';
            
            echo '</div>';
        }
       
        echo '</div>';
    } else {
        echo '<p>No posts available.</p>';
    }

    $conn->close();



