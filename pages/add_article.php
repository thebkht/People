<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Article</title>
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
    <script src="../js/"></script>
</head>
<body>
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
    $stmt = $conn->prepare("INSERT INTO articles (user_id, title, content, topics) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $userId, $title, $content, $topics);
    $stmt->execute();
    $stmt->close();

    // Redirect the user to the home page or display a success message
    header("Location: index.php");
    exit();
}

$userId = $_SESSION["user_id"];

?>

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
                        $stmt->bind_param("s", $userId);
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
                <button type="submit" form="createPostForm" class="btn btn-success rounded-circle" style="padding: 4px 10px;font-size: 15px;height: 32px;width: 32px;"><i class="fa-regular fa-up-long"></i></button>
            </ul>
            
        </div>
    </div>
</nav>

<div class="container">
    <!-- Blog post form -->
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" id="createPostForm" class="blog-post">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="title" name="title" placeholder="Title">
            <label for="title">Title</label>
        </div>
        <div class="form-floating mb-3">
            <textarea class="form-control h-100" id="content" name="content" placeholder="Content" rows="18"></textarea>
            <label for="content">Content</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="topics" name="topics" placeholder="Topics">
            <label for="topics">Topics</label>
        </div>
        <!-- <button type="submit" class="btn btn-success">Add Article</button> -->
    </form>
</div>


<script src="../js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
