<?php
require('DBConnect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Table - Silver Restaurant</title>
    <link rel="stylesheet" href="ref.css">
    <link rel="stylesheet" href="tableUAddForm.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-brand">
            <h1>🍽️ Silver Restaurant</h1>
        </div>
        <ul class="nav-menu">
            <li><a href="index.php">🏠 Home</a></li>
            <li><a href="employee.php">👨‍🍳 Employee</a></li>
            <li><a href="tableU.php">🪑 Table</a></li>
            <li><a href="order.php">🛒 Order</a></li>
            <li><a href="receipt.php">🧾 Receipt</a></li>
        </ul>
    </nav>

    <div class="hero-section">
        <h2 class="hero-title">Add New Table</h2>
        <p class="hero-subtitle">Fill in the form to add a new table</p>
    </div>

    <div class="form-wrapper">
        <div class="form-container">
            <form action="tableAdd.php" method="POST" class="add-form">
                <h2 class="form-title">Table Information</h2>
                
                <div class="form-group">
                    <label for="table_number">Table Number</label>
                    <input type="text" id="table_number" name="table_number" placeholder="e.g., Table 1" required>
                </div>

                <div class="form-group">
                    <label for="capacity">Capacity</label>
                    <input type="number" id="capacity" name="capacity" placeholder="Number of seats" min="1" required>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="">-- Select Status --</option>
                        <option value="available">Available</option>
                        <option value="occupied">Occupied</option>
                        <option value="reserved">Reserved</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" name="btn_add" class="btn-submit">Add Table</button>
                    <a href="tableU.php" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Silver Restaurant. All rights reserved.</p>
    </footer>
</body>
</html>
