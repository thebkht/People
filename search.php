<?php
// Include the necessary database connection code
require_once "db_connection.php";

// Check if a search query is provided
if (isset($_GET["query"])) {
  $searchQuery = $_GET["query"];

  // Prepare the SQL statement to search for articles
  $stmt = $conn->prepare("SELECT * FROM posts WHERE title LIKE ? OR content LIKE ?");
  $searchQuery = "%{$searchQuery}%";
  $stmt->bind_param("ss", $searchQuery, $searchQuery);
  $stmt->execute();
  $result = $stmt->get_result();
  $articles = $result->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Search Results</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="container">
    <h1>Search Results</h1>
    <?php if (isset($_GET["query"])): ?>
      <?php if (!empty($articles)): ?>
        <div class="list-group">
          <?php foreach ($articles as $article): ?>
            <a href="view_post.php?post_id=<?php echo $article["post_id"]; ?>" class="list-group-item list-group-item-action">
              <h4 class="list-group-item-heading"><?php echo $article["title"]; ?></h4>
              <p class="list-group-item-text"><?php echo strlen($article["content"]) > 50 ? substr($article["content"], 0, 50) . "..." : $article["content"]; ?></p>
            </a>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p>No articles found.</p>
      <?php endif; ?>
    <?php else: ?>
      <p>No search query provided.</p>
    <?php endif; ?>
  </div>
</body>
</html>
