<?php
$page_title = 'View Inventory';
include 'includes/header.php';

$conn = getDBConnection();

// Handle search and filter
$search = '';
$category_filter = '';
$sort_by = 'item_name';
$sort_order = 'ASC';

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

if (isset($_GET['category'])) {
    $category_filter = trim($_GET['category']);
}

if (isset($_GET['sort_by']) && in_array($_GET['sort_by'], ['item_name', 'quantity', 'price', 'category'])) {
    $sort_by = $_GET['sort_by'];
}

if (isset($_GET['sort_order']) && in_array($_GET['sort_order'], ['ASC', 'DESC'])) {
    $sort_order = $_GET['sort_order'];
}

// Build query based on filters
$sql = "SELECT * FROM inventory_items WHERE user_id = ?";
$params = [$_SESSION['user_id']];
$types = "i";

if (!empty($search)) {
    $sql .= " AND (item_name LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= "ss";
}

if (!empty($category_filter)) {
    $sql .= " AND category = ?";
    $params[] = $category_filter;
    $types .= "s";
}

$sql .= " ORDER BY $sort_by $sort_order";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Get categories for filter dropdown
$category_stmt = $conn->prepare("SELECT DISTINCT category FROM inventory_items WHERE user_id = ? AND category IS NOT NULL AND category != ''");
$category_stmt->bind_param("i", $_SESSION['user_id']);
$category_stmt->execute();
$category_result = $category_stmt->get_result();
$categories = [];
while ($row = $category_result->fetch_assoc()) {
    $categories[] = $row['category'];
}
$category_stmt->close();
?>

<div class="container">
    <h2>Inventory Items</h2>
    
    <!-- Search and Filter Form -->
    <div class="filter-section">
        <form method="GET" action="">
            <div class="form-row">
                <div class="form-group">
                    <input type="text" name="search" placeholder="Search items..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                
                <div class="form-group">
                    <select name="category">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category); ?>" <?php echo $category_filter === $category ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <select name="sort_by">
                        <option value="item_name" <?php echo $sort_by === 'item_name' ? 'selected' : ''; ?>>Name</option>
                        <option value="quantity" <?php echo $sort_by === 'quantity' ? 'selected' : ''; ?>>Quantity</option>
                        <option value="price" <?php echo $sort_by === 'price' ? 'selected' : ''; ?>>Price</option>
                        <option value="category" <?php echo $sort_by === 'category' ? 'selected' : ''; ?>>Category</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <select name="sort_order">
                        <option value="ASC" <?php echo $sort_order === 'ASC' ? 'selected' : ''; ?>>Ascending</option>
                        <option value="DESC" <?php echo $sort_order === 'DESC' ? 'selected' : ''; ?>>Descending</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn">Filter</button>
                    <a href="view_inventory.php" class="btn btn-secondary">Clear</a>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Inventory Table -->
    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="inventory-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                            <td><?php echo htmlspecialchars($item['description']); ?></td>
                            <td class="<?php echo $item['quantity'] < 5 ? 'low-stock' : ''; ?>">
                                <?php echo $item['quantity']; ?>
                                <?php if ($item['quantity'] < 5): ?>
                                    <span class="low-stock-badge">Low Stock</span>
                                <?php endif; ?>
                            </td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($item['category']); ?></td>
                            <td>
                                <a href="add_item.php?edit=<?php echo $item['id']; ?>" class="btn btn-small">Edit</a>
                                <form method="POST" action="add_item.php" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                    <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" name="delete_item" class="btn btn-small btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="no-items">
            <p>No inventory items found.</p>
            <a href="add_item.php" class="btn">Add New Item</a>
        </div>
    <?php endif; ?>
    
    <?php
    $stmt->close();
    $conn->close();
    ?>
</div>

<?php include 'includes/footer.php'; ?>