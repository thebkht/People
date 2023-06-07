<?php
// Include the necessary database connection code
require_once "db_connection.php";

// Fetch all books from the database
$stmt = $conn->prepare("SELECT * FROM articles");
$stmt->execute();
$articles = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Check if the search form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    // Retrieve the search keyword
    $keyword = $_POST['search'];

    // Prepare the SQL statement with a search query
    $stmt = $conn->prepare("SELECT * FROM articles WHERE title LIKE ?");
    $searchKeyword = '%' . $keyword . '%';
    $stmt->bind_param("s", $searchKeyword);
    $stmt->execute();
    $articles = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// Pagination
$articlesPerPage = 9;
$totalArticles = count($articles);
$totalPages = ceil($totalArticles / $articlesPerPage);
$currentPage = isset($_GET['page']) ? max(1, $_GET['page']) : 1;
$offset = ($currentPage - 1) * $articlesPerPage;
$articles = array_slice($articles, $offset, $articlesPerPage);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>All Articles</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include "navbar.php"; ?>
    <div class="container">
        <h1>All Articles</h1>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search articles...">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

        <div class="row align-self-stretch">
            <?php foreach ($articles as $article): ?>
                <div class="col-md-4 mb-3">
                    <div class="card mb-3 h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $article['title']; ?></h5>
                            <p class="card-text"><?php echo substr($article['content'], 0, 100); ?>...</p>
                            
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a href="view_post.php?post_id=<?php echo $article['post_id']; ?>" class="btn btn-primary">Read More</a>
                            <p class="card-text d-inline"><small class="text-muted"><?php echo date("F j, Y", strtotime($article["created_at"])); ?></small></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php if ($totalPages > 1): ?>
                    <?php if ($currentPage > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="visually-hidden">Previous</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="visually-hidden">Next</span>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-ZqJb6cPz5q5a9owSd6s3Ezyzy29bOUGFyjk98PhGbfFRqu7aZXg3bQZNvDc46zJx" crossorigin="anonymous"></script>
</body>
</html>
