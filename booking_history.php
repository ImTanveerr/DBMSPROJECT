<?php
require('db_config.php');
require('essentialuser.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: userlogin.php');
    exit(); // Stop further execution of the script
}
?>
<?php
// Fetch user bookings
$user_id = $_SESSION['user_id'];

// Updated query using 'booking_id' instead of 'id'
$stmt = $con->prepare("SELECT b.booking_id, b.service_name, b.start_date, b.end_date, b.total_cost, b.status
                        FROM bookings b
                        JOIN ManageServices s ON b.service_id = s.id
                        WHERE b.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .navbar a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            font-size: 16px;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 28px;
            font-weight: 700;
            color: #343a40;
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        .table th {
            background-color: #007bff;
            color: white;
        }
        .table td {
            background-color: #f8f9fa;
        }
        .status {
            font-weight: 500;
        }
        .status.pending {
            color: #ffc107;
        }
        .status.confirmed {
            color: #28a745;
        }
        .status.canceled {
            color: #dc3545;
        }
        .btn-group {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }
        .btn {
            text-decoration: none;
            display: inline-block;
            padding: 12px 20px;
            background-color: #007bff;
            color: white;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            text-align: center;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php include('user_sidebar.php'); ?>

  
    <!-- Main Content -->
    <div class="container">
        <h1>My Bookings</h1>

        <!-- Booking Table -->
        <?php if ($result->num_rows > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Service Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Total Cost (৳)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['service_name']) ?></td>
                            <td><?= htmlspecialchars($row['start_date']) ?></td>
                            <td><?= htmlspecialchars($row['end_date']) ?></td>
                            <td><?= htmlspecialchars(number_format($row['total_cost'], 2)) ?></td>
                            <td class="status <?= strtolower($row['status']) ?>">
                                <?= htmlspecialchars(ucfirst($row['status'])) ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No bookings found.</p>
        <?php endif; ?>

        <!-- Dashboard Options -->
        <div class="btn-group">
            <a href="services.php" class="btn">View Available Services</a>
            <a href="user_dashboard.php" class="btn">Go to Dashboard</a>
        </div>
    </div>
</body>
</html>
