<?php
$conn = new mysqli('localhost', 'root', '', 'fantsuam_finance');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch today's deposits
$sql = "SELECT account_number, name, clerk, deposit_amount, deposit_date FROM deposit_log WHERE DATE(deposit_date) = CURDATE()";
$result = $conn->query($sql);

$clerks_summary = []; // To track the total deposits and count per clerk

echo "<h1>Today's Deposits Report</h1>";
echo "<table border='1' cellpadding='8' cellspacing='0'>";
echo "<thead><tr><th>Account Number</th><th>Client Name</th><th>Clerk</th><th>Deposit Amount</th><th>Deposit Date</th></tr></thead>";
echo "<tbody>";

$total_today = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['account_number']}</td>
                <td>{$row['name']}</td>
                <td>{$row['clerk']}</td>
                <td>{$row['deposit_amount']}</td>
                <td>{$row['deposit_date']}</td>
              </tr>";

        $total_today += $row['deposit_amount'];

        // Group deposits per clerk
        if (!isset($clerks_summary[$row['clerk']])) {
            $clerks_summary[$row['clerk']] = ['total' => 0, 'count' => 0];
        }
        $clerks_summary[$row['clerk']]['total'] += $row['deposit_amount'];
        $clerks_summary[$row['clerk']]['count'] += 1;
    }
} else {
    echo "<tr><td colspan='5'>No deposits made today.</td></tr>";
}

echo "</tbody></table>";

echo "<h2>Total Deposited Today: ₦" . number_format($total_today, 2) . "</h2>";

echo "<h2>Clerk Summary:</h2>";
echo "<table border='1' cellpadding='8' cellspacing='0'>";
echo "<thead><tr><th>Clerk</th><th>Total Amount Deposited</th><th>Number of Clients</th></tr></thead>";
echo "<tbody>";

foreach ($clerks_summary as $clerk => $summary) {
    echo "<tr>
            <td>$clerk</td>
            <td>₦" . number_format($summary['total'], 2) . "</td>
            <td>{$summary['count']}</td>
          </tr>";
}

echo "</tbody></table>";

$conn->close();
?>
