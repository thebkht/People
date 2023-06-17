<?php
// Include the necessary database connection code
require_once "db_connection.php";

session_start();
if (!isset($_SESSION["user_id"])) {
    // Redirect the user to the login page or display an error message
    header("Location: login.php");
    exit();
}

$followerId = $_SESSION["user_id"];

// Check if the user_id is provided in the URL
if (isset($_GET["user_id"])) {
    $followedId = $_GET["user_id"];

    // Retrieve user information from the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $followedId);
    $stmt->execute();
    $result = $stmt->get_result();
    $member = $result->fetch_assoc();
    $stmt->close();

    // Retrieve user's articles from the database
    $stmt = $conn->prepare("SELECT * FROM articles WHERE user_id = ? ORDER BY `articles`.`created_at` DESC");
    $stmt->bind_param("i", $followedId);
    $stmt->execute();
    $result = $stmt->get_result();
    $articles = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Perform the follow action
    if (isset($_POST["user_id"])) {
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
    }

    // Retrieve the number of followers for the user
    $stmt = $conn->prepare("SELECT COUNT(*) FROM user_followers WHERE followed_id = ?");
    $stmt->bind_param("i", $followedId);
    $stmt->execute();
    $result = $stmt->get_result();
    $followerCount = $result->fetch_row()[0];
    $stmt->close();

    // Retrieve the number of articles for the user
    $postCount = count($articles);
}

