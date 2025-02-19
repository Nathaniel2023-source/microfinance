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

// Handle file upload
$image_path = "uploads/";
if (!is_dir($image_path)) {
    mkdir($image_path, 0777, true);
}

$image_name = $_FILES['applicant_image']['name'];
$image_tmp = $_FILES['applicant_image']['tmp_name'];
$image_full_path = $image_path . basename($image_name);

move_uploaded_file($image_tmp, $image_full_path);

// Insert data into database
$applicant_name = $_POST['applicant_name'];
$loan_amount = $_POST['loan_amount'];
$loan_purpose = $_POST['loan_purpose'];
$image_path_db = $image_full_path; // Save path to database

$sql = "INSERT INTO loan_applications (applicant_name, loan_amount, loan_purpose, applicant_image) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("siss", $applicant_name, $loan_amount, $loan_purpose, $image_path_db);

if ($stmt->execute()) {
    echo "Loan Application submitted successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
