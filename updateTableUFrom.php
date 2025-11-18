<?php
require('DBConnect.php');

if (isset($_GET['table_id'])) {

    $table_id = $_GET['table_id'];

    $select_edit = $db->query("SELECT * FROM table_info WHERE table_id = $table_id");
    $select_edit->execute();

    $row = $select_edit->fetch(PDO::FETCH_ASSOC);
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Table - Silver Restaurant</title>
    <link rel="stylesheet" href="ref.css">
    <link rel="stylesheet" href="updateTableUFrom.css">
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
        <h2 class="hero-title">Edit Table</h2>
        <p class="hero-subtitle">Fill in the form to add a new table</p>
    </div>

    <div class="form-wrapper">
        <div class="form-container">

            <!-- ส่งข้อมูลไปที่ updateTableU.php เพื่ออัพเดทข้อมูล (ไม่ใช่ tableAdd.php) -->
            <form action="updateTableU.php" method="POST" class="add-form">
                <h2 class="form-title">Table Information</h2>
                
                <div class="form-group">
                    <label for="table_number">Table Number</label>
                    <!-- แสดงค่าเดิมจากฐานข้อมูล -->
                    <input type="text" id="table_number" name="table_number" placeholder="e.g., Table 1"
                    value="<?php echo $row['table_number']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="capacity">Capacity</label>
                    <!-- แสดงค่าเดิมจากฐานข้อมูล -->
                    <input type="number" id="capacity" name="capacity" placeholder="Number of seats" min="1" 
                    value="<?php echo $row['capacity']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <!-- <select> ไม่มี attribute value ต้องใช้ selected ใน <option> แทน -->
                    <select id="status" name="status" required>
                        <option value="">-- Select Status --</option>
                        <!-- ใช้ Ternary Operator เช็คว่า status ตรงกับค่าในฐานข้อมูลไหม ถ้าใช่แสดง selected -->
                        <option value="available" <?php echo ($row['status'] == 'available') ? 'selected' : ''; ?>>Available</option>
                        <option value="occupied" <?php echo ($row['status'] == 'occupied') ? 'selected' : ''; ?>>Occupied</option>
                        <option value="reserved" <?php echo ($row['status'] == 'reserved') ? 'selected' : ''; ?>>Reserved</option>
                    </select>
                </div>

                <!-- ซ่อน table_id ไว้ส่งไปด้วยเพื่อให้รู้ว่าจะอัพเดทแถวไหน -->
                <input type="hidden" name="table_id" value="<?php echo $row['table_id']; ?>">

                <div class="form-actions">
                    <button type="submit" name="btn_update" class="btn-submit">Edit</button>
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