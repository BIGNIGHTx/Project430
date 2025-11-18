<?php
require('DBConnect.php');  //เชื่อมต่อฐานข้อมูล
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Management System</title>
    <link rel="stylesheet" href="ref.css">
</head>


<body>
    <nav class="navbar">
        <div class="nav-brand">
            <h1>🍽️ Silver Restaurant</h1>
        </div>
        <ul class="nav-menu">
            <li><a href="employee.php">👨‍🍳 Employee</a></li>
            <li><a href="tableU.php">🪑 Table</a></li>
            
            <li><a href="OrderUi.php">🛒 Order</a></li>
            <li><a href="Receipt.php">🧾 Receipt</a></li>
        </ul>
    </nav>

    <div class="hero-section">
        <h2 class="hero-title">Our Exquisite Menu</h2>
        <p class="hero-subtitle">Discover culinary excellence</p>
    </div>

    <div class="search-container">
        <div class="search-box">
            <input type="text" id="searchInput" class="search-input" placeholder="Search for dishes, ingredients, or categories...">
            <button class="search-btn" onclick="searchMenu()">🔍 Search</button>
        </div>
    </div>

    <div class="menu-container">
        <div class="menu-header">
            <h2 class="section-title">Menu Management</h2>
            <button class="add-menu-btn" onclick="openAddMenuModal()">
                ➕ Add New Menu
            </button>
        </div>

        <div class="menu-categories">
            <button class="category-btn active" onclick="filterCategory('all')">All</button>
            <button class="category-btn" onclick="filterCategory('appetizers')">Appetizers</button>
            <button class="category-btn" onclick="filterCategory('main')">Main Course</button>
            <button class="category-btn" onclick="filterCategory('desserts')">Desserts</button>
            <button class="category-btn" onclick="filterCategory('beverages')">Beverages</button>
        </div>

        <div class="menu-grid" id="menuGrid">
        <div class="menu-grid" id="menuGrid">
            <!-- Menu items from database will be displayed here dynamically -->
            <!-- Example static items (will be replaced by database data) -->
            
            <div class="menu-card" data-category="main">
                <div class="menu-image">
                    <div class="placeholder-image">🍝</div>
                </div>
                <div class="menu-details">
                    <h3 class="menu-title">Pasta Carbonara</h3>
                    <p class="menu-description">Creamy Italian pasta with bacon and parmesan</p>
                    <div class="menu-footer">
                        <span class="menu-price">฿350</span>
                        <div class="menu-actions">
                            <button class="order-btn">Order</button>
                            <button class="edit-btn" onclick="editMenu(1)">✏️</button>
                            <button class="delete-btn" onclick="deleteMenu(1)">🗑️</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="menu-card" data-category="appetizers">
                <div class="menu-image">
                    <div class="placeholder-image">🥗</div>
                </div>
                <div class="menu-details">
                    <h3 class="menu-title">Caesar Salad</h3>
                    <p class="menu-description">Fresh romaine with Caesar dressing and croutons</p>
                    <div class="menu-footer">
                        <span class="menu-price">฿250</span>
                        <div class="menu-actions">
                            <button class="order-btn">Order</button>
                            <button class="edit-btn" onclick="editMenu(2)">✏️</button>
                            <button class="delete-btn" onclick="deleteMenu(2)">🗑️</button>
                        </div>
                    </div>
                </div>
            </div>

            
            </div>

            

           

            
        </div>
    </div>

    <!-- Add/Edit Menu Modal -->
    <div id="menuModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Add New Menu</h2>
                <span class="close-modal" onclick="closeModal()">&times;</span>
            </div>
            <form id="menuForm" class="menu-form">
                <input type="hidden" id="menuId" name="menu_id">
                
                <div class="form-group">
                    <label for="menuName">Menu Name *</label>
                    <input type="text" id="menuName" name="menu_name" class="form-input" placeholder="Enter menu name" required>
                </div>

                <div class="form-group">
                    <label for="menuDescription">Description *</label>
                    <textarea id="menuDescription" name="description" class="form-textarea" placeholder="Enter menu description" rows="3" required></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="menuPrice">Price (฿) *</label>
                        <input type="number" id="menuPrice" name="price" class="form-input" placeholder="0.00" step="0.01" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="menuCategory">Category *</label>
                        <select id="menuCategory" name="category" class="form-select" required>
                            <option value="">Select Category</option>
                            <option value="appetizers">Appetizers</option>
                            <option value="main">Main Course</option>
                            <option value="desserts">Desserts</option>
                            <option value="beverages">Beverages</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="menuEmoji">Emoji Icon</label>
                    <input type="text" id="menuEmoji" name="emoji" class="form-input" placeholder="🍽️" maxlength="2">
                    <small class="form-hint">Choose an emoji to represent this dish</small>
                </div>

                <div class="form-group">
                    <label for="menuImage">Image URL (Optional)</label>
                    <input type="text" id="menuImage" name="image_url" class="form-input" placeholder="https://example.com/image.jpg">
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" id="menuAvailable" name="available" checked>
                        <span>Available for order</span>
                    </label>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn-submit">
                        <span id="submitBtnText">Add Menu</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content modal-small">
            <div class="modal-header">
                <h2>Confirm Delete</h2>
                <span class="close-modal" onclick="closeDeleteModal()">&times;</span>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this menu item?</p>
                <p class="warning-text">This action cannot be undone.</p>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
                <button type="button" class="btn-delete" onclick="confirmDelete()">Delete</button>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Silver Restaurant. All rights reserved.</p>
    </footer>

    <script>
        // Global variables
        let currentDeleteId = null;

        // Open Add Menu Modal
        function openAddMenuModal() {
            document.getElementById('menuModal').style.display = 'flex';
            document.getElementById('modalTitle').textContent = 'Add New Menu';
            document.getElementById('submitBtnText').textContent = 'Add Menu';
            document.getElementById('menuForm').reset();
            document.getElementById('menuId').value = '';
        }

        // Edit Menu
        function editMenu(menuId) {
            // TODO: Fetch menu data from database using menuId
            document.getElementById('menuModal').style.display = 'flex';
            document.getElementById('modalTitle').textContent = 'Edit Menu';
            document.getElementById('submitBtnText').textContent = 'Update Menu';
            document.getElementById('menuId').value = menuId;
            
            // Example: Pre-fill form (replace with actual database data)
            // document.getElementById('menuName').value = 'Menu Name from DB';
            // document.getElementById('menuDescription').value = 'Description from DB';
            // etc...
        }

        // Close Modal
        function closeModal() {
            document.getElementById('menuModal').style.display = 'none';
            document.getElementById('menuForm').reset();
        }

        // Delete Menu
        function deleteMenu(menuId) {
            currentDeleteId = menuId;
            document.getElementById('deleteModal').style.display = 'flex';
        }

        // Close Delete Modal
        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
            currentDeleteId = null;
        }

        // Confirm Delete
        function confirmDelete() {
            if (currentDeleteId) {
                // TODO: Send delete request to database
                console.log('Deleting menu ID:', currentDeleteId);
                alert('Menu item deleted! (Connect to database to actually delete)');
                closeDeleteModal();
            }
        }

        // Submit Form
        document.getElementById('menuForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const menuId = document.getElementById('menuId').value;
            
            // TODO: Send data to database
            if (menuId) {
                console.log('Updating menu ID:', menuId);
                alert('Menu updated! (Connect to database to actually update)');
            } else {
                console.log('Adding new menu');
                alert('New menu added! (Connect to database to actually add)');
            }
            
            closeModal();
        });

        // Search Menu
        function searchMenu() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const menuCards = document.querySelectorAll('.menu-card');
            
            menuCards.forEach(card => {
                const title = card.querySelector('.menu-title').textContent.toLowerCase();
                const description = card.querySelector('.menu-description').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || description.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Filter by Category
        function filterCategory(category) {
            const menuCards = document.querySelectorAll('.menu-card');
            const categoryBtns = document.querySelectorAll('.category-btn');
            
            // Update active button
            categoryBtns.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            // Filter cards
            menuCards.forEach(card => {
                if (category === 'all' || card.dataset.category === category) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const menuModal = document.getElementById('menuModal');
            const deleteModal = document.getElementById('deleteModal');
            
            if (event.target == menuModal) {
                closeModal();
            }
            if (event.target == deleteModal) {
                closeDeleteModal();
            }
        }

        // Search on Enter key
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchMenu();
            }
        });
    </script>
</body>
</html>