<?php
/// connect ฐานข้อมูล

$db = new PDO('sqlite:myDB.db');      // สร้างการเชื่อมต่อฐานข้อมูล SQLite ใช้ PDO และคำสั่งสร้าง sqlite ละชื่อ Databese
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    //set Attribute เขียนตามเลย


// if($db){                // ตรวจสอบว่าเชื่อมได้มั้ย
//     echo 'เชื่อมต่อฐานข้อมูลสำเร็จ' . '<br>';
// }else{
//     echo 'เชื่อมต่อฐานข้อมูลไม่สำเร็จ' . $e->getMessage();
// }

// Employee
// $db->exec("CREATE TABLE IF NOT EXISTS employee (
//     emp_id INTEGER PRIMARY KEY AUTOINCREMENT,
//     empname TEXT NOT NULL,
//     position TEXT NOT NULL
//     )");

// // สร้างตาราง Table
// $db->exec("CREATE TABLE IF NOT EXISTS table_info (
//     table_id INTEGER PRIMARY KEY AUTOINCREMENT,
//     table_number TEXT NOT NULL,
//     capacity INTEGER NOT NULL,
//     status TEXT NOT NULL
// )");

// // สร้างตาราง Menu
// $db->exec("CREATE TABLE IF NOT EXISTS menu (
//     menu_id INTEGER PRIMARY KEY AUTOINCREMENT,
//     menu_name TEXT NOT NULL,
//     category TEXT NOT NULL,
//     menu_price REAL NOT NULL
// )");

// // สร้างตาราง Order
// $db->exec("CREATE TABLE IF NOT EXISTS orders (
//     order_id INTEGER PRIMARY KEY AUTOINCREMENT,
//     employee_id INTEGER NOT NULL,
//     table_id INTEGER NOT NULL,
//     order_time TEXT NOT NULL,
//     order_date TEXT NOT NULL,
//     total_amount REAL NOT NULL,
//     FOREIGN KEY (employee_id) REFERENCES employee(employee_id),
//     FOREIGN KEY (table_id) REFERENCES table_info(table_id)
// )");

// // สร้างตาราง OrderDetail
// $db->exec("CREATE TABLE IF NOT EXISTS order_detail (
//     order_detail_id INTEGER PRIMARY KEY AUTOINCREMENT,
//     order_id INTEGER NOT NULL,
//     menu_id INTEGER NOT NULL,
//     quantity INTEGER NOT NULL,
//     FOREIGN KEY (order_id) REFERENCES orders(order_id),
//     FOREIGN KEY (menu_id) REFERENCES menu(menu_id)
// )");

// // สร้างตาราง Receipt
// $db->exec("CREATE TABLE IF NOT EXISTS receipt (
//     receipt_id INTEGER PRIMARY KEY AUTOINCREMENT,
//     order_id INTEGER NOT NULL,
//     receipt_time TEXT NOT NULL,
//     receipt_date TEXT NOT NULL,
//     FOREIGN KEY (order_id) REFERENCES orders(order_id)
// )");  

// $count = $db->query("SELECT COUNT(*) FROM employee")->fetchColumn();
// if ($count == 0) {
//     //insert ข้อมูลตัวอย่างเฉพาะครั้งแรก
//     $db->exec("INSERT INTO employee (empname, position) 
//     VALUES ('John Doe', 'Chef')");
// }

// // ลบข้อมูลเก่าใน table_info และเพิ่มข้อมูลใหม่
// // คำสั่งนี้จะทำงานทุกครั้งที่โหลดหน้า เพื่อให้ข้อมูลเป็นปัจจุบันเสมอ
// $db->exec("DELETE FROM table_info"); // ลบข้อมูลเก่าทั้งหมด

// // Insert ข้อมูลโต๊ะใหม่
// $db->exec("INSERT INTO table_info (table_number, capacity, status) VALUES 
//     ('Table 1', 4, 'available'),
//     ('Table 2', 2, 'occupied'),
//     ('Table 3', 6, 'reserved'),
//     ('Table 4', 4, 'available'),
//     ('Table 5', 8, 'reserved')");

// // ข้อมูลตัวอย่าง Menu
// $count = $db->query("SELECT COUNT(*) FROM menu")->fetchColumn();
// if ($count == 0) {
//     $db->exec("INSERT INTO menu (menu_name, category, menu_price) VALUES 
//         ('Pad Thai', 'Main Course', 120.00),
//         ('Tom Yum Goong', 'Soup', 150.00),
//         ('Green Curry', 'Main Course', 130.00),
//         ('Mango Sticky Rice', 'Dessert', 80.00),
//         ('Thai Iced Tea', 'Beverage', 50.00)");
// }

// // ข้อมูลตัวอย่าง Orders
// $count = $db->query("SELECT COUNT(*) FROM orders")->fetchColumn();
// if ($count == 0) {
//     $db->exec("INSERT INTO orders (employee_id, table_id, order_time, order_date, total_amount) VALUES 
//         (1, 1, '12:30:00', '2024-11-11', 350.00),
//         (2, 4, '13:15:00', '2024-11-11', 200.00)");
// }

// // ข้อมูลตัวอย่าง OrderDetail
// $count = $db->query("SELECT COUNT(*) FROM order_detail")->fetchColumn();
// if ($count == 0) {
//     $db->exec("INSERT INTO order_detail (order_id, menu_id, quantity) VALUES 
//         (1, 1, 2),
//         (1, 2, 1),
//         (1, 5, 2),
//         (2, 3, 1),
//         (2, 4, 1)");
// }

