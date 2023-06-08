<?php
// Include the necessary database connection code
require_once "db_connection.php";

session_start();
if (!isset($_SESSION["user_id"])) {
    // Redirect the user to the login page or display an error message
    header("Location: login.php");
    exit();
}

$followerId = $_SESSION["user_id"];

// Check if the user_id is provided in the URL
if (isset($_GET["user_id"])) {
    $followedId = $_GET["user_id"];

    // Retrieve user information from the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $followedId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    // Retrieve user's articles from the database
    $stmt = $conn->prepare("SELECT * FROM articles WHERE user_id = ?");
    $stmt->bind_param("i", $followedId);
    $stmt->execute();
    $result = $stmt->get_result();
    $articles = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Perform the follow action
    if (isset($_POST["user_id"])) {
        $followedId = $_POST["user_id"];

        // Check if the user is already following the profile user
        $stmt = $conn->prepare("SELECT * FROM user_followers WHERE follower_id = ? AND followed_id = ?");
        $stmt->bind_param("ii", $followerId, $followedId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // The user is already following, so unfollow
            $stmt = $conn->prepare("DELETE FROM user_followers WHERE follower_id = ? AND followed_id = ?");
            $stmt->bind_param("ii", $followerId, $followedId);
            $stmt->execute();
            $isFollowing = false;
        } else {
            // The user is not following, so follow
            $stmt = $conn->prepare("INSERT INTO user_followers (follower_id, followed_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $followerId, $followedId);
            $stmt->execute();
            $isFollowing = true;
        }

        $stmt->close();
    }

    // Retrieve the number of followers for the user
    $stmt = $conn->prepare("SELECT COUNT(*) FROM user_followers WHERE followed_id = ?");
    $stmt->bind_param("i", $followedId);
    $stmt->execute();
    $result = $stmt->get_result();
    $followerCount = $result->fetch_row()[0];
    $stmt->close();

    // Retrieve the number of articles for the user
    $postCount = count($articles);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>
        <?php 
            if (isset($user["name"])){
                echo $user["name"];
            } else{
                echo $user["username"];
            }
        ?> - Readit
    </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include "navbar.php"; ?>

<div class="container">
    <div class="row d-flex">
        <div class="col-4">
            <div class="profile-photo mb-3">
                <img src="<?php echo $user['avatar']; ?>" class="h-100 rounded-circle" alt="">
            </div>
            <h5><?php echo $user['name']; ?></h5>
            <h6 class="mb-3">@<?php echo $user['username']; ?></h6>
            <p>Email: <?php echo $user['email']; ?></p>
            <p><?php echo $followerCount; ?> Followers | <?php echo $postCount; ?> articles</p>

            <!-- Follow Button -->
            <?php if ($_SESSION['user_id'] != $followedId): ?>
                <?php
                // Check if the user is already following the profile user
                $stmt = $conn->prepare("SELECT * FROM user_followers WHERE follower_id = ? AND followed_id = ?");
                $stmt->bind_param("ii", $followerId, $followedId);
                $stmt->execute();
                $result = $stmt->get_result();
                $isFollowing = $result->num_rows > 0;
                $stmt->close();
                ?>

                <form action="user.php?user_id=<?php echo $followedId; ?>" method="POST">
                    <input type="hidden" name="user_id" value="<?php echo $followedId; ?>">
                    <?php if ($isFollowing): ?>
                        <button type="submit" class="btn btn-outline-success">Following</button>
                    <?php else: ?>
                        <button type="submit" class="btn btn-success">Follow</button>
                    <?php endif; ?>
                </form>
                <?php else: ?>
                <a href="edit_profile.php" class="btn btn-success">Edit Profile</a>
        <a href="logout.php" class="btn btn-success">Logout</a>
            <?php endif; ?>
        </div>
        <div class="col-8">
            <h2>Articles</h2>
            <?php if (count($articles) > 0): ?>
                <?php foreach ($articles as $article): ?>
                    
                    <div class="card mb-3 w-100 me-3">
                        <div class="card-body">
                        <a href="view_post.php?post_id=<?php echo $article['id']; ?>"><h5 class="card-title"><?php echo $article['title']; ?></h5></a>
                            <p class="card-text"><?php echo substr($article['content'], 0, 100); ?>...</p>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                        <?php if ($_SESSION['user_id'] === $article['user_id']): ?>
                            <div class="action-btn">
                            <a href="edit_post.php?post_id=<?php echo $article['id']; ?>" class="btn btn-success">Edit</a>
                            <a href="delete_post.php?post_id=<?php echo $article['id']; ?>" class="btn btn-danger">Delete</a>
                            </div>
                        <?php endif; ?>
                            <p class="card-text d-inline"><small class="text-muted"><?php echo date("F j, Y", strtotime($article["created_at"])); ?></small></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No articles found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
