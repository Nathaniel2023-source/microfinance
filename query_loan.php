<?php
$conn = new mysqli('localhost', 'root', '', 'fantsuam_finance');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT applicant_name, loan_amount, loan_purpose, applicant_image, submitted_at FROM loan_applications";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h1>Loan Applications Submitted</h1>";
    echo "<table border='1' cellpadding='8' cellspacing='0'>";
    echo "<thead><tr><th>Name</th><th>Amount Applied</th><th>Purpose</th><th>Image</th><th>Date Submitted</th></tr></thead>";
    echo "<tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['applicant_name']}</td>
                <td>₦" . number_format($row['loan_amount'], 2) . "</td>
                <td>{$row['loan_purpose']}</td>
                <td><img src='{$row['applicant_image']}' alt='Applicant Image' width='100'></td>
                <td>{$row['submitted_at']}</td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "No applications found.";
}

$conn->close();
?>
