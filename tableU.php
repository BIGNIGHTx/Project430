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

        <!-- ช่องค้นหา Search Box -->
        <div class="search-box-container">
            <!-- 
                การทำงานของฟอร์มค้นหา:
                1. ผู้ใช้กรอกตัวเลขใน input ที่มี name="search_capacity"
                2. กดปุ่มค้นหา form จะส่งค่าแบบ GET ไปที่ tableU.php?search_capacity=ตัวเลข
                3. PHP รับค่าผ่าน $_GET['search_capacity']
                4. นำค่าที่รับมาไปค้นหาในฐานข้อมูล
            -->
            <form method="GET" action="tableU.php" class="search-form">
                <label class="search-label">🔍 ค้นหาจากจำนวนที่นั่ง:</label>
                
                <input type="number" name="search_capacity" placeholder="กรอกจำนวนที่นั่ง" min="1" 
                       value="<?php echo isset($_GET['search_capacity']) ? $_GET['search_capacity'] : ''; ?>"
                       class="search-input">

                <button type="submit" class="search-button">ค้นหา</button>

                <?php if(isset($_GET['search_capacity'])): ?>
                    <a href="tableU.php" class="clear-search-button">ล้างการค้นหา</a>
                <?php endif; ?>
            </form>
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
                // ======================================================================
                // การค้นหาข้อมูล:
                // 1. ตรวจสอบว่ามีการส่งค่า search_capacity มาหรือไม่
                // 2. ถ้ามี ให้ค้นหาเฉพาะโต๊ะที่มีจำนวนที่นั่งตรงกับที่ค้นหา
                // 3. ถ้าไม่มี ให้แสดงข้อมูลทั้งหมด
                // ======================================================================
                
                if(isset($_GET['search_capacity']) && !empty($_GET['search_capacity'])) {
                    // กรณีมีการค้นหา
                    $search_capacity = $_GET['search_capacity']; // รับค่าที่ส่งมาจากฟอร์ม
                    
                    // SQL: SELECT * FROM table_info WHERE capacity = ค่าที่ค้นหา
                    // คำอธิบาย: ดึงข้อมูลทั้งหมดจาก table_info ที่มี capacity ตรงกับที่ค้นหา
                    $select_table = $db->query("SELECT * FROM table_info WHERE capacity = '$search_capacity'");
                } else {
                    // กรณีไม่มีการค้นหา แสดงทั้งหมด
                    
                    // SQL: SELECT * FROM table_info
                    // คำอธิบาย: ดึงข้อมูลทั้งหมดจาก table_info
                    $select_table = $db->query("SELECT * FROM table_info");
                }
                
                $select_table->execute(); // รันคำสั่ง SQL

                // วนลูปแสดงข้อมูลทีละแถว
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
