<?php
require('db_config.php');
require('essential.php');
adminLogin(); // Ensure the user is logged in

// Handle Ban/Unban
if (isset($_GET['ban_id'])) {
    $ban_id = $_GET['ban_id'];
    $stmt = $con->prepare("UPDATE users SET status = 'banned' WHERE id = ?");
    $stmt->bind_param("i", $ban_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_users.php"); // Redirect to refresh page
    exit();
}

if (isset($_GET['unban_id'])) {
    $unban_id = $_GET['unban_id'];
    $stmt = $con->prepare("UPDATE users SET status = 'active' WHERE id = ?");
    $stmt->bind_param("i", $unban_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_users.php"); // Redirect to refresh page
    exit();
}

// Handle Delete User
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $con->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <style>
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

        .ban-btn {
            background-color: #ffc107;
        }

        .ban-btn:hover {
            background-color: #e0a800;
        }

        .unban-btn {
            background-color: #28a745;
        }

        .unban-btn:hover {
            background-color: #218838;
        }

        .content h3 {
            margin-top: 0;
            font-size: 28px;
            color:rgb(5, 5, 5);
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
        function confirmDeletion(userId) {
            return confirm(`Are you sure you want to delete user with ID ${userId}?`);
        }
    </script>
</head>
<body>
<?php include('sidebar.php'); ?>

    <!-- Main content -->
    <div class="content">
        <h3>Manage Users</h3>
        
        <!-- Users Table -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th class="actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch users from the database
                $query = "SELECT * FROM users";
                $result = $con->query($query);
                while ($row = $result->fetch_assoc()) {
                    $status = $row['status'] === 'banned' ? 'Banned' : 'Active';
                    $banAction = $row['status'] === 'banned' 
                                 ? "<a href='?unban_id=" . $row['id'] . "' class='action-btn unban-btn'>Unban</a>"
                                 : "<a href='?ban_id=" . $row['id'] . "' class='action-btn ban-btn'>Ban</a>";

                    echo "<tr>
                            <td>" . htmlspecialchars($row['id']) . "</td>
                            <td>" . htmlspecialchars($row['username']) . "</td>
                            <td>" . htmlspecialchars($row['email']) . "</td>
                            <td>" . htmlspecialchars($status) . "</td>
                            <td class='actions'>
                                $banAction
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
