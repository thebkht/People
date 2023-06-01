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

// Process the profile update form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_profile"])) {
    // Get the updated profile information from the form
    $username = $_POST["username"];
    $email = $_POST["email"];

    // Update the user's profile information in the database
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE user_id = ?");
    $stmt->bind_param("ssi", $username, $email, $_SESSION["user_id"]);
    $stmt->execute();
    $stmt->close();

    // Redirect the user back to the profile page
    header("Location: profile.php");
    exit();
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

            // Redirect the user back to the profile page
            header("Location: profile.php");
            exit();
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
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include "navbar.php"; ?>

<div class="container">
    <h1>Edit Profile</h1>

    <!-- Profile update form -->
    <h3>Update Profile Information</h3>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Name</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo $user["username"]; ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $user["email"]; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary" name="update_profile">Update Profile</button>
    </form>

    <hr>

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
            <div class="alert alert-danger" role="alert">
                <?php echo $password_error; ?>
            </div>
        <?php endif; ?>
        <button type="submit" class="btn btn-primary" name="change_password">Change Password</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-gPcuybB0syW5DjCZVDCDj4FCOBubVUoTLV9iDCDXjzfOiR7SLw6eSKbWJ4EODCK6" crossorigin="anonymous"></script>
</body>
</html>
