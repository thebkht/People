<?php
session_start();

// Include the necessary database connection code
require_once "db_connection.php";

// Check if the post_id is provided in the URL
if (isset($_GET["post_id"])) {
    $post_id = $_GET["post_id"];

    // Retrieve the blog post from the database
    $stmt = $conn->prepare("SELECT * FROM posts WHERE post_id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
    $stmt->close();
} else {
    // Redirect the user if post_id is not provided
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>View Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>View Post</h1>

        <?php if ($post): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $post["title"]; ?></h5>
                    <p class="card-text"><?php echo $post["content"]; ?></p>
                    <p class="card-text">Date: <?php echo $post["created_at"]; ?></p>
                </div>
            </div>
        <?php else: ?>
            <p>Post not found.</p>
        <?php endif; ?>

        <a href="index.php" class="btn btn-primary">Back to Home</a>
    </div>
</body>
</html>
