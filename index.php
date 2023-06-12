<?php

    // Include the necessary database connection code
    require_once "pages/db_connection.php";
    
    session_start();
    if (isset($_SESSION["user_id"])) {
        // Redirect the user to the login page or display an error message
        header("Location: app.php");
        exit();
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>people</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="css/all.css">
    <link rel="shortcut icon" href="img/icon.png" type="image/x-icon">
    <link rel="icon" href="img/favicon_32x32.png" sizes="32x32">
    <link rel="icon" href="img/favicon_48x48.png" sizes="48x48">
    <link rel="icon" href="img/favicon_96x96.png" sizes="96x96">
    <link rel="icon" href="img/favicon_144x144.png" sizes="144x144">

</head>
<body>
<nav class="navbar navbar-expand-lg mb-4 landing-nav">
        <div class="container">
            <a class="navbar-brand" href="index.php">
<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60">
  <path d="m30,0h0C13.44,0,0,13.44,0,30h0c0,16.56,13.44,30,30,30h0c16.56,0,30-13.44,30-30h0C60,13.44,46.58,0,30,0Zm0,17.19c2.23,0,4.04,1.81,4.04,4.04s-1.81,4.04-4.04,4.04-4.04-1.81-4.04-4.04,1.81-4.04,4.04-4.04Zm16.14,12.51c-3.23,1.05-6.6,1.63-10.07,1.91v6.11c0,2.81-2.28,5.09-5.09,5.09h-1.93c-2.81,0-5.09-2.28-5.09-5.09v-6.11c-3.47-.28-6.86-.86-10.07-1.91-.89-.3-1.51-1.12-1.51-2.07,0-1.46,1.39-2.49,2.79-2.09,4.68,1.33,9.68,1.75,14.84,1.75s10.16-.42,14.84-1.75c1.4-.4,2.79.65,2.79,2.09-.02.93-.61,1.77-1.51,2.07Z"/>
</svg>
                people
            </a>
            <div class="navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                <!-- <form action="search.php" method="GET" class="search-form form-group">
                    <input type="text" name="query" class="form-control" placeholder="Search articles...">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form> -->  
                    <li class="nav-item mt-2 mb-2">
                        <a class="nav-link btn btn-outline-light me-3" href="pages/login.php">Login</a>
                    </li>
                    <li class="nav-item mt-2 mb-2">
                        <a class="nav-link btn btn-light" href="pages/signup.php">Join Now</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="header">
        <div class="container-fluid">
            <div class="row justify-content-center">
              <div class="col-10 text-center">
                <div class="text text-white">
                  <h2 class="text-center">Discover and Connect with Like-minded People</h2>
                  <p class="text-center">Expand Your Network and Unleash New Possibilities</p>
              </div>
              <img src="img/header-img.png" class="w-100" alt="">
              </div>
            </div>
        </div>
    </div>


<script src="js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
