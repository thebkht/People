<?php
// Include the necessary database connection code
require_once "db_connection.php";

session_start();
if (!isset($_SESSION["user_id"])) {
    // Redirect the user to the login page or display an error message
    header("Location: ../index.php");
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
    $stmt = $conn->prepare("SELECT followed_id FROM user_followers WHERE follower_id = ? ");
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

    // Add the signed user's ID to the list of followed user IDs
    $followedUserIds[] = $userId;

    // Prepare the placeholders for the IN clause
    $placeholders = rtrim(str_repeat('?,', count($followedUserIds)), ',');

    // Prepare the SQL statement to retrieve articles from the followed users
    $stmt = $conn->prepare("SELECT * FROM articles WHERE user_id IN ($placeholders) ORDER BY `articles`.`created_at` DESC");
    $stmt->bind_param(str_repeat('i', count($followedUserIds)), ...$followedUserIds);
    $stmt->execute();
    $result = $stmt->get_result();
    $articles = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
    $stmt = $conn->prepare("SELECT users.*, COUNT(user_followers.follower_id) AS follower_count 
                            FROM users 
                            LEFT JOIN user_followers ON users.user_id = user_followers.followed_id
                            GROUP BY users.user_id
                            ORDER BY follower_count DESC
                            LIMIT 10");
    $stmt->execute();
    $result = $stmt->get_result();
    $topUsers = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

// Check if the search query is provided in the GET request
if (isset($_GET["searchQuery"])) {
    $searchQuery = $_GET["searchQuery"];

    // Prepare the SQL statement to search for users
    $stmt = $conn->prepare("SELECT * FROM users WHERE username LIKE ?");
    $searchTerm = "%$searchQuery%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Prepare the search results as JSON
    $searchResults = [];
    if (count($users) > 0) {
        foreach ($users as $user) {
            $searchResults[] = [
                'user_id' => $user['user_id'],
                'username' => $user['username'],
                'name' => $user['name'],
                'avatar' => $user['avatar']
            ];
        }
    }

    // Send the search results as JSON response
    header('Content-Type: application/json');
    echo json_encode($searchResults);
    exit();
}

if (empty($articles)){
    $message = "There either has been no new posts, or you don't follow anyone.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Readit</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/all.css">
    <link rel="stylesheet" href="../css/font.css">
    <link rel="shortcut icon" href="../img/icon.png" type="image/x-icon">
    <link rel="icon" href="../img/favicon_32x32.png" sizes="32x32">
    <link rel="icon" href="../img/favicon_48x48.png" sizes="48x48">
    <link rel="icon" href="../img/favicon_96x96.png" sizes="96x96">
    <link rel="icon" href="../img/favicon_144x144.png" sizes="144x144">
</head>
<body>
<?php include "navbar.php"; ?>

<div class="container">
    <div class="row flex-column align-items-center">
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

        <h1 class="text-center mb-3">My Feed</h1>

        <?php if (isset($message)): ?>
            <div class="feed_empty-text"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="mb-5 mt-4 m-auto col-5 search-dropdown">
            <div class="search-input rounded-pill d-flex align-items-center">
            <i class="fa-regular fa-magnifying-glass"></i>
            <input type="text" class="form-control rounded-pill" id="searchQuery" name="searchQuery" placeholder="Search" autocomplete="off">
            </div>
            <div class="dropdown-menu mt-3" id="searchResults" aria-labelledby="searchQuery">
            </div>
        </div>

        
    <?php if (!empty($articles)): ?>
        <div class="articles w-100">
        <?php foreach ($articles as $article): ?>
            <div class="col-4 article me-1 mb-3">
            <a href="view_post.php?post_id=<?php echo $article['id']; ?>">
            <div class="card h-100 mb-3">
                <div class="card-header d-flex justify-content-between">
                <?php
            // Retrieve the publisher's information
            $publisherId = $article['user_id'];
            $stmt = $conn->prepare("SELECT name, username, avatar, verified FROM users WHERE user_id = ?");
            $stmt->bind_param("i", $publisherId);
            $stmt->execute();
            $result = $stmt->get_result();
            $publisher = $result->fetch_assoc();
            $stmt->close();
            ?>
            <div class="d-flex align-items-center">
            <div class="publisher-photo me-2">
            <img src="../img/avatars/<?php echo $publisher['avatar']; ?>" class="h-100 rounded-circle" alt="<?php echo $publisher['name']; ?>'s profile photo">
            </div>
            <div class="publisher">
            <p class="mb-0">
                                    <?php echo $publisher['name']; ?>
                                    <?php if($publisher['verified']): ?>
                                        <i class="fa-solid fa-badge-check text-primary"></i>
                                    <?php endif; ?>
                                    <br>
                                    <span><?php echo $publisher['username']; ?></span>
                                </p>
            </div>
            </div> 
            <span class="justify-content-end publish-date text-muted">
                <?php
                    
                    echo date("F j", strtotime($article["created_at"])); 
                ?>
            </span>  
                </div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $article['title']; ?></h5>
                    <p class="card-text"><?php echo substr($article['content'], 0, 200); ?>...</p>
                </div>
                <div class="card-footer d-flex align--items-center justify-content-center text-center">
                <p class="card-text d-inline mb-0 text-muted me-3"><i class="far fa-eye me-1"></i> <?php echo $article['views']; ?></p>
                <p class="card-text d-inline mb-0 text-muted"><i class="far fa-comments me-1"></i> <?php echo $article['comments']; ?></p>
                </div>
            </div>
            </a>
            </div>
            
        <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="popular-profiles">
        <ul class="list-group mb-4">
                <li class="list-group-item">Popular profiles</li>
                <?php foreach ($topUsers as $u): ?>
                    <?php
                // Check if the user is already following the profile user
                $stmt = $conn->prepare("SELECT * FROM user_followers WHERE follower_id = ? AND followed_id = ?");
                $stmt->bind_param("ii", $_SESSION['user_id'], $u['user_id']);
                $stmt->execute();
                $result = $stmt->get_result();
                $isFollowing = $result->num_rows > 0;
                $stmt->close();
                ?>
                    <?php if ($u['user_id'] !== $userId): ?>
                        <li class="list-group-item">
                            <a href="user.php?user_id=<?php echo $u['user_id']; ?>" class="list-group-link d-flex justify-content-between align-items-center">
                                <div class="d-flex">
                                <div class="list-group-img">
                                    <img src="../img/avatars/<?php echo $u['avatar']; ?>" alt="<?php echo $u['name']; ?>'s profile photo">
                                </div>
                                <p>
                                    <?php echo $u['name']; ?>
                                    <?php if($u['verified']): ?>
                                        <i class="fa-solid fa-badge-check text-primary"></i>
                                    <?php endif; ?>
                                    <br>
                                    <span>@<?php echo $u['username']; ?></span>
                                </p>
                                </div>
                                <form action="follow.php" method="POST">
                    <input type="hidden" name="user_id" value="<?php echo $u['user_id']; ?>">
                    <?php if ($isFollowing): ?>
                        <button type="submit" class="following-btn btn btn-outline-success w-100 disabled"><i class="fa-regular fa-check"></i></button>
                    <?php else: ?>
                        <button type="submit" class="follow-btn btn btn-outline-success w-100"><i class="fa-regular fa-plus me-2"></i>Follow</button>
                    <?php endif; ?>
                </form>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>

        </ul>
        </div>
    <?php endif; ?>
    </div>
</div>

<script src="../js/script.js"></script>
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
                                console.log(user);
                                // Check if the user is verified
                                var verificationBadge = user.verified ? '<i class="fa-solid fa-badge-check text-primary ms-1"></i>' : '';

                var dropdownItem = $('<a class="dropdown-item"></a>')
                    .attr('href', 'user.php?user_id=' + user.user_id) // Set user_id in the URL
                    .html('<div class="d-flex"><div class="publisher-photo me-2"><img src="../img/avatars/' + user.avatar + '" class="h-100 rounded-circle" alt=""></div><div class="publisher"><p class="mb-0">' + user.name + ''  + verificationBadge + '<br><span>@' + user.username + '</span></p></div></div> ');

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
