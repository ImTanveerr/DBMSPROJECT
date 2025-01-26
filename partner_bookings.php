<?php
require('db_config.php');
require('essential.php');

// Ensure the partner is logged in
partnerLogin();

// Fetch partner ID from session
$partner_id = $_SESSION['partner_id'];

// Fetch bookings related to the partner's services
$query = "
    SELECT 
        b.booking_id,
        b.user_id,
        b.service_id,
        b.status AS booking_status,
        b.total_cost,
        s.service_name,
        s.venue,
        s.service_date,
        u.username AS customer_name,
        u.email AS customer_email
    FROM bookings AS b
    INNER JOIN manageservices AS s ON b.service_id = s.id
    INNER JOIN users AS u ON b.user_id = u.id
    WHERE s.organizer = ?
    ORDER BY b.booking_id DESC
";

$stmt = $con->prepare($query);
$stmt->bind_param("i", $partner_id);
$stmt->execute();
$result = $stmt->get_result();
$bookings = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .container h2 {
            color: #007bff;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        table th {
            background-color: #007bff;
            color: #fff;
        }

        .no-data {
            text-align: center;
            color: #666;
            font-size: 16px;
        }

        .status-pending {
            color: #ffc107;
        }

        .status-approved {
            color: #28a745;
        }

        .status-rejected {
            color: #dc3545;
        }
    </style>
</head>
<body>
<?php include('partner_sidebar.php'); ?>
    <div class="container">
        <h2>Booking Details</h2>

        <?php if (!empty($bookings)): ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Service Name</th>
                        <th>Customer Name</th>
                        <th>Customer Email</th>
                        <th>Venue</th>
                        <th>Service Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $index => $booking): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($booking['service_name']); ?></td>
                            <td><?php echo htmlspecialchars($booking['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($booking['customer_email']); ?></td>
                            <td><?php echo htmlspecialchars($booking['venue']); ?></td>
                            <td><?php echo htmlspecialchars($booking['service_date']); ?></td>
                            <td><?php echo number_format($booking['total_cost'], 2); ?></td>
                            <td class="status-<?php echo strtolower($booking['booking_status']); ?>">
                                <?php echo ucfirst($booking['booking_status']); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">No bookings found for your services.</p>
        <?php endif; ?>
    </div>
</body>
</html>
