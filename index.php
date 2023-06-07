<?php
// Include the necessary database connection code
require_once "db_connection.php";

session_start();
if (!isset($_SESSION["user_id"])) {
    // Redirect the user to the login page or display an error message
    header("Location: login.php");
    exit();
}

$userId = $_SESSION["user_id"];

// Check if the user is following someone
$stmt = $conn->prepare("SELECT COUNT(*) FROM user_followers WHERE follower_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$count = $result->fetch_row()[0];
$stmt->close();

$articles = [];
if ($count > 0) {
    // Retrieve the list of followed user IDs
    $stmt = $conn->prepare("SELECT followed_id FROM user_followers WHERE follower_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $followedUsers = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Create an array to store the followed user IDs
    $followedUserIds = [];
    foreach ($followedUsers as $followedUser) {
        $followedUserIds[] = $followedUser['followed_id'];
    }

    // Prepare the placeholders for the IN clause
    $placeholders = rtrim(str_repeat('?,', count($followedUserIds)), ',');

    // Prepare the SQL statement to retrieve articles from the followed users
    $stmt = $conn->prepare("SELECT * FROM articles WHERE user_id IN ($placeholders)");
    $stmt->bind_param(str_repeat('i', count($followedUserIds)), ...$followedUserIds);
    $stmt->execute();
    $result = $stmt->get_result();
    $articles = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// Check if the search query is provided in the GET request
if (isset($_GET["searchQuery"])) {
    $searchQuery = $_GET["searchQuery"];

    // Prepare the SQL statement to search for users
    $stmt = $conn->prepare("SELECT * FROM users WHERE username LIKE ? OR email LIKE ?");
    $searchTerm = "%$searchQuery%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Prepare the search results as JSON
    $searchResults = [];
    if (count($users) > 0) {
        foreach ($users as $user) {
            $searchResults[] = [
                'username' => $user['username'],
                'email' => $user['email'],
                'user_id' => $user['user_id']
            ];
        }
    }

    // Send the search results as JSON response
    header('Content-Type: application/json');
    echo json_encode($searchResults);
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/font.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/all.css">
</head>
<body>
<?php include "navbar.php"; ?>

<div class="container">
    <div class="row">
    <?php
        // Fetch the user's avatar path from the database
        // Replace 'your_user_id' with the actual user ID of the logged-in user
        $stmt = $conn->prepare("SELECT avatar FROM users WHERE user_id = ?");
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $stmt->bind_result($avatar);
        $stmt->fetch();
        $stmt->close();
        ?>

        <h1 class="text-center">My Feed</h1>

        <div class="mb-3 search-dropdown">
            <label for="searchQuery" class="form-label">Search User:</label>
            <input type="text" class="form-control" id="searchQuery" name="searchQuery" placeholder="Enter username or email" autocomplete="off">
            <div class="dropdown-menu mt-3" id="searchResults" aria-labelledby="searchQuery">
            </div>
        </div>
    </div>

    <h1>Home</h1>
    <div class="d-flex">
    <?php if (!empty($articles)): ?>
        <?php foreach ($articles as $article): ?>
            <div class="col-4 me-2 mb-3">
            <a href="view_post.php?post_id=<?php echo $article['post_id']; ?>">
            <div class="card h-100 mb-3">
                <div class="card-header">
                <?php
            // Retrieve the publisher's information
            $publisherId = $article['user_id'];
            $stmt = $conn->prepare("SELECT name, username FROM users WHERE user_id = ?");
            $stmt->bind_param("i", $publisherId);
            $stmt->execute();
            $result = $stmt->get_result();
            $publisher = $result->fetch_assoc();
            $stmt->close();
            ?>
            <span><?php echo $publisher['name']; ?></span>
            <br>
            <span>@<?php echo $publisher['username']; ?></span>    
                </div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $article['title']; ?></h5>
                    <p class="card-text"><?php echo substr($article['content'], 0, 200); ?>...</p>
                </div>
                <div class="card-footer d-flex justify-content-between">
                <p class="card-text d-inline mb-0"><small class="text-muted">Views: <?php echo $article['views']; ?></small></p>
                <p class="card-text d-inline mb-0"><small class="text-muted"><?php echo date("F j, Y", strtotime($article["created_at"])); ?></small></p>
                </div>
            </div>
            </a>
            </div>
            
        <?php endforeach; ?>
    <?php else: ?>
        <p>No articles found.</p>
    <?php endif; ?>
    </div>
    

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        var searchQueryInput = $('#searchQuery');
        var searchResultsContainer = $('#searchResults');

        searchQueryInput.on('input', function() {
            var searchQuery = $(this).val();
            if (searchQuery.length > 0) {
                // Send AJAX request to search for users
                $.ajax({
                    url: 'search_user_ajax.php',
                    method: 'GET',
                    data: { searchQuery: searchQuery },
                    dataType: 'json',
                    success: function(response) {
                        // Clear previous search results
                        searchResultsContainer.empty();

                        // Display search results using Bootstrap dropdown
                        if (response.length > 0) {
                            response.forEach(function(user) {
                                var dropdownItem = $('<a class="dropdown-item"></a>')
                                .attr('href', 'user.php?user_id=' + user.user_id) // Set user_id in the URL
                                .text(user.username + ' - ' + user.email);
                                console.log(user);
                                searchResultsContainer.append(dropdownItem);
                            });
                        } else {
                            var dropdownItem = $('<a class="dropdown-item disabled"></a>').text('No users found');
                            searchResultsContainer.append(dropdownItem);
                        }

                        // Show the dropdown and apply custom styles
                        searchResultsContainer.addClass('show custom-dropdown-menu');
                    },
                });
            } else {
                // Hide the dropdown if search query is empty
                searchResultsContainer.removeClass('show custom-dropdown-menu');
            }
        });
    });
</script>

</body>
</html>
