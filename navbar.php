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

<nav class="navbar navbar-expand-lg mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">Readit.</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                <!-- <form action="search.php" method="GET" class="search-form form-group">
                    <input type="text" name="query" class="form-control" placeholder="Search articles...">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form> -->
                <li class="nav-item">
                        <a class="nav-link" href="add_article.php"><i class="fa-sharp far fa-circle-plus"></i></a>
                    </li>    
                    <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <?php
        // Fetch the user's avatar path from the database
        // Replace 'your_user_id' with the actual user ID of the logged-in user
        $stmt = $conn->prepare("SELECT avatar FROM users WHERE user_id = ?");
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $stmt->bind_result($avatar);
        $stmt->fetch();
        $stmt->close();

        // Display the user's avatar if the path is available
        if (!empty($avatar)) {
            echo '<img src="' . $avatar . '" class="avatar" alt="User Avatar">';
        } /* else {
            // Display a default avatar if the user does not have a custom avatar
            echo '<i class="fas fa-user-circle"></i>';
        } */
        ?>
    </a>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
        <li><a class="dropdown-item" href="#"><i class="fas fa-user"></i> Profile</a></li>
        <li><a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Settings</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li> -->
                </ul>
            </div>
        </div>
    </nav>