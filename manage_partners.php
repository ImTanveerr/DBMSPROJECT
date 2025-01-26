<?php
require('db_config.php');
require('essential.php');
adminLogin(); // Ensure the user is logged in

// Handle Delete Partner
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $con->prepare("DELETE FROM partners WHERE partner_id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_partners.php");
    exit();
}

// Handle Update Partner Status (Approve/Reject)
if (isset($_GET['update_status']) && isset($_GET['id'])) {
    $partner_id = $_GET['id'];
    $status = $_GET['update_status']; // 'active' or 'inactive'
    
    $stmt = $con->prepare("UPDATE partners SET status = ? WHERE partner_id = ?");
    $stmt->bind_param("si", $status, $partner_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_partners.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Partners</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse; /* Ensures borders are combined */
            background-color: white;
            margin-top: 20px;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd; /* Adding solid border for cells */
        }

        table th {
            background-color: #007bff;
            color: white;
            font-size: 16px;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        td.actions {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            padding: 6px 12px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
            font-size: 14px;
            text-align: center;
        }

        .delete-btn {
            background-color: #dc3545;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        .approve-btn {
            background-color: #28a745;
        }

        .approve-btn:hover {
            background-color: #218838;
        }

        .reject-btn {
            background-color: #ffc107;
        }

        .reject-btn:hover {
            background-color: #e0a800;
        }

        .content h2 {
            font-size: 26px;
            color: #007bff;
            margin-top: 0;
        }

        @media (max-width: 768px) {
            .content {
                margin-left: 0;
            }

            table th, table td {
                font-size: 14px;
                padding: 8px;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <?php include('sidebar.php'); ?>

    <!-- Main content -->
    <div class="content">
        <h2>Manage Partners</h2>

        <!-- Partners Table -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Service Type</th>
                    <th>Status</th>
                    <th class="actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch partners from the database
                $query = "SELECT * FROM partners";
                $result = $con->query($query);
                while ($row = $result->fetch_assoc()) {
                    // Display status as 'Active' or 'Inactive'
                    $status_display = ucfirst($row['status']); // Convert 'active' or 'inactive' to capitalized form
                    echo "<tr>
                        <td>" . htmlspecialchars($row['partner_id']) . "</td>
                        <td>" . htmlspecialchars($row['partner_name']) . "</td>
                        <td>" . htmlspecialchars($row['email']) . "</td>
                        <td>" . htmlspecialchars($row['contact_phone']) . "</td>
                        <td>" . htmlspecialchars($row['service_type']) . "</td>
                        <td>" . $status_display . "</td>
                        <td class='actions'>
                            <a href='?update_status=active&id=" . $row['partner_id'] . "' class='action-btn approve-btn'>Activate</a>
                            <a href='?update_status=inactive&id=" . $row['partner_id'] . "' class='action-btn reject-btn'>Deactivate</a>
                            <a href='?delete_id=" . $row['partner_id'] . "' class='action-btn delete-btn' onclick='return confirm(\"Are you sure?\");'>Delete</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
