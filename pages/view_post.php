<?php
// Include the necessary database connection code
require_once "db_connection.php";

// Check if a post ID is provided
if (!isset($_GET["post_id"])) {
    header("Location: index.php");
    exit();
}

// Retrieve the post with author information from the database
$stmt = $conn->prepare("SELECT articles.*, users.username, users.created_at AS user_created_at FROM articles INNER JOIN users ON articles.user_id = users.user_id WHERE articles.id = ?");
$stmt->bind_param("i", $_GET["post_id"]);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
$stmt->close();

// Retrieve comments for the post from the database
$stmt = $conn->prepare("SELECT comments.content, users.username FROM comments INNER JOIN users ON comments.user_id = users.user_id WHERE comments.article_id = ?");
$stmt->bind_param("i", $_GET["post_id"]);
$stmt->execute();
$result = $stmt->get_result();
$comments = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

session_start();

// Check if the post_id is provided in the URL
if (isset($_GET["post_id"])) {
    $post_id = $_GET["post_id"];

    // Check if the post_views cookie exists
    if (isset($_COOKIE['views'])) {
        $postViews = $_COOKIE['views'];

        // Split the cookie value into an array of post IDs
        $viewedarticles = explode(',', $postViews);

        // Check if the current post ID is already in the viewed articles array
        if (!in_array($post_id, $viewedarticles)) {
            // Increment the view count in the database
            $stmt = $conn->prepare("UPDATE articles SET views = views + 1 WHERE id = ?");
            $stmt->bind_param("i", $post_id);
            $stmt->execute();
            $stmt->close();

            // Add the current post ID to the viewed articles array
            $viewedarticles[] = $post_id;

            // Update the post_views cookie
            $updatedPostViews = implode(',', $viewedarticles);
            setcookie('views', $updatedPostViews, time() + (86400 * 30), '/'); // Set the cookie for 30 days
        }
    } else {
        // If the post_views cookie doesn't exist, create it and set the view count in the database
        setcookie('views', $post_id, time() + (86400 * 30), '/'); // Set the cookie for 30 days

        $stmt = $conn->prepare("UPDATE articles SET views = views + 1 WHERE id = ?");
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $stmt->close();
    }

    // Retrieve the current post from the database
    $stmt = $conn->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
    $stmt->close();

    // Check if the post exists
    if ($post) {
        // Retrieve the related articles based on topics
        $topics = explode(",", $post["topics"]);
        $relatedArticles = [];

        foreach ($topics as $topic) {
            $stmt = $conn->prepare("SELECT * FROM articles WHERE topics LIKE ? AND id != ? ORDER BY created_at DESC LIMIT 3");
            $likeTopic = '%' . trim($topic) . '%';
            $stmt->bind_param("si", $likeTopic, $post_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $relatedArticles = array_merge($relatedArticles, $result->fetch_all(MYSQLI_ASSOC));
            $stmt->close();
        }

        // Display the current post
        // ...
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $userId = $_SESSION["user_id"];
            $articleId = $_GET["post_id"];
            $content = nl2br($_POST["comment"]);
        
            // Insert the new comment into the database
            $stmt = $conn->prepare("INSERT INTO comments (user_id, article_id, content) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $userId, $articleId, $content);
            $stmt->execute();
            $stmt->close();
        
            // Update the comment count in the articles table
            $stmt = $conn->prepare("UPDATE articles SET comments = comments + 1 WHERE id = ?");
            $stmt->bind_param("i", $articleId);
            $stmt->execute();
            $stmt->close();
        }
        
        // Retrieve the comments for the post from the database
        $stmt = $conn->prepare("SELECT content, user_id, created_at FROM comments WHERE article_id = ?");
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $comments = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();



        // Display related articles

        
    } else {
        // Redirect the user if the post doesn't exist
        header("Location: index.php");
        exit();
    }
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
    <title>
        <?php echo $post["title"]; ?> - Readit
    </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/all.css">
    <link rel="stylesheet" href="../css/font.css">
    <link rel="shortcut icon" href="../img/icon.png" type="image/x-icon">
    <link rel="icon" href="../img/favicon_32x32.png" sizes="32x32">
    <link rel="icon" href="../img/favicon_48x48.png" sizes="48x48">
    <link rel="icon" href="../img/favicon_96x96.png" sizes="96x96">
    <link rel="icon" href="../img/favicon_144x144.png" sizes="144x144">
</head>
<body style="background-color: #fff;">
<?php include "navbar.php"; ?>

    <div class="container mb-5">
        <h1><?php echo $post["title"]; ?></h1>
        <article class="mt-4 mb-4">
        <?php echo $post["content"]; ?>
        </article>
        <p class="card-text fw-bold">Posted by 
            <a href="user.php?user_id=<?php echo $post['user_id']; ?>">
            <?php
            if (isset($post['user_id'])) {
                $stmt = $conn->prepare("SELECT username FROM users WHERE user_id = ?");
                $stmt->bind_param("i", $post['user_id']);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
                $stmt->close();

                if ($user && isset($user['username'])) {
                    echo $user['username'];
                }
            }
            ?>
            </a>
        </p>
    <p class="card-text d-inline"><small class="text-muted"><?php echo date("F j, Y", strtotime($post["created_at"])); ?></small></p>
        <div class="mt-4 mb-4">
        <h5>Topics:</h5>
        <?php
        // Explode the topics string into an array
        $topicsArray = explode(',', $post["topics"]);

        // Loop through the topics and display each as a button
        foreach ($topicsArray as $topic) {
            echo '<button type="button" class="btn btn-outline-success btn-sm me-2 text-uppercase">' . trim($topic) . '</button>';
        }
        ?>
    </div>

        <hr>


                <!-- Display existing comments -->
                <div class="comments">
                <?php if (!empty($comments)): ?>
            <?php foreach ($comments as $comment): ?>
                <?php
            if (isset($post['user_id'])) {
                $stmt = $conn->prepare("SELECT username, name, avatar, verified FROM users WHERE user_id = ?");
                $stmt->bind_param("i", $comment['user_id']);
                $stmt->execute();
                $result = $stmt->get_result();
                $c_owner = $result->fetch_assoc();
                $stmt->close();
            }
            ?>
                <div class="card mb-3">
                    <div class="card-body">
                    <div class="card-title">
                    <a href="user.php?user_id=<?php echo $comment['user_id']; ?>" class="d-flex">
                        
                        <div class="publisher-photo me-2">
                                <img src="../img/avatars/<?php echo $c_owner['avatar']; ?>" class="h-100 rounded-circle" alt="<?php echo $c_owner['name']; ?>'s profile photo">
                            </div>
                            <div class="publisher">
                                <p class="publicher-name mb-0">
                                    <?php echo $c_owner['name']; ?>
                                    <?php if ($c_owner['verified']): ?>
                                        <i class="fa-solid fa-badge-check text-primary ms-2"></i>
                                    <?php endif; ?>
                                </p>
                                <span class="publisher-username"><?php echo date("F j, Y", strtotime($comment["created_at"])); ?></span> 
                            </div>
                        </a>
                            </div>
                        
                        <div class="card-text"><?php echo $comment["content"]; ?></div>
                    </div>
                    
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No comments found.</p>
        <?php endif; ?>
                </div>

        <hr>

        <!-- Comment form -->
        <?php if (isset($_SESSION["user_id"])): ?>
            <h3>Add a Comment</h3>
            <form action="<?php echo $_SERVER["PHP_SELF"] . "?post_id=" . $_GET["post_id"]; ?>" method="POST">
                <div class="mb-3">
                    <textarea class="form-control" name="comment" placeholder="Your comment" required></textarea>
                </div>
                <button type="submit" class="btn btn-success">Submit Comment</button>
            </form>
        <?php else: ?>
            <p>Please <a href="login.php">log in</a> to add a comment.</p>
        <?php endif; ?>

        <!-- <?php
            
            if (!empty($relatedArticles)) {
                $displayed_articles = [];
                echo "<h2>Related Articles</h2>";
                echo "<ul class='list-group mb-4'>";
                foreach ($relatedArticles as $relatedArticle) {
                    
                    if (!in_array($relatedArticle['post_id'], $displayed_articles)){
                        echo "<li class='list-group-item'>";
                    echo "<a href='view_post.php?post_id=". $relatedArticle['post_id'] . "'>" . $relatedArticle['title'] . "</a>";
                    echo "</li>";
                    $displayed_articles[] = $related_article['post_id'];
                    }    
                }
                echo "</ul>";
            }
        ?> -->
    </div>



    <script src="../js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>