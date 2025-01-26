<?php
require('db_config.php');
require('essential.php');

// Ensure the partner is logged in
if (!isset($_SESSION['partner_id'])) {
    header("Location: partnerlogin.php"); // Redirect to login if not logged in
    exit();
}

// Fetch partner name and basic details
$partner_id = $_SESSION['partner_id'];
$stmt = $con->prepare("SELECT partner_name FROM partners WHERE partner_id = ?");
$stmt->bind_param("i", $partner_id);
$stmt->execute();
$result = $stmt->get_result();
$partner = $result->fetch_assoc();
$stmt->close();

// Fetch service count
$service_count_stmt = $con->prepare("SELECT COUNT(*) AS total_services FROM manageservices WHERE organizer = ?");
$service_count_stmt->bind_param("i", $partner_id);
$service_count_stmt->execute();
$service_result = $service_count_stmt->get_result();
$service_count = $service_result->fetch_assoc()['total_services'];
$service_count_stmt->close();

// Fetch booking count using join
$booking_count_stmt = $con->prepare("
    SELECT COUNT(b.booking_id) AS total_bookings
    FROM bookings b
    JOIN manageservices ms ON b.service_id = ms.id
    WHERE ms.organizer = ?
");
$booking_count_stmt->bind_param("i", $partner_id);
$booking_count_stmt->execute();
$booking_result = $booking_count_stmt->get_result();
$booking_count = $booking_result->fetch_assoc()['total_bookings'];
$booking_count_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partner Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
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

        .card {
            margin-bottom: 20px;
        }

        .card-header {
            background-color: #007bff;
            color: #fff;
        }

        .card-body {
            font-size: 18px;
        }
    </style>
</head>
<body>
<?php include('partner_sidebar.php'); ?>
    <div class="container mt-5">
        <h2>Welcome, <?= htmlspecialchars($partner['partner_name']) ?></h2>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-concierge-bell"></i> Total Services
                    </div>
                    <div class="card-body">
                        <?= $service_count ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-bookmark"></i> Total Bookings
                    </div>
                    <div class="card-body">
                        <?= $booking_count ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>