<?php
// เชื่อมต่อฐานข้อมูล
require('DBConnect.php');

// รับค่า order_id จาก URL (?order_id=1)
// isset() = เช็คว่ามีตัวแปรนี้หรือไม่
// Ternary Operator: (เงื่อนไข) ? ถ้าจริง : ถ้าเท็จ
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : null;

// ถ้าไม่มี order_id ให้กลับไปหน้า Receipt
if (!$order_id) {
    header("Location: Receipt.php");
    exit();
}

// ======================================================================
// JOIN 3 ตาราง: Order -> Employee และ Order -> Table
// ======================================================================
// ดึงข้อมูล Order ตาม order_id ที่ระบุ
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

// Prepared Statement = ป้องกัน SQL Injection
$stmt = $db->prepare($query);
// bindParam() = ผูกค่าตัวแปรเข้ากับ placeholder (:order_id)
$stmt->bindParam(':order_id', $order_id);
$stmt->execute();
// fetch() = ดึงข้อมูล 1 แถว (เพราะ order_id เป็น unique)
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// ถ้าไม่เจอ Order ให้แสดงข้อความ error
if (!$order) {
    echo "Order not found!";
    exit();
}

// ======================================================================
// JOIN 3 ตาราง: Order -> OrderDetail -> Menu
// ======================================================================
// ดึงรายละเอียดเมนูที่สั่งในออร์เดอร์นี้
$queryDetails = "SELECT 
    m.menu_name,
    m.category,
    m.menu_price,
    od.quantity,
    (od.quantity * m.menu_price) AS subtotal
FROM order_detail od
INNER JOIN menu m ON od.menu_id = m.menu_id
WHERE od.order_id = :order_id";

