<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $name = htmlspecialchars($_POST['name']);
    $message = htmlspecialchars($_POST['message']);
    $priority = htmlspecialchars($_POST['priority']);
    $type = htmlspecialchars($_POST['type']);
    $terms = isset($_POST['terms']) ? 'Agreed' : 'Not agreed';
} else {
    die("Invalid request method.");
}

// Database connection parameters
$host = "localhost";
$dbname = "message_db";
$username = "root";
$password = "";

// Corrected connection function
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (mysqli_connect_error()) {
    die("Connection error: " . mysqli_connect_error());
}

// Prepare an INSERT statement
$sql = "INSERT INTO message (name, message, priority, type) VALUES (?, ?, ?, ?)";
$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    die("SQL Error: " . mysqli_error($conn));
}

// Bind parameters (strings 'sss' because all are strings)
mysqli_stmt_bind_param($stmt, "ssss", $name, $message, $priority, $type);

// Execute the statement
if (mysqli_stmt_execute($stmt)) {
    echo "Record saved successfully!";
} else {
    echo "Error: " . mysqli_stmt_error($stmt);
}

// Close statement and connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
