<?php
// Include the necessary database connection code
require_once "db_connection.php";

// Check if the user is logged in
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Retrieve user's profile information from the database
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$current_avatar = $user['avatar'];

// Process the profile update form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_profile"])) {
    // Get the updated profile information from the form
    $username = $_POST["username"];
    $name = $_POST["name"];
    $email = $_POST["email"];

    // Update the user's profile information in the database
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, name = ? WHERE user_id = ?");
    $stmt->bind_param("sssi", $username, $email, $name, $_SESSION["user_id"]);
    $stmt->execute();
    $stmt->close();

    // Check if a file is uploaded
    if (isset($_FILES["avatar"]) && $_FILES["avatar"]["error"] == UPLOAD_ERR_OK) {
        // Get the user ID
        $userId = $_SESSION["user_id"];

        // Define the upload directory and file name
        $uploadDir = "../img/avatars/";
        $filename = $userId . "_" . basename($_FILES["avatar"]["name"]);
        $targetPath = $uploadDir . $filename;

        // Move the uploaded file to the target path
        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetPath)) {
            // Delete the previous avatar file if it exists
            if ($current_avatar != "") {
                unlink($uploadDir . $current_avatar);
            }

            // Update the user's avatar in the database
            $stmt = $conn->prepare("UPDATE users SET avatar = ? WHERE user_id = ?");
            $stmt->bind_param("si", $filename, $userId);
            $stmt->execute();
            $stmt->close();

        } else {
            // Failed to move the uploaded file
            $update_error =  "Error uploading avatar.";
        }
    }
    // Display a success message
    $update_success = "Profile uploaded successfully.";
}

// Process the password change form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["change_password"])) {
    // Get the password change information from the form
    $old_password = $_POST["old_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    // Check if the old password matches the one in the database
    $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stored_password = $row["password"];
    $stmt->close();

    if (password_verify($old_password, $stored_password)) {
        // Check if the new password and confirm password match
        if ($new_password === $confirm_password) {
            // Update the password in the database
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
            $stmt->bind_param("si", $new_password_hash, $_SESSION["user_id"]);
            $stmt->execute();
            $stmt->close();
        } else {
            $password_error = "New password and confirm password do not match.";
        }
    } else {
        $password_error = "Old password is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
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
    <?php if (isset($update_success)): ?>
        <div class="alert alert-success d-flex fade show justify-content-between" role="alert">
                <?php echo $update_success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
    <?php if (isset($update_error)): ?>
        <div class="alert alert-danger d-flex fade show justify-content-between" role="alert">
                <?php echo $update_error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
    <div class="row justify-content-between mb-5">

<div class="col-5">
    <!-- Profile update form -->
<h3>Update Profile Information</h3>

        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
<div class="mb-3">
                <label for="avatar" class="form-label d-block">Avatar</label>
                <input type="file" class="form-control" id="avatar" name="avatar" style="display: none;" onchange="checkImageResolution(this);">
                <img id="current-avatar" src="../img/avatars/<?php echo $current_avatar; ?>" class="rounded-circle" alt="Current Avatar" width="150" style="cursor: pointer;" onclick="document.getElementById('avatar').click();">
            </div>

    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php echo $user["name"]; ?>" required>
    </div>
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" value="<?php echo $user["username"]; ?>" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo $user["email"]; ?>" required>
    </div>
    <button type="submit" class="btn btn-success" name="update_profile">Update Profile</button>
</form>
</div>

<div class="col-5">
    <!-- Password change form -->
<h3>Change Password</h3>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
    <div class="mb-3">
        <label for="old_password" class="form-label">Old Password</label>
        <input type="password" class="form-control" id="old_password" name="old_password" required>
    </div>
    <div class="mb-3">
        <label for="new_password" class="form-label">New Password</label>
        <input type="password" class="form-control" id="new_password" name="new_password" required>
    </div>
    <div class="mb-3">
        <label for="confirm_password" class="form-label">Confirm Password</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
    </div>
    <?php if (isset($password_error)): ?>
        <div class="alert alert-danger fade show justify-content-between" role="alert">
                <?php echo $password_error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
    <button type="submit" class="btn btn-success" name="change_password">Change Password</button>
</form>
</div>
    </div>
</div>

<script src="../js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function checkImageResolution(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                var image = new Image();

                image.onload = function () {
                    var width = this.width;
                    var height = this.height;

                    console.log('got image');
                    var preview = document.getElementById("current-avatar");
                        preview.src = e.target.result;
                };

                image.src = e.target.result;
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
</script>

</body>
</html>
