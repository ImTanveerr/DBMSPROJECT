<?php
require('db_config.php');
require('essential.php');

// Ensure the partner is logged in
partnerLogin();

// Fetch partner ID from session
$partner_id = $_SESSION['partner_id'];

// Fetch partner name
$stmt = $con->prepare("SELECT partner_name FROM partners WHERE partner_id = ?");
$stmt->bind_param("i", $partner_id);
$stmt->execute();
$result = $stmt->get_result();
$partner = $result->fetch_assoc();
$stmt->close();
$partner_name = $partner['partner_name'];

// Handle update of a service
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_service'])) {
    $service_id = intval($_POST['service_id']);
    $service_name = trim($_POST['service_name']);
    $description = trim($_POST['description']);
    $cost = floatval($_POST['cost']);
    $service_date = trim($_POST['service_date']);
    $venue = trim($_POST['venue']);

    $stmt = $con->prepare("UPDATE manageservices SET service_name = ?, description = ?, cost = ?, service_date = ?, venue = ? WHERE id = ? AND organizer = ?");
    $stmt->bind_param("ssdssii", $service_name, $description, $cost, $service_date, $venue, $service_id, $partner_id);
    $stmt->execute();
    $stmt->close();
    header("Location: partner_services.php");
    exit();
}

// Fetch services added by the partner
$stmt = $con->prepare("SELECT id, service_name, description, cost, service_date, venue, status, service_category FROM manageservices WHERE organizer = ?");
$stmt->bind_param("i", $partner_id);
$stmt->execute();
$result = $stmt->get_result();
$services = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services</title>
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
            max-width: 1400px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(172, 18, 36, 0.1);
            border-radius: 8px;
        }

        .container h2 {
            color: #007bff;
            text-align: center;
            margin-bottom: 20px;
        }

        .table-responsive {
            margin-top: 20px;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .form-control {
            width: 100%;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>
<?php include('partner_sidebar.php'); ?>
    <div class="container mt-5">
        <h2>Manage Services</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead style="background-color:rgb(18, 42, 229); color: #fff;">
                    <tr>
                        <th>ID</th>
                        <th>Service Name</th>
                        <th>Description</th>
                        <th>Cost</th>
                        <th>Service Date</th>
                        <th>Venue</th>
                        <th>Status</th>
                        <th>Category</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $service): ?>
                        <tr>
                            <form method="POST" action="">
                                <td><?= htmlspecialchars($service['id']) ?></td>
                                <td><input type="text" name="service_name" class="form-control" value="<?= htmlspecialchars($service['service_name']) ?>" required></td>
                                <td><input type="text" name="description" class="form-control" value="<?= htmlspecialchars($service['description']) ?>"></td>
                                <td><input type="number" name="cost" class="form-control" step="0.01" value="<?= htmlspecialchars($service['cost']) ?>" required></td>
                                <td><input type="datetime-local" name="service_date" class="form-control" value="<?= htmlspecialchars($service['service_date']) ?>" required></td>
                                <td><input type="text" name="venue" class="form-control" value="<?= htmlspecialchars($service['venue']) ?>" required></td>
                                <td><?= htmlspecialchars($service['status']) ?></td>
                                <td><?= htmlspecialchars($service['service_category']) ?></td>
                                <td>
                                    <input type="hidden" name="service_id" value="<?= $service['id'] ?>">
                                    <button type="submit" name="update_service" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Update</button>
                                </td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>