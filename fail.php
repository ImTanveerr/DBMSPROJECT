<?php
require('db_config.php');  
require_once('essentialuser.php');  


// Fetch user_id from session
$user_id = $_SESSION['user_id'];

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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Failed</title>
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
            background-color: #dc3545;
            color: white;
        }
        table td {
            background-color: #f8f9fa;
        }
        .message {
            color: #dc3545;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="content">
        <h3>Payment Failed!</h3>
        <div class="message">
            <p>Unfortunately, your payment could not be processed. Please try again later.</p>
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
                    <td>Payment Failed</td>
                </tr>
            </tbody>
        </table>

        <p>Please check your payment details or contact support for assistance.</p>
    </div>
</body>
</html>
