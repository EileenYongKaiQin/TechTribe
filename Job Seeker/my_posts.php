<?php
session_start();
include('../database/config.php');

// Ensure the user is logged in
// if (!isset($_SESSION['user_id'])) {
//     header('Location: login.php');
//     exit;
// }

// $user_id = $_SESSION['user_id'];
$user_name = "Ali";
// Retrieve user's posts
// $sql = "SELECT * FROM wall_posts WHERE user_id = ?";
$sql = "SELECT * FROM wall_posts WHERE user_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_name);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Posts</title>
    <link rel="stylesheet" href="../css/my_posts.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <h1>My Posts</h1>
    <div class="posts-container">
        <?php while($row = $result->fetch_assoc()) { ?>
            <div class="post">
                <h2><?php echo htmlspecialchars($row['skill']); ?></h2>
                <p><?php echo htmlspecialchars($row['skillDetails']); ?></p>
                <p><strong>Availability:</strong> <?php echo htmlspecialchars($row['availability']); ?></p>
                <button onclick="window.location.href='edit_wall_post.php?post_id=<?php echo $row['post_id']; ?>'">Edit</button>
                <button onclick="confirmDelete(<?php echo $row['post_id']; ?>)">Delete</button>
            </div>
        <?php } ?>
    </div>

    <script>
        function confirmDelete(postId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'delete_post.php?post_id=' + postId;
                }
            });
        }
    </script>
</body>
</html>
