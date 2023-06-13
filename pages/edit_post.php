<?php
session_start();

// Include the necessary database connection code
require_once "db_connection.php";

// Check if the post_id is provided in the URL
if (isset($_GET["post_id"])) {
    $post_id = $_GET["post_id"];

    // Retrieve the blog post from the database
    $stmt = $conn->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
    $stmt->close();

    // Check if the user is the author of the post
    if ($post && $_SESSION["user_id"] === $post["user_id"]) {
        // Check if the form is submitted for updating the post
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Retrieve the form data
            $title = $_POST["title"];
            $content = $_POST["content"];
            $topics = $_POST["topics"];

            // Convert newlines to HTML line breaks
            $content = nl2br($content);

            // Update the post in the database
            $stmt = $conn->prepare("UPDATE articles SET title = ?, content = ?, topics = ? WHERE post_id = ?");
            $stmt->bind_param("sssi", $title, $content, $topics, $post_id);
            $stmt->execute();
            $stmt->close();

            // Redirect the user to the view post page or display a success message
            header("Location: view_post.php?post_id=" . $post_id);
            exit();
        }

        // Display the form for editing the post
        
    } else {
        // Redirect the user if they are not the author of the post
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
    <title>Edit "<?php echo $post["title"]; ?>"</title>
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
<body>
<nav class="navbar navbar-expand-lg mb-4">
    <div class="container">
        <div class="navbar-collapse justify-content-between" id="navbarNav">
            <ul class="navbar-nav align-items-center justify-content-between" style="display: contents;">
                <li class="nav-item">
                    <a class="nav-link ms-0 d-flex align-items-center" href="user.php?user_id=<?php echo $_SESSION['user_id']; ?>">
                        <?php
                        // Fetch the user's avatar path from the database
                        // Replace 'your_user_id' with the actual user ID of the logged-in user
                        $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
                        $stmt->bind_param("s", $_SESSION['user_id']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $user = $result->fetch_assoc();
                        $stmt->close();

                        // Display the user's avatar if the path is available
                        if (!empty($user['avatar'])) {
                            echo '<img src="../img/avatars/' . $user['avatar'] . '" class="avatar rounded-circle me-2" alt="User Avatar">';
                        } else {
                            // Display a default avatar if the user does not have a custom avatar
                            echo '<i class="fas fa-user-circle"></i>';
                        }

                        echo $user['name'];
                        ?>
                    </a>
                </li>
                <div class="buttons">
            <a href="view_post.php?post_id=<?php echo $post_id; ?>" class="btn btn-outline-danger rounded-circle me-1" style="padding: 6px 13px; border-width: 2px"><i class="fa-regular fa-xmark"></i></a>
            <button type="submit" form="editPostForm" class="btn btn-success rounded-circle" style="padding: 6px 11px; border-width: 2px"><i class="fa-regular fa-check"></i></button>
            </div>
            </ul>
            
        </div>
    </div>
</nav>

    <div class="container">
        <?php if ($post): ?>
            <!-- Blog post form -->
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" id="editPostForm" class="blog-post">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="title" name="title" value="<?php echo $post["title"]; ?>" placeholder="Title">
            <label for="title">Title</label>
        </div>
        <div class="form-floating mb-3">
            <textarea class="form-control h-100" id="content" name="content" placeholder="Content" rows="18" maxlength="2000"><?php echo $post["content"]; ?></textarea>
            <label for="content">Content</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="topics" name="topics" placeholder="Topics" value="<?php echo $post["topics"]; ?>">
            <label for="topics">Topics</label>
        </div>
        <!-- <button type="submit" class="btn btn-success">Add Article</button> -->
    </form>
        <?php else: ?>
            <p>Post not found.</p>
        <?php endif; ?>
    </div>

    <script src="../js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
