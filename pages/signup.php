<?php
// Include the necessary database connection code
require_once "db_connection.php";

// Initialize variables
$email = "";
$error = "";
$showForm = false;

// Process the signup form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];

    // Validate user input if needed

    // Check if the email is already registered
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user) {
        $error = "Email is already registered.";
        $showForm = false;
    } else {
        // Display the form to collect name, username, and password
        $showForm = true;
        // Proceed to the next step or redirect to another page
        // where you can collect new user details and save them to the database
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - people</title>
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
                <div class="row h-100 auth-page">
                    <div class="col-lg-5 d-flex flex-column justify-content-between background">
                        <div class="d-flex justify-con../tent-start">
                            <div class="image overflow-hidden d-flex justify-content-center">
                                <img src="../img/header-img.png" style="width: 110%;" class="mb-4" alt="">
                            </div>
                        </div>
                        <div class="text justify-content-end">
                            <h1 class="title m-5">Stay updated on your network</h1>
                            <div class="ask-btn d-flex justify-content-between align-items-center m-5">
                                <p class="text-white">Already have an account?</p>
                                <a href="login.php" class="btn pt-3 pb-3 p-5">Sign In</a>
                            </div>
                        </div>
                    </div>
                    <div class="signup-form col-lg-4 d-flex flex-column justify-content-center align-items-center">
                        <div class="logo">
                            <img src="../img/icon.png" class="m-4 mt-0" height="60px" alt="">
                        </div>
                        <h2>Sign Up</h2>
                        <p class="mb-3 text">Take the next step and sign in to your account</p>
                        <?php if (!$showForm) { ?>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" class="w-100 mt-3 signup-form">
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="email" required>
                <label for="floatingInput" class="label">Email address</label>
            </div>
            <div class="error"><?php echo $error; ?></div>
            <div class="col-auto">
                <button type="submit" class="btn btn-success w-100 pt-3 pb-3">Check Email</button>
            </div>
        </form>
    <?php } else { ?>
        <form action="process_signup.php" method="POST" class="w-100 mt-3 signup-form" id="signup-form">
        <div class="form-floating mb-3">
                <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="email" value="<?php echo $email; ?>" required readonly>
                <label for="floatingInput" class="label">Email address</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingName" placeholder="Your Name" name="name" required>
                <label for="floatingName" class="label">Your Name</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingUsername" placeholder="Username" name="username" required>
                <label for="floatingUsername" class="label">Username</label>
            </div>
            <h5 class="mb-3 signup-form__title">Security</h5>
                            <div class="form-floating mb-2 d-flex password-input">
                                <input type="password" class="form-control" id="Password" placeholder="Password" name="password" required>
                                <label for="Password">Password</label>
                                <span class="password-toggle" onclick="togglePasswordVisibility('Password', 'password-icon')">
        <i class="far fa-eye" id="password-icon"></i>
    </span>
                            </div>
                            <div class="form-floating mb-4 d-flex password-input">
                                <input type="password" class="form-control" id="ConfimPassword" placeholder="Password" name="confirm_password" required>
                                <label for="ConfimPassword">Confirm password</label>
                                <span class="password-toggle" onclick="togglePasswordVisibility('ConfimPassword', 'password-icon')">
        <i class="far fa-eye" id="password-icon"></i>
    </span>
                            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-success w-100 pt-3 pb-3">Sign Up</button>
            </div>
        </form>
    <?php } ?>

                    </div>
                </div>
            </div>
        </div>



        <script src="../js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>   
</body>
</html>