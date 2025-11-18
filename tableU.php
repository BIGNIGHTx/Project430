<?php
// เชื่อมต่อฐานข้อมูล SQLite ผ่านไฟล์ DBConnect.php
// ไฟล์นี้จะสร้างตาราง table_info และเตรียมข้อมูลตัวอย่างโต๊ะให้พร้อมใช้งาน
require('DBConnect.php');

if (isset($_GET['table_id'])) {

    $table_id = $_GET['table_id'];

    $deleteTable = $db->prepare("DELETE FROM table_info WHERE table_id = :table_id");
    $deleteTable->bindParam(':table_id', $table_id);
    $deleteTable->execute();

    header('refresh:1; url=tableU.php');

    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Management - Silver Restaurant</title>
    <link rel="stylesheet" href="ref.css">
    <link rel="stylesheet" href="tableU.css">
    <style>
        
    </style>
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
            
            <li><a href="OrderUi.php">🛒 Order</a></li>
            <li><a href="Receipt.php">🧾 Receipt</a></li>
        </ul>
    </nav>

    <div class="hero-section">
        <h2 class="hero-title">Table Management</h2>
        <p class="hero-subtitle">Manage restaurant tables and reservations</p>
    </div>

    <div class="menu-container">
        <div class="menu-header">
            <h2 class="section-title">Tables Overview</h2>
            <a href="tableUAddForm.php" class="add-menu-btn" style="text-decoration: none; display: inline-block;">
                ➕ Add New Table
            </a>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-container">
            <div class="stat-card stat-total">
                <div class="stat-number" id="totalTables">0</div>
                <div class="stat-label">Total Tables</div>
            </div>
            <div class="stat-card stat-available">
                <div class="stat-number" id="availableTables">0</div>
                <div class="stat-label">Available</div>
            </div>
            <div class="stat-card stat-occupied">
                <div class="stat-number" id="occupiedTables">0</div>
                <div class="stat-label">Occupied</div>
            </div>
            <div class="stat-card stat-reserved">
                <div class="stat-number" id="reservedTables">0</div>
                <div class="stat-label">Reserved</div>
            </div>
        </div>

        <!-- Legend -->
        <div class="legend">
            <div class="legend-item">
                <div class="legend-color green"></div>
                <span>Available</span>
            </div>
            <div class="legend-item">
                <div class="legend-color red"></div>
                <span>Occupied</span>
            </div>
            <div class="legend-item">
                <div class="legend-color yellow"></div>
                <span>Reserved</span>
            </div>
        </div>

    <!-- Add/ -->
    <div id="tableModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                
                
            </div>
            <form id="tableForm" class="menu-form" action="tableAdd.php" method="post">
                <input type="hidden" id="tableId" name="table_id">
                
                <div class="form-group">
                    <label for="tableNumber">Table Number *</label>
                    <input type="text" id="tableNumber" name="table_number" class="form-input" placeholder="e.g., Table 1, VIP-A" required>
                </div>

                <div class="form-group">
                    <label for="tableCapacity">Seating Capacity *</label>
                    <input type="number" id="tableCapacity" name="capacity" class="form-input" placeholder="Number of seats" min="1" required>
                </div>

                <div class="form-group">
                    <label for="tableStatus">Initial Status *</label>
                    <select id="tableStatus" name="status" class="form-select" required>
                        <option value="available">Available</option>
                        <option value="occupied">Occupied</option>
                        <option value="reserved">Reserved</option>
                    </select>
                </div>

                
            </form>
        </div>
    </div>

     <!-- Tables Grid -->
    <div class="table-grid" id="tableGrid">
            
    <table class="product-table">
    
    <tbody>
         <!-- แถวแรก Showdata-->
            <!-- แทรก PHP ดึงข้อมูลจากฐานข้อมูลมาแสดง -->
            <?php
                $select_table = $db->query("SELECT * FROM table_info"); // ดึงข้อมูลจากตาราง product ทั้งหมด ใช้ query ไม่ต้อง execute
                $select_table->execute(); // รันคำสั่ง SQL

                while($row = $select_table->fetch(PDO::FETCH_ASSOC)):?> 
                <!-- // วนลูปแสดงข้อมูลทีละแถวแต่อันนี้ใช้ fetch ไม่ใช่ fetchAll          -->
                <!-- while เสร็จก็เอามาแสดง -->

        <tr>
            <td><?php echo $row['table_number'] ?></td>
            <td><?php echo $row['capacity'] ?></td>
            <td data-status="<?php echo strtolower($row['status']); ?>"><?php echo ucfirst($row['status']) ?></td>
            <td>
                <a class="action-btn edit-btn" href="updateTableUFrom.php?table_id=<?php echo $row['table_id']; ?>">แก้ไข</a>
                <a  href="?table_id=<?php echo $row['table_id']; ?>" class="action-btn delete-btn">ลบ</a>
            </td>
        </tr>
        <?php endwhile; ?>

    </tbody>
    </table>
        </div>

        </div>
    </div>


    <footer class="footer">
        <p>&copy; 2025 Silver Restaurant. All rights reserved.</p>
    </footer>

   
</body>
</html>