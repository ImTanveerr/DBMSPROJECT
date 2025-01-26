<?php
require('db_config.php'); // Include database configuration

// Handle POST request to update total amount
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    // Fetch data from POST request
    $booking_id = $_POST['booking_id'];
    $service_id = $_POST['service_id'];

    // Step 1: Calculate the total amount
    // We join Bookings and manageservices tables to calculate the total amount (service cost * duration).
    $query = "
    SELECT 
        ms.cost * (DATEDIFF(b.end_date, b.start_date) + 1) AS total_amount
    FROM 
        Bookings b
    JOIN 
        manageservices ms ON b.service_id = ms.id
    WHERE 
        b.booking_id = ? AND ms.id = ?
    ";

    $stmt = $con->prepare($query);
    $stmt->bind_param("ii", $booking_id, $service_id);
    $stmt->execute();
    $stmt->bind_result($total_amount);
    $stmt->fetch();
    $stmt->close();

    // Check if the total_amount was calculated
    if ($total_amount === null) {
        die("Error: Invalid booking or service ID.");
    }

    // Step 2: Update the total_amount in Invoice table
    $updateQuery = "
        UPDATE Invoice i
        JOIN Bookings b ON i.booking_id = b.booking_id
        JOIN manageservices ms ON b.service_id = ms.id
        SET i.total_amount = ?
        WHERE i.booking_id = ? AND i.service_id = ?
    ";

    $stmt = $con->prepare($updateQuery);
    $stmt->bind_param("dii", $total_amount, $booking_id, $service_id);

    if ($stmt->execute()) {
        $message = "Total amount updated successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle delete action
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Delete the invoice record
    $deleteQuery = "DELETE FROM Invoice WHERE booking_id = ?";
    $stmt = $con->prepare($deleteQuery);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        $message = "Invoice deleted successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Invoices</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        header {
            background-color: #f8f9fa;
            padding: 15px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border-bottom: 1px solid #ccc;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #f8f9fa;
        }
        .action-btn {
            padding: 5px 10px;
            text-decoration: none;
            color: white;
            border-radius: 3px;
            font-size: 14px;
        }
        .update-btn {
            background-color: #28a745;
        }
        .update-btn:hover {
            background-color: #218838;
        }
        .delete-btn {
            background-color: #dc3545;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <!-- Sidebar (can be customized for your layout) -->
    <?php include('sidebar.php'); ?>

    <!-- Main content -->
    <div class="content">
        <h3>Manage Invoices</h3>

        <?php
        if (isset($message)) {
            echo "<p><strong>$message</strong></p>";
        }
        ?>

        <!-- Invoices Table -->
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Service ID</th>
                    <th>Total Amount</th>
                    <th>Payment Method</th>
                    <th>Invoice Status</th>
                    <th class="actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch invoices from the database
                $query = "SELECT * FROM Invoice";
                $result = $con->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>" . htmlspecialchars($row['booking_id']) . "</td>
                        <td>" . htmlspecialchars($row['service_id']) . "</td>
                        <td>" . htmlspecialchars($row['total_amount']) . "</td>
                        <td>" . htmlspecialchars($row['payment_method']) . "</td>
                        <td>" . htmlspecialchars($row['invoice_status']) . "</td>
                        <td class='actions'>
                            <a href='?update_id=" . $row['booking_id'] . "' class='action-btn update-btn'>Update</a>
                            <a href='?delete_id=" . $row['booking_id'] . "' class='action-btn delete-btn' onclick='return confirm(\"Are you sure?\");'>Delete</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
