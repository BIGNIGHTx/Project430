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
    <style>
        /* Hover effects for restaurant cards */
        .restaurant-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }
        
        .restaurant-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }
        
        .restaurant-card img {
            transition: transform 0.3s ease;
        }
        
        .restaurant-card:hover img {
            transform: scale(1.1);
        }
    </style>
</head>


<body>
    <nav class="navbar">
        <div class="nav-brand">
            <h1>🍽️ Restaurant MS</h1>
        </div>
        <ul class="nav-menu">
            <li><a href="tableU.php">🪑 Table</a></li>
            
            <li><a href="OrderUi.php">🛒 Order</a></li>
            <li><a href="Receipt.php">🧾 Receipt</a></li>
        </ul>
    </nav>

    <!-- Hero Section with Restaurant Image -->
    <div class="hero-section" style="background: linear-gradient(135deg, #2c3e50 0%, #4a5f7f 100%); padding: 4rem 2rem; text-align: center;">
        <h2 class="hero-title" style="color: white; font-size: 3rem; margin-bottom: 1rem;">Welcome to Silver Restaurant</h2>
        <p class="hero-subtitle" style="color: #d3d3d3; font-size: 1.3rem; margin-bottom: 3rem;">Experience culinary excellence in an elegant atmosphere</p>
        
        <!-- Restaurant Images Gallery -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; max-width: 1400px; margin: 0 auto; padding: 2rem;">
            
            <!-- Image 1: Elegant Dining -->
            <div class="restaurant-card">
                <div style="width: 100%; height: 250px; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #f0f0f0;">
                    <img src="https://media.istockphoto.com/id/1151361779/photo/luxury-banquet-hall-in-hotel-dinner-table-for-special-guests.webp?a=1&b=1&s=612x612&w=0&k=20&c=asTYsinSREZiQCgiy8_MCy6H0DAy8oRVuKos13zU_uE=" 
                         style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div style="padding: 1.5rem; text-align: center;">
                    <h3 style="color: #333; margin-bottom: 0.5rem;">Elegant Dining</h3>
                    <p style="color: #666;">Experience fine dining in our sophisticated atmosphere</p>
                </div>
            </div>

            <!-- Image 2: Gourmet Cuisine -->
            <div class="restaurant-card">
                <div style="width: 100%; height: 250px; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #f0f0f0;">
                    <img src="https://images.unsplash.com/photo-1577219491135-ce391730fb2c?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8Y2hlZnxlbnwwfHwwfHx8MA%3D%3D" 
                         style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div style="padding: 1.5rem; text-align: center;">
                    <h3 style="color: #333; margin-bottom: 0.5rem;">Gourmet Cuisine</h3>
                    <p style="color: #666;">Crafted by world-class chefs with passion</p>
                </div>
            </div>

            <!-- Image 3: Premium Ingredients -->
            <div class="restaurant-card">
                <div style="width: 100%; height: 250px; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #f0f0f0;">
                    <img src="https://images.unsplash.com/photo-1750943079478-ae516c4133cd?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTF8fFByZW1pdW0lMjBJbmdyZWRpZW50c3xlbnwwfHwwfHx8MA%3D%3D" 
                         style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div style="padding: 1.5rem; text-align: center;">
                    <h3 style="color: #333; margin-bottom: 0.5rem;">Premium Ingredients</h3>
                    <p style="color: #666;">Only the finest ingredients in every dish</p>
                </div>
            </div>

            <!-- Image 4: Exceptional Service -->
            <div class="restaurant-card">
                <div style="width: 100%; height: 250px; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #f0f0f0;">
                    <img src="https://media.istockphoto.com/id/1364946137/photo/businessman-holding-and-showing-the-best-quality-assurance-with-golden-five-stars-for.webp?a=1&b=1&s=612x612&w=0&k=20&c=Y8XgALrxUSw-jWNHeTCUHnieAaPR1hqjv0XaBmA_j94=" 
                         style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div style="padding: 1.5rem; text-align: center;">
                    <h3 style="color: #333; margin-bottom: 0.5rem;">Exceptional Service</h3>
                    <p style="color: #666;">Dedicated to making your visit memorable</p>
                </div>
            </div>

        </div>

        <!-- Restaurant Features -->
        <div style="margin-top: 4rem; padding: 3rem; background: rgba(255,255,255,0.1); border-radius: 20px; backdrop-filter: blur(10px);">
            <h3 style="color: white; font-size: 2rem; margin-bottom: 2rem;">Why Choose Silver Restaurant?</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; text-align: left;">
                
                <div style="padding: 1.5rem; background: rgba(255,255,255,0.15); border-radius: 15px; border-left: 4px solid #c0c0c0;">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">🏆</div>
                    <h4 style="color: white; margin-bottom: 0.5rem;">Award-Winning</h4>
                    <p style="color: #d3d3d3;">Recognized for culinary excellence</p>
                </div>

                <div style="padding: 1.5rem; background: rgba(255,255,255,0.15); border-radius: 15px; border-left: 4px solid #c0c0c0;">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">🌟</div>
                    <h4 style="color: white; margin-bottom: 0.5rem;">5-Star Rated</h4>
                    <p style="color: #d3d3d3;">Exceptional customer satisfaction</p>
                </div>

                <div style="padding: 1.5rem; background: rgba(255,255,255,0.15); border-radius: 15px; border-left: 4px solid #c0c0c0;">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">🍷</div>
                    <h4 style="color: white; margin-bottom: 0.5rem;">Fine Wine Selection</h4>
                    <p style="color: #d3d3d3;">Curated wine list from around the world</p>
                </div>

                <div style="padding: 1.5rem; background: rgba(255,255,255,0.15); border-radius: 15px; border-left: 4px solid #c0c0c0;">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">🎵</div>
                    <h4 style="color: white; margin-bottom: 0.5rem;">Live Entertainment</h4>
                    <p style="color: #d3d3d3;">Enjoy live music while you dine</p>
                </div>

            </div>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Silver Restaurant. All rights reserved.</p>
    </footer>

</body>
</html>