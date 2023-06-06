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

// Check if the post_id is provided in the URL
if (isset($_GET["post_id"])) {
    $post_id = $_GET["post_id"];

    // Check if the post_views cookie exists
    if (isset($_COOKIE['post_views'])) {
        $postViews = $_COOKIE['post_views'];

        // Split the cookie value into an array of post IDs
        $viewedPosts = explode(',', $postViews);

        // Check if the current post ID is already in the viewed posts array
        if (!in_array($post_id, $viewedPosts)) {
            // Increment the view count in the database
            $stmt = $conn->prepare("UPDATE posts SET views = views + 1 WHERE post_id = ?");
            $stmt->bind_param("i", $post_id);
            $stmt->execute();
            $stmt->close();

            // Add the current post ID to the viewed posts array
            $viewedPosts[] = $post_id;

            // Update the post_views cookie
            $updatedPostViews = implode(',', $viewedPosts);
            setcookie('post_views', $updatedPostViews, time() + (86400 * 30), '/'); // Set the cookie for 30 days
        }
    } else {
        // If the post_views cookie doesn't exist, create it and set the view count in the database
        setcookie('post_views', $post_id, time() + (86400 * 30), '/'); // Set the cookie for 30 days

        $stmt = $conn->prepare("UPDATE posts SET views = views + 1 WHERE post_id = ?");
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $stmt->close();
    }

    // Retrieve the current post from the database
    $stmt = $conn->prepare("SELECT * FROM posts WHERE post_id = ?");
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
            $stmt = $conn->prepare("SELECT * FROM posts WHERE topics LIKE ? AND post_id != ? ORDER BY created_at DESC LIMIT 3");
            $likeTopic = '%' . trim($topic) . '%';
            $stmt->bind_param("si", $likeTopic, $post_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $relatedArticles = array_merge($relatedArticles, $result->fetch_all(MYSQLI_ASSOC));
            $stmt->close();
        }

        // Display the current post
        // ...

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
    <title>View Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include "navbar.php"; ?>

    <div class="container mb-5">
        <h1><?php echo $post["title"]; ?></h1>
        <p class="mt-4"><?php echo $post["content"]; ?></p>
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
            echo '<button type="button" class="btn btn-outline-primary btn-sm me-2 text-uppercase">' . trim($topic) . '</button>';
        }
        ?>
    </div>

        <hr>

        <a class="btn btn-primary mb-4" data-bs-toggle="offcanvas" href="#comments" role="button" aria-controls="comments">
  Show Comments
        </a>

        <div class="offcanvas offcanvas-start" tabindex="-1" id="comments" aria-labelledby="commentsLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasLabel">Comments</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
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
        </div>

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



    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
    <script>
        const offcanvasElementList = document.querySelectorAll('.offcanvas')
const offcanvasList = [...offcanvasElementList].map(offcanvasEl => new bootstrap.Offcanvas(offcanvasEl))
    </script>


</body>
</html>
