<?php
// Include the necessary database connection code
require_once "db_connection.php";

session_start();
if (!isset($_SESSION["user_id"])) {
    // Redirect the user to the login page or display an error message
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userId = $_SESSION["user_id"];
    $title = $_POST["title"];
    $content = nl2br($_POST["content"]);
    $topics = $_POST["topics"];

    // Insert the new article into the database
    $stmt = $conn->prepare("INSERT INTO posts (user_id, title, content, topics) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $userId, $title, $content, $topics);
    $stmt->execute();
    $stmt->close();

    // Redirect the user to the home page or display a success message
    header("Location: index.php");
    exit();
}

// Retrieve the list of topics from the database
$stmt = $conn->prepare("SELECT * FROM topics");
$stmt->execute();
$result = $stmt->get_result();
$topics = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Article</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include "navbar.php"; ?>

<div class="container">

    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    <div class="container">
            <!-- Blog post form -->
        <form action="create_post.php" method="POST" class="blog-post">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="title" name="title" placeholder="Title">
                <label for="title">Title</label>
            </div>
            <div class="form-floating mb-3">
                <textarea class="form-control h-100" id="content" name="content" placeholder="Content" rows="10"></textarea>
                <label for="content">Content</label>
        </div>
        <div class="form-floating mb-3">
                <input type="text" class="form-control" id="topics" name="topics" placeholder="Topics">
                <label for="topics">Topics</label>
</div>
        <button type="submit" class="btn btn-primary">Add Article</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
