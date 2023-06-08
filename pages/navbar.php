<?php
// Include the necessary database connection code
require_once "db_connection.php";

if (!isset($_SESSION["user_id"])) {
    // Redirect the user to the login page or display an error message
    header("Location: login.php");
    exit();
}

$userId = $_SESSION["user_id"];

?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<nav class="navbar navbar-expand-lg mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="../img/icon.png" height="30" alt="">
                Readit.
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                <!-- <form action="search.php" method="GET" class="search-form form-group">
                    <input type="text" name="query" class="form-control" placeholder="Search articles...">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form> -->
                <li class="nav-item">
                        <a class="nav-link add-btn" href="add_article.php">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#1A1919" viewBox="0 0 32 32" class="icon iconButton__icon"><path fill-rule="evenodd" d="M16.75 12a.75.75 0 00-1.5 0v3.25H12a.75.75 0 000 1.5h3.25V20a.75.75 0 001.5 0v-3.25H20a.75.75 0 000-1.5h-3.25V12z" clip-rule="evenodd"></path><path fill-rule="evenodd" d="M16 6.75A9.25 9.25 0 006.75 16 9.25 9.25 0 0016 25.25 9.25 9.25 0 0025.25 16 9.25 9.25 0 0016 6.75zM5.25 16c0-5.938 4.813-10.75 10.75-10.75 5.938 0 10.75 4.813 10.75 10.75 0 5.938-4.813 10.75-10.75 10.75-5.938 0-10.75-4.813-10.75-10.75z" clip-rule="evenodd"></path></svg>
                    </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="fa-regular fa-rectangle-history" style="padding-top: 5px;"></i></a>
                    </li>        
                    <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    <?php
    // Fetch the user's avatar path from the database
    // Replace 'your_user_id' with the actual user ID of the logged-in user
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    // Display the user's avatar if the path is available
    if (!empty($user['avatar'])) {
        echo '<img src="../img/avatars/' . $user['avatar'] . '" class="avatar rounded-circle me-2" alt="">';
    } else {
        // Display a default avatar if the user does not have a custom avatar
        echo '<i class="fas fa-user-circle"></i>';
    }

    echo $user['name'];
?>


    </a>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
        <li><a class="dropdown-item" href="user.php?user_id=<?php echo $_SESSION['user_id']; ?>"><i class="far fa-user"></i> Profile</a></li>
        <li><a class="dropdown-item" href="edit_profile.php"><i class="far fa-cog"></i> Settings</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="logout.php"><i class="far fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li> -->
                </ul>
            </div>
        </div>
    </nav>