<?php
$servername = "localhost";
$username = "root";
$password = ""; // Change to your actual MySQL password
$dbname = "user_signup"; // Change to your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>