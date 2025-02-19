<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $account_number = $_POST['account_number'];

    $conn = new mysqli('localhost', 'root', '', 'fantsuam_finance');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM client_enrollment WHERE account_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $account_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $client = $result->fetch_assoc();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Client Details</title>
            <style>
                body { font-family: Arial, sans-serif; background-color: #f4f4f9; padding: 20px; }
                .container { background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
                label { font-weight: bold; }
                input, select, button { margin-top: 10px; padding: 10px; width: 100%; }
            </style>
        </head>
        <body>
            <div class="container">
                <h3>Client Details</h3>
                <p>Name: <?php echo htmlspecialchars($client['name']); ?></p>
                <p>Address: <?php echo htmlspecialchars($client['address']); ?></p>
                <p>Phone No: <?php echo htmlspecialchars($client['phone_no']); ?></p>
                <p>Deposit Amount: <?php echo htmlspecialchars($client['deposit']); ?></p>

                <form action="update_deposit.php" method="POST">
                    <input type="hidden" name="account_number" value="<?php echo htmlspecialchars($client['account_number']); ?>">
                    <label for="deposit">Update Deposit Amount:</label>
                    <input type="number" name="deposit" step="0.01" required>

                    <label for="clerk">Clerk:</label>
                    <select name="clerk" required>
                        <option value="">Select Clerk</option>
                        <option value="Ibrahim">Ibrahim</option>
                        <option value="Stephen">Stephen</option>
                        <option value="Faith">Faith</option>
                        <option value="Richard">Richard</option>
                    </select>

                    <button type="submit">Update Deposit</button>
                </form>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "Account number not found.";
    }

    $stmt->close();
    $conn->close();
}
?>
