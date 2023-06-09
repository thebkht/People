<?php
require_once "db_connection.php";

session_start();
// Perform the follow action
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $followerId = $_SESSION["user_id"];
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

    // Redirect back to the page after following the user
    header("Location: ".$_SERVER['HTTP_REFERER']);
    exit();
}


/* if (isset($_POST['follow_user'])) {
    $userIdToFollow = $_POST['follow_user'];

    // Check if the user ID and session user ID are not null
    if ($userIdToFollow && isset($_SESSION['user_id'])) {
        try {
            // Check if the user is already being followed
            $stmt = $conn->prepare("SELECT * FROM followers WHERE user_id = ? AND follower_id = ?");
            $stmt->bind_param('ii', $userIdToFollow, $_SESSION['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $isFollowing = ($result->num_rows > 0);

            if (!$isFollowing) {
                // Add the follow relationship to the database
                $stmt = $conn->prepare("INSERT INTO followers (user_id, follower_id) VALUES (?, ?)");
                $stmt->bind_param('ii', $userIdToFollow, $_SESSION['user_id']);
                $stmt->execute();
            }

            // Redirect back to the page after following the user
            header("Location: ".$_SERVER['HTTP_REFERER']);
            exit();
        } catch (mysqli_sql_exception $e) {
            // Handle database errors
            echo "Database Error: " . $e->getMessage();
            die();
        }
    } else {
        // Redirect to the home page if the user ID or session user ID is null
        header("Location: index.php");
        exit();
    }
} else {
    // Redirect to the home page if accessed directly
    header("Location: index.php");
    exit();
}
 */?>
