<?php
    include('../database/config.php');
    $userID="JS001";
    $sql = "SELECT wp.*, js.fullName, js.contactNo 
        FROM wallPost wp
        INNER JOIN jobSeeker js ON wp.userID = js.userID
        WHERE wp.userID = '$userID'
        ORDER BY wp.createdAt DESC";
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
            echo '<div class="action-button">';
                echo '<button class="edit-btn" onclick="editPost(\'' . htmlspecialchars($row['postID']) . '\')">Edit</button>';
                // Delete button with postID in the URL and confirmation
                echo '<button class="delete-btn" onclick="confirmDelete(\'' . htmlspecialchars($row['postID']) . '\')">Delete</button>';
             echo '</div>';  
            echo '</div>';
        }
    
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



