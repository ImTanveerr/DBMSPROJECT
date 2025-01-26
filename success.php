<?php
require('db_config.php');  
require_once('essentialuser.php');  

// Fetch user_id from session
//$user_id = $_SESSION['user_id'];

// Get the service_id from the query string
if (isset($_GET['service_id'])) {
    $service_id = $_GET['service_id'];
} else {
    echo "Service ID not provided.";
    exit;
}

// Fetch service details based on service_id
$stmt = $con->prepare("SELECT service_name, cost, venue FROM ManageServices WHERE id = ?");
$stmt->bind_param("i", $service_id);
$stmt->execute();
$stmt->bind_result($service_name, $service_cost, $venue);
$stmt->fetch();
$stmt->close();

if (empty($service_name)) {
    echo "Service not found.";
    exit;
}

// Calculate the total amount (cost of the service)
$amount = $service_cost;

// Update cart status to 'booked'
$stmt = $con->prepare("UPDATE Cart SET status = 'booked' WHERE user_id = ? AND service_id = ? AND status = 'in_cart'");
$stmt->bind_param("ii", $user_id, $service_id);
if ($stmt->execute()) {
    // Successfully updated the cart status
} else {
    echo "Error updating cart status.";
    exit;
}
$stmt->close();

// Display the service details
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Success</title>
    <style>
        .content {
            margin: 20px;
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
            background-color: #007bff;
            color: white;
        }
        table td {
            background-color: #f8f9fa;
        }
        .message {
            color: #28a745;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="content">
        <h3>Booking Successful!</h3>
        <div class="message">
            <p>Your service has been booked successfully. Here are the details:</p>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Service Name</th>
                    <th>Venue</th>
                    <th>Cost</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= htmlspecialchars($service_name) ?></td>
                    <td><?= htmlspecialchars($venue) ?></td>
                    <td><?= htmlspecialchars($service_cost) ?> BDT</td>
                    <td>Booked</td>
                </tr>
            </tbody>
        </table>

        <p>Thank you for your payment! We will process your booking shortly.</p>
    </div>
</body>
</html>
