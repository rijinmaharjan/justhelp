<?php
session_start(); // 1. Start session at the very top
include('config/dbcon.php'); // 2. Connect to database
include('config/function.php'); // 3. Include your redirect function

if (isset($_POST['add_to_cart'])) {

    // 4. Check if user is logged in
    // We check 'loggedInUser' because that is what your login script sets
    if (!isset($_SESSION['loggedInUser'])) {
        redirect('login.php', 'Please login to add items to cart');
        exit;
    }

    // 5. Capture data from form
    $userId = $_SESSION['loggedInUser']['user_id'];
    $productId = mysqli_real_escape_string($conn, $_POST['product_id']);
    $size = mysqli_real_escape_string($conn, $_POST['size']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);

    // 6. Check if product + size combo already exists in user's cart
    $checkCart = "SELECT * FROM cart WHERE user_id = '$userId' AND product_id = '$productId' AND size = '$size' LIMIT 1";
    $checkCartRun = mysqli_query($conn, $checkCart);

    if (mysqli_num_rows($checkCartRun) > 0) {
        // Item exists: Update quantity
        $updateQuery = "UPDATE cart SET quantity = quantity + $quantity 
                        WHERE user_id = '$userId' AND product_id = '$productId' AND size = '$size'";
        $queryRun = mysqli_query($conn, $updateQuery);
    } else {
        // New item: Insert into cart
        $insertQuery = "INSERT INTO cart (user_id, product_id, size, quantity) 
                        VALUES ('$userId', '$productId', '$size', '$quantity')";
        $queryRun = mysqli_query($conn, $insertQuery);
    }

    if ($queryRun) {
        redirect("cart.php?id=$productId", "Product added to cart successfully");
    } else {
        redirect("product-view.php?id=$productId", "Something went wrong. Please try again.");
    }

} else {
    header("Location: index.php");
    exit;
}
?>