<?php
session_start();

// Include the necessary database connection code
require_once "db_connection.php";

// Check if the post_id is provided in the URL
if (isset($_GET["post_id"])) {
    $post_id = $_GET["post_id"];

    // Retrieve the blog post from the database
    $stmt = $conn->prepare("SELECT * FROM posts WHERE post_id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
    $stmt->close();

    // Check if the user is the author of the post
    if ($post && $_SESSION["user_id"] === $post["user_id"]) {
        // Delete the comments associated with the post
        $stmt = $conn->prepare("DELETE FROM comments WHERE post_id = ?");
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $stmt->close();

        // Delete the blog post from the database
        $stmt = $conn->prepare("DELETE FROM posts WHERE post_id = ?");
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $stmt->close();

        // Redirect the user to the index page or display a success message
        header("Location: index.php");
        exit();
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
