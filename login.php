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
    <title>Sign In | Readit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/all.css">
    <link rel="shortcut icon" href="img/icon.png" type="image/x-icon">
    <link rel="icon" href="img/favicon_32x32.png" sizes="32x32">
    <link rel="icon" href="img/favicon_48x48.png" sizes="48x48">
    <link rel="icon" href="img/favicon_96x96.png" sizes="96x96">
    <link rel="icon" href="img/favicon_144x144.png" sizes="144x144">
    <script src="js/jquery.min.js"></script>
    <script src="js/load.js"></script>
</head>
<body>
    <div id="load">
        <div class="loading"><img src="img/icon.png" id="load-img" alt=""></div>
    </div>
    <div id="page" class="page">
        <div class="content d-flex p-4">
            <div class="container-fluid">
                <div class="row h-100">
                    <div class="col-5 d-flex flex-column justify-content-between background">
                        <div class="d-flex justify-content-start">
                            <div class="image overflow-hidden d-flex justify-content-center">
                                <img src="img/header-img.png" style="width: 110%;" class="mb-4" alt="">
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
                            <img src="img/icon.png" class="m-3 mb-2" height="60px" alt="">
                        </div>
                        <h2>Sign In</h2>
                        <p class="mb-3 text">Take the next step and sign in to your account</p>
                        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" class="w-100 mt-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" placeholder="username" id="username" name="username">
                                <label for="username" class="label">Username</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control password" placeholder="Password" id="password" name="password">
                                <label class="label" for="password">Password</label>
                                <span class="password-toggle" onclick="togglePasswordVisibility('password', 'password-icon')">
        <i class="far fa-eye" id="password-icon"></i>
    </span>
                            </div>
                            <div class="signin-libks d-flex justify-content-between">
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                      Remember me
                                    </label>
                                </div>
                                <a href="" class="reset d-flex justify-content-end">Reset password</a>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-success w-100 pt-3 pb-3">Sign In</button>
                              </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>    

    <script>
        function togglePasswordVisibility(inputId, iconId) {
    var passwordInput = document.getElementById(inputId);
    var passwordIcon = document.getElementById(iconId);

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        passwordIcon.classList.remove("fa-eye");
        passwordIcon.classList.add("fa-eye-slash");
    } else {
        passwordInput.type = "password";
        passwordIcon.classList.remove("fa-eye-slash");
        passwordIcon.classList.add("fa-eye");
    }
}
    </script>
</body>
</html>

<!-- <!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-4 m-auto mt-5">
            <h1>Login</h1>

<?php if (isset($error_message)): ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $error_message; ?>
    </div>
<?php endif; ?>

<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary w-100 mb-3">Login</button>
</form>
<p>Don't have an account? <a href="signup.php">Sign up</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-gPcuybB0syW5DjCZVDCDj4FCOBubVUoTLV9iDCDXjzfOiR7SLw6eSKbWJ4EODCK6" crossorigin="anonymous"></script>
</body>
</html> -->
