<?php
require('DBConnect.php');

// ======================================================================
// ดึงข้อมูล Employee ทั้งหมด
// ======================================================================
// SELECT emp_id, empname, position = เลือกเฉพาะคอลัมน์ที่ต้องการ
// FROM employee = จากตาราง employee
$employees = $db->query("SELECT emp_id, empname, position FROM employee")->fetchAll(PDO::FETCH_ASSOC);

// ======================================================================
// ดึงข้อมูล Table ที่ว่าง (status = 'available')
// ======================================================================
// WHERE status = 'available' = เลือกเฉพาะโต๊ะที่ว่าง
$tables = $db->query("SELECT table_id, table_number, capacity FROM table_info WHERE status = 'available'")->fetchAll(PDO::FETCH_ASSOC);

// ======================================================================
// ดึงข้อมูล Menu ทั้งหมด
// ======================================================================
// SELECT menu_id, menu_name, category, menu_price = ดึงข้อมูลเมนูที่จำเป็น
$menus = $db->query("SELECT menu_id, menu_name, category, menu_price FROM menu")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Order - Silver Restaurant</title>
    <link rel="stylesheet" href="ref.css">
    <link rel="stylesheet" href="OrderUi.css">
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

    <div class="hero-section">
        <h2 class="hero-title">Create New Order</h2>
        <p class="hero-subtitle">Take customer orders efficiently</p>
    </div>

    <div class="order-container">
        <form action="OrderProcess.php" method="POST" id="orderForm">
            <div class="form-grid">
                <!-- Left Side: Order Information -->
                <div class="order-info-section">
                    <h3 class="section-title">📋 Order Information</h3>
                    
                    <div class="form-group">
                        <label for="employee_id">Employee / พนักงาน *</label>
                        <select id="employee_id" name="employee_id" required>
                            <option value="">-- Select Employee --</option>
                            <?php foreach($employees as $emp): ?>
                                <option value="<?php echo $emp['emp_id']; ?>">
                                    <?php echo $emp['empname']; ?> (<?php echo $emp['position']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="table_id">Table / โต๊ะ *</label>
                        <select id="table_id" name="table_id" required>
                            <option value="">-- Select Table --</option>
                            <?php foreach($tables as $table): ?>
                                <option value="<?php echo $table['table_id']; ?>">
                                    <?php echo $table['table_number']; ?> (Capacity: <?php echo $table['capacity']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="order_date">Order Date *</label>
                            <input type="date" id="order_date" name="order_date" 
                                   value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="order_time">Order Time *</label>
                            <input type="time" id="order_time" name="order_time" 
                                   value="<?php echo date('H:i'); ?>" required>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="order-summary">
                        <h4>Order Summary</h4>
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span id="subtotal">฿0.00</span>
                        </div>
                        <div class="summary-row total">
                            <span>Total Amount:</span>
                            <span id="total_amount">฿0.00</span>
                        </div>
                        <input type="hidden" name="total_amount" id="total_amount_input" value="0">
                    </div>
                </div>

                <!-- Right Side: Menu Selection -->
                <div class="menu-selection-section">
                    <h3 class="section-title">🍴 Menu Selection</h3>
                    
                    <div class="menu-list" id="menuList">
                        <?php foreach($menus as $menu): ?>
                            <div class="menu-item" data-menu-id="<?php echo $menu['menu_id']; ?>" 
                                 data-menu-name="<?php echo htmlspecialchars($menu['menu_name']); ?>"
                                 data-menu-price="<?php echo $menu['menu_price']; ?>">
                                <div class="menu-info">
                                    <h4><?php echo $menu['menu_name']; ?></h4>
                                    <span class="category"><?php echo $menu['category']; ?></span>
                                    <span class="price">฿<?php echo number_format($menu['menu_price'], 2); ?></span>
                                </div>
                                <div class="menu-actions">
                                    <button type="button" class="btn-minus" onclick="decreaseQuantity(<?php echo $menu['menu_id']; ?>)">-</button>
                                    <input type="number" class="quantity-input" id="qty_<?php echo $menu['menu_id']; ?>" 
                                           value="0" min="0" readonly>
                                    <button type="button" class="btn-plus" onclick="increaseQuantity(<?php echo $menu['menu_id']; ?>)">+</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Selected Items Display -->
            <div class="selected-items-section">
                <h3 class="section-title">🛒 Selected Items</h3>
                <div id="selectedItemsList" class="selected-list">
                    <p class="empty-message">No items selected yet</p>
                </div>
            </div>

            <!-- Hidden inputs for menu items will be added dynamically by JavaScript -->
            <div id="menuItemsInputs"></div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">✓ Create Order</button>
                <a href="index.php" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Silver Restaurant. All rights reserved.</p>
    </footer>

    <script>
        // เก็บข้อมูลรายการที่เลือก
        let selectedItems = {};

        function increaseQuantity(menuId) {
            const input = document.getElementById('qty_' + menuId);
            input.value = parseInt(input.value) + 1;
            updateSelectedItems(menuId, parseInt(input.value));
        }

        function decreaseQuantity(menuId) {
            const input = document.getElementById('qty_' + menuId);
            if (parseInt(input.value) > 0) {
                input.value = parseInt(input.value) - 1;
                updateSelectedItems(menuId, parseInt(input.value));
            }
        }

        function updateSelectedItems(menuId, quantity) {
            const menuItem = document.querySelector(`[data-menu-id="${menuId}"]`);
            const menuName = menuItem.dataset.menuName;
            const menuPrice = parseFloat(menuItem.dataset.menuPrice);

            if (quantity > 0) {
                selectedItems[menuId] = {
                    name: menuName,
                    price: menuPrice,
                    quantity: quantity
                };
            } else {
                delete selectedItems[menuId];
            }

            displaySelectedItems();
            calculateTotal();
        }

        function displaySelectedItems() {
            const container = document.getElementById('selectedItemsList');
            const inputsContainer = document.getElementById('menuItemsInputs');
            
            if (Object.keys(selectedItems).length === 0) {
                container.innerHTML = '<p class="empty-message">No items selected yet</p>';
                inputsContainer.innerHTML = '';
                return;
            }

            let html = '<table class="selected-table"><thead><tr><th>Menu</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead><tbody>';
            let inputsHtml = '';

            for (let menuId in selectedItems) {
                const item = selectedItems[menuId];
                const subtotal = item.price * item.quantity;
                html += `
                    <tr>
                        <td>${item.name}</td>
                        <td>฿${item.price.toFixed(2)}</td>
                        <td>${item.quantity}</td>
                        <td>฿${subtotal.toFixed(2)}</td>
                    </tr>
                `;
                
                // สร้าง hidden inputs สำหรับส่งข้อมูลไป backend
                inputsHtml += `
                    <input type="hidden" name="menu_id[]" value="${menuId}">
                    <input type="hidden" name="quantity[]" value="${item.quantity}">
                `;
            }

            html += '</tbody></table>';
            container.innerHTML = html;
            inputsContainer.innerHTML = inputsHtml;
        }

        function calculateTotal() {
            let total = 0;
            for (let menuId in selectedItems) {
                const item = selectedItems[menuId];
                total += item.price * item.quantity;
            }

            document.getElementById('subtotal').textContent = '฿' + total.toFixed(2);
            document.getElementById('total_amount').textContent = '฿' + total.toFixed(2);
            document.getElementById('total_amount_input').value = total.toFixed(2);
        }

        // Validate form before submit
        document.getElementById('orderForm').addEventListener('submit', function(e) {
            if (Object.keys(selectedItems).length === 0) {
                e.preventDefault();
                alert('Please select at least one menu item!');
                return false;
            }
        });
    </script>
</body>
</html>