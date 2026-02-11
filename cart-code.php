<?php
// Includes the session_start and database connection
require 'config/function.php';

if (isset($_POST['add_to_cart'])) {


    // 1. Check Authentication (Using the key from your login script)
    if (!isset($_SESSION['loggedInUser'])) {
        redirect('login.php', 'Please login to add products to cart');
    }

    // 2. Validate Inputs using your validate function
    $userId = $_SESSION['loggedInUser']['user_id'];
    $productId = validate($_POST['product_id']);
    $size = validate($_POST['size']);
    $quantity = validate($_POST['quantity']);

    // 3. Optional: Auto-create cart table if not exists
    $createTable = "CREATE TABLE IF NOT EXISTS cart (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        product_id INT NOT NULL,
        size VARCHAR(50) NOT NULL,
        quantity INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    mysqli_query($conn, $createTable);

    // 4. Check if item already exists in cart for this user
    $checkCart = "SELECT * FROM cart WHERE user_id='$userId' AND product_id='$productId' AND size='$size' LIMIT 1";
    $checkCartRes = mysqli_query($conn, $checkCart);

    if (mysqli_num_rows($checkCartRes) > 0) {
        // Update existing quantity
        $updateQuery = "UPDATE cart SET quantity = quantity + $quantity 
                        WHERE user_id='$userId' AND product_id='$productId' AND size='$size'";
        $result = mysqli_query($conn, $updateQuery);
    } else {
        // Insert new cart item
        $insertQuery = "INSERT INTO cart (user_id, product_id, size, quantity) 
                        VALUES ('$userId', '$productId', '$size', '$quantity')";
        $result = mysqli_query($conn, $insertQuery);
    }

    if ($result) {
        // Redirect back to the product page with success message
        redirect("product-detail.php?id=$productId", "Product added to cart successfully", 'success');
    } else {
        redirect("product-detail.php?id=$productId", "Something went wrong!");
    }

} else {
    header('Location: index.php');
    exit;
}