<?php
// Database configuration
$servername = "localhost";   // Replace with your MySQL server name
$username = "root";          // Replace with your MySQL username
$password = "";      // Replace with your MySQL password
$dbname = "readit";            // Replace with your MySQL database name

// Create a new MySQLi instance
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
