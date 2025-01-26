<?php
require('db_config.php');
require('essential.php');
adminLogin(); // Ensure the user is logged in

// Handle Add Service
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_service'])) {
    $service_name = $_POST['service_name'];
    $service_date = $_POST['service_date'];
    $venue = $_POST['venue'];
    $organizer_id = $_POST['organizer_id'];
    
    // Fetch the organizer name from the partners table using the organizer ID
    $stmt = $con->prepare("SELECT name FROM partners WHERE id = ?");
    $stmt->bind_param("i", $organizer_id);
    $stmt->execute();
    $stmt->bind_result($organizer_name);
    $stmt->fetch();
    $stmt->close();
    
    if ($organizer_name) {
        // Insert the service into the ManageServices table
        $stmt = $con->prepare("INSERT INTO ManageServices (service_name, service_date, venue, organizer, status) VALUES (?, ?, ?, ?, 'pending')");
        $stmt->bind_param("ssss", $service_name, $service_date, $venue, $organizer_name);
        $stmt->execute();
        $stmt->close();
        header("Location: manage_services.php"); // Refresh the page to display the new service
        exit();
    } else {
        echo "Organizer not found!";
    }
}

// Handle Delete Service
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $con->prepare("DELETE FROM ManageServices WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_services.php");
    exit();
}

// Handle Update Service Status (Approve/Reject)
if (isset($_GET['update_status']) && isset($_GET['id'])) {
    $service_id = $_GET['id'];
    $status = $_GET['update_status']; // 'approved' or 'rejected'
    
    $stmt = $con->prepare("UPDATE ManageServices SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $service_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_services.php"); // Redirect after status update
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services</title>
    <style>
        /* General styling */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
        }

        .content {
            margin-left: 250px;
            padding: 30px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px; /* Add spacing between rows */
            margin-top: 20px;
        }

        table th, table td {
            padding: 15px;
            text-align: left;
            border: 1px solid #ddd;
            background-color: white;
        }

        table th {
            background-color: #007bff;
            color: white;
            font-size: 16px;
            text-transform: uppercase;
        }

        table tr {
            border-radius: 8px; /* Add rounded corners to rows */
            overflow: hidden; /* Ensure corners are clipped */
        }

        table tr:hover {
            background-color: #f1f1f1; /* Highlight row on hover */
        }

        td.actions {
            display: flex;
            gap: 10px;
        }

        .action-btn {
            padding: 8px 15px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
            font-size: 14px;
            text-align: center;
            min-width: 80px;
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

        .content h3 {
            margin-top: 0;
            font-size: 28px;
            color:rgb(0, 0, 0);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .content {
                margin-left: 0;
            }

            table th, table td {
                font-size: 14px;
                padding: 8px;
            }
        }
    </style>
    <script>
        function confirmDeletion(serviceId) {
            return confirm(`Are you sure you want to delete service with ID ${serviceId}?`);
        }
    </script>
</head>
<body>
<?php include('sidebar.php'); ?>

    <!-- Main content -->
    <div class="content">
        <h3>Manage Services</h3>
        
        <!-- Services Table -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Service Name</th>
                    <th>Date</th>
                    <th>Venue</th>
                    <th>Organizer</th>
                    <th>Status</th>
                    <th class="actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch services from the database
                $query = "SELECT * FROM ManageServices";
                $result = $con->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>" . htmlspecialchars($row['id']) . "</td>
                        <td>" . htmlspecialchars($row['service_name']) . "</td>
                        <td>" . htmlspecialchars($row['service_date']) . "</td>
                        <td>" . htmlspecialchars($row['venue']) . "</td>
                        <td>" . htmlspecialchars($row['organizer']) . "</td>
                        <td>" . htmlspecialchars($row['status']) . "</td>
                        <td class='actions'>
                            <a href='?update_status=approved&id=" . $row['id'] . "' class='action-btn approve-btn'>Approve</a>
                            <a href='?update_status=rejected&id=" . $row['id'] . "' class='action-btn reject-btn'>Reject</a>
                            <a href='?delete_id=" . $row['id'] . "' class='action-btn delete-btn' onclick='return confirmDeletion(" . htmlspecialchars($row['id']) . ");'>Delete</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
