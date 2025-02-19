<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fantsuam_finance";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $amount = htmlspecialchars($_POST['amount']);
    $purpose = htmlspecialchars($_POST['purpose']);

    $sql = "INSERT INTO loan_applications (name, email, phone, amount, purpose) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssis", $name, $email, $phone, $amount, $purpose);

    if ($stmt->execute()) {
        echo "Application submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
