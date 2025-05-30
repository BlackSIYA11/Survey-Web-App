<?php
// __define-ocg__ Database connection file
$host = "localhost";
$user = "root";
$password = ""; // Default WAMP password is empty
$database = "survey_responses";

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
