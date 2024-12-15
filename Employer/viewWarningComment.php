<?php
include('../database/config.php');
include('employer1.php');


if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit();
}

$userID = $_SESSION['userID'];

// Query the user's saved positions
$sql = "
    SELECT comment 
    FROM reportPost 
    WHERE reportedUserID = ? AND comment IS NOT NULL 
    ORDER BY createTime DESC
";
$stmt = $con->prepare($sql);
$stmt->bind_param('s', $userID);
$stmt->execute();
$result = $stmt->get_result();

$comments = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row['comment'];
    }
} else {
    $comments[] = "No warnings found."; // Default message if no warnings
}
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warning</title>
    <style>
        .content h1 {
            margin: 0; /* Remove default margin for proper centering */
            font-weight: 700;
            margin-left: 150px;
        }

        .main-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
            padding: 20px;
        }

        .warning-box {
            width: 80%;
            max-width: 600px;
            background-color: #dff6fa;
            border: 1px solid #b3e6ed;
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 15px;
            color: #07587a;
            font-size: 16px;
            line-height: 1.5;
        }

        .back-btn {
            display: inline-block;
            margin: 20px 0;
            padding: 10px 20px;
            background-color: #2b6777;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }

        .back-btn:hover {
            background-color: #1b4f57;
        }
    </style>
</head>
<body>
<div class="content">
    <h1>Warning</h1>
</div>
    <!-- Main Content -->
    <div class="main-content">
    <?php if (!empty($comments) && $comments[0] !== "No warnings found."): ?>
            <?php foreach ($comments as $comment): ?>
                <div class="warning-box">
                    <h3>Comment</h3>
                    <p><?php echo htmlspecialchars($comment); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="warning-box">
                No warnings found.
            </div>
        <?php endif; ?>

        <a href="employer_dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>