<?php
// Include the necessary database connection code
require_once "db_connection.php";

// Check if a post ID is provided
if (!isset($_GET["post_id"])) {
    header("Location: index.php");
    exit();
}

// Retrieve the post with author information from the database
$stmt = $conn->prepare("SELECT posts.*, users.username, users.created_at AS user_created_at FROM posts INNER JOIN users ON posts.user_id = users.user_id WHERE posts.post_id = ?");
$stmt->bind_param("i", $_GET["post_id"]);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
$stmt->close();

// Retrieve comments for the post from the database
$stmt = $conn->prepare("SELECT comments.comment, users.username FROM comments INNER JOIN users ON comments.user_id = users.user_id WHERE comments.post_id = ?");
$stmt->bind_param("i", $_GET["post_id"]);
$stmt->execute();
$result = $stmt->get_result();
$comments = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

session_start();

// Process the comment form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION["user_id"])) {
    $comment = $_POST["comment"];
    $user_id = $_SESSION["user_id"];
    $post_id = $_GET["post_id"];

    $stmt = $conn->prepare("INSERT INTO comments (comment, user_id, post_id) VALUES (?, ?, ?)");
    $stmt->bind_param("sii", $comment, $user_id, $post_id);
    $stmt->execute();
    $stmt->close();

    header("Location: view_post.php?post_id=" . $_GET["post_id"]);
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
<?php include "navbar.php"; ?>

    <div class="container mb-5">
        <h1><?php echo $post["title"]; ?></h1>
        <p class="mt-4"><?php echo $post["content"]; ?></p>
        <p class="author fw-bold">Posted by <?php echo $post["username"]; ?> on <?php echo date("F j, Y", strtotime($post["created_at"])); ?></p>

        <div class="mt-4 mb-4">
        <h5>Topics:</h5>
        <?php
        // Explode the topics string into an array
        $topicsArray = explode(',', $post["topics"]);

        // Loop through the topics and display each as a button
        foreach ($topicsArray as $topic) {
            echo '<button type="button" class="btn btn-outline-primary btn-sm me-2 text-uppercase">' . trim($topic) . '</button>';
        }
        ?>
    </div>

        <hr>

        <h2>Comments</h2>
        <!-- Display existing comments -->
        <?php if (!empty($comments)): ?>
            <?php foreach ($comments as $comment): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $comment["username"]; ?></h5>
                        <p class="card-text"><?php echo $comment["comment"]; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No comments found.</p>
        <?php endif; ?>

        <hr>

        <!-- Comment form -->
        <?php if (isset($_SESSION["user_id"])): ?>
            <h3>Add a Comment</h3>
            <form action="<?php echo $_SERVER["PHP_SELF"] . "?post_id=" . $_GET["post_id"]; ?>" method="POST">
                <div class="mb-3">
                    <textarea class="form-control" name="comment" placeholder="Your comment" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Comment</button>
            </form>
        <?php else: ?>
            <p>Please <a href="login.php">log in</a> to add a comment.</p>
        <?php endif; ?>
    </div>
</body>
</html>
