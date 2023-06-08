<?php
// Include the necessary database connection code
require_once "db_connection.php";

if (isset($_GET["user_id"]) && isset($_GET["searchQuery"])) {
    $userId = $_GET["user_id"];
    $searchQuery = $_GET["searchQuery"];

    // Prepare the SQL statement to search for articles
    $stmt = $conn->prepare("SELECT * FROM articles WHERE user_id = ? AND title LIKE ?");
    $searchTerm = "%$searchQuery%";
    $stmt->bind_param("is", $userId, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $articles = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Prepare the search results as JSON
    $searchResults = [];
    if (count($articles) > 0) {
        foreach ($articles as $article) {
            $searchResults[] = [
                'post_id' => $article['id'],
                'title' => $article['title']
            ];
        }
    }

    // Send the search results as JSON response
    header('Content-Type: application/json');
    echo json_encode($searchResults);
    exit();
}
