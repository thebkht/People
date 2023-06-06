<?php
// Include the necessary database connection code
require_once "db_connection.php";

session_start();
if (!isset($_SESSION["user_id"])) {
    // Redirect the user to the login page or display an error message
    header("Location: login.php");
    exit();
}

// Check if the search query is provided in the GET request
if (isset($_GET["searchQuery"])) {
    $searchQuery = $_GET["searchQuery"];

    // Prepare the SQL statement to search for users
    $stmt = $conn->prepare("SELECT user_id, username, email FROM users WHERE username LIKE ? OR email LIKE ?");
    $searchTerm = "%$searchQuery%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Send JSON response with the search results
    header("Content-Type: application/json");
    echo json_encode($users);
    exit();
}
?>
