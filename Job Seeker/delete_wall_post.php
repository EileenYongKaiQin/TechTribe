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
            // Redirect back to the page with a success message
            header('Location: my_posts.php?message=Post deleted successfully');
            exit;
        } else {
            // If there was an error, show a message
            echo 'Error deleting post. Please try again.';
        }
    } else {
        echo 'Invalid post ID format.';
    }
} else {
    echo 'Post ID is missing.';
}
?>
