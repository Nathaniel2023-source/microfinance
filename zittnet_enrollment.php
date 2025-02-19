<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date_enrollment = htmlspecialchars($_POST['date_enrollment']);
    $first_name = htmlspecialchars($_POST['first_name']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $phone = htmlspecialchars($_POST['Phone']);
    $contact_address = htmlspecialchars($_POST['contact_address']);
    $state = htmlspecialchars($_POST['state']);
    $local_government = htmlspecialchars($_POST['local_government']);
    $nationality = htmlspecialchars($_POST['nationality']);
    $course_of_study = htmlspecialchars($_POST['course_of_study']);
    $sponsor_name = htmlspecialchars($_POST['sponsor_name']);
    $sponsor_address = htmlspecialchars($_POST['sponsor_address']);
} else {
    die("Invalid request method.");
}

// Database connection
$host = "localhost";
$dbname = "kat_ictub";
$username = "kat_ictub";
$password = "";

$conn = mysqli_connect($host, $username, $password, $dbname);

if (mysqli_connect_error()) {
    die("Connection error: " . mysqli_connect_error());
}

// Insert query
$sql = "INSERT INTO enrollment (date_enrollment, first_name, last_name, phone, contact_address, state, local_government, nationality, course_of_study, sponsor_name, sponsor_address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    die("SQL Error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param(
    $stmt,
    "sssssssssss",
    $date_enrollment,
    $first_name,
    $last_name,
    $phone,
    $contact_address,
    $state,
    $local_government,
    $nationality,
    $course_of_study,
    $sponsor_name,
    $sponsor_address
);

if (mysqli_stmt_execute($stmt)) {
    // Redirect back to the form page after successful submission
    header("Location: zittnet_enrollment_form.html");
    exit();
} else {
    echo "Error: " . mysqli_stmt_error($stmt);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
