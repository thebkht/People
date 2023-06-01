<?php
// Include the necessary database connection code
require_once "db_connection.php";

session_start();
if (!isset($_SESSION["user_id"])) {
    // Redirect the user to the login page or display an error message
    header("Location: login.php");
    exit();
}

// Retrieve the blog posts with author information from the database
$stmt = $conn->prepare("SELECT posts.*, users.username, users.created_at AS user_created_at FROM posts INNER JOIN users ON posts.user_id = users.user_id ORDER BY posts.created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
$posts = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Process the blog post form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST["title"];
    $content = $_POST["content"];
    // Additional validation and sanitization if needed

    $user_id = $_SESSION["user_id"]; // Retrieve the user_id from the session

    // Insert the new blog post into the database with the user_id
    $stmt = $conn->prepare("INSERT INTO posts (title, content, user_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $title, $content, $user_id);
    $stmt->execute();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include "navbar.php"; ?>


    <div class="container">
        <div class="row mt-4">
            <div class="col-4">
                <!-- Blog post form -->
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" class="blog-post">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="title" name="title" placeholder="Title">
                <label for="title">Title</label>
            </div>
            <div class="form-floating mb-3">
                <textarea class="form-control" id="content" name="content" placeholder="Content"></textarea>
                <label for="content">Content</label>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Create Blog Post</button>
            </div>
        </form>
            </div>
            <div class="col-8">
        <!-- Display existing blog posts -->
        <?php if (!empty($posts)): ?>
            <div class="row">
                <?php foreach ($posts as $post): ?>
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $post["title"]; ?></h5>
                                <p class="card-text card-content"><?php echo strlen($post["content"]) > 100 ? substr($post["content"], 0, 100) . "..." : $post["content"]; ?></p>
                                <div class="d-flex justify-content-between">
                                <a href="view_post.php?post_id=<?php echo $post['post_id']; ?>" class="btn btn-primary">Read More</a>
                                <p class="card-text"><small class="text-muted"><?php echo date("F j, Y", strtotime($post["created_at"])); ?></small></p>
                                </div>
                            </div>
                        </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No blog posts found.</p>
        <?php endif; ?>
    </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-0fie6IFq0e8Brl8sk7Iam7vgs+bMUu1xCvDyTpjFJfGdZ/tBuLx6TP3aw5HKZCjL" crossorigin="anonymous"></script>
</body>
</html>
