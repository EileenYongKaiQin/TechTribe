<?php
    include('../database/config.php');
    // Set the current page number (default is 1)
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $postsPerPage = 10;  // Display 10 posts per page
    $offset = ($page - 1) * $postsPerPage;
    $sql = "SELECT wp.*, js.fullName, js.contactNo, js.email 
        FROM wallPost wp
        INNER JOIN jobSeeker js ON wp.userID = js.userID
        WHERE wp.userID = '$userID'
        ORDER BY wp.createdAt DESC
        LIMIT $postsPerPage OFFSET $offset";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        // Display each post
        while ($row = $result->fetch_assoc()) {
            echo '<div class="post-container">';
            // Convert times to 12-hour format
            $availability = json_decode($row['availableTime'], true);
            echo '<div class="user-info-post"">';
                // echo '<img src="'. htmlspecialchars($row['profilePic']) . '" alt="Profile Picture" class="profile-pic">';
                echo '<div class="picture">';
                    echo '<a href="view_jobseeker_profile.php?userID=' . $row['userID'] . '">
                    <img src="../images/JobSeeker.png" alt="Profile Picture" class="profile-pic"></a>';
                echo '</div>';
                echo '<div class="name">';
                    echo '<a href="view_jobseeker_profile.php?userID=' . $row['userID'] . '"><h3>' . htmlspecialchars($row['fullName']) . '</h3></a>';
                echo '</div>';
            echo '</div>';

            // Display individual post
            echo '<div class="post">';
            echo '<p><strong>Skills:</strong> ' . htmlspecialchars($row['skillCategory']) . '</p>';
            echo '<p><strong>Details:</strong></p> ';
            echo '<div class="skill-details" style="min-height: 3em;"> ';
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
            
            echo '<p><strong>Job Preferences:</strong></p> ';
            echo '<div class="preference" style="min-height: 3em;"> ';
            echo '<p>' . htmlspecialchars($row['jobPreferences']) . '</p>';
            echo '</div>';

            
            echo '<div class="contact-info" style="display:flex; justify-content:space-between;">';
            echo '<p><strong>Phone:</strong> ' . htmlspecialchars($row['contactNo']) . '</p>';
            echo '<p><strong>Email:</strong> ' . htmlspecialchars($row['email']) . '</p>';
            echo '</div>';
            
            echo '<p class="published-time" style="text-align: center; font-size: 0.9em; color: #555;">Published on: ' . htmlspecialchars(date("F j, Y, g:i a", strtotime($row['createdAt']))) . '</p>';
            
            echo '</div>';
            // Add Chat Button below each post
            echo '<div class="action-button">';
                echo '<button class="edit-btn" onclick="editPost(\'' . htmlspecialchars($row['postID']) . '\')">Edit</button>';
                // Delete button with postID in the URL and confirmation
                echo '<button class="delete-btn" onclick="confirmDelete(\'' . htmlspecialchars($row['postID']) . '\')">Delete</button>';
             echo '</div>';  
            echo '</div>';
        }
        // Pagination: Display "Previous" and "Next" links
        $sqlTotalPosts = "SELECT COUNT(*) AS total FROM wallPost WHERE userID='$userID'";
        $resultTotalPosts = $con->query($sqlTotalPosts);
        $totalPosts = $resultTotalPosts->fetch_assoc()['total'];
        $totalPages = ceil($totalPosts / $postsPerPage);

        echo '<div class="pagination">';
        if ($page > 1) {
            echo '<a href="my_posts.php?page=' . ($page - 1) . '">Previous</a>';
        }
        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $page) {
                echo '<a style="background-color: #2357e7; color: #fff;">' . $i . '</a>'; // Highlight current page
            } else {
                echo '<a href="my_posts.php?page=' . $i . '">' . $i . '</a>';
            }
        }
        if ($page < $totalPages) {
            echo '<a href="my_posts.php?page=' . ($page + 1) . '">Next</a>';
        }
        echo '</div>';
    } else {
        echo '<p>No posts available.</p>';
    }

    $con->close();
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

function editPost(postID) {
    // Redirect to the edit page with the postID
    window.location.href = 'edit_wall_post.php?postID=' + postID;
}

function confirmDelete(postID) {
    Swal.fire({
        title: 'Are you sure you want to delete this post?',
        text: "you won't be able to revert this",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirect to the delete script with the postID
            window.location.href = 'delete_wall_post.php?postID=' + postID;
        }
    });
}
</script>



