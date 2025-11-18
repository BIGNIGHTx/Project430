<?php
require('DBConnect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // รับข้อมูลจากฟอร์ม
        $employee_id = $_POST['employee_id'];
        $table_id = $_POST['table_id'];
        $order_date = $_POST['order_date'];
        $order_time = $_POST['order_time'];
        $total_amount = $_POST['total_amount'];
        
        // รับข้อมูล Menu Items
        $menu_ids = $_POST['menu_id'];
        $quantities = $_POST['quantity'];

        // เริ่ม Transaction
        // Transaction = กลุ่มคำสั่ง SQL ที่ต้องสำเร็จทั้งหมดพร้อมกัน
        // ถ้ามีข้อผิดพลาดจะ Rollback (ยกเลิก) ทั้งหมด
        $db->beginTransaction();

        // ======================================================================
        // 1. บันทึกข้อมูล Order ลงตาราง orders
        // ======================================================================
        // INSERT INTO orders: เพิ่มข้อมูลเข้าตาราง orders
        // VALUES: ค่าที่จะบันทึก (ใช้ placeholder :ชื่อตัวแปร)
        // Prepared Statement ป้องกัน SQL Injection
        $insertOrder = $db->prepare("INSERT INTO orders (employee_id, table_id, order_date, order_time, total_amount) 
                                      VALUES (:employee_id, :table_id, :order_date, :order_time, :total_amount)");
        
        // bindParam: ผูกค่าจากตัวแปร PHP เข้ากับ placeholder
        $insertOrder->bindParam(':employee_id', $employee_id);
        $insertOrder->bindParam(':table_id', $table_id);
        $insertOrder->bindParam(':order_date', $order_date);
        $insertOrder->bindParam(':order_time', $order_time);
        $insertOrder->bindParam(':total_amount', $total_amount);
        
        $insertOrder->execute();
        
        // ดึง Order ID ที่เพิ่งสร้าง
        // lastInsertId() = ดึง ID ล่าสุดที่ถูก AUTO_INCREMENT
        $order_id = $db->lastInsertId();

        // ======================================================================
        // 2. บันทึกข้อมูล Order Details ลงตาราง order_detail
        // ======================================================================
        // INSERT INTO order_detail: เพิ่มรายละเอียดเมนูที่สั่ง
        // วนลูปเพิ่มทีละรายการ (เพราะอาจสั่งหลายเมนู)
        $insertDetail = $db->prepare("INSERT INTO order_detail (order_id, menu_id, quantity) 
                                       VALUES (:order_id, :menu_id, :quantity)");

        // for loop วนเพิ่มเมนูทีละรายการ
        for ($i = 0; $i < count($menu_ids); $i++) {
            $insertDetail->bindParam(':order_id', $order_id);
            $insertDetail->bindParam(':menu_id', $menu_ids[$i]);
            $insertDetail->bindParam(':quantity', $quantities[$i]);
            $insertDetail->execute();
        }

        // ======================================================================
        // 3. อัพเดทสถานะโต๊ะเป็น 'occupied' (ไม่ว่าง)
        // ======================================================================
        // UPDATE table_info: แก้ไขข้อมูลในตาราง table_info
        // SET status = 'occupied': เปลี่ยนสถานะเป็น occupied (มีคนใช้แล้ว)
        // WHERE table_id = :table_id: เฉพาะโต๊ะที่ถูกเลือก
        $updateTable = $db->prepare("UPDATE table_info SET status = 'occupied' WHERE table_id = :table_id");
        $updateTable->bindParam(':table_id', $table_id);
        $updateTable->execute();

        // Commit Transaction
        // commit() = ยืนยันการทำงานทั้งหมด ถ้าไม่มี error
        $db->commit();

        // Redirect ไปหน้า Order Success พร้อม Order ID
        header("Location: OrderSuccess.php?order_id=" . $order_id);
        exit();

    } catch (Exception $e) {
        // Rollback ถ้ามี Error
        $db->rollBack();
        echo "Error creating order: " . $e->getMessage();
    }
} else {
    header("Location: OrderUi.php");
    exit();
}
?>
