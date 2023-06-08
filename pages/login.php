<?php
// Include the necessary database connection code
require_once "db_connection.php";

// Check if the user is already logged in
session_start();
if (isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

// Process the login form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Check if the username exists in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user && password_verify($password, $user["password"])) {
        // Store the user ID in the session
        $_SESSION["user_id"] = $user["user_id"];

        // Redirect the user to the homepage
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Readit</title>
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
        <div class="content d-flex p-4">
            <div class="container-fluid">
                <div class="row h-100">
                    <div class="col-5 d-flex flex-column justify-content-between background">
                        <div class="d-flex justify-content-start">
                            <div class="image overflow-hidden d-flex justify-content-center">
                                <img src="../img/header-img.png" style="width: 110%;" class="mb-4" alt="">
                            </div>
                        </div>
                        <div class="text justify-content-end">
                            <h1 class="title m-5">Stay updated on your network</h1>
                            <div class="ask-btn d-flex justify-content-between align-items-center m-5">
                                <p class="text-white">You don't have an account?</p>
                                <a href="signup.php" class="btn pt-3 pb-3 p-5">Sign Up</a>
                            </div>
                        </div>
                    </div>
                    <div class="signup-form col-4 d-flex flex-column justify-content-center align-items-center">
                        <div class="logo">
                            <img src="../img/icon.png" class="m-3 mb-2" height="60px" alt="">
                        </div>
                        <h2>Sign In</h2>
                        <p class="mb-3 text">Take the next step and sign in to your account</p>
                        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" class="w-100 mt-4 login-form">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" placeholder="username" id="username" name="username" required>
                                <label for="username" class="label">Username</label>
                            </div>
                            <div class="form-floating d-flex mb-3 password-input">
                                <input type="password" class="form-control password" placeholder="Password" id="password" name="password" required>
                                <label class="label" for="password">Password</label>
                                <span class="password-toggle" onclick="togglePasswordVisibility('password', 'password-icon')">
        <i class="far fa-eye" id="password-icon"></i>
    </span>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-success w-100 pt-3 pb-3">Sign In</button>
                              </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>



    <script src="../js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>