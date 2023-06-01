<?php
session_start();

// Include the necessary database connection code
require_once "db_connection.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the form data
    $title = $_POST["title"];
    $content = $_POST["content"];
    $topics = $_POST["topics"];

    $content = nl2br($content);

    // Prepare and execute the SQL statement to insert the post into the database
    $stmt = $conn->prepare("INSERT INTO posts (title, content, topics, user_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $title, $content, $topics, $_SESSION["user_id"]);
    $stmt->execute();
    $stmt->close();

    // Redirect the user to the index page or display a success message
    header("Location: articles.php");
    exit();
}
?>