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
    <title>Sign Up | workster</title>
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
        <div class="content d-flex p-4" style="height: 100vh;">
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
                                <p class="text-white">Already have an account?</p>
                                <a href="login.php" class="btn pt-3 pb-3 p-5">Sign In</a>
                            </div>
                        </div>
                    </div>
                    <div class="signup-form col-4 d-flex flex-column justify-content-center align-items-center">
                        <div class="logo">
                            <img src="img/icon.png" class="m-5 mt-0" height="60px" alt="">
                        </div>
                        <h2>Sign Up</h2>
                        <p class="mb-3 text">Take the next step and sign in to your account</p>
                        <?php if (!$showForm) { ?>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" class="w-100 mt-4">
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="email">
                <label for="floatingInput" class="label">Email address</label>
            </div>
            <div class="error"><?php echo $error; ?></div>
            <div class="col-auto">
                <button type="submit" class="btn btn-success w-100 pt-3 pb-3">Check Email</button>
            </div>
        </form>
    <?php } else { ?>
        <form action="process_signup.php" method="POST" class="w-100 mt-4">
        <div class="form-floating mb-3">
                <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="email" value="<?php echo $email; ?>">
                <label for="floatingInput" class="label">Email address</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingName" placeholder="Your Name" name="name">
                <label for="floatingName" class="label">Your Name</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingUsername" placeholder="Username" name="username">
                <label for="floatingUsername" class="label">Username</label>
            </div>
            <h5 class="mb-3 signup-form__title">Security</h5>
                            <div class="form-floating mb-2">
                                <input type="password" class="form-control" id="Password" placeholder="Password" name="password">
                                <label for="Password">Password</label>
                                <span class="password-toggle" onclick="togglePasswordVisibility('password', 'password-icon')">
        <i class="fas fa-eye" id="password-icon"></i>
    </span>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control" id="ConfimPassword" placeholder="Password" name="confirm_password">
                                <label for="ConfimPassword">Confirm password</label>
                                <span class="password-toggle" onclick="togglePasswordVisibility('password', 'password-icon')">
        <i class="fas fa-eye" id="password-icon"></i>
    </span>
                            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-success w-100 pt-3 pb-3">Sign Up</button>
            </div>
        </form>
    <?php } ?>


                        <!-- <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" class="w-100 mt-4">
        <?php if (!$showForm) { ?>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="email">
                <label for="floatingInput" class="label">Email address</label>
            </div>
            <div class="error"><?php echo $error; ?></div>
            <div class="col-auto">
                <button type="submit" class="btn btn-success w-100 pt-3 pb-3">Check Email</button>
            </div>
        <?php } else { ?>
            <div class="form-floating mb-2">
                                <input type="email" class="form-control" id="email" placeholder="name@example.com" value="<?php echo $email; ?>">
                                <label for="email" class="label">Email address</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control" id="firstname" placeholder="name@example.com" name="name">
                                <label for="firstname" class="label">First Name</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control" id="firstname" placeholder="name@example.com" name="username">
                                <label for="firstname" class="label">Username</label>
                            </div>
                            <h5 class="mb-3 signup-form__title">Security</h5>
                            <div class="form-floating mb-2">
                                <input type="password" class="form-control" id="Password" placeholder="Password" name="password">
                                <label for="Password">Password</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control" id="ConfimPassword" placeholder="Password" name="confirm_password">
                                <label for="ConfimPassword">Confirm password</label>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-success w-100 pt-3 pb-3">Submit and Continue</button>
                              </div>
        <?php } ?> -->
    </form>
                        <!-- <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" class="w-100 mt-4">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                                <label for="floatingInput" class="label">Email address</label>
                            </div>
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                  I agree with the terms and conditions
                                </label>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-success w-100 pt-3 pb-3">Sign Up</button>
                              </div>
                        </form> -->
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>    
</body>
</html>