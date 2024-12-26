<?php
include('../database/config.php');

// Check if the postID is provided
if (isset($_GET['postID'])) {
    $postID = $_GET['postID'];

    // Validate the postID format (e.g., WP0001)
    if (preg_match('/^WP\d{4}$/', $postID)) {
        // Prepare the SQL query to delete the post
        $query = "DELETE FROM wallPost WHERE postID = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $postID); // 's' for string (postID)

        // Execute the query and check if the post was deleted
        if ($stmt->execute()) {
            echo '<script>
                alert("Post deleted successfully!");
                window.location.href = "my_posts.php";
            </script>';
            exit;
        } else {
            // If there was an error, show a message
            echo '<script>
                alert("Failed to delete the post. Please try again.");
                window.history.back();
            </script>';
        }
    } else {
        echo 'Invalid post ID format.';
    }
} else {
    echo 'Post ID is missing.';
}
?>