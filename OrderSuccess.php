<?php
require('DBConnect.php');

// รับค่า order_id จาก URL (?order_id=1)
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : null;

if (!$order_id) {
    header("Location: OrderUi.php");
    exit();
}

// ======================================================================
// JOIN 3 ตาราง: Order -> Employee และ Order -> Table
// ======================================================================
// คำอธิบาย SQL:
// - SELECT: เลือกคอลัมน์ที่ต้องการแสดง
// - o.order_id: order_id จากตาราง orders (ใช้ alias o)
// - FROM orders o: จากตาราง orders และตั้งชื่อย่อเป็น o
// - INNER JOIN employee e: เชื่อมตาราง employee (ชื่อย่อ e)
//   ON o.employee_id = e.emp_id: เชื่อมด้วยเงื่อนไข employee_id ตรงกัน
// - INNER JOIN table_info t: เชื่อมตาราง table_info (ชื่อย่อ t)
//   ON o.table_id = t.table_id: เชื่อมด้วยเงื่อนไข table_id ตรงกัน
// - WHERE o.order_id = :order_id: กรองเฉพาะ order_id ที่ระบุ
//   (:order_id เป็น placeholder สำหรับ Prepared Statement)
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
WHERE o.order_id = :order_id";

// Prepared Statement: ป้องกัน SQL Injection
$stmt = $db->prepare($query);
// bindParam: ผูกค่า $order_id เข้ากับ :order_id
$stmt->bindParam(':order_id', $order_id);
$stmt->execute();
// fetch: ดึงข้อมูล 1 แถว (เพราะ order_id เป็น PRIMARY KEY ไม่ซ้ำกัน)
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// ======================================================================
// JOIN 3 ตาราง: Order -> OrderDetail -> Menu
// ======================================================================
// คำอธิบาย SQL:
// - FROM order_detail od: เริ่มจากตาราง order_detail (รายละเอียดสินค้าในออร์เดอร์)
// - INNER JOIN menu m: เชื่อมกับตาราง menu เพื่อดึงข้อมูลเมนู
//   ON od.menu_id = m.menu_id: เชื่อมด้วย menu_id
// - (od.quantity * m.menu_price) AS subtotal: คำนวณยอดรวมแต่ละรายการ
//   AS subtotal: ตั้งชื่อคอลัมน์ผลลัพธ์เป็น subtotal
// - WHERE od.order_id = :order_id: กรองเฉพาะรายการในออร์เดอร์นี้
$queryDetails = "SELECT 
    m.menu_name,
    m.menu_price,
    od.quantity,
    (od.quantity * m.menu_price) AS subtotal
FROM order_detail od
INNER JOIN menu m ON od.menu_id = m.menu_id
WHERE od.order_id = :order_id";

$stmtDetails = $db->prepare($queryDetails);
$stmtDetails->bindParam(':order_id', $order_id);
$stmtDetails->execute();
// fetchAll: ดึงข้อมูลทั้งหมด (เพราะ order detail อาจมีหลายรายการ)
$orderDetails = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success - Silver Restaurant</title>
    <link rel="stylesheet" href="ref.css">
    <style>
        .success-container {
            max-width: 800px;
            margin: 3rem auto;
            padding: 2rem;
            background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .success-icon {
            font-size: 5rem;
            color: #27ae60;
            animation: scaleIn 0.5s ease;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }

        .success-title {
            color: #2c3e50;
            font-size: 2rem;
            margin: 1rem 0;
        }

        .order-info-box {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            margin: 2rem 0;
            text-align: left;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #555;
        }

        .info-value {
            color: #2c3e50;
        }

        .order-items-table {
            width: 100%;
            margin-top: 1.5rem;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }

        .order-items-table th {
            background: linear-gradient(135deg, #a8a8a8 0%, #c0c0c0 100%);
            color: white;
            padding: 1rem;
            text-align: left;
        }

        .order-items-table td {
            padding: 1rem;
            border-bottom: 1px solid #e0e0e0;
            color: #555;
        }

        .order-items-table tr:last-child td {
            border-bottom: none;
        }

        .total-row {
            background: #f8f8f8;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .action-buttons {
            margin-top: 2rem;
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .btn {
            padding: 1rem 2rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #2980b9 0%, #21618c 100%);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
            color: white;
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #7f8c8d 0%, #626d71 100%);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-brand">
            <h1>🍽️ Silver Restaurant</h1>
        </div>
        <ul class="nav-menu">
            <li><a href="index.php">🏠 Home</a></li>
            <li><a href="tableU.php">🪑 Table</a></li>
            <li><a href="OrderUi.php">🛒 Order</a></li>
            <li><a href="receipt.php">🧾 Receipt</a></li>
        </ul>
    </nav>

    <div class="success-container">
        <div class="success-icon">✓</div>
        <h1 class="success-title">Order Created Successfully!</h1>
        <p style="color: #7f8c8d; font-size: 1.1rem;">Order #<?php echo $order_id; ?> has been placed</p>

        <div class="order-info-box">
            <h3 style="color: #2c3e50; margin-bottom: 1rem;">Order Information</h3>
            <div class="info-row">
                <span class="info-label">Order ID:</span>
                <span class="info-value">#<?php echo $order['order_id']; ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Date & Time:</span>
                <span class="info-value"><?php echo $order['order_date']; ?> at <?php echo $order['order_time']; ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Server:</span>
                <span class="info-value"><?php echo $order['empname']; ?> (<?php echo $order['position']; ?>)</span>
            </div>
            <div class="info-row">
                <span class="info-label">Table:</span>
                <span class="info-value"><?php echo $order['table_number']; ?> (Capacity: <?php echo $order['capacity']; ?>)</span>
            </div>
        </div>

        <div class="order-info-box">
            <h3 style="color: #2c3e50; margin-bottom: 1rem;">Order Items</h3>
            <table class="order-items-table">
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($orderDetails as $item): ?>
                    <tr>
                        <td><?php echo $item['menu_name']; ?></td>
                        <td>฿<?php echo number_format($item['menu_price'], 2); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>฿<?php echo number_format($item['subtotal'], 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr class="total-row">
                        <td colspan="3">Total Amount</td>
                        <td>฿<?php echo number_format($order['total_amount'], 2); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="action-buttons">
            <a href="OrderUi.php" class="btn btn-primary">Create Another Order</a>
            <a href="index.php" class="btn btn-secondary">Back to Home</a>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Silver Restaurant. All rights reserved.</p>
    </footer>
</body>
</html>
