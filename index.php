<?php
// Include the necessary database connection code
require_once "db_connection.php";

session_start();
if (!isset($_SESSION["user_id"])) {
    // Redirect the user to the login page or display an error message
    header("Location: login.php");
    exit();
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
        <div class="row">
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
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Create Blog Post</button>
            </div>
        </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-0fie6IFq0e8Brl8sk7Iam7vgs+bMUu1xCvDyTpjFJfGdZ/tBuLx6TP3aw5HKZCjL" crossorigin="anonymous"></script>
</body>
</html>
