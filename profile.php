<?php
session_start();

// Check if the user is logged in, otherwise redirect to the login page
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Include the necessary database connection code
require_once "db_connection.php";

// Retrieve the user's information from the database
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Retrieve the user's blog posts from the database
$stmt = $conn->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->get_result();
$posts = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include "navbar.php"; ?>

    <div class="container">
        <h1>Profile</h1>
        <p>Welcome, <?php echo $user["username"]; ?>!</p>
        <p>Email: <?php echo $user["email"]; ?></p>
        <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
        <a href="logout.php" class="btn btn-primary">Logout</a>

        <hr>

        <h2>Your Blogs</h2>

        <div class="row align-self-stretch">
            <?php foreach ($posts as $article): ?>
                <div class="col-md-4 mb-3">
                    <div class="card mb-3 h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $article['title']; ?></h5>
                            <p class="card-text"><?php echo substr($article['content'], 0, 100); ?>...</p>
                            
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <div>
                            <a href="edit_post.php?post_id=<?php echo $article['post_id']; ?>" class="btn btn-primary">Edit</a>
                            <a href="delete_post.php?post_id=<?php echo $article['post_id']; ?>" class="btn btn-danger">Delete</a>

                            </div>
                            <p class="card-text d-inline"><small class="text-muted"><?php echo date("F j, Y", strtotime($article["created_at"])); ?></small></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
