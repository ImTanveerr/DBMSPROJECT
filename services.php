<?php
require('db_config.php');
require('essentialuser.php');

// Ensure the user is logged in
//userlogin();

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

// Default query
$query = "SELECT * FROM ManageServices WHERE status = 'approved'";

// Add filters
$filters = [];
if (!empty($_GET['category'])) {
    $filters[] = "service_category = '" . $con->real_escape_string($_GET['category']) . "'";
}
if (!empty($filters)) {
    $query .= " AND " . implode(' AND ', $filters);
}

// Add sorting
if (!empty($_GET['sort'])) {
    if ($_GET['sort'] === 'price_asc') {
        $query .= " ORDER BY cost ASC";
    } elseif ($_GET['sort'] === 'price_desc') {
        $query .= " ORDER BY cost DESC";
    }
}

// Execute the query
$result = $con->query($query);
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

        .filter-container {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 8px;
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .filter-container label {
            font-size: 1rem;
            color: #333;
        }

        .filter-container select, .filter-container button {
            padding: 8px 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .filter-container button {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .filter-container button:hover {
            background-color: #0056b3;
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
        
        <?php if (isset($message)): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="filter-container">
            <form method="GET" action="">
                <label for="category">Category:</label>
                <select name="category" id="category">
                    <option value="">All Categories</option>
                    <?php
                    $categories = $con->query("SELECT DISTINCT service_category FROM ManageServices WHERE status = 'approved'");
                    while ($cat = $categories->fetch_assoc()) {
                        $selected = isset($_GET['category']) && $_GET['category'] === $cat['service_category'] ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($cat['service_category']) . "' $selected>" . htmlspecialchars($cat['service_category']) . "</option>";
                    }
                    ?>
                </select>

                <label for="sort">Sort By:</label>
                <select name="sort" id="sort">
                    <option value="">Default</option>
                    <option value="price_asc" <?= isset($_GET['sort']) && $_GET['sort'] === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
                    <option value="price_desc" <?= isset($_GET['sort']) && $_GET['sort'] === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
                </select>

                <button type="submit">Apply Filters</button>
            </form>
        </div>

        <div class="service-card">
        <?php while ($row = $result->fetch_assoc()): ?> 
        <div class="card">
            <img src="Event-Planning.jpg" alt="Service Image">
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
