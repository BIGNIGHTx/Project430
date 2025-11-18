<?php
// เชื่อมต่อฐานข้อมูล SQLite
require('DBConnect.php');

// ======================================================================
// JOIN 3 ตาราง: Order -> Employee และ Order -> Table
// ======================================================================
// คำอธิบาย: ดึงข้อมูลออร์เดอร์ทั้งหมดพร้อมชื่อพนักงานที่ทำการบริการและโต๊ะที่ใช้
// - INNER JOIN employee: เชื่อมตาราง orders กับ employee ผ่าน employee_id
// - INNER JOIN table_info: เชื่อมตาราง orders กับ table_info ผ่าน table_id
// - ORDER BY: เรียงลำดับจากวันที่และเวลาล่าสุดไปเก่าสุด (DESC = Descending)
$query = "SELECT 
    o.order_id,
    o.order_date,
    o.order_time,
    o.total_amount,
    e.empname,
    e.position,
    t.table_number,
    t.capacity
FROM orders o
INNER JOIN employee e ON o.employee_id = e.emp_id
INNER JOIN table_info t ON o.table_id = t.table_id
ORDER BY o.order_date DESC, o.order_time DESC";

// รันคำสั่ง SQL และเก็บผลลัพธ์ทั้งหมดใน array
$stmt = $db->query($query);
// FETCH_ASSOC = ได้ array แบบ key-value เช่น ['order_id' => 1, 'empname' => 'John']
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt Management - Silver Restaurant</title>
    <link rel="stylesheet" href="ref.css">
    <link rel="stylesheet" href="Receipt.css">
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
        <h2 class="hero-title">Receipt Management</h2>
        <p class="hero-subtitle">View all orders and receipts</p>
    </div>

    <div class="receipt-container">
        <div class="receipt-header">
            <h2 class="section-title">All Orders</h2>
            <!-- สถิติสรุป -->
            <div class="stats-summary">
                <div class="stat-item">
                    <!-- นับจำนวน Order ทั้งหมด -->
                    <span class="stat-number"><?php echo count($orders); ?></span>
                    <span class="stat-label">Total Orders</span>
                </div>
                <div class="stat-item">
                    <!-- รวมยอดขายทั้งหมด -->
                    <!-- array_column($orders, 'total_amount') = ดึงเฉพาะคอลัมน์ total_amount มาเป็น array -->
                    <!-- array_sum() = รวมค่าทั้งหมดใน array -->
                    <span class="stat-number">฿<?php echo number_format(array_sum(array_column($orders, 'total_amount')), 2); ?></span>
                    <span class="stat-label">Total Revenue</span>
                </div>
            </div>
        </div>

        <?php if (empty($orders)): ?>
            <!-- กรณียังไม่มี Order เลย -->
            <div class="empty-state">
                <div class="empty-icon">📋</div>
                <h3>No Orders Yet</h3>
                <p>Orders will appear here after they are created</p>
                <a href="OrderUi.php" class="btn-create-order">Create New Order</a>
            </div>
        <?php else: ?>
            <!-- แสดง Order Cards ในรูปแบบ Grid -->
            <div class="orders-grid">
                <?php foreach($orders as $order): ?>
                    <!-- foreach วนลูปแสดง Order ทีละรายการ -->
                    <div class="order-card">
                        <div class="order-card-header">
                            <div class="order-id">
                                <span class="label">Order ID</span>
                                <span class="value">#<?php echo $order['order_id']; ?></span>
                            </div>
                            <div class="order-amount">
                                <!-- number_format(ตัวเลข, ทศนิยม) = จัดรูปแบบตัวเลข เช่น 1234.50 -->
                                <span class="amount">฿<?php echo number_format($order['total_amount'], 2); ?></span>
                            </div>
                        </div>

                        <div class="order-card-body">
                            <div class="order-info-row">
                                <span class="icon">📅</span>
                                <!-- date() = แปลงวันที่เป็นรูปแบบที่อ่านง่าย เช่น 18 Nov 2025 -->
                                <!-- strtotime() = แปลง string เป็น timestamp -->
                                <span class="text"><?php echo date('d M Y', strtotime($order['order_date'])); ?></span>
                            </div>
                            <div class="order-info-row">
                                <span class="icon">🕐</span>
                                <!-- h:i A = ชั่วโมง:นาที AM/PM เช่น 02:30 PM -->
                                <span class="text"><?php echo date('h:i A', strtotime($order['order_time'])); ?></span>
                            </div>
                            <div class="order-info-row">
                                <span class="icon">👨‍🍳</span>
                                <span class="text"><?php echo $order['empname']; ?> (<?php echo $order['position']; ?>)</span>
                            </div>
                            <div class="order-info-row">
                                <span class="icon">🪑</span>
                                <span class="text"><?php echo $order['table_number']; ?> (<?php echo $order['capacity']; ?> seats)</span>
                            </div>
                        </div>

                        <div class="order-card-footer">
                            <!-- ส่ง order_id ไปหน้ารายละเอียด -->
                            <a href="ReceiptDetail.php?order_id=<?php echo $order['order_id']; ?>" class="btn-view-detail">
                                View Details
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Silver Restaurant. All rights reserved.</p>
    </footer>
</body>
</html>