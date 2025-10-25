<?php
$page_title = 'Dashboard';
include 'includes/header.php';

$conn = getDBConnection();

// Get total items count
$stmt = $conn->prepare("SELECT COUNT(*) as total_items FROM inventory_items WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$total_items = $result->fetch_assoc()['total_items'];

// Get total quantity
$stmt = $conn->prepare("SELECT SUM(quantity) as total_quantity FROM inventory_items WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$total_quantity = $result->fetch_assoc()['total_quantity'] ?? 0;

// Get low stock items (less than 5)
$stmt = $conn->prepare("SELECT COUNT(*) as low_stock FROM inventory_items WHERE user_id = ? AND quantity < 5");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$low_stock = $result->fetch_assoc()['low_stock'];

$stmt->close();
$conn->close();
?>

<div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    
    <div class="dashboard-stats">
        <div class="stat-card">
            <h3><?php echo $total_items; ?></h3>
            <p>Total Items</p>
        </div>
        
        <div class="stat-card">
            <h3><?php echo $total_quantity; ?></h3>
            <p>Total Quantity</p>
        </div>
        
        <div class="stat-card">
            <h3><?php echo $low_stock; ?></h3>
            <p>Low Stock Items</p>
        </div>
    </div>
    
    <div class="dashboard-actions">
        <a href="add_item.php" class="btn">Add New Item</a>
        <a href="view_inventory.php" class="btn">View Inventory</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>