<?php
$page_title = 'Add Item';
include 'includes/header.php';

$error = '';
$success = '';

$conn = getDBConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_item'])) {
        // Add new item
        $item_name = trim($_POST['item_name']);
        $description = trim($_POST['description']);
        $quantity = (int)$_POST['quantity'];
        $price = (float)$_POST['price'];
        $category = trim($_POST['category']);
        
        if (empty($item_name) || $quantity < 0 || $price < 0) {
            $error = "Please fill all required fields correctly.";
        } else {
            $stmt = $conn->prepare("INSERT INTO inventory_items (user_id, item_name, description, quantity, price, category) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issids", $_SESSION['user_id'], $item_name, $description, $quantity, $price, $category);
            
            if ($stmt->execute()) {
                $success = "Item added successfully!";
            } else {
                $error = "Failed to add item. Please try again.";
            }
            $stmt->close();
        }
    } elseif (isset($_POST['update_item'])) {
        // Update existing item
        $item_id = (int)$_POST['item_id'];
        $item_name = trim($_POST['item_name']);
        $description = trim($_POST['description']);
        $quantity = (int)$_POST['quantity'];
        $price = (float)$_POST['price'];
        $category = trim($_POST['category']);
        
        if (empty($item_name) || $quantity < 0 || $price < 0) {
            $error = "Please fill all required fields correctly.";
        } else {
            $stmt = $conn->prepare("UPDATE inventory_items SET item_name = ?, description = ?, quantity = ?, price = ?, category = ? WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ssiddii", $item_name, $description, $quantity, $price, $category, $item_id, $_SESSION['user_id']);
            
            if ($stmt->execute()) {
                $success = "Item updated successfully!";
            } else {
                $error = "Failed to update item. Please try again.";
            }
            $stmt->close();
        }
    } elseif (isset($_POST['delete_item'])) {
        // Delete item
        $item_id = (int)$_POST['item_id'];
        
        $stmt = $conn->prepare("DELETE FROM inventory_items WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $item_id, $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            $success = "Item deleted successfully!";
        } else {
            $error = "Failed to delete item. Please try again.";
        }
        $stmt->close();
    }
}

// Check if we're editing an item
$editing_item = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $item_id = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM inventory_items WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $item_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $editing_item = $result->fetch_assoc();
    $stmt->close();
}

$conn->close();
?>

<div class="container">
    <h2><?php echo $editing_item ? 'Edit Item' : 'Add New Item'; ?></h2>
    
    <?php if ($error): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="success-message"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <input type="hidden" name="item_id" value="<?php echo $editing_item ? $editing_item['id'] : ''; ?>">
        
        <div class="form-group">
            <label for="item_name">Item Name:</label>
            <input type="text" id="item_name" name="item_name" value="<?php echo $editing_item ? htmlspecialchars($editing_item['item_name']) : ''; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="3"><?php echo $editing_item ? htmlspecialchars($editing_item['description']) : ''; ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" min="0" value="<?php echo $editing_item ? $editing_item['quantity'] : '0'; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="price">Price ($):</label>
            <input type="number" id="price" name="price" step="0.01" min="0" value="<?php echo $editing_item ? $editing_item['price'] : '0.00'; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="category">Category:</label>
            <input type="text" id="category" name="category" value="<?php echo $editing_item ? htmlspecialchars($editing_item['category']) : ''; ?>">
        </div>
        
        <button type="submit" name="<?php echo $editing_item ? 'update_item' : 'add_item'; ?>" class="btn">
            <?php echo $editing_item ? 'Update Item' : 'Add Item'; ?>
        </button>
        
        <?php if ($editing_item): ?>
            <a href="add_item.php" class="btn btn-secondary">Cancel</a>
        <?php endif; ?>
    </form>
    
    <?php if (!$editing_item): ?>
        <h3>Delete Item</h3>
        <form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete this item?');">
            <div class="form-group">
                <label for="delete_item_id">Item ID to Delete:</label>
                <input type="number" id="delete_item_id" name="item_id" min="1" required>
            </div>
            <button type="submit" name="delete_item" class="btn btn-danger">Delete Item</button>
        </form>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>