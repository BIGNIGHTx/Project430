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







// ?>