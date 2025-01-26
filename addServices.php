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

// Initialize variables for error and success messages
$error = "";
$success = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_name = trim($_POST['service_name']);
    $description = trim($_POST['description']);
    $cost = trim($_POST['cost']);
    $service_date = trim($_POST['service_date']);
    $venue = trim($_POST['venue']);
    $service_category = trim($_POST['service_category']);

    // Validate inputs
    if (empty($service_name) || empty($cost) || empty($service_date) || empty($venue) || empty($service_category)) {
        $error = "Please fill in all required fields.";
    } else {
        // Insert into the database
        $stmt = $con->prepare("INSERT INTO manageservices (service_name, description, cost, service_date, venue, organizer, service_category) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param(
                "ssdssis",
                $service_name,
                $description,
                $cost,
                $service_date,
                $venue,
                $partner_id,
                $service_category
            );

            if ($stmt->execute()) {
                $success = "Service added successfully!";
            } else {
                $error = "Failed to add service. Please try again.";
            }

            $stmt->close();
        } else {
            $error = "Database error: Unable to prepare the statement.";
        }
    }
}

// Close database connection
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Service</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 255, 0.1);
            border-radius: 8px;
        }

        .container h2 {
            color: #28a745;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-group textarea {
            resize: vertical;
        }

        .form-group button {
            background: #28a745;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .form-group button:hover {
            background: #218838;
        }

        .message {
            text-align: center;
            margin-top: 15px;
        }

        .message.success {
            color: #28a745;
        }

        .message.error {
            color: #dc3545;
        }
    </style>
</head>
<body>
<?php include('partner_sidebar.php'); ?>
    <div class="container mt-5">
        <h2>Add New Service</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="service_name">Service Name</label>
                <input type="text" id="service_name" name="service_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label for="cost">Cost</label>
                <input type="number" id="cost" name="cost" class="form-control" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="service_date">Service Date</label>
                <input type="datetime-local" id="service_date" name="service_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="venue">Venue</label>
                <input type="text" id="venue" name="venue" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="service_category">Service Category</label>
                <input type="text" id="service_category" name="service_category" class="form-control" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Add Service</button>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>