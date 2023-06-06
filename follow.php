<?php
// Include the necessary database connection code
require_once "db_connection.php";

session_start();
if (!isset($_SESSION["user_id"])) {
    // Redirect the user to the login page or display an error message
    header("Location: login.php");
    exit();
}

// Check if the user_id is provided in the POST request
if (isset($_POST["user_id"])) {
    $userId = $_POST["user_id"];
    $followerId = $_SESSION["user_id"];

    // Check if the user is already following the profile user
    $stmt = $conn->prepare("SELECT * FROM followers WHERE user_id = ? AND follower_id = ?");
    $stmt->bind_param("ii", $userId, $followerId);
    $stmt->execute();
    $result = $stmt->get_result();
    $isFollowing = $result->num_rows > 0;
    $stmt->close();

    if ($isFollowing) {
        // Unfollow the user
        $stmt = $conn->prepare("DELETE FROM followers WHERE user_id = ? AND follower_id = ?");
        $stmt->bind_param("ii", $userId, $followerId);
        $stmt->execute();
        $stmt->close();
    } else {
        // Follow the user
        $stmt = $conn->prepare("INSERT INTO followers (user_id, follower_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $userId, $followerId);
        $stmt->execute();
        $stmt->close();
    }

    // Redirect the user back to the user profile page
    header("Location: user.php?user_id=" . $userId);
    exit();
} else {
    // If the user_id is not provided, redirect the user to an error page or display an error message
    header("Location: error.php");
    exit();
}
