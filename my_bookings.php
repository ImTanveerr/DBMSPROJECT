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
// Fetch user information
$user_id = $_SESSION['user_id'];

// Handle 'Remove from Cart' action
if (isset($_POST['remove_from_cart'])) {
    $cart_id = $_POST['cart_id'];

    // Delete service from cart
    $stmt = $con->prepare("DELETE FROM Cart WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cart_id, $user_id);
    if ($stmt->execute()) {
        $message = "Service successfully removed from cart!";
    } else {
        $message = "Error removing service from cart!";
    }
    $stmt->close();
}

// Handle 'Book' action
if (isset($_POST['book_service'])) {
    $cart_id = $_POST['cart_id'];

    // Fetch cart details
    $stmt = $con->prepare("
        SELECT c.*, s.service_name, s.cost 
        FROM Cart c
        JOIN ManageServices s ON c.service_id = s.id
        WHERE c.id = ? AND c.user_id = ? AND c.status = 'in_cart'
    ");
    $stmt->bind_param("ii", $cart_id, $user_id);
    $stmt->execute();
    $cart_item = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($cart_item) {
        // Calculate total cost based on the duration
        $start_date = new DateTime($cart_item['start_date']);
        $end_date = new DateTime($cart_item['end_date']);
        $duration = $end_date->diff($start_date)->days + 1; // Add 1 to include the start date
        $total_cost = $duration * $cart_item['cost'];

        // Insert into Bookings table with 'pending' status
        $stmt = $con->prepare("
        INSERT INTO Bookings (service_id, service_name, user_id, start_date, end_date, duration, total_cost, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')
    ");
    $stmt->bind_param("issssds", $cart_item['service_id'], $cart_item['service_name'], $user_id, 
                      $cart_item['start_date'], $cart_item['end_date'], $duration, $total_cost);
    
        if ($stmt->execute()) {
            // Remove service from cart after booking
            $stmt = $con->prepare("DELETE FROM Cart WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ii", $cart_id, $user_id);
            $stmt->execute();
            $stmt->close();

            // Redirect to payment page with the service details
            header("Location: payment.php?service_id=" . $cart_item['service_id'] . "&total_cost=" . $total_cost);
            exit;
        } else {
            $message = "Error adding to bookings!";
        }
    } else {
        $message = "Cart item not found!";
    }
}

// Fetch user's cart items
$cart_query = "
    SELECT c.*, s.service_name, s.venue, s.organizer, s.cost
    FROM Cart c
    JOIN ManageServices s ON c.service_id = s.id
    WHERE c.user_id = ? AND c.status = 'in_cart'
";
$stmt_cart = $con->prepare($cart_query);
$stmt_cart->bind_param("i", $user_id);
$stmt_cart->execute();
$cart_result = $stmt_cart->get_result();
$stmt_cart->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
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

        .action-btn {
            padding: 5px 10px;
            text-decoration: none;
            color: white;
            border-radius: 3px;
            font-size: 14px;
        }

        .remove-btn {
            background-color: #dc3545;
        }

        .remove-btn:hover {
            background-color: #c82333;
        }

        .book-btn {
            background-color: #28a745;
        }

        .book-btn:hover {
            background-color: #218838;
        }

        .message {
            color: #28a745;
            font-weight: bold;
            text-align: center;
        }

        .message.error {
            color: #dc3545;
        }
    </style>
</head>
<body>
<?php include('user_sidebar.php'); ?>

<div class="content">
    <h3>Your Bookings</h3>

    <?php if (isset($message)): ?>
        <div class="message <?= strpos($message, 'Error') !== false ? 'error' : '' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- Cart Table -->
    <table>
        <thead>
        <tr>
            <th>Service Name</th>
            <th>Venue</th>
            <th>Organizer</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Duration (days)</th>
            <th>Cost (per day)</th>
            <th>Total Cost</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $cart_result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['service_name']) ?></td>
                <td><?= htmlspecialchars($row['venue']) ?></td>
                <td><?= htmlspecialchars($row['organizer']) ?></td>
                <td><?= htmlspecialchars($row['start_date']) ?></td>
                <td><?= htmlspecialchars($row['end_date']) ?></td>
                <td><?= htmlspecialchars($row['duration']) ?></td>
                <td><?= htmlspecialchars($row['cost']) ?></td>
                <td><?= htmlspecialchars($row['total_cost']) ?></td>
                <td>
                    <!-- Remove from Cart Button -->
                    <form method="POST" action="">
                        <input type="hidden" name="cart_id" value="<?= $row['id'] ?>">
                        <button type="submit" name="remove_from_cart" class="action-btn remove-btn">Remove</button>
                    </form>
                    <br>
                    <!-- Book Button -->
                    <form method="POST" action="">
                        <input type="hidden" name="cart_id" value="<?= $row['id'] ?>">
                        <button type="submit" name="book_service" class="action-btn book-btn">Book</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