// Check if the search query is provided in the GET request
if (isset($_GET["searchQuery"])) {
    $searchQuery = $_GET["searchQuery"];

    // Prepare the SQL statement to search for users
    $stmt = $conn->prepare("SELECT * FROM articles WHERE user_id = ? AND title = ? ");
    $searchTerm = "%$searchQuery%";
    $stmt->bind_param("is", $followedId, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $posts = $result->fetch_all(MYSQLI_ASSOC);
    $articles = $posts; 
    $stmt->close();

    // Prepare the search results as JSON
    $searchResults = [];
    if (count($posts) > 0) {
        foreach ($posts as $post) {
            $searchResults[] = [
                'post_id' => $post['id'],
                'title' => $post['title'],
                'content' => $post['content'],
                'avatar' => $post['avatar']
            ];
        }
    }

    // Send the search results as JSON response
    header('Content-Type: application/json');
    echo json_encode($searchResults);
    exit();
}

// Calculate the time since the user joined
$joinedDateTime = new DateTime($member["created_at"]);
$currentDateTime = new DateTime();
$timeSinceJoined = $joinedDateTime->diff($currentDateTime);

// Format the time since joined
if ($timeSinceJoined->y > 0) {
    $joinedText = "Joined " . $timeSinceJoined->y . " year" . ($timeSinceJoined->y > 1 ? "s" : "") . " ago";
} elseif ($timeSinceJoined->m > 0) {
    $joinedText = "Joined " . $timeSinceJoined->m . " month" . ($timeSinceJoined->m > 1 ? "s" : "") . " ago";
} elseif ($timeSinceJoined->d > 7) {
        $weeks = floor($timeSinceJoined->d / 7);
        $joinedText = "Joined " . $weeks . " week" . ($weeks > 1 ? "s" : "") . " ago";
} elseif ($timeSinceJoined->d > 0) {
    $joinedText = "Joined " . $timeSinceJoined->d . " day" . ($timeSinceJoined->d > 1 ? "s" : "") . " ago";
} else {
    $joinedText = "Joined recently";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>
        <?php 
            if (isset($member["name"])){
                echo $member["name"];
            } else{
                echo $member["username"];
            }
        ?> - people
    </title>
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
    <script src="../js/jquery.min.js"></script>
    <script src="../js/load.js"></script>
</head>
<body style="background-color: #fff;">
<div id="load">
  <div class="loading"><img src="../img/icon.png" height="50" id="load-img" alt=""></div>
</div>

<div id="page" class="page">
    <div class="app">
    </div>
</div>
<?php include "navbar.php"; ?>

<div class="container">
    <div class="row justify-content-between mb-5">
        <div class="col-lg-3">
            <div class="user">
            <div class="profile-photo mb-3">
                <img src="../img/avatars/<?php echo $member['avatar']; ?>" class="h-100 rounded-circle" alt="<?php echo $member['name']; ?>'s profile photo">
            </div>
            <h5>
                <?php echo $member['name']; ?>
                <?php if ($member['verified']): ?>
                    <i class="fa-solid fa-badge-check text-primary ms-1"></i>
                <?php endif; ?>
            </h5>
            <h6 class="mb-3" style="color: #949494">@<?php echo $member['username']; ?></h6>
            <p class="d-flex align-items-center"><i class="fa-regular fa-envelope me-1"></i><?php echo $member['email']; ?></p>
            <p class="d-flex align-items-center"><i class="far fa-rocket-launch me-1"></i><?php echo $joinedText; ?></p>
            <div class="userInfo-buttons mb-3">
            <button class="btn me-3" data-bs-toggle="modal" data-bs-target="#followersModal" data-userid="<?php echo $member['user_id']; ?>"><b><?php echo $followerCount; ?></b> Followers</button>

                <button class="btn"><b><?php echo $postCount; ?></b> Articles</button>
            </div>

            <!-- Follow Button -->
            <?php if ($_SESSION['user_id'] !== $member['user_id']): ?>
                <?php
                // Check if the user is already following the profile user
                $stmt = $conn->prepare("SELECT * FROM user_followers WHERE follower_id = ? AND followed_id = ?");
                $stmt->bind_param("ii", $followerId, $followedId);
                $stmt->execute();
                $result = $stmt->get_result();
                $isFollowing = $result->num_rows > 0;
                $stmt->close();
                ?>

                <form action="user.php?user_id=<?php echo $followedId; ?>" method="POST">
                    <input type="hidden" name="user_id" value="<?php echo $followedId; ?>">
                    <?php if ($isFollowing): ?>
                        <button type="submit" class="btn btn-outline-success w-100 mb-5">Following</button>
                    <?php else: ?>
                        <button type="submit" class="btn btn-success w-100 mb-5">Follow</button>
                    <?php endif; ?>
                </form>
                <?php else: ?>
                <a href="edit_profile.php" class="btn btn-outline-success w-100 mb-5">Edit Profile</a>
            <?php endif; ?>
            </div>

            <div class="user-scroll">
            <div class="user-scroll_header">
            <div class="profile-photo mb-3 me-2">
                <img src="../img/avatars/<?php echo $member['avatar']; ?>" class="h-100 rounded-circle" alt="<?php echo $member['name']; ?>'s profile photo">
            </div>
            <div class="user-scroll_user">
            <span class="user-scroll_name d-block">
                <?php echo $member['name']; ?>
                <?php if ($member['verified']): ?>
                    <i class="fa-solid fa-badge-check text-primary ms-1"></i>
                <?php endif; ?>
            </span>
            <span class="mb-3 user-scroll_username">@<?php echo $member['username']; ?></span>
            </div>
            </div>

            <!-- Follow Button -->
            <?php if ($_SESSION['user_id'] !== $member['user_id']): ?>
                <?php
                // Check if the user is already following the profile user
                $stmt = $conn->prepare("SELECT * FROM user_followers WHERE follower_id = ? AND followed_id = ?");
                $stmt->bind_param("ii", $followerId, $followedId);
                $stmt->execute();
                $result = $stmt->get_result();
                $isFollowing = $result->num_rows > 0;
                $stmt->close();
                ?>

                <form action="user.php?user_id=<?php echo $followedId; ?>" method="POST">
                    <input type="hidden" name="user_id" value="<?php echo $followedId; ?>">
                    <?php if ($isFollowing): ?>
                        <button type="submit" class="btn btn-outline-success">Following</button>
                    <?php else: ?>
                        <button type="submit" class="btn btn-success">Follow</button>
                    <?php endif; ?>
                </form>
                <?php else: ?>
                <a href="edit_profile.php" class="btn btn-outline-success">Edit Profile</a>
            <?php endif; ?>
        </div>
        </div>
        
        

        <div class="col-lg-8">
            <h2>Articles</h2>
            <div class="user-articles">
            <div class="mb-5 mt-4 w-100 m-auto col-6 search-dropdown">
            <div class="search-input rounded-pill w-100 d-flex align-items-center">
            <i class="fa-regular fa-magnifying-glass"></i>
            <input type="text" class="form-control w-100 rounded-pill" id="searchQuery" name="searchQuery" placeholder="Search" autocomplete="off">
            </div>
            <div class="dropdown-menu mt-3 w-100" id="searchResults" aria-labelledby="searchQuery">
            </div>
        </div>
            <?php if (count($articles) > 0): ?>
                <?php foreach ($articles as $article): ?>
                    <div class="card mb-3 w-100 me-3">
                        <div class="card-body">
                        <a href="view_post.php?post_id=<?php echo $article['id']; ?>"><h5 class="card-title"><?php echo $article['title']; ?></h5></a>
                            <p class="card-text"><?php echo substr($article['content'], 0, 500); ?>...</p>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                        <?php if ($_SESSION['user_id'] === $article['user_id']): ?>
                            <div class="action-btn">
                            <a href="edit_post.php?post_id=<?php echo $article['id']; ?>" class="btn btn-success">Edit</a>
                            <a href="delete_post.php?post_id=<?php echo $article['id']; ?>" class="btn btn-danger">Delete</a>
                            </div>
                        <?php endif; ?>
                        <div class="stats d-flex align-items-center">
                        <p class="card-text d-inline mb-0 me-3"><?php echo date("F j Y, H:i", strtotime($article["created_at"])); ?></p>
                            <p class="card-text d-inline mb-0 text-muted me-3"><i class="far fa-eye me-1"></i> <?php echo $article['views']; ?></p>
                <p class="card-text d-inline mb-0 text-muted"><i class="far fa-comments me-1"></i> <?php echo $article['comments']; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>This user has no posts</p>
            <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="followersModal" tabindex="-1" aria-labelledby="followersModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="followersModalLabel">Followers</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="followersList"></div> <!-- Empty div to display followers -->
      </div>
    </div>
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
                    url: 'search_article_ajax.php?user_id=' + <?php echo $followedId; ?>,
                    method: 'GET',
                    data: { searchQuery: searchQuery },
                    dataType: 'json',
                    success: function(response) {
                        // Clear previous search results
                        searchResultsContainer.empty();

                        // Display search results using Bootstrap dropdown
                        if (response.length > 0) {
                            response.forEach(function(post) {
                                console.log(post);
                                var dropdownItem = $('<a class="dropdown-item"></a>')
                                .attr('href', 'view_post.php?post_id=' + post.post_id) // Set user_id in the URL
                                .text(post.title);
                                searchResultsContainer.append(dropdownItem);
                            });
                        } else {
                            var dropdownItem = $('<a class="dropdown-item disabled"></a>').text('No article found');
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

<script>
  var modalBody = document.querySelector('#followersModal .modal-body');

  function updateFollowersModal(userId) {
    modalBody.innerHTML = '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';

    // Send AJAX request to get followers data
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readyState === 4 && xhr.status === 200) {
        var followers = JSON.parse(xhr.responseText);
        modalBody.innerHTML = '';

        if (followers.length > 0) {
          var followersList = document.createElement('ul');
          followersList.classList.add('list-group');
          followersList.classList.add('followers-list');

          followers.forEach(function(follower) {
            console.log(follower);
            // Check if the user is verified
            var verificationBadge = follower['verified'] ? '<i class="fa-solid fa-badge-check text-primary ms-1"></i>' : '';

            var listItem = document.createElement('li');
            listItem.classList.add('list-group-item');
            var listContent = document.createElement('a');
            listContent.classList.add('list-item');
            listContent.setAttribute('href', 'user.php?user_id=' + follower['user_id']);
            listContent.innerHTML = '<div class="d-flex"><div class="publisher-photo me-2"><img src="../img/avatars/' + follower['avatar'] + '" class="h-100 rounded-circle" alt=""></div><div class="publisher"><p class="mb-0">' + follower['name'] + ''  + verificationBadge + '<br><span>@' + follower['username'] + '</span></p></div></div> ';
            listItem.appendChild(listContent);
            followersList.appendChild(listItem);
          });

          modalBody.appendChild(followersList);
        } else {
          modalBody.textContent = 'No followers found.';
        }
      } else if (xhr.readyState === 4) {
        modalBody.textContent = 'Failed to load followers.';
      }
    };

    xhr.open('GET', 'get_followers.php?user_id=' + userId, true);
    xhr.send();
  }

  document.querySelector('#followersModal').addEventListener('show.bs.modal', function(event) {
    var button = event.relatedTarget;
    var userId = button.getAttribute('data-userid');
    updateFollowersModal(userId);
  });
</script>



</body>
</html>
