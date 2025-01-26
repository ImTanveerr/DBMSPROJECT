<?php
require('db_config.php');
require('essentialuser.php');

// Ensure the user is logged in
userlogin();

// Check if sort parameter is set in GET request
$sort_order = isset($_GET['sort']) && $_GET['sort'] == 'desc' ? 'DESC' : 'ASC';

// Modify the query to order by service_name
$query = "SELECT * FROM ManageServices WHERE status = 'approved' ORDER BY service_name $sort_order";
$result = $con->query($query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $service_id = $_POST['service_id'];
        $user_id = $_SESSION['user_id'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        // Validate dates
        if (strtotime($start_date) >= strtotime($end_date)) {
            $message = "End date must be after the start date.";
        } else {
            // Calculate duration
            $duration = (strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24); // Convert seconds to days

            // Fetch service cost
            $stmt = $con->prepare("SELECT cost FROM ManageServices WHERE id = ?");
            $stmt->bind_param("i", $service_id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if ($result) {
                $cost = $result['cost'];
                $total_cost = $duration * $cost;

                // Check if the service is already in the cart
                $stmt = $con->prepare("SELECT * FROM Cart WHERE user_id = ? AND service_id = ? AND status = 'in_cart'");
                $stmt->bind_param("ii", $user_id, $service_id);
                $stmt->execute();
                $existing_cart = $stmt->get_result();
                $stmt->close();

                if ($existing_cart->num_rows > 0) {
                    $message = "Service is already in your cart!";
                } else {
                    // Add service to the cart with calculated details
                    $stmt = $con->prepare("
                        INSERT INTO Cart (user_id, service_id, start_date, end_date, duration, total_cost, status) 
                        VALUES (?, ?, ?, ?, ?, ?, 'in_cart')");
                    $stmt->bind_param("iissid", $user_id, $service_id, $start_date, $end_date, $duration, $total_cost);
                    if ($stmt->execute()) {
                        $message = "Service added to cart successfully!";
                    } else {
                        $message = "Error adding service to cart.";
                    }
                    $stmt->close();
                }
            } else {
                $message = "Service not found!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Services</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            margin-top: 60px;
        }

        h3 {
            color: #007bff;
            margin-bottom: 20px;
            font-size: 2rem;
        }

        .message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .sort-options {
            margin-bottom: 20px;
        }

        .service-card {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            width: 100%;
            max-width: 300px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background-color: #ccc; /* Placeholder color */
        }

        .card-content {
            padding: 15px;
        }

        .card-title {
            font-size: 1.5rem;
            color: #007bff;
            margin-bottom: 10px;
        }

        .card-details {
            color: #555;
            margin-bottom: 10px;
        }

        .card-price {
            font-size: 1.2rem;
            color: #28a745;
            font-weight: bold;
        }

        .form-container {
            margin-top: 15px;
        }

        input[type="date"], button {
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
            border: 1px solid #ddd;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            margin-left: 10px;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php include('user_sidebar.php'); ?>
    <div class="content">
        <h3>Available Services</h3>

        <!-- Sort options form -->
        <div class="sort-options">
            <form method="GET" action="">
                <label for="sort">Sort by Name:</label>
                <select name="sort" id="sort">
                    <option value="asc" <?= isset($_GET['sort']) && $_GET['sort'] == 'asc' ? 'selected' : '' ?>>Ascending</option>
                    <option value="desc" <?= isset($_GET['sort']) && $_GET['sort'] == 'desc' ? 'selected' : '' ?>>Descending</option>
                </select>
                <button type="submit">Sort</button>
            </form>
        </div>

        <?php if (isset($message)): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="service-card">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="card">
                    <div style="width: 100%; height: 200px; background-color: #ccc; text-align: center; line-height: 200px; color: white;">Image</div>
                    <div class="card-content">
                        <h4 class="card-title"><?= htmlspecialchars($row['service_name']) ?></h4>
                        <p class="card-details">Venue: <?= htmlspecialchars($row['venue']) ?></p>
                        <p class="card-details">Category: <?= htmlspecialchars($row['service_category']) ?></p>
                        <p class="card-details">Description: <?= htmlspecialchars($row['description']) ?></p>
                        <p class="card-details">Organizer ID: <?= htmlspecialchars($row['organizer']) ?></p>
                        <p class="card-price">৳<?= htmlspecialchars($row['cost']) ?> / day</p>
                        <div class="form-container">
                            <form method="POST" action="">
                                <input type="hidden" name="service_id" value="<?= $row['id'] ?>">
                                <input type="date" name="start_date" required>
                                <input type="date" name="end_date" required>
                                <button type="submit" name="add_to_cart">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