// // ข้อมูลตัวอย่าง Receipt
// $count = $db->query("SELECT COUNT(*) FROM receipt")->fetchColumn();
// if ($count == 0) {
//     $db->exec("INSERT INTO receipt (order_id, receipt_time, receipt_date) VALUES 
//         (1, '13:00:00', '2024-11-11'),
//         (2, '13:45:00', '2024-11-11')");
// }





-- ======================================================================
-- 1. JOIN 3 ตาราง: Order -> Employee และ Order -> Table
-- ======================================================================
-- แสดงข้อมูลออร์เดอร์พร้อมชื่อพนักงานที่ทำการบริการและโต๊ะที่ใช้
SELECT 
    o.Order_ID,
    o.Order_Date,
    o.Order_Time,
    o.Total_Amount,
    e.Employee_Name,
    e.Position,
    t.Table_Number,
    t.Capacity
FROM Order o
INNER JOIN Employee e ON o.Employee_ID = e.Employee_ID
INNER JOIN Table t ON o.Table_ID = t.Table_ID;

-- ======================================================================
-- 2. JOIN 3 ตาราง: Order -> OrderDetail -> Menu
-- ======================================================================
-- แสดงรายละเอียดเมนูที่สั่งในแต่ละออร์เดอร์
SELECT 
    o.Order_ID,
    o.Order_Date,
    o.Total_Amount,
    m.Menu_Name,
    m.Menu_Price,
    od.Quantity,
    (od.Quantity * m.Menu_Price) AS Subtotal
FROM Order o
INNER JOIN OrderDetail od ON o.Order_ID = od.Order_ID
INNER JOIN Menu m ON od.Menu_ID = m.Menu_ID;

-- ======================================================================
-- 3. JOIN 5 ตาราง: Order -> Employee, Table, OrderDetail -> Menu
-- ======================================================================
-- แสดงข้อมูลครบถ้วนของออร์เดอร์ (Order, Employee, Table, Menu)
SELECT 
    o.Order_ID,
    o.Order_Date,
    o.Order_Time,
    o.Total_Amount,
    e.Employee_Name,
    t.Table_Number,
    m.Menu_Name,
    od.Quantity,
    m.Menu_Price
FROM Order o
INNER JOIN Employee e ON o.Employee_ID = e.Employee_ID
INNER JOIN Table t ON o.Table_ID = t.Table_ID
INNER JOIN OrderDetail od ON o.Order_ID = od.Order_ID
INNER JOIN Menu m ON od.Menu_ID = m.Menu_ID;

-- ======================================================================
-- 4. JOIN 4 ตาราง: Recept -> Order -> Employee และ Table
-- ======================================================================
-- แสดงข้อมูลใบเสร็จพร้อมรายละเอียดออร์เดอร์
SELECT 
    r.Recept_ID,
    r.Recept_Date,
    r.Recept_Time,
    o.Order_ID,
    o.Total_Amount,
    e.Employee_Name,
    t.Table_Number
FROM Recept r
INNER JOIN Order o ON r.Order_ID = o.Order_ID
INNER JOIN Employee e ON o.Employee_ID = e.Employee_ID
INNER JOIN Table t ON o.Table_ID = t.Table_ID;

-- ======================================================================
-- 5. LEFT JOIN 2 ตาราง: Menu <- OrderDetail (แสดงเมนูทั้งหมด)
-- ======================================================================
-- สรุปยอดขายของแต่ละเมนู
SELECT 
    m.Menu_ID,
    m.Menu_Name,
    m.Menu_Price,
    SUM(od.Quantity) AS Total_Sold,
    SUM(od.Quantity * m.Menu_Price) AS Total_Revenue
FROM Menu m
LEFT JOIN OrderDetail od ON m.Menu_ID = od.Menu_ID
GROUP BY m.Menu_ID, m.Menu_Name, m.Menu_Price
ORDER BY Total_Revenue DESC;

-- ======================================================================
-- 6. LEFT JOIN 2 ตาราง: Employee <- Order (แสดงพนักงานทั้งหมด)
-- ======================================================================
-- สรุปยอดขายของแต่ละพนักงาน
SELECT 
    e.Employee_ID,
    e.Employee_Name,
    e.Position,
    COUNT(o.Order_ID) AS Total_Orders,
    SUM(o.Total_Amount) AS Total_Sales
FROM Employee e
LEFT JOIN Order o ON e.Employee_ID = o.Employee_ID
GROUP BY e.Employee_ID, e.Employee_Name, e.Position
ORDER BY Total_Sales DESC;

-- ======================================================================
-- 7. LEFT JOIN 2 ตาราง: Table <- Order (แสดงโต๊ะทั้งหมด)
-- ======================================================================
-- แสดงโต๊ะที่มีการใช้งานพร้อมจำนวนออร์เดอร์
SELECT 
    t.Table_ID,
    t.Table_Number,
    t.Capacity,
    t.Status,
    COUNT(o.Order_ID) AS Order_Count
FROM Table t
LEFT JOIN Order o ON t.Table_ID = o.Table_ID
GROUP BY t.Table_ID, t.Table_Number, t.Capacity, t.Status;

-- ======================================================================
-- 8. LEFT JOIN 2 ตาราง: Order <- Recept (รายงานสรุป)
-- ======================================================================
-- รายงานยอดขายรายวัน
SELECT 
    o.Order_Date,
    COUNT(DISTINCT o.Order_ID) AS Total_Orders,
    SUM(o.Total_Amount) AS Daily_Revenue,
    COUNT(DISTINCT r.Recept_ID) AS Total_Recepts
FROM Order o
LEFT JOIN Recept r ON o.Order_ID = r.Order_ID
GROUP BY o.Order_Date
ORDER BY o.Order_Date DESC;

// ?>