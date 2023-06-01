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

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST["title"];
    $content = $_POST["content"];
    // Additional validation and sanitization if needed

    // Update the blog post in the database
    $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE post_id = ?");
    $stmt->bind_param("ssi", $title, $content, $post_id);
    $stmt->execute();
    $stmt->close();

    // Redirect the user to the updated post
    header("Location: view_post.php?post_id=$post_id");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Post</h1>

        <?php if ($post): ?>
            <form action="<?php echo $_SERVER["PHP_SELF"] . "?post_id=" . $post_id; ?>" method="POST">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control" value="<?php echo $post["title"]; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea name="content" id="content" class="form-control" required><?php echo $post["content"]; ?></textarea>
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Update Post</button>
                    <a href="view_post.php?post_id=<?php echo $post_id; ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        <?php else: ?>
            <p>Post not found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
