<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enrol_date = $_POST['enrol_date'];
    $account_number = $_POST['account_number'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone_no = $_POST['phone_no'];
    $date_birth = $_POST['date_birth'];
    $next_kin = $_POST['next_kin'];
    $relationship = $_POST['relationship'];
    $checked_by = $_POST['checked_by'];

    $passport = $_FILES['passport'];

    // Basic Validation
    if (empty($enrol_date) || empty($account_number) || empty($name) || empty($address) ||
        empty($phone_no) || empty($date_birth) || empty($next_kin) || empty($relationship) || empty($checked_by)) {
        die("All fields are required.");
    }

    // File Upload Handling
    $upload_dir = 'uploads/';
    $passport_path = $upload_dir . basename($passport['name']);
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    if (!move_uploaded_file($passport['tmp_name'], $passport_path)) {
        die("Failed to upload passport.");
    }

    // Database Connection (Assuming MySQL)
    $conn = new mysqli('localhost', 'root', '', 'fantsuam_finance');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO client_enrollment (enrol_date, account_number, name, address, phone_no, date_birth, next_kin, relationship, checked_by, passport_path)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssssss', $enrol_date, $account_number, $name, $address, $phone_no, $date_birth, $next_kin, $relationship, $checked_by, $passport_path);

    if ($stmt->execute()) {
        header("Location: microfinace-home.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
