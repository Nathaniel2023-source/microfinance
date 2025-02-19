<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $account_number = $_POST['account_number'];
    $deposit = (float)$_POST['deposit'];
    $clerk = $_POST['clerk'];

    $conn = new mysqli('localhost', 'root', '', 'fantsuam_finance');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch the current deposit and client name
    $sql = "SELECT deposit, name FROM client_enrollment WHERE account_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $account_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $new_balance = $row['deposit'] + $deposit;
        $name = $row['name'];

        // Update the deposit in the client_enrollment table
        $update_sql = "UPDATE client_enrollment SET deposit = ?, clerk = ? WHERE account_number = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param('dss', $new_balance, $clerk, $account_number);

        if ($update_stmt->execute()) {
            // Log the deposit into deposit_log table
            $log_sql = "INSERT INTO deposit_log (account_number, name, clerk, deposit_amount) VALUES (?, ?, ?, ?)";
            $log_stmt = $conn->prepare($log_sql);
            $log_stmt->bind_param('sssd', $account_number, $name, $clerk, $deposit);
            $log_stmt->execute();
            $log_stmt->close();

            echo "<script>alert('Deposit updated successfully. New balance: $new_balance'); window.location.href='search-account-form.html';</script>";
        } else {
            echo "<script>alert('Error updating deposit: " . $conn->error . "'); window.location.href='search-account-form.html';</script>";
        }

        $update_stmt->close();
    } else {
        echo "<script>alert('Account not found.'); window.location.href='search-account-form.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
