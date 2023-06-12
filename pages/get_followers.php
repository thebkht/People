<?php
// Include the necessary database connection code
require_once "db_connection.php";

if (isset($_GET["user_id"])) {
  $userId = $_GET["user_id"];

  // Retrieve followers' information from the database
  $stmt = $conn->prepare("SELECT users.user_id, users.name, users.username, users.avatar, users.verified FROM user_followers JOIN users ON user_followers.follower_id = users.user_id WHERE user_followers.followed_id = ?");
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $result = $stmt->get_result();
  $followers = $result->fetch_all(MYSQLI_ASSOC);
  $stmt->close();

  // Send the followers' information as JSON response
  header('Content-Type: application/json');
  echo json_encode($followers);
  exit();
}
?>
