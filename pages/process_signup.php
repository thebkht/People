<?php
    require_once "db_connection.php";

    $username = $_POST["username"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    if ($password === $confirmPassword) {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        if (!empty($username)) {
            // Insert the new user into the database
            $stmt = $conn->prepare("INSERT INTO users (name, username, email, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $username, $email, $hashedPassword);
            $stmt->execute();
            $stmt->close();

            // Redirect the user to the login page or any other desired page
            header("Location: login.php");
            exit();
        } else {
            echo "Username is required.";
        }
    } else {
        echo "Passwords do not match.";
    }
?>