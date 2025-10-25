<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/db_config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Inventory Management System'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <h1>Inventory Management System</h1>
                <nav>
                    <ul>
                        <li><a href="dashboard.php">Dashboard</a></li>
                        <li><a href="view_inventory.php">View Inventory</a></li>
                        <li><a href="add_item.php">Add Item</a></li>
                        <li><a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <main>