$stmtDetails = $db->prepare($queryDetails);
$stmtDetails->bindParam(':order_id', $order_id);
$stmtDetails->execute();
// fetchAll() = ดึงข้อมูลทั้งหมด (เพราะ order detail อาจมีหลายรายการ)
$orderDetails = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #<?php echo $order['order_id']; ?> - Silver Restaurant</title>
    <link rel="stylesheet" href="ref.css">
    <style>
        .receipt-detail-container {
            max-width: 900px;
            margin: 3rem auto;
            padding: 0 2rem;
        }

        .receipt-paper {
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            position: relative;
        }

        .receipt-header {
            text-align: center;
            border-bottom: 3px dashed #a8a8a8;
            padding-bottom: 2rem;
            margin-bottom: 2rem;
        }

        .restaurant-name {
            font-size: 2.5rem;
            color: #2c3e50;
            margin: 0;
            font-weight: bold;
        }

        .restaurant-tagline {
            color: #7f8c8d;
            font-style: italic;
            margin: 0.5rem 0;
        }

        .receipt-number {
            background: linear-gradient(135deg, #a8a8a8 0%, #c0c0c0 100%);
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 25px;
            display: inline-block;
            margin-top: 1rem;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .receipt-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #f8f8f8;
            border-radius: 10px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 0.85rem;
            color: #7f8c8d;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 0.25rem;
        }

        .info-value {
            font-size: 1rem;
            color: #2c3e50;
            font-weight: 500;
        }

        .items-table {
            width: 100%;
            margin: 2rem 0;
            border-collapse: collapse;
        }

        .items-table thead {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
        }

        .items-table th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .items-table td {
            padding: 1rem;
            border-bottom: 1px solid #e0e0e0;
            color: #555;
        }

        .items-table tr:last-child td {
            border-bottom: none;
        }

        .items-table tbody tr:hover {
            background: #f8f8f8;
        }

        .category-badge {
            background: #e0e0e0;
            color: #555;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.8rem;
            display: inline-block;
            margin-left: 0.5rem;
        }

        .total-section {
            border-top: 3px dashed #a8a8a8;
            padding-top: 2rem;
            margin-top: 2rem;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            font-size: 1.1rem;
            color: #555;
        }

        .total-final {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 10px;
            margin-top: 1rem;
            font-size: 1.5rem;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
        }

        .receipt-footer {
            text-align: center;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 3px dashed #a8a8a8;
            color: #7f8c8d;
        }

        .thank-you {
            font-size: 1.5rem;
            color: #27ae60;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .action-buttons {
            margin-top: 3rem;
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
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .btn-print {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        .btn-print:hover {
            background: linear-gradient(135deg, #2980b9 0%, #21618c 100%);
            transform: translateY(-2px);
        }

        .btn-back {
            background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
            color: white;
        }

        .btn-back:hover {
            background: linear-gradient(135deg, #7f8c8d 0%, #626d71 100%);
            transform: translateY(-2px);
        }

        @media print {
            .navbar, .action-buttons, .footer {
                display: none;
            }
            .receipt-paper {
                box-shadow: none;
            }
        }

        @media (max-width: 768px) {
            .receipt-info-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-brand">
            <h1>🍽️ Restaurant MS</h1>
        </div>
        <ul class="nav-menu">
            <li><a href="index.php">🏠 Home</a></li>
            <li><a href="tableU.php">🪑 Table</a></li>
            <li><a href="OrderUi.php">🛒 Order</a></li>
            <li><a href="Receipt.php">🧾 Receipt</a></li>
        </ul>
    </nav>

    <div class="receipt-detail-container">
        <div class="receipt-paper">
            <div class="receipt-header">
                <h1 class="restaurant-name">🍽️ Silver Restaurant</h1>
                <p class="restaurant-tagline">Fine Dining Experience</p>
                <div class="receipt-number">Receipt #<?php echo $order['order_id']; ?></div>
            </div>

            <div class="receipt-info-grid">
                <div class="info-item">
                    <span class="info-label">Order Date</span>
                    <span class="info-value"><?php echo date('d M Y', strtotime($order['order_date'])); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Order Time</span>
                    <span class="info-value"><?php echo date('h:i A', strtotime($order['order_time'])); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Server</span>
                    <span class="info-value"><?php echo $order['empname']; ?> (<?php echo $order['position']; ?>)</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Table</span>
                    <span class="info-value"><?php echo $order['table_number']; ?> (<?php echo $order['capacity']; ?> seats)</span>
                </div>
            </div>

            <table class="items-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Price</th>
                        <th style="text-align: center;">Qty</th>
                        <th style="text-align: right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($orderDetails as $item): ?>
                    <!-- วนลูปแสดงรายการเมนูทีละรายการ -->
                    <tr>
                        <td>
                            <?php echo $item['menu_name']; ?>
                            <!-- แสดง category badge ข้างชื่อเมนู -->
                            <span class="category-badge"><?php echo $item['category']; ?></span>
                        </td>
                        <td>฿<?php echo number_format($item['menu_price'], 2); ?></td>
                        <td style="text-align: center;"><?php echo $item['quantity']; ?></td>
                        <td style="text-align: right; font-weight: 600;">฿<?php echo number_format($item['subtotal'], 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="total-section">
                <div class="total-row">
                    <span>Subtotal</span>
                    <span>฿<?php echo number_format($order['total_amount'], 2); ?></span>
                </div>
                <div class="total-row">
                    <span>Tax (7%)</span>
                    <!-- คำนวณภาษี 7% = ยอดรวม * 0.07 -->
                    <span>฿<?php echo number_format($order['total_amount'] * 0.07, 2); ?></span>
                </div>
                <div class="total-final">
                    <span>TOTAL</span>
                    <!-- ยอดรวมสุทธิ = ยอดรวม + ภาษี (ยอดรวม * 1.07) -->
                    <span>฿<?php echo number_format($order['total_amount'] * 1.07, 2); ?></span>
                </div>
            </div>

            <div class="receipt-footer">
                <p class="thank-you">Thank You!</p>
                <p>Please come again</p>
                <p style="font-size: 0.9rem; margin-top: 1rem;">📞 Tel: 02-xxx-xxxx | 📧 info@silverrestaurant.com</p>
            </div>
        </div>

        <div class="action-buttons">
            <!-- window.print() = เปิดหน้าต่าง Print ของ browser -->
            <button onclick="window.print()" class="btn btn-print">🖨️ Print Receipt</button>
            <a href="Receipt.php" class="btn btn-back">← Back to Receipts</a>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Silver Restaurant. All rights reserved.</p>
    </footer>
</body>
</html>
